<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="main-archive">

			<?php if (is_category()) { ?>
				<h1 class="archive-title h2">
					<span><?php _e( 'Posts Categorized:', 'glocal-theme' ); ?></span> <?php single_cat_title(); ?>
				</h1>
          
            <?php } elseif ( is_post_type_archive() ) { ?>
                <h1 class="archive-title h2">
					<?php post_type_archive_title(); ?>
				</h1>
          
            <?php } elseif ( is_tax() ) { ?>
                <h1 class="archive-title h2">
					<span><?php _e( 'Taxonomy:', 'glocal-theme' ); ?></span> <?php single_term_title(); ?>
				</h1>

			<?php } elseif (is_tag()) { ?>
				<h1 class="archive-title h2">
					<span><?php _e( 'Posts Tagged:', 'glocal-theme' ); ?></span> <?php single_tag_title(); ?>
				</h1>

			<?php } elseif (is_author()) {
				global $post;
				$author_id = $post->post_author;
			?>
				<h1 class="archive-title h2">
					<span><?php _e( 'Posts By:', 'glocal-theme' ); ?></span> <?php the_author_meta('display_name', $author_id); ?>
				</h1>
				
			<?php } elseif (is_day()) { ?>
				<h1 class="archive-title h2">
					<span><?php _e( 'Daily Archives:', 'glocal-theme' ); ?></span> <?php the_time('l, F j, Y'); ?>
				</h1>

			<?php } elseif (is_month()) { ?>
				<h1 class="archive-title h2">
					<span><?php _e( 'Monthly Archives:', 'glocal-theme' ); ?></span> <?php the_time('F Y'); ?>
				</h1>

			<?php } elseif (is_year()) { ?>
				<h1 class="archive-title h2">
					<span><?php _e( 'Yearly Archives:', 'glocal-theme' ); ?></span> <?php the_time('Y'); ?>
				</h1>
					
			<?php } ?>

			<?php if( is_post_type_archive( array( 'meeting', 'proposal', 'summary', 'agenda' ) ) ) : ?>

				<?php ( function_exists( 'anp_meetings_taxonomy_filter' ) ) ? anp_meetings_taxonomy_filter() : '' ;?>

			<?php endif; ?>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <?php get_template_part( 'partials/content', 'archive' ); ?>

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

				<?php get_template_part( 'partials/error', 'no-posts' ); ?>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

    </div>

</div>

<?php get_footer(); ?>
