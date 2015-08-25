<?php
/**
 * adaption functions and definitions
 *
 * @package Adaption
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 680; /* pixels */
}

if ( ! function_exists( 'adaption_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function adaption_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'adaption', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'featured-image', 1380, 9999 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'adaption' ),
		'social'  => __( 'Social Links Menu', 'adaption' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'adaption_custom_background_args', array(
		'default-color' => 'fafafa',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );

	// Adds editor support
	add_editor_style( array( 'editor-style.css', 'fonts/genericons.css', arimo_font_url() ) );

}
endif;
add_action( 'after_setup_theme', 'adaption_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function adaption_widgets_init() {
	// Sidebar under navigation
	register_sidebar( array(
		'name'          => __( 'Sidebar One', 'adaption' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	// Secondary sidebar panel
	register_sidebar( array(
		'name'          => __( 'Sidebar Two', 'adaption' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	// Mobile only
	register_sidebar( array(
		'name'          => __( 'Mobile Widgets', 'adaption' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'adaption_widgets_init' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * @since adaption 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function arimo_font_url() {

	$arimo_font_url = '';

	/* translators: If there are characters in your language that are not supported
	   by Arimo, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Arimo sans-serif font: on or off', 'adaption' ) ) {

		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Armio character subsets,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'.
		 * Do not translate these strings into your own langauge.
		 */
		$subset = _x( 'no-subset', 'Arimo: add new subset (cyrillic, greek, vietnamese)', 'adaption' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' == $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}

		$query_args = array(
			'family' => urlencode( 'Arimo:400,400italic,700,700italic' ),
			'subset' => urlencode( $subsets ),
		);

		$arimo_font_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

	}

	return $arimo_font_url;
}

/**
 * Enqueue Google Fonts for admin
 */
function adaption_admin_fonts() {
	wp_enqueue_style( 'adaption-adminarimo', arimo_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'adaption_admin_fonts' );

/**
 * Enqueue scripts and styles.
 */
function adaption_scripts() {

	wp_enqueue_style( 'adaption-arimo', arimo_font_url(), array(), null );

	wp_enqueue_style( 'adaption-style', get_stylesheet_uri() );

	wp_enqueue_script( 'adaption-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_enqueue_script( 'adaption-scripts', get_template_directory_uri() . '/js/adaption.js', array('jquery'), '20142202', true );

	// Add Genericons font, used in the main stylesheet.
	if ( wp_style_is( 'genericons', 'registered' ) )
		wp_enqueue_style( 'genericons' );
	else
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons.css', array(), null );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'adaption_scripts' );

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
