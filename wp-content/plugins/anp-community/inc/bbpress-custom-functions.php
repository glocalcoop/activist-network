<?php

/**
 * ANP BBPress Customization Functions
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_BuddyPress_Customization
 */



/* 
 * the_title()
 * Modify the title to display the meeting type and meeting date rather than post title
 */

if( !function_exists( 'anp_rename_forums' ) ) {

    function anp_rename_forums($array) {

        $array = array(
            'name'               => __( 'Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'menu_name'          => __( 'Discussions', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'singular_name'      => __( 'Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'all_items'          => __( 'All Discussions', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'add_new'            => __( 'New Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'add_new_item'       => __( 'Create New Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'edit'               => __( 'Edit', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'edit_item'          => __( 'Edit Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'new_item'           => __( 'New Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'view'               => __( 'View Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'view_item'          => __( 'View Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'search_items'       => __( 'Search Discussion', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'not_found'          => __( 'No discussion found', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'not_found_in_trash' => __( 'No discussions found in Trash', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'parent_item_colon'  => __( 'Parent Discussion:', ANP_BP_CUSTOM_PLUGIN_NAMESPACE )
        );

        return $array;

    }

add_filter( 'bbp_get_forum_post_type_labels', 'anp_rename_forums' );

}

?>
