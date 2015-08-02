<?php
/**
 * Mixfolio Theme Options
 *
 * @package Mixfolio
 * @since Mixfolio 1.1
 */

 /**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'mixfolio-theme-options', get_template_directory_uri() . '/inc/theme-options/theme-options.css', false, '2012-06-13' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'mixfolio_admin_enqueue_scripts' );

/**
 * Register the form setting for our mixfolio_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, mixfolio_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are properly
 * formatted, and safe.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_theme_options_init() {
	register_setting(
		'mixfolio_options', // Options group, see settings_fields() call in mixfolio_theme_options_render_page()
		'mixfolio_theme_options', // Database option, see mixfolio_get_theme_options()
		'mixfolio_theme_options_validate' // The sanitization callback, see mixfolio_theme_options_validate()
	);

	add_settings_section( // Register our settings field group
		'mixfolio_welcome_area', // Unique identifier for the settings section
		'', // Section title
		'__return_false',  // Section callback (we don't want anything)
		'theme_options'  // Menu slug, used to uniquely identify the page; see mixfolio_theme_options_add_page()
	);

	// Welcome Area Information
	add_settings_field( // Register our individual settings fields
		'mixfolio_display_welcome_area', // Unique identifier for the field for this section
		__( 'Display Welcome Area', 'mixfolio' ), // Setting field label
		'mixfolio_display_welcome_area', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see mixfolio_theme_options_add_page()
		'mixfolio_welcome_area' // Settings section. Same as the first argument in the add_settings_section() above
	);
	add_settings_field( 'mixfolio_welcome_area_title', __( 'Welcome Area Title', 'mixfolio' ), 'mixfolio_welcome_area_title', 'theme_options', 'mixfolio_welcome_area' );
	add_settings_field( 'mixfolio_welcome_area_message', __( 'Welcome Area Message', 'mixfolio' ), 'mixfolio_welcome_area_message', 'theme_options', 'mixfolio_welcome_area' );
	add_settings_field( 'mixfolio_twitter_id', __( 'Twitter ID', 'mixfolio' ), 'mixfolio_twitter_id', 'theme_options', 'mixfolio_welcome_area' );
	add_settings_field( 'mixfolio_display_contact_information', __( 'Display Contact Information', 'mixfolio' ), 'mixfolio_display_contact_information', 'theme_options', 'mixfolio_welcome_area' );
	add_settings_field( 'mixfolio_contact_details', __( 'Contact Details', 'mixfolio' ), 'mixfolio_contact_details', 'theme_options', 'mixfolio_welcome_area' );
	add_settings_field( 'mixfolio_contact_email_address', __( 'Contact Email Address', 'mixfolio' ), 'mixfolio_contact_email_address', 'theme_options', 'mixfolio_welcome_area' );

}
add_action( 'admin_init', 'mixfolio_theme_options_init' );

/**
 * Change the capability required to save the 'mixfolio_options' options group.
 *
 * @see mixfolio_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see mixfolio_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function mixfolio_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_mixfolio_options', 'mixfolio_option_page_capability' );

/**
 * Add our theme options page to the admin menu.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'mixfolio' ),   // Name of page
		__( 'Theme Options', 'mixfolio' ),   // Label in menu
		'edit_theme_options',          // Capability required
		'theme_options',               // Menu slug, used to uniquely identify the page
		'mixfolio_theme_options_render_page' // Function that renders the options page
	);
}
add_action( 'admin_menu', 'mixfolio_theme_options_add_page' );

/**
 * Returns the options array for Mixfolio.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_get_theme_options() {
	$saved = (array) get_option( 'mixfolio_theme_options' );
	$defaults = array(
		'mixfolio_display_welcome_area'			=> 'off',
		'mixfolio_welcome_area_title'			=> '',
		'mixfolio_welcome_area_message'			=> '',
		'mixfolio_twitter_id'					=> '',
		'mixfolio_display_contact_information'  => 'off',
		'mixfolio_contact_details'				=> '',
		'mixfolio_contact_email_address'		=> '',
	);

	$defaults = apply_filters( 'mixfolio_default_theme_options', $defaults );

	$options = wp_parse_args( $saved, $defaults );
	$options = array_intersect_key( $options, $defaults );

	return $options;
}

/**
 * Renders the Display Welcome Area setting field.
 */
function mixfolio_display_welcome_area() {
	$options = mixfolio_get_theme_options();
	?>
	<input type="checkbox" name="mixfolio_theme_options[mixfolio_display_welcome_area]" id="display-welcome-area" <?php checked( 'on', $options[ 'mixfolio_display_welcome_area' ] ); ?> />
	<label class="description" for="display-welcome-area"><?php _e( 'Display a welcome message at the top of your home page.', 'mixfolio' ); ?></label>
	<?php
}

/**
 * Renders the Welcome Area Title setting field.
 */
function mixfolio_welcome_area_title() {
	$options = mixfolio_get_theme_options();
	?>
	<input type="text" name="mixfolio_theme_options[mixfolio_welcome_area_title]" id="welcome-area-title" value="<?php echo esc_attr( $options[ 'mixfolio_welcome_area_title' ] ); ?>" />
	<label class="description" for="welcome-area-title"><?php _e( 'Something short and snazzy, like &#8220;Howdy!&#8221;', 'mixfolio' ); ?></label>
	<?php
}

