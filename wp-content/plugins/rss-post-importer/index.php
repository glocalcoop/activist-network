<?php

/*
  Plugin Name: Rss Post Importer
  Plugin URI: https://wordpress.org/plugins/rss-post-importer/
  Description: This plugin lets you set up an import posts from one or several rss-feeds and save them as posts on your site, simple and flexible.
  Author: feedsapi
  Version: 2.1.2
  Author URI: https://www.feedsapi.org/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: rss_pi
  Domain Path: /lang/
 */

// define some constants
if (!defined('RSS_PI_PATH')) {
	define('RSS_PI_PATH', trailingslashit(plugin_dir_path(__FILE__)));
}

if (!defined('RSS_PI_URL')) {
	define('RSS_PI_URL', trailingslashit(plugin_dir_url(__FILE__)));
}

if (!defined('RSS_PI_BASENAME')) {
	define('RSS_PI_BASENAME', plugin_basename(__FILE__));
}

if (!defined('RSS_PI_VERSION')) {
	define('RSS_PI_VERSION', '2.1.2');
}

if (!defined('RSS_PI_LOG_PATH')) {
	define('RSS_PI_LOG_PATH', trailingslashit(WP_CONTENT_DIR) . 'rsspi-log/');
}

if (!is_dir(RSS_PI_LOG_PATH)) {
	mkdir(RSS_PI_LOG_PATH);
}

// helper classes
include_once RSS_PI_PATH . 'app/classes/helpers/class-rss-pi-log.php';
include_once RSS_PI_PATH . 'app/classes/helpers/class-rss-pi-featured-image.php';
include_once RSS_PI_PATH . 'app/classes/helpers/class-rss-pi-parser.php';
include_once RSS_PI_PATH . 'app/classes/helpers/rss-pi-functions.php';

// admin classes
include_once RSS_PI_PATH . 'app/classes/admin/class-rss-pi-admin-processor.php';
include_once RSS_PI_PATH . 'app/classes/admin/class-rss-pi-admin.php';
include_once RSS_PI_PATH . 'app/classes/admin/class-rss-pi-export-to-csv.php';
include_once RSS_PI_PATH . 'app/classes/admin/class-rss-pi-stats.php';

// Front classes
include_once RSS_PI_PATH . 'app/classes/front/class-rss-pi-front.php';

// main importers
include_once RSS_PI_PATH . 'app/classes/import/class-rss-pi-engine.php';
include_once RSS_PI_PATH . 'app/classes/import/class-rss-pi-cron.php';

// the main loader class
include_once RSS_PI_PATH . 'app/class-rss-post-importer.php';

// initialise plugin as a global var
global $rss_post_importer;

$rss_post_importer = new rssPostImporter();

$rss_post_importer->init();

