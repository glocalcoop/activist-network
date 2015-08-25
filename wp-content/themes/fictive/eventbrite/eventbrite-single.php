<?php
/**
 * The Template for displaying all single Eventbrite events.
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
				// Get our event based on the ID passed by query variable.
				$event = new Eventbrite_Query( array( 'p' => get_query_var( 'eventbrite_id' ) ) );

				if ( $event->have_posts() ) :
					while ( $event->have_posts() ) : $event->the_post(); ?>

						<div class="hentry-wrapper">
							<?php if ( has_post_thumbnail() ) : ?>
								<figure class="entry-thumbnail">
									<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail(); ?></a>
								</figure>
							<?php endif; ?>

							<article id="event-<?php the_ID(); ?>" <?php post_class(); ?>>
								<span class="screen-reader-text"><?php esc_html_e( 'Eventbrite event', 'fictive' ); ?></span>

								<header class="entry-header">
									<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

									<div class="entry-meta">
										<?php eventbrite_event_meta(); ?>

										<?php eventbrite_edit_post_link( __( 'Edit', 'fictive' ), '<span class="edit-link">', '</span>' ); ?>
									</div><!-- .entry-meta -->
								</header><!-- .entry-header -->

								<div class="entry-content">
									<?php the_content(); ?>

									<?php eventbrite_ticket_form_widget(); ?>
								</div><!-- .entry-content -->
							</article><!-- #post-## -->
						</div><!-- .hentry-wrapper -->

					<?php endwhile;

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;

				// Return $post to its rightful owner.
				wp_reset_postdata();
			?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
