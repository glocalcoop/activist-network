<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package hew
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function hew_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'hew_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hew_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_active_sidebar( 'sidebar-1' )
	  || is_active_sidebar( 'sidebar-2' )
	  || is_active_sidebar( 'sidebar-3' )
	  || is_active_sidebar( 'sidebar-4' )
	) {
		$classes[] = 'has-sidebar';
	}

	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) {
		$classes[] = 'has-header-image';
	}

	return $classes;
}
add_filter( 'body_class', 'hew_body_classes' );

/**
 * Get first image from the_content()
 */
function hew_get_first_image( $content ) {
	$images = array();

	preg_match_all( '!<img.*src=[\'"]([^"]+)[\'"].*/?>!iUs', $content, $matches );

	if ( !empty( $matches[1] ) ) {
		foreach ( $matches[1] as $match ) {
			if ( stristr( $match, '/smilies/' ) ) {
				continue;
			}

			$images[] = array(
				'type'  => 'image',
				'from'  => 'html',
				'src'   => html_entity_decode( $match ),
				'href'  => '', // No link to apply to these. Might potentially parse for that as well, but not for now
			);
		}

		return $images[0]; //Return the first image
	}

}

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 */
	function hew_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}
		global $page, $paged;
		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'hew' ), max( $paged, $page ) );
		}
		return $title;
	}
	add_filter( 'wp_title', 'hew_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function hew_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'hew_render_title' );
endif;
