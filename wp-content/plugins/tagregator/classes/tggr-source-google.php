<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRSourceGoogle' ) ) {
	/**
	 * Creates a custom post type and associated taxonomies
	 * @package Tagregator
	 */
	class TGGRSourceGoogle extends TGGRMediaSource {
		protected static $readable_properties  = array( 'view_folder' );
		protected static $writeable_properties = array();
		protected $setting_names, $default_settings, $view_folder;

		const POST_TYPE_NAME_SINGULAR = 'Google+ Activity';
		const POST_TYPE_NAME_PLURAL   = 'Google+ Activities';
		const POST_TYPE_SLUG          = 'tggr-google';
		const SETTINGS_TITLE          = 'Google+';
		const SETTINGS_PREFIX         = 'tggr_google_';
		const API_URL                 = 'https://www.googleapis.com/plus';	// It's important to use HTTPS for security

		/**
		 * Constructor
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->view_folder   = dirname( __DIR__ ) . '/views/'. str_replace( '.php', '', basename( __FILE__ ) );
			$this->setting_names = array( 'API Key', 'Highlighted Accounts' );

			foreach ( $this->setting_names as $key ) {
				$this->default_settings[ strtolower( str_replace( ' ', '_', $key ) ) ] = '';
			}
			$this->default_settings[ '_newest_activity_date' ] = 0;

			$this->register_hook_callbacks();
		}

		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {
			$this->init();
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 */
		public function deactivate() {}

		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action( 'init',                                       array( $this, 'init' ) );
			add_action( 'admin_init',                                 array( $this, 'register_settings' ) );
			add_filter( 'excerpt_length',                             __CLASS__ . '::get_excerpt_length' );

			add_filter( Tagregator::PREFIX . 'default_settings',      __CLASS__ . '::register_default_settings' );
		}

		/**
		 * Initializes variables
		 * @mvc Controller
		 */
		public function init() {
			self::register_post_type( 
				self::POST_TYPE_SLUG, 
				$this->get_post_type_params( 
					self::POST_TYPE_SLUG, 
					self::POST_TYPE_NAME_SINGULAR, 
					self::POST_TYPE_NAME_PLURAL 
				) 
			);
			self::create_post_author();   
			self::get_post_author_user_id();
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 * @mvc Model
		 *
		 * @param string $db_version
		 */
		public function upgrade( $db_version = 0 ) {}

		/**
		 * Validates submitted setting values before they get saved to the database. 
		 * Invalid data will be overwritten with defaults.
		 * @mvc Model
		 *
		 * @param array $new_settings
		 * @return array
		 */
		public function validate_settings( $new_settings ) {
			$new_settings = shortcode_atts( $this->default_settings, $new_settings, TGGRSettings::SETTING_SLUG );

			foreach ( $new_settings as $setting => $value ) {
				switch ( $setting ) {
					case '_newest_activity_date':
						$new_settings[ $setting ] = absint( $value );
						break;
					default:
						$new_settings[ $setting ] = $this->default_settings[ $setting ];
						if ( is_string( $value ) ) {
							$new_settings[ $setting ] = sanitize_text_field( $value );
						}
						break;
				}
			}

			return $new_settings;
		}

		/**
		 * Fetches new items from an external sources and saves them as posts in the local database
		 * @mvc Controller
		 *
		 * @param string $hashtag
		 */
		public function import_new_items( $hashtag ) {
			$activities = self::get_new_activities(
				TGGRSettings::get_instance()->settings[ __CLASS__ ]['api_key'],
				$hashtag,
				TGGRSettings::get_instance()->settings[ __CLASS__ ]['_newest_activity_date']
			);

			$this->import_new_posts( $this->convert_items_to_posts( $activities, $hashtag ) );
			self::update_newest_activity_date( $hashtag );
		}

		/**
		 * Retrieves activities containing the given hashtag that were posted since the last import
		 * @mvc Model
		 *
		 * @param string $api_key
		 * @param string $hashtag
		 * @param string $last_updated_activities The timestamp of the most recent item that is already saved in the database
		 * @return mixed string|false
		 */
		protected static function get_new_activities( $api_key, $hashtag, $last_updated_activities ) {
			$response = $activities = false;

			if ( $api_key && $hashtag ) {
				$url = sprintf(
					'%s/v1/activities?query=%s&key=%s',
					self::API_URL,
					urlencode( $hashtag ),
					urlencode( $api_key )
				);

				$response = wp_remote_get( $url );
				$body     = json_decode( wp_remote_retrieve_body( $response ) );

				if ( isset( $body->updated ) && strtotime( $body->updated ) > $last_updated_activities && ! empty( $body->items ) ) {
					$activities = $body->items;
				}
			}

			self::log( __METHOD__, 'Results', compact( 'api_key', 'hashtag', 'last_updated_activities', 'response' ) );

			return $activities;
		}

		/**
		 * Converts data from external source into a post/postmeta format so it can be saved in the local database
		 * @mvc Model
		 *
		 * @param array $items
		 * @param string $term
		 * @return array
		 */
		public function convert_items_to_posts( $items, $term ) {
			$posts = array();

			if ( $items ) {
				foreach ( $items as $item ) {
					$post_timestamp_gmt   = strtotime( $item->published );
					$post_timestamp_local = self::convert_gmt_timestamp_to_local( $post_timestamp_gmt );
					
					$post = array(
						'post_author'   => TGGRMediaSource::$post_author_id,
						'post_content'  => wp_kses( $item->object->content, wp_kses_allowed_html( 'data' ), array( 'http', 'https', 'mailto' ) ),
						'post_date'     => date( 'Y-m-d H:i:s', $post_timestamp_local ),
						'post_date_gmt' => date( 'Y-m-d H:i:s', $post_timestamp_gmt ),
						'post_status'   => 'publish',
						'post_title'    => sanitize_text_field( $item->title ),
						'post_type'     => self::POST_TYPE_SLUG,
					);

					$post_meta = array(
						'source_id'        => sanitize_text_field( $item->id ),
						'post_permalink'   => esc_url_raw( $item->url ),
						'author_id'        => sanitize_text_field( $item->actor->id ),
						'author_name'      => sanitize_text_field( $item->actor->displayName ),
						'author_url'       => esc_url( $item->actor->url ),
						'author_image_url' => esc_url( $item->actor->image->url ),
						'media'            => array(
							array(
								'small_url' => isset( $item->object->attachments[0]->image ) ? esc_url_raw( $item->object->attachments[0]->image->url ) : false,
								'large_url' => isset( $item->object->attachments[0]->fullImage ) ? esc_url_raw( $item->object->attachments[0]->fullImage->url ) : false,
								'type'      => 'image',
							),
						),
					);

					$posts[] = array(
						'post'       => $post,
						'post_meta'  => $post_meta,
						'term_name'  => $term,
					);
				}
			}

			return $posts;
		}

		/**
		 * Updates the _newest_activity_date setting with the timestamp of the most recent
		 * @mvc Model
		 *
		 * @param string $hashtag
		 */
		protected static function update_newest_activity_date( $hashtag ) {
			$latest_post = self::get_latest_hashtagged_post( self::POST_TYPE_SLUG, $hashtag );
			
			if ( isset( $latest_post->ID ) ) {
				$settings = TGGRSettings::get_instance()->settings;
				$settings[ __CLASS__ ]['_newest_activity_date'] = strtotime( $latest_post->post_date_gmt . ' GMT' );
				TGGRSettings::get_instance()->settings = $settings;
			}
		}

		/**
		 * Gathers the data that the media-item view will need
		 * @mvc Model
		 *
		 * @param WP_Post $post
		 *
		 * @return array
		 */
		public function get_item_view_data( $post ) {
			$postmeta = get_post_custom( $post->ID );
			$necessary_data = array(
				'source_id'        => $postmeta['source_id'][0],
				'post_permalink'   => $postmeta['post_permalink'][0],
				'author_name'      => $postmeta['author_name'][0],
				'author_url'       => $postmeta['author_url'][0],
				'author_image_url' => $postmeta['author_image_url'][0],
				'media'            => isset( $postmeta['media'][0] ) ? maybe_unserialize( $postmeta['media'][0] ) : array(),
				'logo_url'         => plugins_url( 'images/source-logos/google-plus.png', __DIR__ ),
				'css_classes'      => self::get_css_classes( $post->ID, $postmeta['author_name'][0] ),
				'show_excerpt'     => self::show_excerpt( $post ),
			);

			return $necessary_data;
		}
	} // end TGGRSourceGoogle
}
