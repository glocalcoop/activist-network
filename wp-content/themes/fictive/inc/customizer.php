<?php
/**
 * Fictive Theme Customizer
 *
 * @package Fictive
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fictive_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_section( 'fictive_theme_options', array(
		'title'             => __( 'Theme', 'fictive' ),
		'priority'          => 35,
	) );

	$wp_customize->add_setting( 'fictive_gravatar_email', array(
		'default'           => get_option( 'admin_email' ),
		'sanitize_callback' => 'fictive_sanitize_email',
	) );

	$wp_customize->add_control( 'fictive_gravatar_email', array(
		'label'             => __( 'Gravatar Email', 'fictive' ),
		'section'           => 'fictive_theme_options',
		'type'              => 'text',
		'priority'          => 1,
	) );

	$wp_customize->add_setting( 'fictive_sidebar', array(
		'default'           => 'scroll',
		'sanitize_callback' => 'fictive_sanitize_sidebar',
	) );

	$wp_customize->add_control( 'fictive_sidebar', array(
		'label'             => __( 'Sidebar Position', 'fictive' ),
		'section'           => 'fictive_theme_options',
		'type'              => 'radio',
		'choices'           => array(
			                        'fixed'  => __( 'Fixed', 'fictive' ),
			                        'scroll' => __( 'Scroll', 'fictive' ),
			                        ),
		'priority'          => 1,
	) );
}
add_action( 'customize_register', 'fictive_customize_register' );


function fictive_sanitize_email( $value ) {

	if ( '' != $value && is_email( $value ) )
		$value = sanitize_email( $value );
	else
		$value = '';

	return $value;
}

function fictive_sanitize_sidebar( $sidebarvalue ) {

	if ( '' != $sidebarvalue && ! in_array( $sidebarvalue, array( 'fixed', 'scroll' ) ) )
		$sidebarvalue = 'scroll';

	return $sidebarvalue;
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function fictive_customize_preview_js() {
	wp_enqueue_script( 'fictive_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'fictive_customize_preview_js' );
