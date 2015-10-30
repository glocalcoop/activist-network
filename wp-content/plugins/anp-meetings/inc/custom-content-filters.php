<?php

/**
 * ANP Meetings Content Filters
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */


/* 
 * CUSTOM CONTENT FILTERS
 */

/* 
 * the_title()
 * Modify the title to display the meeting type and meeting date rather than post title
 */

if(! function_exists( 'anp_meetings_title_filter' ) ) {

    function anp_meetings_title_filter( $title, $id = null ) {

        if( is_admin() || !in_the_loop() || !is_main_query() ) {
            return $title;
        }

        // If meeting, display as {meeting_type} - {meeting_date}
        if( is_post_type_archive( 'meeting' ) || is_tax( array( 'meeting_type', 'meeting_tag' ) ) ) {

            global $post;

            $term_list = wp_get_post_terms( get_the_ID(), 'meeting_type', array( "fields" => "names" ) );
            $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );

            return ( !empty( $term_list ) ) ? $term_list[0] . ' - ' . $meeting_date : $post->post_title;

        }

        // If agenda or summary, display as {post_type name - singular} - {meeting_type} - {meeting_date}
        if( is_post_type_archive( array( 'agenda', 'summary' ) ) || is_singular( array( 'agenda', 'summary' ) ) ) {

            global $post;

            $post_type_object = get_post_type_object( get_post_type( get_the_ID() ) );
            $post_type_name = $post_type_object->labels->singular_name;
            $term_list = wp_get_post_terms( get_the_ID(), 'meeting_type', array( "fields" => "names" ) );

            return ( !empty( $term_list ) ) ? '<span class="post-type">' . $post_type_name . ':</span> ' . $term_list[0] : $post->post_title;
            
        }


        if( is_singular( 'meeting' ) ) {

            global $post;

            $term_list = wp_get_post_terms( get_the_ID(), 'meeting_type', array( "fields" => "names" ) );

            return ( !empty( $term_list ) ) ? $term_list[0] : $post->title;

        }

        // If proposal or status archive, display as the_title {proposal_status} {meeting_date} (conditional)
        if( is_post_type_archive( 'proposal' ) ||  is_tax( 'proposal_status' ) ) {

            global $post;

            $post_type_object = get_post_type_object( get_post_type( get_the_ID() ) );
            $post_type_name = $post_type_object->labels->singular_name;
            $term_list = wp_get_post_terms( get_the_ID(), 'proposal_status', array( "fields" => "names" ) );
            $approval_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );
            $meeting_title = ( !empty( $term_list ) ) ? '<span class="proposal-status meta">'. $term_list[0] . '</span> ' : '';
            $meeting_title .= ( $approval_date && !is_singular( 'proposal' ) ) ? '<span class="proposal-approval-dte meta"><time>'. $approval_date . '</time></span>' : '';
            
            return $title . ' ' . $meeting_title;

        }

        return $title;

    }

    add_filter( 'the_title', 'anp_meetings_title_filter', 10, 2 );

}


/* 
 * the_content()
 * Modify `the_content` to display custom post meta data above and below content
 */

if(! function_exists( 'anp_meetings_content_filter' ) ) {

    function anp_meetings_content_filter( $content ) {

        if( is_admin() || !in_the_loop() || !is_main_query() ) {
            return $content;
        }

        $post_types = array(
            'meeting', 
            'proposal', 
            'summary', 
            'agenda'
        );

        $post_tax = array(
            'meeting_type',
            'meeting_tag',
            'proposal_status',
        );


        if ( ( is_post_type_archive( 'meeting' ) || is_tax( array( 'meeting_type', 'meeting_tag' ) ) ) && in_the_loop() ) {

            global $post;

            $tag_terms = get_the_term_list( $post->ID, 'meeting_tag', '<span class="tags"> ', ', ', '</span>' );
            $meeting_tags = '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'meeting' ) . '</span> ';
            $meeting_tags .= $tag_terms;
            $meeting_tags .= '</p>';

            include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-archive.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= ( $tag_terms ) ? $meeting_tags : '';
            $meeting_content .= $meeting_post_content;

            return $meeting_content;

        } 


        if ( ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) && in_the_loop() ) {

            global $post;

            include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-archive.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= $meeting_post_content;

            return $meeting_content;

        } 

        if( is_singular( 'meeting' ) && in_the_loop() ) {

            global $post;

            include_once( ANP_MEETINGS_PLUGIN_DIR . 'views/content-single.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= $content;
            $meeting_content .= $meeting_post_content;

            return $meeting_content;

        } 

        if( is_singular( $post_types ) && in_the_loop() ) {

            global $post;

            include_once( ANP_MEETINGS_PLUGIN_DIR . 'views/content-single.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= $content;

            return $meeting_content;

        } 

        return $content;

    }

    add_filter( 'the_content', 'anp_meetings_content_filter' );

}


?>
