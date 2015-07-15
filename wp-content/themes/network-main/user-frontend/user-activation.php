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

			<h3><?php _e( 'Activation', 'user-frontend-td' ); ?></h3>
			<?php echo apply_filters( 'uf_activation_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' ); ?>

			<form action="<?php echo uf_get_action_url( 'activation' ); ?>" method="post">
				<?php wp_nonce_field( 'activation', 'wp_uf_nonce_activation' ); ?>

				<p>
					<?php _e( 'Please enter your key.', 'user-frontend-td' ) ?>
				</p>
				<p>
					<label for="user_key"><?php _e( 'Key:', 'user-frontend-td' ); ?></label>
					<input type="text" name="user_key" id="user_key" value="<?php echo isset( $_GET[ 'key' ] ) ? $_GET[ 'key' ] : ''; ?>">
				</p>
				<p>
					<input type="submit" name="submit" id="submit" value="<?php _e( 'Activate' ); ?>">
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
