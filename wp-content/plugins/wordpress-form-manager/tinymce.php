<?php
/****************************************************************/
/*********** TINYMCE BUTTON**************************************/

function add_formmanager_button() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_form_manager_tinymce_plugin");
     add_filter('mce_buttons', 'register_WPformManager_button');
   }
}
 
function register_WPformManager_button($buttons) {
   array_push($buttons, "|", "WPformManager");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_form_manager_tinymce_plugin($plugin_array) {
   $plugin_array['WPformManager'] = plugins_url('mce_plugins/editor_plugin.js',__FILE__);
   return $plugin_array;
}
 
function fm_refresh_mce($ver) {
  $ver += 3;
  return $ver;
}

// init process for button control
add_filter( 'tiny_mce_version', 'fm_refresh_mce');
add_action('init', 'add_formmanager_button');
/****************************************************************/
/***********  END TINYMCE BUTTON ********************************/
?>