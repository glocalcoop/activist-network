<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          
                <?php
                    $meeting_agenda = get_post_meta($post->ID, 'meeting_agenda', true);
                    $meeting_links = get_post_meta($post->ID, 'meeting_links', true);
                ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

					<header class="article-header">

                      <h1 class="entry-title single-title" itemprop="headline"><?php the_title(); ?></h1>
                      
                      <p class="meta"><?php
                      printf(__( 'Date: <time class="updated" datetime="%1$s" pubdate>%2$s</time>', 'anp_meetings' ),  get_post_meta($post->ID, 'meeting_date', true), date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) ) );
                      ?>
                      </p>
                      
                      <p class="meta">
                        <?php echo get_the_term_list( $post->ID, 'anp_meetings_type', 'Type: <span class="category">', ', ', '</span>' ) ?>
                      </p>
                      
					</header>

					<section class="entry-content clearfix" itemprop="articleBody">
                                            
                        <?php
                        if( !empty($meeting_agenda) ) {
                          
                            echo '<div class="meeting-agenda">';
                            echo '<h2>Agenda</h2>';
                            echo wpautop( $meeting_agenda, true );
                            echo '</div>';
                        }
                        ?>
                        
                        <h2 id="meeting-notes">Notes</h2>

                        <?php the_content(); ?>

					</section>

					<footer class="article-footer">
                      
                      <?php

                      if( !empty($meeting_links) ) {
                        echo '<h3 id="meeting-links">Associated Content</h3>';
                        echo '<ul class="meeting-links">';
                        foreach($meeting_links as $link) {
                          echo '<li>';
                          echo '<a href="' . get_permalink( $link ) . '">';
                          echo get_the_title( $link );
                          echo '</a>';
                          echo '</li>';
                        }
                        echo '</ul>';
                      }

                      ?>

                      <?php echo get_the_term_list( $post->ID, 'anp_meetings_tag', '<p class="tags meta">Tags: <span class="tags">', ', ', '</span></p>' ) ?>

					</footer>

					<?php comments_template(); ?>

				</article>

			<?php endwhile; ?>

			<?php else : ?>

				<article id="post-not-found" class="hentry clearfix">
						<header class="article-header">
							<h1><?php _e( 'Oops, Post Not Found!', 'anp_meetings' ); ?></h1>
						</header>
						<section class="entry-content">
							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'anp_meetings' ); ?></p>
						</section>
						<footer class="article-footer">
								<p><?php _e( 'This is the error message in the single.php template.', 'anp_meetings' ); ?></p>
						</footer>
				</article>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
