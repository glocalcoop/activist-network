<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRSourceFlickr' ) ) {
	/**
	 * Creates a custom post type and associated taxonomies
	 * @package Tagregator
	 */
	class TGGRSourceFlickr extends TGGRMediaSource {
		protected static $readable_properties  = array( 'view_folder' );
		protected static $writeable_properties = array();
		protected $setting_names, $default_settings, $view_folder;

		const POST_TYPE_NAME_SINGULAR = 'Flickr Post';
		const POST_TYPE_NAME_PLURAL   = 'Flickr Posts';
		const POST_TYPE_SLUG          = 'tggr-flickr';
		const SETTINGS_TITLE          = 'Flickr';
		const SETTINGS_PREFIX         = 'tggr_flickr_';
		const API_URL                 = 'https://secure.flickr.com/services/rest/';	// It's important to use HTTPS for security


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
			$this->default_settings[ '_newest_media_date' ] = 0;
			
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
			add_action( 'init',                     array( $this, 'init' ) );
			add_action( 'admin_init',               array( $this, 'register_settings' ) );
			add_filter( 'excerpt_length',           __CLASS__ . '::get_excerpt_length' );

			add_filter( Tagregator::PREFIX . 'default_settings', __CLASS__ . '::register_default_settings' );
		}

		/**
		 * Initializes variables
		 * @mvc Controller
		 */
		public function init() {
			self::register_post_type( self::POST_TYPE_SLUG, $this->get_post_type_params( self::POST_TYPE_SLUG, self::POST_TYPE_NAME_SINGULAR, self::POST_TYPE_NAME_PLURAL ) );
			self::create_post_author();   // It should already exist from the first time this class was instantiated, but we need to make sure it still exists now
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
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 * @mvc Model
		 *
		 * @param array $new_settings
		 * @return array
		 */
		public function validate_settings( $new_settings ) {
			$new_settings = shortcode_atts( $this->default_settings, $new_settings, TGGRSettings::SETTING_SLUG );

			foreach ( $new_settings as $setting => $value ) {
				switch( $setting ) {
					case '_newest_media_date':
						$new_settings[ $setting ] = absint( $value );
					break;
				
					default:
						if ( is_string( $value ) ) {
							$new_settings[ $setting ] = sanitize_text_field( $value );
						} else {
							$new_settings[ $setting ] = $this->default_settings[ $setting ];
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
			$media = self::get_new_media(
				TGGRSettings::get_instance()->settings[ __CLASS__ ]['api_key'],
				$hashtag,
				TGGRSettings::get_instance()->settings[ __CLASS__ ]['_newest_media_date']
			);

			$this->import_new_posts( $this->convert_items_to_posts( $media, $hashtag ) );
			self::update_newest_media_date( $hashtag );
		}

		/**
		 * Retrieves media containing the given hashtag that were posted since the last import
		 * @mvc Model
		 *
		 * @param string $api_key
		 * @param string $hashtag
		 * @param string $min_upload_date The upload date of the most recent item that is already saved in the database
		 * @return mixed string|false
		 */
		protected static function get_new_media( $api_key, $hashtag, $min_upload_date ) {
			$response = $media = false;

			if ( $api_key && $hashtag ) {
				$url = sprintf(
					'%s?method=flickr.photos.search&tags=%s&min_upload_date=%d&extras=date_upload,description,owner_name,url_n,url_l,icon_farm,icon_server&format=json&nojsoncallback=1&api_key=%s',
					self::API_URL,
					urlencode( str_replace( '#', '', $hashtag ) ),
					urlencode( $min_upload_date ),
					urlencode( $api_key )
				);

				$response = wp_remote_get( $url );
				$body     = json_decode( wp_remote_retrieve_body( $response ) );
				
				if ( isset( $body->stat ) && 'ok' == $body->stat ) {
					$media = $body->photos->photo;
				}
			}

			self::log( __METHOD__, 'Results', compact( 'api_key', 'hashtag', 'min_upload_date', 'response' ) );

			return $media;
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
					$post_timestamp_gmt   = absint( $item->dateupload );
					$post_timestamp_local = self::convert_gmt_timestamp_to_local( $post_timestamp_gmt );
					
					$post = array(
						'post_author'   => TGGRMediaSource::$post_author_id,
						'post_content'  => wp_kses( $item->description->_content, wp_kses_allowed_html( 'data' ), array( 'http', 'https', 'mailto' ) ),
						'post_date'     => date( 'Y-m-d H:i:s', $post_timestamp_local ),
						'post_date_gmt' => date( 'Y-m-d H:i:s', $post_timestamp_gmt ),
						'post_status'   => 'publish',
						'post_title'    => sanitize_text_field( $item->title ),
						'post_type'     => self::POST_TYPE_SLUG,
					);

					$post_meta = array(
						'source_id'        => sanitize_text_field( $item->id ),
						'author_id'        => sanitize_text_field( $item->owner ),
						//'author_name'      => sanitize_text_field( ),	// would like to get full name here, but flickr.photos.search endpoint doesn't provide it, so would have to do an extra request for each item, which would not be performant
						'author_username'  => sanitize_text_field( $item->ownername ),
						'icon_farm'        => absint( $item->iconfarm ),
						'icon_server'      => absint( $item->iconserver ),
						'media'            => array(
							array(
								'small_url' => isset( $item->url_n ) ? esc_url_raw( $item->url_n ) : false,
								'large_url' => isset( $item->url_l ) ? esc_url_raw( $item->url_l ) : false,
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
		 * Updates the _newest_media_date setting with the ID of the most recent
		 * @mvc Model
		 *
		 * @param string $hashtag
		 */
		protected static function update_newest_media_date( $hashtag ) {
			$latest_post = self::get_latest_hashtagged_post( self::POST_TYPE_SLUG, $hashtag );
			
			if ( isset( $latest_post->ID ) ) {
				$settings = TGGRSettings::get_instance()->settings;
				$settings[ __CLASS__ ]['_newest_media_date'] = strtotime( $latest_post->post_date_gmt . ' GMT' );
				TGGRSettings::get_instance()->settings = $settings;
			}
		}

		/**
		 * Gathers the data that the media-item view will need
		 * @mvc Model
		 *
		 * @param WP_Post $post_id
		 *
		 * @return array
		 */
		public function get_item_view_data( $post ) {
			$postmeta = get_post_custom( $post->ID );
			$necessary_data = array(
				'media_permalink'    => sprintf( 'http://www.flickr.com/photos/%s/%s', $postmeta['author_id'][0], $postmeta['source_id'][0] ),
				'author_username'    => $postmeta['author_username'][0],
				'author_profile_url' => sprintf( 'http://www.flickr.com/people/%s', $postmeta['author_id'][0] ),
				'author_image_url'   => $postmeta['icon_server'][0] > 0 ? sprintf( 'http://farm%d.staticflickr.com/%d/buddyicons/%s.jpg', $postmeta['icon_farm'][0], $postmeta['icon_server'][0], $postmeta['author_id'][0] ) : 'http://www.flickr.com/images/buddyicon.gif',
				'media'              => isset( $postmeta['media'][0] ) ? maybe_unserialize( $postmeta['media'][0] ) : array(),
				'logo_url'           => plugins_url( 'images/source-logos/flickr.png', __DIR__ ),
				'css_classes'        => self::get_css_classes( $post->ID, $postmeta['author_username'][0] ),
				'show_excerpt'       => self::show_excerpt( $post ),
			);

			return $necessary_data;
		}
	} // end TGGRSourceFlickr
}
