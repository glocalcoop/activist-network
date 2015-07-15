<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<?php get_sidebar(); ?>

		<main class="main-partner">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

					<header class="article-header">

						<?php 
						if ( has_post_thumbnail() ) { ?>

						<section class="post-image">
							<?php the_post_thumbnail('full'); ?> 
						</section>

						<?php } ?>

						<h1 class="entry-title single-title" itemprop="headline"><?php the_title(); ?></h1>
						<p class="byline vcard"><?php
							printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time>', 'glocal-theme' ), get_the_time( 'Y-m-j' ), get_the_time( get_option('date_format')), bones_get_the_author_posts_link(), get_the_category_list(', ') );
						?></p>

					</header>

					<section class="entry-content clearfix" itemprop="articleBody">
						<?php the_content(); ?>
					</section>

					<footer class="article-footer">
						<?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'glocal-theme' ) . '</span> ', ', ', '</p>' ); ?>

					</footer>

					<?php comments_template(); ?>

				</article>

			<?php endwhile; ?>

			<?php else : ?>

				<article id="post-not-found" class="hentry clearfix">
						<header class="article-header">
							<h1><?php _e( 'Oops, Post Not Found!', 'glocal-theme' ); ?></h1>
						</header>
						<section class="entry-content">
							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'glocal-theme' ); ?></p>
						</section>
						<footer class="article-footer">
								<p><?php _e( 'This is the error message in the single.php template.', 'glocal-theme' ); ?></p>
						</footer>
				</article>

			<?php endif; ?>

		</main>

	</div>

</div>

<?php get_footer(); ?>
