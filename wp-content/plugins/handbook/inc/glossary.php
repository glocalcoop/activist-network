<?php
/**
 * @author Nacin
 */
class WPorg_Handbook_Glossary {
	const capability_type = 'handbook_page';

	static function init() {
		add_action( 'init', array( __CLASS__, 'init_hook' ) );
		add_action( 'load-edit.php', array( __CLASS__, 'load_edit_php' ) );
		add_filter( 'edit_glossary_per_page', array( __CLASS__, 'edit_posts_per_page' ) );
		add_filter( 'post_type_link', array( __CLASS__, 'post_type_link' ), 10, 2 );
		add_action( 'wporg_email_changes_for_post_types', array( __CLASS__, 'wporg_email_changes_for_post_types' ) );
		add_action( 'wporg_handbook_glossary', array( __CLASS__, 'page_content' ) );
		add_action( 'manage_glossary_posts_columns', array( __CLASS__, 'manage_glossary_posts_columns' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_new_glossary_term_submenu' ) );
	}

	static function edit_posts_per_page() {
		$per_page = get_user_option( 'edit_glossary_per_page' );
		if ( ! $per_page || $per_page < 1 )
			$per_page = 100;
		return $per_page;
	}

	static function init_hook() {
		add_shortcode( 'glossary', array( __CLASS__, 'shortcode' ) );

		$slug = 'glossary';

		$default_config = array(
			'labels' => array(
				'name' => _x( 'Glossary Terms', 'glossary' ),
				'singular_name' => _x( 'Glossary Term', 'glossary' ),
				'add_new' => _x( 'New Glossary Term', 'glossary' ),
				'add_new_item' => _x( 'New Glossary Term', 'glossary' ),
				'edit_item' => _x( 'Edit Glossary Term', 'glossary' ),
				'new_item' => _x( 'New Glossary Term', 'glossary' ),
				'view_item' => _x( 'View Glossary Term', 'glossary' ),
				'search_items' => _x( 'Search Glossary Term', 'glossary' ),
				'not_found' => _x( 'No terms found', 'glossary' ),
				'not_found_in_trash' => _x( 'No terms found in Trash', 'glossary' ),
				'parent_item_colon' => _x( 'Parent Glossary Term:', 'glossary' ),
				'name_admin_bar' => _x( 'Glossary Term', 'glossary' ),
			),
			'public' => true,
			'show_ui' => true,
			'hierarchical' => false,
			'show_in_menu' => 'edit.php?post_type=handbook',
			# 'has_archive' => true,
			# 'rewrite' => array( 'slug' => 'handbook/glossary' ),
			'supports' => array( 'title', 'editor', 'revisions' ),
			'capability_type' => self::capability_type,
			'map_meta_cap' => true,
			'menu_position' => 12,
		);

		$config = apply_filters( 'glossary_post_type_defaults', $default_config, $slug );

		register_post_type( $slug, $config );
	}

    static function add_new_glossary_term_submenu() {
        add_submenu_page(
            'edit.php?post_type=handbook', 
            __('New Glossary Term', 'meeting'), 
            __('New Glossary Term', 'meeting'), 
            'manage_options', 
            'post-new.php?post_type=glossary'
        );
    }

	static function load_edit_php() {
		if ( get_current_screen()->post_type !== 'glossary' )
		   return;
		if ( ! isset( $_GET['mode'] ) )
			$_GET['mode'] = $_REQUEST['mode'] = 'excerpt';
		if ( ! isset( $_GET['orderby'] ) && ! isset( $_GET['order'] ) ) {
			$_GET['order']   = $_REQUEST['order']   = 'ASC';
			$_GET['orderby'] = $_REQUEST['orderby'] = 'title';
		}
	}

	static function manage_glossary_posts_columns( $columns ) {
		$columns['author'] = __( 'Created by' );
		return $columns;
	}

	static function post_type_link( $link, $post ) {
		if ( $post->post_type !== 'glossary' )
			return $link;
		return untrailingslashit( str_replace( '/glossary/', '/glossary/#', $link ) );
	}

	static function wporg_email_changes_for_post_types( $post_types ) {
		if ( ! in_array( 'glossary', $post_types ) )
			$post_types[] = 'glossary';
		return $post_types;
	}

	static function shortcode() {
		$glossary = new WP_Query( array( 'post_status' => 'publish', 'post_type' => 'glossary', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );

		$content = '<style> .glossary-entry { margin-top: -50px; padding-top: 50px; } </style>';
		foreach ( $glossary->posts as $post ) {
			$entry = trim( apply_filters( 'the_content', '<strong>' . apply_filters( 'the_title', $post->post_title ) . ':</strong> ' . $post->post_content ) );
			$edit = get_edit_post_link( $post );
			if ( $edit )
					$edit = ' - <a href="' . $edit . '">edit</a>';
			// if ( '<p>' === substr( $entry, 0, 3 ) && '</p>' === substr( $entry, -4 ) )
			$entry = '<p class="glossary-entry" id="' . $post->post_name . '">' . substr( substr( $entry, 3 ), 0, -4 ) . ' <a href="' . get_permalink( $post ) . '">#</a>' . $edit . '</p>';

			$content .= $entry;
		}
		if ( current_user_can( get_post_type_object('glossary')->cap->edit_posts ) )
			$content .= '<p><a href="' . admin_url( 'post-new.php?post_type=glossary' ) . '">add new entry</a>';
		return $content;
	}

	static function page_content() {
		echo self::shortcode();
	}
}
