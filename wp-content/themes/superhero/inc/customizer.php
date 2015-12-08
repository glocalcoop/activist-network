<?php
/**
 * superhero Theme Customizer
 *
 * @package superhero
 * @since superhero 1.2
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since superhero 1.2
 */
function superhero_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'superhero_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since superhero 1.2
 */
function superhero_customize_preview_js() {
	wp_enqueue_script( 'superhero_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20140722', true );
}
add_action( 'customize_preview_init', 'superhero_customize_preview_js' );
