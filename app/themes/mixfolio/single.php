<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Mixfolio
 */

get_header(); ?>

<?php
	$format = get_post_format();
	if ( false === $format )
		$format = 'standard';

	$post_class = 'standard';
	if ( 'image' == $format || 'gallery' == $format || 'video' == $format )
		$post_class = 'full-width';
?>

	<div id="primary" class="<?php echo $post_class; ?>">
		<div id="content" role="main">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php mixfolio_content_nav( 'nav-below' ); ?>

			<?php
				if (
					'image' != $format && 'gallery' != $format && 'video' != $format &&
					( comments_open() || '0' != get_comments_number() )
				)
					comments_template( '', true );
			?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
	if ( 'image' != $format && 'gallery' != $format && 'video' != $format )
		get_sidebar();
?>

<?php get_footer(); ?>