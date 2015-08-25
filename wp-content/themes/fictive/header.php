<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Fictive
 */

$gravatar = get_theme_mod( 'fictive_gravatar_email', get_option( 'admin_email' ) );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<?php if ( get_header_image() ) : ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" class="header-image">
		</a>
		<?php endif; // End header image check. ?>
		<div class="site-branding">
			<?php if ( '' !=  $gravatar ) : ?>
				<div class="header-avatar">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php echo esc_url( fictive_get_gravatar() ); ?>" width="70" height="70" alt="">
					</a>
				</div>
			<?php endif; ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<div class="social-links">
					<?php wp_nav_menu( array(
										'theme_location' => 'social',
										'depth'          => 1,
										'link_before'    => '<span class="screen-reader-text">',
										'link_after'     => '</span>')
										);
					?>
				</div>
			<?php endif; ?>
		</div>

		<div class="menu-toggles clear">
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<h1 id="menu-toggle" class="menu-toggle"><span class="screen-reader-text"><?php _e( 'Menu', 'fictive' ); ?></span></h1>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<h1 id="widgets-toggle" class="menu-toggle"><span class="screen-reader-text"><?php _e( 'Widgets', 'fictive' ); ?></span></h1>
			<?php endif; ?>
			<h1 id="search-toggle" class="menu-toggle"><span class="screen-reader-text"><?php _e( 'Search', 'fictive' ); ?></span></h1>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'fictive' ); ?></a>
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			<?php endif; ?>
		</nav><!-- #site-navigation -->

		<?php get_sidebar(); ?>

		<div id="site-search" class="header-search">
			<?php get_search_form(); ?>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
