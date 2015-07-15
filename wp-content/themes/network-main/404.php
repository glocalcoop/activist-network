<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="first" role="main">

			<article id="post-not-found" class="hentry clearfix">

				<header class="article-header">

					<h1><?php _e( 'Epic 404 - Article Not Found', 'glocal-theme' ); ?></h1>

				</header>

				<section class="entry-content">

					<p><?php _e( 'The article you were looking for was not found, but maybe try looking again!', 'glocal-theme' ); ?></p>

				</section>

				<section class="search">

						<p><?php get_search_form(); ?></p>

				</section>

				<footer class="article-footer">

						<p><?php _e( 'This is the 404.php template.', 'glocal-theme' ); ?></p>

				</footer>

			</article>

		</main>

	</div>

</div>

<?php get_footer(); ?>
