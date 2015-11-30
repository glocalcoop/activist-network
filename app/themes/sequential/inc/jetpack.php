<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Sequential
 */

function sequential_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer_widgets' => array(
			'sidebar-2',
		),
		'footer'         => 'page',
		'render'    	 => 'sequential_infinite_scroll_render',
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	/**
	 * Add theme support for Testimonial CPT.
	 */
	add_image_size( 'sequential-avatar', 96, 96, true );
	add_theme_support( 'jetpack-testimonial' );

	/**
	 * Add theme support for Logo upload.
	 */
	add_image_size( 'sequential-logo', 624, 624 );
	add_theme_support( 'site-logo', array( 'size' => 'sequential-logo' ) );
}
add_action( 'after_setup_theme', 'sequential_jetpack_setup' );

/**
 * Disable Infinite Scroll for the Testimonial CPT
 * @return bool
 */
function sequential_infinite_scroll_supported() {
        return current_theme_supports( 'infinite-scroll' ) && ! is_post_type_archive( 'jetpack-testimonial' );
}
add_filter( 'infinite_scroll_archive_supported', 'sequential_infinite_scroll_supported' );

/**
 * Flush the Rewrite Rules for the Testimonial CPT after the user has activated the theme.
 */
function sequential_flush_rewrite_rules() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'sequential_flush_rewrite_rules' );

/**
 * Return early if Site Logo is not available.
 */
function sequential_the_site_logo() {
	if ( ! function_exists( 'jetpack_the_site_logo' ) ) {
		return;
	} else {
		jetpack_the_site_logo();
	}
}

/**
 * Remove sharedaddy script.
 */
function sequential_remove_sharedaddy_script() {
    remove_action( 'wp_head', 'sharing_add_header', 1 );
}
add_action( 'template_redirect', 'sequential_remove_sharedaddy_script' );

/**
 * Remove related-posts and likes scripts.
 */
function sequential_remove_jetpack_scripts() {
    wp_dequeue_style( 'jetpack_related-posts' );
    wp_dequeue_style( 'jetpack_likes' );
}
add_action( 'wp_enqueue_scripts', 'sequential_remove_jetpack_scripts' );

/**
 * Remove sharedaddy from excerpt.
 */
function sequential_remove_sharedaddy() {
    remove_filter( 'the_excerpt', 'sharing_display', 19 );
}
add_action( 'loop_start', 'sequential_remove_sharedaddy' );

/**
 * Remove Testimonial Page Featured Image option.
 */
function sequential_testimonials_customize_register( $wp_customize ) {
	$wp_customize->remove_setting( 'jetpack_testimonials[featured-image]' );
	$wp_customize->remove_control( 'jetpack_testimonials[featured-image]' );
}
add_action( 'customize_register', 'sequential_testimonials_customize_register', 11 );
