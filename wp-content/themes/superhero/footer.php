<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Superhero
 * @since Superhero 1.0
 */
?>

	</div><!-- #main .site-main -->

	<div id="colophon-wrap">
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'superhero_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'superhero' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'superhero' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'superhero' ), 'Superhero', '<a href="https://wordpress.com/themes/" rel="designer">WordPress.com</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->
	</div><!-- #colophon-wrap -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>
</body>
</html>