<?php 
if(function_exists('glocal_customization_settings')) {
	$glocal_home_settings = glocal_customization_settings();
	$sitenumber = $glocal_home_settings['sites']['number_sites'];
} 
?>

<article id="sites-module" class="module row sites">
	<h2 class="module-heading">
	<?php if(!empty($glocal_home_settings['sites']['sites_heading_link'])) { ?>
		<a href="<?php echo $glocal_home_settings['sites']['sites_heading_link']; ?>">
			<?php echo $glocal_home_settings['sites']['sites_heading']; ?>
		</a>
	<?php } else { ?>
		<?php echo $glocal_home_settings['sites']['sites_heading']; ?>
	<?php } ?>	
	</h2>

	<ul class="sites-list">

	<?php 
	// TODO: Add exclude site customization	
	$sites = wp_get_sites('offset=1&archived=0&deleted=0'); // Set up variable that holds array of sites ?>	

	<?php
	$counter = 0; 

	foreach ($sites as $site) {
		$site_id = $site['blog_id'];
		$site_details = get_blog_details($site_id);

		if(function_exists('glocal_get_site_image')) {
			$header = glocal_get_site_image($site_id);
		} 
		?>
	
		<li id="site-<?php echo $site_id; ?>">
		    <a href="<?php echo $site_details->path; ?>" title="<?php echo $site_details->blogname; ?>" class="item-image <?php if(!$header) { echo 'no-image'; } ?>" style="background-image: url('<?php if($header) { echo $header; } ?>');"></a>
			<h3 class="item-title"><a href="<?php echo $site_details->path; ?>" title="<?php echo $site_details->blogname; ?>"><?php echo $site_details->blogname; ?></a></h3>
			<h6 class="meta item-modified"><span class="modified-title">Last updated</span> <time><?php echo date_i18n(get_option('date_format') ,strtotime($site_details->last_updated));?></time></h6>
		</li>
	
	<?php
		if(!empty($sitenumber)) {
			if (++$counter == $sitenumber) break;
		}
        
    } ?>

		<li class="directory-promo" id="promo-directory">
			<a href="/directory" title="Directory">
				<!-- TODO: Add theme customization to change text and link of sites promo -->
				<h3 class="post-title">Join the Network</h3>
				<div class="promo-icons"><i class="icon"></i><i class="icon"></i><i class="icon"></i><i class="icon"></i></div>
			</a>
		</li>

	</ul>
	
</article>