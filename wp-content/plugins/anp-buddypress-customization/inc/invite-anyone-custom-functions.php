<?php

/**
 * ANP Invite Anyone Customization Functions
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_BuddyPress_Customization
 */


if( !function_exists( 'anp_rename_invite_anyone' ) ) {

    function anp_rename_invite_anyone( $post_type_args ) {

        $post_type_labels = array(
            'name'          => _x( 'Community Invitations', 'post type general name', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'singular_name'     => _x( 'Invitation', 'post type singular name', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'add_new'       => _x( 'Add New', 'add new', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'add_new_item'      => __( 'Add New Invitation', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'edit_item'         => __( 'Edit Invitation', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'new_item'      => __( 'New Invitation', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'view_item'         => __( 'View Invitation', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'search_items'      => __( 'Search Invitation', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'not_found'         =>  __( 'No Invitations found', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'not_found_in_trash'    => __( 'No Invitations found in Trash', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'parent_item_colon'     => ''
        );

        $post_type_args = array(
            'label'     => __( 'Community Invitations', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'labels'    => $post_type_labels,
            'public'    => false,
            '_builtin'  => false,
            'show_ui'   => Invite_Anyone_Schema::show_dashboard_ui(),
            'hierarchical'  => false,
            'menu_icon' => 'dashicons-email-alt',
            'supports'  => array( 'title', 'editor', 'custom-fields', 'author' )
        );

        return $post_type_args;

    }

add_filter( 'invite_anyone_post_type_args', 'anp_rename_invite_anyone' );

}





?>
