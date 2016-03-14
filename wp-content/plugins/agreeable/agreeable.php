<?php
/*
Plugin Name: Agreeable
Plugin URI: http://wordpress.org/extend/plugins/agreeable
Description: Add a required "Agree to terms" checkbox to login and/or register forms.
Version: 1.5
Author: kraftpress
Author URI: http://kraftpress.it
*/


//==================================
//! TODO-
//! Cleanup functions, make it smarter, faster, better.
//==================================

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(session_id() == '') {
	session_start();
}

class Agreeable {
	function __construct() {

		/* Initialize the plugin */
		add_action('init', array($this, 'init'));
		add_action('admin_enqueue_scripts', array($this, 'ag_admin'));
		add_action('wp_enqueue_scripts', array($this, 'ag_front'));
		add_action('login_enqueue_scripts', array($this, 'ag_front'));
		add_action('admin_menu', array($this, 'agreeable_options'));

		/* Registration Validation Hooks  */
		add_filter('registration_errors', array($this, 'registration_validation'), 0, 2);
		add_filter('bp_signup_validate', array($this, 'ag_authenticate_user_acc'), 10, 2);
		add_filter('wpmu_validate_user_signup', array($this, 'ag_authenticate_user_acc'), 10, 3);

		/* Login Validation Hooks */
		add_filter('wp_authenticate_user', array($this, 'ag_authenticate_user_acc'), 10, 2);

		/* Comment Validation Hooks */
		add_action('pre_comment_on_post', array($this, 'ag_validate_comment'), 10, 2);

		/* Output Hooks */
		add_filter('login_form', array($this, 'ag_login_terms_accept') );
		add_filter('register_form', array($this, 'ag_register_terms_accept'));
		add_filter('comment_form_after_fields', array($this, 'ag_comment_terms_accept'));
		add_filter('comment_form_logged_in_after', array($this, 'ag_comment_terms_accept'));
		add_action('bp_before_registration_submit_buttons', array($this, 'ag_register_terms_accept'));
		add_action('tml_register_form', array($this, 'ag_register_terms_accept'), 10, 3);
		add_action('bp_after_login_widget_loggedout', array($this, 'ag_widget_terms_accept'));

		$this->options = array(
			'login' => get_option('ag_login'),
			'register' => get_option('ag_register'),
			'fail_text' => get_option('ag_fail'),
			'remember_me' => get_option('ag_remember'),
			'message' => get_option('ag_termm'),
			'terms_page' => get_option('ag_url'),
			'comments' => get_option('ag_comments'),
			'lightbox' => get_option('ag_lightbox'),
			'colors' => get_option('ag_colors')
		);

		return true;
	}

	function kp_sanatize_hex_color( $color ) {

		$color = (empty($color) || !preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) ? '' : $color;

		return $color;

	}

	function update_options() {

		if( current_user_can( 'manage_options' ) ) {

			if( ( isset($_POST['ag_hidden']) ) && ( $_POST['ag_hidden'] == 'Y' ) && ( check_admin_referer( 'ag_settings_page' ) ) ) {

				// Validate and sanatize all options, run text fields through wp_kses to allow approved HTML tags

				isset($_POST['ag_fail']) ? update_option('ag_fail', wp_kses( $_POST['ag_fail'], wp_kses_allowed_html( 'post' ) ) ) : update_option('ag_fail', '');
				isset($_POST['ag_termm']) ? update_option('ag_termm', wp_kses( $_POST['ag_termm'], wp_kses_allowed_html( 'post' ) ) ) : update_option('ag_termm', '');

				// Make sure an integer / post_id is passed through

				if( isset( $_POST[ 'ag_url'] ) ) {

					$ag_url = intval($_POST[ 'ag_url' ]);

					if( ! $ag_url ) {
						$ag_url = '1';
					}

					update_option( 'ag_url', $ag_url );

				}

				isset($_POST['ag_hidden']) ? update_option('ag_colors', array('text-color' => $this->kp_sanatize_hex_color( $_POST['ag_text_color']), 'bg-color' => $this->kp_sanatize_hex_color($_POST['ag_bg_color']))) : update_option('ag_colors', array('text-color' => '#333', 'bg-color' => '#fafafa'));

				$checkboxes = array(
					'ag_login',
					'ag_register',
					'ag_comments',
					'ag_lightbox',
					'ag_remember'
				);

				foreach( $checkboxes as $checkbox ) {

					// Is the option set and is it a boolean?
					if( isset( $_POST[ $checkbox ] ) && ( boolval( $_POST[ $checkbox ] ) ) ) {

						update_option( $checkbox, $_POST[ $checkbox ] );

					} else {

						update_option( $checkbox, '' );

					}

				}

				if( ( isset( $_POST[ 'ag_login'] ) ) && ( intval( $_POST[ 'ag_login' ] ) ) ) {

					update_option('ag_login', $_POST['ag_login']);

				}

			}

			$this->options = array(
				'login' => get_option('ag_login'),
				'register' => get_option('ag_register'),
				'fail_text' => get_option('ag_fail'),
				'remember_me' => get_option('ag_remember'),
				'message' => get_option('ag_termm'),
				'terms_page' => get_option('ag_url'),
				'comments' => get_option('ag_comments'),
				'lightbox' => get_option('ag_lightbox'),
				'colors' => get_option('ag_colors')
			);

		}

	}

