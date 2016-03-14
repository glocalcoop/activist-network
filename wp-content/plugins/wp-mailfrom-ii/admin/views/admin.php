<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wp_version;

$header_tag = version_compare( $wp_version, '4.3', '>=' ) ? 'h1' : 'h2';

?>

<div class="wrap">
	<?php screen_icon(); ?>
	<?php printf( '<%s>', $header_tag ); ?><?php echo esc_html( get_admin_page_title() ); ?><?php printf( '</%s>', $header_tag ); ?>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'wp_mailfrom_ii' );
		do_settings_sections( 'wp_mailfrom_ii' );
		?>
		<p class="submit"><input name="submit" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'wp-mailfrom-ii' ); ?>" /></p>
	</form>
</div>
