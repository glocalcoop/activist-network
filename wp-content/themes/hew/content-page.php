<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Hew
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-wrapper">
		<?php hew_post_thumbnail() ?>

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'hew' ),
					'after'  => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
			?>
		</div><!-- .entry-content -->
		<footer class="entry-footer">
			<?php edit_post_link( __( 'Edit', 'hew' ), '<span class="edit-link theme-genericon">', '</span>' ); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .entry-wrapper -->
</article><!-- #post-## -->
