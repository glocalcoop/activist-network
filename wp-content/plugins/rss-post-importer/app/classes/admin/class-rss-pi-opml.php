<?php

/**
 * This class handles all OPML functionality
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
if (!class_exists("Rss_pi_opml")) {

	class Rss_pi_opml {

		var $options;

		var $errors;

		/*
		 * The constructor
		 */
		function __construct() {

			$this->options = get_option('rss_pi_feeds', array());
		}

		/*
		 * Exports all feeds, no Settings exported
		 */
		function export() {

			if ( $this->options['settings']['is_key_valid'] ) {

				$feeds = $this->options['feeds'];
				$title = get_option('blogname');
				$ownerEmail = get_option('admin_email');

				if ( ! count($feeds) || ! trim($title) || ! $ownerEmail ) {
					return;
				}

				if ( ! $title || ! $ownerEmail ) {
					return;
				}

				$output = '';

				$output .= $this->_header($title, $ownerEmail);

				foreach ( $feeds as $feed ) {
					$output .= $this->_entry($feed['url'], $feed['name']);
				}

				$output .= $this->_footer();

				$filename = "rss_pi_export_" . date("Y-m-d") . ".opml";
				$this->_send_headers($filename);
				echo "\xEF\xBB\xBF";
				print($output);
				die();

			}

		}

		/*
		 * Imports feeds from file
		 */
		function import($feeds) {

			if ( $this->options['settings']['is_key_valid'] ) {

				$file = $_FILES['import_opml']['tmp_name'];
				$opml = file_get_contents($file);
				@unlink($file);

				// apply some validation fixes:
				// - & -> &amp;
				$opml = preg_replace( '/(&(?!amp;))/', '&amp;', $opml );

				$opml = new OPMLParser($opml);

				$feeds = $this->_parse_data( $opml->data, $feeds );
				$this->options['feeds'] = $feeds;

			}

			return $feeds;

		}

		private function _parse_data($data, $feeds) {

			if ( ! is_array($data) ) {
				return $feeds;
			}

			foreach ( $data as $k => $item ) {

				if ( isset($item['xmlurl']) && isset($item['text']) && trim($item['xmlurl']) !== '' ) {

//					if ( $feed['xmlurl'] && filter_var($feed['xmlurl'], FILTER_VALIDATE_URL) && $this->_feed_url_exists( $feeds, $feed['xmlurl'] ) )
					if ( $this->_feed_url_exists( $feeds, $item['xmlurl'] ) ) {
						$this->errors[] = 'Duplicate Feed url: ' . $item['xmlurl'];
						continue;
					}
					if ( $this->_feed_name_exists( $feeds, $item['text'] ) ) {
						$this->errors[] = 'Duplicate Feed name: ' . $item['text'];
						continue;
					}

					$c = count($feeds);
					array_push($feeds, array(
						'id' => uniqid("54d4c" . $c),
						'url' => $item['xmlurl'],
						'name' => $item['text'],
						// default values
						'max_posts' => 10,
						'author_id' => get_current_user_id(),
						'category_id' => array(1),
						'tags_id' => '',
						'keywords' => '',
						'strip_html' => 'false',
						'nofollow_outbound' => 'false',
						'automatic_import_categories' => 'false',
						'automatic_import_author' => 'false',
						'feed_status' => 'active',
						'canonical_urls' => 'my_blog'
					));

				} else {

					$feeds = $this->_parse_data($item, $feeds);

				}

			}

			return $feeds;

		}

		private function _feed_url_exists($feeds, $url) {

			if ( ! empty($feeds) && ! empty($url) ) {
				for ( $i = 0; $i < count($feeds); $i++ ) {
					if ( $feeds[$i]['url'] == $url ) {
						return true;
					}
				}
				return false;
			}
		}

		private function _feed_name_exists($feeds, $name) {

			if ( ! empty($feeds) && ! empty($name) ) {
				for ( $i = 0; $i < count($feeds); $i++ ) {
					if ( $feeds[$i]['name'] == $name ) {
						return true;
					}
				}
				return false;
			}
		}

		/**
		 * basic opml header
		 * @param string $opmlTitle
		 * @param string $opmlOwnerEmail
		 * @return string
		 */
		private function _header($title, $ownerEmail) {
//			$result = "<-?xml version=\"1.0\" encoding=\"ISO-8859-1\"?->\n"
			$result = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
					. "<opml version=\"1.1\">\n"
					. "<head>\n"
					. "      <title>" . $title . "</title>\n"
					. "    <dateCreated>" . date("r") . "</dateCreated>\n"
//					. "    <ownerName>" . $ownerName . "</ownerName>\n"
					. "    <ownerEmail>" . $ownerEmail . "</ownerEmail>\n"
					. "  </head>\n"
					. "  <body>\n";
			return $result;
		}

		/**
		 * just returns a test footer
		 * @return string
		 */
		private function _footer() {
			$result = "  </body>\n"
					. "</opml>";
			return $result;
		}

		/**
		 * creates an XML entry for the OPML file
		 * @param string $feedURL
		 * @param string $feedTitle
		 * @return string
		 */
		private function _entry($feedURL, $feedTitle) {
			$result = "    <outline text=\"" . $feedTitle . "\" type=\"rss\" xmlUrl=\"" . $feedURL . "\"/>\n";
			return $result;
		}

		private function _send_headers($filename) {

			// disable caching
			$now = gmdate("D, d M Y H:i:s");
			header("Expires: Tue, 01 Jan 2000 01:00:00 GMT"); // a date in the past
			header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
			header("Last-Modified: {$now} GMT");

			// force download  
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");

			// disposition / encoding on response body
			header("Content-Disposition: attachment;filename={$filename}");
			header("Content-Transfer-Encoding: binary");
			header("Content-type: text/html;charset=utf-8");

		}

	}
	// CLass Rss_pi_opml
}
