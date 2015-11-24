<?php
/**
 * WordPress.com-specific functions and definitions
 *
 * @package Mixfolio
 * @since Mixfolio 1.1
 */

//global $themecolors;

/**
 * Set a default theme color array for WP.com.
 *
 * @global array $themecolors
 * @since Mixfolio 1.1
 */
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'ffffff',
		'border' => 'dddddd',
		'text' => '555555',
		'link' => '11639d',
		'url' => '11639d',
	);
}