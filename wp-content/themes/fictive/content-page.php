<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Fictive
 */
?>

<div class="hentry-wrapper">
	<?php if ( '' != get_the_post_thumbnail() ) : ?>
		<figure class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'fictive-index-thumb' ); ?></a>
		</figure>
	<?php endif; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'fictive' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<div class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'fictive' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
		</div>
	</article><!-- #post-## -->
</div>