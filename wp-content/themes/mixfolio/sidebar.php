<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Mixfolio
 */
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

		<aside id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</aside><!-- .widget_search -->

		<aside id="archives" class="widget">
			<h1 class="widget-title">
				<?php _e( 'Archives', 'mixfolio' ); ?>
			</h1><!-- .widget-title -->
			<ul>
				<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
			</ul>
		</aside><!-- .widget -->

		<aside id="meta" class="widget">
			<h1 class="widget-title">
				<?php _e( 'Meta', 'mixfolio' ); ?>
			</h1><!-- .widget-title -->
			<ul>
				<?php wp_register(); ?>
				<aside>
					<?php wp_loginout(); ?>
				</aside>
				<?php wp_meta(); ?>
			</ul>
		</aside><!-- .widget -->

	<?php endif; // end sidebar widget area ?>
</div><!-- #secondary .widget-area -->