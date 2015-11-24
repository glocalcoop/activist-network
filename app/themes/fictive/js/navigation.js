jQuery(document).ready(function($) {

	function navMenu() {

		var sidebarToggle = $('#widgets-toggle');
		var menuToggle = $('#menu-toggle');
		var searchToggle = $('#search-toggle');

		var sidebarNav = $('#secondary');
		var searchNav = $('#header-search');
		var menuNav = $('#site-navigation');

		function myToggleClass( $myvar ) {
			if ( $myvar.hasClass( 'active' ) ) {
				$myvar.removeClass( 'active' );
			} else {
				$myvar.addClass('active');
			}
		}

		// Display/hide sidebar
		sidebarToggle.on('click', function() {
			sidebarNav.slideToggle();
			myToggleClass($(this));

			menuNav.hide();
			searchNav.hide();

			searchToggle.removeClass('active');
			menuToggle.removeClass('active');
		});
		// Display/hide menu
		menuToggle.on('click', function() {
			menuNav.slideToggle();
			myToggleClass($(this));

			searchNav.hide();
			sidebarNav.hide();

			searchToggle.removeClass('active');
			sidebarToggle.removeClass('active');
		});
		// Display/hide search
		searchToggle.on('click', function() {
			searchNav.slideToggle();
			myToggleClass($(this));

			sidebarNav.hide();
			menuNav.hide();

			sidebarToggle.removeClass('active');
			menuToggle.removeClass('active');
		});
	}
	$(window).on('load', navMenu);
} );