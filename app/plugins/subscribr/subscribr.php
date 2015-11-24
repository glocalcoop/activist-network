<?php
/*
Plugin Name: Subscribr
Plugin URI: https://mindsharelabs.com/downloads/subscribr/
Description: Allows WordPress users to subscribe to email notifications for new posts, pages, and custom types, filterable by taxonomies.
Version: 0.1.9.1
Author: Mindshare Studios, Inc.
Author URI: http://mind.sh/are/
License: GNU General Public License
License URI: LICENSE
Text Domain: subscribr
Domain Path: /lang
*/

/**
 *
 * @author    Mindshare Studios, Inc.
 * @copyright Copyright (c) 2014
 * @link      http://www.mindsharelabs.com/documentation/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 * Changelog:
 *
 * 0.1.9.1 - Bugfixes for terms selections, added subscribr_disabled_terms filter
 * 0.1.9 - Bugfix for auto-draft action
 * 0.1.8 - Bugfix for email sends using default settings
 * 0.1.7 - Bugfix for custom taxonomies
 * 0.1.5 - CSS fixes, verified PHP 5.3 support, updated Chosen JS library, update screenshots, bugfix for removing user prefs
 * 0.1.4 - Bugfixes for disabled post types
 * 0.1.3 - added custom email template options, added copy to theme folder option, added import/export options tab, added Type support & better Taxonomies support, fixes for WP 3.8, fixes to register
 * screen, fix for is_register fn, disable main.js file for now, misc minor bugfixes
 * 0.1.2 - bugfix for subscribr_profile_title filter,
 * 0.1.1 - Minor updates, fixed date_format, fix for only one notification getting sent
 * 0.1 - Initial release
 *
 */

if(!defined('SUBSCRIBR_MIN_WP_VERSION')) {
	define('SUBSCRIBR_MIN_WP_VERSION', '3.8');
}

if(!defined('SUBSCRIBR_PLUGIN_NAME')) {
	define('SUBSCRIBR_PLUGIN_NAME', 'Subscribr');
}

if(!defined('SUBSCRIBR_PLUGIN_SLUG')) {
	define('SUBSCRIBR_PLUGIN_SLUG', dirname(plugin_basename(__FILE__))); // subscribr
}

if(!defined('SUBSCRIBR_DIR_PATH')) {
	define('SUBSCRIBR_DIR_PATH', plugin_dir_path(__FILE__));
}

if(!defined('SUBSCRIBR_DIR_URL')) {
	define('SUBSCRIBR_DIR_URL', trailingslashit(plugins_url(NULL, __FILE__)));
}

if(!defined('SUBSCRIBR_OPTIONS')) {
	define('SUBSCRIBR_OPTIONS', 'subscribr_options');
}

if(!defined('SUBSCRIBR_TEMPLATE_PATH')) {
	define('SUBSCRIBR_TEMPLATE_PATH', trailingslashit(get_template_directory()).trailingslashit(SUBSCRIBR_PLUGIN_SLUG));
	// e.g. /wp-content/themes/__ACTIVE_THEME__/subscribr
}

// check WordPress version
global $wp_version;
if(version_compare($wp_version, SUBSCRIBR_MIN_WP_VERSION, "<")) {
	exit(SUBSCRIBR_PLUGIN_NAME.' requires WordPress '.SUBSCRIBR_MIN_WP_VERSION.' or newer.');
}

