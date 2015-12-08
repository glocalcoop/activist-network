/*global _:false */
/**
 * thumbnail.js
 *
 * Handles resizing of the post thumbnail for small screens.
 */
( function( $ ) {

	function sequential_thumbnail() {

		var post_thumbnail;

		$( '.hentry' ).each( function() {
			post_thumbnail = $( this ).find( '.post-thumbnail' );

			post_thumbnail.css( 'width', '' );

			if ( $( window ).width() >= 768 ) {
				post_thumbnail.css( 'width', '' );
			} else if ( $( window ).width() >= 600 ) {
				post_thumbnail.css( 'width', $( this ).width() + 48 );
			} else {
				post_thumbnail.css( 'width', $( this ).width() + 24 );
			}
		} );

	}

	$( window ).load( sequential_thumbnail ).resize( _.debounce( sequential_thumbnail, 100 ) );
	$( document ).on( 'post-load', sequential_thumbnail );

} )( jQuery );
