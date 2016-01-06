<?php 

# create new parseCSV object.
//$csv = new  EO_CSV_Parser( file_get_contents( '_books.csv' ) );
//$csv = new  EO_CSV_Parser( '_books.csv' );

class EO_CSV_Parser{

	// Data mapped to items array
	var $items = array();
	
	var $first_row_is_header = false;
	
	/**
	 * Array of the form
	 * [i] => {event key}
	 * where i is a 0-based column index, {event key} is used to identify object property 
	 * @var unknown_type
	 */
	var $column_map = array();

	//Constructor
	function __construct( $csv = false ){
		$this->csv = $csv;
	}
	
	function set_column_map( $map ){
		// removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
		$this->column_map = $map; //array_filter( $map, 'strlen' );
	}
	

	function map( $csv = false ){
		
		$this->csv = ( $csv ? $csv : $this->csv );
		
		$this->map_columns();
		
	}

	/**
	 * Converts parsed data into object array
	 */
	function map_columns(){
		
		$rows  = $this->csv->get_number_rows();
		$start = $this->first_row_is_header ? 1 : 0;
		
		if( $rows ){
			for( $r = $start; $r < $rows; $r++ ){
	
				//Initialise item array
				$item = array();
					
				$row = $this->csv->get_row( $r );
				
				if( !$row ){
					continue;
				}
				
				foreach( $row as $c => $value ){
					$key = isset( $this->column_map[$c] ) ? $this->column_map[$c] : false;
					if( $key ){
						$this->parse_value( utf8_encode( $value ), $key, $item );
					}
				}
				
				//Add event array
				$this->items[] = $item;
				
			}
		}
	}

	
	function parse_value( $value, $key, &$item ){
		$item[$key] = $value;
	}

}