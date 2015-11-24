<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package adaption
 */
?>


		<footer id="colophon" class="site-footer" role="contentinfo">

			<div class="site-info">
				<?php do_action( 'adaption_credits' ); ?>
				<a href="http://wordpress.org/" rel="generator"><?php printf( __( 'Proudly powered by %s', 'adaption' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'adaption' ), 'Adaption', '<a href="http://themes.wordpress.com/themes/adaption" rel="designer">WordPress.com</a>' ); ?>
			</div><!-- .site-info -->

		</footer><!-- #colophon .site-footer -->

	</div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>