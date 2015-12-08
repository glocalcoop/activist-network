<?php
/**
 * The template for displaying search forms in Mixfolio
 *
 * @package Mixfolio
 * @since Mixfolio 1.1
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'mixfolio' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'mixfolio' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'mixfolio' ); ?>" />
	</form><!-- #searchform -->