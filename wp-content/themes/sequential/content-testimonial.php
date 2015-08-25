<?php
/**
 * The template used for displaying testimonials.
 *
 * @package Sequential
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="testimonial-entry-content">
		<?php the_content(); ?>
	</div>

	<?php the_title( '<span class="testimonial-entry-title">', '</span>' ); ?>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="testimonial-featured-image">
			<?php the_post_thumbnail( 'sequential-avatar' ); ?>
		</div>
	<?php endif; ?>

</article><!-- #post-## -->
