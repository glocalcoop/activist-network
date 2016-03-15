<?php

/**
 * ANP Group Blog Customization Functions
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_BuddyPress_Customization
 */

/**
 * Change Nav Text
 * Replace Blog with Website in BuddyPress nav tabs
 */
add_filter( 'bp_groupblog_subnav_item_name', function( $name ) { 
    return $name = __( 'Website', ANP_BP_CUSTOM_PLUGIN_NAMESPACE );
} );

/**
 * Change Slug
 * Replace blog with site slug
 */
add_filter( 'bp_groupblog_subnav_item_slug', function( $slug ) { 
    return $slug = __( 'site', ANP_BP_CUSTOM_PLUGIN_NAMESPACE );
} );

/**
 * Change Blog and Blog text
 * Replace Blog and Blogs with Website and Websites respectively
 * @link {https://bbpress.org/forums/topic/group-forum-tab/}
 */
if ( ! function_exists( 'anp_change_blogs_text' ) ) {

    function anp_change_blogs_text( $translated_text ) {
        if ( $translated_text == 'Blog' ) {
            $translated_text = 'Website';
        } elseif( $translated_text == 'Blogs' ) {
            $translated_text = 'Websites';
        }
        return $translated_text;
    }

add_filter( 'gettext', 'anp_change_blogs_text', 20 );
}

?>
