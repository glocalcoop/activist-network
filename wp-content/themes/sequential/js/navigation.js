/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
( function( $ ) {

	var main_navigation = $( '.main-navigation' );

	function menu_responsive() {

		var elements_width = $( '.site-branding' ).width() + main_navigation.width() + 72,
		    wrapper_width = $( '.site-header > .wrapper' ).width();

		if ( elements_width > wrapper_width && $( window ).width() >= 1020 ) {
			$( 'body' ).addClass( 'menu-left' );
		} else {
			$( 'body' ).removeClass( 'menu-left' );
		}

		if ( $( window ).width() < 1020 ) {
			$( '.site-branding' ).css( 'width', '100%' )
			                     .css( 'width', '-=72px' );
		} else {
			$( '.site-branding' ).css( 'width', '' )
		}

		$( '.main-navigation .page_item_has_children > a, .main-navigation .menu-item-has-children > a' ).addClass( 'dropdown-link' );

		$( '.dropdown-link' ).each( function() {

			if ( $( this ).children( '.dropdown-toggle' ).length === 0 ) {
				$( this ).append( '<button class="dropdown-toggle" aria-expanded="false" />' );
			}
			if ( $( window ).width() >= 1020 ) {
				$( this ).children( '.dropdown-toggle' ).remove();
			}

		} );

	}

	function menu_toggles() {

		$( '.menu-toggle' ).click( function( e ) {

			e.preventDefault();
			if ( main_navigation.hasClass( 'toggled' ) ) {
				$( this ).attr( 'aria-expanded', 'false' );
				main_navigation.removeClass( 'toggled' );
			} else {
				$( this ).attr( 'aria-expanded', 'true' );
				main_navigation.addClass( 'toggled' );
			}

		} );

		$( '.dropdown-toggle' ).click( function( e ) {

			e.preventDefault();
			if ( $( this ).hasClass( 'toggle-on' ) ) {
				$( this ).attr( 'aria-expanded', 'false' );
				$( this ).removeClass( 'toggle-on' );
				$( this ).parent( 'a' ).removeClass( 'toggle-on' );
				$( this ).parent().next( '.sub-menu' ).removeClass( 'toggle-on' );
			} else {
				$( this ).attr( 'aria-expanded', 'true' );
				$( this ).addClass( 'toggle-on' );
				$( this ).parent( 'a' ).addClass( 'toggle-on' );
				$( this ).parent().next( '.sub-menu' ).addClass( 'toggle-on' );
			}

		} );

	}

	function sequential_menu() {

		menu_responsive();
		menu_toggles();

	}

	$( document ).ready( sequential_menu );
	$( window ).resize( _.debounce( menu_responsive, 100 ) );

} )( jQuery );
