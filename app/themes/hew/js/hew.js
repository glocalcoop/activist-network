( function( $ ) {
	$( document ).ready( function() {

		// Focus styles for menus.
		$( '.main-navigation' ).find( 'a' ).on( 'focus.hew blur.hew', function() {
			$( this ).parents().toggleClass( 'focus' );
		} );

		$( '.widgets-toggle' ).click( function( e ) {

			e.preventDefault();

			$( 'body,html' ).animate( {
				scrollTop: 0
			}, 400 );

			$( '#widgets-wrapper' ).slideToggle( 400, function() {} );
			$( '.widgets-toggle' ).toggleClass( 'open' );

			// Trigger for Jetpack to display Tiled Gallery widget when top panel is open
			$( window ).trigger( 'resize' );
		});

		$( document.body ).on( 'post-load', function () {
        	$( '.infinite-wrap .hentry:first-child' ).not( '.has-post-thumbnail' ).css( 'margin-top', '3.75em' );
    	} );

	} );
} )( jQuery );
