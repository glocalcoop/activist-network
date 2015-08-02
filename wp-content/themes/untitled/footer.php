<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package untitled
 */
?>

	</div><!-- #main .site-main -->
</div><!-- #page .hfeed .site -->

	<div id="colophon-wrap">
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info">
				<?php do_action( 'untitled_credits' ); ?>
				<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'untitled' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'untitled' ), 'WordPress' ); ?></a>.
				<?php printf( __( 'Theme: %1$s by %2$s.', 'untitled' ), 'Untitled', '<a href="https://wordpress.com/themes/" rel="designer">WordPress.com</a>' ); ?>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #colophon-wrap -->

<?php wp_footer(); ?>
</body>
</html>