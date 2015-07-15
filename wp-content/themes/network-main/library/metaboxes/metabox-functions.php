<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Network Main Theme
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_glocal_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_glocal_metaboxes( array $meta_boxes ) {

	$prefix = 'glocal_';

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all the sites into an array
	$options_sites = array();
	$options_sites_obj = wp_get_sites('offset=1');
	foreach ($options_sites_obj as $site) {
		$site_id = $site['blog_id'];
		$site_details = get_blog_details($site_id);
		$options_sites[$site_id] = $site_details->blogname;
	}

	/**
	 * Metabox for networks
	 */
	$meta_boxes['sites_metabox'] = array(
		'id'         => 'sites_metabox',
		'title'      => __( 'Sites', 'glocal' ),
		'pages'      => array( 'site_networks', ), // Post type
		'context'    => 'side',
		'priority'   => 'high',
		'show_names' => false, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name'    => __( 'Sites', 'glocal' ),
				'desc'    => __( 'Select the sites that are part of this network', 'glocal' ),
				'id'      => $prefix . 'network_sites',
				'type'    => 'multicheck',
				'options' => $options_sites,
			),
		),
	);

	$meta_boxes['contact_metabox'] = array(
		'id'         => 'contact_metabox',
		'title'      => __( 'Contact Information', 'glocal' ),
		'pages'      => array( 'site_networks', ), // Post type
		'context'    => 'side',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Website', 'glocal' ),
				'desc' => __( '', 'glocal' ),
				'id'   => $prefix . 'websiteurl',
				'type' => 'text_url',
			),
			array(
				'name' => __( 'Facebook', 'glocal' ),
				'desc' => __( '', 'glocal' ),
				'id'   => $prefix . 'facebookurl',
				'type' => 'text_url',
			),
			array(
				'name' => __( 'Twitter', 'glocal' ),
				'desc' => __( '', 'glocal' ),
				'id'   => $prefix . 'twitterurl',
				'type' => 'text_url',
			),
		)
	);

	/**
	 * Metabox for volunteer posts
	 */
	$meta_boxes['volunteer_location_metabox'] = array(
		'id'            => 'volunteer_location_metabox',
		'title'         => __( 'Location Information', 'glocal' ),
		'pages'         => array( 'post' ), // Tells CMB to use user_meta vs post_meta
		'show_names'    => false,
		// 'cmb_styles' => true, // Show cmb bundled styles.. not needed on user profile page
		'fields'        => array(
			array(
				'name' => __( 'Location', 'cmb' ),
				'desc' => __( 'Enter location of volunteer opportunity', 'glocal' ),
				'id'   => $prefix . 'test_text',
				'type' => 'text',
				// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
				// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
				// 'on_front'        => false, // Optionally designate a field to wp-admin only
				// 'repeatable'      => true,
			),
		)
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
