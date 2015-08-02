<?php
/**
 * @package Mixfolio
 * @since Mixfolio 1.1
 */

global $mixfolio_count;

if ( $mixfolio_count % 3 == 0 ) {
	$post_class = 'three ';
}
else {
	$post_class = '';
}
?>

<li id="post-<?php the_ID(); ?>" <?php post_class( $post_class . 'wrap four columns' ); ?>>
	<div class="entry-link">
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'mixfolio' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"></a>
	</div><!-- .entry-link -->

	<?php
		$entry_class = 'show';
		if ( '' != get_the_post_thumbnail () ) {
			the_post_thumbnail();
			$entry_class = 'hide';
		}
		else {
			$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
			if ( $images ) {
				$image = array_shift( $images );
				$image = wp_get_attachment_image_src( $image->ID, 'mixfolio-featured-thumbnail' );
				echo '<img src="' . $image[0] . '" class="wp-post-image"/>';
				$entry_class = 'hide';
			}
			else {
				echo '<img src="' . get_stylesheet_directory_uri() . '/images/image.jpg" class="wp-post-image"/>';
			}
		}
	?>

	<div class="<?php echo $entry_class; ?>">
		<h1 class="entry-title"><?php the_title(); ?></h1><!-- .entry-title -->
		<footer class="entry-meta">
			<?php if ( ! is_sticky() && ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) ) : ?>
				<span class="postcomments"><?php echo get_comments_number(); ?></span><!-- .postcomments -->
			<?php endif; ?>

			<?php if ( ! is_sticky() ) : ?>
				<span class="postdate">
					<?php echo get_the_date(); ?>
				</span><!-- .postdate -->
			<?php endif; ?>

			<?php
				$format = get_post_format();
				if ( false === $format )
					$format = 'standard';
			?>
			<span class="format <?php echo $format; ?>"><?php echo $format; ?></span><!-- .format -->
		</footer><!-- .entry-meta -->
	</div><!-- .hide -->
</li><!-- #post-<?php the_ID(); ?> -->