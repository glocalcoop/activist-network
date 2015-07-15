
(function($) {

	'use strict';

	if( anpGlobalMenuVars.globalMenu ) {

		$( '<header class="header-global" id="anp-global-header"><div class="wrap"></div></header>' ).prependTo('body');

		$( '#anp-global-header' ).find( '.wrap' ).html( anpGlobalMenuVars.globalSiteLogo + anpGlobalMenuVars.prependMenu + '<nav id="anp-global-menu" class="nav-global" role="navigation">' + anpGlobalMenuVars.globalMenu + '</nav>' );
	
	} else {

		console.log( 'errorCode: ', anpGlobalMenuVars.errorCode, ' errorMessage: ', anpGlobalMenuVars.errorMessage );

	}

})( jQuery );