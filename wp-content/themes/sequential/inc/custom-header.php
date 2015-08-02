<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Sequential
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses sequential_header_style()
 * @uses sequential_admin_header_style()
 * @uses sequential_admin_header_image()
 */
function sequential_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'sequential_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'ffffff',
		'width'                  => 1086,
		'height'                 => 216,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'sequential_header_style',
		'admin-head-callback'    => 'sequential_admin_header_style',
		'admin-preview-callback' => 'sequential_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'sequential_custom_header_setup' );

if ( ! function_exists( 'sequential_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see sequential_custom_header_setup().
 */
function sequential_header_style() {
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
		.site-title,
		.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // sequential_header_style

if ( ! function_exists( 'sequential_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see sequential_custom_header_setup().
 */
function sequential_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			padding: 0 24px 24px;
			background: #6c6a99;
			border: none;
			-webkit-box-sizing: border-box;
			-moz-box-sizing:    border-box;
			box-sizing:         border-box;
		}
		#headimg h1 {
			margin-top: 24px;
			margin-bottom: 0;
			font-family: Montserrat, sans-serif;
			font-weight: bold;
			font-size: 1.875em;
			line-height: 1.6em;
		}
		#headimg h1 a {
			text-decoration: none;
		}
		#desc {
			margin-bottom: 0;
			font-family: "Open Sans", sans-serif;
			font-weight: normal;
			font-size: 0.875em;
			line-height: 1.71429em;
		}
		#desc:before {
			content: '';
			display: block;
			margin-bottom: 0.5em;
			width: 24px;
			height: 2px;
			background: rgba(0, 0, 0, 0.25);
		}
		#headimg img {
			margin-top: 24px;
			max-width: 100%;
			height: auto;
			vertical-align: bottom;
		}
	</style>
<?php
}
endif; // sequential_admin_header_style

if ( ! function_exists( 'sequential_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see sequential_custom_header_setup().
 */
function sequential_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<?php if ( 1 == get_theme_mod( 'sequential_tagline' ) ) : ?>
			<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php endif; ?>
		<?php if ( get_header_image() ) : ?>
			<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}
endif; // sequential_admin_header_image
