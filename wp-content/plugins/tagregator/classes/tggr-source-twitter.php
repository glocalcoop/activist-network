<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRSourceTwitter' ) ) {
	/**
	 * Creates a custom post type and associated taxonomies
	 * Implements Twitter's "Application-only Authentication" described at https://dev.twitter.com/docs/auth/application-only-auth
	 * @package Tagregator
	 */
	class TGGRSourceTwitter extends TGGRMediaSource {
		protected static $readable_properties  = array( 'view_folder' );
		protected static $writeable_properties = array();
		protected $setting_names, $default_settings, $view_folder;

		const POST_TYPE_NAME_SINGULAR = 'Tweet';
		const POST_TYPE_NAME_PLURAL   = 'Tweets';
		const POST_TYPE_SLUG          = 'tggr-tweets';
		const SETTINGS_TITLE          = 'Twitter';
		const SETTINGS_PREFIX         = 'tggr_tweets_';
		const API_URL                 = 'https://api.twitter.com';	// It's important to use HTTPS for security


		/**
		 * Constructor
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->view_folder   = dirname( __DIR__ ) . '/views/'. str_replace( '.php', '', basename( __FILE__ ) );
			$this->setting_names = array( 'Consumer Key', 'Consumer Secret', 'Highlighted Accounts', '_bearer_token', '_newest_tweet_id' );

			foreach ( $this->setting_names as $key ) {
				$this->default_settings[ strtolower( str_replace( ' ', '_', $key ) ) ] = '';
			}
			$this->default_settings[ '_newest_tweet_id' ] = 0;

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

			add_filter( Tagregator::PREFIX . 'default_settings',      __CLASS__ . '::register_default_settings' );
			add_filter( 'update_option_'. TGGRSettings::SETTING_SLUG, __CLASS__ . '::obtain_bearer_token', 10, 2 );
			add_filter( 'the_content',                                __CLASS__ . '::convert_urls_to_links', 9 );    // before wp_texturize() to avoid malformed links. see https://core.trac.wordpress.org/ticket/17097#comment:1
			add_filter( 'the_content',                                __CLASS__ . '::link_hashtags_and_usernames' );
			add_filter( 'excerpt_length',                             __CLASS__ . '::get_excerpt_length' );
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
					case '_bearer_token':
						$new_settings[ $setting ] = strip_tags( $value );
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
		 * Obtains a bearer token from the API and saves it in the settings
		 * Can be called as callback for update_options_{$name}, or directly
		 * @mvc Controller
		 *
		 * @param string $old_settings
		 * @param string $new_settings
		 * @return string
		 */
		public static function obtain_bearer_token( $old_settings = null, $new_settings = null ) {
			$need_new_token = false;

			if ( null == $old_settings && null == $new_settings ) {
				$need_new_token = true;
			}

			if ( $old_settings[ __CLASS__ ]['consumer_key'] != $new_settings[ __CLASS__ ]['consumer_key'] || $old_settings[ __CLASS__ ]['consumer_secret'] != $new_settings[ __CLASS__ ]['consumer_secret'] ) {
				$need_new_token = true;
			}

			if ( $need_new_token ) {
				$credentials = self::get_bearer_credentials( $new_settings[ __CLASS__ ]['consumer_key'], $new_settings[ __CLASS__ ]['consumer_secret'] );
				$new_settings[ __CLASS__ ]['_bearer_token'] = self::get_bearer_token( $credentials );

				// Avoid infinite loop when updating option
				remove_filter( 'update_option_'. TGGRSettings::SETTING_SLUG, __CLASS__ . '::obtain_bearer_token', 10, 2 );
				update_option( TGGRSettings::SETTING_SLUG, $new_settings );
				add_filter( 'update_option_'. TGGRSettings::SETTING_SLUG, __CLASS__ . '::obtain_bearer_token', 10, 2 );
			}
		}

		/**
		 * Converts a consumer key and consumer secret into bearer credentials
		 * @mvc Model
		 *
		 * @param string $consumer_key
		 * @param string $consumer_secret
		 * @return string
		 */
		protected static function get_bearer_credentials( $consumer_key, $consumer_secret ) {
			$credentials = urlencode( $consumer_key ) . ':' . urlencode( $consumer_secret );

			return base64_encode( $credentials );
		}

		/**
		 * Obtains a bearer token from the API
		 * @mvc Model
		 *
		 * @param string $credentials
		 * @return mixed string|false
		 */
		protected static function get_bearer_token( $credentials ) {
			$response = wp_remote_post(
				self::API_URL . '/oauth2/token',
				array(
					'headers' => array(
						'Authorization' => 'Basic ' . $credentials,
						'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8'
					),
					'body' => 'grant_type=client_credentials'
				)
			);
			
			$token = json_decode( wp_remote_retrieve_body( $response ) );

			if ( isset( $token->token_type ) && 'bearer' == $token->token_type ) {
				$token = $token->access_token;
			} else {
				$token = false;
			}

			self::log( __METHOD__, 'Results', compact( 'credentials', 'response', 'token' ) );

			return $token;
		}

		/**
		 * Fetches new items from an external sources and saves them as posts in the local database
		 * @mvc Controller
		 *
		 * @param string $hashtag
		 */
		public function import_new_items( $hashtag ) {
			$tweets = self::get_new_hashtagged_tweets(
				TGGRSettings::get_instance()->settings[ __CLASS__ ]['_bearer_token'],
				$hashtag,
				TGGRSettings::get_instance()->settings[ __CLASS__ ]['_newest_tweet_id']
			);

			$this->import_new_posts( $this->convert_items_to_posts( $tweets, $hashtag ) );
			self::update_newest_tweet_id( $hashtag );
		}

		/**
		 * Retrieves tweets containing the given hashtag that were posted since the last import
		 * @mvc Model
		 *
		 * @param string $bearer_token
		 * @param string $hashtag
		 * @param int $since_id The ID of the most recent tweet that is already saved in the database
		 * @return mixed string|false
		 */
		protected static function get_new_hashtagged_tweets( $bearer_token, $hashtag, $since_id ) {
			$response = $tweets = false;

			if ( ! $bearer_token ) {
				self::obtain_bearer_token();
				$bearer_token = TGGRSettings::get_instance()->settings[ __CLASS__ ]['_bearer_token'];
			}

			// probably need to include since_last_id or whatever. add that to params/phpdoc

			// don't pull in more than ~20 at a time, but do it to that if there are 100 availble, you get 1-20, then 21-40, then 41-59, etc
			// api already defaults to 15
			// what if there are 30 new ones, does it give you the latest 15? probably, and that would skip the other 15
			// maybe need cron job to run every 5 minutes and pull them in, so they don't get missed when nobody is hitting the page
			// filter around those values

			if ( $bearer_token && $hashtag && is_numeric( $since_id ) ) {
				$url = self::API_URL . '/1.1/search/tweets.json?q=' . urlencode( $hashtag ) . '&since_id=' . urlencode( $since_id );

				$response = wp_remote_get(
					$url,
					array(
						'headers' => array(
							'Authorization' => 'Bearer ' . $bearer_token,
						),
					)
				);
				$body = json_decode( wp_remote_retrieve_body( $response ) );

				if ( isset( $body->statuses ) && ! empty( $body->statuses ) ) {
					$tweets = $body->statuses;
				}
			}

			self::log( __METHOD__, 'Results', compact( 'bearer_token', 'hashtag', 'since_id', 'response' ) );

			return $tweets;
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
					if ( isset( $item->retweeted_status ) && apply_filters( Tagregator::PREFIX . 'skip_retweets', true ) ) {
						continue;
					}

					$post_timestamp_gmt   = strtotime( $item->created_at );
					$post_timestamp_local = self::convert_gmt_timestamp_to_local( $post_timestamp_gmt );

					$post = array(
						'post_author'   => TGGRMediaSource::$post_author_id,
						'post_content'  => wp_kses( $item->text, wp_kses_allowed_html( 'data' ), array( 'http', 'https', 'mailto' ) ),
						'post_date'     => date( 'Y-m-d H:i:s', $post_timestamp_local ),
						'post_date_gmt' => date( 'Y-m-d H:i:s', $post_timestamp_gmt ),
						'post_status'   => 'publish',
						'post_title'    => self::get_title_from_content( $item->text ),
						'post_type'     => self::POST_TYPE_SLUG,
					);

					$post_meta = array(
						'source_id'        => sanitize_text_field( $item->id ),
						'author_name'      => sanitize_text_field( $item->user->name ),
						'author_username'  => sanitize_text_field( $item->user->screen_name ),
						'author_url'       => isset( $item->user->entities->url->urls[0]->expanded_url ) ? esc_url( $item->user->entities->url->urls[0]->expanded_url ) : '',
						'author_image_url' => esc_url( $item->user->profile_image_url ),
						'media'            => array(),
					);

					if ( isset ( $item->entities->media ) ) {
						foreach ( $item->entities->media as $media_item ) {
							if ( 'photo' == $media_item->type ) {
								$post_meta['media'][] = array(
									'id'   => sanitize_text_field( $media_item->id_str ),
									'url'  => esc_url_raw( $media_item->media_url ),
									'type' => 'image',
								);
							}
						}
					}

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
		 * Convert usernames and hashtags to links
		 * @mvc Model
		 * 
		 * @link http://snipplr.com/view.php?codeview&id=28482 Based on
		 * @link https://gist.github.com/georgestephanis/6567420 Based on
		 * @param string $text
		 * @return string
		 */
		public static function link_hashtags_and_usernames( $content ) {
			$post = get_post();

			if ( isset( $post->post_type ) && self::POST_TYPE_SLUG == $post->post_type ) {
				$content = preg_replace( "/@(\w+)/", "<a href=\"https://twitter.com/\\1\" class=\"". self::POST_TYPE_SLUG ."-username\">@\\1</a>", $content );
				$content = preg_replace( "/(?<!&)#(\w+)/", "<a href=\"https://twitter.com/search?q=\\1\" class=\"". self::POST_TYPE_SLUG ."-tag\">#\\1</a>", $content );
			}
			
			return $content;
		}

		/**
		 * Updates the _newest_tweet_id setting with the ID of the most recent
		 * @mvc Model
		 *
		 * @param string $hashtag
		 */
		protected static function update_newest_tweet_id( $hashtag ) {
			$latest_post = self::get_latest_hashtagged_post( self::POST_TYPE_SLUG, $hashtag );
	
			if ( isset( $latest_post->ID ) ) {
				$source_id = get_post_meta( $latest_post->ID, 'source_id', true );

				if ( $source_id ) {
					$settings = TGGRSettings::get_instance()->settings;
					$settings[ __CLASS__ ]['_newest_tweet_id'] = $source_id;
					TGGRSettings::get_instance()->settings = $settings;
				}
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
				'tweet_id'         => $postmeta['source_id'][0],
				'post_permalink'   => sprintf( 'https://twitter.com/%s/status/%s', $postmeta['author_username'][0], $postmeta['source_id'][0] ),
				'author_name'      => $postmeta['author_name'][0],
				'author_username'  => $postmeta['author_username'][0],
				'author_image_url' => $postmeta['author_image_url'][0],
				'media'            => isset( $postmeta['media'][0] ) ? maybe_unserialize( $postmeta['media'][0] ) : array(),
				'logo_url'         => plugins_url( 'images/source-logos/twitter.png', __DIR__ ),
				'css_classes'      => self::get_css_classes( $post->ID, $postmeta['author_username'][0] ),
				'show_excerpt'     => self::show_excerpt( $post ),
			);

			return $necessary_data;
		}
	} // end TGGRSourceTwitter
}