// deny direct access
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if(!class_exists("Subscribr")) {

	/**
	 * Class Subscribr
	 */
	class Subscribr {

		/**
		 * The plugin version number.
		 *
		 * @var string
		 */
		private $version = '0.1.9.1';

		/**
		 * @var $options - holds all plugin options
		 */
		public $options;

		/**
		 * Initialize the plugin. Set up actions / filters.
		 *
		 */
		public function __construct() {

			// i8n
			add_action('plugins_loaded', array($this, 'load_textdomain'));

			// setup the options page
			add_action('init', array($this, 'options_init'));

			// filesystem functions
			add_action('init', array($this, 'copy_default_templates'));

			// load scripts, etc
			add_action('wp_print_scripts', array($this, 'print_scripts'));
			add_action('admin_head', array($this, 'head_scripts'));
			add_action('wp_head', array($this, 'head_scripts'));
			add_action('login_head', array($this, 'head_scripts'));

			// action links
			add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);

			// add meta box
			if(is_admin()) {
				add_action('subscribr_post_defaults', array($this, 'add_opt_out_meta_box'));
			}

			// hooks for email notifications, setup up pretty much the same way that we would in a separate add-on
			add_action('subscribr_profile_fields', array($this, 'email_profile_fields'));
			add_action('subscribr_update_user_meta', array($this, 'email_update_user_meta'), 10, 2);
		}

		/**
		 * Returns the class name and version.
		 *
		 * @return string
		 */
		public function __toString() {
			return get_class($this).' '.$this->get_version();
		}

		/**
		 * Returns the plugin version number.
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Register the plugin text domain for translation
		 *
		 */
		public function load_textdomain() {
			load_plugin_textdomain('subscribr', FALSE, SUBSCRIBR_PLUGIN_SLUG);
		}

		/**
		 *
		 */
		public function add_opt_out_meta_box() {

			include_once('views/meta-box.php');
			new opt_out_meta_box($this->options);
		}

		/**
		 * Enqueues plugin CSS/JS.
		 *
		 */
		public function print_scripts() {

			if($this->do_scripts()) {

				// register scripts
				$scripts = array();

				$scripts[] = array(
					'handle' => 'chosen-js',
					'src'    => SUBSCRIBR_DIR_URL.'lib/chosen/chosen.jquery.min.js',
					'deps'   => array('jquery')
				);

				/*$scripts[] = array(
					'handle' => 'subscribr',
					'src'    => SUBSCRIBR_DIR_URL.'js/main.js',
					'deps'   => array('jquery')
				);*/

				foreach($scripts as $script) {
					wp_enqueue_script($script['handle'], $script['src'], $script['deps'], $this->version);
				}

				// register styles
				$styles = array(
					'chosen-css'    => SUBSCRIBR_DIR_URL.'lib/chosen/chosen.min.css',
					'subscribr-css' => SUBSCRIBR_DIR_URL.'css/subscribr.min.css',
				);

				foreach($styles as $k => $v) {
					wp_enqueue_style($k, $v, FALSE, $this->version);
				}
			}
		}

		/**
		 * Outputs JS into the HEAD
		 *
		 */
		public function head_scripts() {

			if($this->do_scripts()) {
				?>
				<script type="text/javascript">
					jQuery.noConflict();
					jQuery(document).ready(function() {

						jQuery('.chosen-select').chosen({
							search_contains:           true,
							width:                     '100%',
							placeholder_text_multiple: '<?php echo apply_filters('subscribr_terms_search_placeholder', sprintf(__('Select or search for %s', 'subscribr'), $this->get_option('notifications_label'))); ?>',
							no_results_text:           '<?php echo apply_filters('subscribr_terms_search_no_results', __('No results', 'subscribr')); ?>'
						});
					});
				</script>
			<?php
			}
		}

		/**
		 * @return mixed|void
		 */
		public function do_scripts() {
			// only enqueue if we're on the register screen, user profile, or Theme_My_Login pages (and the options are enabled)
			if(($this->is_register() && $this->get_option('show_on_register')) || ($this->is_profile() && $this->get_option('show_on_profile')) || (class_exists('Theme_My_Login')) || ($this->is_user_edit() && $this->get_option('show_on_profile') || $this->is_settings_page())) {
				$do_scripts = TRUE;
			} else {
				$do_scripts = FALSE;
			}

			// allow add-on plugins to filter when scripts get enqueued
			return apply_filters('subscribr_do_scripts', $do_scripts);
		}

		/**
		 *
		 * Add settings link to plugins page
		 *
		 * @param $links
		 * @param $file
		 *
		 * @return array
		 */
		public function plugin_action_links($links, $file) {
			if($file == plugin_basename(__FILE__)) {
				$settingslink = '<a href="options-general.php?page='.SUBSCRIBR_PLUGIN_SLUG.'-settings" title="'.__('Email Subscribe Settings', 'subscribr').'">'.__('Settings', 'subscribr').'</a>';
				array_unshift($links, $settingslink);
			}

			return $links;
		}

		/**
		 * Check saved options, perform related actions
		 *
		 */
		public function options_init() {
			// load the options framework
			include_once('lib/mindshare-options-framework/mindshare-options-framework.php');
			include_once('views/options-page.php');

			// load existing options
			$this->options = get_option(SUBSCRIBR_OPTIONS);
			include_once('controllers/options-init.php');
			new subscribr_options($this->options);
			//$this->notification_send(162, 1); // debugging
		}

		/**
		 * Adds additional fields to profile if global email option is enabled
		 *
		 */
		public function email_profile_fields($user) {
			if($this->get_option('enable_mail_notifications') && $this->get_option('enable_html_mail')) {
				$notifications_label = $this->get_option('notifications_label');
				if($user) {
					$subscribr_send_html = get_user_meta($user->ID, 'subscribr-send-html', TRUE);
				} else {
					$subscribr_send_html = FALSE;
				}
				include_once('views/email-profile-fields.php');
			}
		}

		/**
		 * Displays the custom user fields on the registration and profile screens.
		 *
		 * @param $user
		 */
		public function user_profile_fields($user) {

			// determine what taxonomies are enabled for email notification, if any
			$enabled_taxonomies = $this->get_enabled_taxonomies();
			$enabled_terms = $this->get_enabled_terms();

			if(!is_array($enabled_taxonomies)) {
				// no terms are enabled, exit now
				return;
			}

			if($user) {
				$subscribed_terms = get_user_meta($user->ID, 'subscribr-terms', TRUE);
				$subscribr_pause = get_user_meta($user->ID, 'subscribr-pause', TRUE);
				$subscribr_unsubscribe = get_user_meta($user->ID, 'subscribr-unsubscribe', TRUE);
			} else {
				$subscribed_terms = array();
				$subscribr_pause = FALSE;
				$subscribr_unsubscribe = FALSE;
			}

			$notifications_label = $this->get_option('notifications_label');

			do_action('subscribr_pre_profile');

			include_once('views/profile-fields.php');
		}

		/**
		 * @param $user_id
		 *
		 * @param $post_array
		 *
		 * @return bool
		 */
		public function email_update_user_meta($user_id, $post_array) {
			if(array_key_exists('subscribr-send-html', $post_array) && $post_array['subscribr-send-html'] == 1) {
				// the user wants HTML email
				$enable_html_mail = 1;
			} else {
				$enable_html_mail = 0;
			}

			update_user_meta($user_id, 'subscribr-send-html', $enable_html_mail);
		}

		/**
		 * @param $user_id
		 *
		 * @return bool
		 */
		public function update_user_meta($user_id) {

			// Check if our nonce is set and valid
			if(!(current_user_can('edit_user', $user_id) || (!isset($_POST['subscribr_update_user_meta_nonce']) || !wp_verify_nonce($_POST['subscribr_update_user_meta_nonce'], 'subscribr_update_user_meta')))) {
				return FALSE;
			}

			if(array_key_exists('subscribr-terms', $_POST)) {
				$subscribr_terms = array();

				// delete any invalid terms the user may have typed in manually
				foreach($_POST['subscribr-terms'] as $term) {
					$term_result = term_exists($term);
					if($term_result !== 0 && $term_result !== NULL) {
						$subscribr_terms[] = $term;
					}
				}
			} else {

				// no terms were selected
				$subscribr_terms = FALSE;
			}

			if(array_key_exists('subscribr-pause', $_POST) && $_POST['subscribr-pause'] == 1) {
				// the user is pausing
				$subscribr_pause = 1;
			} else {
				$subscribr_pause = 0;
			}

			if(array_key_exists('subscribr-unsubscribe', $_POST) && $_POST['subscribr-unsubscribe'] == 1) {
				// the user is unsubscribing
				$subscribr_unsubscribe = 1;
				$subscribr_terms = FALSE; // remove existing notifications
				$subscribr_pause = 0;
			} else {
				$subscribr_unsubscribe = 0;
			}
			update_user_meta($user_id, 'subscribr-unsubscribe', $subscribr_unsubscribe);
			update_user_meta($user_id, 'subscribr-terms', $subscribr_terms);
			update_user_meta($user_id, 'subscribr-pause', $subscribr_pause);

			// hook to add additional user options in add-ons
			do_action('subscribr_update_user_meta', $user_id, $_POST);
		}

		/**
		 *
		 * When a new post is saved find all users with matching notification preferences.
		 *
		 * @param $post_id
		 */
		public function queue_notifications($post_id) {

			// different WP hooks will send either the post ID or the actual post object, so we need to test for both cases
			if(is_a($post_id, 'WP_Post')) {
				$post_id = $post_id->ID;
			}

			if((array_key_exists('subscribr_opt_out', $_POST) && !$this->is_true($_POST['subscribr_opt_out'])) || !array_key_exists('subscribr_opt_out', $_POST)) {

				if(!wp_is_post_revision($post_id)) {

					$post = get_post($post_id);

					// quit if post has been published already
					if($post->post_date != $post->post_modified) {
						return;
					}

					// quit if the post type is not enabled
					$enabled_types = $this->get_enabled_types();
					if(!is_array($enabled_types) || !in_array($post->post_type, $enabled_types)) {
						return;
					}

					// query users with active notification preferences
					$active_user_ids = new WP_User_Query(
						array(
							'fields'     => 'id',
							//'fields' => 'all_with_meta',
							// check for any subscribed terms
							'meta_query' => array(
								array(
									'key'     => 'subscribr-terms',
									'value'   => '',
									'compare' => '!='
								),
								// make sure notifications are not disabled or paused
								array(
									'key'     => 'subscribr-pause',
									'value'   => 1,
									'compare' => '!='
								),
								array(
									'key'     => 'subscribr-unsubscribe',
									'value'   => 1,
									'compare' => '!='
								)
							)
						)
					);

					// grab the terms (as an array instead of an object)
					$post_terms = json_decode(json_encode(wp_get_object_terms($post_id, $this->get_enabled_taxonomies())), TRUE);

					// array to hold matched users
					$notify_user_ids = array();

					// 1. loop through the subscribed users
					foreach($active_user_ids->get_results() as $user_id) {
						$user_id = intval($user_id); // data type correction
						$subscribr_terms = get_user_meta($user_id, 'subscribr-terms', TRUE);
						if(is_array($subscribr_terms)) {

							// 2. loop through the subscribed terms
							foreach($subscribr_terms as $term) {

								// 3. loop through the post terms to test for a match
								foreach($post_terms as $post_term) {
									if($post_term['slug'] == $term) {

										// 4. we've got a match, add the user to the notify array
										$notify_user_ids[] = $user_id;
									}
								}
							}
						}
					}

					// remove duplicates so we don't send mail more than once!
					$notify_user_ids = array_unique($notify_user_ids, SORT_NUMERIC);

					if(!empty($notify_user_ids)) {

						foreach($notify_user_ids as $user_id) {

							do_action('subscribr_pre_user_query', $post, $user_id); // likely the best spot to plugin other types of notifications (SMS, etc)

							// email notifications
							if($this->get_option('enable_mail_notifications')) {

								// test for public post statuses, this allows for custom statuses as well as the default 'publish'
								$post_status = get_post_status_object(get_post_status($post_id));
								if($post_status->public) {
									$this->notification_send($post_id, $user_id);
								}
							}

							// add other notification methods here
							do_action('subscribr_post_user_query');
						}
					} else {

						// no matches
						do_action('subscribr_empty_user_query');
					}
				}
			}
		}

		/**
		 * Handles send out notifications to subscribed users.
		 *
		 * @param $post_id
		 * @param $user_id
		 */
		public function notification_send($post_id, $user_id) {

			do_action('subscribr_pre_notification_send', $post_id, $user_id);

			// get users details and send the message
			$user = get_user_by('id', $user_id);
			$to_name = apply_filters('subsribr_to_name', $user->data->display_name);
			$to_email = apply_filters('subscribr_to_email', $user->data->user_email);
			$to = $to_name.' <'.$to_email.'>';

			$from_name = apply_filters('subsribr_from_name', $this->get_option('from_name'));
			$from_email = apply_filters('subscribr_from_email', $this->get_option('from_email'));
			$from = $from_name.' <'.$from_email.'>';

			$mail_subject = $this->get_option('mail_subject');
			$mail_subject = $this->merge_user_vars($mail_subject, $post_id, $user_id);
			$mail_subject = apply_filters('subscribr_mail_subject', $mail_subject);

			$headers[] = 'From: '.$from;

			// check if user wants HTML messages, if HTML mail has been enabled by the admin
			if($this->get_option('enable_html_mail') && get_user_meta($user_id, 'subscribr-send-html', TRUE)) {
				$headers[] = 'MIME-Version: 1.0';
				//$headers[] = 'Content-type: text/html; charset=UTF-8';
				$headers[] = 'Content-type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset');
				$message = $this->get_html_template();
			} else {
				$message = $this->get_plaintext_template();
			}

			// merge user variables, apply a final filter and send
			$message = $this->merge_user_vars($message, $post_id, $user_id);
			$message = apply_filters('subsribr_mail_body', $message);
			$send_result = wp_mail($to, $mail_subject, $message, $headers);
			do_action('subscribr_post_notification_send', $send_result);
		}

		/**
		 * Returns the contents of a custom HTML template or the contents
		 * of the integrated editor if no template is found.
		 *
		 * @return mixed|string|void
		 */
		public function get_html_template() {

			$html_template_exists = FALSE;

			// allow template filenames to be changed by add-ons or theme functions
			$html_template = apply_filters('subscribr_html_email_template', 'html-email-template.php');

			// test for user defined PHP email template in the 'subscribr' folder in the current theme (or child theme)
			$template_files = $this->locate_theme_templates();

			if(is_array($template_files)) {
				foreach($template_files as $file) {
					// test to see if the files found in the directory match the template filename
					if(basename($file) == $html_template) {
						$html_template_exists = TRUE;
						$html_template = $file;
					}
				}
			}

			if($html_template_exists) {
				include(ABSPATH.$html_template);
				/** @noinspection PhpUndefinedVariableInspection */ // variable defined in the included file
				return $html_mail_body;
			} else {
				$html_template = $this->get_option('enable_html_mail');
				$html_template = stripslashes($html_template['mail_body_html']); // @todo test to see if stripslashes() needs to be applied to both
				return $html_template;
			}
		}

		/**
		 * Returns the contents of a custom plain text email template or the contents
		 * of the integrated editor if no template is found.
		 *
		 * @return mixed|string|void
		 */
		public function get_plaintext_template() {

			$plain_text_template_exists = FALSE;

			// allow template filenames to be changed by add-ons or theme functions
			$plain_text_template = apply_filters('subscribr_plaintext_email_template', 'email-template.php');

			// test for user defined PHP email subscribr in the 'subscribr' folder in the current theme (or child theme)
			$template_files = $this->locate_theme_templates();

			if(is_array($template_files)) {
				foreach($template_files as $file) {
					// test to see if the files found in the directory match the template filenames
					if(basename($file) == $plain_text_template) {
						$plain_text_template_exists = TRUE;
						$plain_text_template = $file;
					}
				}
			}

			if($plain_text_template_exists) {
				include(ABSPATH.$plain_text_template);
				/** @noinspection PhpUndefinedVariableInspection */ // variable defined in the included file
				return $mail_body;
			} else {
				return $this->get_option('mail_body');
			}
		}

		/**
		 * Scans the current theme for template files. Based on mapi_file_dir_array().
		 *
		 * @param null   $dir
		 * @param string $exts
		 *
		 * @return array
		 */
		public function locate_theme_templates($dir = NULL, $exts = 'php') {
			if(!isset($dir)) {
				$dir = apply_filters('subscribr_template_directory', SUBSCRIBR_TEMPLATE_PATH);
			}

			if(file_exists($dir)) {
				$files = array();
				$i = -1;
				$handle = opendir($dir);
				$exts = explode(',', strtolower($exts));
				while(FALSE !== ($file = readdir($handle))) {
					foreach($exts as $ext) {
						if(preg_match('/\.'.$ext.'$/i', $file, $test)) {
							$files[] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir.$file);
							++$i;
						}
					}
				}
				closedir($handle);

				return $files;
			} else {
				return FALSE; // template folder was not found
			}
		}

		/**
		 * Copies the default templates over to the the active theme directory.
		 *
		 * @param null $from
		 * @param null $to
		 *
		 * @return mixed
		 */
		public function copy_default_templates($from = NULL, $to = NULL) {

			if($this->get_option('use_custom_templates') && is_admin()) {

				if(empty($from)) {
					$from = SUBSCRIBR_DIR_PATH.'views/templates/';
				}

				if(empty($to)) {
					$to = apply_filters('subscribr_template_directory', SUBSCRIBR_TEMPLATE_PATH);
				}

				// copy the template to the current theme
				$this->recursive_copy($from, $to);

				// reset the custom template options option
				$this->update_option('use_custom_templates', FALSE);
			}
		}

		/**
		 * Copies a directory recursively.
		 *
		 * @param $from
		 * @param $to
		 *
		 * @return \WP_Error
		 */
		public function recursive_copy($from, $to) {

			$dir = opendir($from);

			// create the target folder
			if(!is_dir($to)) {
				if(!mkdir($to, 0755)) {
					$notice = __('Could not copy the template files. Could not create the target directory. Try copying the files manually or checking your file permissions. ', 'subscribr');
					$this->admin_notice($notice, 'error');

					return new WP_Error('mkdir_failed', $notice);
				}
			} else {
				// folder already exists
				$notice = __('Could not copy the template files. The target directory already exists.', 'subscribr');
				$this->admin_notice($notice);

				return new WP_Error('mkdir_failed', $notice);
			}

			while(FALSE !== ($file = readdir($dir))) {
				if(($file != '.') && ($file != '..')) {
					if(is_dir($from.'/'.$file)) {
						$this->recursive_copy($from.'/'.$file, $to.'/'.$file);
					} else {
						copy($from.'/'.$file, $to.'/'.$file);
					}
				}
			}
			closedir($dir);
		}

		/**
		 * Outputs a notice to the admin screen.
		 *
		 * @param        $notice
		 * @param string $level 'updated' or 'error'
		 */
		public function admin_notice($notice, $level = 'updated') {
			if(is_admin()) : ?>
				<div class="updated">
					<p><?php echo $notice; ?></p>
				</div>
			<?php endif;
		}

		/**
		 * Determine what taxonomy terms are enabled
		 *
		 */
		public function get_enabled_terms() {
			if($this->get_option('enable_all_terms')) {
				return $this->get_default_terms();
			} else {
				return $this->get_option('enabled_terms');
			}
		}

		/**
		 * Determine what post types are enabled for email notification, if any.
		 *
		 */
		public function get_enabled_types() {

			$enabled_types = $this->get_option('enabled_types');
			$all_types = $this->get_default_types();

			if($this->get_option('enable_all_types')) {
				// return all available types
				return $all_types;
			} elseif(is_array($enabled_types)) {

				$enabled_types = array_unique($enabled_types);

				// return all enabled post types
				return $enabled_types;
			} else {
				// no types are enabled, exit now
				return FALSE;
			}
		}

		/**
		 * Determine what taxonomies are enabled for email notification, if any.
		 *
		 */
		public function get_enabled_taxonomies() {

			$enabled_terms = $this->get_option('enabled_terms');
			$all_taxonomies = $this->get_default_taxonomies();
			$enabled_taxonomies = array();

			if($this->get_option('enable_all_terms')) {

				$enabled_types = $this->get_enabled_types();

				foreach($all_taxonomies as $tax) {
					foreach($enabled_types as $type) {

						// check if the taxonomy is on an enabled post type
						if(is_object_in_taxonomy($type, $tax)) {

							// if so, add it to our array
							$enabled_taxonomies[] = $tax;
						}
					}
				}

				// return all available taxonomies
				$enabled_taxonomies = array_unique($enabled_taxonomies);

				// return all user enabled taxonomies

				return $enabled_taxonomies;
			} elseif($enabled_terms) {

				// this bit gets nasty because, surprisingly, there is no
				// WP function to lookup a taxonomy from just a `term_id`
				// all WP term related functions have `taxonomy` as a required param
				// in this case we don't know the taxonomy so we have to look it up

				// 1. loop through user enabled terms
				foreach($enabled_terms as $term) {

					// 2. loop through all taxonomies
					foreach($all_taxonomies as $tax) {

						// 3. check if the term exists in each taxonomy
						$term_result = term_exists($term, $tax);
						if(!empty($term_result) && !is_a($term_result, 'WP_Error')) {

							// 4. if so, add it to our array
							$term_meta = get_term($term_result['term_id'], $tax, ARRAY_A);
							$enabled_taxonomies[] = $term_meta['taxonomy'];
						}
					}
				}

				$enabled_taxonomies = array_unique($enabled_taxonomies);

				//mapi_var_dump($enabled_taxonomies,1);
				// return all user enabled taxonomies
				return $enabled_taxonomies;
			} else {
				// no terms are enabled, exit now
				return FALSE;
			}
		}

		/**
		 * Setup the terms that are enabled by default.
		 *
		 */
		public function get_default_terms() {
			$terms = get_terms($this->get_default_taxonomies(), array('hide_empty' => FALSE, 'fields' => 'id=>slug'));
			$disabled_terms = array('uncategorized');
			$disabled_terms = apply_filters('subscribr_disabled_terms', $disabled_terms);

			//mapi_var_dump($terms,1);
			$terms = array_diff($terms, $disabled_terms);

			return $terms;
		}

		/**
		 * Setup the taxonomies that are enabled by default.
		 *
		 * @return array
		 */
		public function get_default_taxonomies() {
			$taxonomies = get_taxonomies();
			$disabled_taxonomies = array('post_status', 'nav_menu', 'post_format', 'link_category');
			$disabled_taxonomies = apply_filters('subscribr_disabled_taxonomies', $disabled_taxonomies);

			$taxonomies = array_diff($taxonomies, $disabled_taxonomies);

			return $taxonomies;
		}

		/**
		 * Setup the post types that are enabled by default.
		 *
		 * @return array
		 */
		public function get_default_types() {
			$types = get_post_types();

			$disabled_types = array('attachment', 'revision', 'nav_menu_item', 'acf', 'deprecated_log');
			$disabled_types = apply_filters('subscribr_disabled_types', $disabled_types);

			$types = array_diff($types, $disabled_types);

			return $types;
		}

		/**
		 * Replaces certain user and blog variables in $input string.
		 *
		 * Based on code from the Theme My Login plugin.
		 *
		 * @since  0.1
		 * @access public
		 *
		 *
		 * @param string     $input_str    The input string
		 * @param int|string $post_id      The post ID
		 * @param int|string $user_id      User ID to replace user specific variables
		 * @param array      $replacements Misc variables => values replacements
		 *
		 * @return string The $input string with variables replaced
		 */
		public function merge_user_vars($input_str, $post_id = 0, $user_id = '', $replacements = array()) {
			$defaults = array(
				'%post_title%'          => get_the_title($post_id),
				'%post_type%'           => get_post_type($post_id),
				'%post_date%'           => date(get_option('date_format'), strtotime(get_post($post_id)->post_date)),
				'%post_excerpt%'        => wp_trim_words(get_post($post_id)->post_content, $num_words = 55, $more = NULL),
				'%permalink%'           => get_permalink($post_id),
				'%site_name%'           => get_bloginfo('name'),
				'%site_url%'            => get_home_url(),
				'%notification_label%'  => $this->get_option('notification_label'),
				'%notifications_label%' => $this->get_option('notifications_label'),
				'%profile_url%'         => admin_url('profile.php'),
				'%user_ip%'             => $_SERVER['REMOTE_ADDR']
			);
			$replacements = wp_parse_args($replacements, $defaults);

			// Get user data
			$user = FALSE;
			if($user_id) {
				$user = get_user_by('id', $user_id);
			}

			// Get all matches ($matches[0] will be '%value%'; $matches[1] will be 'value')
			preg_match_all('/%([a-zA-Z0-9-_]*)%/', $input_str, $matches);

			// Iterate through matches
			foreach($matches[0] as $key => $match) {
				if(!isset($replacements[$match])) {
					if($user && isset($user->{$matches[1][$key]})) {
						// Replacement from WP_User object
						$replacements[$match] = $user->{$matches[1][$key]};
					} else {
						// Replacement from get_bloginfo()
						$replacements[$match] = get_bloginfo($matches[1][$key]);
					}
				}
			}

			// Allow replacements to be filtered
			$replacements = apply_filters('subscribr_replace_vars', $replacements, $user_id);

			if(empty($replacements)) {
				return $input_str;
			}

			// Get search values
			$search = array_keys($replacements);

			// Get replacement values
			$replace = array_values($replacements);

			return str_replace($search, $replace, $input_str);
		}

		/**
		 *
		 * Retrieve an option from the options array.
		 *
		 * @param null $name
		 *
		 * @return string
		 */
		public function get_option($name = NULL) {
			if(empty($name)) {
				return FALSE;
			}

			if($this->options && array_key_exists($name, $this->options)) {

				// check if the option is a URL
				if(stristr($name, 'uri')) {
					return html_entity_decode($this->options[$name]);
				} else {
					return $this->options[$name];
				}
			} else {
				return FALSE;
			}
		}

		/**
		 * Sets an option in the database.
		 *
		 * @param $name
		 * @param $value
		 *
		 * @return bool
		 */
		function update_option($name, $value) {
			$name = trim($name);
			if(empty($name)) {
				return FALSE;
			}

			$options = get_option(SUBSCRIBR_OPTIONS);
			if($options) {
				$options[$name] = $value;

				return update_option(SUBSCRIBR_OPTIONS, $options);
			}
		}

		/**
		 * Deletes an option from the Subscribr options array and updates the database.
		 *
		 * @param $name
		 *
		 * @return bool
		 */
		function delete_option($name = NULL) {
			$name = trim($name);
			if(empty($name)) {
				return FALSE;
			}

			$options = get_option(SUBSCRIBR_OPTIONS);
			if($options) {
				$options[$name] = '';

				return update_option(SUBSCRIBR_OPTIONS, $options);
			}
		}

		/**
		 * Tests if the current screen is the profile page.
		 *
		 * @return bool
		 */
		public function is_profile() {
			return in_array($GLOBALS['pagenow'], array('profile.php'));
		}

		/**
		 * Tests if the current screen is the user profile editor.
		 *
		 * @return bool
		 */
		public function is_user_edit() {
			return in_array($GLOBALS['pagenow'], array('user-edit.php'));
		}

		/**
		 * Tests if the current screen is the register page.
		 *
		 * @return bool
		 */
		public function is_register() {
			if(in_array($GLOBALS['pagenow'], array('wp-login.php')) && (isset($_GET['action']) && $_GET['action'] == 'register')) {
				return TRUE;
			} else {
				return FALSE;
			}
		}

		/**
		 * Tests if the current screen is the settings page.
		 *
		 * @return bool
		 */
		public function is_settings_page() {
			return in_array($GLOBALS['pagenow'], array('options-general.php'));
		}

		/**
		 * Evaluates natural language strings to boolean equivalent
		 *
		 * All values defined as TRUE will return TRUE, anything else is FALSE.
		 * Boolean values will be passed through.
		 *
		 * @since  0.1.7
		 *
		 * @param string $string        The natural language value
		 * @param array  $true_synonyms A list strings that are TRUE
		 *
		 * @return boolean The boolean value of the provided text
		 **/
		public function is_true($string, $true_synonyms = array('yes', 'y', 'true', '1', 'on', 'open', 'affirmative', '+', 'positive')) {
			if(is_array($string)) {
				return FALSE;
			}
			if(is_bool($string)) {
				return $string;
			}

			return in_array(strtolower(trim($string)), $true_synonyms);
		}
	}
}

$subscribr = new Subscribr;
