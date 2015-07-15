<?php

/**
 * Handles cron jobs
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
class rssPICron {

	/**
	 * Initialise
	 */
	public function init() {

		// hook up scheduled events
		add_action('wp', array(&$this, 'schedule'));

		add_action('rss_pi_cron', array(&$this, 'do_hourly'));
	}

	/**
	 * Check and confirm scheduling
	 */
	function schedule() {

		if (!wp_next_scheduled('rss_pi_cron')) {

			wp_schedule_event(time(), 'hourly', 'rss_pi_cron');
		}
	}

	/**
	 * Import the feeds on schedule
	 */
	function do_hourly() {

		$engine = new rssPIEngine();
		$engine->import_feed();
	}

}
