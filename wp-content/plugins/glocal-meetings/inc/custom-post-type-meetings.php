<?php

/************* CUSTOM POST TYPE*****************/

if ( ! function_exists('anp_meetings_post_type') ) {

	// Register Custom Post Type - Meeting
	function anp_meetings_post_type() {

		$labels = array(
			'name'                => _x( 'Meetings', 'Post Type General Name', 'anp_meetings' ),
			'singular_name'       => _x( 'Meeting', 'Post Type Singular Name', 'anp_meetings' ),
			'menu_name'           => __( 'Meetings', 'anp_meetings' ),
			'name_admin_bar'      => __( 'Meetings', 'anp_meetings' ),
			'parent_item_colon'   => __( 'Parent Meeting:', 'anp_meetings' ),
			'all_items'           => __( 'All Meetings', 'anp_meetings' ),
			'add_new_item'        => __( 'Add New Meeting', 'anp_meetings' ),
			'add_new'             => __( 'Add New Meeting', 'anp_meetings' ),
			'new_item'            => __( 'New Meeting', 'anp_meetings' ),
			'edit_item'           => __( 'Edit Meeting', 'anp_meetings' ),
			'update_item'         => __( 'Update Meeting', 'anp_meetings' ),
			'view_item'           => __( 'View Meeting', 'anp_meetings' ),
			'search_items'        => __( 'Search Meeting', 'anp_meetings' ),
			'not_found'           => __( 'Not found', 'anp_meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'anp_meetings' ),
		);
		$rewrite = array(
			'slug'                => 'meetings',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'anp_meetings', 'anp_meetings' ),
			'description'         => __( 'Custom post type for meeting agendas and notes', 'anp_meetings' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'comments', 'custom-fields', 'wpcom-markdown' ),
			'taxonomies'          => array( 'anp_meetings_type', 'anp_meetings_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-clipboard',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => 'meeting',
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( 'anp_meetings', $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'anp_meetings_post_type', 0 );

}


/************* CUSTOM TAXONOMIES *****************/

if ( ! function_exists( 'anp_meetings_type' ) ) {

	// Register Custom Taxonomy
	function anp_meetings_type() {

		$labels = array(
			'name'                       => _x( 'Meeting Type', 'Taxonomy General Name', 'anp_meetings' ),
			'singular_name'              => _x( 'Meeting Type', 'Taxonomy Singular Name', 'anp_meetings' ),
			'menu_name'                  => __( 'Meeting Types', 'anp_meetings' ),
			'all_items'                  => __( 'All Meeting Types', 'anp_meetings' ),
			'parent_item'                => __( 'Parent Meeting Type', 'anp_meetings' ),
			'parent_item_colon'          => __( 'Parent Meeting Type:', 'anp_meetings' ),
			'new_item_name'              => __( 'New Meeting Type Name', 'anp_meetings' ),
			'add_new_item'               => __( 'Add New Meeting Type', 'anp_meetings' ),
			'edit_item'                  => __( 'Edit Meeting Type', 'anp_meetings' ),
			'update_item'                => __( 'Update Meeting Type', 'anp_meetings' ),
			'view_item'                  => __( 'View Meeting Type', 'anp_meetings' ),
			'separate_items_with_commas' => __( 'Separate meeting types with commas', 'anp_meetings' ),
			'add_or_remove_items'        => __( 'Add or remove meeting types', 'anp_meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'anp_meetings' ),
			'popular_items'              => __( 'Popular Meeting Types', 'anp_meetings' ),
			'search_items'               => __( 'Search Meeting Types', 'anp_meetings' ),
			'not_found'                  => __( 'Not Found', 'anp_meetings' ),
		);
		$rewrite = array(
			'slug'                       => 'meeting_types',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'query_var'                  => 'meeting_type',
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'anp_meetings_type', array( 'anp_meetings' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'anp_meetings_type', 0 );

}

if ( ! function_exists( 'anp_meetings_tag' ) ) {

	// Register Custom Taxonomy
	function anp_meetings_tag() {

		$labels = array(
			'name'                       => _x( 'Meeting Tags', 'Taxonomy General Name', 'anp_meetings' ),
			'singular_name'              => _x( 'Meeting Tag', 'Taxonomy Singular Name', 'anp_meetings' ),
			'menu_name'                  => __( 'Meeting Tags', 'anp_meetings' ),
			'all_items'                  => __( 'All Tags', 'anp_meetings' ),
			'parent_item'                => __( 'Parent Tag', 'anp_meetings' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'anp_meetings' ),
			'new_item_name'              => __( 'New Tag Name', 'anp_meetings' ),
			'add_new_item'               => __( 'Add New Tag', 'anp_meetings' ),
			'edit_item'                  => __( 'Edit Tag', 'anp_meetings' ),
			'update_item'                => __( 'Update Tag', 'anp_meetings' ),
			'view_item'                  => __( 'View Tag', 'anp_meetings' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'anp_meetings' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'anp_meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'anp_meetings' ),
			'popular_items'              => __( 'Popular Tags', 'anp_meetings' ),
			'search_items'               => __( 'Search Tags', 'anp_meetings' ),
			'not_found'                  => __( 'Not Found', 'anp_meetings' ),
		);
		$rewrite = array(
			'slug'                       => 'meeting_tags',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'query_var'                  => 'meeting_tag',
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'anp_meetings_tag', array( 'anp_meetings' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'anp_meetings_tag', 0 );

}


/************* CUSTOM FIELDS *****************/

if( function_exists( "register_field_group" ) ) {
    
	register_field_group(array (
		'id' => 'acf_meeting-details',
		'title' => 'Meeting Details',
		'fields' => array (
			array (
				'key' => '_meeting_date',
				'label' => 'Date',
				'name' => 'meeting_date',
				'type' => 'date_picker',
				'required' => 1,
				'date_format' => 'yy-mm-dd',
				'display_format' => 'mm/dd/yy',
				'first_day' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'anp_meetings',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'custom_fields',
				1 => 'format',
				2 => 'featured_image',
			),
		),
		'menu_order' => 10,
	));
    
}


/************* CUSTOM ADMIN COLUMNS *****************/

add_filter( 'manage_edit-anp_meetings_columns', 'anp_meetings_columns' ) ;

function anp_meetings_columns( $columns ) {
    $columns['meeting_date'] = 'Date';
    unset( $columns['comments'] );
    unset( $columns['glocal_post_thumb'] );
    unset( $columns['date'] );
    unset( $columns['author'] );
    return $columns;
}

add_action( 'manage_posts_custom_column', 'anp_meetings_populate_columns' );

function anp_meetings_populate_columns( $column ) {
    if ( 'meeting_date' == $column ) {
        $meeting_date = esc_html( get_post_meta( get_the_ID(), 'meeting_date', true ) );
        echo $meeting_date;
    }
}


?>