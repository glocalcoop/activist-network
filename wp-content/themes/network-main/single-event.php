<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

					<header class="article-header">

						<section class="event-image"><?php echo $EM_Event->output('#_EVENTIMAGE'); ?></section>
						
						<h1 class="entry-title single-title event-title" itemprop="headline"><?php echo $EM_Event->output('#_EVENTNAME'); ?></h1>
						<p class="byline vcard"><?php
							printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span> <span class="amp">&amp;</span> filed under %4$s.', 'glocal-theme' ), get_the_time( 'Y-m-j' ), get_the_time( get_option('date_format')), bones_get_the_author_posts_link(), get_the_category_list(', ') );
						?></p>

					</header>

					<section class="event-details">
						<h4 class="date-time">
							<span class="event-day"><?php echo $EM_Event->output('#l'); ?></span>
							<span class="event-date"><?php echo $EM_Event->output('#F #j, #Y'); ?></span>
							<span class="event-time"><?php echo $EM_Event->output('#g:#i#a'); ?> - <?php echo $EM_Event->output('#@g:#@i#@a'); ?></span>
						</h4>

						<?php if($EM_Event->location_id) { ?>
						<h4 class="location">
							<span class="location-name"><?php echo $EM_Event->output('#_LOCATIONLINK'); ?></span>
							<span class="location-address"><?php echo $EM_Event->output('#_LOCATIONTOWN #_LOCATIONSTATE'); ?></span>
						</h4>
						<?php } ?>

						<div class="tools"><a href="<?php echo $EM_Event->output('#_EVENTICALURL'); ?>" class="add-to-calendar button">Add to Calendar</a></div>
					</section>

					<section class="event-description">
					
					    <?php echo $EM_Event->output('#_EVENTNOTES'); ?>
					
						<?php if($EM_Event->location_id) { ?>
						<div class="event-map"><?php echo $EM_Event->output('#_MAP'); ?></div>
						<?php } ?>

					</section>

					<footer class="event-footer">
						<div class="meta categories"><?php echo $EM_Event->output('#_EVENTCATEGORIES'); ?></div>
						<div class="meta tags"><?php echo $EM_Event->output('#_EVENTTAGS'); ?></div>
						<div class="share"></div>
					</footer>

					<?php
					// echo "<pre>";
					// var_dump($EM_Event);
					// echo "</pre>";
					?>

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

		<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
