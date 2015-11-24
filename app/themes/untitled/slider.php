<?php
/**
 * @package untitled
 */
$featured = untitled_get_featured_posts();

if ( empty( $featured ) ) :
	return;
endif;
?>

<div id="featured-content" class="flexslider">
	<ul class="featured-posts slides">

		<?php
			foreach ( $featured as $post ) :
				setup_postdata( $post );
				if ( '' != get_the_post_thumbnail( $post->ID ) ) :
		?>
			<li class="featured">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'slider-img' ); ?></a>
				<div class="featured-hentry-wrap">
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="slider-header">
							<div class="slide-meta">
								<?php
									$categories_list = get_the_category_list( __( ', ', 'untitled' ) );
									if ( $categories_list && untitled_categorized_blog() ) :
										echo '<span class="categories-links">' . $categories_list . '</span>';
									endif;
								?>
							</div>
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						</div><!-- .slider-header -->
						<div class="slide-meta">
							<?php get_the_date(); ?>
						</div><!-- .slide-meta -->
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
