<?php

/**
 * ANP Group Docs Customization Functions
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_BuddyPress_Customization
 */

if( !function_exists( 'anp_rename_bp_docs' ) ) {

    function anp_rename_bp_docs( $array ) {

        $post_type_labels = array(
            'name'           => _x( 'Community Documents', 'post type general name', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'singular_name'      => _x( 'Documents', 'post type singular name', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'add_new'        => _x( 'New Document', 'add new', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'add_new_item'       => __( 'Add New Document', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'edit_item'          => __( 'Edit Document', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'new_item'       => __( 'New Document', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'view_item'          => __( 'View Document', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'search_items'       => __( 'Search  Documents', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'not_found'          =>  __( 'No  Documents found', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'not_found_in_trash' => __( 'No  Documents found in Trash', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'parent_item_colon'  => ''
        );

        $array = array(
            'label'        => __( 'Community Documents', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'labels'       => $post_type_labels,
            'public'       => true,
            'show_ui'      => (new BP_Docs)->show_cpt_ui(),
            'hierarchical' => true,
            'supports'     => array( 'title', 'editor', 'revisions', 'excerpt', 'comments', 'author' ),
            'query_var'    => true,
            'has_archive'  => true,
            'rewrite'      => array(
                'slug'       => bp_docs_get_docs_slug(),
                'with_front' => false
            ),
            'menu_icon'     => 'dashicons-media-text',
        );

        return $array;

    }

add_filter( 'bp_docs_post_type_args', 'anp_rename_bp_docs' );

}

//apply_filters( 'bp_docs_post_type_name', 'bp_doc' );

if( !function_exists( 'anp_dequeue_docs_styles' ) ) {

    function anp_dequeue_docs_styles() {

        wp_dequeue_style( 'bp-docs-css' );

    }

}

add_action( 'wp_enqueue_scripts', 'anp_dequeue_docs_styles', 100 );


?>
