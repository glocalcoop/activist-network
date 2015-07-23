<?php

/**
 * 
 * Register Scripts and Make WP Variables Available to Script
 * 
 **/

function anp_global_menu_register_scripts() {

    global $plugin_url;
    global $anp_global_menu_errors;


    if ( !is_admin() ) {
         wp_register_script( 'anp-global-menu-js', $plugin_url . '/assets/js/anpGlobalMenu.js', array('jquery'), null, true );
    }

    // Retrieve menu options
    $options = get_option( 'anp-global-menu' );

    // Check if anp_global_menu_get_main_menu() returns error
    $return = anp_global_menu_get_main_menu();

    if( !is_wp_error( $return ) ) {

        // Assign values to array that will be made available for jQuery
        $menuData = array(
            'globalMenuStatus' => 'OK',
            'mainSite'  => is_main_site(),
            'mainSiteURL'   => get_blog_details(1)->siteurl,
            'mainSiteName'  => get_blog_details(1)->blogname,
            'globalSiteLogo'    => '<a href="' . get_blog_details(1)->siteurl . '">' . get_blog_details(1)->blogname . '</a>',
            'globalMenu'    => anp_global_menu_get_main_menu(),
            'globalMenuMobile' => '<a class="mobile" href="#">' . get_blog_details(1)->blogname . '</a>',
            'prependMenu'   => '<ul class="nav-anchors js-anchors"><li><a href="#menu-main-navigation" class="anchor-menu" title="menu">' . get_blog_details(1)->blogname . '</a></li><li><a href="#search-global" class="anchor-search" title="search"></a></li></ul>',
        );

    } else {

        // Send error info
        $menuData = array(
            'globalMenuStatus' => 'Error',
            'errorCode' => 'BOO! ' . $return->get_error_code(),
            'errorMessage' => $return->get_error_message(),
        );

    }

    // Make WP variables array available globally available
    // https://codex.wordpress.org/Function_Reference/wp_localize_script
    wp_localize_script( 'anp-global-menu-js', 'anpGlobalMenuVars', $menuData );

    // Only add global nav to sub-sites
    if( !is_main_site() ) {

        $anp_global_menu_errors->add( 'Success', __( __FUNCTION__ . ': This site is not the main site.', 'glocal-global-menu' ) );

        wp_enqueue_script( 'anp-global-menu-js' );

    } else {

        $anp_global_menu_errors->add( 'Wrong Site', __( 'In anp_global_menu_register_scripts(): Logic ( !is_main_site() ) says this is the main site (1); but, the global menu does not get added to the main site. ', 'glocal-global-menu' ) );

    }

    $anp_global_menu_css = $plugin_url . '/assets/css/style.css';

    // Provide filter to override style
    $anp_global_menu_css = apply_filters( 'anp_global_menu_css_override', $anp_global_menu_css );
    
    // Enqueue styles
    wp_enqueue_style( 'anp_global_menu_stylesheet', $anp_global_menu_css);
    
}

add_action( 'wp_enqueue_scripts', 'anp_global_menu_register_scripts' );

/**----------
 * 
 * HELPERS
 * 
 **---------/

/**
 * Get Main Site
 * Returns number id of main site
 **/

function anp_global_menu_get_main_site() {

    global $anp_global_menu_errors;

    $site_args = array(
        'archived'   => 0,
        'spam'       => 0,
        'deleted'    => 0,
    );

    $sites = wp_get_sites( $site_args );

    if( !empty( $sites ) ) {

        foreach( $sites as $site) {

            if( is_main_site( $site['blog_id'] ) ) {

                $main_site_id = $site['blog_id'];

            }

        }

    } else {

        $anp_global_menu_errors->add( 'Variable Undefined', __( __FUNCTION__ . ': Using wp_get_sites( $site_args ), sites list is empty. :(', 'glocal-global-menu' ) );

    }

    if( $main_site_id ) {

        $anp_global_menu_errors->add( 'Success', __( __FUNCTION__ . ': Main site was found.', 'glocal-global-menu' ) );

        return $main_site_id;

    } else {

        $anp_global_menu_errors->add( 'No Main Site Match', __( __FUNCTION__ . ': is_main_site() did not match any site returned in wp_get_sites()', 'glocal-global-menu' ) );

        return new WP_Error( 'No Main Site Match', __( __FUNCTION__ . ': is_main_site() did not match any site returned in wp_get_sites()', 'glocal-global-menu' ) );

    }

}

/**
 * Get List of Menus on Main Site
 **/

function anp_global_menu_get_main_menu() {

    global $anp_global_menu_errors;

    if( function_exists( 'anp_global_menu_get_main_site' ) ) {

        $main_site_id = anp_global_menu_get_main_site();

    } else {

        $anp_global_menu_errors->add( 'Unknown Function', __( 'anp_global_menu_get_main_site() was not found.', 'glocal-global-menu' ) );

    }

    if( !$main_site_id ) {

        $anp_global_menu_errors->add( 'Undefined Variable', __( 'anp_global_menu_get_main_site() did not return a value for $main_site_id. Default of 1 was used.', 'glocal-global-menu' ) );

        $main_site_id = 1;
    } 
    
    // SWITCH TO MAIN SITE TABLE
    switch_to_blog( $main_site_id );

    // Get anp-global-menu options table from the main site
    $options = get_option( 'anp-global-menu' );

    // Get value of anp_global_menu_selected field
    $anp_main_menu = $options['anp_global_menu_selected'];

    if( $anp_main_menu ) {

        // Fetch the menu and assign to $main_menu
        $main_menu = wp_nav_menu( array(
            'container' => false,                           // remove nav container
            'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
            'menu' => $anp_main_menu,                       // nav name
            'menu_class' => 'menu clearfix',                // adding custom nav class
            'menu_id' => 'menu-main-navigation',            // menu id
            'theme_location' => 'network-menu',             // where it's located in the theme
            'before' => '',                                 // before the menu
            'after' => '',                                  // after the menu
            'link_before' => '',                            // before each link
            'link_after' => '',                             // after each link
            'depth' => 0,                                   // limit the depth of the nav
            'fallback_cb' => 'false',                       // fallback function - FALSE
            'echo' => 0
        ) );

        // RESTORE! - SWITCH BACK TO CURRENT SITE TABLE
        restore_current_blog();

        return $main_menu;

    } else {

        // RESTORE! - SWITCH BACK TO CURRENT SITE TABLE
        restore_current_blog();

        $anp_global_menu_errors->add( 'No Menu', __( __FUNCTION__ . ': $options[\'anp_global_menu_selected\'] condition failed meaning it was unset or empty.', 'glocal-global-menu' ) );

        return new WP_Error( 'No Menu', __( __FUNCTION__ . ': $options[\'anp_global_menu_selected\'] condition failed meaning it was unset or empty.', 'glocal-global-menu' ) );
   
    }
    
}




?>