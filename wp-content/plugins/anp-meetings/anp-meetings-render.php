<?php

/* 
 * TEMPLATE LOCATION
 * Templates can be overwritten by putting a template file of the same name in 
 * plugins/anp-meetings/ folder of your active theme 
 */


if(! function_exists( 'include_meeting_templates' ) ) {

    function include_meeting_templates( $template_path ) {
        if ( is_singular( 'anp_meetings' ) ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'plugins/anp-meetings/single-anp_meetings.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/single-anp_meetings.php';
            }
        } elseif ( is_post_type_archive( 'anp_meetings' ) ) {
            if ( $theme_file = locate_template( array('plugins/anp-meetings/archive-anp_meetings.php') ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/archive-anp_meetings.php';
            }
        }
        return $template_path;
    }
    add_filter( 'template_include', 'include_meeting_templates', 1 );

}


/* 
 * CUSTOM CONTENT FILTERS
 * Append meeting post type field to content
 */

/* 
 * the_title()
 * Modify the title to display the meeting type and meeting date rather than post title
 */

if(! function_exists( 'meetings_title_filter' ) ) {

    function meetings_title_filter( $title, $id = null ) {

        if( is_admin() || !in_the_loop() ) {
            return $title;
        }

        if( ( is_post_type_archive('anp_meetings') || is_tax('anp_meetings_tag') || is_tax('anp_meetings_type') ) && in_the_loop() ) {
            global $post;
            $term_list = wp_get_post_terms( $post->ID, 'anp_meetings_type', array("fields" => "names") );
            $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );
            $meeting_title = $term_list[0] . ' - ' . $meeting_date;
            $title = $meeting_title;
        } 

        return $title;
    }

    add_filter( 'the_title', 'meetings_title_filter', 10, 2 );

}

/* 
 * Add markdown support for custom post types
 */

if(! function_exists( 'meetings_markdown_support' )  ) {

    function meetings_markdown_support() {
        add_post_type_support( 'anp_meetings', 'wpcom-markdown' );
        add_post_type_support( 'anp_proposal', 'wpcom-markdown' );
        add_post_type_support( 'anp_summary', 'wpcom-markdown' );
        add_post_type_support( 'anp_agenda', 'wpcom-markdown' );
    }

    add_action( 'init', 'meetings_markdown_support' );

}


/* 
 * the_content()
 */

if(! function_exists( 'meetings_content_filter' ) ) {

    function meetings_content_filter( $content ) {

        if( is_admin() || !in_the_loop() ) {
            return $content;
        }

        if ( ( is_post_type_archive('anp_meetings') || is_tax('anp_meetings_tag') || is_tax('anp_meetings_type') ) && in_the_loop() ) {

            global $post;

            $meeting_tags = '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'anp_meetings' ) . '</span> ';
            $meeting_tags .= get_the_term_list( $post->ID, 'anp_meetings_tag', '<span class="tags"> ', ', ', '</span>' );
            $meeting_tags .= '</p>';

            include( plugin_dir_path( __FILE__ ) . 'views/content-archive.php' );

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

}


/* CUSTOM POST TYPE QUERY
 * Modify query parameters for anp_meetings post archive, anp_meetings_tag archive or anp_meetings_type archive
 *
 */

if(! function_exists( 'meetings_pre_get_posts' ) ) {

    function meetings_pre_get_posts( $query ) {
        
        // Do not modify queries in the admin or other queries (like nav)
        if( is_admin() || !$query->is_main_query() ) {
            return;
        }
        
        // If meetings post archive, anp_meetings_tag archive or anp_meetings_type archive
        if ( ( is_post_type_archive( array( 'anp_meetings', 'anp_proposal', 'anp_summary', 'anp_agenda' ) ) || is_tax( 'anp_meetings_tag' ) || is_tax( 'anp_meetings_type' ) || is_tax( 'anp_proposal_status' ) ) ) {

            set_query_var( 'orderby', 'meta_value' );
            set_query_var( 'meta_key', 'meeting_date' );
            set_query_var( 'order', 'DESC' );
            
            //print_r($query);
        }
        
        return $query;

    }

    add_action('pre_get_posts', 'meetings_pre_get_posts');

}




?>