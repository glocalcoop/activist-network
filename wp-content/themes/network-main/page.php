<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="first" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<header class="article-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>

			<section class="entry-content clearfix">
				<?php the_content(); ?>
			</section>

			<footer class="article-footer"></footer>

			<?php // comments_template(); // uncomment if you want to use them ?>

			<?php endwhile; ?>

					<?php if ( function_exists( 'bones_page_navi' ) ) { ?>
							<?php bones_page_navi(); ?>
					<?php } else { ?>
							<nav class="wp-prev-next">
									<ul class="clearfix">
										<li class="prev-link"><?php next_posts_link( __( '&laquo; Older Entries', 'glocal-theme' )) ?></li>
										<li class="next-link"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'glocal-theme' )) ?></li>
									</ul>
							</nav>
					<?php } ?>

			<?php else : ?>

					<article id="post-not-found" class="hentry clearfix">
							<header class="article-header">
								<h1><?php _e( 'Oops, Post Not Found!', 'glocal-theme' ); ?></h1>
						</header>
							<section class="entry-content">
								<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'glocal-theme' ); ?></p>
						</section>
						<footer class="article-footer">
								<p><?php _e( 'This is the error message in the index.php template.', 'glocal-theme' ); ?></p>
						</footer>
					</article>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
