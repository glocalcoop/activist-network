/*! Event Organiser CSV - v0.1.0
 * http://wp-event-organiser.com/
 * Copyright (c) 2013; * Licensed GPLv2+ */
/*global $:false, jQuery:false, eo_csv:false, eo_csv_data:false, document:false, alert:false, console:false*/
(function ($) {
	$(document).ready(function(){
		
		var eventorganisercsv = {};
		
		eventorganisercsv.errorMessage = function( message ){
			$('#eo-csv-error p').text( message );
			$('#eo-csv-error').show();
			$('#eo-csv-submit').attr('disabled','disabled').hide();
		};
		
		eventorganisercsv.clearError = function(){
			$('#eo-csv-error p').text( '' );
			$('#eo-csv-error').hide();
			$('#eo-csv-submit').removeAttr('disabled' ).show();
		};
		
		$('#eo-csv-preview').on( 'submit', function(ev){
			
			var selected = false;
			$(".eo-csv-import-column-selection select").each(function(){
				if( $(this).val() === "start" ){
					selected = true;
				}
			});
			 
			if( !selected ){
				eventorganisercsv.errorMessage( eo_csv.i18n.select_start_column );
				ev.preventDefault();
				return false;
			}
			
			return true;
		});
		
		
		//Toggle visibility of first row
		$('.eo-first-row-is-header').on( 'change', function(e){
			//$('.eo-csv-row-0').toggle(  !$(this).is(":checked") );
			$('.eo-csv-row-0').toggleClass( 'eo-csv-row-is-header',  $(this).is(":checked") );
		});

		//Toggle visibility of additional input for column map
		$('.eo-csv-table-wrap').on( 'change', '.eo-csv-col-map', function(e){
			eventorganisercsv.clearError();
			var $input = $(this).parent('td').find('.eo-csv-col-map-meta');
			$input.toggle( $(this).val() === 'post_meta' );
			$input.attr( "placeholder", $(this).find('option:selected').text() );
		});

		//Listen for delimiter change
		$('input[name="delimiter"]').change(function(e){
			
			eventorganisercsv.clearError();
			var delimiter;
			switch( $(this).val() ){
				case 'space':
					delimiter = " ";
				break;
				
				case 'tab':
					delimiter = "\t";
					break;
				
				case 'semicolon':
					delimiter = ";";
					break;
					
				default:
					delimiter = ",";
					break;
			}
			
			if( !eo_csv_data.hasOwnProperty( 'input' ) || !eo_csv_data.input ){
				eventorganisercsv.errorMessage( "Cannot read file content. Please check that the uploaded file is CSV encoded." );
				return;
			}
			
			var rows;
			try{
				rows = $.csv.toArrays(eo_csv_data.input, {
					delimiter:"\"", // sets a custom value delimiter character
					separator:delimiter // sets a custom field separator character
				});
			}catch( exception ){
				eventorganisercsv.errorMessage( "The CSV file is invalid with the chosen delimiter. Please try a different one.");
				console.log( exception );
				$('.eo-csv-table-wrap table thead').html('');
				$('.eo-csv-table-wrap table tfoot').html('');
				$('.eo-csv-table-wrap table tbody').html('');
				return;
			}
			
			var header_size = rows[0].length;
			
			var $table = $('.eo-csv-table-wrap table');
			var $tbody = $table.find('tbody').html( '' );
			var $thead = $table.find('tbody').html( '' );
			
			//Generate table header
			var $action_row = $( '<tr class="eo-csv-import-column-selection">' );
			var $label_row = $('<tr></tr>' );
			
			for( var c = 0; c < header_size; c++ ){
				
				var col_header = "";
				var index = c;
				
				if( index === 0 ){
					col_header= "A";
				}else{
					while( index >= 0 ){
						var digit = index % 26;
						index = Math.floor( index/26 ) -1;
						col_header = String.fromCharCode( 65 + digit ) + col_header;
					}
				}
				
				var select = '<td>' + 
						'<select class="eo-csv-col-map" name="column_map['+c+'][col]" style="width: 100%;" data-eo-csv-col="1">' +
							'<option value="0"> Please select </option>';
						
							for ( var key in eo_csv.columns ) {
								if( eo_csv.columns.hasOwnProperty( key ) ){
									select += '<option value="' + key + '">' + eo_csv.columns[key] + '</option>';
								}
							}
						select += '</select>' +
							'<input type="text" name="column_map['+c+'][other]" style="display:none" value="" class="eo-csv-col-map-meta">' + 
							'</td>';
				
				$action_row.append( $( select ) );
				$label_row.append( $( '<th>' + col_header + '</th>' ) );
				
			}
			
			$thead.append( $action_row );
			$thead.append( $label_row );

			//Generate table body
			var $row,$cell;
			for( var r = 0; r < rows.length; r++ ){
				$row = $('<tr class="eo-csv-row-'+r+'"></tr>' );
				
				for( c = 0; c < header_size; c++ ){
					var value = rows[r][c];
					$cell = $('<td><div class="eo-csv-cell-content"></div></td>').text( value );
					$row.append($cell);
				}
				$tbody.append($row);
			}
						
		});
		
		
		if( !eo_csv_data.hasOwnProperty( 'input' ) || !eo_csv_data.input ){
			eventorganisercsv.errorMessage( "Cannot read file content. Please check that the uploaded file is CSV encoded." );
		
		}else{
		
			//Try all delimiters and pick the first one without an error
			var delimiters = [ " ", "\t", ",", ";" ];
			for( var i = 0; i < delimiters.length; i++ ){
				
				var delimiter = delimiters[i];
				
				try{
					$.csv.toArrays( eo_csv_data.input, {
						delimiter:"\"", // sets a custom value delimiter character
						separator:delimiter // sets a custom field separator character
					});
					$('input[name="delimiter"]').eq(0).click();
					break;
					
				}catch( exception ){
					continue;
				}
				
			}
		}	
		
	});
	
})(jQuery);