<?php
/* translators: the following are from the form's advanced section */

global $fmdb;
global $fm_display;
global $fm_templates;
global $fm_form_behavior_types;

global $fm_DEBUG;
global $fm_MEMBERS_EXISTS;

$form = null;
if($_REQUEST['id']!=""){
	$form = $fmdb->getForm($_REQUEST['id']);
	$formMeta = $fmdb->getFormItems($_REQUEST['id'],1);
	$allFormItems = array_merge($form['items'], $formMeta);
}
/////////////////////////////////////////////////////////////////////////////////////
// Process settings changes

if(isset($_POST['submit-form-settings'])){
	$formInfo = array();
	
	$formInfo['behaviors'] = $_POST['behaviors'];
	
	$formInfo['form_template'] 			= $_POST['form_template'];
	$formInfo['email_template'] 		= $_POST['email_template'];
	$formInfo['summary_template'] 		= $_POST['summary_template'];
	$formInfo['use_advanced_email'] 	= ($_POST['use_advanced_email']=="on"?1:0);
	$formInfo['advanced_email'] 		= $_POST['advanced_email'];
	$formInfo['publish_post'] 			= ($_POST['publish_post']=="on"?1:0);
	$formInfo['publish_post_category'] 	= $_POST['publish_post_category'];
	$formInfo['publish_post_title'] 	= $_POST['publish_post_title'];
	$formInfo['reg_user_only_msg'] 		= $_POST['reg_user_only_msg'];
	$formInfo['publish_post_status'] 	= $_POST['publish_post_status'];
	$formInfo['summary_hide_empty']		= ($_POST['summary_hide_empty']=="on"?1:0);
	$formInfo['exact_form_action']		= $_POST['exact_form_action'];
	$formInfo['enable_autocomplete']	= ($_POST['enable_autocomplete']=="on"?1:0);
	
	$fmdb->updateForm($_POST['fm-form-id'], $formInfo);
	
	$fmdb->showerr = false;
	$itemTypeErr = array();
	foreach($allFormItems as $item){
		if($fmdb->isDataCol($item['unique_name']) 
			&& $_POST[$item['unique_name']."-dbtype-prev"] != $_POST[$item['unique_name']."-dbtype"]){			
			$fmdb->updateDataType($form['ID'], $item['unique_name'], stripslashes($_POST[$item['unique_name']."-dbtype"]));
			$itemTypeErr[$item['unique_name']] = false;
		}
	}
	$fmdb->showerr = true;
	
	$form = $fmdb->getForm($_REQUEST['id']);
}


// Process an updated form definition
if($fm_DEBUG) $formDef = new fm_form_definition_class(); 
if($fm_DEBUG && isset($_POST['form-definition'])){	
	
	
	$formInfo = $formDef->createFormInfo($_POST['form-definition']);	
	$fmdb->updateForm($_POST['fm-form-id'], $formInfo);
} 
	
$formTemplateFile = $form['form_template'];
	if($formTemplateFile == '') $formTemplateFile = $fmdb->getGlobalSetting('template_form');
	if($formTemplateFile == '') $formTemplateFile = get_option('fm-default-form-template');

$formTemplate = $fm_templates->getTemplateAttributes($formTemplateFile);
$templateList = $fm_templates->getTemplateFilesByType();

/////////////////////////////////////////////////////////////////////////////////////

$fm_globalSettings = $fmdb->getGlobalSettings();

?>
<form name="fm-main-form" id="fm-main-form" action="" method="post">
<input type="hidden" value="1" name="message" id="message-post" />
<input type="hidden" value="<?php echo $form['ID'];?>" name="fm-form-id" />

<div class="wrap">

<div style="float:right;">
<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
<input type="submit" name="submit-form-settings" id="submit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>"  />&nbsp;&nbsp;
</div>

	<div id="message-container"><?php 
	if(isset($_POST['message']) && isset($_POST['submit-form-settings']))
		switch($_POST['message']){
			case 1: ?><div id="message-success" class="updated"><p><strong><?php _e("Settings Saved.", 'wordpress-form-manager');?> </strong></p></div><?php break;
			case 2: ?><div id="message-error" class="error"><p><?php _e("Save failed.", 'wordpress-form-manager');?> </p></div><?php break;
			default: ?>
				<?php if(isset($_POST['message']) && trim($_POST['message']) != ""): ?>
				<div id="message-error" class="error"><p><?php echo stripslashes($_POST['message']);?></p></div>
				<?php endif; ?>
			<?php
		} 
	?></div>

