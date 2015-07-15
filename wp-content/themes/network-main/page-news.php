<?php
/*
Template Name: Network News
*/
?>

<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<div id="intro" role="intro" class="intro-news">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article role="article" id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/BlogPosting">

				<header class="post-header">
					<h1 class="post-title" itemprop="headline"><?php the_title(); ?></h1>
				</header>

				<section class="post-body" itemprop="articleBody">
					<?php the_content(); ?>
				</section>

				<footer class="post-footer">
					<?php the_tags( '<h6 class="tags">' . __( 'Tags:', 'glocal-theme' ) . '</h6> ', ', ', '' ); ?>
				</footer>

			</article>

			<?php endwhile; endif; ?>

		</div>
		
		
		<main class="main-news" >
			
		<?php

			if(function_exists( 'glocal_networkwide_sites_module' )) {

				$parameters = array(
					'style' => 'block',
					'id' => 'network-posts-list',
					'show_thumbnail' => True,
				);	

				echo glocal_networkwide_posts_module($parameters);

			} else {
				
				get_template_part( 'partials/error', 'plugin' );
				
			}


		?>			

		</main>

		<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
