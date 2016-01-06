<?php
/*
Plugin Name: Event Organiser Posterboard
Plugin URI: http://www.wp-event-organiser.com
Version: 2.0.1
Description: Display events in as a responsive posterboard.
Author: Stephen Harris
Author URI: http://www.stephenharris.info
Text Domain: event-organiser-posterboard
Domain Path: /languages
*/
/*  Copyright 2013 Stephen Harris (contact@stephenharris.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

define( 'EVENT_ORGANISER_POSTERBOARD_VER', '2.0.1' );
define( 'EVENT_ORGANISER_POSTERBOARD_DIR', plugin_dir_path( __FILE__ ) );
function _eventorganiser_posterboard_set_constants(){
	/*
	 * Defines the plug-in directory url
	* <code>url:http://mysite.com/wp-content/plugins/event-organiser-posterboard</code>
	*/
	define( 'EVENT_ORGANISER_POSTERBOARD_URL', plugin_dir_url( __FILE__ ) );
}
add_action( 'after_setup_theme', '_eventorganiser_posterboard_set_constants' );


function eventorganiser_posterboard_register_stack( $stacks ){
	$stacks[] = EVENT_ORGANISER_POSTERBOARD_DIR . 'templates';
	return $stacks;
}
add_filter( 'eventorganiser_template_stack', 'eventorganiser_posterboard_register_stack' );

function eventorganiser_posterboard_register_styles(){
	$ver = EVENT_ORGANISER_POSTERBOARD_VER;
	wp_register_style( 'eo_posterboard', EVENT_ORGANISER_POSTERBOARD_URL.'css/event-board.css', array(), $ver );
}
add_action( 'init', 'eventorganiser_posterboard_register_styles' );

function eventorganiser_posterboard_register_scripts(){
	$ext = (defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG) ? '' : '.min';
	$ver = EVENT_ORGANISER_POSTERBOARD_VER;
	wp_register_script( 'eo_posterboard', EVENT_ORGANISER_POSTERBOARD_URL."js/event-board{$ext}.js", array( 'jquery', 'jquery-masonry' ), $ver );	
}
add_action( 'init', 'eventorganiser_posterboard_register_scripts' );


function eventorganiser_posterboard_shortcode_handler( $atts = array() ){
	
	$defaults = array( 'filters' => '' );
	$query    = array_diff_key( (array) $atts, $defaults );
	$atts     = shortcode_atts( $defaults, $atts );
	
	$query = array_merge( array( 'posts_per_page' => 10 ), $query );
	
	//Get template
	ob_start();
	eo_locate_template( 'single-event-board-item.html', true, false );
	$template = ob_get_contents();
	ob_end_clean();
	
	//Load & 'localize' script
	if( !eventorganiser_get_option( 'disable_css' ) ){
		wp_enqueue_style( 'eo_posterboard' );
	}
	wp_enqueue_script( 'eo_posterboard' );

	wp_localize_script( 'eo_posterboard', 'eventorganiser_posterboard',
		array(
			'url'       => admin_url( 'admin-ajax.php' ),
			'loading'   => __( 'Loading...', 'event-organiser-posterboard' ),
			'load_more' => __( 'Load more', 'event-organiser-posterboard' ),
			'template'  => $template,
			'query'     => $query,
		)
	);
	
	//Handle filters
	$filters = explode( ',', $atts['filters'] );
	$filers_markup = '';
	
	$venues = eo_get_venues();
	$cats = get_terms( array( 'event-category' ), array( 'hide_empty' => false ) );

	//'state'/'country'/'city' functions only available in Pro
	$is_pro_active = in_array( 'event-organiser-pro/event-organiser-pro.php', (array) get_option( 'active_plugins', array() ) );
	
	if( $filters ):
	
		foreach( $filters as $filter ){
		
			$filter = strtolower( trim( $filter ) );
		
			switch( $filter ){
	
				case 'venue':
					if( $venues ){
						foreach( $venues as $venue ){
							$filers_markup .= sprintf(
								'<a href="#" class="eo-eb-filter eo-eb-filter-venue eo-eb-filter-venue-%1$d" data-filter-type="venue" data-venue="%1$d" data-filter-on="false">%2$s</a>',
								$venue->term_id,
								$venue->name
							);
						}
					}
				break;
		
				case 'category':
					if( $cats ){
						foreach( $cats as $cat ){
							$filers_markup .= sprintf(
								'<a href="#" class="eo-eb-filter eo-eb-filter-category eo-eb-filter-category-%1$d" data-filter-type="category" data-category="%1$d" data-filter-on="false">%2$s</a>',
								$cat->term_id,
								$cat->name
							);
						}
					}
					$filers_markup .= sprintf(
						'<a href="#" class="eo-eb-filter eo-eb-filter-category eo-eb-filter-category-%1$d" data-filter-type="category" data-category="%1$d" data-filter-on="false">%2$s</a>',
						0,
						__( 'Uncategorised', 'event-organiser-posterboard' )
					);
				break;
			
				case 'city':
				case 'state':
				case 'country':
				
					//If Pro isn't active, this won't work
					if( !$is_pro_active ){
						break;
					}
				
					if( 'city' == $filter ){
						$terms = eo_get_venue_cities();
					}elseif( 'state' == $filter ){
						$terms = eo_get_venue_states();
					}else{
						$terms  = eo_get_venue_countries();
					}
				
					if( $terms ){
						foreach( $terms as $term ){
							$filers_markup .= sprintf(
								'<a href="#" class="eo-eb-filter eo-eb-filter-%1$s eo-eb-filter-%1$s-%2$s" data-filter-type="%1$s" data-%1$s="%2$s" data-filter-on="false">%2$s</a>',
								$filter,
								$term
							);
						}						
					}
				break;
			};
		}
	endif;
	
	return
		'<div id="event-board">' 
			.'<div id="event-board-filters" data-filters="">'. $filers_markup . '</div>'  
			.'<div id="event-board-items"></div>'
			.'<div id="event-board-more"></div>'
		.'</div>';
}
add_shortcode( 'event_board', 'eventorganiser_posterboard_shortcode_handler' );


