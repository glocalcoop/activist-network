<?php

/**
 * The class that handles the admin screen
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
class rssPIAdmin {

	/**
	 * Whether the API key is valid
	 * 
	 * @var boolean
	 */
	var $is_key_valid;

	/**
	 * The options
	 * 
	 * @var array 
	 */
	var $options;

	/**
	 * Aprompt for invalid/absent API keys
	 * @var string
	 */
	var $key_prompt;

	/**
	 *  Start
	 * 
	 * @global object $rss_post_importer
	 */
	public function __construct() {

		$this->load_options();

		// add a key prompt
		$this->key_prompt = __('%1$sYou need a <a href="%2$s" target="_blank">Full Text RSS Key</a> to activate this section, please <a href="%2$s" target="_blank">get one and try it free</a> for the next 14 days to see how it goes.', 'rss_pi');

		// initialise logging
		$this->log = new rssPILog();
		$this->log->init();

		// load the form processor
		$this->processor = new rssPIAdminProcessor();
	}

	private function load_options() {
		global $rss_post_importer;

		// add options
		$this->options = $rss_post_importer->options;

		// check for valid key when we don't have it cached
		// actually this populates the settings with our defaults on the first plugin activation
		if ( !isset($this->options['settings']['is_key_valid']) ) {
			// check if key is valid
			$this->is_key_valid = $rss_post_importer->is_valid_key($this->options['settings']['feeds_api_key']);
			$this->options['settings']['is_key_valid'] = $this->is_key_valid;
			// if the key is not fine
			if (!empty($this->options['settings']['feeds_api_key']) && !$this->is_key_valid) {
				// unset from settings
				unset($this->options['settings']['feeds_api_key']);
			}
			// update options
			$new_options = array(
				'feeds' => $this->options['feeds'],
				'settings' => $this->options['settings'],
				'latest_import' => $this->options['latest_import'],
				'imports' => $this->options['imports'],
				'upgraded' => $this->options['upgraded']
			);
			// update in db
			update_option('rss_pi_feeds', $new_options);
		} else {
			$this->is_key_valid = $this->options['settings']['is_key_valid'];
		}
	}

	/**
	 * Initialise and hook all actions
	 */
	public function init() {

		// add to admin menu
		add_action('admin_menu', array($this, 'admin_menu'));

		// process and save options prior to screen ui display
		add_action('load-settings_page_rss_pi', array($this, 'save_options'));

		// load scripts and styles we need
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));

		// manage meta data on post deletion and restoring
		add_action('wp_trash_post', array($this, 'delete_post')); // trashing a post
		add_action('before_delete_post', array($this, 'delete_post')); // deleting a post permanently
		add_action('untrash_post', array($this, 'restore_post')); // restoring a post from trash

		// the ajax for adding new feeds (table rows)
		add_action('wp_ajax_rss_pi_add_row', array($this, 'add_row'));

		// the ajax for stats chart
		add_action('wp_ajax_rss_pi_stats', array($this, 'ajax_stats'));

		// the ajax for importing feeds via admin
		add_action('wp_ajax_rss_pi_import', array($this, 'ajax_import'));

		// disable the feed author dropdown for invalid/absent API keys
		add_filter('wp_dropdown_users', array($this, 'disable_user_dropdown'));

		// Add 10 minutes in frequency.
		add_filter('cron_schedules', array($this, 'rss_pi_cron_add'));
	}

	/**
	 * Add to admin menu
	 */
	function admin_menu() {

		add_options_page('Rss Post Importer', 'Rss Post Importer', 'manage_options', 'rss_pi', array($this, 'screen'));
	}

	/**
	 * Enqueue our admin css and js
	 * 
	 * @param string $hook The current screens hook
	 * @return null
	 */
	public function enqueue($hook) {

		// don't load if it isn't our screen
		if ($hook != 'settings_page_rss_pi') {
			return;
		}

		// register scripts & styles
		wp_enqueue_style('rss-pi', RSS_PI_URL . 'app/assets/css/style.css', array(), RSS_PI_VERSION);

		wp_enqueue_style('rss-pi-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/redmond/jquery-ui.css', array(), RSS_PI_VERSION);

		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-progressbar');

		wp_enqueue_script('modernizr', RSS_PI_URL . 'app/assets/js/modernizr.custom.32882.js', array(), RSS_PI_VERSION, true);
		wp_enqueue_script('phpjs-uniqid', RSS_PI_URL . 'app/assets/js/uniqid.js', array(), RSS_PI_VERSION, true);
		wp_enqueue_script('rss-pi', RSS_PI_URL . 'app/assets/js/main.js', array('jquery'), RSS_PI_VERSION, true);

		// localise ajaxuel for use
		$localise_args = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'pluginurl' => RSS_PI_URL,
			'l18n' => array(
				'unsaved' => __( 'You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?', 'rss_pi' )
			)
		);
		wp_localize_script('rss-pi', 'rss_pi', $localise_args);
	}

	// add post URL to rss_pi_deleted_posts when trashing
	function delete_post($post_id) {
		$rss_pi_deleted_posts = get_option( 'rss_pi_deleted_posts', array() );
		$source_md5 = get_post_meta($post_id, 'rss_pi_source_md5', true);
		if ( $source_md5 && ! in_array( $source_md5, $rss_pi_deleted_posts ) ) {
			// add this source URL hash to the "deleted" metadata
			$rss_pi_deleted_posts[] = $source_md5;
			update_option('rss_pi_deleted_posts', $rss_pi_deleted_posts);
		}
	}

	// remove post URL from rss_pi_deleted_posts when restoring from trash
	function restore_post($post_id) {
		$rss_pi_deleted_posts = get_option( 'rss_pi_deleted_posts', array() );
		$source_md5 = get_post_meta($post_id, 'rss_pi_source_md5', true);
		if ( $source_md5 && in_array( $source_md5, $rss_pi_deleted_posts ) ) {
			// remove this source URL hash from the "deleted" metadata
			$rss_pi_deleted_posts = array_diff( $rss_pi_deleted_posts, array( $source_md5 ) );
			update_option('rss_pi_deleted_posts', $rss_pi_deleted_posts);
		}
	}

	function rss_pi_cron_add($schedules) {

		$schedules['minutes_10'] = array(
			'interval' => 600,
			'display' => '10 minutes'
		);
		return $schedules;
	}

	/**
	 * save any options submitted before the screen/ui get displayed
	 */
	function save_options() {

		// load the form processor
		$this->processor->process();

		if ( $this->is_key_valid ) {
			// purge "deleted posts" cache when requested
			$this->processor->purge_deleted_posts_cache();
		}
	}

	/**
	 * Display the screen/ui
	 */
	function screen() {

		// display a success message
		if( isset($_GET['deleted_cache_purged']) || isset($_GET['settings-updated']) || isset($_GET['invalid_api_key']) || isset($_GET['import']) && $_GET['settings-updated'] ) {
?>
		<div id="message" class="updated">
<?php
			if( isset($_GET['deleted_cache_purged']) && $_GET['deleted_cache_purged'] == 'true' ) {
?>
			<p><strong><?php _e('Cache for Deleted posts was purged.') ?></strong></p>
<?php
			}
			if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
?>
			<p><strong><?php _e('Settings saved.') ?></strong></p>
<?php
			}
			if( isset($_GET['imported']) && $_GET['imported'] ) {
				$imported = intval($_GET['imported']);
?>
			<p><strong><?php printf( _n( '%s new post imported.', '%s new posts imported.', $imported, 'rss_pi' ), $imported ); ?></strong></p>
<?php
			}
?>
		</div>
<?php
			// import feeds via AJAX
			if( isset($_GET['import']) ) {
?>
<script type="text/javascript">
<?php
$ids = array();
if ( is_array($this->options['feeds']) ) :
	foreach ($this->options['feeds'] as $f) :
		$ids[] = $f['id'];
	endforeach;
endif;
?>
if (feeds !== undefined) {
	feeds.set(<?php echo json_encode($ids); ?>);
}  else {
	var feeds = <?php echo json_encode($ids); ?>;
}
</script>
<?php
			}
		}

		// display a error message
		if( isset($_GET['message']) && $_GET['message'] > 1 ) {
?>
		<div id="message" class="error">
<?php
			switch ( $_GET['message'] ) {
				case 2:
				{
?>
			<p><strong><?php _e('Invalid API key!', 'rss_api'); ?></strong></p>
<?php
				}
				break;
			}
?>
		</div>
<?php
		}

		// it'll process any submitted form data
		// reload the options just in case
		$this->load_options();

		global $rss_post_importer;

		// include the template for the ui
		include( RSS_PI_PATH . 'app/templates/admin-ui.php');
	}

	/**
	 * Display errors
	 * 
	 * @param string $error The error message
	 * @param boolean $inline Whether the error is inline or shown like regular wp errors
	 */
	function key_error($error, $inline = false) {

		$class = ($inline) ? 'rss-pi-error' : 'error';

		echo '<div class="' . $class . '"><p>' . $error . '</p></div>';
	}

	/**
	 * Add a new row for a new feed
	 */
	function add_row() {

		include( RSS_PI_PATH . 'app/templates/feed-table-row.php');
		die();
	}

	/**
	 * Generate stats data and return
	 */
	function ajax_stats() {

		include( RSS_PI_PATH . 'app/templates/stats.php');
		die();
	}

	/**
	 * Import any feeds
	 */
	function ajax_import() {
		global $rss_post_importer;

		// if there's nothing for processing or invalid data, bail
		if ( ! isset($_POST['feed']) ) {
			wp_send_json_error(array('message'=>'no feed provided'));
		}

		$_found = false;
		foreach ( $this->options['feeds'] as $id => $f ) {
			if ( $f['id'] == $_POST['feed'] ) {
				$_found = $id;
				break;
			}
		}
		if ( $_found === false ) {
			wp_send_json_error(array('message'=>'wrong feed id provided'));
		}

		// TODO: make this better
		if ( $_found == 0 ) {
			// check for valid key only for the first feed
			$this->is_key_valid = $rss_post_importer->is_valid_key($this->options['settings']['feeds_api_key']);
			$this->options['settings']['is_key_valid'] = $this->is_key_valid;
			// if the key is not fine
			if (!empty($this->options['settings']['feeds_api_key']) && !$this->is_key_valid) {
				// unset from settings
				unset($this->options['settings']['feeds_api_key']);
			}
			// update options
			$new_options = array(
				'feeds' => $this->options['feeds'],
				'settings' => $this->options['settings'],
				'latest_import' => $this->options['latest_import'],
				'imports' => $this->options['imports'],
				'upgraded' => $this->options['upgraded']
			);
			// update in db
			update_option('rss_pi_feeds', $new_options);
		}

		$post_count = 0;

		$f = $this->options['feeds'][$_found];

		$engine = new rssPIEngine();

		// filter cache lifetime
		add_filter('wp_feed_cache_transient_lifetime', array($engine, 'frequency'));

		// prepare, import feed and count imported posts
		if ( $items = $engine->do_import($f) ) {
			$post_count += count($items);
		}

		remove_filter('wp_feed_cache_transient_lifetime', array($engine, 'frequency'));

		// reformulate import count
		$imports = intval($this->options['imports']) + $post_count;

		// update options
		$new_options = array(
			'feeds' => $this->options['feeds'],
			'settings' => $this->options['settings'],
			'latest_import' => date("Y-m-d H:i:s"),
			'imports' => $imports,
			'upgraded' => $this->options['upgraded']
		);
		// update in db
		update_option('rss_pi_feeds', $new_options);

		global $rss_post_importer;
		// reload options
		$rss_post_importer->load_options();

		// log this
		rssPILog::log($post_count);

		wp_send_json_success(array('count'=>$post_count, 'url'=>$f['url']));

	}

	/**
	 * Disable the user dropdwon for each feed
	 * 
	 * @param string $output The html of the select dropdown
	 * @return string
	 */
	function disable_user_dropdown($output) {

		// if we have a valid key we don't need to disable anything
		if ($this->is_key_valid) {
			return $output;
		}

		// check if this is the feed dropdown (and not any other)
		preg_match('/rss-pi-specific-feed-author/i', $output, $matched);

		// this is not our dropdown, no need to disable
		if (empty($matched)) {
			return $output;
		}

		// otherwise just disable the dropdown
		return str_replace('<select ', '<select disabled="disabled" ', $output);
	}

	/**
	 * Walker class function for category multiple checkbox
	 * 
	 * 
	 * 
	 */
	function wp_category_checklist_rss_pi($post_id = 0, $descendants_and_self = 0, $selected_cats = false, $popular_cats = false, $walker = null, $checked_ontop = true) {

		$cat = "";
		if (empty($walker) || !is_a($walker, 'Walker'))
			$walker = new Walker_Category_Checklist;
		$descendants_and_self = (int) $descendants_and_self;
		$args = array();
		if (is_array($selected_cats))
			$args['selected_cats'] = $selected_cats;
		elseif ($post_id)
			$args['selected_cats'] = wp_get_post_categories($post_id);
		else
			$args['selected_cats'] = array();

		if ($descendants_and_self) {
			$categories = get_categories("child_of=$descendants_and_self&hierarchical=0&hide_empty=0");
			$self = get_category($descendants_and_self);
			array_unshift($categories, $self);
		} else {
			$categories = get_categories('get=all');
		}
		if ($checked_ontop) {
			// Post process $categories rather than adding an exclude to the get_terms() query to keep the query the same across all posts (for any query cache)
			$checked_categories = array();
			$keys = array_keys($categories);
			foreach ($keys as $k) {
				if (in_array($categories[$k]->term_id, $args['selected_cats'])) {
					$checked_categories[] = $categories[$k];
					unset($categories[$k]);
				}
			}
			// Put checked cats on top
			$cat = $cat . call_user_func_array(array(
						&$walker,
						'walk'
							), array(
						$checked_categories,
						0,
						$args
			));
		}
		// Then the rest of them
		$cat = $cat . call_user_func_array(array(
					&$walker,
					'walk'
						), array(
					$categories,
					0,
					$args
		));
		return $cat;
	}

	function rss_pi_tags_dropdown($fid, $seleced_tags) {

		if ($tags = get_tags(array('orderby' => 'name', 'hide_empty' => false))) {

			echo '<select name="' . $fid . '-tags_id[]" id="tag" class="postform">';

			foreach ($tags as $tag) {
				$strsel = "";
				if (!empty($seleced_tags)) {

					if ($seleced_tags[0] == $tag->term_id) {
						$strsel = "selected='selected'";
					}
				}
				echo '<option value="' . $tag->term_id . '" ' . $strsel . '>' . $tag->name . '</option>';
			}
			echo '</select> ';
		}
	}

	function rss_pi_tags_checkboxes($fid, $seleced_tags) {

		$tags = get_tags(array('hide_empty' => false));
		if ($tags) {
			$checkboxes = "<ul>";

			foreach ($tags as $tag) :
				$strsel = "";
				if (in_array($tag->term_id, $seleced_tags))
					$strsel = "checked='checked'";

				$checkboxes .=
						'<li><label for="tag-' . $tag->term_id . '">
								<input type="checkbox" name="' . $fid . '-tags_id[]" value="' . $tag->term_id . '" id="tag-' . $tag->term_id . '" ' . $strsel . ' />' . $tag->name . '
							</label></li>';
			endforeach;
			$checkboxes .= "</ul>";
			print $checkboxes;
		}
	}

}
