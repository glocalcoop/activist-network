<?php
/**
 *
 * @package Fictive
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses fictive_header_style()
 * @uses fictive_admin_header_style()
 * @uses fictive_admin_header_image()
 */
function fictive_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'fictive_custom_header_args', array(
		'default-image'          => '%s/images/header.jpg',
		'default-text-color'     => 'bd5532',
		'width'                  => 1112,
		'height'                 => 1000,
		'flex-height'            => true,
		'wp-head-callback'       => 'fictive_header_style',
		'admin-head-callback'    => 'fictive_admin_header_style',
		'admin-preview-callback' => 'fictive_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'fictive_custom_header_setup' );

if ( ! function_exists( 'fictive_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see fictive_custom_header_setup().
 */
function fictive_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // fictive_header_style

if ( ! function_exists( 'fictive_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see fictive_custom_header_setup().
 */
function fictive_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background-color: white;
			border: none;
			border-left: 1px solid #e4e1dc;
			border-right: 1px solid #e4e1dc;
			border-bottom: 1px solid #e4e1dc;
			box-sizing: border-box;
			font-family: "Open Sans", Helvetica, Arial, sans-serif;
			font-weight: 300;
			margin: 0 0 7px;
			overflow: visible;
			padding: 24px;
			position: relative;
			text-align: center;
			width: 276px;
		}
		#headimg:after {
			border-top: 8px solid #bd5532;
			content: '';
			position: absolute;
			top: -8px;
			left: -1px;
			width: -webkit-calc(100% + 2px);
			width: calc(100% + 2px);
		}
		#headimg h1,
		#desc {
		}
		#headimg h1 {
			font-size: 26px;
			font-weight: 300;
			line-height: 1.29231em;
			margin: 6px 0 0;
			text-align: center;
		}
		#headimg h1 a {
			text-decoration: none;
		}
		#desc {
			color: #ada393;
			font-size: 13px;
			text-align: center;
		}
		.header-image {
			display: block;
			max-width: 276px;
			position: relative;
				top: -7px;
			height: auto;
		}
	</style>
<?php
}
endif; // fictive_admin_header_style

if ( ! function_exists( 'fictive_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see fictive_custom_header_setup().
 */
function fictive_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<?php if ( get_header_image() ) : ?>
	<img src="<?php header_image(); ?>" alt="" class="header-image">
	<?php endif; ?>
	<div id="headimg">
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"><?php bloginfo( 'description' ); ?></div>
	</div>
<?php
}
endif; // fictive_admin_header_image
