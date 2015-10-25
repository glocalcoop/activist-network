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
 * Enqueue JS
 */

if(! function_exists( 'anp_meetings_enqueue_scripts' )  ) {

    function anp_meetings_enqueue_scripts()  {

        wp_enqueue_script( 'anp-meetings-script', ANP_MEETINGS_PLUGIN_URL . '/js/searchFilters.js', array( 'jquery' ), '', true );

    }
    
    add_action( 'wp_enqueue_scripts', 'anp_meetings_enqueue_scripts' );
}

// ANP_MEETINGS_PLUGIN_DIR


/**
 * Taxonomy filter
 * Renders taxonomy links based on post_type, excludes empty
 * @return echo string
 */

if(! function_exists( 'anp_meetings_taxonomy_filter' ) ) {

    function anp_meetings_taxonomy_filter() {

        $post_type = get_post_type( get_the_ID() );
        $post_type_obj = get_post_type_object( $post_type );

        if( $post_type ) {

            switch( $post_type ) {

                case 'anp_meetings':
                    $taxonomy = 'anp_meetings_type';
                    $query_var = get_taxonomy( $taxonomy )->query_var;
                    break;
                case 'anp_proposal':
                    $taxonomy = 'anp_proposal_status';
                    $query_var = get_taxonomy( $taxonomy )->query_var;
                    break;
                default: 
                    $taxonomy = 'anp_meetings_tag';
                    $query_var = get_taxonomy( $taxonomy )->query_var;

            }

            $terms = get_terms( $taxonomy );

            if( count( $terms ) > 0 ) {

                $term_obj = get_taxonomy( $taxonomy );

                echo '<div class="content-filter">';

                echo '<ul class="' . $post_type . '-filter filter js-menu">';

                echo '<li class="is-on all" data-filter="*">' . $term_obj->labels->name . '</li>';

                foreach( $terms as $term ) {

                    // If terms have posts of type currently in, show them
                    $term_args = array(
                      'post_type' => $post_type,
                      'tax_query' => array(
                              array(
                                  'taxonomy' => $taxonomy,
                                  'field' => 'slug',
                                  'terms' => $term->slug
                              )
                          )
                      );
                
                    $terms_with_posts = get_posts( $term_args );

                    if( $terms_with_posts ) {

                        echo '<li data-filter="' . $term->slug . '">';

                        echo '<a href="' . esc_url( add_query_arg( $query_var, $term->slug ) ) . '">' . $term->name . '</a>';

                        echo '</li>';

                    }

                }

                echo '</ul>';
                echo '</div>';

            }

        }

        return;

    } 

}


?>
