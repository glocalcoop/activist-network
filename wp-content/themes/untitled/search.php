<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package untitled
 */

get_header(); ?>

	<div id="main" class="site-main">
		<section id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'untitled' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->

				<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'content', 'search' );
						endwhile;
						untitled_content_nav( 'nav-below' );

					else :
						get_template_part( 'no-results', 'search' );

					endif;
				?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
