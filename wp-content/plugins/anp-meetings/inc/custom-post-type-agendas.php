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
            'name'                => _x( 'Agendas', 'Post Type General Name', 'anp_meetings' ),
            'singular_name'       => _x( 'Agenda', 'Post Type Singular Name', 'anp_meetings' ),
            'menu_name'           => __( 'Agendas', 'anp_meetings' ),
            'name_admin_bar'      => __( 'Agenda', 'anp_meetings' ),
            'parent_item_colon'   => __( 'Parent Agenda:', 'anp_meetings' ),
            'all_items'           => __( 'All Agenda', 'anp_meetings' ),
            'add_new_item'        => __( 'Add New Agenda', 'anp_meetings' ),
            'add_new'             => __( 'Add New Agenda', 'anp_meetings' ),
            'new_item'            => __( 'New Agenda', 'anp_meetings' ),
            'edit_item'           => __( 'Edit Agenda', 'anp_meetings' ),
            'update_item'         => __( 'Update Agenda', 'anp_meetings' ),
            'view_item'           => __( 'View Agenda', 'anp_meetings' ),
            'search_items'        => __( 'Search Agenda', 'anp_meetings' ),
            'not_found'           => __( 'Not found', 'anp_meetings' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'anp_meetings' ),
        );
        $rewrite = array(
            'slug'                => 'agenda',
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $args = array(
            'label'               => __( 'Agenda', 'anp_meetings' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author', ),
            'taxonomies'          => array( 'anp_meetings_type', 'anp_meetings_tag' ),
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
        register_post_type( 'anp_agenda', $args );

    }
    add_action( 'init', 'anp_agenda_post_type', 0 );

}

if ( ! function_exists( 'anp_agenda_add_to_menu' ) ) {

    function anp_agenda_add_to_menu() { 

        add_submenu_page(
            'edit.php?post_type=anp_meetings', 
            __('All Agendas', 'anp_meetings'), 
            __('All Agendas', 'anp_meetings'), 
            'manage_options', 
            'edit.php?post_type=anp_agenda'
        ); 

        add_submenu_page(
            'edit.php?post_type=anp_meetings', 
            __('New Agenda', 'anp_meetings'), 
            __('New Agenda', 'anp_meetings'), 
            'manage_options', 
            'post-new.php?post_type=anp_agenda'
        ); 

    }

    add_action('admin_menu', 'anp_agenda_add_to_menu'); 

}

?>