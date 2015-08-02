<?php
/**
 * @package Hew
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-wrapper">
		<?php hew_post_thumbnail() ?>

		<header class="entry-header">
			<a href="<?php echo esc_url( get_post_format_link( get_post_format() ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'hew' ), get_post_format_string( get_post_format() ) ) ); ?>">
				<span class="screen-reader-text"><?php echo get_post_format_string( get_post_format() ); ?></span>
				<span class="entry-format"></span>
			</a>

			<div class="entry-meta">
				<?php hew_posted_on(); ?>
			</div><!-- .entry-meta -->

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
