<?php
// **********************************************************
// If you want to have an own template for this action
// just copy this file into your current theme folder
// and change the markup as you want to
// **********************************************************
?>
<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main class="first" role="main">
            

<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<h3><?php _e( 'Error', 'user-frontend-td' ); ?></h3>
			<p><?php echo apply_filters( 'uf_error_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' ); ?></p>
		</div>
	</div>
</div>

            </main>
        <?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
