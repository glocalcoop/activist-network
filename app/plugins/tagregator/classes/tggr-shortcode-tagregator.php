<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRShortcodeTagregator' ) ) {
	/**
	 * Handles the [tagregator] shortcode
	 *
	 * @package Tagregator
	 */
	class TGGRShortcodeTagregator extends TGGRModule {
		protected $refresh_interval, $post_types_to_class_names, $view_folder;		// $refresh_interval is in seconds
		protected static $readable_properties  = array( 'refresh_interval', 'view_folder' );
		protected static $writeable_properties = array( 'refresh_interval' );
		
		const SHORTCODE_NAME = 'tagregator';

		/**
		 * Constructor
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
			$this->view_folder = dirname( __DIR__ ) . '/views/'. str_replace( '.php', '', basename( __FILE__ ) );
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
			add_action( 'init',                                                              array( $this, 'init' ) );
			add_action( 'save_post',                                                         array( $this, 'prefetch_media_items' ), 10, 2 );
			add_action( 'wp_ajax_'.        Tagregator::PREFIX . 'render_latest_media_items', array( $this, 'render_latest_media_items' ) );
			add_action( 'wp_ajax_nopriv_'. Tagregator::PREFIX . 'render_latest_media_items', array( $this, 'render_latest_media_items' ) );
			add_filter( 'body_class',                                                        array( $this, 'add_body_classes' ) );

			add_shortcode( self::SHORTCODE_NAME,                                             array( $this, 'shortcode_tagregator' ) );
		}

		/**
		 * Initializes variables
		 * @mvc Controller
		 */
		public function init() {
			foreach ( Tagregator::get_instance()->media_sources as $class_name => $object ) {
				$this->post_types_to_class_names[ $object::POST_TYPE_SLUG ] = $class_name;
			}

			$this->refresh_interval = apply_filters( Tagregator::PREFIX . 'refresh_interval', 30 );
		}

		/**
		 * Checks if the plugin was recently updated and upgrades if necessary
		 * @mvc Controller
		 *
		 * @param string $db_version
		 */
		public function upgrade( $db_version = 0 ) {}

		/**
		 * Add a class to body if this page has the tagregator shortcode.
		 *
		 * @param array $classes
		 * @return array
		 */
		public function add_body_classes( $classes ) {
			if ( self::current_page_has_shortcode( self::SHORTCODE_NAME ) ) {
				$classes[] = self::SHORTCODE_NAME;
			}

			return $classes;
		}

		/**
		 * Check if the current page has a given shortcode.
		 *
		 * @param string $shortcode
		 * @return boolean
		 */
		protected static function current_page_has_shortcode( $shortcode ) {
			global $post;
			$has_shortcode = false;

			if ( is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode ) ) {
				$has_shortcode = true;
			}

			return $has_shortcode;
		}

		/**
		 * Controller for the [tagregator] shortcode
		 * @mvc Controller
		 *
		 * @return string
		 */
		public function shortcode_tagregator( $attributes ) {
			$attributes = shortcode_atts( array(
				'hashtag' => '',
				'layout'  => 'three-column',
			), $attributes );
			$items = array();

			if ( $attributes['hashtag'] ) {
				$items = self::get_media_items( $attributes['hashtag'] );
			}

			if ( ! in_array( $attributes['layout'], array( 'one-column', 'two-column', 'three-column' ) ) ) {
				$attributes['layout'] = 'three-column';
			}

			ob_start();
			require_once( $this->view_folder . '/shortcode-tagregator.php' );
			return apply_filters( Tagregator::PREFIX . 'shortcode_output', ob_get_clean() );
		}

		/**
		 * Gets all of the items from all of the media sources that are assigned to the given hashtags
		 * @mvc Model
		 *
		 * @param string $hashtags Comma-separated list of hashtags
		 * @return array
		 */
		protected function get_media_items( $hashtags ) {
			$items = $post_types = $terms = array();
			$hashtags = explode( ',', $hashtags );

			foreach ( Tagregator::get_instance()->media_sources as $source ) {
				$post_types[] = $source::POST_TYPE_SLUG;
			}

			foreach ( $hashtags as $hashtag ) {
				$term = get_term_by( 'name', trim( $hashtag ), TGGRMediaSource::TAXONOMY_HASHTAG_SLUG );

				if ( $term ) {
					$terms[] = $term;
				}
			}

			if ( $terms ) {
				$params = array(
					'posts_per_page' => apply_filters( Tagregator::PREFIX . 'media_items_per_page', 30 ),
					'post_type'      => $post_types,
					'tax_query'      => array(
						'relation'   => 'OR',
					),
				);

				foreach ( $terms as $term ) {
					$params['tax_query'][] = array(
						'taxonomy' => TGGRMediaSource::TAXONOMY_HASHTAG_SLUG,
						'field'    => 'slug',
						'terms'    => $term->slug,
					);
				}

				$items = get_posts( $params );
			}

			return $items;
		}

		/**
		 * Returns HTML markup for the latest media items
		 * This is an AJAX handler
		 * @mvc Controller
		 */
		public function render_latest_media_items() {
			$hashtag = sanitize_text_field( $_REQUEST['hashtag'] );
			$existing_item_ids = isset( $_REQUEST['existingItemIDs'] ) ? (array) $_REQUEST['existingItemIDs'] : array();
			array_walk( $existing_item_ids, 'absint' );

			if ( empty( $hashtag ) ) {
				wp_send_json_error( array( 'error' => 'Invalid hashtag') );
			}

			$this->import_new_items( $hashtag );
			$items = $this->get_media_items( $hashtag );
			$new_items_markup = $this->get_new_items_markup( $items, $existing_item_ids );
			$new_items_markup = str_replace( array( "\n", "\t" ), '', $new_items_markup );  // jQuery.prependTo() complains if a string of HTML doesn't start with a `<`

			wp_send_json_success( $new_items_markup ? $new_items_markup : 0 );
		}

		/**
		 * Imports the latest items from media sources
		 *
		 * @mvc Controller
		 *
		 * The semaphore is used to prevent importing the same post twice in a parallel request. The key is
		 * based on the `site_url()` in order to avoid blocking requests to other sites in the same multisite network,
		 * or other single-site installations on the same server. We could include the hashtag in the key as
		 * well in order to allow parallel requests for different hashtags, but that would require handling the case
		 * where multiple hashtags are used in one or both requests, which would complicate things without adding
		 * much benefit.
		 * 
		 * @param string $hashtags Comma-separated list of hashtags
		 * @param string $rate_limit 'respect' to enforce the rate limit, or 'ignore' to ignore it
		 */
		protected function import_new_items( $hashtags, $rate_limit = 'respect' ) {
			$hashtags      = explode( ',', $hashtags );
			$semaphore_key = (int) base_convert( substr( md5( __METHOD__ . site_url() ), 0, 8 ), 16, 10 );
			$semaphore_id  = function_exists( 'sem_get' ) ? sem_get( $semaphore_key ) : false;

			if ( $semaphore_id ) {
				sem_acquire( $semaphore_id );
			}

			$last_fetch = get_transient( Tagregator::PREFIX . 'last_media_fetch', 0 );

			if ( 'ignore' == $rate_limit || self::refresh_interval_elapsed( $last_fetch, $this->refresh_interval ) ) {
				set_transient( Tagregator::PREFIX . 'last_media_fetch', microtime( true ) );	// do this right away to minimize the chance of race conditions on systems that don't support the Semaphore module
				
				foreach ( Tagregator::get_instance()->media_sources as $source ) {
					foreach( $hashtags as $hashtag ) {
						$source->import_new_items( trim( $hashtag ) );
					}
				}
			}

			if ( $semaphore_id ) {
				sem_release( $semaphore_id );
			}
		}

		/**
		 * Determines if the enough time has passed since the previous media fetch
		 *
		 * @param int $last_fetch The number of seconds between the Unix epoch and the last time the data was fetched, as a float (i.e., the recorded output of microtime( true ) during the last fetch).
		 * @param int $refresh_interval The minimum number of seconds that should elapse between refreshes
		 * @return bool
		 */
		protected static function refresh_interval_elapsed( $last_fetch, $refresh_interval ) {
			$current_time = microtime( true );
			$elapsed_time = $current_time - $last_fetch;

			return $elapsed_time > $refresh_interval;
		}

		/**
		 * Builds the markup for an array of items if they don't already exist in the given array
		 *
		 * @param array $items
		 * @param array $existing_item_ids
		 * @return string
		 */
		protected function get_new_items_markup( $items, $existing_item_ids ) {
			ob_start();
			$this->render_media_items( $items, $existing_item_ids );
			$items_markup = ob_get_clean();

			return $items_markup ? $items_markup : '';
		}

		/**
		 * Builds the markup for media items, optionally skipping some items
		 *
		 * @param array $items
		 * @param array $excluded_item_ids
		 */
		protected function render_media_items( $items, $excluded_item_ids = array() ) {
			foreach ( $items as $item ) {
				if ( ! in_array( $item->ID, $excluded_item_ids ) ) {
					$GLOBALS['post'] = $item;
					setup_postdata( $GLOBALS['post'] );

					// Populate variables specific to this media type.
					$post_type = get_post_type();
					$class_name = $this->post_types_to_class_names[ $post_type ];
					extract( $class_name::get_instance()->get_item_view_data( $item ) );

					require( self::get_view_folder_from_post_type( $post_type ) . '/shortcode-tagregator-media-item.php' );
				}
			}

			wp_reset_postdata();
		}

		/**
		 * Determines the path to a media source's view folder based on the post type
		 *
		 * @param string $post_type
		 * @return string
		 */
		protected function get_view_folder_from_post_type( $post_type ) {
			$class_name = $this->post_types_to_class_names[ $post_type ];
			return $class_name::get_instance()->view_folder;
		}
		
		/**
		 * Fetches media items for a given hashtag when a post is saved, so that they'll be available immediately when the shortcode is displayed for the first time
		 * Note that this works, even though it often appears to do nothing. The problem is that Twitter's search API often returns no results,
		 * even when matching tweets exist. See https://dev.twitter.com/docs/faq#8650 more for details.
		 * 
		 * @Controller
		 * 
		 * @param int $post_id
		 * @param WP_Post $post
		 */
		public function prefetch_media_items( $post_id, $post ) {
			$ignored_actions = array( 'trash', 'untrash', 'restore' );
			
			if ( 1 !== did_action( 'save_post' ) ) {
				return;
			}

			if ( isset( $_GET['action'] ) && in_array( $_GET['action'], $ignored_actions ) ) {
				return;
			}
			
			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! $post || $post->post_status == 'auto-draft' ) {
				return;
			}
			
			preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $shortcodes, PREG_SET_ORDER );
			
			foreach ( $shortcodes as $shortcode ) {
				if ( self::SHORTCODE_NAME == $shortcode[2] ) {
					$attributes = shortcode_parse_atts( $shortcode[3] );
					
					if ( isset( $attributes['hashtag'] ) ) {
						$this->import_new_items( $attributes['hashtag'], 'ignore' );
					}
				}
			}
		}
	} // end TGGRShortcodeTagregator
}