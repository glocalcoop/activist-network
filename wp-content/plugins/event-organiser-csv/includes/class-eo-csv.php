<?php
class EO_CSV{
	
	public $delimiter = ',';
	
	public $entries;
	
	public $column_num = false;
	
	protected function __reset(){
		$this->entiries = array();
	}
	
	function parse( $input ){
		
		$this->__reset();
		
		//Local file
		if( is_file( $input ) && file_exists( $input )  ){
			$this->parse_file( $input );
			
		//Remote file
		}elseif( preg_match('!^(http|https|ftp)://!i', $input ) ){
			$content = wp_remote_retrieve_body( wp_remote_get( $input ) );
			if( $content ){
				$this->parse_string( $content );
			}
			
		//String
		}else{
			$this->parse_string( $input );
		}
		
	}
	
	function get_rows(){
		foreach( $this->entries as $r => $row ){
			$this->entries[$row] = array_pad( $this->entries[$row], $this->column_num, null );	
		}
		return $this->entries;
	}
	
		
	function get_row( $row ){
		
		if( isset( $this->entries[$row] ) ){
			$this->entries[$row] = array_pad( $this->entries[$row], $this->column_num, null );
			return $this->entries[$row];
		}
		
	}
	
	function insert_row( $row ){
		$this->column_num = max( count( $row ), $this->column_num );
		$this->entries[] = array_pad( $row, $this->column_num, null );
	}
	
	function set_row( $row_index, $row ){
		
		while( $row_index > count( $this->entries ) ){
			$this->insert_row( array() );		
		}
	
		$this->column_num = max( count( $row ), $this->column_num );
		
		$row = array_pad( $row, $this->column_num, null );
					
		$this->entries[$row_index] = $row;
		
	}
	
	
	function get_column( $col ){
		
		if( false == $this->column_num || $col > $this->column_num ){
			return false;
		}
		
		$target_col = array();
		
		foreach( $this->entries as $i => $row ){
			$target_col[$i] = $row[$col];
		}
		
		return $target_col;
	
	}
	
	
	function get_cell( $row, $col = false ){
		
		//TODO support get_cell( "A1" ) and/or get_cell( 1, "A" ); 
		if( !isset( $this->entries[$row] ) ){
			return false;
		}
		
		if( false == $this->column_num || $col > $this->column_num ){
			return false;
		}
		
		$this->entries[$row] = array_pad( $this->entries[$row], $this->column_num, null );
		
		return $this->entries[$row][$col];
	}

	
	function get_number_rows(){
		
		if( !$this->entries ){
			return 0;
		}
		
		end( $this->entries );
		$key = key( $this->entries );
		reset( $this->entries );

		return $key + 1;
	}
	
	function get_number_columns(){
		return $this->$column_num;
	}
	
	
	protected function parse_file( $filename ){

		if ( ( $handle = fopen( $filename, 'r' ) ) !== FALSE ){
						
			while ( ( $line = fgetcsv( $handle, 1000, $this->delimiter ) ) !== FALSE ){
				if( array_filter( $line, 'trim' ) ) {
					$this->insert_row( $line );
				}
        	}
		
			fclose( $handle );
    	}
    		
	}
	
	protected function parse_string( $input ){
			
		$lines = str_getcsv( $input, "\n" );

		foreach( $lines as $line ){
			$line_data = str_getcsv( $line, $this->delimiter );
			if( array_filter( $line_data, 'trim' ) ) {
				$this->insert_row( $line_data );
			} 
		}
		
	}
	
}