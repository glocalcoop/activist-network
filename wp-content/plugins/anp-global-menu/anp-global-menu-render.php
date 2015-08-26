<?php

/**
 * 
 * Register Scripts and Localize Data to Scripts
 * @return array of key / value pairs and localize to registered script
 * 
 **/

function anp_global_menu_register_scripts() {


    if ( !is_admin() ) {
         wp_register_script( 'anp-global-menu-js', ANP_GLOBAL_MENU_URL . '/assets/js/anpGlobalMenu.js', array('jquery'), null, true );
    }

    // Retrieve menu name stored in wp_sitemeta - don't think this is needed.
    $options = get_site_option( 'anp-global-nav-menu' );

    // Get menu data
    $anp_global_menu = anp_global_menu_get_main_menu();

    if( $anp_global_menu ) {

        // Assign values to array that will be made available for jQuery
        $menuData = array(
            'status'            => 'OK',
            'networkURL'        => get_blog_details(1)->siteurl,
            'networkName'       => get_blog_details(1)->blogname,
            'networkLogo'       => '<a href="' . get_blog_details(1)->siteurl . '">' . get_blog_details(1)->blogname . '</a>',
            'globalMenu'        => $anp_global_menu,
        );

    } else {

        // Send error info
        $menuData = array(
            'status'            => 'Error',
            'errorCode'         => 'No Menu',
            'errorMessage'      => 'No menu was returned',
        );

    }

    // Make WP variables array available globally available
    // https://codex.wordpress.org/Function_Reference/wp_localize_script
    wp_localize_script( 'anp-global-menu-js', 'anpGlobalMenuVars', $menuData );

    // Only add global nav to sub-sites
    if( !is_main_site() ) {

        wp_enqueue_script( 'anp-global-menu-js' );

    } else {

        // Error

    }

    $anp_global_menu_css = ANP_GLOBAL_MENU_URL . '/assets/css/style.min.css';

    // Provide filter to override style
    $anp_global_menu_css = apply_filters( 'anp_global_menu_css_override', $anp_global_menu_css );
    
    // Enqueue styles
    wp_enqueue_style( 'anp_global_menu_stylesheet', $anp_global_menu_css);
    
}

add_action( 'wp_enqueue_scripts', 'anp_global_menu_register_scripts' );


/**
 *
 * Get Global Menu
 * @return string of menu markup or null
 * @param integer value for echo - 0 or 1
 *
 **/

function anp_global_menu_get_main_menu( $echo_menu = 0 ) {

    $main_site_id = 1;

    if ( false === ( $main_menu = get_site_transient( 'anp_global_menu_markup' ) ) ) {
    // If there is no valid transient set

        // Get anp-global-menu options table from the main site
        $anp_main_menu = get_site_option( 'anp-global-nav-menu' );

        // SWITCH TO MAIN SITE TABLE
        switch_to_blog( $main_site_id );

        if( $anp_main_menu ) {

            // Fetch the menu and assign to $main_menu
            $main_menu = wp_nav_menu( array(
                'container'         => false,                           // remove nav container
                'menu'              => $anp_main_menu,                  // nav name
                'menu_class'        => 'global-menu menu clearfix',     // adding custom nav class
                'menu_id'           => '',                              // menu id
                'depth'             => 0,                               // limit the depth of the nav
                'fallback_cb'       => 'false',                         // fallback function - FALSE
                'echo'              => $echo_menu
            ) );

            // RESTORE! - SWITCH BACK TO CURRENT SITE TABLE
            restore_current_blog();

        }

        set_site_transient( 'anp_global_menu_markup', $main_menu, 28800 );

    }

    return $main_menu;

}


/**
 *
 * Template Tag
 * Menu to template
 * @return echo the global menu
 *
 **/

function anp_show_global_menu() {

    echo anp_global_menu_get_main_menu();

}

?>