<?php

/*
 * a class for the Polylang settings pages
 * accessible in $polylang global object
 *
 * properties:
 * options          => inherited, reference to Polylang options array
 * model            => inherited, reference to PLL_Model object
 * links_model      => inherited, reference to PLL_Links_Model object
 * links            => inherited, reference to PLL_Admin_Links object
 * static_pages     => inherited, reference to PLL_Admin_Static_Pages object
 * filters_links    => inherited, reference to PLL_Filters_Links object
 * curlang          => inherited, optional, current language used to filter admin content
 * pref_lang        => inherited, preferred language used as default when saving posts or terms
 *
 * @since 1.2
 */
class PLL_Settings extends PLL_Admin_Base {
	protected $active_tab, $modules;

	/*
	 * constructor
	 *
	 * @since 1.2
	 *
	 * @param object $links_model
	 */
	public function __construct( &$links_model ) {
		parent::__construct( $links_model );

		$this->active_tab = ! empty( $_GET['tab'] ) ? $_GET['tab'] : 'lang';

		PLL_Admin_Strings::init();

		// FIXME put this as late as possible
		add_action( 'admin_init', array( &$this, 'register_settings_modules' ) );

		// adds screen options and the about box in the languages admin panel
		add_action( 'load-settings_page_mlang',  array( &$this, 'load_page' ) );

		// saves per-page value in screen option
		add_filter( 'set-screen-option', create_function( '$s, $o, $v', 'return $v;' ), 10, 3 );
	}

	/*
	 * initializes the modules
	 *
	 * @since 1.8
	 */
	public function register_settings_modules() {
		$modules = apply_filters( 'pll_settings_modules', array(
			'PLL_Settings_Url',
			'PLL_Settings_Browser',
			'PLL_Settings_Media',
			'PLL_Settings_CPT',
			'PLL_Settings_Sync',
			'PLL_Settings_WPML',
			'PLL_Settings_Tools',
		) );

		foreach ( $modules as $key => $class ) {
			$key = is_numeric( $key ) ? strtolower( str_replace( 'PLL_Settings_', '', $class ) ) : $key;
			$this->modules[ $key ] = new $class( $this );
		}
	}

	/*
	 * adds the link to the languages panel in the WordPress admin menu
	 *
	 * @since 0.1
	 */
	public function add_menus() {
		add_submenu_page( 'options-general.php', $title = __( 'Languages', 'polylang' ), $title, 'manage_options', 'mlang', array( $this, 'languages_page' ) );
	}

	/*
	 * adds screen options and the about box in the languages admin panel
	 *
	 * @since 0.9.5
	 */
	public function load_page() {
		// test of $this->active_tab avoids displaying the automatically generated screen options on other tabs
		switch ( $this->active_tab ) {
			case 'lang':
				ob_start();
				include( PLL_SETTINGS_INC.'/view-recommended.php' );
				$content = trim( ob_get_contents() );
				ob_end_clean();

				if ( strlen( $content ) > 0 ) {
					add_meta_box(
						'pll-recommended',
						__( 'Recommended plugins', 'polylang' ),
						create_function( '', "echo '$content';" ),
						'settings_page_mlang',
						'normal'
					);
				}

				if ( ! defined( 'PLL_DISPLAY_ABOUT' ) || PLL_DISPLAY_ABOUT ) {
					add_meta_box(
						'pll-about-box',
						__( 'About Polylang', 'polylang' ),
						create_function( '', "include( PLL_SETTINGS_INC.'/view-about.php' );" ),
						'settings_page_mlang',
						'normal'
					);
				}

				add_screen_option( 'per_page', array(
					'label'   => __( 'Languages', 'polylang' ),
					'default' => 10,
					'option'  => 'pll_lang_per_page',
				) );

				add_action( 'admin_notices', array( &$this, 'notice_objects_with_no_lang' ) );
			break;

			case 'strings':
				add_screen_option( 'per_page', array(
					'label'   => __( 'Strings translations', 'polylang' ),
					'default' => 10,
					'option'  => 'pll_strings_per_page',
				) );
			break;
		}
	}

