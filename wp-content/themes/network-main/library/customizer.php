<?php
/**
 * Glocal Network Theme Customizer
 *
 * @package Glocal Network
 */

function anp_customize_register( $wp_customize ) {

	//Section

	$wp_customize->add_section( 'colors', array(
		'title' => __( 'Colors', 'glocal-theme' ),
		'description' => __( 'Description', 'glocal-theme' ),
		'priority' => 40, // After title_tagline
		'capability' => 'edit_theme_options'
	) );

	//Settings

	//Primary Color
	$wp_customize->add_setting( 'primary_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Secondary Color
	//Callouts
	$wp_customize->add_setting( 'secondary_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Accent Color
	//Buttons, Scrollers
	$wp_customize->add_setting( 'accent_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Page Background Color
	$wp_customize->add_setting( 'page_background_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Post Background Color
	$wp_customize->add_setting( 'post_background_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Heading Color
	$wp_customize->add_setting( 'heading_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Text Color
	$wp_customize->add_setting( 'text_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	//Link Color
	$wp_customize->add_setting( 'link_color', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );


	// Controls

	//Primary Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
		'label' => __( 'Primary Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Secondary Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
		'label' => __( 'Secondary Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Accent Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
		'label' => __( 'Accent Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Page Background Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_background_color', array(
		'label' => __( 'Page Background Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Post Background Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_background_color', array(
		'label' => __( 'Post Background Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Heading Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
		'label' => __( 'Heading Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Text Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
		'label' => __( 'Text Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

	//Link Color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label' => __( 'Link Color', 'glocal-theme' ),
		'section' => 'colors'
	) ) );

}

add_action( 'customize_register', 'anp_customize_register' );


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function anp_customize_postmessage_support( $wp_customize ) {

	$wp_customize->get_setting( 'primary_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'secondary_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'accent_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'page_background_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'post_background_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'heading_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'text_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'link_color' )->transport = 'postMessage';

}

add_action( 'customize_register', 'anp_customize_postmessage_support' );


/**
 * Registers the Theme Customizer Preview JavaScript with WordPress.
 */

function anp_enqueue_preview_js() {
	wp_enqueue_script( 'anp-customize-preview-js', 
		get_template_directory_uri() . '/library/js/customize-preview.js', 
		array( 'jquery', 'customize-preview' ),
		'',
		true );
}

add_action( 'customize_preview_init', 'anp_enqueue_preview_js' );



function anp_dynamic_css() { ?>
	
	<style type='text/css'>

	/* Nav */
	.header-global {
		background-color: <?php echo get_theme_mod('secondary_color') ?>;
	}

	/* Nav - Desktop */
	@media (min-width: 769px) { 
		.header-global .menu li.current-menu-item:after, 
        .header-global .menu li.current_page_item:after, 
        .header-global .menu li.current-page-ancestor:after {
        	
        	border-bottom-color: <?php echo get_theme_mod('accent_color') ?>;
        }

        .header-global .menu li.current-menu-item > a, 
        .header-global .menu li.current_page_item  > a, 
        .header-global .menu li.current-page-ancestor  > a {
        	color: <?php echo get_theme_mod('accent_color') ?>;
        }

        .nav-global .menu > li a {
        	color: <?php echo get_theme_mod('accent_color') ?>;
        }
	}

	/* Nav - Mobile */
	.nav-anchors li > a:hover {
		color: <?php echo get_theme_mod('accent_color') ?>;
	}

	/* Page Background */
	#container {
		background-color: <?php echo get_theme_mod('page_background_color') ?>;
	}

	/* Post Background */
	article.post,
	.sites-list li {
		background-color: <?php echo get_theme_mod('post_background_color') ?>;
	}

	/* Sidebar */
	.glocal-network .sidebar .widget,
	aside .widget {
		background-color: <?php echo get_theme_mod('accent_color') ?> !important;
	}

	.footer {
		background-color: <?php echo get_theme_mod('accent_color') ?>;
	}

	.footer-slug {
		background-color: <?php echo get_theme_mod('secondary_color') ?>;
	}

	button,
	.button,
	.nav-local #searchform #searchsubmit {
		background-color: <?php echo get_theme_mod('accent_color') ?>;
	}

	button:hover,
	.button:hover,
	.nav-local #searchform #searchsubmit:hover {
		background-color: <?php echo get_theme_mod('primary_color') ?>;
	}

	.home-modules .widget_em_widget ul li,
	.home-modules .sites-list.no-site-image li {
		background-color: <?php echo get_theme_mod('primary_color') ?>;
	}

	.home-modules .widget_em_widget ul li:hover,
	.home-modules .sites-list.no-site-image li:hover {
		background-color: <?php echo get_theme_mod('accent_color') ?>;
	}

	/* Text */
	p,
	li {
		color: <?php echo get_theme_mod('text_color') ?>;
	}

	/* Headings */
	h1,
	h2,
	h3,
	h4 {
		color: <?php echo get_theme_mod('heading_color') ?>;
	}


	/* Links */
	a,
	.css-events-list a,
	.footer .widget a,
	.nav-global .menu a:hover {
		color: <?php echo get_theme_mod('link_color') ?>;
	}

	a:hover,
	a:focus,
	.css-events-list a:hover,
	.css-events-list a:focus,
	.footer .widget a:hover,
	.footer .widget a:focus {
		color: <?php echo get_theme_mod('secondary_color') ?>;
	}

	</style>

<?php }

add_action( 'wp_head' , 'anp_dynamic_css' );



?>