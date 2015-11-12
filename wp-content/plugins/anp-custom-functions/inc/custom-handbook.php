<?php
/**
 * ANP Custom Shortcodes
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Custom
 */

/*
Description: Adds customization to Handbook and Glossary plugins
*/

/*
Add more default styles in the Plugin
*/

/*
Move Glossary to sub-menu of Handbook, so it's contained in the same space
*/

if ( ! function_exists( 'anp_handbook_move_menu' ) ) {

    function anp_handbook_move_menu() { 

        // Remove Add New menu
        remove_submenu_page( 'edit.php?post_type=handbook', 'post-new.php?post_type=handbook' );

        // Add New Handbook Page menu
        add_submenu_page(
            'edit.php?post_type=handbook', 
            __( 'New Handbook Page', 'anp_custom_functions' ), 
            __( 'New Handbook Page', 'anp_custom_functions' ), 
            'manage_options', 
            'post-new.php?post_type=handbook'
        );

        // Remove the menu
        remove_menu_page( 'edit.php?post_type=glossary' );

        // Add Glossary Terms listing as sub-menu of Handbook menu
        add_submenu_page(
            'edit.php?post_type=handbook', 
            __( 'Glossary Terms', 'anp_custom_functions' ), 
            __( 'Glossary Terms', 'anp_custom_functions' ), 
            'manage_options', 
            'edit.php?post_type=glossary'
        );

        // Add New Glossary Item as sub-menu of Handbook menu
        add_submenu_page(
            'edit.php?post_type=handbook', 
            __( 'New Glossary Term', 'anp_custom_functions' ), 
            __( 'New Glossary Term', 'anp_custom_functions' ), 
            'manage_options', 
            'post-new.php?post_type=glossary'
        ); 

    }

    add_action( 'admin_menu', 'anp_handbook_move_menu', 999 ); 

}

/*
Add a nicer icon for the Handbook post-type?
*/
if(! function_exists( 'anp_handbook_replace_admin_icon' ) ) {

    function anp_handbook_replace_admin_icon( $default ) {

        $new_defaults = array(
            'menu_icon' => 'dashicons-book-alt',
            'labels' => array(
                'add_new' => __( 'New Handbook Page', 'wporg' ),
            ),
        );

        $default = array_merge( $new_defaults, $default );

        return $default;

    }

    add_filter( 'handbook_post_type_defaults', 'anp_handbook_replace_admin_icon' );

}

?>