<?php  // Get the site info for the main site
$global_site_details = get_blog_details(1);
$global_site_header = glocal_get_site_image(1);
?>

<footer class="footer-global footer" role="contentinfo">
	<div class="wrap">

		<section class="global site-meta first">
			<h2 class="footer-logo"><a href="<?php echo $global_site_details->siteurl; ?>"><img src="<?php echo $global_site_header?>" alt="<?php echo $global_site_details->blogname; ?>" /></a> <span class="tagline-NYCP"><?php bloginfo('description'); ?></span></h2>
		</section>
		
		<section class="widgets">
			<?php if ( is_active_sidebar( 'footer1' ) ) : ?>
				<?php dynamic_sidebar( 'footer1' ); ?>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer2' ) ) : ?>
				<?php dynamic_sidebar( 'footer2' ); ?>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer3' ) ) : ?>
				<?php dynamic_sidebar( 'footer3' ); ?>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer4' ) ) : ?>
				<?php dynamic_sidebar( 'footer4' ); ?>
			<?php endif; ?>
		</section>
		
		<nav class="footer-nav" role="navigation clearfix">
			<?php bones_footer_links(); ?>
		</nav>

	</div>
</footer>