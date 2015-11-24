<?php
/**
 * The template used for displaying featured page content in page-templates/front-page.php
 *
 * @package Sequential
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php sequential_post_thumbnail(); ?>

	<?php the_title( sprintf( '<header class="entry-header"><h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1></header>' ); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<p><a class="more-link" href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'Read more <span class="meta-nav">&rarr;</span>', 'sequential' ); ?></a></p>
	</div><!-- .entry-summary -->

	<?php edit_post_link( __( 'Edit', 'sequential' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
