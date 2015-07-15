<article class="home-feature">

	<script type="text/javascript">

	jQuery(document).ready(function(){
	  jQuery('.featured-posts').bxSlider({
	    mode: 'fade',
	    adaptiveHeight: true,
	    captions: true
	  });
	});
	</script>

	<ul id="featured" class="featured-posts bxslider">
	<!-- Displays sticky posts from the current site -->

		<?php
		$sticky = get_option( 'sticky_posts' ); // Fetch an array of sticky posts
		rsort( $sticky ); // Sort by latest first
		$sticky = array_slice( $sticky, 0, 5 ); // Change the last number to show more or less posts
		$featuredposts = new WP_Query( array( 'post__in' => $sticky, 'ignore_sticky_posts' => 1 ) );

		while ($featuredposts->have_posts()) : $featuredposts->the_post();
			$permalink = get_permalink();
			$title = get_the_title();
			// $post_meta = get_post_meta($post->ID, 'glocal_volunteer_location', true);
			$imagearg = array(
                'title'	=> trim(strip_tags($title)),
				'alt'	=> trim(strip_tags($title))
			);
		?>

		<li class="featured-post">
			<a href="<?php echo $permalink; ?>" title="<?php echo get_the_title();?>" >
			<?php echo get_the_post_thumbnail($post->ID, 'full', $imagearg); ?>
			</a>
		</li>
		

	<?php endwhile; ?>
	<?php wp_reset_query(); ?> 

	</ul>

</article>