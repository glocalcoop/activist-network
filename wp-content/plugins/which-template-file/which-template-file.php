<?php
/*
Plugin Name: Which Template File
Description: Show which php file of your theme is used to display the current page in your front office.
Version: 3.1
Author: Gilles Dumas
Author URI: http://gillesdumas.com
*/



add_action('admin_bar_menu', 'gwp_my_admin_bar_menu', 9999);
function gwp_my_admin_bar_menu($wp_admin_bar) {

	if (is_admin()) return;

	global $user_ID, $template;
    if ($user_ID == 0) {
        return $template;
    }

    $userdatas = get_userdata($user_ID);
    if (isset($userdatas->roles) && (is_array($userdatas->roles)) ) {
        if (in_array('administrator', $userdatas->roles)) {
            if (strpos($template, '/') !== false) {
                $gwp_my_template_file = ltrim(strrchr($template, '/'), '/');
            }
            else {
                $gwp_my_template_file = $template;
            }
			$args = array(
				'id'      => '_gwp_my_template_file',
				'title'   => '<span style="color:hotpink !important;">Template file : '.$gwp_my_template_file.'</span>',
				'meta'   => array(
					'title' => $template,
					'class' => 'class_gwp_my_template_file'
				)
			);
			$wp_admin_bar->add_node($args);
        }
    }
}

add_action('wp_head', 'which_template_file_style');
function which_template_file_style() {
	?>
	<style type="text/css">
		.class_gwp_my_template_file {
			cursor:help;
		}
	</style>
	<?php
}









 