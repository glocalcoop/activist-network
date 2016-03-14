<?php

if (!defined('ABSPATH')) exit;

class gdbbAtt_Front {
    private $icons = array(
        'code' => 'c|cc|h|js|class', 
        'xml' => 'xml', 
        'excel' => 'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm', 
        'word' => 'docx|dotx|docm|dotm', 
        'image' => 'png|gif|jpg|jpeg|jpe|jp|bmp|tif|tiff', 
        'psd' => 'psd', 
        'ai' => 'ai', 
        'archive' => 'zip|rar|gz|gzip|tar',
        'text' => 'txt|asc|nfo', 
        'powerpoint' => 'pot|pps|ppt|pptx|ppam|pptm|sldm|ppsm|potm', 
        'pdf' => 'pdf', 
        'html' => 'htm|html|css', 
        'video' => 'avi|asf|asx|wax|wmv|wmx|divx|flv|mov|qt|mpeg|mpg|mpe|mp4|m4v|ogv|mkv', 
        'documents' => 'odt|odp|ods|odg|odc|odb|odf|wp|wpd|rtf',
        'audio' => 'mp3|m4a|m4b|mp4|m4v|wav|ra|ram|ogg|oga|mid|midi|wma|mka',
        'icon' => 'ico'
    );

    function __construct() {
        add_action('bbp_init', array($this, 'load'));
    }

