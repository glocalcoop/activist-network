<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Activist Network - Community Group
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'community_group_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function community_group_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_community_group_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['hide_post_metabox'] = array(
		'id'         => 'hide_post_metabox',
		'title'      => __( 'Display on Main Site?', 'community_group' ),
		'pages'      => array( 'post', ), // Post type
		'context'    => 'side',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left

		'fields'     => array(
			array(
				'name' => __( 'Hide Post', 'community_group' ),
				'desc' => __( 'Check to hide this post from main site', 'community_group' ),
				'id'   => $prefix . 'hide_post_checkbox',
				'type' => 'checkbox',
			),
		),
	);

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
