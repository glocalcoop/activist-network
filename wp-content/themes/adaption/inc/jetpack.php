<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Adaption
 */

function adaption_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );

	// Add responsive video support
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'adaption_jetpack_setup' );