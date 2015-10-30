<?php

/* ADMIN CONNECTION 
 * Order posts alphabetically in the P2P connections box
 *
 */

if(! function_exists( 'anp_connection_box_order' ) ) {

    function anp_connection_box_order( $args, $ctype, $post_id ) {
        if ( ( 'meeting_to_agenda' == $ctype->name || 'meeting_to_summary' == $ctype->name || 'meeting_to_proposal' == $ctype->name ) ) {
            $args['orderby'] = 'title';
            $args['order'] = 'asc';
        }

        return $args;
    }

    add_filter( 'p2p_connectable_args', 'anp_connection_box_order', 10, 3 );

}


/**
 * CONNECTION RENDERING
 */

/**
 * Agenda
 * Render agenda associated with content
 */

if(! function_exists( 'meeting_get_agenda' ) ) {

    function meeting_get_agenda( $post_id ) {
     
        $query_args = array(
            'connected_type' => 'meeting_to_agenda',
            'connected_items' => intval( $post_id ),        
            'nopaging' => true
        );
         
        $agendas = get_posts( $query_args );
        
        $content = '';

        if( count( $agendas ) > 0 ) {

            foreach( $agendas as $post ) {

                $post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
                $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

                $content .= '<li class="agenda-link"><a href="' . get_post_permalink( $post->ID ) . '">';
                $content .= ( $post_type_name ) ? $post_type_name : $post->post_title;
                $content .= '</a></li>';
            }         

        }

        // Filter added to allow content be overriden
        return apply_filters( 'meeting_get_agenda_content', $content, $post_id );
    }  

}

/**
 * Summary
 * Render summary associated with content
 */

if(! function_exists( 'meeting_get_summary' ) ) {

    function meeting_get_summary( $post_id ) {
     
        $query_args = array(
            'connected_type' => 'meeting_to_summary',
            'connected_items' => intval( $post_id ),        
            'nopaging' => true
        );
         
        $summaries = get_posts( $query_args );
        
        $content = '';

        if( count( $summaries ) > 0 ) {

            foreach( $summaries as $post ) {

                $post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
                $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

                $content .= '<li class="summary-link"><a href="' . get_post_permalink( $post->ID ) . '">';
                $content .= ( $post_type_name ) ? $post_type_name : $post->post_title;
                $content .= '</a></li>';
            }         

        }

        // Filter added to allow content be overriden
        return apply_filters( 'meeting_get_summary_content', $content, $post_id );
    }  
      
}

/**
 * Proposal
 * Render proposal associated with content
 */

if(! function_exists( 'meeting_get_proposal' ) ) {

    function meeting_get_proposal( $post_id ) {
     
        $query_args = array(
            'connected_type' => 'meeting_to_proposal',
            'connected_items' => intval( $post_id ),        
            'nopaging' => true
        );
         
        $proposals = get_posts( $query_args );

        $url = array( 
            'connected_type' => 'meeting_to_proposal', 
            'connected_items' => intval( $post_id ),
            'connected_direction' => 'from'
        );
        
        $content = '';

        if( count( $proposals ) > 0 ) {

            $content .= '<li class="proposal-link"><a href="' . esc_url( add_query_arg( $url ) ) . '">';
            $content .= ( 1 == count( $proposals ) ) ? __( 'Proposal', 'meeting' ) : __( 'Proposals', 'meeting' );
            $content .= '</a></li>';                   

        }

        // Filter added to allow content be overriden
        return apply_filters( 'meeting_get_proposal_content', $content, $post_id );
    }  
      
}

/* 
 * Add markdown support for custom post types
 */

if(! function_exists( 'meeting_markdown_support' )  ) {

    function meeting_markdown_support() {
        add_post_type_support( 'meeting', 'wpcom-markdown' );
        add_post_type_support( 'proposal', 'wpcom-markdown' );
        add_post_type_support( 'summary', 'wpcom-markdown' );
        add_post_type_support( 'agenda', 'wpcom-markdown' );
    }

    add_action( 'init', 'meeting_markdown_support' );

}


/* 
 * TEMPLATE LOCATION
 * Templates can be overwritten by putting a template file of the same name in 
 * plugins/anp-meeting/ folder of your active theme 
 */


if(! function_exists( 'include_meeting_templates' ) ) {

    function include_meeting_templates( $template_path ) {

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

        if ( is_singular( $post_types ) ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'plugins/anp-meeting/single.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = ANP_MEETINGS_PLUGIN_DIR . 'templates/single.php';
            }
        } elseif ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) {
            if ( $theme_file = locate_template( array('plugins/anp-meeting/archive.php') ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = ANP_MEETINGS_PLUGIN_DIR . 'templates/archive.php';
            }
        }
        return $template_path;
    }
    //add_filter( 'template_include', 'include_meeting_templates', 1 );

}



?>