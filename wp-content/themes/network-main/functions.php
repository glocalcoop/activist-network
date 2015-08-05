<?php
/*
Author: Pea, Glocal
URL: htp://glocal.coop
*/

/************* FUNCTION TO CHECK IF PLUGINS ARE ACTIVE ***************/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once( 'library/bones.php' ); // if you remove this, bones will break
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
// require_once( 'library/custom-post-type.php' ); // you can disable this if you like
/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
// require_once( 'library/admin.php' ); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once( 'library/translation/translation.php' ); // this comes turned off by default


require_once( 'library/custom-functions.php' );

/**
 * Customizer additions.
 */
require get_template_directory() . '/library/customizer.php';


/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
add_image_size( 'bones-thumb-150', 150, 150, true );
/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Primary', 'glocal-theme' ),
		'description' => __( 'First (primary) sidebar.', 'glocal-theme' ),
		'before_widget' => '<div id="%1$s" class="widget primary %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'home-modules',
		'name' => __( 'Homepage Modules', 'glocal-theme' ),
		'description' => __( 'Modules for the Homepage', 'glocal-theme' ),
		'before_widget' => '<article id="%1$s" class="module row %2$s">',
		'after_widget' => '</article>',
		'before_title' => '<h2 class="module-heading">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'id' => 'home-sidebar',
		'name' => __( 'Home Sidebar', 'glocal-theme' ),
		'description' => __( 'A homepage widget area.', 'glocal-theme' ),
		'before_widget' => '<div id="%1$s" class="home-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="module-heading">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'footer1',
		'name' => __( 'Footer 1', 'glocal-theme' ),
		'description' => __( 'First footer widget area.', 'glocal-theme' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget-1 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'footer2',
		'name' => __( 'Footer 2', 'glocal-theme' ),
		'description' => __( 'Second footer widget area.', 'glocal-theme' ),
		'before_widget' => '<nav id="%1$s" class="widget footer-widget-2 %2$s">',
		'after_widget' => '</nav>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'footer3',
		'name' => __( 'Footer 3', 'glocal-theme' ),
		'description' => __( 'Third footer widget area.', 'glocal-theme' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget-3 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'footer4',
		'name' => __( 'Footer 4', 'glocal-theme' ),
		'description' => __( 'Fourth footer widget area.', 'glocal-theme' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget-4 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'social',
		'name' => __( 'Social Widget', 'glocal-theme' ),
		'description' => __( 'Widget area for social links.', 'glocal-theme' ),
		'before_widget' => '<div id="%1$s" class="social-links %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	/*

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!

/*********************
MENUS & NAVIGATION
*********************/

// wp menus
add_theme_support( 'menus' );

// // registering wp3+ menus
// register_nav_menus(
// 	array(
// 		'secondary-nav' => __( 'The Secondary Menu', 'glocal-theme' ),   // secondary nav in header
// 		'utility-nav' => __( 'The Utility Menu', 'glocal-theme' ),   // utility nav in header
// 	)
// );

// // the secondary menu
// function bones_secondary_nav() {
// 	// display the wp3 menu if available
// 	wp_nav_menu(array(
// 		'container' => false,                           // remove nav container
// 		'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
// 		'menu' => __( 'The Secondary Menu', 'glocal-theme' ),  // nav name
// 		'menu_class' => 'nav second-nav clearfix',         // adding custom nav class
// 		'theme_location' => 'second-nav',                 // where it's located in the theme
// 		'before' => '',                                 // before the menu
// 		'after' => '',                                  // after the menu
// 		'link_before' => '',                            // before each link
// 		'link_after' => '',                             // after each link
// 		'depth' => 0                                  // limit the depth of the nav
// 	));
// } /* end bones secondary nav */

// // the utility menu
// function bones_utility_nav() {
// 	// display the wp3 menu if available
// 	wp_nav_menu(array(
// 		'container' => false,                           // remove nav container
// 		'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
// 		'menu' => __( 'Utility Menu', 'glocal-theme' ),  // nav name
// 		'menu_class' => 'nav utility-nav clearfix',         // adding custom nav class
// 		'theme_location' => 'utility-nav',                 // where it's located in the theme
// 		'before' => '',                                 // before the menu
// 		'after' => '',                                  // after the menu
// 		'link_before' => '',                            // before each link
// 		'link_after' => '',                             // after each link
// 		'depth' => 0                                  // limit the depth of the nav
// 	));
// } /* end bones secondary nav */


/*************************
ADD THEME SUPPORT
*************************/

if ( ! function_exists('glocal_theme_features') ) {

// Register Theme Features
function glocal_theme_features()  {

	// Add theme support for Post Formats
	$formats = array( 'gallery', 'image', 'video', 'link', 'aside', );
	add_theme_support( 'post-formats', $formats );	

	// Add theme support for Semantic Markup
	$markup = array( 'search-form', );
	add_theme_support( 'html5', $markup );	

	// Add theme support for custom header
	add_theme_support( 'custom-header' );

	// Add theme support for Translation
	load_theme_textdomain( 'glocal', get_template_directory() . '/library/language' );	
}

// Hook into the 'after_setup_theme' action
add_action( 'after_setup_theme', 'glocal_theme_features' );

}

/**************************************
ENQUEUE AND REGISTER SCRIPTS AND STYLES
***************************************/


function glocal_scripts_and_styles() {

	// Load hosted version of jquery for template using isotope only
	if(is_page_template('page-directory.php')) {
		// Deregister WP jquery
		wp_deregister_script( 'jquery' );
		// wp_deregister_script( 'jquery-ui-draggable' );

		// Hosted jquery
		// wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.js', array(), '', true );
		wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.js', false, null);

		// Enqueue style
	    wp_enqueue_script( 'jquery' );
	}

	// Responsive Slider Script
	wp_register_script( 'responsive-slider-script', get_template_directory_uri() . '/library/boxslider/jquery.bxslider.min.js', array(), '', true );

	// Responsive Slider Styles
	wp_register_style( 'responsive-slider-stylesheet', get_template_directory_uri() . '/library/boxslider/jquery.bxslider.css');

	// Isotype Script
	wp_register_script( 'isotope-script', get_template_directory_uri() . '/library/js/isotope.pkgd.min.js', array(), '', true );
	
	// Civi Theme Stylesheet
	wp_register_style( 'glocal-civicrm', get_template_directory_uri() . '/library/css/plugins/civicrm.css');

	// Main Theme Stylesheet
	wp_register_style( 'glocal-stylesheet', get_template_directory_uri() . '/library/css/style.css');
	

	

	// enqueue styles and scripts
    wp_enqueue_script( 'responsive-slider-script' );
    wp_enqueue_script( 'isotope-script' );

    wp_enqueue_style( 'responsive-slider-stylesheet' );
	wp_enqueue_style( 'glocal-civicrm' );
    wp_enqueue_style( 'glocal-stylesheet' );

}

if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'glocal_scripts_and_styles' );

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<?php // custom gravatar call ?>
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<?php // end custom gravatar call ?>
				<?php printf(__( '<cite class="fn">%s</cite>', 'glocal-theme' ), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'glocal-theme' )); ?> </a></time>
				<?php edit_comment_link(__( '(Edit)', 'glocal-theme' ),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<div class="alert alert-info">
					<p><?php _e( 'Your comment is awaiting moderation.', 'glocal-theme' ) ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
	<?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
	$form = '<form role="search" class="search reveal" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __( 'Search for:', 'glocal-theme' ) . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__( 'Search', 'glocal-theme' ) . '" />
	<button type="submit" id="searchsubmit"></button>
	</form>';
	return $form;
} // don't remove this bracket!


/************* REMOVE UNWANTED THEME SUPPORT OPTIONS *****************/
// http://codex.wordpress.org/Function_Reference/add_theme_support
// http://codex.wordpress.org/Function_Reference/remove_theme_support

function glocal_custom_theme_support() {

	// Turn off support for custom background
	remove_theme_support( 'custom-background' );

	// Turn on support for custom header image
	add_theme_support( 'custom-header', array(
		'header-text' => false
	) );

}
add_action( 'after_setup_theme', 'glocal_custom_theme_support', 60 );



?>
