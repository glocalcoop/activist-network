/*jslint white: true */
/**
 * 
 * Search Filter
 * 
 * 
 **/

jQuery( document ).ready( function( $ ) {

    console.log( 'filters.js loaded' );

    var queryVars = getUrlVars();

    function getUrlVars() {

        var url = window.location.href;
        var vars = {};
        var hashes = url.split( '?' )[1];

        if( hashes ) {
            var hash = hashes.split( '&' );
        } else {
            return false;
        }
        
        for ( var i = 0; i < hash.length; i++ ) {
            params=hash[i].split( '=' );
            vars[params[0]] = params[1];
        }

        return vars;
    }

    if( queryVars ) {

        $( 'li[data-filter=' + queryVars.meeting_type + ']' ).addClass( 'active' );

        $( 'li[data-filter=' + queryVars.meeting_tag + ']' ).addClass( 'active' );

        $( 'li[data-filter=' + queryVars.proposal_status + ']' ).addClass( 'active' );

    } else {

        $( 'li.all' ).addClass( 'active' );

    }



} );