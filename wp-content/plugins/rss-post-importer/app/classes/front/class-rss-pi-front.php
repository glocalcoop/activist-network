<?php

/**
 * The class that handles the front screen
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
class rssPIFront {

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
	 * Initialise and hook all actions
	 */
	public function init() {
		global $post, $rss_post_importer;

		// add noidex to front
		add_action('wp_head', array($this, 'rss_pi_noindex_meta_tag'));

		// add options
		$this->options = $rss_post_importer->options;

		// Check for block indexing
		if ($this->options['settings']['nofollow_outbound'] == 'true') {
			add_filter('the_content', array($this, 'rss_pi_url_parse'));
		}
	}

	function rss_pi_noindex_meta_tag() {
		global $post, $rss_post_importer;

		//Add meta tag for UTF-8 character encoding.
		echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';

		// Check if single post
		if (is_single()) {

			// Get current post id
			$current_post_id = $post->ID;

			// add options
			$this->options = $rss_post_importer->options;

			// get value of block indexing
			$block_indexing = $this->options['settings']['block_indexing'];

			// Check for block indexing
			if ($this->options['settings']['block_indexing'] == 'true') {
				$meta_values = get_post_meta($current_post_id, 'rss_pi_source_url', false);
				// if meta value array is empty it means post is not imported by this plugin.
				if (!empty($meta_values)) {
					echo '<meta name="robots" content="noindex">';
				}
			}
		}
	}

	function rss_pi_url_parse($content) {

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
