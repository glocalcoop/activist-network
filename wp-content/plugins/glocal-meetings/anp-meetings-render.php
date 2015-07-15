<?php

/* 
 * TEMPLATE LOCATION
 * Templates can be overwritten by putting a template file of the same name in 
 * plugins/anp-meetings/ folder of your active theme 
 */

function include_meeting_templates( $template_path ) {
    if ( get_post_type() == 'anp_meetings' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'plugins/anp-meetings/single-anp_meetings.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/single-anp_meetings.php';
            }
        } elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array('plugins/anp-meetings/archive-anp_meetings.php') ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/archive-anp_meetings.php';
            }
        }
    }
    return $template_path;
}

//add_filter( 'template_include', 'include_meeting_templates', 1 );

/* 
 * CUSTOM CONTENT FILTERS
 * Append meeting post type field to content
 */

/* 
 * the_title()
 * Modify the title to display the meeting type and meeting date rather than post title
 */

function meetings_title_filter( $title, $id = null ) {

    if( is_admin() || !in_the_loop() ) {
        return $title;
    }

    if( ( is_post_type_archive('anp_meetings') || is_tax('anp_meetings_tag') || is_tax('anp_meetings_type') ) && in_the_loop() ) {
        global $post;
        $term_list = wp_get_post_terms($post->ID, 'anp_meetings_type', array("fields" => "names"));
        $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) );
        $meeting_title = $term_list[0] . ' - ' . $meeting_date;
        $title = $meeting_title;
    } 

    return $title;
}

add_filter( 'the_title', 'meetings_title_filter', 10, 2 );

/* 
 * the_content()
 */

function meetings_content_filter( $content ) {

    if( is_admin() || !in_the_loop() ) {
        return $content;
    }

    if ( ( is_post_type_archive('anp_meetings') || is_tax('anp_meetings_tag') || is_tax('anp_meetings_type') ) && in_the_loop() ) {

        global $post;

        $meeting_tags = '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'anp_meetings' ) . '</span> ';
        $meeting_tags .= get_the_term_list( $post->ID, 'anp_meetings_tag', '<span class="tags"> ', ', ', '</span>' );
        $meeting_tags .= '</p>';

        include_once( plugin_dir_path( __FILE__ ) . 'views/content-archive.php' );

        $meeting_content = $meeting_pre_content;
        $meeting_content .= $meeting_tags;
        $meeting_content .= $meeting_post_content;

        $content = $meeting_content;

    } elseif ( is_singular('anp_meetings') && in_the_loop() ) {

        global $post;

        include_once( plugin_dir_path( __FILE__ ) . 'views/content-single.php' );

        $meeting_content = $meeting_pre_content;
        $meeting_content .= $content;
        $meeting_content .= $meeting_post_content;

        $content = $meeting_content;

    } 

    return $content;

}

add_filter( 'the_content', 'meetings_content_filter' );

/* 
 * the_post()
 */


/* CUSTOM POST TYPE QUERY
 * Modify query parameters for anp_meetings post archive, anp_meetings_tag archive or anp_meetings_type archive
 *
 */

function meetings_pre_get_posts( $query ) {
	
	// Do not modify queries in the admin or other queries (like nav)
	if( is_admin() && !$query->is_main_query() ) {
		return $query;
	}
	
    // If meetings post archive, anp_meetings_tag archive or anp_meetings_type archive
    if ( ( is_post_type_archive('anp_meetings') || is_tax('anp_meetings_tag') || is_tax('anp_meetings_type') ) ) {

        set_query_var( 'orderby', 'meta_value' );
        set_query_var( 'meta_key', 'meeting_date' );
        set_query_var( 'order', 'DESC' );
        
        //print_r($query);
	}
	
	return $query;

}

add_action('pre_get_posts', 'meetings_pre_get_posts');


/** 
 * Get the post-type info. 
 *
 * Use: Call function <?php is_post_type('post-type'); ?>
 * Function is used to determine if current post is of a certain post-type.
 */
// function is_post_type($type) {
//     global $wp_query;
//     if( $type == get_post_type($wp_query->post->ID) ) {
//         return true;
 
//     } else {
//         return false;
//     }
// }

/************* POST FILTER AND SEARCH *****************/

// Load scripts

// Function to process request

// JS for AJAX post

//add_action( 'edit_form_top', 'meetings_edit_form_title' );
//add_action( 'edit_form_after_title', 'meetings_edit_form_title' );
//add_action( 'edit_form_after_editor', 'meetings_edit_form_title' );
//
//function meetings_edit_form_title() {
//	echo '<h2>' . current_filter() . '</h2>';
//}


?>