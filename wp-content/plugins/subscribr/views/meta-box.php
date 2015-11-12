<?php
/**
 * meta-box.php
 *
 * Adds an out-out option for notifications on the post edit screen.
 *
 * @created   9/25/13 11:31 AM
 * @author    Mindshare Studios, Inc.
 * @copyright Copyright (c) 2013
 * @link      http://www.mindsharelabs.com/documentation/
 *
 */
if(!class_exists('opt_out_meta_box')) :

	class opt_out_meta_box extends Subscribr {

		private $notifications_label;

		/**
		 * Hook into the appropriate actions when the class is constructed.
		 */
		public function __construct($options) {

			$this->options = $options;

			$this->notifications_label = $this->get_option('notifications_label');

			add_action('add_meta_boxes', array($this, 'add_meta_box'));
			add_action('save_post', array($this, 'save'));
		}

		/**
		 * Adds the meta box container.
		 */
		public function add_meta_box() {

			add_meta_box(
				'subscribr_opt_out_meta_box',
				sprintf(__('Disable %s for this post', 'subscribr'), $this->get_option('notifications_label')),
				array($this, 'render_meta_box_content'),
				NULL,
				'side',
				'default'
			);
		}

		/**
		 * Save the meta when the post is saved.
		 *
		 * @param int $post_id The ID of the post being saved.
		 *
		 * @return int
		 */
		public function save($post_id) {

			/*
			 * We need to verify this came from the our screen and with proper authorization,
			 * because save_post can be triggered at other times.
			 */

			if(!current_user_can(('edit_posts'))) {
				return $post_id;
			}

			// Check if our nonce is set and valid
			if(!isset($_POST['subscribr_inner_custom_box_nonce']) || !wp_verify_nonce($_POST['subscribr_inner_custom_box_nonce'], 'subscribr_inner_custom_box')) {
				return $post_id;
			}

			// If this is an autosave don't do anything
			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}

			// avoid undefined index notices (if box is unchecked)
			if(!array_key_exists('subscribr_opt_out', $_POST)) {
				$_POST['subscribr_opt_out'] = 0;
			}
			update_post_meta($post_id, '_subscribr_opt_out', $_POST['subscribr_opt_out']);
		}

		/**
		 * Render Meta Box content.
		 *
		 * @param WP_Post $post The post object.
		 */
		public function render_meta_box_content($post) {

			// Add an nonce field
			wp_nonce_field('subscribr_inner_custom_box', 'subscribr_inner_custom_box_nonce');

			// Use get_post_meta to retrieve an existing value from the database.
			$subscribr_opt_out = get_post_meta($post->ID, '_subscribr_opt_out', TRUE);

			// Display the form, using the current value
			?>
			<label for="_subscribr_opt_out">
				<input name="subscribr_opt_out" type="checkbox" id="subscribr_opt_out" value="1" <?php checked($subscribr_opt_out, 1); ?>>
				<?php echo __(sprintf('Prevent %1$s from being sent for this %2$s.', $this->notifications_label, get_post_type()), 'subscribr'); ?>
			</label>
		<?php
		}
	}
endif;
