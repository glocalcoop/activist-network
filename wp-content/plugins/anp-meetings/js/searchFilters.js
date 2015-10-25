/**
 * 
 * Search Filter
 * 
 * 
 **/


(function($) {

    'use strict';

    //console.log( 'searchFilters.js is loaded' );

    var url = $( location ).attr( 'href' );

    $( '.filter' ).on( 'click', '.all', function( event ) {

        url = url.split('?')[0];
        window.location.replace( url );

    } );
    
})( jQuery );