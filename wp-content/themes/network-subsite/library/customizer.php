<?php


function anp_child_dynamic_css() {
	?>
	<style type='text/css'>

	.nav-local {
		background-color: <?php echo get_theme_mod('primary_color') ?>;
	}

	.nav-local .menu li.current-menu-item a,
	.nav-local .menu li a:hover,
	.nav-local .menu li a:focus {
		background-color: <?php echo get_theme_mod('accent_color') ?>;
	}

	@media (max-width: 768px) {
		.nav-local .menu li a {
			color: <?php echo get_theme_mod('accent_color') ?>;
		}
	}

	</style>
	<?php
}

add_action( 'wp_head' , 'anp_child_dynamic_css' );



?>