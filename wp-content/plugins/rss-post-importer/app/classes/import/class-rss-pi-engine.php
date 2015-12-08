<?php

/**
 * Main import engine
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
class rssPIEngine {

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
	var $options = array();

	/**
	 * Start the engine
	 * 
	 * @global type $rss_post_importer
	 */
	public function __construct() {

		global $rss_post_importer;

		$this->load_options();
	}

	/**
	 * Start the engine
	 * 
	 * @global type $rss_post_importer
	 */
	public function load_options() {

		global $rss_post_importer;
		$this->options = $rss_post_importer->options;
	}

	/**
	 * Import feeds
	 * 
	 * @return int
	 */
	public function import_feed() {
	global $rss_post_importer;
	$this->load_options();
	$post_count = 0;
   // filter cache lifetime
		add_filter('wp_feed_cache_transient_lifetime', array($this, 'frequency'));
		
	foreach ($this->options['feeds'] as $i => $f) {

			// before the first feed, we check for key validity
			if ( $i == 0 ) {
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

			// prepare, import feed and count imported posts
			if ( $items = $this->do_import($f) ) {
				$post_count += count($items);
			}
		}

		// reformulate import count
		$imports = intval($this->options['imports']) + $post_count;

		// update options
		update_option('rss_pi_feeds', array(
			'feeds' => $this->options['feeds'],
			'settings' => $this->options['settings'],
			'latest_import' => date("Y-m-d H:i:s"),
			'imports' => $imports
		));

		global $rss_post_importer;
		// reload options
		$rss_post_importer->load_options();

		remove_filter('wp_feed_cache_transient_lifetime', array($this, 'frequency'));

		// log this
		rssPILog::log($post_count);

		return $post_count;
	}

	/**
	 * Dummy function for filtering because we can't use anon ones yet
	 * @return string
	 */
	public function frequency() {

		return $this->options['settings']['frequency'];
	}

	/**
	 * Prepares arguments and imports
	 * 
	 * @param array $f feed array
	 * @return array
	 */
	public function do_import($f) {
     
	 
	    
		$args = array(
			'feed_title' => $f['name'],
			'max_posts' => $f['max_posts'],
			'author_id' => $f['author_id'],
			'category_id' => $f['category_id'],
			'tags_id' => $f['tags_id'],
			'keywords' => isset($f['keywords']) && is_array($f['keywords']) ? $f['keywords'] : array(),
			'strip_html' => $f['strip_html'],
			'nofollow_outbound' => $f['nofollow_outbound'],
			'automatic_import_categories' => $f['automatic_import_categories'],
			'automatic_import_author' => $f['automatic_import_author'],
			'feed_status' => $f['feed_status'],
			'canonical_urls' => $f['canonical_urls'],
			'save_to_db' => true
		);
		return $this->_import($f['url'], $args);
	}

	/**
	 * Import feeds from url
	 * 
	 * @param string $url The remote feed url
	 * @param array $args Arguments for the import
	 * @return null|array
	 */
	private function _import($url = '', $args = array()) {

		if (empty($url)) {
			return;
		}

		$defaults = array(
			'feed_title' => '',
			'max_posts' => 5,
			'author_id' => 1,
			'category_id' => 0,
			'tags_id' => array(),
			'keywords' => array(),
			'strip_html' => true,
			'save_to_db' => true,
			'nofollow_outbound' => true,
			'automatic_import_categories' => true,
			'automatic_import_author' => true,
			'feed_status' => 'active',
			'canonical_urls' => 'my_blog'
		);
		
		if($args['feed_status']=='pause'){
		   return;	
		}

		$args = wp_parse_args($args, $defaults);

		// include the default WP feed processing functions
		include_once( ABSPATH . WPINC . '/feed.php' );

		// get the right url for fetching (premium vs free)
		$url = $this->url($url);

		// fetch the feed
		$feed = fetch_feed($url);
        
		 
		if (is_wp_error($feed)) {
			return false;
		}

		// save as posts
		$posts = $this->save($feed, $args);

		return $posts;
	}

	/**
	 * Formulate the right url
	 * 
	 * @param string $url
	 * @return string
	 */
	private function url($url) {

		$key = $this->options['settings']['feeds_api_key'];

		//if api key has been saved by user and is not empty
		if (isset($key) && !empty($key)) {

//			$api_url = 'http://176.58.108.28/fetch.php?key=' . $key . '&url=' . $url;
			$api_url = 'http://176.58.108.28/fetch.php?key=' . $key . '&url=' . urlencode($url);

			return $api_url;
		}

		return $url;
	}

	/**
	 * Save the feed
	 * 
	 * @param object $feed The feed object
	 * @param array $args The arguments
	 * @return boolean
	 */
	private function save($feed, $args = array()) {

		// filter the feed and get feed items
		$feed_items = $this->filter($feed, $args);

		// if we are saving
		if ($args['save_to_db']) {
			// insert and return
			
			$saved_posts = $this->insert($feed_items, $args);
			
			
			return $saved_posts;
		}

		// otherwsie return the feed items
		return $feed_items;
	}

	/**
	 * Filter the feed based on keywords
	 * 
	 * @param object $feed The feed object
	 * @param array $args Arguments
	 * @return array
	 */
	private function filter($feed, $args) {

		// the count of keyword matched items
		 
		$got = 0;

		// the current index of the items aray
		$index = 0;

		$filtered = array();

		// till we have as many as the posts needed
		while ($got < $args['max_posts']) {

			// get only one item at the current index
			$feed_item = $feed->get_items($index, 1);

			// if this is empty, get out of the while
			if (empty($feed_item)) {
				break;
			}
			// else be in a forever loop
			// get the content
			$content = $feed_item[0]->get_content();
			
			
			

			// test it against the keywords
			$tested = $this->test($content,$args['keywords']);

			// if this is good for us
			if ($tested) {
				$got++;

				array_push($filtered, $feed_item[0]);
			}
			// shift the index
			$index++;
		}

		return $filtered;
	}

	/**
	 * Test a piece of content against keywords
	 * 
	 * @param string $content
	 * @return boolean
	 */
	function test($content,$keywords=null) {

		if ( ! $keywords ) {
			$keywords = $this->options['settings']['keywords'];
		}

		if ( empty($keywords) || ! is_array($keywords) ) {
			return true;
		}

		$match = false;

		// loop through keywords
		foreach ( $keywords as $keyword ) {

			// if the keyword is not a regex, make it one
			if ( ! $this->is_regex($keyword) ) {
				$keyword = '/' . $keyword . '/i';
			}

			// look for keyword in content
			preg_match($keyword, $content, $tested);

			// if it's there, we are good
			if ( ! empty($tested) ) {
				$match = true;
				// no need to test anymore
				break;
			}
		}


		return $match;
	}

	/**
	 * Check if a string is regex
	 * 
	 * @param string $str The string to check
	 * @return boolean
	 */
	private function is_regex($str) {

		// check regex with a regex!
		$regex = '/^\/[\s\S]+\/$/';
		preg_match($regex, $str, $matched);
		return !empty($matched);
	}

	/**
	 * Insert feed items as posts
	 * 
	 * @param array $items Fetched feed items
	 * @param array $args arguments
	 * @return array
	 */
	private function insert($items, $args = array()) {

		$saved_posts = array();
        
		// Initialise the content parser
		$parser = new rssPIParser($this->options);

		// Featured Image setter
		$thumbnail = new rssPIFeaturedImage();
		// If Item is active then Import
		
	    if($args['feed_status']=="active"){
		
		foreach ( $items as $item ) {
			
			 
			if ( ! $this->post_exists($item) ) {
				
				/* Code to convert tags id array to tag name array * */
				if ( ! empty($args['tags_id']) ) {
					foreach ( $args['tags_id'] as $tagid ) {
						$tag_name = get_tag($tagid); // <-- your tag ID
						$tags_name[] = $tag_name->name;
					}
				} else {
					$tags_name = array();
				}

				// parse the content
				$content = $parser->_parse($item, $args['feed_title'], $args['strip_html']);
				
				//Filter content for /* Add rel="nofollow" to all outbounded links. */
				if($args['nofollow_outbound']=='true')
				{
				  $content=$this->rss_pi_url_parse_content($content);	
				}
				
				// Get auto categories from Feeds 
				$post_category = array();
				if($args['automatic_import_categories']=='true') {
						
				$category_array = array();
				foreach ($item->get_categories() as $category){
					$cat_id = wp_create_category($category->get_label());
					if($cat_id > 0){
						$category_array[] = $cat_id;
					}else{
						$category = get_term_by('name', $category->get_label(), 'category');
						if($category){
							$category_array[] = $category->term_id;
						}
					}
				} 
				  $post_category =  $category_array;
				}else
				{
				  $post_category =  array($args['category_id']);	
				}
				
	     // Get Author From Feed URl
		 if($args['automatic_import_author']=='true') {	
		 
			  if ($author = $item->get_author())
			  {
				  $array_author = explode(",",$author->get_name());
				  $user_name =   preg_replace('/[^A-Za-z0-9\-]/', ' ', $array_author[0]); 
				  $user_id = username_exists( $user_name );
				  if ( !$user_id) :
				   $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
				   $user_id = wp_create_user( $user_name, $random_password,'');
				  
				  endif;
				  
				$post_author = $user_id;   
			  
			  }else
			  {
				  $post_author = $args['author_id'];
			  }
		 }else
		 {
			 $post_author = $args['author_id'];
		 }
		
		 $post = array(
					'post_title' => $item->get_title(),
					'post_content' => $content,
					'post_status' => $this->options['settings']['post_status'],
					'post_author' => $post_author,
					'post_category' => $post_category,
					'tags_input' => $tags_name,
					'comment_status' => $this->options['settings']['allow_comments'],
					'post_date' => get_date_from_gmt($item->get_date('Y-m-d H:i:s'))
				);
				
				

				// catch base url and replace any img src with it
				if (preg_match('/src="\//ui', $content)) {
					preg_match('/href="(.+?)"/ui', $content, $matches);
					$baseref = (is_array($matches) && !empty($matches)) ? $matches[1] : '';
					if (!empty($baseref)) {
						$bc = parse_url($baseref);
						$scheme = (!isset($bc['scheme']) || empty($bc['scheme'])) ? 'http' : $bc['scheme'];
						$port = isset($bc['port']) ? $bc['port'] : '';
						$host = isset($bc['host']) ? $bc['host'] : '';
						if (!empty($host)) {
							$preurl = $scheme . ( $port ? ':' . $port : '' ) . '//' . $host;
							$post['post_content'] = preg_replace('/(src="\/)/i', 'src="' . $preurl . '/', $content);
						}
					}
				}

				//download images and save them locally if setting suggests so
				if ($this->options['settings']['import_images_locally'] == 'true') {
					$post = $this->download_images_locally($post);
				}

				// insert as post
				
				
				
				$post_id = $this->_insert($post, $item->get_permalink());

				// set thumbnail
				if ( $this->options['settings']['disable_thumbnail'] == 'false' ) {
					// assign a thumbnail (featured image) to the post
					$thumbnail->_set($item, $post_id);
					$attachment_id = get_post_thumbnail_id($post_id);
				} else {
					// just download the image to the media library
					$attachment_id = $thumbnail->_prepare($item, $post_id);
				}

				/* Parse {$inline_image} template tag
				 * @since 2.1.3
				 */
				if ( preg_match('/\{\$inline_image\}/i', $post['post_content']) ) {
					$_post_content = $post['post_content'];
					if ( $attachment_id ) {
//						$featured_image = wp_get_attachment_image($attachment_id);
						$featured_image = wp_get_attachment_image_src($attachment_id,'full');
						$featured_image = '<img src="'.$featured_image[0].'" width="'.$featured_image[1].'" height="'.$featured_image[2].'">';
					} else {
						$featured_image = '';
					}
					$_post_content = preg_replace('/\{\$inline_image\}/i', $featured_image, $_post_content);
					$_post = array(
						'ID' => $post_id,
						'post_content' => $_post_content
					);
					wp_update_post($_post);
					$post['post_content'] = $_post_content;
				}
				// canonical_urls
                update_post_meta($post_id, 'rss_pi_canonical_url',$args['canonical_urls']);
				array_push($saved_posts, $post);
			}
		}
	}

		return $saved_posts;
	}

	/**
	 * Check if a feed ite is alreday imported
	 * 
	 * @param string $permalink
	 * @return boolean
	 */
	private function post_exists($item) {
		global $wpdb;

		$permalink = $item->get_permalink();
		// calculate md5 hash
		$permalink_md5 = md5($permalink);
		// strip any params from the URL
		$permalink_new = $permalink;
		$permalink_new = explode('?',$permalink_new);
		
		$permalink_new = $permalink_new[0];
		// calculate new md5 hash
		$permalink_md5_new = md5($permalink_new);
		$post_exists = FALSE;

		if ( isset($this->options['upgraded']['deleted_posts']) ) { // database migrated
			// check if there is post with this source URL that is not trashed
//			$posts = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->postmeta} WHERE meta_key = 'rss_pi_source_md5' and meta_value = %s", $permalink_md5 ), 'ARRAY_A');
			$posts = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->postmeta} pm, {$wpdb->posts} p WHERE pm.meta_key = 'rss_pi_source_md5' AND ( pm.meta_value = %s) AND pm.post_id = p.ID AND p.post_status <> 'trash'", $permalink_md5), 'ARRAY_A');
			 //echo $wpdb->last_query;
			if ( count($posts) ) {
				$post_exists = TRUE;
			}
		}
//		} else {
		if ( ! $post_exists ) {
			// do it the old fashion way -> check for post title and source domain
			$title = $item->get_title();
			$domain_old = $this->get_domain($permalink);

			//checking if post title already exists
			if ($posts = $wpdb->get_results("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_title = '" . $title . "' and post_status = 'publish' ", 'ARRAY_A')) {
				//checking if post source is also same 
				foreach ($posts as $post) {
					$post_id = $post['ID'];
					$source_url = get_post_meta($post_id, 'rss_pi_source_url', true);
					$domain_new = $this->get_domain($source_url);

					if ($domain_new == $domain_old) {
						$post_exists = TRUE;
					}
				}
			}
		}

		if ( ! $post_exists && $this->options['settings']['cache_deleted'] == 'true' ) {

			// check if the post has been imported and then deleted
			if ( $this->options['upgraded']['deleted_posts'] ) { // database migrated
				$rss_pi_deleted_posts = get_option( 'rss_pi_deleted_posts', array() );
				if ( in_array( $permalink_md5, $rss_pi_deleted_posts )) {
					$post_exists = TRUE;
				}
			} else {
				//do it the old fashion way
				$rss_pi_imported_posts = get_option( 'rss_pi_imported_posts', array() );
				if ( in_array( $permalink, $rss_pi_imported_posts ) ) {
					$post_exists = TRUE;
				}
			}
		}
        /* if($post_exists==true){
			
			echo "1"; 
		 }else{
			echo "0"; 
		 }*/
		return $post_exists;
	}

	// deprecated as of 2.1.2
	private function get_domain($url) {

		$pieces = parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : '';
		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
			return $regs['domain'];
		}
		return false;
	}

	/**
	 * Insert feed item as post
	 * 
	 * @param array $post Post array
	 * @param string $url source url meta
	 * @return int
	 */
	private function _insert($post, $url) {

		if ($post['post_category'][0] == "") {
			$post['post_category'] = array(1);
		} else {
			if (is_array($post['post_category'][0]))
				$post['post_category'] = array_values($post['post_category'][0]);
			else
				$post['post_category'] = array_values($post['post_category']);
		}

		$_post = apply_filters('pre_rss_pi_insert_post', $post);
       
		$post_id = wp_insert_post($_post);

		add_action('save_rss_pi_post', $post_id);

//		$url_md5 = md5($url);
		// strip any params from the URL
		$url_new = $url;
		$url_new = explode('?',$url_new);
		$url_new = $url_new[0];
		// calculate new md5 hash
		$url_md5 = md5($url_new);
		update_post_meta($post_id, 'rss_pi_source_url', esc_url($url));
		update_post_meta($post_id, 'rss_pi_source_md5', $url_md5);

		return $post_id;
	}

	public function pre($arr) {

		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}

	function download_images_locally($post) {

		$post_content = $post['post_content'];
		// initializing DOMDocument to modify the img source 
		$dom = new DOMDocument;
		libxml_use_internal_errors(true);
		$dom->loadHTML('<?xml encoding="utf-8" ?>' . $post_content);
		$xpath = new DOMXPath($dom);
		libxml_clear_errors();
		//get all the src attribs and their values
		$doc = $dom->getElementsByTagName('html')->item(0);
		$src = $xpath->query('.//@src');
		$count = 1;
		foreach ($src as $s) {
			$url = trim($s->nodeValue);
			$attachment_id = $this->add_to_media($url, 0, $post['post_title'] . '-media-' . $count);
			$src = wp_get_attachment_url($attachment_id);
			$s->nodeValue = $src;
			$count++;
		}
		$post['post_content'] = $dom->saveXML($doc);
		return $post;
	}

	function add_to_media($url, $associated_with_post, $desc) {
		$tmp = @download_url($url);
		if (is_wp_error($tmp)) {
			return false;
		}
		$post_id = $associated_with_post;
		$desc = $desc;
		$file_array = array();
		// Set variables for storage
		// fix file filename for query strings
		if ( ! preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches) ) {
			return false;
		}
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;
		// If error storing temporarily, unlink
		if (is_wp_error($tmp)) {
			@unlink($file_array['tmp_name']);
			return false;
		}
		// do the validation and storage stuff
		$id = media_handle_sideload($file_array, $post_id, $desc);
		// If error storing permanently, unlink
		if (is_wp_error($id)) {
			@unlink($file_array['tmp_name']);
			return false;
		}

		return $id;
	}
	
	function rss_pi_url_parse_content($content) {

		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
		if (preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
			if (!empty($matches)) {

				$srcUrl = get_option('home');
				for ($i = 0; $i < count($matches); $i++) {

					$tag = $matches[$i][0];
					$tag2 = $matches[$i][0];
					$url = $matches[$i][0];

					$noFollow = '';

					$pattern = '/target\s*=\s*"\s*_blank\s*"/';
					preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
					if (count($match) < 1)
						$noFollow .= ' target="_blank" ';

					$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
					preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
					if (count($match) < 1)
						$noFollow .= ' rel="nofollow" ';

					$pos = strpos($url, $srcUrl);
					if ($pos === false) {
						$tag = rtrim($tag, '>');
						$tag .= $noFollow . '>';
						$content = str_replace($tag2, $tag, $content);
					}
				}
			}
		}

		$content = str_replace(']]>', ']]&gt;', $content);
		return $content;
	}

}
