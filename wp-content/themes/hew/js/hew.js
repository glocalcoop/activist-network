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

		// Calculating how much space there is for website title, depending on whether site logo, widget & menu toggles are present
		function calcTitleWidth() {
			if ( 768 > $( document ).width() ) {
				var brandingWidth = $( '.site-header' ).innerWidth() - ( $( '.site-logo' ).outerWidth() + $( '.menu-toggle' ).outerWidth() + $( '.toggle-wrapper' ).outerWidth() );
				$( '.site-branding' ).css( 'width', brandingWidth + 'px' );
			} else {
				$( '.site-branding' ).css( 'width', 'auto' );
			}
		}

		$( document ).ready( function() {
			calcTitleWidth();
		} );

		$( window ).on( 'resize', function() {
			calcTitleWidth();
		} );

		$( document.body ).on( 'post-load', function () {
        	$( '.infinite-wrap .hentry:first-child' ).not( '.has-post-thumbnail' ).css( 'margin-top', '3.75em' );
    	} );

	} );
} )( jQuery );
