<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

	<?php $header_image = get_header_image();
	if ( ! empty( $header_image ) ) { ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		</a>
	<?php } // if ( ! empty( $header_image ) ) ?>

 *
 * @package Superhero
 * @since Superhero 1.0
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses superhero_header_style()
 * @uses superhero_admin_header_style()
 * @uses superhero_admin_header_image()
 *
 * @package Superhero
 */
function superhero_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'superhero_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'fff',
		'width'                  => 480,
		'height'                 => 150,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'superhero_header_style',
		'admin-head-callback'    => 'superhero_admin_header_style',
		'admin-preview-callback' => 'superhero_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'superhero_custom_header_setup' );

if ( ! function_exists( 'superhero_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see superhero_custom_header_setup().
 *
 * @since Superhero 1.0
 */
function superhero_header_style() {

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
			position: absolute;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // superhero_header_style

if ( ! function_exists( 'superhero_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see superhero_custom_header_setup().
 *
 * @since Superhero 1.0
 */
function superhero_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: #262626;
			border: none;
			font-family: 'Carrois Gothic', sans-serif;
			padding: 25px;
			text-align: left;
			max-width: 480px;
			max-height: 150px;
		}
		#headimg h1,
		#description {
		}
		#headimg h1 {
			color: #fff;
			font-size: 30px;
			font-weight: bold;
			line-height: 36px;
			margin: 0;
			padding: 0;
		}
		#headimg h1 a {
			color: #fff;
			text-decoration: none;
		}
		#description {
			color: #818181;
			font-size: 14px;
		}
		#headimg img {
			width: auto;
			max-height: 150px;
		}
	</style>
<?php
}
endif; // superhero_admin_header_style

if ( ! function_exists( 'superhero_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see superhero_custom_header_setup().
 *
 * @since superhero 1.0
 */
function superhero_admin_header_image() {
	$style        = sprintf( ' style="color:#%s;"', get_header_textcolor() );
	$header_image = get_header_image();
?>
	<div id="headimg">
		<?php if ( ! empty( $header_image ) ) : ?>
		<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="description"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php
}
endif; // superhero_admin_header_image