function eventorganiser_posterboard_ajax_response(){

	$page  = isset( $_GET['page'] ) ? (int) $_GET['page'] : 1;
	$query = empty( $_GET['query'] ) ? array() : $_GET['query'];

	foreach ( array( 'category', 'tag', 'venue' ) as $tax ){
		if( isset( $query['event_'.$tax] ) ){
			$query['event-'.$tax] = $query['event_'.$tax];
			unset( $query['event_'.$tax] );
		}
	}
	
	if( isset( $query['event-venue'] ) && '%this%' == $query['event-venue'] ){
		if( eo_get_venue_slug() ){
			$query['event-venue'] = eo_get_venue_slug();
		}else{
			unset( $query['event-venue'] );
		}
	}
	
	if( isset( $query['users_events'] ) && 'true' == strtolower( $query['users_events'] ) ){
		$query['bookee_id'] = get_current_user_id();
	}
	
	$query = array_merge( 
		array(
			'event_start_after' => 'today',
			'posts_per_page'    => 10,
		),
		$query,
		array(
			'post_type'         => 'event',
			'paged'             => $page,
			'post_status'       => array( 'publish', 'private' ),
			'perm'              => 'readable',
			'supress_filters'   => false,
		)
	);
	
	$event_query = new WP_Query( $query );
	
	$response = array();
	if( $event_query->have_posts() ){
		
		global $post;
		
		while( $event_query->have_posts() ){
			
			$event_query->the_post();
			$start_format = get_option( 'time_format' );
			
			if( eo_get_the_start( 'Y-m-d' ) == eo_get_the_end( 'Y-m-d' )  ){
				$end_format = get_option( 'time_format' );
			}else{
				$end_format = 'j M '.get_option( 'time_format' );
			}
			
			$venue_id   = eo_get_venue();
			$categories = get_the_terms( get_the_ID(), 'event-category' );
			$colour     = ( eo_get_event_color() ? eo_get_event_color() : '#1e8cbe' );
			$address    = eo_get_venue_address( $venue_id );
			
			$event = array(
				'event_id'             => get_the_ID(),
				'occurrence_id'       => $post->occurrence_id,
				'event_title'         => get_the_title( ),
				'event_color'         => $colour,
				'event_color_light'   => eo_color_luminance( $colour, 0.3 ),
				'event_start_day'     => eo_get_the_start( 'j' ),
				'event_start_month'   => eo_get_the_start( 'M' ),
				'event_content'       => get_the_content(),
				'event_excerpt'       => get_the_excerpt(),
				'event_thumbnail'     => get_the_post_thumbnail( get_the_ID(), array( '200', '200' ), array( 'class' => 'aligncenter' ) ),
				'event_permalink'     => get_permalink(),
				'event_categories'    => get_the_term_list( get_the_ID(),'event-category', '#', ', #', '' ),
				'event_venue'         => ( $venue_id ? eo_get_venue_name( $venue_id ) : false ),
				'event_venue_id'      => $venue_id,
				'event_venue_city'    => ( $venue_id ? $address['city'] : false ),
				'event_venue_state'   => ( $venue_id ? $address['state'] : false ),
				'event_venue_country' => ( $venue_id ? $address['country'] : false ),
				'event_venue_url'     => ( $venue_id ? eo_get_venue_link( $venue_id ) : false ),
				'event_is_all_day'    => eo_is_all_day(),
				'event_cat_ids'       => $categories ? array_values( wp_list_pluck( $categories, 'term_id' ) ) : array( 0 ), 
				'event_range'         => eo_get_the_start( $start_format ) . ' - ' . eo_get_the_end( $end_format ),
			);
			
			$event = apply_filters( 'eventorganiser_posterboard_item', $event, $event['event_id'], $event['occurrence_id'] );
			$response[] = $event;
		}
	}

	wp_reset_postdata();
	
	echo json_encode( $response );
	exit;
}
add_action( 'wp_ajax_eventorganiser-posterboard', 'eventorganiser_posterboard_ajax_response' );
add_action( 'wp_ajax_nopriv_eventorganiser-posterboard', 'eventorganiser_posterboard_ajax_response' );
