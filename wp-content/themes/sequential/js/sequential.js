( function( $ ) {

	$( window ).load( function() {

		/* Add hover class to Search Submit */
		function add_class() {
			$( this ).closest( '.search-form' ).addClass( 'hover' );
		}

		function remove_class() {
			$( this ).closest( '.search-form' ).removeClass( 'hover' );
		}
		$( '.search-submit' ).hover( add_class, remove_class );
		$( '.search-submit' ).focusin( add_class );
		$( '.search-submit' ).focusout( remove_class );

		/* Remove Content Area if empty */
		var content_area = $( '.content-area' );
		if ( ! content_area.find( 'img' ).length && $.trim( content_area.text() ) === '' ) {
			content_area.remove();
		}

		/* Remove Comment Reply if empty */
		$( '.comment .reply' ).each( function() {
			if ( $.trim( $( this ).text() ) === '' ) {
				$( this ).remove();
			}
		} );

		/* Add class to last column element */
		var child_number = 1;
		$( '.column-1-2' ).filter( ':odd' ).addClass( 'last-column' );
		$( '.column-1-3' ).each( function() {
			if ( child_number % 3 === 0 ) {
				$( this ).addClass( 'last-column' );
			}
		    child_number++;
		} );

	} );

} )( jQuery );