	function init() {
		// Localization
		load_plugin_textdomain('agreeable', false, basename( dirname( __FILE__ ) ) . '/languages' );

		if (is_multisite()) {
			add_action( 'signup_extra_fields', array($this, 'ag_register_terms_accept'), 10, 3);
			add_action( 'signup_blogform', array($this, 'ag_register_terms_accept'), 10, 3);
		}

		$this->update_options();

	}

	function ag_admin() {

		/* Plugin Stylesheet */
		wp_enqueue_style( 'agreeable-css', plugins_url('css/admin.css', __FILE__), '', '1.3.5', 'screen');

	}

	function ag_front() {

		/* Only load lightbox code on the frontend, where we need it */
		if ( $this->is_login_page() ) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script( 'magnific', plugins_url('js/magnific.js', __FILE__),'', '', true);
		wp_enqueue_script( 'agreeable-js', plugins_url('js/agreeable.js', __FILE__), '', '', true);
		wp_enqueue_style( 'magnific', plugins_url('css/magnific.css', __FILE__));
		wp_enqueue_style( 'agreeable-css', plugins_url('css/front.css', __FILE__));

	}

	function registration_validation() {

		$errors = new WP_error();

		if(isset($_POST['ag_type']) && $_POST['ag_type'] == 'register' && $this->options['register'] == 1) {

			if ( isset( $_POST['ag_login_accept'] ) && $_POST['ag_login_accept'] == 1) {

				return $errors;

			} else {

				$errors->add('ag_login_accept', $this->options['fail_text']);

				return $errors;
			}

		} else {

			return $errors;

		}

	}

	function ag_authenticate_user_acc($user) {


		if(isset($_POST['ag_type']) && $_POST['ag_type'] == "login" && $this->options['login'] == 1 || isset($_POST['ag_type']) && $_POST['ag_type'] == 'register' && $this->options['register'] == 1) {

			// See if the checkbox #ag_login_accept was checked
			if ( isset( $_POST['ag_login_accept'] ) && $_POST['ag_login_accept'] == 1) {

				// Checkbox on, allow login, set the cookie if necessary

				if ( !isset( $_COOKIE['agreeable_terms'] ) && $this->options['remember_me'] == 1 ) {
					setcookie( 'agreeable_terms', 'yes', strtotime('+30 days'), COOKIEPATH, COOKIE_DOMAIN, false );
				}


				do_action('agreeable_validate_user', $user, $_POST['ag_type']);

				unset($_SESSION['ag_errors']);

				return $user;

			} else {

				if($this->is_buddypress_registration()) {

					global $bp;

					$bp->signup->errors['ag_login_accept'] = $this->options['fail_text'];

					return;

				}

				$errors = new WP_Error();

				$errors->add('ag_login_accept', $this->options['fail_text']);


				/* Incase it's a form that doesn't respect WordPress' error system */

				$_SESSION['ag_errors'] = $this->options['fail_text'];

				if(is_multisite() && $this->is_multisite_register() && !$this->is_login_page()) {

					$result = $user;

					$result['errors'] = $errors;

					return $result;

				}

				return $errors;

			}

		} else {

			return $user;

		}

	}

