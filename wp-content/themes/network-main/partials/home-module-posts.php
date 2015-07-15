<?php 
if(function_exists('glocal_customization_settings')) {
	$glocal_home_settings = glocal_customization_settings();
	//Check the setting exists
	if(!empty($glocal_home_settings['posts']['featured_category'])) {
		if(is_array($glocal_home_settings['posts']['featured_category'])) {
			$postcategory = implode(",", $glocal_home_settings['posts']['featured_category']);
		} else {
			$postcategory = $glocal_home_settings['posts']['featured_category'];
		}
	}
	$postnumber = $glocal_home_settings['posts']['number_posts'];
} 
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.news-list').bxSlider({
		slideWidth: 5000,
		minSlides: 2,
		maxSlides: 2,
		slideMargin: 10,
		pager: false
	});
	var responsive_viewport = jQuery(window).width();
	if (responsive_viewport < 320) {
		jQuery('.news-list').reloadSlider({
		slideWidth: 5000,
		minSlides: 1,
		maxSlides: 1,
		slideMargin: 10,
		pager: false
		});
	} 
});
</script>

<article id="news-module" class="module row news clearfix">
	<h2 class="module-heading">
	<?php if(!empty($glocal_home_settings['posts']['posts_heading_link'])) { ?>
		<a href="<?php echo $glocal_home_settings['posts']['posts_heading_link']; ?>">
			<?php echo $glocal_home_settings['posts']['posts_heading']; ?>
		</a>
	<?php } else { ?>
		<?php echo $glocal_home_settings['posts']['posts_heading']; ?>
	<?php } ?>	
	</h2>

	<?php
	if(function_exists( 'network_latest_posts' )) {

		$parameters = array(
		// 'title'         => '',
		'title_only'    => 'false',
		// TODO: Add exclude site customization	
		'ignore_blog' => '1',
		'display_type'     => 'ulist',
		'auto_excerpt'  => 'true',
		'full_meta'		=> 'true',
		'sort_by_date'	=> 'true',
		'use_pub_date'	=> 'true',
		'excerpt_length'   => '20',
		// BUG: Not respecting number of updates specified
		'number_posts'     => 2,
		'wrapper_list_css' => 'news-list',
		'wrapper_block_css'=> 'module row news', //The wrapper classe
		'instance'         => 'news-module', //The wrapper ID
		);

		// If a category was selected, limit to that category
		if(isset($postcategory)) {
			$parameters['category'] = $postcategory;
		}

		// If number of posts is specified, limit to that number of posts
		if($postnumber) {
			$parameters['number_posts'] = $postnumber;
		}
		// Execute
		$recent_posts = network_latest_posts($parameters);
	} else {

		get_template_part( 'partials/error', 'plugin' );

	}
	?>
</article>




