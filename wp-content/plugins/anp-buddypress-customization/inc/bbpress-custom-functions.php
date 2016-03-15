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

/**
 * Change the labels registered for bbPress forum post type
 * Note: This only affects the dashboard and native bbPress, not display in BuddyPress
 */
if( !function_exists( 'anp_rename_forums' ) ) {

    function anp_rename_forums( $array ) {

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

/**
 * Change Forum and Forums text
 * Replace Forum and Forums with Discussion and Discussions respectively
 * @link {https://bbpress.org/forums/topic/group-forum-tab/}
 */
if ( ! function_exists( 'anp_change_forums_text' ) ) {

    function anp_change_forums_text( $translated_text ) {
        if ( $translated_text == 'Forum' ) {
            $translated_text = 'Discussion';
        } elseif( $translated_text == 'Forums' ) {
            $translated_text = 'Discussions';
        }
        return $translated_text;
    }

add_filter( 'gettext', 'anp_change_forums_text', 20 );
}


/**
 * Hide Topic Menu
 * Hide the Topics menu in the dashboard
 */
if( !function_exists( 'anp_hide_topic_admin_menu' ) ) {

    function anp_hide_topic_admin_menu( $array ) {

        $array = array(
            'labels'              => bbp_get_topic_post_type_labels(),
            'rewrite'             => bbp_get_topic_post_type_rewrite(),
            'supports'            => bbp_get_topic_post_type_supports(),
            'description'         => __( 'Topics', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'capabilities'        => bbp_get_topic_caps(),
            'capability_type'     => array( 'topic', 'topics' ),
            'menu_position'       => 555555,
            'has_archive'         => ( 'discussion' === bbp_show_on_root() ) ? bbp_get_topic_archive_slug() : false,
            'exclude_from_search' => true,
            'show_in_nav_menus'   => false,
            'public'              => true,
            'show_ui'             => current_user_can( 'bbp_topics_admin' ),
            'can_export'          => true,
            'hierarchical'        => false,
            'query_var'           => true,
            'menu_icon'           => '',
            'show_in_menu'        => false
        );

        return $array;

    }

add_filter( 'bbp_register_topic_post_type', 'anp_hide_topic_admin_menu' );

}

/**
 * Hide Reply Menu
 * Hide the Replies menu in the dashboard
 */
if( !function_exists( 'anp_hide_reply_admin_menu' ) ) {

    function anp_hide_reply_admin_menu( $array ) {

        $array = array(
            'labels'              => bbp_get_reply_post_type_labels(),
            'rewrite'             => bbp_get_reply_post_type_rewrite(),
            'supports'            => bbp_get_reply_post_type_supports(),
            'description'         => __( 'Topic Replies', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ),
            'capabilities'        => bbp_get_reply_caps(),
            'capability_type'     => array( 'reply', 'replies' ),
            'menu_position'       => 555555,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'show_in_nav_menus'   => false,
            'public'              => true,
            'show_ui'             => current_user_can( 'bbp_replies_admin' ),
            'can_export'          => true,
            'hierarchical'        => false,
            'query_var'           => true,
            'menu_icon'           => '',
            'show_in_menu'        => false
        );

        return $array;

    }

add_filter( 'bbp_register_reply_post_type', 'anp_hide_reply_admin_menu' );

}

/**
 * Add Topics and Replies as Submenus of Discussions
 * Show the menu items under the Discussions menu
 */
if ( ! function_exists( 'anp_add_subitems_to_forums' ) ) {

    function anp_add_subitems_to_forums() { 

        //edit.php?post_type=forum

        add_submenu_page(
            'edit.php?post_type=forum', 
            __( 'All Topics', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            __( 'All Topics', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            'manage_options', 
            'edit.php?post_type=topic'
        ); 

        add_submenu_page(
            'edit.php?post_type=forum', 
            __( 'New Topic', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            __( 'New Topic', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            'manage_options', 
            'post-new.php?post_type=topic'
        ); 

        add_submenu_page(
            'edit.php?post_type=forum', 
            __( 'All Replies', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            __( 'All Replies', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            'manage_options', 
            'post-new.php?post_type=reply'
        ); 

        add_submenu_page(
            'edit.php?post_type=forum', 
            __( 'New Reply', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            __( 'New Reply', ANP_BP_CUSTOM_PLUGIN_NAMESPACE ), 
            'manage_options', 
            'post-new.php?post_type=reply'
        ); 

    }

    add_action( 'admin_menu', 'anp_add_subitems_to_forums' ); 

}


?>
