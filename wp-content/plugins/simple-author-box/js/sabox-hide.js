if (typeof (jQuery) != 'undefined') {
    jQuery(document).ready(function () {
        validate();
        jQuery('input').change(function () {
            validate();
        })
    });

    function validate() {
        if (jQuery('input[id=sab-toggle-1]').is(':checked')) {
            jQuery('#saboxplugin-hide').show( 400 );
        } else {
            jQuery('#saboxplugin-hide').hide( 400 );
        }

        if (jQuery('input[id=sab-toggle-3]').is(':checked')) {
            jQuery('#saboxplugin-hide-two').show( 400 );
        } else {
            jQuery('#saboxplugin-hide-two').hide( 400 );
        }

        if (jQuery('input[id=sab-toggle-15]').is(':checked')) {
            jQuery('#saboxplugin-hide-three').show( 400 );
        } else {
            jQuery('#saboxplugin-hide-three').hide( 400 );
        }

    }
}