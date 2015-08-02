<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Adaption
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="entry-title page-title"><?php printf( __( 'Search Results for: %s', 'adaption' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .fullwidth-block .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

			<?php adaption_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main .site-main -->
	</section><!-- #primary .content-area -->

<?php get_footer(); ?>