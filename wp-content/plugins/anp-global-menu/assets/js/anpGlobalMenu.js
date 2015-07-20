/**
 * Inserts global header mark-up directly below body tag
 *
 * @version 1.0
 */


(function($) {

	'use strict';

	if( anpGlobalMenuVars.globalMenu ) {

		$( '<header class="header-global" id="anp-global-header"><div class="wrap"></div></header>' ).prependTo('body');

		$( '#anp-global-header' ).find( '.wrap' ).html( anpGlobalMenuVars.globalSiteLogo + '<a class="mobile" href="#"> </a><nav id="anp-global-menu" class="nav-global" role="navigation">' + anpGlobalMenuVars.globalMenu + '</nav>' );
	
	} else {

		console.log( 'errorCode: ', anpGlobalMenuVars.errorCode, ' errorMessage: ', anpGlobalMenuVars.errorMessage );

	}

	$('.mobile').click(function () {
		$('nav').toggleClass('active'); 
	});

	$('nav ul li ul').each(function() {
		$(this).before('<span class=\"arrow\"></span>');
	});

	$('nav ul li').click(function(event) {
		event.preventDefault();
		$(this).children('ul').toggleClass('active');
		$(this).children('.arrow').toggleClass('rotate');
	});

})( jQuery );