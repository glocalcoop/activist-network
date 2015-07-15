<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<?php get_sidebar(); ?>

		<main class="main-partner">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article role="article" class="partner post news">
			
				<header class="post-header">
					<div class="post-date date"><?php echo get_the_date('M j, Y'); ?></div>
				</header>
				
				<section class="post-body">
					<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="post-image"><?php echo get_the_post_thumbnail($page->ID, 'medium'); ?></div>
					<div class="post-excerpt"><?php the_excerpt(); ?></div>
				</section>
				
			</article>

		<?php endwhile; ?>

		<?php if ( function_exists( 'bones_page_navi' ) ) { ?>

			<?php bones_page_navi(); ?>

		<?php } else { ?>
				<nav class="wp-prev-next">
					<ul>
						<li class="prev-link"><?php next_posts_link( __( '&laquo; Older Entries', 'bonestheme' )) ?></li>
						<li class="next-link"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'bonestheme' )) ?></li>
					</ul>
				</nav>
		<?php } ?>

		<?php else : ?>

			<article id="post-not-found" class="hentry">
			    <header class="article-header">
                    <h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
				</header>
                <section class="entry-content">
                    <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
                </section>
				<footer class="article-footer">
                    <p><?php _e( 'This is the error message in the index.php template.', 'bonestheme' ); ?></p>
				</footer>
			</article>

		<?php endif; ?>

		</main>

	</div>

</div>

<?php get_footer(); ?>
