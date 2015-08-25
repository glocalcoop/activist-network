<?php
/**
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Hew
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php
		if ( is_active_sidebar( 'sidebar-1' )
		  || is_active_sidebar( 'sidebar-2' )
		  || is_active_sidebar( 'sidebar-3' )
		  || is_active_sidebar( 'sidebar-4' )
		) :
	?>
	<div id="widgets-wrapper" class="hide">
		<?php get_sidebar(); ?>
	</div>
	<?php endif ;?>

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hew' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) { ?>
			<a class="site-logo"  href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="" class="no-grav header-image" />
			</a>
		<?php } // if ( ! empty( $header_image ) ) ?>

		<div class="site-branding">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle toggle-button"><span class="screen-reader-text"><?php _e( 'Primary Menu', 'hew' ); ?></span></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation -->
		<?php endif; ?>

		<?php if ( has_nav_menu ( 'social' ) ) : ?>
			<nav class="social-links">
				<?php wp_nav_menu( array( 'theme_location' => 'social', 'depth' => 1, 'link_before' => '<span class="screen-reader-text">', 'link_after' => '</span>', 'container' => 'false', ) ); ?>
			</nav>
		<?php endif; ?>

		<?php
			if ( is_active_sidebar( 'sidebar-1' )
			  || is_active_sidebar( 'sidebar-2' )
			  || is_active_sidebar( 'sidebar-3' )
			  || is_active_sidebar( 'sidebar-4' )
			) :
		?>
		<div class="toggle-wrapper">
			<a href="#" class="widgets-toggle toggle-button" title="<?php esc_attr_e( 'Widgets', 'hew' ); ?>">
				<span class="screen-reader-text"><?php _e( 'Widgets', 'hew' ); ?></span>
			</a>
		</div>
		<?php endif; ?>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
