(function($) {

    $(document).ready(function() {

        function showBlock(){

            var menuLink = $('#mobile-link');
            var widgetLink = $('#widget-link');

            var menuBlock = $('#mobile-block');
            var widgetBlock = $('#widget-block');

            var menuQuery = null;
            menuQuery = $("#mobile-block").detach();

            var widgetQuery = null;
            widgetQuery = $("#widget-block").detach();

            function adaptionToggleClass( $myvar ) {
                if ( $myvar.hasClass( 'active' ) ) {
                    $myvar.removeClass( 'active' );
                } else {
                    $myvar.addClass('active');
                }
            }

            menuLink.on('click', function() {

                $("#panel-block").html(menuQuery);

                menuBlock.slideToggle();
                adaptionToggleClass($(this));

                widgetBlock.removeClass('active');
            });

            widgetLink.on('click', function() {

                $("#panel-block").html(widgetQuery);

                widgetBlock.slideToggle();
                adaptionToggleClass($(this));

                menuBlock.removeClass('active');
            });
        }

    	$(document).on('ready', showBlock);
    });

    /**
    * Navigation sub menu show and hide
    *
    * Show sub menus with an arrow click to work across all devices
    * This switches classes and changes the genericon.
    * Note: Props Espied for the aria addition
    *
    */
    $( '.main-navigation .page_item_has_children > a, .main-navigation .menu-item-has-children > a' ).append( '<div class="showsub-toggle" aria-expanded="false"></div>' );

    $( '.showsub-toggle' ).click( function( e ) {
        e.preventDefault();
        $( this ).toggleClass( 'sub-on' );
        $( this ).parent().next( '.children, .sub-menu' ).toggleClass( 'sub-on' );
        $( this ).attr( 'aria-expanded', $( this ).attr( 'aria-expanded' ) == 'false' ? 'true' : 'false');
    } );

})(jQuery);