	/*
	 * diplays the 3 tabs pages: languages, strings translations, settings
	 * also manages user input for these pages
	 *
	 * @since 0.1
	 */
	public function languages_page() {
		// prepare the list of tabs
		$tabs = array( 'lang' => __( 'Languages','polylang' ) );

		// only if at least one language has been created
		if ( $listlanguages = $this->model->get_languages_list() ) {
			$tabs['strings'] = __( 'Strings translation','polylang' );
			$tabs['settings'] = __( 'Settings', 'polylang' );
		}

		// allows plugins to add tabs
		$tabs = apply_filters( 'pll_settings_tabs', $tabs );

		switch ( $this->active_tab ) {
			case 'lang':
				// prepare the list table of languages
				$list_table = new PLL_Table_Languages();
				$list_table->prepare_items( $listlanguages );
			break;

			case 'strings':
				// get the strings to translate
				$data = PLL_Admin_Strings::get_strings();

				// get the groups
				foreach ( $data as $key => $row ) {
					$groups[] = $row['context'];
				}

				$groups = array_unique( $groups );
				$selected = empty( $_GET['group'] ) || ! in_array( $_GET['group'], $groups ) ? -1 : $_GET['group'];
				$s = empty( $_GET['s'] ) ? '' : wp_unslash( $_GET['s'] );

				// filter for search string
				foreach ( $data as $key => $row ) {
					if ( ( -1 != $selected && $row['context'] != $selected ) || ( ! empty( $s ) && stripos( $row['name'], $s ) === false && stripos( $row['string'], $s ) === false ) ) {
						unset( $data[ $key ] );
					}
				}

				// load translations
				foreach ( $listlanguages as $language ) {
					// filters by language if requested
					if ( ( $lg = get_user_meta( get_current_user_id(), 'pll_filter_content', true ) ) && $language->slug != $lg ) {
						continue;
					}

					$mo = new PLL_MO();
					$mo->import_from_db( $language );
					foreach ( $data as $key => $row ) {
						$data[ $key ]['translations'][ $language->slug ] = $mo->translate( $row['string'] );
						$data[ $key ]['row'] = $key; // store the row number for convenience
					}
				}

				// get an array with language slugs as keys, names as values
				$languages = array_combine( wp_list_pluck( $listlanguages, 'slug' ), wp_list_pluck( $listlanguages, 'name' ) );

				$string_table = new PLL_Table_String( compact( 'languages', 'groups', 'selected' ) );
				$string_table->prepare_items( $data );
			break;

			case 'settings':
				$post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );
				$post_types = array_diff( $post_types, get_post_types( array( '_pll' => true ) ) );
				$post_types = array_unique( apply_filters( 'pll_get_post_types', $post_types, true ) );

				$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ) );
				$taxonomies = array_diff( $taxonomies, get_taxonomies( array( '_pll' => true ) ) );
				$taxonomies = array_unique( apply_filters( 'pll_get_taxonomies', $taxonomies , true ) );
			break;
		}

		$action = isset( $_REQUEST['pll_action'] ) ? $_REQUEST['pll_action'] : '';

		switch ( $action ) {
			case 'add':
				check_admin_referer( 'add-lang', '_wpnonce_add-lang' );

				if ( $this->model->add_language( $_POST ) && 'en_US' != $_POST['locale'] ) {
					// attempts to install the language pack
					require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
					if ( ! wp_download_language_pack( $_POST['locale'] ) ) {
						add_settings_error( 'general', 'pll_download_mo', __( 'The language was created, but the WordPress language file was not downloaded. Please install it manually.', 'polylang' ) );
					}

					// force checking for themes and plugins translations updates
					wp_clean_themes_cache();
					wp_clean_plugins_cache();
				}
				self::redirect(); // to refresh the page ( possible thanks to the $_GET['noheader']=true )
			break;

			case 'delete':
				check_admin_referer( 'delete-lang' );

				if ( ! empty( $_GET['lang'] ) ) {
					$this->model->delete_language( (int) $_GET['lang'] );
				}

				self::redirect(); // to refresh the page ( possible thanks to the $_GET['noheader']=true )
			break;

			case 'edit':
				if ( ! empty( $_GET['lang'] ) ) {
					$edit_lang = $this->model->get_language( (int) $_GET['lang'] );
				}
			break;

			case 'update':
				check_admin_referer( 'add-lang', '_wpnonce_add-lang' );
				$error = $this->model->update_language( $_POST );
				self::redirect(); // to refresh the page ( possible thanks to the $_GET['noheader']=true )
			break;

			case 'default-lang':
				check_admin_referer( 'default-lang' );

				if ( $lang = $this->model->get_language( (int) $_GET['lang'] ) ) {
					$this->model->update_default_lang( $lang->slug );
				}

				self::redirect(); // to refresh the page ( possible thanks to the $_GET['noheader']=true )
			break;

			case 'content-default-lang':
				check_admin_referer( 'content-default-lang' );

				if ( $nolang = $this->model->get_objects_with_no_lang() ) {
					if ( ! empty( $nolang['posts'] ) ) {
						$this->model->set_language_in_mass( 'post', $nolang['posts'], $this->options['default_lang'] );
					}
					if ( ! empty( $nolang['terms'] ) ) {
						$this->model->set_language_in_mass( 'term', $nolang['terms'], $this->options['default_lang'] );
					}
				}

				self::redirect(); // to refresh the page ( possible thanks to the $_GET['noheader']=true )
			break;

			case 'string-translation':
				if ( ! empty( $_POST['submit'] ) ) {
					check_admin_referer( 'string-translation', '_wpnonce_string-translation' );
					$strings = PLL_Admin_Strings::get_strings();

					foreach ( $this->model->get_languages_list() as $language ) {
						if ( empty( $_POST['translation'][ $language->slug ] ) ) { // in case the language filter is active ( thanks to John P. Bloch )
							continue;
						}

						$mo = new PLL_MO();
						$mo->import_from_db( $language );

						foreach ( $_POST['translation'][ $language->slug ] as $key => $translation ) {
							$translation = apply_filters( 'pll_sanitize_string_translation', $translation, $strings[ $key ]['name'], $strings[ $key ]['context'] );
							$mo->add_entry( $mo->make_entry( $strings[ $key ]['string'], $translation ) );
						}

						// clean database ( removes all strings which were registered some day but are no more )
						if ( ! empty( $_POST['clean'] ) ) {
							$new_mo = new PLL_MO();

							foreach ( $strings as $string ) {
								$new_mo->add_entry( $mo->make_entry( $string['string'], $mo->translate( $string['string'] ) ) );
							}
						}

						isset( $new_mo ) ? $new_mo->export_to_db( $language ) : $mo->export_to_db( $language );
					}

					add_settings_error( 'general', 'pll_strings_translations_updated', __( 'Translations updated.', 'polylang' ), 'updated' );
					do_action( 'pll_save_strings_translations' );
				}

				// unregisters strings registered through WPML API
				if ( $string_table->current_action() == 'delete' && !empty( $_POST['strings'] ) && function_exists( 'icl_unregister_string' ) ) {
					check_admin_referer( 'string-translation', '_wpnonce_string-translation' );
					$strings = PLL_Admin_Strings::get_strings();

					foreach ( $_POST['strings'] as $key ) {
						icl_unregister_string( $strings[ $key ]['context'], $strings[ $key ]['name'] );
					}
				}

				// to refresh the page ( possible thanks to the $_GET['noheader']=true )
				$args = array_intersect_key( $_REQUEST, array_flip( array( 's', 'paged', 'group' ) ) );
				if ( ! empty( $args['s'] ) ) {
					$args['s'] = urlencode( $args['s'] ); // searched string needs to be encoded as it comes from $_POST
				}
				self::redirect( $args );
			break;

			case 'activate':
				check_admin_referer( 'pll_activate' );
				$this->modules[ $_GET['module'] ]->activate();
				self::redirect();
			break;

			case 'deactivate':
				check_admin_referer( 'pll_deactivate' );
				$this->modules[ $_GET['module'] ]->deactivate();
				self::redirect();
			break;

			default:
				do_action( "mlang_action_$action" );
			break;
		}

		// displays the page
		include( PLL_SETTINGS_INC.'/view-languages.php' );
	}

	/*
	 * enqueues scripts and styles
	 */
	public function admin_enqueue_scripts() {
		parent::admin_enqueue_scripts();

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'pll_admin', POLYLANG_URL .'/js/admin'.$suffix.'.js', array( 'jquery', 'wp-ajax-response', 'postbox', 'jquery-ui-selectmenu' ), POLYLANG_VERSION );
		wp_localize_script( 'pll_admin', 'pll_flag_base_url', POLYLANG_URL . '/flags/' );

		wp_enqueue_style( 'pll_selectmenu', POLYLANG_URL .'/css/selectmenu'.$suffix.'.css', array(), POLYLANG_VERSION );
	}

	/*
	 * displays a notice when there are objects with no language assigned
	 *
	 * @since 1.8
	 */
	public function notice_objects_with_no_lang() {
		if ( ! empty( $this->options['default_lang'] ) && $this->model->get_objects_with_no_lang() ) {
			printf(
				'<div class="error"><p>%s <a href="%s">%s</a></p></div>',
				__( 'There are posts, pages, categories or tags without language.', 'polylang' ),
				wp_nonce_url( '?page=mlang&amp;pll_action=content-default-lang&amp;noheader=true', 'content-default-lang' ),
				__( 'You can set them all to the default language.', 'polylang' )
			);
		}
	}

	/*
	 * redirects to language page ( current active tab )
	 * saves error messages in a transient for reuse in redirected page
	 *
	 * @since 1.5
	 *
	 * @param array $args query arguments to add to the url
	 */
	static public function redirect( $args = array() ) {
		if ( $errors = get_settings_errors() ) {
			set_transient( 'settings_errors', $errors, 30 );
			$args['settings-updated'] = 1;
		}
		// remove possible 'pll_action' and 'lang' query args from the referer before redirecting
		wp_safe_redirect( add_query_arg( $args,  remove_query_arg( array( 'pll_action', 'lang' ), wp_get_referer() ) ) );
		exit;
	}
}
