<?php
// **********************************************************
// If you want to have an own template for this action
// just copy this file into your current theme folder
// and change the markup as you want to
// **********************************************************
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/user-login/' ) );
	exit;
}

// get profile user
$user = get_userdata( get_current_user_id() );
$user->filter = 'edit';
$profileuser = $user;
?>
<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="first" role="main">
            
<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<h2><?php _e( 'Profile', 'user-frontend-td' ); ?></h2>
			<?php echo apply_filters( 'uf_profile_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' ); ?>

			<form action="<?php echo uf_get_action_url( 'profile' ); ?>" method="post" <?php do_action( 'user_edit_form_tag' ); ?>>
				<?php wp_nonce_field( 'profile', 'wp_uf_nonce_profile' ); ?>

				<h3><?php _e( 'Name' ) ?></h3>

				<table class="form-table">
					<tr>
						<th><label for="user_login"><?php _e( 'Username' ); ?></label></th>
						<td><input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled="disabled" class="regular-text" /> <span class="description"><?php _e( 'Usernames cannot be changed.' ); ?></span></td>
					</tr>

					<tr>
						<th><label for="first_name"><?php _e( 'First Name' ) ?></label></th>
						<td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ) ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th><label for="last_name"><?php _e( 'Last Name' ) ?></label></th>
						<td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ) ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th><label for="nickname"><?php _e( 'Nickname' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
						<td><input type="text" name="nickname" id="nickname" value="<?php echo esc_attr( $profileuser->nickname ) ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th><label for="display_name"><?php _e( 'Display name publicly as' ) ?></label></th>
						<td>
							<select name="display_name" id="display_name">
							<?php
								$public_display = array();
								$public_display[ 'display_nickname' ] = $profileuser->nickname;
								$public_display[ 'display_username' ] = $profileuser->user_login;

								if ( ! empty( $profileuser->first_name ) )
									$public_display[ 'display_firstname' ] = $profileuser->first_name;

								if ( ! empty( $profileuser->last_name ) )
									$public_display[ 'display_lastname' ] = $profileuser->last_name;

								if ( ! empty( $profileuser->first_name ) && ! empty( $profileuser->last_name ) ) {
									$public_display[ 'display_firstlast' ] = $profileuser->first_name . ' ' . $profileuser->last_name;
									$public_display[ 'display_lastfirst' ] = $profileuser->last_name . ' ' . $profileuser->first_name;
								}

								if ( ! in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
									$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

								$public_display = array_map( 'trim', $public_display );
								$public_display = array_unique( $public_display );

								foreach ( $public_display as $id => $item ) { ?>
									<option <?php selected( $profileuser->display_name, $item ); ?>><?php echo $item; ?></option>
								<?php }
							?>
							</select>
						</td>
					</tr>
				</table>

				<h3><?php _e( 'Contact Info' ) ?></h3>

				<table class="form-table">
					<tr>
						<th><label for="email"><?php _e( 'E-mail' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
						<td><input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th><label for="url"><?php _e( 'Website' ) ?></label></th>
						<td><input type="text" name="url" id="url" value="<?php echo esc_attr( $profileuser->user_url ) ?>" class="regular-text code" /></td>
					</tr>
					<?php foreach ( _wp_get_user_contactmethods( $profileuser ) as $name => $desc ) { ?>
					<tr>
						<th><label for="<?php echo $name; ?>"><?php echo apply_filters('user_'.$name.'_label', $desc); ?></label></th>
						<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $profileuser->$name) ?>" class="regular-text" /></td>
					</tr>
					<?php } ?>
				</table>

				<h3><?php _e( 'About Yourself' ); ?></h3>

				<table class="form-table">
					<tr>
						<th><label for="description"><?php _e( 'Biographical Info' ); ?></label></th>
						<td><textarea name="description" id="description" rows="5" cols="30"><?php echo $profileuser->description; // textarea_escaped ?></textarea><br />
						<span class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.' ); ?></span></td>
					</tr>

					<?php
					$show_password_fields = apply_filters( 'show_password_fields', TRUE, $profileuser );
					if ( $show_password_fields ) { ?>
						<tr id="password">
							<th><label for="pass1"><?php _e( 'New Password' ); ?></label></th>
							<td><input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /> <span class="description"><?php _e( 'If you would like to change the password type a new one. Otherwise leave this blank.' ); ?></span><br />
								<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" /> <span class="description"><?php _e( 'Type your new password again.' ); ?></span><br />
								<div id="pass-strength-result"><?php _e( 'Strength indicator' ); ?></div>
								<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).' ); ?></p>
							</td>
						</tr>
					<?php } ?>
				</table>

				<?php do_action( 'show_user_profile', $profileuser ); ?>

				<input type="submit" name="submit" id="submit" value="<?php _e( 'Update Profile' ); ?>">

			</form>
		</div>
	</div>
</div>
            
            </main>
        <?php get_sidebar(); ?>

	</div>

</div>
<?php get_footer(); ?>
