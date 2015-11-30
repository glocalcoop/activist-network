<?php
/**
 * Sequential functions and definitions
 *
 * @package Sequential
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 700; /* pixels */
}

if ( ! function_exists( 'sequential_content_width' ) ) :

	function sequential_content_width() {
		global $content_width;

		if ( is_page_template( 'page-templates/front-page.php' ) || is_page_template( 'page-templates/full-width-page.php' ) || is_page_template( 'page-templates/grid-page.php' ) || is_post_type_archive( 'jetpack-testimonial' ) ) {
			$content_width = 1086;
		}
	}

endif;
add_action( 'template_redirect', 'sequential_content_width' );

if ( ! function_exists( 'sequential_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sequential_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Sequential, use a find and replace
	 * to change 'sequential' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sequential', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'sequential-featured-image', 772, 9999 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sequential' ),
		'footer'  => __( 'Footer Menu', 'sequential' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Excerpt on Pages.
	 * See http://codex.wordpress.org/Excerpt
	 */
	add_post_type_support( 'page', 'excerpt' );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'status', 'gallery',
	) );

	/**
	 * Suggest the Jetpack plugin to users
	 */
	add_theme_support( 'theme-plugin-enhancements', array(
		array(
			'slug'    => 'jetpack',
			'name'    => 'Jetpack by WordPress.com',
			'message' => __( 'The Jetpack plugin is needed to use some of Sequential\'s special features, including the testimonial custom post type (Custom Content Types module), and site logo (no particular module activation needed).', 'sequential' ),
		),
	) );
}
endif; // sequential_setup
add_action( 'after_setup_theme', 'sequential_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sequential_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'sequential' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer', 'sequential' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'sequential_widgets_init' );

/**
 * Register Montserrat font.
 *
 * @return string
 */
function sequential_montserrat_font_url() {
	$montserrat_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Montserrat, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'sequential' ) ) {
		$query_args = array(
			'family' => urlencode( 'Montserrat:400,700' ),
		);

		$montserrat_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $montserrat_font_url;
}

/**
 * Register Open Sans font.
 *
 * @return string
 */
function sequential_open_sans_font_url() {
	$open_sans_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'sequential' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'sequential' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' == $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}

		$query_args = array(
			'family' => urlencode( 'Open Sans:300italic,400italic,600italic,700italic,300,400,600,700' ),
			'subset' => urlencode( $subsets ),
		);

		$open_sans_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $open_sans_font_url;
}

/**
 * Register Source Code Pro font.
 *
 * @return string
 */
function sequential_source_code_pro_font_url() {
	$source_code_pro_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Source Code Pro, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Source Code Pro font: on or off', 'sequential' ) ) {
		$query_args = array(
			'family' => urlencode( 'Source Code Pro:400,700' ),
		);

		$source_code_pro_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $source_code_pro_font_url;
}

/**
 * Enqueue scripts and styles.
 */
function sequential_scripts() {
	wp_enqueue_style( 'sequential-montserrat', sequential_montserrat_font_url(), array(), null );

	wp_enqueue_style( 'sequential-open-sans', sequential_open_sans_font_url(), array(), null );

	wp_enqueue_style( 'sequential-source-code-pro', sequential_source_code_pro_font_url(), array(), null );

	if ( wp_style_is( 'genericons', 'registered' ) ) {
		wp_enqueue_style( 'genericons' );
	} else {
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );
	}

	wp_enqueue_style( 'sequential-style', get_stylesheet_uri() );

	wp_enqueue_script( 'sequential-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery', 'underscore' ), '20141022', true );

	wp_enqueue_script( 'sequential-thumbnail', get_template_directory_uri() . '/js/thumbnail.js', array( 'jquery', 'underscore' ), '20141022', true );

	wp_enqueue_script( 'sequential-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'sequential-script', get_template_directory_uri() . '/js/sequential.js', array( 'jquery' ), '20141022', true );
}
add_action( 'wp_enqueue_scripts', 'sequential_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @return void
 */
function sequential_admin_fonts() {
	wp_enqueue_style( 'sequential-montserrat', sequential_montserrat_font_url(), array(), null );

	wp_enqueue_style( 'sequential-open-sans', sequential_open_sans_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'sequential_admin_fonts' );

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
 * Require our Theme Plugin Enhancements class.
 */
require get_template_directory() . '/inc/plugin-enhancements.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
