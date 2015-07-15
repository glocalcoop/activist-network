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

			<h3><?php _e( 'Reset your password?', 'user-frontend-td' ); ?></h3>
			<?php echo apply_filters( 'uf_reset_password_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' ); ?>

			<form action="<?php echo uf_get_action_url( 'reset_password' ); ?>" method="post">
				<?php wp_nonce_field( 'reset_password', 'wp_uf_nonce_reset_password' ); ?>
				<p>
					<?php _e( 'Please enter your username, your key and your new password.', 'user-frontend-td' ) ?>
				</p>
				<p>
					<label for="user_login"><?php _e( 'Username:', 'user-frontend-td' ); ?></label>
					<input type="text" name="user_login" id="user_login" value="<?php echo isset( $_GET[ 'login' ] ) ? $_GET[ 'login' ] : ''; ?>">
				</p>
				<p>
					<label for="user_key"><?php _e( 'Key:', 'user-frontend-td' ); ?></label>
					<input type="text" name="user_key" id="user_key" value="<?php echo isset( $_GET[ 'key' ] ) ? $_GET[ 'key' ] : ''; ?>">
				</p>
				<p>
					<label for="pass1"><?php _e( 'New Password' ); ?></label>
					<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" />
				</p>
				<p>
					<label for="pass1"><?php _e( 'Confirm Password', 'user-frontend-td' ); ?></label>
					<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />
				</p>
				<p>
					<div id="pass-strength-result"><?php _e( 'Strength indicator' ); ?></div>
					<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).' ); ?></p>
				</p>
				<p>
					<input type="submit" name="submit" id="submit" value="<?php _e( 'Reset Password' ); ?>">
				</p>
			</form>

		</div>
	</div>
</div>

            </main>
        <?php get_sidebar(); ?>

	</div>

</div>
        
<?php get_footer(); ?>
