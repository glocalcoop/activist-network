<?php
/**
 * Plugin Name: Admin Post Navigation
 * Version:     2.0
 * Plugin URI:  http://coffee2code.com/wp-plugins/admin-post-navigation/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: admin-post-navigation
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Adds links to navigate to the next and previous posts when editing a post in the WordPress admin.
 *
 * Compatible with WordPress 3.0 through 4.4+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/admin-post-navigation/
 *
 * @package Admin_Post_Navigation
 * @author  Scott Reilly
 * @version 2.0
 */

/*
 * TODO:
 * - Add ability for navigation to save current post before navigating away.
 * - Hide screen option checkbox for metabox if metabox is being hidden
 * - Add screen option allowing user selection of post navigation order
 * - Add more unit tests
 * - Add dropdown to post nav links to allow selecting different types of things
 *   to navigate to (e.g. next draft (if looking at a draft), next in category X)
 */

/*
	Copyright (c) 2008-2016 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_AdminPostNavigation' ) ) :

class c2c_AdminPostNavigation {

	/**
	 * Translated text for previous link.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private static $prev_text = '';

	/**
	 * Translated text for next link.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private static $next_text = '';

	/**
	 * Default post statuses for navigation.
	 *
	 * Filterable later.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $post_statuses = array( 'draft', 'future', 'pending', 'private', 'publish', 'inherit' );

	/**
	 * Post status query fragment.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private static $post_statuses_sql = '';

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.7
	 */
	public static function version() {
		return '2.0';
	}

	/**
	 * Class constructor: initializes class variables and adds actions and filters.
	 */
	public static function init() {
		add_action( 'load-post.php', array( __CLASS__, 'register_post_page_hooks' ) );
	}

	/**
	 * Filters/actions to hook on the admin post.php page.
	 *
	 * @since 1.7
	 */
	public static function register_post_page_hooks() {

		// Load textdomain.
		load_plugin_textdomain( 'admin-post-navigation' );

		// Set translatable strings.
		self::$prev_text = apply_filters( 'c2c_admin_post_navigation_prev_text', __( '&larr; Previous', 'admin-post-navigation' ) );
		self::$next_text = apply_filters( 'c2c_admin_post_navigation_next_text', __( 'Next &rarr;', 'admin-post-navigation' ) );

		// Register hooks.
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'admin_enqueue_scripts_and_styles' ) );
		add_action( 'do_meta_boxes',              array( __CLASS__, 'do_meta_box' ), 10, 3 );
	}

	/**
	 * Enqueues scripts and stylesheets on post edit admin pages.
	 *
	 * @since 2.0
	 */
	public static function admin_enqueue_scripts_and_styles() {
		wp_register_style( 'admin-post-navigation-admin', plugins_url( 'assets/admin-post-navigation.css', __FILE__ ), array(), self::version() );
		wp_enqueue_style( 'admin-post-navigation-admin' );

		wp_register_script( 'admin-post-navigation-admin', plugins_url( 'assets/admin-post-navigation.js', __FILE__ ), array( 'jquery' ), self::version(), true );
		// Localize script.
		wp_localize_script( 'admin-post-navigation-admin', 'c2c_apn', array(
			'tag' => version_compare( $GLOBALS['wp_version'], '4.3', '>=' ) ? 'h1' : 'h2',
		) );
		wp_enqueue_script( 'admin-post-navigation-admin' );
	}

	/**
	 * Register meta box.
	 *
	 * By default, the navigation is present for all post types. Filter
	 * 'c2c_admin_post_navigation_post_types' to limit its use.
	 *
	 * @param string  $post_type The post type.
	 * @param string  $type      The mode for the meta box (normal, advanced, or side).
	 * @param WP_Post $post      The post.
	 */
	public static function do_meta_box( $post_type, $type, $post ) {
		$post_types = apply_filters( 'c2c_admin_post_navigation_post_types', get_post_types() );
		if ( ! in_array( $post_type, $post_types ) ) {
			return;
		}

		$post_statuses = (array) apply_filters( 'c2c_admin_post_navigation_post_statuses', self::$post_statuses, $post_type, $post );
		if ( $post_statuses ) {
			foreach( $post_statuses as $i => $v ) { $GLOBALS['wpdb']->escape_by_ref( $v ); $post_statuses[ $i ] = $v; }
			self::$post_statuses_sql = "'" . implode( "', '", $post_statuses ) . "'";
		}

		if ( in_array( $post->post_status, $post_statuses ) ) {
			add_meta_box(
				'adminpostnav',
				sprintf( __( '%s Navigation', 'admin-post-navigation' ), ucfirst( $post_type ) ),
				array( __CLASS__, 'add_meta_box' ),
				$post_type,
				'side',
				'core'
			);
		}
	}

	/**
	 * Adds the content for the post navigation meta_box.
	 *
	 * @param object $object
	 * @param array  $box
	 */
	public static function add_meta_box( $object, $box ) {
		$display = '';

		$context = self::_get_post_type_label( $object->post_type );

		$prev = self::previous_post();
		if ( $prev ) {
			$post_title = the_title_attribute( array( 'echo' => false, 'post' => $prev->ID ) );
			$display .= '<a href="' . get_edit_post_link( $prev->ID ) . '" id="admin-post-nav-prev" title="' .
				esc_attr( sprintf( __( 'Previous %1$s: %2$s', 'admin-post-navigation' ), $context, $post_title ) ) .
				'" class="admin-post-nav-prev add-new-h2">' . self::$prev_text . '</a>';
		}

		$next = self::next_post();
		if ( $next ) {
			if ( $display ) {
				$display .= ' ';
			}
			$post_title = the_title_attribute( array( 'echo' => false, 'post' => $next->ID ) );
			$display .= '<a href="' . get_edit_post_link( $next->ID ) .
				'" id="admin-post-nav-next" title="' .
				esc_attr( sprintf( __( 'Next %1$s: %2$s', 'admin-post-navigation' ), $context, $post_title ) ).
				'" class="admin-post-nav-next add-new-h2">' . self::$next_text . '</a>';
		}

		$display = '<span id="admin-post-nav">' . $display . '</span>';
		$display = apply_filters( 'admin_post_nav', $display ); /* Deprecated as of v1.5 */
		echo apply_filters( 'c2c_admin_post_navigation_display', $display );
	}

	/**
	 * Gets label for post type.
	 *
	 * @since 1.7
	 *
	 * @param string  $post_type The post_type.
	 * @return string The label for the post_type.
	 */
	public static function _get_post_type_label( $post_type ) {
		$label = $post_type;
		$post_type_object = get_post_type_object( $label );
		if ( is_object( $post_type_object ) ) {
			$label = $post_type_object->labels->singular_name;
		}

		return strtolower( $label );
	}

	/**
	 * Returns the previous or next post relative to the current post.
	 *
	 * Currently, a previous/next post is determined by the next lower/higher
	 * valid post based on relative sequential post ID and which the user can
	 * edit.  Other post criteria such as post type (draft, pending, etc),
	 * publish date, post author, category, etc, are not taken into
	 * consideration when determining the previous or next post.
	 *
	 * @param string $type   Optional. Either '<' or '>', indicating previous or next post, respectively. Default '<'.
	 * @param int    $offset Optional. Offset. Primarily for internal, self-referencial use. Default 0.
	 * @param int    $limit  Optional. Number of posts to get in the query. Not just the next post because a few might
	 *                       need to be traversed to find a post the user has the capability to edit. Default 15.
	 * @return WP_Post|false
	 */
	public static function query( $type = '<', $offset = 0, $limit = 15 ) {
		global $post_ID, $wpdb;

		if ( $type != '<' ) {
			$type = '>';
		}
		$offset = (int) $offset;
		$limit  = (int) $limit;

		$post = get_post( $post_ID );

		if ( ! $post || ! self::$post_statuses_sql ) {
			return false;
		}

		$post_type = esc_sql( get_post_type( $post_ID ) );

		$sql = "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = '$post_type' AND post_status IN (" . self::$post_statuses_sql . ') ';

		// Determine order.
		if ( is_post_type_hierarchical( $post_type ) ) {
			$orderby = 'post_title';
		} else {
			$orderby = 'post_date';
		}
		$default_orderby = $orderby;
		// Restrict orderby to actual post fields.
		$orderby = esc_sql( apply_filters( 'c2c_admin_post_navigation_orderby', $orderby, $post_type ) );
		if ( ! in_array( $orderby, array( 'comment_count', 'ID', 'menu_order', 'post_author', 'post_content', 'post_content_filtered', 'post_date', 'post_excerpt', 'post_date_gmt', 'post_mime_type', 'post_modified', 'post_modified_gmt', 'post_name', 'post_parent', 'post_status', 'post_title', 'post_type' ) ) ) {
			$orderby = $default_orderby;
		}

		$sql .= "AND {$orderby} {$type} '{$post->$orderby}' ";

		$sort = $type == '<' ? 'DESC' : 'ASC';
		$sql .= "ORDER BY {$orderby} {$sort} LIMIT {$offset}, {$limit}";

		// Find the first post the user can actually edit.
		$posts = $wpdb->get_results( $sql );
		$result = false;
		if ( $posts ) {
			foreach ( $posts as $post ) {
				if ( current_user_can( 'edit_post', $post->ID ) ) {
					$result = $post;
					break;
				}
			}
			if ( ! $result ) { // The fetch did not yield a post editable by user, so query again.
				$offset += $limit;
				// Double the limit each time (if haven't found a post yet, chances are we may not, so try to get through posts quicker).
				$limit += $limit;
				return self::query( $type, $offset, $limit );
			}
		}
		return $result;
	}

	/**
	 * Returns the next post relative to the current post.
	 *
	 * A convenience function that calls query().
	 *
	 * @return object The next post object.
	 */
	public static function next_post() {
		return self::query( '>' );
	}

	/**
	 * Returns the previous post relative to the current post.
	 *
	 * A convenience function that calls query().
	 *
	 * @return object The previous post object.
	 */
	public static function previous_post() {
		return self::query( '<' );
	}

} // end c2c_AdminPostNavigation

c2c_AdminPostNavigation::init();

endif; // end if !class_exists()
