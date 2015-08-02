/* Scroll past header image on small screens */

jQuery( document ).ready( function( $ ) {

	var $width = $(window).width();
	var $height = $(window).height();
	var $masthead = $( '.site-header' );
	var $timeout = false;
	var $sidebarheight = $masthead.height() + 100;

	//Allow sidebar to scroll if the sidebar is too tall for the screen height
	if ( $sidebarheight > $height ) {
		$masthead.css( 'position', 'relative' );
	}

	//Toggle open $class by clicking $toggle
	$.fn.navToggle = function() {
		$( '.main-navigation' ).addClass( 'active' );

		$( '#menu-toggle' ).unbind( 'click' ).click( function() {

			$( '.widget-area' ).hide().removeClass( 'active' );
			$( '.header-search' ).hide().removeClass( 'active' );

			$( '.main-navigation' ).slideToggle( 'ease' );
			$( this ).toggleClass( 'toggled-on' );
		} );
	};

	$.fn.widgetsToggle = function() {
		$( '.widget-area' ).addClass( 'active' );

		$( '#widgets-toggle' ).unbind( 'click' ).click( function() {

			$( '.main-navigation' ).hide().removeClass( 'active' );
			$( '.header-search' ).hide().removeClass( 'active' );

			$( '.widget-area' ).slideToggle( 'ease' );
			$( this ).toggleClass( 'toggled-on' );
		} );
	};

	$.fn.searchToggle = function() {
		$( '.header-search' ).addClass( 'active' );

		$( '#search-toggle' ).unbind( 'click' ).click( function() {

			$( '.main-navigation' ).hide().removeClass( 'active' );
			$( '.widget-area' ).hide().removeClass( 'active' );

			$( '.header-search' ).slideToggle( 'ease' );
			$( this ).toggleClass( 'toggled-on' );
		} );
	};


	// Check viewport width on first load.
	if ( $width < 820 ) {
		$.fn.navToggle();
		$.fn.widgetsToggle();
		$.fn.searchToggle();
	}

	// Check viewport width when user resizes the browser window.
	$( window ).on( 'resize', function() {

		$width = $(window).width();

		if ( false !== $timeout ) {
			clearTimeout( $timeout );
		}

		$timeout = setTimeout( function() {

			//Allow sidebar to scroll if the sidebar is too tall for the screen height
			if ( $sidebarheight > $height ) {
				$masthead.css( 'position', 'relative' );
			}

			if ( $width < 820 ) {
				$.fn.navToggle();
				$.fn.widgetsToggle();
				$.fn.searchToggle();
			} else {
				$( '.main-navigation' ).removeClass( 'active' );
				$( '.widget-area' ).removeClass( 'active' );
				$( '.header-search' ).removeClass( 'active' );

				$( '.main-navigation' ).removeAttr( 'style' );
				$( '.widget-area' ).removeAttr( 'style' );
				$( '.header-search' ).removeAttr( 'style' );
			}
		}, 200 );
	} );


});
