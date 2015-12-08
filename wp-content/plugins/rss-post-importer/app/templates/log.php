<div class="wrap">
	<h2><?php _e("Rss Post Importer Log", 'rss-post-importer'); ?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-2" class="postbox-container">
				<p class="large">
					<?php printf(__("If your imports are not running regularly according to your settings you might need to set up a scheduled task, there are several ways to do this, most convenient is to set up a scheduled task on your server and simply ask it to hit your sites url (%s) regularly, there are also external sites that offer the same service, such as:", "rss-post-importer"), get_site_url()); ?>
				<ul>
					<li><a href="http://www.mywebcron.com" target="_blank">www.mywebcron.com</a></li>
					<li><a href="http://www.onlinecronjobs.com" target="_blank">www.onlinecronjobs.com</a></li>
					<li><a href="http://www.easycron.com" target="_blank">www.easycron.com</a></li>
					<li><a href="http://cronless.com" target="_blank">cronless.com</a></li>
				</ul>
				</p>
				<a href="#" class="button button-large button-primary show-main-ui"><?php _e("Ok, all done", "rss-post-importer"); ?></a> 
				<a href="#" class="button button-large button-warning clear-log"><?php _e("Clear log", "rss-post-importer"); ?></a> 
				<div class="log">
					<code><?php echo(wpautop($log, true)); ?></code>
				</div>
			</div>
		</div>
	</div>
</div>