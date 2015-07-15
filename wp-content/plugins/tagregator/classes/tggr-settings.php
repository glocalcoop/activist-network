<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'TGGRSettings' ) ) {
	/**
	 * Handles plugin settings and user profile meta fields
	 * @package Tagregator
	 */
	class TGGRSettings extends TGGRModule {
		protected $settings;
		protected static $default_settings;
		protected static $readable_properties  = array( 'settings' );
		protected static $writeable_properties = array( 'settings' );
		
		const REQUIRED_CAPABILITY = 'manage_options';
		const MENU_SLUG           = 'tagregator';
		const SETTING_SLUG        = 'tggr_settings';


		/**
		 * Constructor
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
		}

		/**
		 * Public setter for protected variables
		 * Updates settings outside of the Settings API or other subsystems
		 * @mvc Controller
		 *
		 * @param string $variable
		 * @param array  $value This will be merged with TGGRSettings->settings, so it should mimic the structure of the TGGRSettings::$default_settings. It only needs the contain the values that will change, though. See Tagregator->upgrade() for an example.
		 */
		public function __set( $variable, $value ) {
			// Note: TGGRModule::__set() is automatically called before this

			if ( $variable != 'settings' ) {
				return;
			}

			$this->settings = self::validate_settings( $value );
			update_option( Tagregator::PREFIX . 'settings', $this->settings );
		}

		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action( 'init',                                 array( $this, 'init' ) );
			add_action( 'admin_init',                           array( $this, 'register_settings' ) );
			add_action( 'admin_menu',                           __CLASS__ . '::register_settings_pages' );

			add_filter( 'shortcode_atts_' . self::SETTING_SLUG, array( $this, 'maintain_nested_settings' ), 10, 3 );
			add_filter(
				'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
				__CLASS__ . '::add_plugin_action_links'
			);
		}

		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 */
		public function deactivate() {}

		/**
		 * Initializes variables
		 * @mvc Controller
		 */
		public function init() {
			self::$default_settings = self::get_default_settings();
			$this->settings         = self::get_settings();
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 * @mvc Model
		 *
		 * @param string $db_version
		 */
		public function upgrade( $db_version = 0 ) {}

		/**
		 * Establishes initial values for all settings
		 * @mvc Model
		 * @return array
		 */
		protected static function get_default_settings() {
			return apply_filters( Tagregator::PREFIX . 'default_settings', array( 'db_version' => '0' ) );
		}

		/**
		 * Retrieves all of the settings from the database
		 * @mvc Model
		 * @return array
		 */
		protected static function get_settings() {
			$settings = shortcode_atts(
				self::$default_settings,
				get_option( Tagregator::PREFIX . 'settings', array() ),
				self::SETTING_SLUG
			);

			return $settings;
		}

		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 * @mvc Model
		 *
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 */
		public static function add_plugin_action_links( $links ) {
			array_unshift( $links, '<a href="http://wordpress.org/plugins/tagregator/faq/">Help</a>' );
			array_unshift( $links, '<a href="admin.php?page=' . self::SETTING_SLUG .'">Settings</a>' );

			return $links;
		}

		/**
		 * Adds pages to the Admin Panel menu
		 * @mvc Controller
		 */
		public static function register_settings_pages() {
			add_menu_page(
				TGGR_NAME,
				TGGR_NAME,
				self::REQUIRED_CAPABILITY,
				self::MENU_SLUG,
				__CLASS__ . '::markup_settings_page'
			);

			add_submenu_page(
				self::MENU_SLUG,
				TGGRMediaSource::TAXONOMY_HASHTAG_NAME_PLURAL,
				TGGRMediaSource::TAXONOMY_HASHTAG_NAME_PLURAL,
				self::REQUIRED_CAPABILITY,
				'edit-tags.php?taxonomy=' . TGGRMediaSource::TAXONOMY_HASHTAG_SLUG
			);

			add_submenu_page(
				self::MENU_SLUG,
				'Settings',
				'Settings',
				self::REQUIRED_CAPABILITY,
				Tagregator::PREFIX . 'settings',
				__CLASS__ . '::markup_settings_page'
			);

			if ( apply_filters( Tagregator::PREFIX . 'show_log', false ) ) {
				add_submenu_page(
					self::MENU_SLUG,
					'Log',
					'Log',
					self::REQUIRED_CAPABILITY,
					Tagregator::PREFIX . 'log',
					__CLASS__ . '::markup_log_page'
				);
			}

			remove_submenu_page( self::MENU_SLUG, self::MENU_SLUG );	// The top level menu just points to the Settings page, so the 'Tagregator' submenu is redundant
		}

		/**
		 * Creates the markup for the Settings page
		 * @mvc Controller
		 */
		public static function markup_settings_page() {
			if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
				require_once( dirname( __DIR__ ) . '/views/tggr-settings/page-settings.php' );
			} else {
				wp_die( 'Access denied.' );
			}
		}

		/**
		 * Creates the markup for the Log page
		 * @mvc Controller
		 */
		public static function markup_log_page() {
			if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
				$log_entries = get_option( Tagregator::PREFIX . 'log', array() );
				require_once( dirname( __DIR__ ) . '/views/tggr-settings/page-log.php' );
			} else {
				wp_die( 'Access denied.' );
			}
		}

		/**
		 * Registers settings sections, fields and settings
		 * @mvc Controller
		 */
		public function register_settings() {
			register_setting(
				self::SETTING_SLUG,
				self::SETTING_SLUG,
				array( $this, 'validate_settings' )
			);
		}

		/**
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 * @mvc Model
		 *
		 * @param array $new_settings
		 * @return array
		 */
		public function validate_settings( $new_settings ) {
			$new_settings = shortcode_atts( $this->settings, $new_settings, self::SETTING_SLUG );

			if ( is_string( $new_settings['db_version'] ) ) {
				$new_settings['db_version'] = sanitize_text_field( $new_settings['db_version'] );
			} else {
				$new_settings['db_version'] = Tagregator::VERSION;
			}

			foreach ( Tagregator::get_instance()->media_sources as $class_name => $media_source ) {
				$new_settings[ $class_name ] = $media_source::get_instance()->validate_settings( $new_settings[ $class_name ] );
			}

			return $new_settings;
		}

		/**
		 * Prevents shortcode_atts() from breaking my shit
		 * This is a callback for the shortcode_atts_{$shortcode} filter.
		 *
		 * shortcode_atts() doesn't recurse into arrays, so it will strip out nested $pairs that aren't present in $atts
		 * e.g., Given $pairs['foo']['bar'] = '5', and $pairs['foo']['bar2'] = 6, and $atts = $pairs['foo']['bar'] = 3;
		 * the returned value would be missing $pairs['foo']['bar2']
		 *
		 * This isn't recursive; it only supports 1 level deep, but that's enough for me.
		 *
		 * @mvc Model
		 *
		 * @param array $output The output array of shortcode attributes.
		 * @param array $pairs The supported attributes and their defaults.
		 * @param array $atts The user defined shortcode attributes.
		 * @return array
		 */
		public function maintain_nested_settings( $output, $pairs, $atts ) {
			// Check each item in $pairs. If the item is itself an array, then check all of it's items. If any of those items are missing from $atts, then add them to $output
			foreach ( $pairs as $outer_pair_key => $outer_pair_value ) {
				if ( is_array( $pairs[ $outer_pair_key ] ) ) {
					foreach ( $outer_pair_value as $key => $value ) {
						if ( isset( $atts[ $outer_pair_key ] ) && ! array_key_exists( $key, $atts[ $outer_pair_key ] ) ) {
							$output[ $outer_pair_key ][ $key ] = $value;
						}
					}
				}
			}

			return $output;
		}
	} // end TGGRSettings
}
