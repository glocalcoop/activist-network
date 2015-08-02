jQuery( function( $ ) {
	$( '.flexslider' ).flexslider({
		slideshow:      false,
		animation:      'fade',
		controlNav:     false,
		prevText:       '&#8249;',
		nextText:       '&#8250;',
		slideshowSpeed: '14000',
		animateHeight:  true
	});

	$( '.minislides .carousel' ).flexslider({
		animation:     'slide',
		slideshow:     false,
		animationLoop: false,
		itemWidth:     62,
		itemHeight:    62,
		directionNav:  true,
		controlNav:    false,
		prevText:      '&#8249;',
		nextText:      '&#8250;',
		animationLoop: false,
	  });
} );
