<?php
/**
 * Hew functions and definitions
 *
 * @package Hew
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 984; /* pixels */
}

if ( ! function_exists( 'hew_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function hew_setup() {

	load_theme_textdomain( 'hew', get_template_directory() . '/languages' );

	add_editor_style( array( 'editor-style.css', hew_fonts_url() ) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	// Post thumbnails
	set_post_thumbnail_size( 984, 9999, false );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'	=> __( 'Primary Menu', 'hew' ),
		'social'	=> __( 'Social Menu', 'hew' ),
	) );

	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'caption',
	) );

	add_theme_support( 'post-formats', array(
		'aside', 'image', 'gallery', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'hew_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	add_filter( 'use_default_gallery_style', '__return_false' );

}
endif; // hew_setup
add_action( 'after_setup_theme', 'hew_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function hew_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Top Widget Area One', 'hew' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Top Widget Area Two', 'hew' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Top Widget Area Three', 'hew' ),
		'id'            => 'sidebar-3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Top Widget Area Four', 'hew' ),
		'id'            => 'sidebar-4',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'hew_widgets_init' );

/**
 * Register Google fonts for Hew
 */
/**
 * Returns the Google font stylesheet URL, if available.
 */
function hew_fonts_url() {
	$fonts_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Source Sans Pro, translate this to 'off'. Do not translate into your own language.
	 */
	$open_sans  = _x( 'on', 'Open Sans font: on or off',  'hew' );

	/* translators: If there are characters in your language that are not supported
	 * by Droid Serif, translate this to 'off'. Do not translate into your own language.
	 */
	$noto_serif = _x( 'on', 'Noto Serif font: on or off', 'hew' );

	if ( 'off' !== $open_sans || 'off' !== $noto_serif ) {
		$font_families = array();

		if ( 'off' !== $open_sans ) {
			$font_families[] = 'Open Sans:400,600,700,400italic,600italic,700italic';
		}
		if ( 'off' !== $noto_serif ) {
			$font_families[] = 'Noto Serif:400,700,400italic,700italic';
		}
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin' ),
		);
		$fonts_url = add_query_arg( $query_args, "https://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles.
 */
function hew_scripts() {
	// Add Open Sans and Noto Serif fonts.
	wp_enqueue_style( 'hew-fonts', hew_fonts_url(), array(), null );

	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	wp_enqueue_style( 'hew-style', get_stylesheet_uri() );

	wp_enqueue_script( 'hew-scripts', get_template_directory_uri() . '/js/hew.js', array( 'jquery' ), '20140909', true );

	wp_enqueue_script( 'hew-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'hew-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hew_scripts' );

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



