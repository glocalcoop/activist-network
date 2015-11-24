<?php
/**
 * Fictive functions and definitions
 *
 * @package Fictive
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 634; /* pixels */
}

if ( ! function_exists( 'fictive_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fictive_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Fictive, use a find and replace
	 * to change 'fictive' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'fictive', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
	add_image_size( 'fictive-index-thumb', 816, 999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'fictive' ),
		'social'  => __( 'Social Links', 'fictive' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'audio', 'gallery', 'status' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'fictive_custom_background_args', array(
		'default-color' => 'efedea',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

	/**
	 * Add support for Eventbrite.
	 * See: https://wordpress.org/plugins/eventbrite-api/
	 */
	add_theme_support( 'eventbrite' );
}
endif; // fictive_setup
add_action( 'after_setup_theme', 'fictive_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function fictive_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'fictive' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'fictive_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fictive_scripts() {
	wp_enqueue_style( 'fictive-style', get_stylesheet_uri() );

	wp_enqueue_style( 'fictive-open-sans' );
	wp_enqueue_style( 'fictive-bitter' );

	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', '3.0.3' );

	wp_enqueue_script( 'fictive-script', get_template_directory_uri() . '/js/fictive.js', array( 'jquery' ), '20140403', true );

	wp_enqueue_script( 'fictive-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'fictive_scripts' );

function fictive_sidebar_position() {

	if ( 'fixed' == get_theme_mod( 'fictive_sidebar', 'scroll' ) ) : ?>
		<style type="text/css" id="fictive-sidebar">
			@media only screen and ( min-width: 1120px ) {
				.site-header {
					position: fixed;
				}
			}
		</style>
	<?php endif;
}
add_action( 'wp_head', 'fictive_sidebar_position' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Register Google Fonts
 */
function fictive_google_fonts() {

	$protocol = is_ssl() ? 'https' : 'http';

	/*	translators: If there are characters in your language that are not supported
		by Open Sans, translate this to 'off'. Do not translate into your own language. */

	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'fictive' ) ) {

		wp_register_style( 'fictive-open-sans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700" );

	}

	/*	translators: If there are characters in your language that are not supported
		by Bitter, translate this to 'off'. Do not translate into your own language. */

	if ( 'off' !== _x( 'on', 'Bitter font: on or off', 'fictive' ) ) {

		wp_register_style( 'fictive-bitter', "$protocol://fonts.googleapis.com/css?family=Bitter:400,700,400italic&subset=latin,latin-ext" );

	}

}
add_action( 'init', 'fictive_google_fonts' );

/**
 * Enqueue Google Fonts for custom headers
 */
function fictive_admin_scripts( $hook_suffix ) {

	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	wp_enqueue_style( 'fictive-open-sans' );
	wp_enqueue_style( 'fictive-bitter' );

}
add_action( 'admin_enqueue_scripts', 'fictive_admin_scripts' );

/**
 * Remove the separator from Eventbrite events meta.
 */
function edin_remove_meta_separator() {
	return false;
}
add_filter( 'eventbrite_meta_separator', 'edin_remove_meta_separator' );
