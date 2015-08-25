<?php
/**
 * The Sidebar containing the footer widget area.
 *
 * @package Sequential
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}
?>

<div id="tertiary" class="footer-widget-area" role="complementary">
	<div class="wrapper">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div><!-- .wrapper -->
</div><!-- #tertiary -->
