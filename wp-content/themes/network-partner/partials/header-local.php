<header class="header-local" role="banner">

	<div class="wrap">

		<div class="partner-logo">
			<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
		</div>

		<div class="partner-banner">
			<h5 class="site-prefix">News from</h5>
			<h1 class="site-name"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></h1>
			<?php // bloginfo('description'); ?>
		</div>

	</div>

</header>