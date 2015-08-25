<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package untitled
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since untitled 1.0
 */
function untitled_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'untitled_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since untitled 1.0
 */
function untitled_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'widget-area';
	}

	return $classes;
}
add_filter( 'body_class', 'untitled_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since untitled 1.1
 */
function untitled_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'untitled' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'untitled_wp_title', 10, 2 );

function untitled_enhanced_image_navigation( $url, $id ) {
	_deprecated_function( __FUNCTION__, '1.1' );
	return $url;
}
