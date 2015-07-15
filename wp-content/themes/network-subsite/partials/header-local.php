<header class="header-local" role="banner">

	<div class="wrap">

		<div class="site-banner">
		    <div class="banner-inner">
			    <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
		    </div>
            <h1 class="site-name"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></h1>
		</div>
		<?php // bloginfo('description'); ?>


		<nav role="navigation" class="nav-local">
			<ul class="nav-anchors js-anchors">
            	<li><a href="#menu-main-navigation-1" class="anchor-menu" title="menu">MENU</a></li>
            	<li><a href="#search-local" class="anchor-search" title="search"></a></li>
            </ul>
			<div class="search-form" id="search-local">
			    <?php get_search_form(); ?>
			</div>
			<?php wp_nav_menu( array( 
				'theme_location' => 'site-nav',
				'container' => false,                           // remove nav container
				'menu_class' => 'menu clearfix',                // adding custom nav class
				'depth' => 0,                                   // limit the depth of the nav
			 ) ); ?>
		</nav>

	</div>

</header>