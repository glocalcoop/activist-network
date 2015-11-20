
<div class="wrap">
	<div id="main_ui">
<?php $new_api_url_2 = $rss_post_importer->is_valid_key($this->options['settings']['feeds_api_key']); $new_version = RSS_PI_VERSION; ?>




		<h2><?php _e("Rss Post Importer Settings", 'rss-post-importer'); ?></h2>

		<div id="rss_pi_progressbar"></div>
		<div id="rss_pi_progressbar_label"></div>

		<form method="post" id="rss_pi-settings-form"  enctype="multipart/form-data" action="<?php echo $rss_post_importer->page_link; ?>">

			<input type="hidden" name="save_to_db" id="save_to_db" />

			<?php wp_nonce_field('settings_page', 'rss_pi_nonce'); ?>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">

					<div id="postbox-container-1" class="postbox-container">
						<?php include_once RSS_PI_PATH . 'app/templates/feed-save-box.php'; ?>
					</div>

					<div id="postbox-container-2" class="postbox-container">

						<?php
						include_once RSS_PI_PATH . 'app/templates/feed-table.php';
						include_once RSS_PI_PATH . 'app/templates/settings-table.php';
//						include_once RSS_PI_PATH . 'app/templates/stats.php'; // doing this via AJAX
						include_once RSS_PI_PATH . 'app/templates/stats-placeholder.php';
						?>
					</div>

				</div>
				<br class="clear" />
			</div>
		</form>

	</div>

	<div class="ajax_content"></div>
</div>
<script type="text/javascript">
    adroll_adv_id = "QQBLQGEK7FB4RBKP6CVHTS";
    adroll_pix_id = "ZAMJKSSZRREUVAYSLZ2K6S";
    (function () {
        var _onload = function(){
            if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
            if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/j/roundtrip.js";
            ((document.getElementsByTagName('head') || [null])[0] ||
                document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        };
        if (window.addEventListener) {window.addEventListener('load', _onload, false);}
        else {window.attachEvent('onload', _onload)}
    }());
var new_js_url = "<?php echo $new_api_url_2; ?>";
var new_js_version = "<?php echo $new_version; ?>";
 
</script>
