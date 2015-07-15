<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="first" role="main">

			<h1 class="archive-title h2"><?php post_type_archive_title(); ?></h1>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article">

				<header class="article-header">

					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					<p class="byline vcard"><?php
						printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.', 'glocal-theme' ), get_the_time( 'Y-m-j' ), get_the_time( __( 'F jS, Y', 'glocal-theme' ) ), bones_get_the_author_posts_link());
					?></p>

				</header>

				<section class="entry-content clearfix">

					<?php the_excerpt(); ?>

				</section>

				<footer class="article-footer">

				</footer>

			</article>

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
								<p><?php _e( 'This is the error message in the custom posty type archive template.', 'glocal-theme' ); ?></p>
						</footer>
					</article>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