	function is_woocommerce_page () {

		if(  function_exists( "is_woocommerce" )){

			$woocommerce_keys = array ( "woocommerce_shop_page_id" ,
				"woocommerce_checkout_page_id" ,
				"woocommerce_myaccount_page_id"
				);

			foreach ( $woocommerce_keys as $wc_page_id ) {
				if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
					return true ;
				}
			}
		}
			return false;
	}

	function is_buddypress_registration() {

		if(function_exists('bp_current_component')) {

			/* Lets make sure we're on the right page- Ie the buddypress register page */
			$bp_pages = get_option('bp-pages');
			$bp_page = get_post($bp_pages['register']);

			global $wp_query;
			$current_page = isset($wp_query->query_vars['name']) ? $wp_query->query_vars['name'] : '';

			return $bp_page->post_name == $current_page ?  true : false;

		}
	}

	function ag_validate_comment($comment) {

		if($this->options['comments'] == 1) {

			// See if the checkbox #ag_login_accept was checked
			if ( isset( $_REQUEST['ag_login_accept'] ) && $_REQUEST['ag_login_accept'] == 1 ) {

				// Checkbox on, allow comment
				// Grab info from the form

				global $current_user;

				if(is_user_logged_in()) {
					$user = $current_user->ID;
				} else {
					$user = array('author' => $_REQUEST['author'], 'email' => $_REQUEST['email']);
				}

				do_action('agreeable_validate_user', $user, $_POST['ag_type']);
				return $comment;

			} else {
				// Did NOT check the box, do not allow comment

				$error = new WP_Error();
				$error->add('did_not_accept', $this->options['fail_text']);

				wp_die( __($this->options['fail_text']) );
				return $error;
			}
		} else {
			return $comment;
		}

	}


	function ag_display_terms_form($type, $errors = '') {

		global $bp;


		// Validate a valid (ha) type is passed in and fall back to default of not
		if( ( $type != 'login' ) || ( $type != 'register' ) || ( $type != 'comments' ) ) {

			$type = 'login';

		}

		if(isset($this->options['terms_page'])) {
			$terms = get_post($this->options['terms_page']);
			$terms_content = '<h3>'.esc_html( $terms->post_title ).'</h3>'.esc_html( apply_filters('the_content', $terms->post_content) );
		}

		/* Add an element to the login form, which must be checked */

		$term_link = get_post_permalink($terms);

		$class = '';
		if($this->options['lightbox'] == 1) {

			$class = 'ag-open-popup-link';

			$term_link = '#ag-terms';

			if($this->options['colors']) {
				echo '<style>#ag-terms {background: '.esc_html( $this->options['colors']['bg-color'] ).' !important; color: '.esc_html( $this->options['colors']['text-color'] ).';}</style>';
			}
		}

		/*  Get our errors incase we need to display */

		$errors = new WP_Error;

		if(isset($_SESSION['ag_errors']) && $errors->get_error_message( 'ag_login_accept' ) == '') {

			$error = $_SESSION['ag_errors'];
			unset($_SESSION['ag_errors']);

		}

		if ( isset($error) && !$this->is_login_page()) {

			echo '<br><p class="error">'.esc_html( $error ).'</p>';

		}

		/* Are we remembering logins?  Lets check. */

		$remember = '';

		if ( isset($_COOKIE['agreeable_terms'] ) && $this->options['remember_me'] == 1 ) {
			$remember = ' checked ';
		}

		if(!$this->is_woocommerce_page()) {

			echo '<div style="clear: both; padding: .25em 0;" id="terms-accept" class="terms-form">';

			if($this->is_buddypress_registration()){do_action( 'bp_ag_login_accept_errors' );}

			echo '<label style="text-align: left;"><input type="checkbox" value="1" name="ag_login_accept" id="ag_login_accept" '.$remember.' />&nbsp;<a title="' . esc_attr( get_post($this->options['terms_page'])->post_title ) . '" class="' . esc_attr( $class ) . '" target="_BLANK" href="' . esc_url( $term_link ) . '">' . esc_html( $this->options['message'] ) .'</a></label>';
			echo '<input type="hidden" value="' . esc_attr( $type ) . '" name="ag_type" /></div>';
			echo $term_link == '#ag-terms' ? '<div id="ag-terms" class="mfp-hide">' . esc_html( $terms_content ) . '</div>' : '';
			echo $type == 'comments' ? '<br>':'';

		}

	}


	function ag_login_terms_accept($errors){

		if($this->options['login'] == 1) {
			$this->ag_display_terms_form('login', $errors);
		}
	}

	function ag_comment_terms_accept(){

		if($this->options['comments'] == 1) {
			$this->ag_display_terms_form('comments');
		}
	}

	function ag_register_terms_accept($errors) {


		if($this->options['register'] == 1) {

			$this->ag_display_terms_form('register', $errors);

			if(class_exists('ThemeMyLogin')) {
				echo '<script>';
				echo '
						jQuery(document).ready(function($){
							if($("#theme-my-login")) {
								$("#theme-my-login #terms-accept").insertBefore("#theme-my-login .submit");
							}
						});
					';
				echo '</script>';
			}

		}
		return;
	}

	function ag_widget_terms_accept() {

		if($this->options['login'] == 1) {
			$this->ag_display_terms_form('login');
		}

		echo '<script>';
		echo '
				jQuery(document).ready(function($){
					$(".widget_bp_core_login_widget #terms-accept").insertBefore("#bp-login-widget-form .forgetmenot");
					$(".widget_bp_core_login_widget #bp-login-widget-form").nextAll(".terms-form").remove();
				});
			';
		echo '</script>';

	}

	function agreeable_options() {
		add_options_page('agreeable', 'Agreeable', 'manage_options', 'terms-options', array($this, 'agoptions'));
	}

	function agoptions() {
		include_once('includes/settings.php');
	}

	/* Plugin cross promotion area */

	function cross_promotions($plugin) {
		include('kp_cross_promote.php');
	}

	function is_login_page() {
		return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
	}

	function is_multisite_register() {
		return in_array($GLOBALS['pagenow'], array('wp-signup.php'));
	}

}

$agreeable = new Agreeable();
