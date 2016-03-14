<?php

/*----------------------------------------------------------------------------------------------------------
    Uninstall Simple Author Box plugin - deletes plugin data in database
-----------------------------------------------------------------------------------------------------------*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'saboxplugin_options' );
// delete_option( 'sab_box_margin_top' );
// delete_option( 'sab_box_margin_bottom' );
// delete_option( 'sab_box_icon_size' );
// delete_option( 'sab_box_name_size' );
// delete_option( 'sab_box_name_font' );
// delete_option( 'sab_box_subset' );
// delete_option( 'sab_box_desc_font' );
// delete_option( 'sab_box_desc_size' );
// delete_option( 'sab_box_desc_font' );
// delete_option( 'sab_box_web_size' );