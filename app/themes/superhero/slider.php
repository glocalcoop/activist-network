<?php
/**
 * @package Superhero
 * @since Superhero 1.0
 */
$featured = superhero_get_featured_posts();

if ( empty( $featured ) )
	return;
?>

<div id="featured-content" class="flexslider">
	<ul class="featured-posts slides">

		<?php
		foreach ( $featured as $post ) :
			setup_postdata( $post );

			if ( has_post_thumbnail() ) : ?>
					<li class="featured">
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'superhero' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'slider-img' ); ?></a>
						<div class="featured-hentry-wrap">
							<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<?php the_title( '<div class="entry-header"><h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2></div>' ); ?>
							</div><!-- #post-## -->
						</div><!-- .featured-hentry-wrap -->
					</li>
		<?php
			endif;
		endforeach;
		wp_reset_postdata();
		?>
	</ul><!-- .featured-posts -->
</div><!-- #featured-content -->