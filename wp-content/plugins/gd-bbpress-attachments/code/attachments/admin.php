<?php

if (!defined('ABSPATH')) exit;

class gdbbAtt_Admin {
    function __construct() {
        add_action('after_setup_theme', array($this, 'load'), 10);
    }

    public function load() {
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_meta'));
        add_action('admin_head', array(&$this, 'admin_head'));
        add_action('save_post', array(&$this, 'save_edit_forum'));

        add_action('manage_topic_posts_columns', array(&$this, 'admin_post_columns'), 1000);
        add_action('manage_reply_posts_columns', array(&$this, 'admin_post_columns'), 1000);

        add_action('manage_topic_posts_custom_column', array(&$this, 'admin_columns_data'), 1000, 2);
        add_action('manage_reply_posts_custom_column', array(&$this, 'admin_columns_data'), 1000, 2);
    }

    public function admin_init() {
        if (isset($_POST['gdbb-attach-submit'])) {
            global $gdbbpress_attachments;
            check_admin_referer('gd-bbpress-attachments');

            $gdbbpress_attachments->o['max_file_size'] = absint(intval($_POST['max_file_size']));
            $gdbbpress_attachments->o['max_to_upload'] = absint(intval($_POST['max_to_upload']));
            $gdbbpress_attachments->o['roles_to_upload'] = (array)$_POST['roles_to_upload'];
            $gdbbpress_attachments->o['attachment_icon'] = isset($_POST['attachment_icon']) ? 1 : 0;
            $gdbbpress_attachments->o['attchment_icons'] = isset($_POST['attchment_icons']) ? 1 : 0;
            $gdbbpress_attachments->o['hide_from_visitors'] = isset($_POST['hide_from_visitors']) ? 1 : 0;
            $gdbbpress_attachments->o['include_always'] = isset($_POST['include_always']) ? 1 : 0;
            $gdbbpress_attachments->o['include_js'] = isset($_POST['include_js']) ? 1 : 0;
            $gdbbpress_attachments->o['include_css'] = isset($_POST['include_css']) ? 1 : 0;
            $gdbbpress_attachments->o['delete_attachments'] = strip_tags($_POST['delete_attachments']);
            $gdbbpress_attachments->o['image_thumbnail_active'] = isset($_POST['image_thumbnail_active']) ? 1 : 0;
            $gdbbpress_attachments->o['image_thumbnail_inline'] = isset($_POST['image_thumbnail_inline']) ? 1 : 0;
            $gdbbpress_attachments->o['image_thumbnail_caption'] = isset($_POST['image_thumbnail_caption']) ? 1 : 0;
            $gdbbpress_attachments->o['image_thumbnail_rel'] = strip_tags($_POST['image_thumbnail_rel']);
            $gdbbpress_attachments->o['image_thumbnail_css'] = strip_tags($_POST['image_thumbnail_css']);
            $gdbbpress_attachments->o['image_thumbnail_size_x'] = absint(intval($_POST['image_thumbnail_size_x']));
            $gdbbpress_attachments->o['image_thumbnail_size_y'] = absint(intval($_POST['image_thumbnail_size_y']));
            $gdbbpress_attachments->o['log_upload_errors'] = isset($_POST['log_upload_errors']) ? 1 : 0;
            $gdbbpress_attachments->o['errors_visible_to_admins'] = isset($_POST['errors_visible_to_admins']) ? 1 : 0;
            $gdbbpress_attachments->o['errors_visible_to_moderators'] = isset($_POST['errors_visible_to_moderators']) ? 1 : 0;
            $gdbbpress_attachments->o['errors_visible_to_author'] = isset($_POST['errors_visible_to_author']) ? 1 : 0;
            $gdbbpress_attachments->o['delete_visible_to_admins'] = strip_tags($_POST['delete_visible_to_admins']);
            $gdbbpress_attachments->o['delete_visible_to_moderators'] = strip_tags($_POST['delete_visible_to_moderators']);
            $gdbbpress_attachments->o['delete_visible_to_author'] = strip_tags($_POST['delete_visible_to_author']);

            update_option('gd-bbpress-attachments', $gdbbpress_attachments->o);
            wp_redirect(add_query_arg('settings-updated', 'true'));
            exit();
        }
    }

    public function admin_head() { ?>
        <style type="text/css">
            /*<![CDATA[*/
            th.column-gdbbatt_count, td.column-gdbbatt_count { width: 3%; text-align: center; }
            /*]]>*/
        </style><?php
    }

    public function save_edit_forum($post_id) {
        if (isset($_POST['post_ID']) && $_POST['post_ID'] > 0) {
            $post_id = $_POST['post_ID'];
        }

        if (isset($_POST['gdbbatt_forum_meta']) && $_POST['gdbbatt_forum_meta'] == 'edit') {
            $data = (array)$_POST['gdbbatt'];
            $meta = array(
                'disable' => isset($data['disable']) ? 1 : 0,
                'to_override' => isset($data['to_override']) ? 1 : 0,
                'hide_from_visitors' => isset($data['hide_from_visitors']) ? 1 : 0,
                'max_file_size' => absint(intval($data['max_file_size'])),
                'max_to_upload' => absint(intval($data['max_to_upload']))
            );

            update_post_meta($post_id, '_gdbbatt_settings', $meta);
        }
    }

    public function admin_post_columns($columns) {
        $columns['gdbbatt_count'] = '<img src="'.GDBBPRESSATTACHMENTS_URL.'gfx/attachment.png" width="16" height="12" alt="'.__("Attachments", "gd-bbpress-attachments").'" title="'.__("Attachments", "gd-bbpress-attachments").'" />';
        return $columns;
    }

    public function admin_columns_data($column, $id) {
        if ($column == 'gdbbatt_count') {
            $attachments = d4p_get_post_attachments($id);
            echo count($attachments);
        }
    }

    public function admin_meta() {
        if (current_user_can(GDBBPRESSATTACHMENTS_CAP)) {
            add_meta_box('gdbbattach-meta-forum', __("Attachments Settings", "gd-bbpress-attachments"), array(&$this, 'metabox_forum'), 'forum', 'side', 'high');
            add_meta_box('gdbbattach-meta-files', __("Attachments List", "gd-bbpress-attachments"), array(&$this, 'metabox_files'), 'topic', 'side', 'high');
            add_meta_box('gdbbattach-meta-files', __("Attachments List", "gd-bbpress-attachments"), array(&$this, 'metabox_files'), 'reply', 'side', 'high');
        }
    }

    public function metabox_forum() {
        global $post_ID;

        $meta = get_post_meta($post_ID, '_gdbbatt_settings', true);
        if (!is_array($meta)) {
            global $gdbbpress_attachments;
            $meta = array(
                'disable' => 0, 
                'to_override' => 0, 
                'hide_from_visitors' => 1, 
                'max_file_size' => $gdbbpress_attachments->get_file_size(true), 
                'max_to_upload' => $gdbbpress_attachments->get_max_files(true)
            );
        }

        include(GDBBPRESSATTACHMENTS_PATH.'forms/attachments/meta_forum.php');
    }

    public function metabox_files() {
        global $post_ID, $user_ID;
        $post = get_post($post_ID);
        $author_id = $post->post_author;

        include(GDBBPRESSATTACHMENTS_PATH.'forms/attachments/meta_files.php');
    }
}

global $gdbbpress_attachments_admin;
$gdbbpress_attachments_admin = new gdbbAtt_Admin();

?>