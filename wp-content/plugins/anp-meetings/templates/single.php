<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          
        <?php
          $query = get_queried_object();
        ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

					<header class="article-header">

            <h1 class="entry-title single-title" itemprop="headline"><?php the_title(); ?></h1>
            
            <p class="meta"><?php
            printf(__( 'Date: <time class="updated" datetime="%1$s" pubdate>%2$s</time>', 'meeting' ),  get_post_meta($post->ID, 'meeting_date', true), date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) ) );
            ?>
            </p>
            
            <p class="meta">
              <?php echo get_the_term_list( $post->ID, 'meeting_type', 'Type: <span class="category">', ', ', '</span>' ) ?>
            </p>

            <?php  
            // Find connected summary
            $agenda = new WP_Query( array(
              'connected_type' => 'meeting_to_agenda',
              'connected_items' => $query,
              'nopaging' => true,
            ) );

            // Display connected pages
            if ( $agenda->have_posts() ) :
            ?>
            <p class="meta">
              <?php while ( $agenda->have_posts() ) : $agenda->the_post(); ?>
              <a href="<?php the_permalink(); ?>"><?php _e( 'Agenda', 'meeting' ); ?></a>
              <?php endwhile; ?>
            </p>

            <?php 
            // Prevent weirdness
            wp_reset_postdata();

            endif;
            ?>

            <?php  
            // Find connected summary
            $summary = new WP_Query( array(
              'connected_type' => 'meeting_to_summary',
              'connected_items' => $query,
              'nopaging' => true,
            ) );

            // Display connected pages
            if ( $summary->have_posts() ) :
            ?>
            <p class="meta">
              <?php while ( $summary->have_posts() ) : $summary->the_post(); ?>
              <a href="<?php the_permalink(); ?>"><?php _e( 'Summary', 'meeting' ); ?></a>
              <?php endwhile; ?>
            </p>

            <?php 
            // Prevent weirdness
            wp_reset_postdata();

            endif;
            ?> 
                      
					</header>

					<section class="entry-content clearfix" itemprop="articleBody">
                                                        
            <?php the_content(); ?>

					</section>

					<footer class="article-footer">

            <?php  

            // Find connected pages
            $proposals = new WP_Query( array(
              'connected_type' => 'meeting_to_proposals',
              'connected_items' => $query,
              'nopaging' => true,
            ) );

            // Display connected pages
            if ( $proposals->have_posts() ) :
            ?>
            <h3><?php _e( 'Proposals', 'meeting' ); ?></h3>
            <ul class="meeting-links">
              <?php while ( $proposals->have_posts() ) : $proposals->the_post(); ?>
              <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
              <?php endwhile; ?>
            </ul>

            <?php 
            // Prevent weirdness
            wp_reset_postdata();

            endif;
            ?>                  

            <?php echo get_the_term_list( $post->ID, 'meeting_tag', '<p class="tags meta">Tags: <span class="tags">', ', ', '</span></p>' ) ?>

					</footer>

					<?php comments_template(); ?>

				</article>

			<?php endwhile; ?>

			<?php else : ?>

				<article id="post-not-found" class="hentry clearfix">
						<header class="article-header">
							<h1><?php _e( 'Oops, Post Not Found!', 'meeting' ); ?></h1>
						</header>
						<section class="entry-content">
							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'meeting' ); ?></p>
						</section>
						<footer class="article-footer">
								<p><?php _e( 'This is the error message in the single.php template.', 'meeting' ); ?></p>
						</footer>
				</article>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
