/**
 * Inserts global header mark-up directly below body tag
 *
 * @version 1.0
 */


(function($) {

	'use strict';

	if( anpGlobalMenuVars.globalMenu ) {

		$( '<header class="header-global" id="anp-global-header"><div class="wrap"></div></header>' ).prependTo('body');

		$( '#anp-global-header' ).find( '.wrap' ).html( anpGlobalMenuVars.globalSiteLogo + '<a class="mobile menu-link" href="#" rel="icon"><span class="hide-text">MENU</span></a><nav id="anp-global-menu" class="menu-global" role="navigation">' + anpGlobalMenuVars.globalMenu + '</nav>' );
	
	} else {

		console.log( 'errorCode: ', anpGlobalMenuVars.errorCode, ' errorMessage: ', anpGlobalMenuVars.errorMessage );

	}

	$('.mobile').click(function () {
		$('nav').toggleClass('active'); 
	});

	$('.menu-item-has-children').click(function(event) {
		//event.preventDefault();
		$(this).children('a').toggleClass('active');
	});

})( jQuery );