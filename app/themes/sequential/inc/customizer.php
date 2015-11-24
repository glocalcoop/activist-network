<?php
/**
 * Sequential Theme Customizer
 *
 * @package Sequential
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sequential_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/* Theme Options */
	$wp_customize->add_section( 'sequential_theme_options', array(
		'title'    => __( 'Theme', 'sequential' ),
		'priority' => 130,
	) );

	/* Show Tagline */
	$wp_customize->add_setting( 'sequential_tagline', array(
		'default'           => '',
		'sanitize_callback' => 'sequential_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'sequential_tagline', array(
		'label'             => __( 'Show Tagline', 'sequential' ),
		'section'           => 'sequential_theme_options',
		'priority'          => 10,
		'type'              => 'checkbox',
	) );

	/* Top Area Content */
	$wp_customize->add_setting( 'sequential_top_area_content', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'sequential_top_area_content', array(
		'label'             => __( 'Top Area Content', 'sequential' ),
		'section'           => 'sequential_theme_options',
		'priority'          => 20,
		'type'              => 'textarea',
	) );

	/* Front Page: Featured Page One */
	$wp_customize->add_setting( 'sequential_featured_page_one_front_page', array(
		'default'           => '',
		'sanitize_callback' => 'sequential_sanitize_dropdown_pages',
	) );
	$wp_customize->add_control( 'sequential_featured_page_one_front_page', array(
		'label'             => __( 'Front Page: Featured Page One', 'sequential' ),
		'section'           => 'sequential_theme_options',
		'priority'          => 30,
		'type'              => 'dropdown-pages',
	) );

	/* Front Page: Featured Page Two */
	$wp_customize->add_setting( 'sequential_featured_page_two_front_page', array(
		'default'           => '',
		'sanitize_callback' => 'sequential_sanitize_dropdown_pages',
	) );
	$wp_customize->add_control( 'sequential_featured_page_two_front_page', array(
		'label'             => __( 'Front Page: Featured Page Two', 'sequential' ),
		'section'           => 'sequential_theme_options',
		'priority'          => 40,
		'type'              => 'dropdown-pages',
	) );

	/* Front Page: show title */
	$wp_customize->add_setting( 'sequential_title_front_page', array(
		'default'           => '',
		'sanitize_callback' => 'sequential_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sequential_title_front_page', array(
		'label'             => __( 'Front Page: Show Page Titles', 'sequential' ),
		'section'           => 'sequential_theme_options',
		'priority'          => 50,
		'type'              => 'checkbox',
	) );
}
add_action( 'customize_register', 'sequential_customize_register' );

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean (true|false).
 */
function sequential_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Sanitize the dropdown pages.
 *
 * @param interger $input.
 * @return interger.
 */
function sequential_sanitize_dropdown_pages( $input ) {
	if ( is_numeric( $input ) ) {
		return intval( $input );
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sequential_customize_preview_js() {
	wp_enqueue_script( 'sequential-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20141022', true );
}
add_action( 'customize_preview_init', 'sequential_customize_preview_js' );
