<?php

/**
 * ANP Meetings Summaries Post Type
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

/************* CUSTOM POST TYPE*****************/

if ( ! function_exists( 'anp_summary_post_type' ) ) {

	// Register Custom Post Type
	function anp_summary_post_type() {

		$labels = array(
			'name'                => _x( 'Summaries', 'Post Type General Name', 'meeting' ),
			'singular_name'       => _x( 'Summary', 'Post Type Singular Name', 'meeting' ),
			'menu_name'           => __( 'Summaries', 'meeting' ),
			'name_admin_bar'      => __( 'Summaries', 'meeting' ),
			'parent_item_colon'   => __( 'Parent Summary:', 'meeting' ),
			'all_items'           => __( 'All Summaries', 'meeting' ),
			'add_new_item'        => __( 'Add New Summary', 'meeting' ),
			'add_new'             => __( 'Add New Summary', 'meeting' ),
			'new_item'            => __( 'New Summary', 'meeting' ),
			'edit_item'           => __( 'Edit Summary', 'meeting' ),
			'update_item'         => __( 'Update Summary', 'meeting' ),
			'view_item'           => __( 'View Summary', 'meeting' ),
			'search_items'        => __( 'Search Summary', 'meeting' ),
			'not_found'           => __( 'Not found', 'meeting' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'meeting' ),
		);
		$rewrite = array(
			'slug'                => 'summary',
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'Summary', 'meeting' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', ),
			'taxonomies'          => array( 'meeting_type', 'meeting_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 30,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'summaries',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => 'summary',
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( 'summary', $args );

	}
	add_action( 'init', 'anp_summary_post_type', 0 );

}


if ( ! function_exists( 'anp_summary_add_to_menu' ) ) {

    function anp_summary_add_to_menu() { 

        add_submenu_page(
            'edit.php?post_type=meeting', 
            __('All Summaries', 'meeting'), 
            __('All Summaries', 'meeting'), 
            'manage_options', 
            'edit.php?post_type=summary'
        ); 

        add_submenu_page(
            'edit.php?post_type=meeting', 
            __('New Summary', 'meeting'), 
            __('New Summary', 'meeting'), 
            'manage_options', 
            'post-new.php?post_type=summary'
        ); 

    }

    add_action('admin_menu', 'anp_summary_add_to_menu'); 

}

?>