<?php
/*
Plugin Name: Which Template
Plugin URI: http://wordpress.org/extend/plugins/which-template/
Description: Helps the admin user work out which template a particular page is using.

Installation:

1) Install WordPress 4.0 or higher

2) Download the latest from:

http://wordpress.org/extend/plugins/which-template 

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 4.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

add_action('wp_footer', 'which_template_included_files');
function which_template_included_files() {
  $included_files = get_included_files();
  $GLOBALS["which_included_templates"] = array();
  foreach ($included_files as $filename) {
    if (preg_match("/header|footer|sidebar/", $filename) && !preg_match("/blog-header/", $filename)) {
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $temp = ltrim(strrchr($filename, '\\'), '\\');
      } else {
        $temp = ltrim(strrchr($filename, '/'), '/');
      }
      $p = get_template_directory();
      $p = str_replace("\\", "/", $p);
      $filename = str_replace("\\", "/", $filename);
      $GLOBALS["which_included_templates"] = array_merge($GLOBALS["which_included_templates"], array($temp => preg_replace("/^\//", "", str_replace($p, "", $filename))));
    }
  }
}

add_filter('template_include', 'which_template_template_include', 1000);
function which_template_template_include($template) {
	// Check if your on an admin page.
  if (is_admin()) {
  	// On admin page so don't display menu link.
  	return;
  } 
  // Check to see if user is logged in.
  if (is_user_logged_in()) {
  	// User is logged in so define constant for use later on.
    define('WHICH_TEMPLATE_USED', ltrim(strrchr($template, '/'), '/'));
    add_filter('admin_bar_menu', 'which_template_admin_bar_menu', 1000);
  }
  return $template;
}


function which_template_admin_bar_menu($template) {

	// Check if your on an admin page.
  if (is_admin()) {
  	// On admin page so don't display menu link.
  	return;
  } 
  // Check if user is logged in.
  if (is_user_logged_in()) {
  	// User is logged in, so display template info.
  	global $wp_admin_bar;
  	$link = get_option("siteurl")."/wp-admin/theme-editor.php?file=".WHICH_TEMPLATE_USED."&theme=".wp_get_theme()->Template;
	  $wp_admin_bar->add_menu(
      array(
        'id'      => "which_template_template_file",
        'title'   => "<a target='_blank' href='".$link."' style='color:gold !important;'>Template file : ".WHICH_TEMPLATE_USED."</a>",
      )
	  );

    foreach ($GLOBALS["which_included_templates"] as $key => $value) {
      $link = get_option("siteurl")."/wp-admin/theme-editor.php?file=".$value."&theme=".wp_get_theme()->Template;

      $template_header = "Template";

      if (preg_match("/header/i", $value)) {
        $template_header = "Header";
      } else if (preg_match("/footer/i", $value)) {
        $template_header = "Footer";
      } else if (preg_match("/sidebar/i", $value)) {
        $template_header = "Sidebar";
      }

      $wp_admin_bar->add_menu(
        array(
          'id'      => "which_template_".strtolower(str_replace(" ", "_", $value)),
          'title'   => "<a target='_blank' href='".$link."' style='color:gold !important;'>$template_header file : ".$key."</a>",
          'parent'  => 'which_template_template_file'
        )
      );
    }

  }
}

?>