<div class="bbp-template-notice">
    <p><?php echo __("Maximum file size allowed is", "gd-bbpress-attachments")." ".$file_size." KB."; ?></p>
</div>
<p class="bbp-attachments-form">
    <label for="bbp_topic_tags">
        <?php _e("Attachments", "gd-bbpress-attachments"); ?>:
    </label><br/>
    <input type="file" size="40" name="d4p_attachment[]"><br/>
    <a class="d4p-attachment-addfile" href="#"><?php _e("Add another file", "gd-bbpress-attachments"); ?></a>
</p>