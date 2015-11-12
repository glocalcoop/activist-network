<?php

/**
 * ANP Meetings Agenda Post Type
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */


if ( ! function_exists('anp_agenda_post_type') ) {

    // Register Custom Post Type
    function anp_agenda_post_type() {

        $labels = array(
            'name'                => _x( 'Agendas', 'Post Type General Name', 'meeting' ),
            'singular_name'       => _x( 'Agenda', 'Post Type Singular Name', 'meeting' ),
            'menu_name'           => __( 'Agendas', 'meeting' ),
            'name_admin_bar'      => __( 'Agenda', 'meeting' ),
            'parent_item_colon'   => __( 'Parent Agenda:', 'meeting' ),
            'all_items'           => __( 'All Agenda', 'meeting' ),
            'add_new_item'        => __( 'Add New Agenda', 'meeting' ),
            'add_new'             => __( 'Add New Agenda', 'meeting' ),
            'new_item'            => __( 'New Agenda', 'meeting' ),
            'edit_item'           => __( 'Edit Agenda', 'meeting' ),
            'update_item'         => __( 'Update Agenda', 'meeting' ),
            'view_item'           => __( 'View Agenda', 'meeting' ),
            'search_items'        => __( 'Search Agenda', 'meeting' ),
            'not_found'           => __( 'Not found', 'meeting' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'meeting' ),
        );
        $rewrite = array(
            'slug'                => 'agenda',
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $args = array(
            'label'               => __( 'Agenda', 'meeting' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author', ),
            'taxonomies'          => array( 'meeting_type', 'meeting_tag' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'menu_position'       => 5,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => 'agendas',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'query_var'           => 'agenda',
            'rewrite'             => $rewrite,
            'capability_type'     => 'page',
        );
        register_post_type( 'agenda', $args );

    }
    add_action( 'init', 'anp_agenda_post_type', 0 );

}

if ( ! function_exists( 'anp_agenda_add_to_menu' ) ) {

    function anp_agenda_add_to_menu() { 

        add_submenu_page(
            'edit.php?post_type=meeting', 
            __('All Agendas', 'meeting'), 
            __('All Agendas', 'meeting'), 
            'manage_options', 
            'edit.php?post_type=agenda'
        ); 

        add_submenu_page(
            'edit.php?post_type=meeting', 
            __('New Agenda', 'meeting'), 
            __('New Agenda', 'meeting'), 
            'manage_options', 
            'post-new.php?post_type=agenda'
        ); 

    }

    add_action('admin_menu', 'anp_agenda_add_to_menu'); 

}

?>