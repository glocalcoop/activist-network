<?php

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if( !class_exists( 'EO_Export_CSV' ) ):
	
abstract class EO_Export_CSV {

	var $file_name;
	
	var $file_type = 'text/csv';
	
	public $args=array();

	/**
	 * Constructor
	*/
	public function __construct( $args=array() ) {
		$this->args = $args;
		$this->get_export_file();
	}

	abstract function file_name();

	function file_type() {
		return 'text/csv';
	}

	abstract function get_headers();

	abstract function get_items();

	abstract function get_cell(  $header, $item );

	/**
	 *
	 *
	 * @since 1.0.0
	*/
	public function get_export_file() {
		$this->file_name = $this->file_name();
		$this->file_type = $this->file_type();
		$this->headers = $this->get_headers( );
		$this->items = $this->get_items( $this->args );
		$filename = urlencode( $this->file_name. '.csv' );
		$this->export( $filename, $this->file_type );
	}


	/**
	 * Creates a CVS file
	 *
	 * @since 1.0.0
	 *  @param string filename - the name of the file to be created
	 *  @param string filetype - the type of the file ('text/csv')
	 */
	public function export( $filename, $filetype ) {
		global $wpdb;

		$headers = $this->headers;
		$items = $this->items;

		if ( ! $items ) {
			exit;
		}

		//Collect output
		ob_start();

		// File header
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: '.$filetype.'; charset=' . get_option( 'blog_charset' ), true );

		//Headers
		echo implode( ',', $headers ) . "\r\n";

		//Data
		foreach ( $items as $item ) {
			$data = array();
			foreach ( $headers as $hid => $header ) {
				$value = $this->get_cell( $hid, $item );
				$data[] = '"' . str_replace( '"', '""', $value ) . '"';
			}
			echo implode( ',', $data ) . "\r\n";
		}

		//Collect output and echo
		$csv_file = ob_get_contents();
		ob_end_clean();
		echo $csv_file;
		exit();
	}
}
// end class
endif; //class doesn't exist


class EO_Export_Events_CSV extends EO_Export_CSV  {

	function get_headers() {
		$headers = array(
			'post_title'=>__( 'Title', 'event-organiser-csv' ),
				
			'start' =>__( 'Start Date', 'event-organiser-csv' ),
			'end'=>__( 'End Date', 'event-organiser-csv' ),
				
			'post_content' => __( 'Content', 'event-organiser-csv' ),
				
			'event-venue' => __( 'Venue', 'event-organiser-csv' ),
			'event-category' =>__( 'Category', 'event-organiser-csv' ),
			'event-tag' => __( 'Tags', 'event-organiser-csv' ),
				
			'schedule'=>__( 'Recurrence schedule', 'event-organiser-csv' ),
			'schedule_meta'=>__( 'Schedule meta', 'event-organiser-csv' ),
			'frequency'=>__( 'Recurrence frequency', 'event-organiser-csv' ),
			'schedule_last'=>__( 'Recurr until', 'event-organiser-csv' ),
			'include'=>__( 'Include dates', 'event-organiser-csv' ),
			'exclude'=>__( 'Exclude dates', 'event-organiser-csv' ),
		);
		
		$taxonomies = array( 'event-venue', 'event-tag', 'event-category' );
		foreach( $taxonomies as $taxonomy ){
			if( !taxonomy_exists( $taxonomy ) ){
				unset( $headers[$taxonomy] );
			}	
		}

		return apply_filters_ref_array( 'eventorganiser_export_events_headers', array( $headers, &$this ) );
	}

	function file_name() {
		$filename = 'eo-events-'.get_bloginfo('name').'-' ;
		return $filename.date( 'Ymd' );
	}


	function get_items( $args=array() ) {
		$events = eo_get_events( array(
			'showpastevents' => true,
			'group_events_by' => 'series',	
		));
		return $events;
	}

	function get_cell( $header, $item ) {

		switch ( $header ) {
			case 'post_title':
				$value = get_the_title( $item );
				break;
				
			case 'start':
				$format = ( eo_is_all_day( $item->ID ) ? 'Y-m-d' : 'Y-m-d H:i:s' );
				$value = eo_get_schedule_start( $format, $item->ID );
				break;
			
			case 'end':
				$format = ( eo_is_all_day( $item->ID ) ? 'Y-m-d' : 'Y-m-d H:i:s' );
				$value = eo_get_the_end( $format, $item->ID, null, $item->occurrence_id );
				break;
				
			case 'schedule_last':
				$value = eo_get_schedule_last( 'Y-m-d', $item->ID );
				break;
				
			case 'post_content':
				$value = $item->post_content;
				break;

			case 'event-venue':
				$venue = eo_get_venue( $item->ID );
				if( $venue ){
					$value = eo_get_venue_name( $venue );
				}else{
					$value = false;
				}
				break;

			case 'event-tag':
			case 'event-category':
				$terms = get_the_terms( $item, $header );
				if( $terms ){
					$names = wp_list_pluck( $terms, 'name' );
					$value = implode( ',', $names );
				}else{
					$value = false;
				}
				break;

			case 'schedule':
			case 'schedule_meta':
			case 'frequency':
				$schedule = eo_get_event_schedule( $item->ID );
				$value = isset( $schedule[$header] ) ? $schedule[$header] : false;
				$value = ( is_array( $value ) ? implode( ',', $value ) : $value );
				break; 

			case 'include':
			case 'exclude':
				$schedule = eo_get_event_schedule( $item->ID );
				
				$dates = $schedule[$header];
				if( $dates ){
					$value = array();
					foreach( $dates as $date ){
						$value[] = $date->format( 'Y-m-d' );
					}
					$value = implode(',', $value );
				}else{
					$value = false;
				}
				
				break;
				
			default:
				$value = '';
		}

		return apply_filters_ref_array('eventorganiser_export_events_cell_'.$header, array( $value, $item, &$this) );
	}

} // end class

