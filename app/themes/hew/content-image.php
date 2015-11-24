<?php
/**
 * @package Hew
 */

// Put the_content() in a variable for regex
$content = apply_filters( 'the_content', get_the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hew' ) ) );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-wrapper">

		<?php if ( has_post_thumbnail() ) : ?>

			<?php hew_post_thumbnail(); ?>

		<?php else :
			$image = hew_get_first_image( $content );

			$content = preg_replace( '!<img.*src=[\'"]([^"]+)[\'"].*/?>!iUs', '', $content ); //Strip out all images from content

			if ( ! empty( $image ) ) : ?>
				<div class="post-thumbnail">
					<?php printf( '<a href="%1$s"><img src="%2$s" /></a>', esc_url( get_permalink() ), esc_url( $image['src'] ) ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<header class="entry-header">
			<?php hew_entry_format(); ?>

			<div class="entry-meta">
				<?php hew_posted_on(); ?>
			</div><!-- .entry-meta -->

			<?php if ( is_single() ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php else : ?>
				<?php the_title( '<h1 class="entry-title"><a href=" ' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?></a>
			<?php endif; // is_single() ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php echo $content; ?>
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
