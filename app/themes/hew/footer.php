<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Hew
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer wrap" role="contentinfo">
		<div class="site-info">
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'hew' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'hew' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'hew' ), 'Hew', '<a href="http://wordpress.com/themes/hew/" rel="designer">WordPress.com</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
