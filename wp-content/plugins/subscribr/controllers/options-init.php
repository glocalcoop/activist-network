<?php
/**
 * options-init.php
 *
 * @created   9/17/13 3:55 PM
 * @author    Mindshare Studios, Inc.
 * @copyright Copyright (c) 2014
 * @link      http://www.mindsharelabs.com/documentation/
 *
 */

if(!class_exists('subscribr_options')) :
	class subscribr_options extends Subscribr {

		public function __construct($options) {

			$this->options = $options;

			// no options have been saved yet, so we'll start with an empty array
			if(!is_array($this->options)) {
				$this->options = array();
			}
			$this->set_options();
			$this->apply_options();
		}

		/**
		 * Setup default options
		 *
		 * Technically this is done by the Mindshare Options Framework, but we want to
		 * make sure we have the correct defaults even if a user never visits the settings
		 * page.
		 */
		public function set_options() {

			$option_changed = FALSE;

			if(!array_key_exists('enable_mail_notifications', $this->options)) {
				$this->options['enable_mail_notifications'] = apply_filters('subscribr_default_enable_mail_notifications', TRUE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('show_on_profile', $this->options)) {
				$this->options['show_on_profile'] = apply_filters('subscribr_default_show_on_profile', TRUE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('show_on_register', $this->options)) {
				$this->options['show_on_register'] = apply_filters('subscribr_default_show_on_register', TRUE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('enable_all_terms', $this->options)) {
				$this->options['enable_all_terms'] = apply_filters('subscribr_default_enable_all_terms', TRUE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('enabled_terms', $this->options)) {
				$this->options['enabled_terms'] = apply_filters('subscribr_default_taxonomies', FALSE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('enable_all_types', $this->options)) {
				$this->options['enable_all_types'] = apply_filters('subscribr_default_enable_all_types', TRUE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('enabled_types', $this->options)) {
				$this->options['enabled_types'] = apply_filters('subscribr_default_types', FALSE);
				$option_changed = TRUE;
			}
			if(!array_key_exists('from_name', $this->options)) {
				$this->options['from_name'] = apply_filters('subscribr_default_from_name', get_bloginfo('name'));
				$option_changed = TRUE;
			}
			if(!array_key_exists('from_email', $this->options)) {
				$this->options['from_email'] = apply_filters('subscribr_default_from_email', get_option('admin_email'));
				$option_changed = TRUE;
			}

			if(!array_key_exists('notifications_label', $this->options)) {
				$this->options['notifications_label'] = apply_filters('subscribr_default_notifications_label', __('notifications', 'subscribr'));
				$option_changed = TRUE;
			}

			if(!array_key_exists('notification_label', $this->options)) {
				$this->options['notification_label'] = apply_filters('subscribr_default_notification_label', __('notification', 'subscribr'));
				$option_changed = TRUE;
			}

			if(!array_key_exists('trigger_action', $this->options)) {
				$this->options['trigger_action'] = apply_filters('subscribr_default_trigger_action', 'new_to_publish,pending_to_publish,auto-draft_to_publish,draft_to_publish,future_to_publish');
				$option_changed = TRUE;
			}

			// default trigger action changed, migrate if still using the old default
			if(version_compare($this->get_version(), '0.1.9', '<=')) {
				if(array_key_exists('trigger_action', $this->options) && $this->options['trigger_action'] == 'publish_post') {
					$this->options['trigger_action'] = apply_filters('subscribr_default_trigger_action', 'new_to_publish,pending_to_publish,auto-draft_to_publish,draft_to_publish,future_to_publish');
					$option_changed = TRUE;
				}
			}

			if(!array_key_exists('use_custom_templates', $this->options)) {
				$this->options['use_custom_templates'] = apply_filters('subscribr_default_use_custom_templates', FALSE);
				$option_changed = TRUE;
			}

			if(!array_key_exists('mail_body', $this->options)) {
				// import the default template $mail_body
				include(SUBSCRIBR_DIR_PATH.'/views/templates/email-template.php');
				$this->options['mail_body'] = apply_filters('subscribr_default_mail_body', $mail_body);
				$option_changed = TRUE;
			}

			if(!array_key_exists('mail_subject', $this->options)) {
				$this->options['mail_subject'] = apply_filters('subscribr_default_mail_body', __('A notification from %sitename%', 'subscribr'));
				$option_changed = TRUE;
			}

			do_action('subscribr_pre_defaults');

			if($option_changed) {
				$this->save_options();
			}

			do_action('subscribr_post_defaults');
		}

		/**
		 * Apply default options
		 *
		 */
		public function apply_options() {

			// hook for adding additional actions with add-ons
			do_action('subscribr_apply_options');

			// action to send emails
			$trigger_action = $this->options['trigger_action'];
			$trigger_action = preg_replace('/\s+/', '', $trigger_action); // strip whitespace
			$trigger_action = explode(',', $trigger_action); // split on commas

			// add the actions
			foreach($trigger_action as $hook) {
				add_action($hook, array($this, 'queue_notifications'));
			}

			if($this->options['show_on_profile']) {

				// actions to add fields to the user profile, register form and edit profile
				add_action('show_user_profile', array($this, 'user_profile_fields'));
				add_action('edit_user_profile', array($this, 'user_profile_fields'));

				// actions to store updated preferences in the user meta table
				add_action('personal_options_update', array($this, 'update_user_meta'));
				add_action('edit_user_profile_update', array($this, 'update_user_meta'));
			}

			if($this->options['show_on_register']) {
				add_action('register_form', array($this, 'user_profile_fields'));
				add_action('user_register', array($this, 'update_user_meta'));
			}
		}

		/**
		 * Saves the options to the DB
		 *
		 */
		public function save_options() {
			update_option(SUBSCRIBR_OPTIONS, $this->options);
		}
	}
endif;
