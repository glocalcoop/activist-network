<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package untitled
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function untitled_infinite_scroll_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'content',
		'footer'    => 'page',
	) );

	// Add support for the Featured Content Plugin
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'untitled_get_featured_posts',
		'description'             => __( 'The featured content section displays on the front page above the first post in the content area.', 'untitled' ),
		'max_posts'               => 10,
	) );
}
add_action( 'after_setup_theme', 'untitled_infinite_scroll_setup' );

/**
 * Featured Posts
 *
 * @since untitled 1.0
 */
function untitled_has_multiple_featured_posts() {
	$featured_posts = untitled_get_featured_posts();
	return is_array( $featured_posts ) && 1 < count( $featured_posts );
}

function untitled_get_featured_posts() {
	return apply_filters( 'untitled_get_featured_posts', array() );
}
