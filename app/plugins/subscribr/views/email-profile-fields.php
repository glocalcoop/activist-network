<?php
/**
 * email-profile-fields.php
 *
 * @created   10/2/13 9:17 PM
 * @author    Mindshare Studios, Inc.
 * @copyright Copyright (c) 2013
 * @link      http://www.mindsharelabs.com/documentation/
 *
 */

?>

<?php if($this->get_option('enable_html_mail')) : ?>
	<tr>
		<th scope="row"><label><?php echo sprintf(__('Receive HTML Email %s', 'subscribr'), $notifications_label); ?></label></th>
		<td>
			<label for="subscribr-send-html" class="muted">
				<input name="subscribr-send-html" type="checkbox" id="subscribr-send-html" value="1" <?php checked($subscribr_send_html, 1); ?>>
				<?php echo sprintf(__('Receive HTML email %s (uncheck for plain text)', 'subscribr'), $notifications_label); ?>
			</label>
		</td>
	</tr>
<?php endif; ?>
