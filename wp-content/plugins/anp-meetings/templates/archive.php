<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="main-archive">
            
            <?php if ( is_post_type_archive() ) { ?>
                <h1 class="archive-title h2">
					<?php post_type_archive_title(); ?>
				</h1>
          
            <?php } elseif ( is_tax( 'meeting_type' ) ) { ?>
                <h1 class="archive-title h2">
					<span><?php _e( 'Meeting Type: ', 'meeting' ); ?></span> <?php single_term_title(); ?>
				</h1>
            
            <?php } elseif ( is_tax( 'meeting_tag' ) ) { ?>
                <h1 class="archive-title h2">
					<span><?php _e( 'Meeting Tags: ', 'meeting' ); ?></span> <?php single_term_title(); ?>
				</h1>

            <?php } ?>
         
            <?php
             $queried_object = get_queried_object();
             // var_dump( $queried_object );
            // Post query modified using pre_get_posts filter
            ?>

            <?php
            // Find connected pages (for all posts)
            p2p_type( 'meeting_to_agenda' )->each_connected( $wp_query, array(), 'agendas' );

            p2p_type( 'meeting_to_summary' )->each_connected( $wp_query, array(), 'summaries' );

            p2p_type( 'meeting_to_proposals' )->each_connected( $wp_query, array(), 'proposals' );
            ?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          
            <?php $term_list = wp_get_post_terms( $post->ID, 'meeting_type', array( "fields" => "names" ) ); ?>

			<article role="article" id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
              
				<header class="article-header">

                  <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo $term_list[0]; ?> - <?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) ); ?></a></h3>
                  
				</header>

				<section class="entry-content">

				</section>

				<footer class="article-footer">

                  <div class="meta tags">
                    <?php echo get_the_term_list( $post->ID, 'meeting_tag', 'Tags: <span class="tags">', ', ', '</span>' ) ?>
                  </div>

                      

                  <div class="related-content">

                    <?php
                    if( count( $post->agendas ) > 0 ) {

                        foreach ( $post->agendas as $post ) : setup_postdata( $post ); ?>

                            <span class="tags agenda"><a href="<?php the_permalink( $post->ID ); ?>"><?php _e( 'Agenda', 'meeting' ) ?></a></span>
                                                    
                        <?php
                        endforeach;

                    }

                    wp_reset_postdata(); ?>


                    <?php
                    if( count( $post->summaries ) > 0 ) {

                        foreach ( $post->summaries as $post ) : setup_postdata( $post ); ?>

                            <span class="tags summary"><a href="<?php the_permalink( $post->ID ); ?>"><?php _e( 'Summary', 'meeting' ) ?></a></span>
                                                    
                        <?php
                        endforeach;

                    }

                    wp_reset_postdata();
                    ?>


                    <?php
                    if( count( $post->proposals ) > 0 ) {

                    }
                    ?>

                  </div>

				</footer>

			</article>

			<?php endwhile; ?>
          
            <nav class="wp-prev-next">
                <ul class="clearfix">
                    <li class="prev-link"><?php next_posts_link( __( '&laquo; Older Entries', 'meeting' )) ?></li>
                    <li class="next-link"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'meeting' )) ?></li>
                </ul>
            </nav>

			<?php else : ?>

					<article id="post-not-found" class="hentry">
						<header class="article-header">
							<h1><?php _e( 'Oops, Post Not Found!', 'meeting' ); ?></h1>
						</header>
						<section class="entry-content">
							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'meeting' ); ?></p>
						</section>
						<footer class="article-footer">
								<p><?php _e( 'This is the error message in the archive.php template.', 'meeting' ); ?></p>
						</footer>
					</article>

			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

    </div>

</div>

<?php get_footer(); ?>
