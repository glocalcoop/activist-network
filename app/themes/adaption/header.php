<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Adaption
 */
?>
<!DOCTYPE html>
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
	<?php do_action( 'before' ); ?>

	<div id="mobile-panel">
		<?php if ( ( has_nav_menu( 'social' ) ) || ( has_nav_Menu( 'primary') ) ) : ?>
			<div id="mobile-link"></div><!-- #mobile-link -->
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
			<div id="widget-link"></div><!-- #widget-link -->
		<?php endif; ?>

		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	</div>

	<div id="panel-block">
		<div id="mobile-block">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'adaption' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation .main-navigation -->

			<?php
			if ( has_nav_menu( 'social' ) ) : ?>
				<div id="social-links-wrapper">
					<?php wp_nav_menu( array(
						'theme_location'  => 'social',
						'container_class' => 'social-links',
						'menu_class'      => 'clear',
						'link_before'     => '<span class="screen-reader-text">',
						'link_after'      => '</span>',
					) ); ?>
				</div>
			<?php endif; ?>
		</div><!-- #mobile-block-->

		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
			<div id="widget-block">

				<div class="widget-areas">
					<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
						<div class="widget-area">
							<?php dynamic_sidebar( 'sidebar-3' ); ?>
						</div><!-- .widget-area -->
					<?php endif; ?>
				</div><!-- .widgets-areas -->

			</div><!-- #widget-block-->
		<?php endif; ?>

	</div>

	<header id="masthead" class="panel" role="banner">
		<?php
		// You can upload a custom header and it'll output in a smaller logo size.
		$header_image = get_header_image();

		if ( ! empty( $header_image ) ) { ?>
			<div id="header-image" class="custom-header">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" class="header-image" />
				</a>
			</div><!-- #header-image .custom-header -->
		<?php } else { ?>
			<div id="header-image" class="no-header"></div><!-- #header-image .no-header -->
		<?php } ?>

		<div class="site-branding">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'adaption' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #site-navigation .main-navigation -->

		<?php
		if ( has_nav_menu( 'social' ) ) : ?>
			<div id="social-links-wrapper">
				<?php wp_nav_menu( array(
					'theme_location'  => 'social',
					'container_class' => 'social-links',
					'menu_class'      => 'clear',
					'link_before'     => '<span class="screen-reader-text">',
					'link_after'      => '</span>',
				) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<div class="widget-areas">
				<div class="widget-area">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</div><!-- .widget-area -->
			</div><!-- .widgets-areas -->
		<?php endif; ?>

	</header><!-- #masthead .site-header -->

	<?php get_sidebar(); ?>

	<div id="content" class="site-content">