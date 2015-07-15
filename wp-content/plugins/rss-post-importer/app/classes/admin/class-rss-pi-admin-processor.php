<?php

/**
 * Processes the admin screen form submissions
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
class rssPIAdminProcessor {

	/**
	 * If we have a valid api key
	 * 
	 * @var boolean
	 */
	var $is_key_valid;

	/**
	 * Process the form result
	 * 
	 * @global object $rss_post_importer
	 * @return null
	 */
	function process() {

		// if there's nothing for processing or invalid data, bail
		if (!isset($_POST['info_update']) || !wp_verify_nonce($_POST['rss_pi_nonce'], 'settings_page')) {
			return;
		}

		// Get ids of feed-rows
		$ids = explode(",", $_POST['ids']);

		// formulate the settings array
		$settings = $this->process_settings();

		// check result for "invalid_key" flag
		$invalid_api_key = isset($settings['invalid_api_key']);
		unset($settings['invalid_api_key']);

		// update cron settings
		$this->update_cron($settings['frequency']);

		// formulate the feeds array
		$feeds = $this->process_feeds($ids);

		// import settings
		if ( isset($_FILES['import_csv']) && $settings['is_key_valid'] ) {
			$feeds = $this->import_csv($feeds);
		}

		// save and reload the options
		$this->save_reload_options($settings, $feeds);

		global $rss_post_importer;

		wp_redirect(add_query_arg(
			array(
				'settings-updated' => 'true',
				// yield the routine for import feeds via AJAX when needed
				'import' => ( $_POST['save_to_db'] == 'true' ),
				'message' => $invalid_api_key ? 2 : 1
			),
			$rss_post_importer->page_link
		));
		exit;
	}

	/**
	 * Purge "deleted_posts" cache from wp_options
	 * @return void
	 */
	function purge_deleted_posts_cache() {

		if (isset($_POST['purge_deleted_cache'])) {

			delete_option('rss_pi_deleted_posts');
			delete_option('rss_pi_imported_posts');

			global $rss_post_importer;

			wp_redirect(add_query_arg(
				array(
					'deleted_cache_purged' => 'true',
				),
				$rss_post_importer->page_link
			));
			exit;

		}

	}

	/**
	 * Import CSV function to import CSV file data into database

	 * @return array
	 */
	private function import_csv($feeds) {

		if (is_uploaded_file($_FILES['import_csv']['tmp_name'])) {
			$file = $_FILES['import_csv']['tmp_name'];
			$fcount = file($file);
			$linescount = count($fcount) - 1;
			$file_handle = fopen($file, "r");
			$t = 0;
			$titlearray = array();
			while ($csv_line = fgetcsv($file_handle, 1024)) {

				if ($t <> 0) {

					for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
						if ($i == 0)
							$importdata['feeds'][$t - 1]['id'] = uniqid("54d4c" . $t);

						$importdata['feeds'][$t - 1][$titlearray[$i]] = $csv_line[$i];
					}
				}
				else {
					for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
						$titlearray[] = $csv_line[$i];
					}
				}
				$t++;
			}
			fclose($file_handle) or die("can't close file");

			if (!empty($importdata['feeds'])) {
				for ($r = 0; $r < count($importdata['feeds']); $r++) {
					if ( isset($importdata['feeds'][$r]['category_id']) ) {
						$importdata['feeds'][$r]['category_id'] = explode(',',$importdata['feeds'][$r]['category_id']);
						$importdata['feeds'][$r]['tags_id'] = explode(',',$importdata['feeds'][$r]['tags_id']);
						$importdata['feeds'][$r]['keywords'] = explode(',',$importdata['feeds'][$r]['keywords']);
						$importdata['feeds'][$r]['strip_html'] = $importdata['feeds'][$r]['strip_html']; // this is a STRING, not a BOOLEAN
					} else {
						$importdata['feeds'][$r]['category_id'] = array(1);
						$importdata['feeds'][$r]['tags_id'] = "";
						$importdata['feeds'][$r]['keywords'] = "";
						$importdata['feeds'][$r]['strip_html'] = "false";
					}

					$check_result = $this->check_feed_exist($feeds, $importdata['feeds'][$r]);

					if ($check_result) {
						unset($importdata['feeds'][$r]);
					} else {
						array_push($feeds, $importdata['feeds'][$r]);
					}
				}
			}
		}

		return $feeds;
	}

	function check_feed_exist($feeds, $csvlink) {

		if (!empty($feeds) && !empty($csvlink)) {

			for ($g = 0; $g < count($feeds); $g++) {

				if ($feeds[$g]['url'] == $csvlink['url']) {

					return true;
				}
			}
			return false;
		}
	}

	/**
	 * Process submitted data to formulate settings array
	 * 
	 * @global object $rss_post_importer
	 * @return array
	 */
	private function process_settings() {

		// Get selected settings for all imported posts
		$settings = array(
			'frequency' => $_POST['frequency'],
			'feeds_api_key' => $_POST['feeds_api_key'],
			'post_template' => stripslashes_deep($_POST['post_template']),
			'post_status' => $_POST['post_status'],
			'author_id' => $_POST['author_id'],
			'allow_comments' => $_POST['allow_comments'],
			'block_indexing' => $_POST['block_indexing'],
			'nofollow_outbound' => $_POST['nofollow_outbound'],
			'enable_logging' => $_POST['enable_logging'],
			'import_images_locally' => $_POST['import_images_locally'],
			'disable_thumbnail' => $_POST['disable_thumbnail'],
			// these values are setup after key_validity check via filter()
			'keywords' => array(),
			'cache_deleted' => 'true',
		);

		global $rss_post_importer;

		// check if submitted api key is valid
		$this->is_key_valid = $rss_post_importer->is_valid_key($settings['feeds_api_key']);
		// save key validity state
		$settings['is_key_valid'] = $this->is_key_valid;

		// filter the settings and then send them back for saving
		return $this->filter($settings);
	}

	/**
	 * Update the frequency of the import cron job
	 * 
	 * @param string $frequency
	 */
	private function update_cron($frequency) {

		// If cron settings have changed
		if (wp_get_schedule('rss_pi_cron') != $frequency) {

			// Reset cron
			wp_clear_scheduled_hook('rss_pi_cron');
			wp_schedule_event(time(), $frequency, 'rss_pi_cron');
		}
	}

	/**
	 * Forms the feeds array from submitted data
	 * 
	 * @param array $ids feeds ids
	 * @return array
	 */
	private function process_feeds($ids) {

		$feeds = array();

		foreach ($ids as $id) {
			if ($id) {
				$keywords = array();
				// if the key is valid
				if ($this->is_key_valid) {
					// set up keywords (otherwise don't)
					if (isset($_POST[$id . '-keywords'])) {
						$keyword_str = $_POST[$id . '-keywords'];
					}
					if (!empty($keyword_str)) {
						$keywords = explode(',', $keyword_str);
					}
				}
				array_push($feeds, array(
					'id' => $id,
					'url' => $_POST[$id . '-url'],
					'name' => $_POST[$id . '-name'],
					'max_posts' => $_POST[$id . '-max_posts'],
					// different author ids depending on valid API keys
					'author_id' => ($this->is_key_valid && isset($_POST[$id . '-author_id'])) ? $_POST[$id . '-author_id'] : $_POST['author_id'],
					'category_id' => (isset($_POST[$id . '-category_id'])) ? $_POST[$id . '-category_id'] : '',
					'tags_id' => (isset($_POST[$id . '-tags_id'])) ? $_POST[$id . '-tags_id'] : '',
					'keywords' => array_map('trim',$keywords),
					'strip_html' => $_POST[$id . '-strip_html']
				));
			}
		}

		return $feeds;
	}

	/**
	 * Update options and reload global options
	 * 
	 * @global type $rss_post_importer
	 * @param array $settings
	 * @param array $feeds
	 */
	private function save_reload_options($settings, $feeds) {

		global $rss_post_importer;

		// existing options
		$options = $rss_post_importer->options;

		// new data
		$new_options = array(
			'feeds' => $feeds,
			'settings' => $settings,
			'latest_import' => $options['latest_import'],
			'imports' => $options['imports'],
			'upgraded' => $options['upgraded']
		);

		// update in db
		update_option('rss_pi_feeds', $new_options);

		// reload so that the new options are used henceforth
		$rss_post_importer->load_options();
	}

	/**
	 * Filter settings for API key vs non-API key installs
	 * 
	 * @param array $settings
	 * @return array
	 */
	private function filter($settings) {

		// if the key is not fine
		if (!empty($settings['feeds_api_key']) && !$this->is_key_valid) {

			// unset from settings
			unset($settings['feeds_api_key']);
			$settings['invalid_api_key'] = true;
		}

		// if the key is valid
		if ($this->is_key_valid) {

			// set up keywords (otherwise don't)
			if (isset($_POST['keyword_filter']))
				$keyword_str = $_POST['keyword_filter'];

			$keywords = array();

			if (!empty($keyword_str)) {
				$keywords = explode(',', $keyword_str);
			}
			$settings['keywords'] = array_map('trim',$keywords);

			// set up "import deleted posts" (otherwise don't)
			$settings['cache_deleted'] = isset($_POST['cache_deleted']) ? $_POST['cache_deleted'] : 'true';
			
		}

		return $settings;
	}

}
