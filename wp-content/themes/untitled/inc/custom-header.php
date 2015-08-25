<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @uses untitled_header_style()
 * @uses untitled_admin_header_style()
 * @uses untitled_admin_header_image()
 *
 * @package untitled
 */
function untitled_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'untitled_custom_header_args', array(
		'header-text'            => false,
		'width'                  => 290,
		'height'                 => 30,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'untitled_header_style',
		'admin-head-callback'    => 'untitled_admin_header_style',
		'admin-preview-callback' => 'untitled_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'untitled_custom_header_setup' );

if ( ! function_exists( 'untitled_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see untitled_custom_header_setup().
 *
 * @since untitled 1.0
 */
function untitled_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		.site-title,
		.site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // untitled_header_style

if ( ! function_exists( 'untitled_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see untitled_custom_header_setup().
 *
 * @since untitled 1.0
 */
function untitled_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background: #000;
		border: none;
		font-family: 'arvo', sans-serif;
		padding: 0 0 0 30px;
		text-align: left;
		max-width: 290px;
		max-height: 30px;
	}
	#headimg h1 {
		color: #fff;
		font-size: 1.5em;
		font-weight: 600;
		line-height: 1.7em;
		margin: 0;
		padding: 0;
		font-family: 'arvo', georgia;
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	#headimg h1 a {
		color: #fff;
		text-decoration: none;
	}
	#headimg img {
		width: auto;
		max-height: 30px;
	}
	</style>
<?php
}
endif; // untitled_admin_header_style

if ( ! function_exists( 'untitled_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see untitled_custom_header_setup().
 *
 * @since untitled 1.0
 */
function untitled_admin_header_image() {
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="" />
		<?php else : ?>
		<h1 class="displaying-header-text"><a id="name" onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<?php endif; ?>
	</div>
<?php
}
endif; // untitled_admin_header_image
