<header class="header-global">

	<div class="wrap">

		<h1 class="site-title <?php if( get_header_image() ) { echo 'network-logo'; } ?>">
			<a class="global-logo logo-network" href="<?php bloginfo( 'url' ); ?>">
            <?php 
            if( get_header_image() ) { ?>
            
                <img src="<?php echo( get_header_image() ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
            
            <?php } else { ?>
            
                <?php bloginfo( 'name' ); ?>
            
            <?php } ?>
	        </a>
	    </h1>

		<nav role="navigation" class="nav-global">
			<ul class="nav-anchors js-anchors">
            	<li><a href="#menu-global" class="anchor-menu" title="menu"><?php bloginfo( 'name' ); ?></a></li>
            	<li><a href="#search-global" class="anchor-search" title="search"></a></li>
            </ul>
			<div class="search-form" id="search-global">
				<?php get_search_form(); ?>
			</div>
			<?php
            wp_nav_menu(array(
				'container' => false,                           // remove nav container
				'container_class' => 'menu',           // class of container (should you choose to use it)
				'menu' => __( 'Global Network Menu', 'glocal-theme' ),  // nav name
				'menu_class' => 'main-nav',           // adding custom nav class
		        'menu_id' => 'menu-global',         			// menu id
				'theme_location' => 'network-menu',             // where it's located in the theme
				'before' => '',                                 // before the menu
				'after' => '',                                  // after the menu
				'link_before' => '',                            // before each link
				'link_after' => '',                             // after each link
				'depth' => 0,                                   // limit the depth of the nav
				'fallback_cb' => 'false'                        // fallback function - FALSE
			));
			?>
		</nav>

	</div>

</header>