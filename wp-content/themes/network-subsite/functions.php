<?php
/*
Author: Pea, Glocal
URL: htp://glocal.coop
*/

$args = array(
	'flex-width'    => true,
	'width'         => 960,
	'flex-height'    => true,
	'height'        => 300,
	'header-text'   => false,
);
add_theme_support( 'custom-header', $args );


// Remove theme customization settings for child theme
// Remove menu customization on child themes

function network_subsite_remove_theme_customization() {

    unregister_nav_menu( 'main-nav' );
	unregister_nav_menu( 'secondary-nav' );
	unregister_nav_menu( 'utility-nav' );
	unregister_nav_menu( 'footer-links' );
	register_nav_menus(
		array(
			'site-nav' => __( 'Main Menu', 'network-subsite' ),
		)
	);
	remove_action( 'customize_register', 'glocal_customize_register');
}
add_action( 'after_setup_theme', 'network_subsite_remove_theme_customization', 20 ); 

// Remove Parent or Network Theme page templates from child theme
add_filter( 'theme_page_templates', 'remove_network_page_template' );
    function remove_network_page_template( $pages_templates ) {
    unset( $pages_templates['page-directory.php'] );
    unset( $pages_templates['page-news.php'] );
    return $pages_templates;
}

?>