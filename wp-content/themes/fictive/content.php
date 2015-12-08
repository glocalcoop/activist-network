<?php
/**
 * @package Fictive
 */

$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>

<div class="hentry-wrapper">
	<?php if ( '' != get_the_post_thumbnail() ) : ?>
		<figure class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'fictive-index-thumb' ); ?></a>
		</figure>
	<?php endif; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( $format && in_array( $format, $formats[0] ) ): ?>
			<a class="entry-format" href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'fictive' ), get_post_format_string( $format ) ) ); ?>"><span class="screen-reader-text"><?php echo esc_attr( get_post_format_string( $format ) ); ?></span></a>
		<?php endif; ?>
		<header class="entry-header">
			<?php if ( 'link' == get_post_format() ) : ?>
				<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( fictive_get_link_url() ) . '" rel="bookmark">', '</a></h1>' ); ?>
			<?php else : ?>
				<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
			<?php endif; ?>
			<div class="entry-meta">
				<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
					<?php fictive_posted_on(); ?>
				<?php endif; // End if 'post' == get_post_type() ?>

				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'fictive' ), __( '1 Comment', 'fictive' ), __( '% Comments', 'fictive' ) ); ?></span>
				<?php endif; ?>

				<?php edit_post_link( __( 'Edit', 'fictive' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fictive' ) ); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'fictive' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-footer entry-meta">

			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
				<?php
					/* translators: used between list items, there is a space after the comma */
					$tags_list = get_the_tag_list( '' );
					if ( $tags_list ) :
				?>
				<span class="tags-links clear">
					<?php echo $tags_list; ?>
				</span>
				<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>
		</footer><!-- .entry-footer -->
	</article><!-- #post-## -->
</div>
