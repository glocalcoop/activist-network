<?php
/**
 * @package Hew
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-wrapper">

		<?php hew_post_thumbnail() ?>

		<header class="entry-header">
			<?php hew_entry_format(); ?>

			<div class="entry-meta">
				<?php hew_posted_on(); ?>
			</div><!-- .entry-meta -->

			<?php if ( is_single() ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php else : ?>
				<?php the_title( '<h1 class="entry-title"><a href=" ' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
			<?php endif; ?>
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

		<?php hew_footer_meta(); ?>
	</div><!-- .entry-wrapper -->
</article><!-- #post-## -->
