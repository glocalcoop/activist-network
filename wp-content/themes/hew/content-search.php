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

			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php hew_posted_on(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>

			<?php if ( is_single() ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php else : ?>
				<?php the_title( '<h1 class="entry-title"><a href=" ' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?></a>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->

		<?php hew_footer_meta(); ?>
	</div><!-- .entry-wrapper -->
</article><!-- #post-## -->
