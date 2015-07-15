<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article">

	<header class="article-header">

		<h2 class="post-title"><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h2>

	</header>

	<section class="entry-content">
		<?php the_content(); ?>
	</section>

	<footer class="article-footer">
		<p class="tags"><?php the_tags( '<span class="tags-title">' . __( 'Tags:', 'glocal-theme' ) . '</span> ', ', ', '' ); ?></p>

	</footer>

</article>