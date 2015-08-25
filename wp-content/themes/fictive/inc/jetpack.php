<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Fictive
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function fictive_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
		'wrapper'   => false,
	) );
}
add_action( 'after_setup_theme', 'fictive_jetpack_setup' );
