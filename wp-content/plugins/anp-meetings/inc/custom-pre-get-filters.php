<?php

/**
 * ANP Meetings Query Filters
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */


/* CUSTOM POST TYPE QUERY
 * Modify query parameters for meeting post archive, meeting_tag archive or meeting_type archive
 *
 */

if(! function_exists( 'anp_meetings_pre_get_posts' ) ) {

    function anp_meetings_pre_get_posts( $query ) {
        
        // Do not modify queries in the admin or other queries (like nav)
        if( is_admin() || !$query->is_main_query() ) {
            return;
        }
        
        // If meeting post archive, meeting_tag archive or meeting_type archive
        if ( ( is_post_type_archive( array( 'meeting', 'summary', 'agenda' ) ) || is_tax( 'meeting_tag' ) || is_tax( 'meeting_type' ) || is_tax( 'proposal_status' ) ) ) {

            set_query_var( 'orderby', 'meta_value' );
            set_query_var( 'meta_key', 'meeting_date' );
            set_query_var( 'order', 'DESC' );
            
            //print_r($query);
        }
        
        return $query;

    }

    add_action( 'pre_get_posts', 'anp_meetings_pre_get_posts' );

}