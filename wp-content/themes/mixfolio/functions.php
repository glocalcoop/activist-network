<?php
/**
 * Mixfolio functions and definitions
 *
 * @package Mixfolio
 * @since Mixfolio 1.1
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Mixfolio 1.1
 */
if ( ! isset( $content_width ) )
	$content_width = 637; /* pixels */

if ( ! function_exists( 'mixfolio_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Mixfolio 1.1
 */
function mixfolio_setup() {

	/**
	 * Custom menu functionality for this theme.
	 */
	require( get_template_directory() . '/inc/menus.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Mixfolio, use a find and replace
	 * to change 'mixfolio' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'mixfolio', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 300, 200, true ); // 300 pixels wide by 200 pixels high, hard crop mode
	add_image_size( 'mixfolio-featured-thumbnail', 300, 200, true ); // 300 pixels wide by 200 pixels high, hard crop mode

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'link', 'quote', 'video' ) );

	/**
	 * This theme uses wp_nav_menu() in two locations.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'mixfolio' ),
		'secondary' => __( 'Secondary Menu', 'mixfolio' ),
	) );

	/**
	 * Custom Background
	 */
	add_theme_support( 'custom-background' );
}
endif; // mixfolio_setup
add_action( 'after_setup_theme', 'mixfolio_setup' );

/**
 * Load Mixfolio options
 */
global $mixfolio_options;
$mixfolio_options = get_option( 'mixfolio_theme_options' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Mixfolio 1.1
 */
function mixfolio_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'mixfolio' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'mixfolio_widgets_init' );

/**
 * Enqueue scripts and styles
 *
 * @since Mixfolio 1.1
 */
function mixfolio_scripts() {
	global $mixfolio_options;

	// Theme stylesheet
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'mobile', get_template_directory_uri() . '/css/mobile.css' );

	// Threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Selectivizr - CSS3 pseudo-class and attribute selectors for IE 6-8
	wp_register_script( 'selectivizr', get_template_directory_uri() . '/js/selectivizr-min.js', array( 'jquery' ), '1.0.2' );
		wp_enqueue_script( 'selectivizr' );

	// Reveal: jQuery Modals Made Easy
	if ( isset( $mixfolio_options[ 'mixfolio_display_contact_information' ] ) && 'on' == $mixfolio_options[ 'mixfolio_display_contact_information' ] ) {
		wp_register_script( 'reveal', get_template_directory_uri() . '/js/jquery.reveal.js', array( 'jquery' ), '1.0' );
			wp_enqueue_script( 'reveal' );
	}

	// Enqueue toggle menu for small screens
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	// Tweetable: jQuery twitter feed plugin, https://github.com/philipbeel/Tweetable
	if (
		is_home() &&
		isset( $mixfolio_options[ 'mixfolio_twitter_id' ] ) && '' != $mixfolio_options[ 'mixfolio_twitter_id' ] &&
		isset( $mixfolio_options[ 'mixfolio_display_welcome_area' ] ) && 'on' == $mixfolio_options[ 'mixfolio_display_welcome_area' ]
	) {
		wp_register_script( 'tweetable', get_template_directory_uri() . '/js/tweetable.jquery.js', array( 'jquery' ), '2.0' );
			wp_enqueue_script( 'tweetable' );
	}

	// FitVids.js: A lightweight, easy-to-use jQuery plugin for fluid width video embeds.
	wp_register_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0' );
		wp_enqueue_script( 'fitvids' );

	// Mixfolio custom JS
	wp_register_script( 'core', get_template_directory_uri() . '/js/jquery.core.js' );
		wp_enqueue_script( 'core' );

}
add_action( 'wp_enqueue_scripts', 'mixfolio_scripts' );

/**
 * Implement the Custom Header feature
 *
 * @since Mixfolio 1.1
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Show tweets in Welcome Area if active
 */
function mixfolio_welcome_area_tweets() {
	if ( ! is_home() )
		return;

	global $mixfolio_options;
	if (
		isset( $mixfolio_options[ 'mixfolio_twitter_id' ] ) && '' != $mixfolio_options[ 'mixfolio_twitter_id' ] &&
		isset( $mixfolio_options[ 'mixfolio_display_welcome_area' ] ) && 'on' == $mixfolio_options[ 'mixfolio_display_welcome_area' ]
	) : ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ){
				$( '#tweets' ).tweetable({
					limit: 1,
					username: '<?php echo esc_attr( $mixfolio_options[ 'mixfolio_twitter_id' ] ); ?>',
					replies: true
				});
			});
		</script><?php
	endif;
}
add_action( 'wp_head', 'mixfolio_welcome_area_tweets' );

if ( ! function_exists( 'mixfolio_custom_background_check' ) ) :
/*
 * Disable text shadows if the user manually sets a custom background color
 */
function mixfolio_custom_background_check() {

	if ( '' != get_background_color() ) : ?>
		<style type="text/css">
			.commentlist,
			#comments,
			#respond {
				text-shadow: none;
			}
		</style>
	<?php endif;
}
endif;

add_action( 'wp_head', 'mixfolio_custom_background_check' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 */
function mixfolio_content_width() {
	if ( is_page_template( 'full-width-page.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) || has_post_format( 'gallery' ) || has_post_format( 'image' ) || has_post_format( 'video' ) ) {
		global $content_width;
		$content_width = 980;
	}
}
add_action( 'template_redirect', 'mixfolio_content_width' );
