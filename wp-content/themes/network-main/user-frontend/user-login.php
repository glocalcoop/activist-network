<?php
// **********************************************************
// If you want to have an own template for this action
// just copy this file into your current theme folder
// and change the markup as you want to
// **********************************************************
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/user-profile/' ) );
	exit;
}
?>
<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="first" role="main">
            
<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<h3><?php _e( 'Login', 'user-frontend-td' ); ?></h3>
			<?php echo apply_filters( 'uf_login_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' ); ?>

			<form action="<?php echo uf_get_action_url( 'login' ); ?>" method="post">
				<?php echo apply_filters( 'login_form_top', '', uf_login_form_args() ); ?>
				<?php wp_nonce_field( 'login', 'wp_uf_nonce_login' ); ?>
				<?php echo isset( $_GET[ 'redirect_to' ] ) ? '<input type="hidden" name="redirect_to" value="' . esc_url( $_GET[ 'redirect_to' ] ) . '">' : ''; ?>
				<p>
					<label for="user_login"><?php _e( 'Username', 'user-frontend-td' ); ?></label>
					<input type="text" name="user_login" id="user_login">
				</p>
				<p>
					<label for="user_pass"><?php _e( 'Password', 'user-frontend-td' ); ?></label>
					<input type="password" name="user_pass" id="user_pass">
				</p>
				<?php echo apply_filters( 'login_form_middle', '', uf_login_form_args() ); ?>
				<p>
					<label for="rememberme"><input type="checkbox" name="rememberme" id="rememberme"> <?php _e( 'Remember', 'user-frontend-td' ); ?></label>
					<input type="submit" name="submit" id="submit" value="<?php _e( 'Submit', 'user-frontend-td' ); ?>">
				</p>
				<p>
					<a href="<?php echo home_url( '/user-forgot-password/' ); ?>"><?php _e( 'Forgot Password?', 'user-frontend-td' ); ?></a>
					<?php
					if ( get_option( 'users_can_register' ) && ( is_multisite() && get_site_option( 'registration' ) != 'none' ) ) :
						$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( home_url( '/user-register/' ) ), __( 'Register' ) );
						/** This filter is documented in wp-includes/general-template.php */
						echo ' | ' . apply_filters( 'register', $registration_url );
					endif;
					?>
				</p>
				<?php echo apply_filters( 'login_form_bottom', '', uf_login_form_args() ); ?>
			</form>
		</div>
	</div>
</div>

            </main>
        <?php get_sidebar(); ?>

	</div>

</div>
            
<?php get_footer(); ?>
