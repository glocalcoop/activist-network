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
		// add canonical urls
		
		 remove_action( 'wp_head', 'rel_canonical' );
         add_action('wp_head', array($this, 'rss_pi_canonical_urls_meta_tag'));
		 
		// add options
		$this->options = $rss_post_importer->options;

		// Check for block indexing
		if ($this->options['settings']['nofollow_outbound'] == 'true') {
			add_filter('the_content', array($this, 'rss_pi_url_parse'));
		}
		if($this->options['settings']['og_show']==1){
			add_action('wp_head',  array($this, 'fb_opengraph'));
		}
		if($this->options['settings']['tw_show']==1){
			add_action('wp_head', array($this, 'add_twitter_cards'));
		}
		if($this->options['settings']['gg_show']==1){
			add_action('wp_head',  array($this, 'add_google_item'));
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
	
	
	function rss_pi_canonical_urls_meta_tag() {
		global $post, $rss_post_importer;

		// Check if single post
		if (is_single()) {

			// Get current post id
			$current_post_id = $post->ID;
			// add options
			$this->options = $rss_post_importer->options;
			
			// Check for block indexing
			    
		      $meta_rss_pi_canonical_url = get_post_meta($current_post_id, 'rss_pi_canonical_url', false);
			  if(!empty($meta_rss_pi_canonical_url) && $meta_rss_pi_canonical_url[0]=="source_blog"){
			  $meta_values_source = get_post_meta($current_post_id, 'rss_pi_source_url', false);
				if (!empty($meta_values_source)) {
				$pieces = parse_url($meta_values_source[0]);
		        $domain = isset($pieces['host']) ? $pieces['host'] : '';
				if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
					$domain= $regs['domain'];
				}
			    $domain= $regs['domain'];
				$check_domain = explode('.',$domain);
				//Check URL for Google Only
				if(isset($check_domain[0]) && $check_domain[0]=="google"){
				   $google_url     = $pieces['fragment'];
				   $google_explode = explode("url=",$google_url);
				   if(isset($google_explode[1]) && $google_explode[1]!='')
				   {
					   $canonical_urls = $google_explode[1];
				   }
				}else{
				       $canonical_urls = $meta_values_source[0];
				}
				
				// if meta value array is empty it means post is not imported by this plugin.
				if (!empty($meta_values_source)) {
					
					echo "<link rel='canonical' href='".$canonical_urls."' />";
				}
				
				}
			  }else
			  {
				  // original code
				  $link = get_permalink( $current_post_id );
				  echo "<link rel='canonical' href='$link' />\n";
					
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
	
	function add_twitter_cards() {
		global $post;
	  if($this->options['settings']['tw_show']==1){
		if(is_single()) {
			$tc_url    = get_permalink();
			$tc_title  = get_the_title();
			if($excerpt  = $post->post_content) {
					$excerpt = strip_tags($post->post_content);
					$excerpt = str_replace("", "'", $excerpt);
				}
			$tc_description = trim(substr($excerpt, 0,150));
			if(has_post_thumbnail($post->ID)) {
				$img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), full );
				$tc_image_thumb  = $img_src[0];
			} else {
				$tc_image_thumb = site_url() . '/wp-content/plugins/rss-post-importer/app/assets/img/03-04-feedsapi-api.jpg';
			}
			echo '<meta name="twitter:card" value="summary" />';
			echo '<meta name="twitter:site" value="@feedsapi" />';
			echo '<meta name="twitter:title" value="'.$tc_title.'" />';
			echo '<meta name="twitter:description" value="'.$tc_description.'" />';
			echo '<meta name="twitter:url" value="'.$tc_url.'" />';
			echo '<meta name="twitter:image" value="'.$tc_image_thumb.'" />';
			echo '<meta name="twitter:creator" value="@feedsapi" />';
    	  }
		}
     }
	 
	 function fb_opengraph() {
		global $post;
		 if($this->options['settings']['og_show']==1){
			if(is_single()) {
				if(has_post_thumbnail($post->ID)) {
					$img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'medium');
					$tc_image_thumb  = $img_src[0];
				} else {
					$tc_image_thumb = site_url() . '/wp-content/plugins/rss-post-importer/app/assets/img/03-04-feedsapi-api.jpg';
				}
				
				if($excerpt  = $post->post_content) {
					$excerpt = strip_tags($post->post_content);
					$excerpt = str_replace("", "'", $excerpt);
				}
				$rest = trim(substr($excerpt, 0,150));
				$post_date = $post->post_date;
				$og_title  = get_the_title();
				$og_url    = get_permalink();
				echo '<meta property="og:title" content="'.$og_title.'"/>';
                echo '<meta property="og:image" content="'.$tc_image_thumb.'"/>';
				echo '<meta property="og:image:width" content="681" />';
				echo '<meta property="og:image:height" content="358" />';
                echo '<meta property="og:site_name" content="'.get_bloginfo().'"/>';
                echo '<meta property="og:description" content="'.$rest.'"/>';
			} 
		 }
     }
	 
	 function add_google_item() {
		 global $post;
	  if($this->options['settings']['gg_show']==1){
		if(is_single()) {
				if(has_post_thumbnail($post->ID)) {
					$img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), full );
					$tc_image_thumb  = $img_src[0];
				} else {
					$tc_image_thumb = site_url() . '/wp-content/plugins/rss-post-importer/app/assets/img/03-04-feedsapi-api.jpg';
				}

				if($excerpt  = $post->post_content) {
					$excerpt = strip_tags($post->post_content);
					$excerpt = str_replace("", "'", $excerpt);
				}
				$rest = trim(substr($excerpt, 0,150));
				$gg_url    = get_permalink();
				$gg_title  = get_the_title();
				echo '<meta itemprop="name" content="'.$gg_title.'">';
				echo '<meta itemprop="description" content="'.$rest.'">';
				echo '<meta itemprop="image" content="'.$tc_image_thumb.'">';
    	  }
		}
	 }


}
