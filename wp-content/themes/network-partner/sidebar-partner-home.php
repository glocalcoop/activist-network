
<?php if ( is_active_sidebar( 'home-sidebar' ) ) : ?>

	<?php dynamic_sidebar( 'home-sidebar' ); ?>

<?php else : ?>

	<?php // This content shows up if there are no widgets defined in the backend. ?>

	<div class="alert alert-help">
		<p><?php _e( 'This area is populated with widget content. Please add some widgets.', 'bonestheme' );  ?></p>
	</div>

<?php endif; ?>
