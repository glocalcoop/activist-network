<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package untitled
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_title( '<header class="entry-header"><h1 class="page-title">', '</h1></header><!-- .entry-header -->' ); ?>

	<div class="entry-content">
		<?php
			the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'untitled' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php edit_post_link( __( 'Edit', 'untitled' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