    public function load() {
        add_action('bbp_head', array(&$this, 'bbp_head'));
        add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));

        add_action('bbp_theme_before_reply_form_submit_wrapper', array(&$this, 'embed_form'));
        add_action('bbp_theme_before_topic_form_submit_wrapper', array(&$this, 'embed_form'));

        add_action('bbp_edit_reply', array(&$this, 'save_reply'), 10, 5);
        add_action('bbp_edit_topic', array(&$this, 'save_topic'), 10, 4);
        add_action('bbp_new_reply', array(&$this, 'save_reply'), 10, 5);
        add_action('bbp_new_topic', array(&$this, 'save_topic'), 10, 4);

        add_filter('bbp_get_reply_content', array(&$this, 'embed_attachments'), 100, 2);
        add_filter('bbp_get_topic_content', array(&$this, 'embed_attachments'), 100, 2);

        if (d4p_bba_o('attachment_icon') == 1) {
            add_action('bbp_theme_before_topic_title', array(&$this, 'show_attachments_icon'));
        }
    }

    private function icon($ext) {
        foreach ($this->icons as $icon => $list) {
            $list = explode('|', $list);

            if (in_array($ext, $list)) {
                return $icon;
            }
        }

        return 'generic';
    }

    public function wp_enqueue_scripts() {
        if (d4p_bba_o('include_always') == 1 || d4p_is_bbpress()) {
            if (d4p_bba_o('include_css') == 1) {
                wp_enqueue_style('d4p-bbattachments-css', GDBBPRESSATTACHMENTS_URL.'css/gd-bbpress-attachments.css', array(), GDBBPRESSATTACHMENTS_VERSION);
            }

            if (d4p_bba_o('include_js') == 1) {
                wp_enqueue_script('jquery');
                wp_enqueue_script('d4p-bbattachments-js', GDBBPRESSATTACHMENTS_URL.'js/gd-bbpress-attachments.js', array('jquery'), GDBBPRESSATTACHMENTS_VERSION, true);
            }
        }
    }

    public function bbp_head() { 
        if (d4p_bba_o('include_always') == 1 || d4p_is_bbpress()) {
            global $gdbbpress_attachments;

        ?><script type="text/javascript">
                /* <![CDATA[ */
                var gdbbPressAttachmentsInit = {
                    max_files: <?php echo apply_filters('d4p_bbpressattchment_allow_upload', $gdbbpress_attachments->get_max_files(), bbp_get_forum_id()); ?>
                };
                /* ]]> */
        </script><?php

        }
    }

    public function save_topic($topic_id, $forum_id, $anonymous_data, $topic_author) {
        $this->save_reply(0, $topic_id, $forum_id, $anonymous_data, $topic_author);
    }

    public function save_reply($reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author) {
        $uploads = array();

        if (!empty($_FILES) && !empty($_FILES['d4p_attachment'])) {
            global $gdbbpress_attachments;

            require_once(ABSPATH.'wp-admin/includes/file.php');

            $errors = new gdbbp_Error();
            $overrides = array('test_form' => false, 'upload_error_handler' => 'd4p_bbattachment_handle_upload_error');

            foreach ($_FILES['d4p_attachment']['error'] as $key => $error) {
                $file_name = $_FILES['d4p_attachment']['name'][$key];

                if ($error == UPLOAD_ERR_OK) {
                    $file = array('name' => $file_name,
                        'type' => $_FILES['d4p_attachment']['type'][$key],
                        'size' => $_FILES['d4p_attachment']['size'][$key],
                        'tmp_name' => $_FILES['d4p_attachment']['tmp_name'][$key],
                        'error' => $_FILES['d4p_attachment']['error'][$key]
                    );

                    if ($gdbbpress_attachments->is_right_size($file, $forum_id)) {
                        $upload = wp_handle_upload($file, $overrides);

                        if (!is_wp_error($upload)) {
                            $uploads[] = $upload;
                        } else {
                            $errors->add('wp_upload', $upload->errors['wp_upload_error'][0], $file_name);
                        }
                    } else {
                        $errors->add('d4p_upload', 'File exceeds allowed file size.', $file_name);
                    }
                } else {
                    switch ($error) {
                        default:
                        case 'UPLOAD_ERR_NO_FILE':
                            $errors->add('php_upload', 'File not uploaded.', $file_name);
                            break;
                        case 'UPLOAD_ERR_INI_SIZE':
                            $errors->add('php_upload', 'Upload file size exceeds PHP maximum file size allowed.', $file_name);
                            break;
                        case 'UPLOAD_ERR_FORM_SIZE':
                            $errors->add('php_upload', 'Upload file size exceeds FORM specified file size.', $file_name);
                            break;
                        case 'UPLOAD_ERR_PARTIAL':
                            $errors->add('php_upload', 'Upload file only partially uploaded.', $file_name);
                            break;
                        case 'UPLOAD_ERR_CANT_WRITE':
                            $errors->add('php_upload', 'Can\'t write file to the disk.', $file_name);
                            break;
                        case 'UPLOAD_ERR_NO_TMP_DIR':
                            $errors->add('php_upload', 'Temporary folder for upload is missing.', $file_name);
                            break;
                        case 'UPLOAD_ERR_EXTENSION':
                            $errors->add('php_upload', 'Server extension restriction stopped upload.', $file_name);
                            break;
                    }
                }
            }
        }

        $post_id = $reply_id == 0 ? $topic_id : $reply_id;

        if (!empty($errors->errors) && d4p_bba_o('log_upload_errors') == 1) {
            foreach ($errors->errors as $code => $errs) {
                foreach ($errs as $error) {
                    if ($error[0] != '' && $error[1] != '') {
                        add_post_meta($post_id, '_bbp_attachment_upload_error', array(
                            'code' => $code, 'file' => $error[1], 'message' => $error[0])
                        );
                    }
                }
            }
        }

        if (!empty($uploads)) {
            require_once(ABSPATH.'wp-admin/includes/media.php');
            require_once(ABSPATH.'wp-admin/includes/image.php');

            foreach ($uploads as $upload) {
                $wp_filetype = wp_check_filetype(basename($upload['file']), null );
                $attachment = array('post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($upload['file'])),
                    'post_content' => '','post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                update_post_meta($attach_id, '_bbp_attachment', '1');
            }
        }
    }

    public function show_attachments_icon() {
        $topic_id = bbp_get_topic_id();
        $count = d4p_topic_attachments_count($topic_id, true);

        if ($count > 0) {
            echo '<span class="bbp-attachments-count" title="'.$count.' '._n("attachment", "attachments", $count, "gd-bbpress-attachments").'"></span>';
        }
    }

    public function embed_attachments($content, $id) {
        global $gdbbpress_attachments, $user_ID;
        $attachments = d4p_get_post_attachments($id);

        $post = get_post($id);
        $author_id = $post->post_author;

        if (!empty($attachments)) {
            $content.= '<div class="bbp-attachments">';
            $content.= '<h6>'.__("Attachments", "gd-bbpress-attachments").':</h6>';

            if (!is_user_logged_in() && $gdbbpress_attachments->is_hidden_from_visitors()) {
                $content.= sprintf(__("You must be <a href='%s'>logged in</a> to view attached files.", "gd-bbpress-attachments"), wp_login_url(get_permalink()));
            } else {
                if (!empty($attachments)) {
                    $listing = '<ol';

                    if (d4p_bba_o("attchment_icons") == 1) {
                        $listing.= ' class="with-icons"';
                    }

                    $listing.= '>';
                    $thumbnails = $listing;
                    $images = $files = 0;

                    foreach ($attachments as $attachment) {
                        $actions = array();

                        $url = add_query_arg('_wpnonce', wp_create_nonce('d4p-bbpress-attachments'));
                        $url = add_query_arg('att_id', $attachment->ID, $url);
                        $url = add_query_arg('bbp_id', $id, $url);

                        $allow = 'no';
                        if (d4p_is_user_admin()) {
                            $allow = d4p_bba_o('delete_visible_to_admins');
                        } else if (d4p_is_user_moderator()) {
                            $allow = d4p_bba_o('delete_visible_to_moderators');
                        } else if ($author_id == $user_ID) {
                            $allow = d4p_bba_o('delete_visible_to_author');
                        }

                        if ($allow == 'delete' || $allow == 'both') {
                            $actions[] = '<a href="'.add_query_arg('d4pbbaction', 'delete', $url).'">'.__("delete", "gd-bbpress-attachments").'</a>';
                        }

                        if ($allow == 'detach' || $allow == 'both') {
                            $actions[] = '<a href="'.add_query_arg('d4pbbaction', 'detach', $url).'">'.__("detach", "gd-bbpress-attachments").'</a>';
                        }
                        
                        if (count($actions) > 0) {
                            $actions = ' ['.join(' | ', $actions).']';
                        } else {
                            $actions = '';
                        }

                        $file = get_attached_file($attachment->ID);
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        $filename = pathinfo($file, PATHINFO_BASENAME);
                        $file_url = wp_get_attachment_url($attachment->ID);

                        $html = $class_li = $class_a = $rel_a = "";
                        $a_title = $filename;
                        $caption = false;

                        $img = false;
                        if (d4p_bba_o('image_thumbnail_active') == 1) {
                            $html = wp_get_attachment_image($attachment->ID, 'd4p-bbp-thumb');

                            if ($html != "") {
                                $img = true;

                                $class_li = 'bbp-atthumb';

                                if (d4p_bba_o('image_thumbnail_inline') == 1) {
                                    $class_li.= ' bbp-inline';
                                }

                                $class_a = d4p_bba_o('image_thumbnail_css');
                                $caption = d4p_bba_o('image_thumbnail_caption') == 1;

                                $rel_a = ' rel="'.d4p_bba_o('image_thumbnail_rel').'"';
                                $rel_a = str_replace('%ID%', $id, $rel_a);
                                $rel_a = str_replace('%TOPIC%', bbp_get_topic_id(), $rel_a);
                            }
                        }

                        if ($html == '') {
                            $html = $filename;

                            if (d4p_bba_o("attchment_icons") == 1) {
                                $class_li = "bbp-atticon bbp-atticon-".$this->icon($ext);
                            }
                        }

                        $item = '<li id="d4p-bbp-attachment_'.$attachment->ID.'" class="d4p-bbp-attachment d4p-bbp-attachment-'.$ext.' '.$class_li.'">';

                        if ($caption) {
                            $item.= '<div style="width: '.d4p_bba_o("image_thumbnail_size_x").'px" class="wp-caption">';
                        }

                        $item.= '<a class="'.$class_a.'"'.$rel_a.' href="'.$file_url.'" title="'.$a_title.'">'.$html.'</a>';

                        if ($caption) {
                            $item.= '<p class="wp-caption-text">'.$a_title.'<br/>'.$actions.'</p></div>';
                        } else {
                            $item.= $actions;
                        }

                        $item.= '</li>';

                        if ($img) {
                            $thumbnails.= $item;
                            $images++;
                        } else {
                            $listing.= $item;
                            $files++;
                        }
                    }

                    $thumbnails.= '</ol>';
                    $listing.= '</ol>';
                    
                    if ($images > 0) {
                        $content.= $thumbnails;
                    }

                    if ($files > 0) {
                        $content.= $listing;
                    }
                }
            }

            $content.= '</div>';
        }

        if ((d4p_bba_o('errors_visible_to_author') == 1 && $author_id == $user_ID) || (d4p_bba_o('errors_visible_to_admins') == 1 && d4p_is_user_admin()) || (d4p_bba_o('errors_visible_to_moderators') == 1 && d4p_is_user_moderator())) {
            $errors = get_post_meta($id, '_bbp_attachment_upload_error');

            if (!empty($errors)) {
                $content.= '<div class="bbp-attachments-errors">';
                $content.= '<h6>'.__("Upload Errors", "gd-bbpress-attachments").':</h6>';
                $content.= '<ol>';

                foreach ($errors as $error) {
                    $content.= '<li><strong>'.$error['file'].'</strong>: '.__($error['message'], "gd-bbpress-attachments").'</li>';
                }

                $content.= '</ol></div>';
            }
        }

        return $content;
    }

    public function embed_form() {
        global $gdbbpress_attachments;

        $can_upload = apply_filters('d4p_bbpressattchment_allow_upload', $gdbbpress_attachments->is_user_allowed(), bbp_get_forum_id());
        if (!$can_upload) return;

        $is_enabled = apply_filters('d4p_bbpressattchment_forum_enabled', $gdbbpress_attachments->enabled_for_forum(), bbp_get_forum_id());
        if (!$is_enabled) return;

        $file_size = apply_filters('d4p_bbpressattchment_max_file_size', $gdbbpress_attachments->get_file_size(), bbp_get_forum_id());

        include(GDBBPRESSATTACHMENTS_PATH.'forms/uploader.php');
    }
}

global $gdbbpress_attachments_front;
$gdbbpress_attachments_front = new gdbbAtt_Front();

?>