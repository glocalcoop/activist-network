<div class="wrap">
	<div id="main_ui">

		<h2><?php _e("Rss Post Importer Settings", 'rss_pi'); ?></h2>

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