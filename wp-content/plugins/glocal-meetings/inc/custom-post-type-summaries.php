<?php

/************* CUSTOM POST TYPE*****************/

if ( ! function_exists('anp_summary_post_type') ) {

	// Register Custom Post Type
	function anp_summary_post_type() {

		$labels = array(
			'name'                => _x( 'Summaries', 'Post Type General Name', 'anp_meetings' ),
			'singular_name'       => _x( 'Summary', 'Post Type Singular Name', 'anp_meetings' ),
			'menu_name'           => __( 'Summaries', 'anp_meetings' ),
			'name_admin_bar'      => __( 'Summaries', 'anp_meetings' ),
			'parent_item_colon'   => __( 'Parent Summary:', 'anp_meetings' ),
			'all_items'           => __( 'All Summaries', 'anp_meetings' ),
			'add_new_item'        => __( 'Add New Summary', 'anp_meetings' ),
			'add_new'             => __( 'Add New Summary', 'anp_meetings' ),
			'new_item'            => __( 'New Summary', 'anp_meetings' ),
			'edit_item'           => __( 'Edit Summary', 'anp_meetings' ),
			'update_item'         => __( 'Update Summary', 'anp_meetings' ),
			'view_item'           => __( 'View Summary', 'anp_meetings' ),
			'search_items'        => __( 'Search Summary', 'anp_meetings' ),
			'not_found'           => __( 'Not found', 'anp_meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'anp_meetings' ),
		);
		$rewrite = array(
			'slug'                => 'summary',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'Summary', 'anp_meetings' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', ),
			'taxonomies'          => array( 'anp_meetings_type', 'anp_meetings_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=anp_meetings',
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
		register_post_type( 'anp_summary', $args );

	}
	add_action( 'init', 'anp_summary_post_type', 0 );

}


if ( ! function_exists( 'anp_summary_add_to_menu' ) ) {

    function anp_summary_add_to_menu() { 

        // add_submenu_page('edit.php?post_type=anp_meetings', 'New Summary', 'New Summary', 'manage_options', 'post-new.php?post_type=anp_summary'); 

    }

    add_action('admin_menu', 'anp_summary_add_to_menu'); 

}

?>