/**
 * Renders the Welcome Area Messagesetting field.
 */
function mixfolio_welcome_area_message() {
	$options = mixfolio_get_theme_options();
	?>
	<textarea class="large-text" type="text" name="mixfolio_theme_options[mixfolio_welcome_area_message]" id="welcome-area-message" cols="50" rows="10" /><?php echo esc_textarea( $options['mixfolio_welcome_area_message'] ); ?></textarea>
	<label class="description" for="welcome-area-message"><?php _e( 'This message will appear above the thumbnails on your home page.', 'mixfolio' ); ?></label>
	<?php
}

/**
 * Renders the Twitter ID setting field.
 */
function mixfolio_twitter_id() {
	$options = mixfolio_get_theme_options();
	?>
	<input type="text" name="mixfolio_theme_options[mixfolio_twitter_id]" id="twitter-id" value="<?php echo esc_attr( $options[ 'mixfolio_twitter_id' ] ); ?>" />
	<label class="description" for="twitter-id"><?php _e( 'Tweets will be shown alongside your welcome message.', 'mixfolio' ); ?></label>
	<?php
}

/**
 * Renders the Display Contact Information setting field.
 */
function mixfolio_display_contact_information() {
	$options = mixfolio_get_theme_options(); ?>
		<input type="checkbox" name="mixfolio_theme_options[mixfolio_display_contact_information]" id="display-contact-information" <?php checked( 'on', $options[ 'mixfolio_display_contact_information' ] ); ?> />
		<label class="description" for="display-contact-information"><?php _e( 'Display contact information alongside your welcome message.', 'mixfolio' ); ?></label>
	<?php
}

/**
 * Renders the Contact Details setting field.
 */
function mixfolio_contact_details() {
	$options = mixfolio_get_theme_options();
	?>
	<textarea class="large-text" type="text" name="mixfolio_theme_options[mixfolio_contact_details]" id="contact-details" cols="50" rows="10" /><?php echo esc_textarea( $options['mixfolio_contact_details'] ); ?></textarea>
	<?php
}

/**
 * Renders the Contact Email Address setting field.
 */
function mixfolio_contact_email_address() {
	$options = mixfolio_get_theme_options();
	?>
	<input type="text" name="mixfolio_theme_options[mixfolio_contact_email_address]" id="contact-email-address" value="<?php echo esc_attr( $options[ 'mixfolio_contact_email_address' ] ); ?>" />
	<?php
}

/**
 * Renders the Theme Options administration screen.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>
			<?php printf( __( '%s Theme Options', 'mixfolio' ), wp_get_theme() ); ?>
		</h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'mixfolio_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see mixfolio_theme_options_init()
 * @todo set up Reset Options action
 *
 * @param array $input Unknown values.
 * @return array Sanitized theme options ready to be stored in the database.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_theme_options_validate( $input ) {
	$output = array();

	// Display Welcome Area?
	if ( isset( $input[ 'mixfolio_display_welcome_area' ] ) )
		$output[ 'mixfolio_display_welcome_area' ] = 'on';

	// Welcome Area Title
	if ( isset( $input[ 'mixfolio_welcome_area_title' ] ) && ! empty( $input[ 'mixfolio_welcome_area_title' ] ) )
		$output[ 'mixfolio_welcome_area_title' ] = strip_tags( $input[ 'mixfolio_welcome_area_title' ] );

	// Welcome Area Message
	if ( isset( $input[ 'mixfolio_welcome_area_message' ] ) && ! empty( $input[ 'mixfolio_welcome_area_message' ] ) )
		$output[ 'mixfolio_welcome_area_message' ] = stripslashes( wp_filter_post_kses( addslashes( $input[ 'mixfolio_welcome_area_message' ] ) ) );

	// Twitter ID
	if ( isset( $input[ 'mixfolio_twitter_id' ] ) && ! empty( $input[ 'mixfolio_twitter_id' ] ) && preg_match( '/^[@A-Za-z0-9_]+$/', $input[ 'mixfolio_twitter_id' ] ) )
		$output[ 'mixfolio_twitter_id' ] = wp_filter_nohtml_kses( ltrim( $input[ 'mixfolio_twitter_id' ], '@' ) );

	// Display Contact Information?
	if ( isset( $input[ 'mixfolio_display_contact_information' ] ) )
		$output[ 'mixfolio_display_contact_information' ] = 'on';

	// Contact Details
	if ( isset( $input[ 'mixfolio_contact_details' ] ) && ! empty( $input[ 'mixfolio_contact_details' ] ) )
		$output[ 'mixfolio_contact_details' ] = stripslashes( wp_filter_post_kses( addslashes( $input[ 'mixfolio_contact_details' ] ) ) );

	// Contact Email Address
	if ( isset( $input[ 'mixfolio_contact_email_address' ] ) && ! empty( $input[ 'mixfolio_contact_email_address' ] ) && is_email( $input[ 'mixfolio_contact_email_address' ] ) )
		$output[ 'mixfolio_contact_email_address' ] = stripslashes( wp_filter_nohtml_kses( addslashes( $input[ 'mixfolio_contact_email_address' ] ) ) );

	return apply_filters( 'mixfolio_theme_options_validate', $output, $input );

}