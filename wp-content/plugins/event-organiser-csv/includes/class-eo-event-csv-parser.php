<?php 

class EO_Event_CSV_Parser extends EO_CSV_Parser{
		
	/**
	 * 
	 * @param string $value The value in the cell
	 * @param string $key   String identifier the row
	 * @param array  $item  Array indexed by column identifier
	 * @return The parsed value.
	 */
	function parse_value( $value, $key, &$item ){
		
		//Allow sub classes to override parsing of values
		$method = 'parse_value_'. str_replace( '-', '_', $key );
		if( method_exists( $this, $method ) ){
			$value = $this->$method( $value, $item );
		}
		
		$value = apply_filters( 'eventorganiser_csv_cell_value', $value, $key, $item );
		
		if( substr( $key, 0, 6 ) === "meta::" ){
			$item['meta'][substr( $key, 6 )] = $value;
		}else{
			$item[$key] = $value;	
		}
		
	}
	
	/**
	 * An event category column should contain a comma-delimited list of category slugs
	 * 
	 * @param string $value Comma-delimited list of category slugs
	 * @param array $item
	 * @return array Array of category slugs
	 */
	function parse_value_event_category( $value, $item ){
		return explode( ',', $value );
	}
	
	/**
	 * An event tag column should contain a comma-delimited list of tag slugs
	 *
	 * @param string $value Comma-delimited list of tag slugs
	 * @param array $item
	 * @return array Array of tag slugs
	 */
	function parse_value_event_tag( $value, $item ){
		return explode( ',', $value );
	}
	
	/**
	 * An start date column should be of the format:
	 * * Y-m-d 			( for all-day events)
	 * * Y-m-d H:i:s 	( for non-all-day events)
	 *
	 * @param string $value Formatted date/date-time
	 * @param array $item
	 * @return array Array of category slugs
	 */
	function parse_value_start( $value, &$item ){
		
		if( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ){
			if( !isset( $item['all_day'] ) )
				$item['all_day'] = 1;
			return $this->parse_date( $value );
		}else{
			if( !isset( $item['all_day'] ) )
				$item['all_day'] = 0;
			
			return $this->parse_date_time( $value );
		}
	}
	
	/**
	 * An end date column should be of the format Y-m-d or Y-m-d H:i:s
	 * 
	 * @param string $value Formatted date/date-time
	 * @param array $item
	 * @return array Array of category slugs
	 */
	function parse_value_end( $value, $item ){
		if( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ){
			return $this->parse_date( $value );
		}else{			
			return $this->parse_date_time( $value );
		}
	}
	
	/**
	 * An schedule last date column should be of the format Y-m-d or Y-m-d H:i:s
	 * 
	 * @param string $value Formatted date/date-time
	 * @param array $item
	 * @return array Array of category slugs
	 */
	function parse_value_schedule_last( $value, $item ){
		if( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ){
			return $this->parse_date( $value );
		}else{			
			return $this->parse_date_time( $value );
		}
	}
	
	/**
	 * Parses a date object (assumed to be Y-m-d format).
	 * 
	 * @param string $value
	 * @return boolean|DateTime False if date could not be interpreted or a DateTime object.
	 */
	function parse_date( $value ){
		
		if( !$value )
			return false;
		
		try{
			$value = new DateTime( $value, eo_get_blog_timezone() );
		}catch( Exception $e ){
			$value = false;
		}
	
		return $value;
	}
	
	/**
	 * Parses a date object (assumed to be Y-m-d H:i:s format).
	 *
	 * @param string $value
	 * @return boolean|DateTime False if date could not be interpreted or a DateTime object.
	 */
	function parse_date_time( $value ){
	
		//TODO handle timezone
		if( !$value )
			return false;
	
		try{
			$value = new DateTime( $value, eo_get_blog_timezone() );
		}catch( Exception $e ){
			$value = false;
		}
	
		return $value;
	}
	
	/**
	 * Include column should be a comma-delimited list of 'Y-m-d'
	 * formatted dates.
	 * @param string $value
	 * @return array
	 */
	function parse_value_include( $value ){
		return $this->parse_date_list( $value );
	}
	
	/**
	 * Exclude column should be a comma-delimited list of 'Y-m-d'
	 * formatted dates.
	 * @param string $value
	 * @return array
	 */
	function parse_value_exclude( $value ){
		return $this->parse_date_list( $value );
	}
	
	/**
	 * Parse a list of dates given as comma-delimited list of 'Y-m-d'
	 * formatted dates.
	 * 
	 * @param string $value Comma-delimited list of dates
	 * @return array An array of DateTime objects
	 */
	function parse_date_list( $value ){
		if( !$value )
			return array();
	
		$dates = explode( ',', $value );
		$dates = array_filter( array_map( array( $this, 'parse_date' ), $dates ) );
		$dates =  $dates ? $dates : array();
		return $dates;
	}
	
	/**
	 * Schedule meta column should be of the form
	 * 
	 * **Weekly recurrence**
	 * Comma delimited list of days of of days given by their two-letter identifier. 
	 * E.g: "MO,TU,FR". 
	 * 
	 * **Montly recurrence**
	 * Either "BYDAY=" followed by  an integer (-1,1-4) and two-letter day identifier, 
	 * e.g. "BYDAY=2TH" for 2nd Thursday of every month. 
	 * 
	 * Or "BYMONTHDAY=" followed an integer (1 - 31) indicating the date on which 
	 * the even should repeat. E.g. "BYMONTHDATE=16" for every month on the 16th.
	 *
	 * @param string $value 
	 * @return array
	 */
	function parse_value_schedule_meta( $value, $item ){
		
		$schedule = 'unknown';
		
		if( isset( $item['schedule'] ) ){
			$schedule = $item['schedule'];
		}
		
		switch( strtolower( $schedule ) ){
			case 'weekly':
				$value = explode( ',', $value );
				break;
			
			case 'monthly':
				break;

			//Don't require schedule meta
			case 'once':
			case 'custom':
			case 'daily':
			case 'yearly':
				break;
			
			//Best guess
			default:
				$_value = explode( ',', $value );
				
				if( strpos( $schedule, 'BYDAY' ) !== false && strpos( $schedule, 'BYMONTHDAY' ) !==false ){
					//Assume monthtly - do nothing
				}elseif( count( $_value ) > 1 ){
					//Assuming weekly
					$value = $_value;
				}elseif( preg_match( '/^[A-Za-z]{2}$/', $value ) ){
					//Assuming weekly
					$value = $_value;
				}
		}
		
		return $value;
	}
	
}