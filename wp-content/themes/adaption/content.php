<?php
/**
 * @package Adaption
 */
$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( '' != get_the_post_thumbnail() && '' == $format ) : ?>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'adaption' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="<?php the_ID(); ?>">
				<?php the_post_thumbnail( 'featured-image' ); ?>
			</a>
		</div><!-- .entry-thumbnail -->
	<?php endif; ?>

	<div class="entry-meta entry-top">

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'adaption' ), __( '1 Comment', 'adaption' ), __( '% Comments', 'adaption' ) ); ?></span>
		<?php endif; ?>

		<?php adaption_posted_on(); ?>

		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'adaption' ) );
			if ( 'post' == get_post_type() && $category_list && adaption_categorized_blog() ) :
			?>
				<span class="cat-links"><?php echo $category_list; ?></span>
			<?php endif; ?>

		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( 'post' == get_post_type() && $format && in_array( $format, $formats[0] ) ): ?>
			<span class="entry-format">
				<a href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'adaption' ), get_post_format_string( $format ) ) ); ?>">
					<?php echo get_post_format_string( $format ); ?>
				</a>
			</span>
		<?php endif; ?>

	</div><!-- .entry-meta -->


	<header class="entry-header">
		<?php
			if ( 'link' == get_post_format() ) :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( adaption_get_link_url() ) . '" rel="bookmark">', '</a></h1>' );
			else :
				if ( is_single() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
				endif;
			endif;
		?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'adaption' ) ); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'adaption' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ' ', 'adaption' ) );
			if ( $tags_list ) :
			?>
				<footer class="entry-meta">
					<span class="tags-links">
						<?php printf( __( '%1$s', 'adaption' ), $tags_list ); ?>
					</span>
				</footer>

			<?php endif; // End if $tags_list ?>
	<?php endif; // End if 'post' == get_post_type() ?>

</article><!-- #post-## -->
