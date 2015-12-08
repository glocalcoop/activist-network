<?php
/**
 * @package Hew
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-wrapper">

		<?php if ( has_post_thumbnail() ) : ?>
			<?php hew_post_thumbnail(); ?>
		<?php endif; ?>

		<header class="entry-header">
			<?php hew_entry_format(); ?>

			<div class="entry-meta">
				<?php hew_posted_on(); ?>
			</div><!-- .entry-meta -->

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hew' ) ); ?>
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
