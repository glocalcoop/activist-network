<?php
// Template: Front Page

// This template makes heavy use of the Events Manager and the network-latest-posts plugin.
// Without Events Manager, the events module (module 3) will not appear.
// Without the network-latest-posts function, the network-wide posts (module 1 and module 2) will not appear

?>

<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<section class="home-start">
			
			<?php  if( 'page' == get_option('show_on_front') ) { ?>
		
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
					<?php 
					$home_content = get_the_content();
					if( !empty( $home_content ) ) { ?>

					<article class="home-intro">

						<?php the_content(); ?>

					</article>
			
					<?php } ?>

				<?php endwhile; endif; ?>
			
			<?php } ?>
			
		</section>

		<section class="home-modules">
			
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.widget_glocal_network_posts_widget .network-posts-list').bxSlider({
					slideWidth: 5000,
					minSlides: 2,
					maxSlides: 2,
					slideMargin: 10,
					pager: false
				});
				var responsive_viewport = jQuery(window).width();
				if (responsive_viewport < 320) {
					jQuery('.widget_glocal_network_posts_widget .network-posts-list').reloadSlider({
					slideWidth: 5000,
					minSlides: 1,
					maxSlides: 1,
					slideMargin: 10,
					pager: false
					});
				} 
			});
			</script>


			<?php // Widgets

			dynamic_sidebar( 'home-modules' ); ?>

			<?php
			

			// $posts = get_posts('post_type=post');
			// echo "<pre>";
			// var_dump($news);
			// echo "</pre>";

			?>
		</section>

	</div>

</div>

<?php get_footer(); ?>
