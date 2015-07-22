<?php
// Template: Front Page

?>

<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<section class="home-start">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<?php 
				$home_content = get_the_content();
				if( !empty( $home_content ) ) { ?>

				<article class="home-intro">

					<?php the_content(); ?>

				</article>
		
				<?php } ?>

			<?php endwhile; endif; ?>
			
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

			<?php ?>
		</section>

	</div>

</div>

<?php get_footer(); ?>
