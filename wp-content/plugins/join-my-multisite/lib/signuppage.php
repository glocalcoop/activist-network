<?php
/*

    This file is part of Join My Multisite, a plugin for WordPress.

    Join My Multisite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    Sitewide Comment Control is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) {
    die();
}

// Fix for page title
$wp_query->is_404 = false;

/**
 * Prints styles for front-end Multisite signup pages
 *
 * @since MU
 */
	?>
	<style type="text/css">
		.mu_register { width: 90%; margin:0 auto; }
		.mu_register form { margin-top: 2em; }
		.mu_register .error { font-weight:700; padding:10px; color:#333333; background:#FFEBE8; border:1px solid #CC0000; }
		.mu_register input[type="submit"],
			.mu_register #blog_title,
			.mu_register #user_email,
			.mu_register #blogname,
			.mu_register #user_name { width:100%; font-size: 24px; margin:5px 0; }
		.mu_register .prefix_address,
			.mu_register .suffix_address {font-size: 18px;display:inline; }
		.mu_register label { font-weight:700; font-size:15px; display:block; margin:10px 0; }
		.mu_register label.checkbox { display:inline; }
		.mu_register .mu_alert { font-weight:700; padding:10px; color:#333333; background:#ffffe0; border:1px solid #e6db55; }
	</style>

<?php

/**
 * Fires before the site sign-up form.
 *
 * @since 3.0.0
 */
do_action( 'before_signup_form' );
?>

<div id="jmm-content" class="widecolumn">
<div class="mu_register">
	
<?php
/**
 * Display user registration form
 *
 * @since MU
 *
 * @param string $user_name The entered username
 * @param string $user_email The entered email address
 * @param array $errors
 */
function show_user_form($user_name = '', $user_email = '', $errors = '') {
	// User name
	echo '<label for="user_name">' . __('Username:', 'join-my-multisite') . '</label>';
	if ( $errmsg = $errors->get_error_message('user_name') ) {
		echo '<p class="error">'.$errmsg.'</p>';
	}
	echo '<input name="user_name" type="text" id="user_name" value="'. esc_attr($user_name) .'" maxlength="60" /><br />';
	_e( '(Must be at least 4 characters, letters and numbers only.)', 'join-my-multisite' );
	?>

	<label for="user_email"><?php _e( 'Email&nbsp;Address:', 'join-my-multisite' ) ?></label>
	<?php if ( $errmsg = $errors->get_error_message('user_email') ) { ?>
		<p class="error"><?php echo $errmsg ?></p>
	<?php } ?>
	<input name="user_email" type="text" id="user_email" value="<?php  echo esc_attr($user_email) ?>" maxlength="200" /><br /><?php _e('We send your registration email to this address. (Double-check your email address before continuing.)', 'join-my-multisite') ?>
	<?php
	if ( $errmsg = $errors->get_error_message('generic') ) {
		echo '<p class="error">' . $errmsg . '</p>';
	}
	/**
	 * Fires at the end of the user registration form on the site sign-up form.
	 *
	 * @since 3.0.0
	 *
	 * @param array $errors An array possibly containing 'user_name' or 'user_email' errors.
	 */
	do_action( 'signup_extra_fields', $errors );
}

/**
 * Validate user signup name and email
 *
 * @since MU
 *
 * @uses wpmu_validate_user_signup() to retrieve an array of user data
 * @return array Contains username, email, and error messages.
 */
function validate_user_form() {
	return wpmu_validate_user_signup($_POST['user_name'], $_POST['user_email']);
}

/**
 * Setup the new user signup process
 *
 * @since MU
 *
 * @uses apply_filters() filter $filtered_results
 * @uses show_user_form() to display the user registration form
 * @param string $user_name The username
 * @param string $user_email The user's email
 * @param array $errors
 */
function signup_user($user_name = '', $user_email = '', $errors = '') {
	global $current_site, $active_signup;

	$jmm_options = get_option( 'helfjmm_options' );
	    if ( !is_null($jmm_options['perpage']) && $jmm_options['perpage'] != "XXXXXX"  )
	        {$goto = get_permalink($jmm_options['perpage']); }
	    else
	        {$goto = '/wp-signup.php';}
	        
	if ( !is_wp_error($errors) )
		$errors = new WP_Error();

	$signup_for = isset( $_POST[ 'signup_for' ] ) ? esc_html( $_POST[ 'signup_for' ] ) : 'blog';

	$signup_user_defaults = array(
		'user_name'  => $user_name,
		'user_email' => $user_email,
		'errors'     => $errors,
	);

	/**
	 * Filter the default user variables used on the user sign-up form.
	 *
	 * @since 3.0.0
	 *
	 * @param array $signup_user_defaults {
	 *     An array of default user variables.
	 *
	 *     @type string $user_name  The user username.
	 *     @type string $user_email The user email address.
	 *     @type array  $errors     An array of possible errors relevant to the sign-up user.
	 * }
	 */
	$filtered_results = apply_filters( 'signup_user_init', $signup_user_defaults );
	$user_name = $filtered_results['user_name'];
	$user_email = $filtered_results['user_email'];
	$errors = $filtered_results['errors'];
	
	?>

	<h2><?php printf( __( 'Create your account on %s', 'join-my-multisite' ), $current_site->site_name ) ?></h2>
	<form id="setupform" method="post" action="<?php echo $goto; ?>">
		<input type="hidden" name="stage" value="validate-user-signup" />
		<?php
		/** This action is documented in wp-signup.php */
		do_action( 'signup_hidden_fields', 'validate-user' );
		?>
		<?php show_user_form($user_name, $user_email, $errors); ?>

		<p><input id="signupblog" type="hidden" name="signup_for" value="user" /></p>

		<p class="submit"><input type="submit" name="submit" class="submit" value="<?php esc_attr_e('Signup', 'join-my-multisite') ?>" /></p>
	</form>
	<?php
}

/**
 * Validate the new user signup
 *
 * @since MU
 *
 * @return bool True if new user signup was validated, false if error
 */
function validate_user_signup() {
	$result = validate_user_form();
	$user_name = $result['user_name'];
	$user_email = $result['user_email'];
	$errors = $result['errors'];

	if ( $errors->get_error_code() ) {
		signup_user($user_name, $user_email, $errors);
		return false;
	}

	/** This filter is documented in wp-signup.php */
	wpmu_signup_user( $user_name, $user_email, apply_filters( 'add_signup_meta', array() ) );

	confirm_user_signup($user_name, $user_email);
	return true;
}

/**
 * New user signup confirmation
 *
 * @since MU
 *
 * @param string $user_name The username
 * @param string $user_email The user's email address
 */
function confirm_user_signup($user_name, $user_email) {
	?>
	<h2><?php printf( __( '%s is your new username', 'join-my-multisite' ), $user_name) ?></h2>
	<p><?php _e( 'Before you can start using your new username, <strong>you must activate it</strong>.', 'join-my-multisite' ) ?></p>
	<p><?php printf( __( 'Check your inbox at <strong>%s</strong> and click the link given.', 'join-my-multisite' ), $user_email ); ?></p>
	<p><?php _e( 'If you do not activate your username within two days, you will have to sign up again.', 'join-my-multisite' ); ?></p>
	<?php
	//duplicate_hook
	do_action( 'signup_finished' );
}

// Main
$active_signup = get_site_option( 'registration', 'none' );
/**
 * Filter the type of site sign-up.
 *
 * @since 3.0.0
 *
 * @param string $active_signup String that returns registration type. The value can be
 *                              'all', 'none', 'blog', or 'user'.
 */
$active_signup = apply_filters( 'wpmu_active_signup', $active_signup );

// Make the signup type translatable.
$i18n_signup['all'] = _x('all', 'Multisite active signup type');
$i18n_signup['none'] = _x('none', 'Multisite active signup type');
$i18n_signup['blog'] = _x('blog', 'Multisite active signup type');
$i18n_signup['user'] = _x('user', 'Multisite active signup type');


if ( is_super_admin() )
	echo '<div class="mu_alert">' . sprintf( __( 'Greetings Network Administrator! You are currently allowing &#8220;%s&#8221; registrations. To change or disable registration go to your <a href="%s">Options page</a>.', 'join-my-multisite' ), $i18n_signup[$active_signup], esc_url( network_admin_url( 'settings.php' ) ) ) . '</div>';

$newblogname = isset($_GET['new']) ? strtolower(preg_replace('/^-|-$|[^-a-zA-Z0-9]/', '', $_GET['new'])) : null;

$current_user = wp_get_current_user();
if ( $active_signup == 'none' ) {
	_e( 'Registration has been disabled.', 'join-my-multisite' );
} elseif ( $active_signup == 'blog' && !is_user_logged_in() ) {
	$login_url = site_url( 'wp-login.php?redirect_to=' . urlencode( get_permalink() ) );
	echo sprintf( __( 'You must first <a href="%s">log in</a>, and then you can join this site.', 'join-my-multisite' ), $login_url );
} else {
	$stage = isset( $_POST['stage'] ) ?  $_POST['stage'] : 'default';
	switch ( $stage ) {
		case 'validate-user-signup' :
			if ( $active_signup == 'all' || $_POST[ 'signup_for' ] == 'blog' && $active_signup == 'blog' || $_POST[ 'signup_for' ] == 'user' && $active_signup == 'user' )
				validate_user_signup();
			else
				_e( 'User registration has been disabled.' );
		break;

		case 'default':
		default :
			$user_email = isset( $_POST[ 'user_email' ] ) ? $_POST[ 'user_email' ] : '';
			/**
			 * Fires when the site sign-up form is sent.
			 *
			 * @since 3.0.0
			 */
			do_action( 'preprocess_signup_form' );
			if ( is_user_logged_in() == false && ( $active_signup == 'all' || $active_signup == 'user' ) )
				signup_user( $user_email );
			elseif ( !is_super_admin() )
				_e( 'You are logged in already. No need to register again!', 'join-my-multisite' );
			break;
	}
}
?>
</div>
</div>
<?php
/**
 * Fires after the sign-up forms, before wp_footer.
 *
 * @since 3.0.0
 */
do_action( 'after_signup_form' ); ?>