<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Hew
 */
?>

<div id="secondary" class="wrap top-widget-area" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="sidebar-1" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #first .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<div id="sidebar-2" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div><!-- #second .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
	<div id="sidebar-3" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
	</div><!-- #third .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
	<div id="sidebar-4" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-4' ); ?>
	</div><!-- #four .widget-area -->
	<?php endif; ?>
</div><!-- #secondary -->
