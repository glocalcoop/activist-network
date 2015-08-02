<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Adaption
 */
?>
<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<div id="secondary">
		<div class="widget-areas">
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .widgets-areas -->
	</div>
<?php endif; ?>