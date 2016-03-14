<?php if (isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") { ?>
<div class="updated settings-error" id="setting-error-settings_updated"> 
    <p><strong><?php _e("Settings saved.", "gd-bbpress-attachments"); ?></strong></p>
</div>
<?php } ?>

<form action="" method="post">
    <?php wp_nonce_field("gd-bbpress-attachments"); ?>
    <div class="d4p-settings">
        <h3><?php _e("Global Attachments Settings", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("These settings can be overriden for individual forums.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="max_file_size"><?php _e("Maximum file size", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="text" class="small-text" value="<?php echo $options["max_file_size"]; ?>" id="max_file_size" name="max_file_size" />
                        <span class="description">KB</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="max_to_upload"><?php _e("Maximum files to upload", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="text" class="small-text" value="<?php echo $options["max_to_upload"]; ?>" id="max_to_upload" name="max_to_upload" />
                        <span class="description"><?php _e("For single topic or reply", "gd-bbpress-attachments"); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="hide_from_visitors"><?php _e("Hide list of attachements from visitors", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["hide_from_visitors"] == 1) echo " checked"; ?> name="hide_from_visitors" />
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("Users Upload Restrictions", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("Only users having one of the selected roles will be able to attach files.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e("Allow upload to", "gd-bbpress-attachments") ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e("Allow upload to", "gd-bbpress-attachments"); ?></span></legend>
                            <?php foreach ($_user_roles as $role => $title) { ?>
                            <label for="roles_to_upload_<?php echo $role; ?>">
                                <input type="checkbox" <?php if (!isset($options["roles_to_upload"]) || is_null($options["roles_to_upload"]) || in_array($role, $options["roles_to_upload"])) echo " checked"; ?> value="<?php echo $role; ?>" id="roles_to_upload_<?php echo $role; ?>" name="roles_to_upload[]" />
                                <?php echo $title; ?>
                            </label><br/>
                            <?php } ?>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("Topic and Reply Deleting", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("Select what to do with attachments when topic or reply with attachments is deleted.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e("Attachments Action", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <select name="delete_attachments" class="regular-text">
                            <option value="detach"<?php if ($options["delete_attachments"] == "detach") echo ' selected="selected"'; ?>><?php _e("Leave in media library", "gd-bbpress-attachments"); ?></option>
                            <option value="delete"<?php if ($options["delete_attachments"] == "delete") echo ' selected="selected"'; ?>><?php _e("Delete", "gd-bbpress-attachments"); ?></option>
                            <option value="nohing"<?php if ($options["delete_attachments"] == "nohing") echo ' selected="selected"'; ?>><?php _e("Do nothing", "gd-bbpress-attachments"); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("JavaScript and CSS Settings", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("You can disable including styles and JavaScript by the plugin, if you want to do it some other way.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="include_js"><?php _e("Include JavaScript", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["include_js"] == 1) echo " checked"; ?> name="include_js" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="include_css"><?php _e("Include CSS", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["include_css"] == 1) echo " checked"; ?> name="include_css" />
                    </td>
                </tr>
            </tbody>
        </table>
        <p><?php _e("If you use shortcodes to embed forums, and you rely on plugin to add JS and CSS, you also need to enable this option to skip checking for bbPress specific pages.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="include_always"><?php _e("Always Include", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["include_always"] == 1) echo " checked"; ?> name="include_always" />
                    </td>
                </tr>
            </tbody>
        </table>
        <p><?php _e("Enable this option if you use BuddyPress with bbPress plugin for site wide forums.", "gd-bbpress-attachments"); ?></p>
    </div>
    <div class="d4p-settings-second">
        <h3><?php _e("Error logging", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("Each failed upload will be logged in postmeta table. Administrators and topic/reply authors can see the log.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="log_upload_errors"><?php _e("Activated", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["log_upload_errors"] == 1) echo " checked"; ?> name="log_upload_errors" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="errors_visible_to_admins"><?php _e("Visible to administrators", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["errors_visible_to_admins"] == 1) echo " checked"; ?> name="errors_visible_to_admins" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="errors_visible_to_moderators"><?php _e("Visible to moderators", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["errors_visible_to_moderators"] == 1) echo " checked"; ?> name="errors_visible_to_moderators" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="errors_visible_to_author"><?php _e("Visible to author", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["errors_visible_to_author"] == 1) echo " checked"; ?> name="errors_visible_to_author" />
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("Deleting attachments", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("Once uploaded and attached, attachments can be deleted. Only administrators and authors can do this.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e("Administrators", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <select name="delete_visible_to_admins" class="regular-text">
                            <option value="no"<?php if ($options["delete_visible_to_admins"] == "no") echo ' selected="selected"'; ?>><?php _e("Don't allow to delete", "gd-bbpress-attachments"); ?></option>
                            <option value="delete"<?php if ($options["delete_visible_to_admins"] == "delete") echo ' selected="selected"'; ?>><?php _e("Delete from Media Library", "gd-bbpress-attachments"); ?></option>
                            <option value="detach"<?php if ($options["delete_visible_to_admins"] == "detach") echo ' selected="selected"'; ?>><?php _e("Only detach from topic/reply", "gd-bbpress-attachments"); ?></option>
                            <option value="both"<?php if ($options["delete_visible_to_admins"] == "both") echo ' selected="selected"'; ?>><?php _e("Allow both delete and detach", "gd-bbpress-attachments"); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e("Moderators", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <select name="delete_visible_to_moderators" class="regular-text">
                            <option value="no"<?php if ($options["delete_visible_to_moderators"] == "no") echo ' selected="selected"'; ?>><?php _e("Don't allow to delete", "gd-bbpress-attachments"); ?></option>
                            <option value="delete"<?php if ($options["delete_visible_to_moderators"] == "delete") echo ' selected="selected"'; ?>><?php _e("Delete from Media Library", "gd-bbpress-attachments"); ?></option>
                            <option value="detach"<?php if ($options["delete_visible_to_moderators"] == "detach") echo ' selected="selected"'; ?>><?php _e("Only detach from topic/reply", "gd-bbpress-attachments"); ?></option>
                            <option value="both"<?php if ($options["delete_visible_to_moderators"] == "both") echo ' selected="selected"'; ?>><?php _e("Allow both delete and detach", "gd-bbpress-attachments"); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e("Author", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <select name="delete_visible_to_author" class="regular-text">
                            <option value="no"<?php if ($options["delete_visible_to_author"] == "no") echo ' selected="selected"'; ?>><?php _e("Don't allow to delete", "gd-bbpress-attachments"); ?></option>
                            <option value="delete"<?php if ($options["delete_visible_to_author"] == "delete") echo ' selected="selected"'; ?>><?php _e("Delete from Media Library", "gd-bbpress-attachments"); ?></option>
                            <option value="detach"<?php if ($options["delete_visible_to_author"] == "detach") echo ' selected="selected"'; ?>><?php _e("Only detach from topic/reply", "gd-bbpress-attachments"); ?></option>
                            <option value="both"<?php if ($options["delete_visible_to_author"] == "both") echo ' selected="selected"'; ?>><?php _e("Allow both delete and detach", "gd-bbpress-attachments"); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("Forums Integration", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("With these options you can modify the forums to include attachment elements.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="attachment_icon"><?php _e("Attachment Icon", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["attachment_icon"] == 1) echo " checked"; ?> name="attachment_icon" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="attchment_icons"><?php _e("File Type Icons", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["attchment_icons"] == 1) echo " checked"; ?> name="attchment_icons" />
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("Display of image attachments", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("Attached images can be displayed as thumbnails, and from here you can control this.", "gd-bbpress-attachments"); ?></p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="image_thumbnail_active"><?php _e("Activated", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_active"] == 1) echo " checked"; ?> name="image_thumbnail_active" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="image_thumbnail_caption"><?php _e("With caption", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_caption"] == 1) echo " checked"; ?> name="image_thumbnail_caption" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="image_thumbnail_inline"><?php _e("In line", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="checkbox" <?php if ($options["image_thumbnail_inline"] == 1) echo " checked"; ?> name="image_thumbnail_inline" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="image_thumbnail_css"><?php _e("CSS class", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo $options["image_thumbnail_css"]; ?>" id="image_thumbnail_css" name="image_thumbnail_css" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="image_thumbnail_rel"><?php _e("REL attribute", "gd-bbpress-attachments"); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo $options["image_thumbnail_rel"]; ?>" id="image_thumbnail_rel" name="image_thumbnail_rel" /><br/>
                        <em><?php _e("You can use these tags", "gd-bbpress-attachments"); ?>:<br/>%ID%, %TOPIC%</em>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e("Image thumbnails size", "gd-bbpress-attachments"); ?></h3>
        <p><?php _e("Changing thumbnails size affects only new image attachments. To use new size for old attachments, resize them using", "gd-bbpress-attachments"); ?> <a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a> <?php _e("plugin", "gd-bbpress-attachments"); ?>.</p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="image_thumbnail_size_x"><?php _e("Thumbnail size", "gd-bbpress-attachments"); ?></label></th>
                    <td>x:</td>
                    <td>
                        <input type="text" class="small-text" value="<?php echo $options["image_thumbnail_size_x"]; ?>" id="image_thumbnail_size_x" name="image_thumbnail_size_x" />
                        <span class="description">px</span>
                    </td>
                    <td>y:</td>
                    <td>
                        <input type="text" class="small-text" value="<?php echo $options["image_thumbnail_size_y"]; ?>" id="image_thumbnail_size_y" name="image_thumbnail_size_y" />
                        <span class="description">px</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d4p-clear"></div>
    <p class="submit"><input type="submit" value="<?php _e("Save Changes", "gd-bbpress-attachments"); ?>" class="button-primary" id="gdbb-attach-submit" name="gdbb-attach-submit" /></p>
</form>
