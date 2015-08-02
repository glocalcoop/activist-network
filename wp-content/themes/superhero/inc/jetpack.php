<?php
/**
 * Compatibility settings and functions for Jetpack.
 * See http://jetpack.me/support/infinite-scroll/
 *
 * @package Superhero
 */


/**
 * Add support for Infinite Scroll.
 */
function superhero_infinite_scroll_init() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'content',
		'footer'    => 'main',
	) );
}
add_action( 'after_setup_theme', 'superhero_infinite_scroll_init' );


/**
 * Check whether or not footer widgets are present. If they are present, then a button to
 * 'Load more posts' will be displayed and IS will not be triggered unless a user manually clicks on that button.
 *
 * @param bool $has_widgets
 * @uses Jetpack_User_Agent_Info::is_ipad, jetpack_is_mobile, is_active_sidebar
 * @filter infinite_scroll_has_footer_widgets
 * @return bool
 */
function superhero_has_footer_widgets( $has_widgets ) {
	if ( ( Jetpack_User_Agent_Info::is_ipad() || ( function_exists( 'jetpack_is_mobile' ) && jetpack_is_mobile() ) ) && is_active_sidebar( 'sidebar-1' ) )
		$has_widgets = true;

	return $has_widgets;
}
add_filter( 'infinite_scroll_has_footer_widgets', 'superhero_has_footer_widgets' );


/**
 * Add support for the Featured Content Plugin
 *
 * @since Superhero 1.0
 */
function superhero_featured_content_init() {
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'superhero_get_featured_posts',
		'description'             => __( 'The featured content section displays on the front page above the first post in the content area.', 'superhero' ),
		'max_posts'               => 10,
	) );
}
add_action( 'after_setup_theme', 'superhero_featured_content_init' );


/**
 * Featured Posts
 *
 * @since Superhero 1.0
 */
function superhero_has_multiple_featured_posts() {
	$featured_posts = apply_filters( 'superhero_get_featured_posts', array() );
	return ( is_array( $featured_posts ) && 1 < count( $featured_posts ) );
}

function superhero_get_featured_posts() {
	return apply_filters( 'superhero_get_featured_posts', false );
}

/**
 * Add support for the Site Logo
 *
 * @since Superhero 1.1.6
 */
function superhero_site_logo_init() {
	add_image_size( 'superhero-logo', 480, 150 );
	add_theme_support( 'site-logo', array( 'size' => 'superhero-logo' ) );
}
add_action( 'after_setup_theme', 'superhero_site_logo_init' );

/**
 * Return early if Site Logo is not available.
 */
function superhero_the_site_logo() {
	if ( ! function_exists( 'jetpack_the_site_logo' ) ) {
		return;
	} else {
		jetpack_the_site_logo();
	}
}

/**
 * Add theme support for Responsive Videos
 *
 * @since Superhero 1.1.6
 */
function superhero_responsive_videos_init() {
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'superhero_responsive_videos_init' );
