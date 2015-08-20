<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Sequential
 */
?>

	</div><!-- #content -->

	<?php get_sidebar( 'footer' ); ?>

	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<nav class="footer-navigation" role="navigation">
			<?php
				wp_nav_menu( array(
					'theme_location'  => 'footer',
					'container_class' => 'menu-footer',
					'menu_class'      => 'clear',
					'depth'           => 1,
				) );
			?>
		</nav><!-- #site-navigation -->
	<?php endif; ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'sequential' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'sequential' ), 'WordPress' ); ?></a>
			<span class="sep"> &mdash; </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'sequential' ), 'Sequential', '<a href="https://wordpress.com/themes/" rel="designer">WordPress.com</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>