<h3><?php _e("Behavior", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php
$behaviorList = array();
foreach($fm_form_behavior_types as $desc => $val)
	$behaviorList[$val] = $desc;
helper_option_field('behaviors', __("Behavior Type", 'wordpress-form-manager'), $behaviorList, $form['behaviors'], __("Behavior types other than 'Default' require a registered user", 'wordpress-form-manager'));
$msg = empty($formInfo['reg_user_only_msg']) ? $fmdb->getGlobalSetting('reg_user_only_msg') : $form['reg_user_only_msg'];
helper_text_field('reg_user_only_msg', __("Message displayed to unregistered users", 'wordpress-form-manager'), $msg, __("Include '%s' where you would like the form title to appear", 'wordpress-form-manager'));
helper_text_field('exact_form_action', __("Exact URL of destination page", 'wordpress-form-manager'), $form['exact_form_action'], __("This page will be loaded after submitting the form, regardless of the 'behavior' setting", 'wordpress-form-manager'));
helper_checkbox_field('enable_autocomplete', __("Enable autocomplete", 'wordpress-form-manager'), ($form['enable_autocomplete'] == 1));
?>
</table>

<h3><?php _e("Templates", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php 
/* translators: the following apply to the different kinds of templates */
helper_option_field('form_template', __("Form Display", 'wordpress-form-manager'), array_merge(array( '' => __("(use default)", 'wordpress-form-manager')), $templateList['form']), $form['form_template']);
helper_option_field('email_template', __("E-Mail Notifications", 'wordpress-form-manager'), array_merge(array( '' => __("(use default)", 'wordpress-form-manager')), $templateList['email']), $form['email_template']);
helper_option_field('summary_template', __("Data Summary", 'wordpress-form-manager'), array_merge(array( '' => __("(use default)", 'wordpress-form-manager')), $templateList['summary']), $form['summary_template']);
?>
</table>

<h3><?php _e("Summary Fields", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('summary_hide_empty', __("Hide empty fields in summaries", 'wordpress-form-manager'), ($form['summary_hide_empty'] == 1)); ?> 
</table>

<h3><?php _e("Custom E-Mail Notifications", 'wordpress-form-manager');?></h3>
<table>
<tr><td width="350px"><?php _e("Use custom e-mail notifications", 'wordpress-form-manager');?></td><td align="left"><input type="checkbox" name="use_advanced_email" <?php echo ($form['use_advanced_email'] == 1 ? "checked=\"checked\"" : ""); ?> ? /></td></tr>
<tr><td colspan="2"><span class="description"><?php _e("This will override the 'E-Mail Notifications' settings in both the main editor and the plugin settings page with the information entered below", 'wordpress-form-manager');?></span></td></tr>
</table>
<textarea name="advanced_email" rows="15" style="width:80%" ><?php echo $form['advanced_email']; ?></textarea>

<h3><?php _e("Publish Submitted Data", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('publish_post', __("Publish submissions as posts", 'wordpress-form-manager'), ($form['publish_post'] == 1)); ?> 
<tr><th scope="row"><label><?php _e("Post category", 'wordpress-form-manager'); ?></label></th><td><?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'publish_post_category', 'hierarchical' => true, 'selected' => $form['publish_post_category'])); ?></td></tr>
<?php helper_text_field('publish_post_title', __("Post title", 'wordpress-form-manager'), htmlspecialchars($form['publish_post_title'])); ?>
<?php helper_option_field('publish_post_status', __("Publish status", 'wordpress-form-manager'), array( 'publish' => 'Publish', 'draft' => 'Draft' ), $form['publish_post_status'] ); ?>
</table>

<h3><?php _e("Submission Data", 'wordpress-form-manager'); ?></h3>
<table class="form-table">
<?php
foreach($allFormItems as $item){
	if($fmdb->isDataCol($item['unique_name'])){
		$dbType = $fmdb->getDataType($item['unique_name']);
		helper_text_field($item['unique_name']."-dbtype", ($item['nickname'] != "" ? $item['nickname'] : $item['label']), $dbType);
		if(isset($itemTypeErr[$item['unique_name']]) && $itemTypeErr['unique_name'] !== false){
			?>
			<tr><td colspan="2"><em style="color:#FF0000;font-weight:bold;"><?php echo $itemTypeErr[$item['unique_name']];?></em></td></tr>
			<?php
		}
		?>
		<input type="hidden" name="<?php echo $item['unique_name']."-dbtype-prev"; ?>" id="<?php echo $item['unique_name']."-dbtype-prev"; ?>" value="<?php echo htmlspecialchars($dbType); ?>" />
		<?php
	}
}
?>
</table>

<p class="submit">
<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
<input type="submit" name="submit-form-settings" id="submit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager'); ?>"  />
</p>

</div>

</form>

<?php if($fm_DEBUG): ?>
<h3><?php _e("Edit Form Definition:", 'wordpress-form-manager');?></h3>
<form name="fm-definition-form" action="" method="post">
	<input type="hidden" value="<?php echo $form['ID'];?>" name="fm-form-id" />
	<textarea name="form-definition" rows="20" cols="80"><?php echo $formDef->printFormAtts($form['items']); ?></textarea>
	<p class="submit"><input type="submit" name="submit-form-definition" class="button-primary" value="<?php _e("Update Form", 'wordpress-form-manager'); ?>" /></p>
</form>
<?php endif; ?>
