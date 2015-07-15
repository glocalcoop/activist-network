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


			<?php

			if(function_exists('glocal_customization_settings')) {
				$glocal_home_settings = glocal_customization_settings();
			} else {
				echo '<pre>glocal_customization_settings() does not exist.</pre>';
			}

			?>

			<?php if (! is_multisite() ) { // Check to see if multisite is active. If not, display a recent posts and events module for this site. ?> 

				<?php get_template_part( 'partials/error', 'multisite' ); ?>

			<?php } else { ?>

				<?php

				// Check that the array is populated
				if(!empty($glocal_home_settings['modules'])) { ?>

					<?php
					if (in_array("updates", $glocal_home_settings['modules'])) {
						
						// Get network-wide updates
						get_template_part( 'partials/home-module', 'updates' );
					}
					?>

					<?php
					if (in_array("posts", $glocal_home_settings['modules'])) {
						
						// Get network-wide posts
						get_template_part( 'partials/home-module', 'posts' );
					}
					?>

					<?php
					if (in_array("events", $glocal_home_settings['modules'])) {
						
						// Get network-wide events
						get_template_part( 'partials/home-module', 'events' );
					}
					?>

					<?php
					if (in_array("sites", $glocal_home_settings['modules'])) {

						// Get network-wide sites
						get_template_part( 'partials/home-module', 'sites' );
					}
					?>

				<?php } else { ?>

					<?php
					// Get network-wide updates
					get_template_part( 'partials/home-module', 'updates' ); ?>

					<?php
					// Get network-wide posts
					get_template_part( 'partials/home-module', 'posts' ); ?>

					<?php
					// Get network-wide events
					get_template_part( 'partials/home-module', 'events' ); ?>

					<?php
					// Get network-wide sites
					get_template_part( 'partials/home-module', 'sites' ); ?>


				<?php } ?>


			<?php } ?>
			
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
