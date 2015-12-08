<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Sequential
 */
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
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'sequential' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<?php
			$sequential_top_area_content = get_theme_mod( 'sequential_top_area_content' );
			if ( '' != $sequential_top_area_content ) :
		?>
		<div class="top-content">
			<div class="wrapper">
				<?php echo wp_kses_post( $sequential_top_area_content ); ?>
			</div>
		</div><!-- .site-top-content -->
		<?php endif; ?>
		<div class="wrapper">
			<div class="site-branding">
				<?php sequential_the_site_logo(); ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<button class="menu-toggle" aria-expanded="false" ><span class="screen-reader-text"><?php _e( 'Menu', 'sequential' ); ?></span></button>
					<?php
						wp_nav_menu( array(
							'theme_location'  => 'primary',
							'container_class' => 'menu-primary',
							'menu_class'      => 'clear nav-menu',
						) );
					?>
				</nav><!-- #site-navigation -->
			<?php endif; ?>

			<?php if ( get_header_image() ) : ?>
				<div class="header-image">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
					</a>
				</div>
			<?php endif; ?>
		</div><!-- .wrapper -->
		<?php if ( function_exists( 'jetpack_breadcrumbs' ) && is_page() && ! is_front_page() ) : ?>
			<div class="breadcrumb-area">
				<div class="wrapper">
					<?php jetpack_breadcrumbs(); ?>
				</div><!-- .wrapper -->
			</div><!-- .breadcrumb-area -->
		<?php endif; ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
