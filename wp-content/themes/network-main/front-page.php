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
			<?php // Widgets

			dynamic_sidebar( 'home-modules' ); ?>

			<?php ?>
		</section>

	</div>

</div>

<?php get_footer(); ?>
