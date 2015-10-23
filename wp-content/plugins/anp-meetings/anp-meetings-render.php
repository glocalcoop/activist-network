<?php

/* 
 * TEMPLATE LOCATION
 * Templates can be overwritten by putting a template file of the same name in 
 * plugins/anp-meetings/ folder of your active theme 
 */


if(! function_exists( 'include_meeting_templates' ) ) {

    function include_meeting_templates( $template_path ) {

        $post_types = array(
            'anp_meetings', 
            'anp_proposal', 
            'anp_summary', 
            'anp_agenda'
        );

        $post_tax = array(
            'anp_meetings_type',
            'anp_meetings_tag',
            'anp_proposal_status',
        );

        if ( is_singular( $post_types ) ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'plugins/anp-meetings/single.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/single.php';
            }
        } elseif ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) {
            if ( $theme_file = locate_template( array('plugins/anp-meetings/archive.php') ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/archive.php';
            }
        }
        return $template_path;
    }
    //add_filter( 'template_include', 'include_meeting_templates', 1 );

}


/* 
 * CUSTOM CONTENT FILTERS
 */

/* 
 * the_title()
 * Modify the title to display the meeting type and meeting date rather than post title
 */

if(! function_exists( 'meetings_title_filter' ) ) {

    function meetings_title_filter( $title, $id = null ) {

        if( is_admin() || !in_the_loop() || !is_main_query() ) {
            return $title;
        }

        // If anp_meetings, display as {anp_meeting_type} - {meeting_date}
        if( is_post_type_archive( 'anp_meetings' ) || is_tax( array( 'anp_meetings_type', 'anp_meetings_tag' ) ) ) {

            global $post;

            $term_list = wp_get_post_terms( get_the_ID(), 'anp_meetings_type', array( "fields" => "names" ) );
            $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );

            return ( !empty( $term_list ) ) ? $term_list[0] . ' - ' . $meeting_date : $post->post_title;

        }

        // If anp_agenda or anp_summary, display as {post_type name - singular} - {anp_meeting_type} - {meeting_date}
        if( is_post_type_archive( array( 'anp_agenda', 'anp_summary' ) ) || is_singular( array( 'anp_agenda', 'anp_summary' ) ) ) {

            global $post;

            $post_type_object = get_post_type_object( get_post_type( get_the_ID() ) );
            $post_type_name = $post_type_object->labels->singular_name;
            $term_list = wp_get_post_terms( get_the_ID(), 'anp_meetings_type', array( "fields" => "names" ) );

            return ( !empty( $term_list ) ) ? '<span class="post-type">' . $post_type_name . ':</span> ' . $term_list[0] : $post->post_title;
            
        }


        if( is_singular( 'anp_meetings' ) ) {

            global $post;

            $term_list = wp_get_post_terms( get_the_ID(), 'anp_meetings_type', array( "fields" => "names" ) );

            return ( !empty( $term_list ) ) ? $term_list[0] : $post->title;

        }

        // If anp_proposal, display as the_title {anp_proposal_status} 
        if( is_post_type_archive( 'anp_proposal' ) ||  is_tax( 'anp_proposal_status' ) || is_singular( 'anp_proposal' ) ) {

            global $post;

            $post_type_object = get_post_type_object( get_post_type( get_the_ID() ) );
            $post_type_name = $post_type_object->labels->singular_name;
            $term_list = wp_get_post_terms( get_the_ID(), 'anp_proposal_status', array( "fields" => "names" ) );
            $approval_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );
            $meeting_title = ( !empty( $term_list ) ) ? '<span class="proposal-status meta">'. $term_list[0] . '</span> ' : '';
            $meeting_title .= ( $approval_date ) ? '<span class="proposal-approval-dte meta"><time>'. $approval_date . '</time></span>' : '';
            
            return $title . ' ' . $meeting_title;

        }

        return $title;

    }

    add_filter( 'the_title', 'meetings_title_filter', 10, 2 );

}


/* 
 * the_content()
 * Modify `the_content` to display custom post meta data above and below content
 */

if(! function_exists( 'meetings_content_filter' ) ) {

    function meetings_content_filter( $content ) {

        if( is_admin() || !in_the_loop() || !is_main_query() ) {
            return $content;
        }

        $post_types = array(
            'anp_meetings', 
            'anp_proposal', 
            'anp_summary', 
            'anp_agenda'
        );

        $post_tax = array(
            'anp_meetings_type',
            'anp_meetings_tag',
            'anp_proposal_status',
        );


        if ( ( is_post_type_archive( 'anp_meetings' ) || is_tax( array( 'anp_meetings_type', 'anp_meetings_tag' ) ) ) && in_the_loop() ) {

            global $post;

            $tag_terms = get_the_term_list( $post->ID, 'anp_meetings_tag', '<span class="tags"> ', ', ', '</span>' );
            $meeting_tags = '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'anp_meetings' ) . '</span> ';
            $meeting_tags .= $tag_terms;
            $meeting_tags .= '</p>';

            include( plugin_dir_path( __FILE__ ) . 'views/content-archive.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= ( $tag_terms ) ? $meeting_tags : '';
            $meeting_content .= $meeting_post_content;

            return $meeting_content;

        } 


        if ( ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) && in_the_loop() ) {

            global $post;

            include( plugin_dir_path( __FILE__ ) . 'views/content-archive.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= $meeting_post_content;

            return $meeting_content;

        } 

        if( is_singular( 'anp_meetings' ) && in_the_loop() ) {

            global $post;

            include_once( plugin_dir_path( __FILE__ ) . 'views/content-single.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= $content;
            $meeting_content .= $meeting_post_content;

            return $meeting_content;

        } 

        if( is_singular( $post_types ) && in_the_loop() ) {

            global $post;

            include_once( plugin_dir_path( __FILE__ ) . 'views/content-single.php' );

            $meeting_content = $meeting_pre_content;
            $meeting_content .= $content;

            return $meeting_content;

        } 

        return $content;

    }

    add_filter( 'the_content', 'meetings_content_filter' );

}

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


/**
 * CONNECTION RENDERING
 */

/**
 * Agenda
 * Render agenda associated with content
 */

if(! function_exists( 'meetings_get_agenda' ) ) {

    function meetings_get_agenda( $post_id ) {
     
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
        return apply_filters( 'meetings_get_agenda_content', $content, $post_id );
    }  

}

/**
 * Summary
 * Render summary associated with content
 */

if(! function_exists( 'meetings_get_summary' ) ) {

    function meetings_get_summary( $post_id ) {
     
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
        return apply_filters( 'meetings_get_summary_content', $content, $post_id );
    }  
      
}

/**
 * Proposal
 * Render proposal associated with content
 */

if(! function_exists( 'meetings_get_proposal' ) ) {

    function meetings_get_proposal( $post_id ) {
     
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
            $content .= ( 1 == count( $proposals ) ) ? __( 'Proposal', 'anp_meetings' ) : __( 'Proposals', 'anp_meetings' );
            $content .= '</a></li>';                   

        }

        // Filter added to allow content be overriden
        return apply_filters( 'meetings_get_proposal_content', $content, $post_id );
    }  
      
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




?>