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
        if ( ( is_post_type_archive( array( 'anp_meetings', 'anp_summary', 'anp_agenda' ) ) || is_tax( 'anp_meetings_tag' ) || is_tax( 'anp_meetings_type' ) || is_tax( 'anp_proposal_status' ) ) ) {

            set_query_var( 'orderby', 'meta_value' );
            set_query_var( 'meta_key', 'meeting_date' );
            set_query_var( 'order', 'DESC' );
            
            //print_r($query);
        }
        
        return $query;

    }

    add_action('pre_get_posts', 'meetings_pre_get_posts');

}