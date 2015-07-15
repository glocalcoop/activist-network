<?php

/************* REGISTER MENUS ***************/

function glocal_register_menus() {
    register_nav_menu('network-menu',__( 'Global Network Menu' ));
    unregister_nav_menu( 'main-nav' );
}
add_action( 'init', 'glocal_register_menus' );

function glocal_global_nav($menu) {
	// display the wp3 menu if available
	wp_nav_menu(array(
		'container' => false,                           // remove nav container
		'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
		'menu' => __( 'Global Network Menu', 'glocal-global-menu' ),  // nav name
		'menu_class' => 'menu clearfix',                // adding custom nav class
        'menu_id' => 'menu-main-navigation',            // menu id
		'theme_location' => $menu,                      // where it's located in the theme
		'before' => '',                                 // before the menu
		'after' => '',                                  // after the menu
		'link_before' => '',                            // before each link
		'link_after' => '',                             // after each link
		'depth' => 0,                                   // limit the depth of the nav
		'fallback_cb' => 'false'                        // fallback function - FALSE
	));
} /* end global nav */


function get_glocal_global_menu($parameters = []) {
    
    /** Default parameters **/
    $defaults = array(
        'site_id' => 1,
        'menu' => 'network-menu',
    );
    
    // Parse & merge parameters with the defaults - http://codex.wordpress.org/Function_Reference/wp_parse_args
    $settings = wp_parse_args( $parameters, $defaults );
    
    // Strip out tags
    foreach($settings as $parameter => $value) {
        // Strip everything
        $settings[$parameter] = strip_tags($value);
    }
    
    // Extract each parameter as its own variable
    extract( $settings, EXTR_SKIP );
    
	//store the current blog_id being viewed
	global $blog_id;
	$current_blog_id = $blog_id;

	//switch to the main blog which will almost always have an ID of 1
	switch_to_blog($site_id);
    
    //get the menu ID for each menu location
    $locations = get_nav_menu_locations();
    //get the menu ID for the location passed into function
    $menu_id = $locations[$menu];
    
    if($menu_id) {
        // Check that the function exists
        if(function_exists('glocal_global_nav')) {
            //output the WordPress navigation menu
            glocal_global_nav($menu);
        }
        else {
            echo "Error: function 'glocal_global_nav' doesn't exist.";
        }
    }
    else {
        echo '<!-- There is no menu in position ' . $menu . ' -->';
    }
    
	//switch back to the current blog being viewed
	switch_to_blog($current_blog_id);
	 
}

/************* CUSTOM WIDGET FOR DISPLAYING SOCIAL LINKS ***************/

// Moved to its own plugin (glocal-social-menu). PEA 1/29/2015

/************* FEATURED IMAGE PREVIEW IN ADMIN ********************/

// add_theme_support( 'post-thumbnails' ); // theme should support
function glocal_add_post_thumbnail_column( $cols ) { // add the thumb column
  // output feature thumb in the end
  //$cols['glocal_post_thumb'] = __( 'Featured image', 'glocal' );
  //return $cols;
  // output feature thumb in a different column position
  $cols_start = array_slice( $cols, 0, 2, true );
  $cols_end   = array_slice( $cols, 2, null, true );
  $custom_cols = array_merge(
    $cols_start,
    array( 'glocal_post_thumb' => __( 'Featured image', 'glocal' ) ),
    $cols_end
  );
  return $custom_cols;
}
add_filter( 'manage_posts_columns', 'glocal_add_post_thumbnail_column', 5 ); // add the thumb column to posts
add_filter( 'manage_pages_columns', 'glocal_add_post_thumbnail_column', 5 ); // add the thumb column to pages

function glocal_display_post_thumbnail_column( $col, $id ) { // output featured image thumbnail
  switch( $col ){
    case 'glocal_post_thumb':
      if( function_exists( 'the_post_thumbnail' ) ) {
        echo the_post_thumbnail( 'thumbnail' );
      } else {
        echo __( 'Not supported in theme', 'glocal' );
      }
      break;
  }
}
add_action( 'manage_posts_custom_column', 'glocal_display_post_thumbnail_column', 5, 2 ); // add the thumb to posts
add_action( 'manage_pages_custom_column', 'glocal_display_post_thumbnail_column', 5, 2 ); // add the thumb to pages

/************* ADD SLUG TO BODY CLASS *****************/

// Add specific CSS class by filter
add_filter('body_class','glocal_class_names');
function glocal_class_names($classes) {
	// add 'class-name' to the $classes array
	global $post; 
	$post_slug_class = $post->post_name; 
	$classes[] = $post_slug_class . ' page-' . $post_slug_class;
	// return the $classes array
	return $classes;
}

/************* CUSTOM EXCERPT *****************/

function get_excerpt_by_id($post_id, $length='55', $trailer=' ...') {
	$the_post = get_post($post_id); //Gets post ID
	$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
	$excerpt_length = $length; //Sets excerpt length by word count
	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);

	if(count($words) > $excerpt_length) :
		array_pop($words);
		$trailer = '<a href="' . get_permalink($post_id) . '">' . $trailer . '</a>';
		array_push($words, $trailer);
		$the_excerpt = implode(' ', $words);
	endif;
	// $the_excerpt = '<p>' . $the_excerpt . '</p>';
	return $the_excerpt;
}


// Hack to fix title on static homepage
add_filter( 'wp_title', 'glocal_hack_wp_title_for_home' );

function glocal_hack_wp_title_for_home( $title ) {
  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
    return __( 'Home', 'glocal' ) . ' | ';
  }
  return $title;
}


/************* GET GLOBAL NAVIGATION FROM MAIN SITE (SITE 1) (MULTISITE) *****************/

function glocal_navigation() {

	//store the current blog_id being viewed
	global $blog_id;
	$current_blog_id = $blog_id;

	//switch to the main blog which will have an id of 1
	switch_to_blog(1);

	//output the WordPress navigation menu
	$glocal_nav = bones_main_nav();

	//switch back to the current blog being viewed
	switch_to_blog($current_blog_id);

	return $glocal_nav;
}

/************* GET CUSTOM HEADER IMAGE FROM NETWORK SITES (MULTISITE) *****************/

function glocal_get_site_image($site_id) {
	//store the current blog_id being viewed
	global $blog_id;
	$current_blog_id = $blog_id;

	//switch to the main blog designated in $site_id
	switch_to_blog($site_id);

	$site_image = get_custom_header();

	//switch back to the current blog being viewed
	switch_to_blog($current_blog_id);

	return $site_image->thumbnail_url;
}



/************* REMOVE BAD CIVI STYLING *****************/

function tc_civicrm_theme_css( ) {
    $tc_css = get_bloginfo( 'stylesheet_directory' ) .'/css/plugins/civicrm.css';
    return $tc_css;
}
add_filter( 'tc_civicss_override', 'tc_civicrm_theme_css' );


?>