<?
/**
 * 
 * Create Options Menu
 * 
 **/

function anp_global_menu_options_menu() {

	/*
	 * 	Use the add_options_page function
	 * 	add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function ) 
	 *
	*/

	add_submenu_page(
		'settings.php', 
		__( 'Activist Network Global Menu', 'glocal-global-menu' ),
		__( 'Global Menu', 'glocal-global-menu' ),
		'manage_options',
		'anp-global-menu',
		'anp_global_menu_options_page'
	);

}

add_action( 'network_admin_menu', 'anp_global_menu_options_menu' );

/**
 * 
 * Create Options Page
 * 
 **/

function anp_global_menu_options_page() {

	if( !current_user_can( 'manage_options' ) ) {

		wp_die( 'You do not have sufficient permissions to access this page.' );

	}

	global $plugin_url;
	global $options;

	/*
	 * Process form
	 */

	if( isset( $_POST['anp_global_menu_form_submitted'] ) ) {

		$hidden_field = esc_html( $_POST['anp_global_menu_form_submitted'] );

		// Test if form has been submitted
		if( $hidden_field == 'Y' ) {

			$anp_global_menu_selected = esc_html( $_POST['anp_global_menu_selected'] );
			$options['anp_global_menu_selected'] = $anp_global_menu_selected;

			$options['last_updated'] = time();

			update_option( 'anp-global-menu', $options );

		}

	}

	// Assign options table values to $options variable
	$options = get_option( 'anp-global-menu' );

	// Check if options table has values
	if( !empty( $options ) ) {

		$anp_global_menu_selected = $options['anp_global_menu_selected'];
		$last_updated = $options['last_updated'];

	}


	/*
	 * Render Page
	 */

	include_once( 'views/view-options-page.php' );

}

	

?>