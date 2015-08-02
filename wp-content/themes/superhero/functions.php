<?php
/**
 * Superhero functions and definitions
 *
 * @package Superhero
 * @since Superhero 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Superhero 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 645; /* pixels */

/**
 * Adjust the content width for Full Width page template.
 */
function superhero_set_content_width() {
	global $content_width;

	if ( is_page_template( 'page-full-width.php' ) )
		$content_width = 910;
}
add_action( 'template_redirect', 'superhero_set_content_width' );

if ( ! function_exists( 'superhero_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Superhero 1.0
 */
function superhero_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on superhero, use a find and replace
	 * to change 'superhero' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'superhero', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'slider-img', 1440, 500, true );
	add_image_size( 'feat-img', 695 );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'superhero' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'f2f2f2',
	) );

	/**
	 * Add support for Eventbrite.
	 * See: https://wordpress.org/plugins/eventbrite-api/
	 */
	add_theme_support( 'eventbrite' );
}
endif; // superhero_setup
add_action( 'after_setup_theme', 'superhero_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Superhero 1.0
 */
function superhero_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'superhero' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'superhero_widgets_init' );

/**
 * Enqueue Google Fonts
 */
function superhero_fonts() {

	$protocol = is_ssl() ? 'https' : 'http';

	/*	translators: If there are characters in your language that are not supported
		by Carrois Gothic, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Carrois Gothic font: on or off', 'superhero' ) ) {
		wp_register_style( 'superhero-carrois-gothic', "$protocol://fonts.googleapis.com/css?family=Carrois+Gothic" );
	}
}
add_action( 'init', 'superhero_fonts' );

/**
 * Enqueue font styles in custom header admin
 */
function superhero_admin_fonts( $hook_suffix ) {

	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	wp_enqueue_style( 'superhero-carrois-gothic' );

}
add_action( 'admin_enqueue_scripts', 'superhero_admin_fonts' );

/**
 * Enqueue scripts and styles
 */
function superhero_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'superhero-carrois-gothic' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'superhero-script', get_template_directory_uri() . '/js/superhero.js', array( 'jquery' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	if ( is_front_page() && superhero_get_featured_posts() ) {
		wp_enqueue_style( 'superhero-flex-slider-style', get_template_directory_uri() . '/js/flex-slider/flexslider.css' );
		wp_enqueue_script( 'superhero-flex-slider', get_template_directory_uri() . '/js/flex-slider/jquery.flexslider-min.js', array( 'jquery' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'superhero_scripts' );

/**
 * Use a pipe for Eventbrite meta separators.
 */
function sketch_eventbrite_meta_separator() {
	return '<span class="sep"> | </span>';
}
add_filter( 'eventbrite_meta_separator', 'sketch_eventbrite_meta_separator' );

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
if ( file_exists( get_template_directory() . '/inc/jetpack.php' ) )
	require get_template_directory() . '/inc/jetpack.php';
