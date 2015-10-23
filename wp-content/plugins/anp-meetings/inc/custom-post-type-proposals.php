<?php

/**
 * ANP Meetings Proposals Post Type
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

/************* CUSTOM POST TYPE*****************/

if ( ! function_exists('anp_proposals_post_type') ) {

    // Register Custom Post Type
    function anp_proposals_post_type() {

        $labels = array(
            'name'                => _x( 'Proposals', 'Post Type General Name', 'anp_meetings' ),
            'singular_name'       => _x( 'Proposal', 'Post Type Singular Name', 'anp_meetings' ),
            'menu_name'           => __( 'Proposals', 'anp_meetings' ),
            'name_admin_bar'      => __( 'Proposals', 'anp_meetings' ),
            'parent_item_colon'   => __( 'Parent Proposal:', 'anp_meetings' ),
            'all_items'           => __( 'All Proposals', 'anp_meetings' ),
            'add_new_item'        => __( 'Add New Proposal', 'anp_meetings' ),
            'add_new'             => __( 'Add Proposal', 'anp_meetings' ),
            'new_item'            => __( 'New Proposal', 'anp_meetings' ),
            'edit_item'           => __( 'Edit Proposal', 'anp_meetings' ),
            'update_item'         => __( 'Update Proposal', 'anp_meetings' ),
            'view_item'           => __( 'View Proposal', 'anp_meetings' ),
            'search_items'        => __( 'Search Proposal', 'anp_meetings' ),
            'not_found'           => __( 'Not found', 'anp_meetings' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'anp_meetings' ),
        );
        $rewrite = array(
            'slug'                => 'proposal',
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $args = array(
            'label'               => __( 'Proposal', 'anp_meetings' ),
            'description'         => __( '', 'anp_meetings' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', ),
            'taxonomies'          => array( 
                'anp_proposal_status', 
                'anp_meetings_type', 
                'anp_meetings_tag' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'menu_position'       => 30,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => 'proposals',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'query_var'           => 'proposal',
            'rewrite'             => $rewrite,
            'capability_type'     => 'page',
        );
        register_post_type( 'anp_proposal', $args );

    }
    add_action( 'init', 'anp_proposals_post_type', 0 );

}


if ( ! function_exists( 'anp_proposals_status_taxonomy' ) ) {

    // Register Custom Taxonomy
    function anp_proposals_status_taxonomy() {

        $labels = array(
            'name'                       => _x( 'Proposal Statuses', 'Taxonomy General Name', 'anp_meetings' ),
            'singular_name'              => _x( 'Proposal Status', 'Taxonomy Singular Name', 'anp_meetings' ),
            'menu_name'                  => __( 'Proposal Statuses', 'anp_meetings' ),
            'all_items'                  => __( 'All Proposal Statuses', 'anp_meetings' ),
            'parent_item'                => __( 'Parent Proposal Status', 'anp_meetings' ),
            'parent_item_colon'          => __( 'Parent Proposal Status:', 'anp_meetings' ),
            'new_item_name'              => __( 'New Proposal Status Name', 'anp_meetings' ),
            'add_new_item'               => __( 'Add New Proposal Status', 'anp_meetings' ),
            'edit_item'                  => __( 'Edit Proposal Status', 'anp_meetings' ),
            'update_item'                => __( 'Update Proposal Status', 'anp_meetings' ),
            'view_item'                  => __( 'View Proposal Status', 'anp_meetings' ),
            'separate_items_with_commas' => __( 'Separate proposal status with commas', 'anp_meetings' ),
            'add_or_remove_items'        => __( 'Add or remove proposal status', 'anp_meetings' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'anp_meetings' ),
            'popular_items'              => __( 'Popular Proposal Statuses', 'anp_meetings' ),
            'search_items'               => __( 'Search Proposal Status', 'anp_meetings' ),
            'not_found'                  => __( 'Not Found', 'anp_meetings' ),
        );
        $rewrite = array(
            'slug'                       => 'proposal-statuses',
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => false,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'query_var'                  => 'proposal_status',
            'rewrite'                    => $rewrite,
        );
        register_taxonomy( 'anp_proposal_status', array( 'anp_proposal' ), $args );

    }
    add_action( 'init', 'anp_proposals_status_taxonomy', 0 );

}

if ( ! function_exists( 'anp_proposals_add_to_menu' ) ) {

    function anp_proposals_add_to_menu() { 

        //edit.php?post_type=anp_meetings

        add_submenu_page(
            'edit.php?post_type=anp_meetings', 
            __('All Proposals', 'anp_meetings'), 
            __('All Proposals', 'anp_meetings'), 
            'manage_options', 
            'edit.php?post_type=anp_proposal'
        ); 

        add_submenu_page(
            'edit.php?post_type=anp_meetings', 
            __('New Proposal', 'anp_meetings'), 
            __('New Proposal', 'anp_meetings'), 
            'manage_options', 
            'post-new.php?post_type=anp_proposal'
        ); 

        add_submenu_page(
            'edit.php?post_type=anp_meetings', 
            __('Proposal Statuses', 'anp_meetings'), 
            __('Proposal Statuses', 'anp_meetings'), 
            'manage_options', 
            'edit-tags.php?taxonomy=anp_proposal_status&post_type=anp_proposal'
        ); 

    }

    add_action('admin_menu', 'anp_proposals_add_to_menu'); 

}


?>