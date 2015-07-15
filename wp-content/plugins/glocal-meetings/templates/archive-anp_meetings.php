<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="main-archive">
            
            <?php if ( is_post_type_archive() ) { ?>
                <h1 class="archive-title h2">
					<?php post_type_archive_title(); ?>
				</h1>
          
            <?php } elseif ( is_tax( 'anp_meetings_type' ) ) { ?>
                <h1 class="archive-title h2">
					<span><?php _e( 'Meeting Type: ', 'anp_meetings' ); ?></span> <?php single_term_title(); ?>
				</h1>
            
            <?php } elseif ( is_tax( 'anp_meetings_tag' ) ) { ?>
                <h1 class="archive-title h2">
					<span><?php _e( 'Meeting Tags: ', 'anp_meetings' ); ?></span> <?php single_term_title(); ?>
				</h1>

            <?php } ?>
         
            <?php
            // Post query modified using pre_get_posts filter
            ?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          
            <?php $term_list = wp_get_post_terms($post->ID, 'anp_meetings_type', array("fields" => "names")); ?>

			<article role="article" id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
              
				<header class="article-header">

                  <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo $term_list[0]; ?> - <?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) ); ?></a></h3>
                  
				</header>

				<section class="entry-content">

					<?php// the_excerpt(); ?>

				</section>

				<footer class="article-footer">
                  
                  <p class="meta tags">
                    <?php echo get_the_term_list( $post->ID, 'anp_meetings_tag', 'Tags: <span class="tags">', ', ', '</span>' ) ?>
                    </p>

				</footer>

			</article>

			<?php endwhile; ?>
          
            <nav class="wp-prev-next">
                <ul class="clearfix">
                    <li class="prev-link"><?php next_posts_link( __( '&laquo; Older Entries', 'anp_meetings' )) ?></li>
                    <li class="next-link"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'anp_meetings' )) ?></li>
                </ul>
            </nav>

			<?php else : ?>

					<article id="post-not-found" class="hentry">
						<header class="article-header">
							<h1><?php _e( 'Oops, Post Not Found!', 'anp_meetings' ); ?></h1>
						</header>
						<section class="entry-content">
							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'anp_meetings' ); ?></p>
						</section>
						<footer class="article-footer">
								<p><?php _e( 'This is the error message in the archive.php template.', 'anp_meetings' ); ?></p>
						</footer>
					</article>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

    </div>

</div>

<?php get_footer(); ?>
