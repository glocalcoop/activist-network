<?php 
/* translators: the following are from the advanced advanced settings page (for the plugin) */

global $fmdb;
global $fm_globalSettings;
global $fm_templates;

global $fm_MEMBERS_EXISTS;

/////////////////////////////////////////////////////////////////////////////////////
// Process settings changes

if(isset($_POST['submit-settings'])){
	
	////////////////////////////////////////////////////////////////////////////////////
	//Process validators
	$count = 0;
	$validators = array();
	for($x=0;$x<$_POST['validator-list-count'];$x++){
		if(isset($_POST['validator-list-item-'.$x.'-name'])){
			$val = array();
			$val['name'] = $_POST['validator-list-item-'.$x.'-name'];
			$val['label'] = stripslashes($_POST['validator-list-item-'.$x.'-label']);
			$val['message'] = stripslashes($_POST['validator-list-item-'.$x.'-message']);
			$val['regexp'] = stripslashes($_POST['validator-list-item-'.$x.'-regexp']);
			
			if($val['name'] == "")
				$val['name'] = 'validator-'.$x;
				
			$validators[$val['name']] = $val;
		}		
	}
	
	$fmdb->setTextValidators($validators);
	
	////////////////////////////////////////////////////////////////////////////////////
	//Process shortcode
	
	$newShortcode = sanitize_title($_POST['shortcode']);
	$oldShortcode = get_option('fm-shortcode');
	if($newShortcode != $oldShortcode){
		remove_shortcode($oldShortcode);	
		update_option('fm-shortcode', $newShortcode);
		add_shortcode($newShortcode, 'fm_shortcodeHandler');
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	//Process template settings
	
	$fmdb->setGlobalSetting('template_form', stripslashes($_POST['template_form']));
	$fmdb->setGlobalSetting('template_email', stripslashes($_POST['template_email']));
	$fmdb->setGlobalSetting('template_summary', stripslashes($_POST['template_summary']));
	
	////////////////////////////////////////////////////////////////////////////////////
	//Other
	
	update_option('fm-enable-mce-button', $_POST['enable_mce_button']?"YES":"");
	update_option('fm-file-method', $_POST['file_method']);
	update_option('fm-file-name-format', $_POST['file_name_format']);
	update_option('fm-email-send-method', $_POST['email_send_method']);
	update_option('fm-allowed-tags', $_POST['fm-allowed-tags']);
	update_option('fm-strip-tags', $_POST['fm-strip-tags']?"YES":"");
	update_option('fm-nonce-check', $_POST['fm-nonce-check']?"YES":"");
	update_option('fm-shortcode-scripts', $_POST['fm-shortcode-scripts']?"YES":"");
	update_option('fm-disable-css', $_POST['fm-disable-css']?"YES":"");
	update_option('fm-disable-cache', $_POST['fm-disable-cache']?"YES":"");
	
}
elseif(isset($_POST['remove-template'])){
	$fm_templates->removeTemplate($_POST['remove-template-filename']);	
}
else if(isset($_POST['reset-templates'])){
	$fm_templates->resetTemplates();
}
else if(isset($_POST['check-db'])){
	echo '<pre>';
	$fmdb->consistencyCheck();
	echo '</pre>';
	die();
}

/////////////////////////////////////////////////////////////////////////////////////
$fm_globalSettings = $fmdb->getGlobalSettings();

/////////////////////////////////////////////////////////////////////////////////////
// Load the templates

$templateList = $fm_templates->getTemplateFilesByType();
$templateFiles = $fm_templates->getTemplateList();

?>
<script type="text/javascript">
function fm_saveSettingsAdvanced(){
	document.getElementById('validator-list-count').value = fm_getManagedListCount('validator-list');
	return true;
}

function fm_submitRemoveTemplate(templateName, templateFile){
	document.getElementById('remove-template-filename').value = templateFile;
	return confirm("<?php /* translators: this will be followed by the name of the template to be removed */ _e("Are you sure you want to remove", 'wordpress-form-manager');?> '" + templateName + "' ?");
}

function fm_resetTemplatesSubmit(){
	return confirm("<?php _e("Are you sure? All templates other than the default will be removed.", 'wordpress-form-manager');?>");
}

/***************************************************************/

var fm_managedLists = [];
function fm_createManagedList(_ulID, _callback, _liClass){	
	var data = {
		ulID: _ulID,
		count: 0,
		createCallback: _callback,
		liClass: _liClass
	};
	fm_managedLists[_ulID] = data;
}
function fm_addManagedListItem(ulID, val){
	var UL = document.getElementById(ulID);	
	var newLI = document.createElement('li');
	var newItemID = ulID + '-item-' + fm_managedLists[ulID].count;	
	eval("var HTML = " + fm_managedLists[ulID].createCallback + "('" + ulID + "', '" + newItemID + "', val);");
	newLI.innerHTML = HTML;
	newLI.id = newItemID;
	newLI.className = fm_managedLists[ulID].liClass;
	UL.appendChild(newLI);
	fm_managedLists[ulID].count++;	
}
function fm_removeManagedListItem(itemID){
	var listItem = document.getElementById(itemID);
	listItem.parentNode.removeChild(listItem);
}
function fm_getManagedListCount(ulID){
	return fm_managedLists[ulID].count;
}

/***************************************************************/
</script>

<form name="fm-main-form" id="fm-main-form" action="" method="post">
<input type="hidden" value="1" name="message" id="message-post" />
<input type="hidden" name="validator-list-count" id="validator-list-count" value="" />

<div class="wrap">
<div id="icon-edit-pages" class="icon32"></div>
<h2><?php _e("Form Manager Settings - Advanced", 'wordpress-form-manager');?></h2>

	<div id="message-container"><?php 
	if(isset($_POST['message']) && isset($_POST['submit-settings']))
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

<h3><?php _e("Text Validators", 'wordpress-form-manager');?></h3>
<table class="form-table">
	<table border=0>
	<tr>
		<td style="width:200px;"><strong><?php _e("Name", 'wordpress-form-manager');?></strong></td>
		<td style="width:200px;"><strong><?php _e("Error Message", 'wordpress-form-manager');?></strong></td>
		<td style="width:400px;"><strong><?php _e("Regular Expression", 'wordpress-form-manager');?></strong></td>
	</tr>
	</table>
	<ul id="validator-list" style="padding-bottom:20px;">		
	</ul>
	<script type="text/javascript" >	
	fm_createManagedList('validator-list', 'fm_new_validator', '');
	
	function fm_new_validator(ulID, itemID, value){
		var str = "<input type=\"text\" value=\"" + value.label + "\" name=\"" + itemID + "-label\" style=\"width:200px;\"/>" +
				"<input type=\"text\" value=\"" + value.message + "\" name=\"" + itemID + "-message\" style=\"width:200px;\" />" + 
				"<input type=\"text\" value=\"" + value.regexp + "\" name=\"" + itemID + "-regexp\" style=\"width:400px;\" />" + 
				"<input type=\"hidden\" value=\"" + value.name + "\" name=\"" + itemID + "-name\" />" +
				"&nbsp;&nbsp;<a onclick=\"fm_removeManagedListItem('" + itemID + "')\" style=\"cursor: pointer\"><?php _e("delete", 'wordpress-form-manager');?></a>";
		if(value.msg != "")
			str = str + '<br /><div style="color:#f00;">' + value.msg + '</div>';
		return str;
	}
	<?php 
	$validators = $fmdb->getTextValidators();	
	foreach($validators as $val){
		$val['label'] = htmlspecialchars(addslashes($val['label']));
		$val['message'] = htmlspecialchars(addslashes($val['message']));
		$val['regexp'] = htmlspecialchars(addslashes($val['regexp']));
		
		//test to see if the regexp is valid (at least for PHP)
		$str = $val['regexp'];
		$msg = "";
		if(@preg_match($str, "foo") === false) $msg = __("The regular expression is invalid.", 'wordpress-form-manager');
		
		echo "var validator = { name: '".$val['name']."', label: '".$val['label']."', message: '".$val['message']."', regexp: '".$val['regexp']."', msg: ".json_encode($msg)." };\n";
		echo "fm_addManagedListItem('validator-list', validator);\n";
	}	
	?>
	var fm_blankItem = { name: "", label: "", message: "", regexp: "" };
	</script>
	</pre>
	<a class="button" onclick="fm_addManagedListItem('validator-list', fm_blankItem)" >Add </a>
</table>
<br />
<br />
<h3><?php _e("Shortcode", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_text_field('shortcode', __("Plugin Shortcode", 'wordpress-form-manager'), get_option('fm-shortcode')); ?>
</table>

<h3><?php _e("Display Templates", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_option_field('template_form', __("Default Form Template", 'wordpress-form-manager'), $templateList['form'], $fm_globalSettings['template_form']); ?>
<?php helper_option_field('template_email', __("Default E-Mail Template", 'wordpress-form-manager'), $templateList['email'], $fm_globalSettings['template_email']); ?>
<?php helper_option_field('template_summary', __("Default Summary Template", 'wordpress-form-manager'), $templateList['summary'], $fm_globalSettings['template_summary']); ?>
</table>
<input type="submit" class="preview button" name="reset-templates" value="<?php _e("Reset Templates", 'wordpress-form-manager');?>" onclick="return fm_resetTemplatesSubmit()" />
<table class="form-table">
<?php foreach($templateFiles as $file=>$template): ?>
<tr>
	<th scope="row"><label style="width:400px;">
	<?php echo $template['template_name'];?> <br /> 
	<?php echo $file; ?>
	</label></th>
<td><input type="submit" name="remove-template" value="<?php _e("Remove", 'wordpress-form-manager');?>"  onclick="return fm_submitRemoveTemplate('<?php echo format_string_for_js($template['template_name']);?>', '<?php echo $file;?>')" /></td></tr>
<?php endforeach; ?>
</table>

<h3><?php _e("Database Check", 'wordpress-form-manager');?></h3>
<table class="form-table">
<tr><th scope="row"><label><?php _e("Check the Form Manager database", 'wordpress-form-manager'); ?>:</label></th><td><input type="submit" name="check-db" class="button secondary" value="<?php _e("Go",'wordpress-form-manager');?>" /></td></tr>
</table>

<h3><?php _e("Post/Page Editor", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('enable_mce_button', __("Enable the editor button", 'wordpress-form-manager'), (get_option('fm-enable-mce-button') == "YES")); ?>
</table>

<?php /*
file_method
*/ ?>

<h3><?php _e("Files", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php $fileMethods = array(
	'auto' => __('auto', 'wordpress-form-manager'),
	'direct' => __('direct', 'wordpress-form-manager'), 
	'ssh' => __('ssh', 'wordpress-form-manager'),
	'ftpext' => __('ftpext', 'wordpress-form-manager'),
	'ftpsockets' => __('ftpsockets', 'wordpress-form-manager'),
);
?>
<?php helper_option_field('file_method', __("Write method", 'wordpress-form-manager'), $fileMethods, get_option('fm-file-method') ); ?>
<?php helper_text_field('file_name_format', __("Default file naming format", 'wordpress-form-manager'), get_option('fm-file-name-format') ); ?>
</table>

<h3><?php _e("E-Mail", 'wordpress-form-manager');?></h3>
<?php $emailMethods = array(
	'wp_mail' => __('WordPress (wp_mail)', 'wordpress-form-manager'),
	'mail' => __('PHP (mail)', 'wordpress-form-manager'),
	'off' => __('None', 'wordpress-form-manager'),
);
?>
<table class="form-table">
<?php helper_option_field('email_send_method', __("Send method", 'wordpress-form-manager'), $emailMethods, get_option('fm-email-send-method') ); ?>
</table>

<h3><?php _e("Content Filtering", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('fm-strip-tags', __("Strip HTML Tags", 'wordpress-form-manager'), (get_option('fm-strip-tags') == "YES"), __("If not enabled, all HTML will be displayed as its literal text.", 'wordpress-form-manager')); ?>
<?php helper_text_field('fm-allowed-tags', __("Allowed HTML Tags", 'wordpress-form-manager'), get_option('fm-allowed-tags'), htmlspecialchars(__("Enter tags including '<' and '>', e.g., \"<a><em><strong><br><hr>\" etc.", 'wordpress-form-manager')));?>
</table>

<h3><?php _e("Security", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('fm-nonce-check', __("Enable nonce check", 'wordpress-form-manager'), (get_option('fm-nonce-check') == "YES")); ?>
</table>

<h3><?php _e("Cache", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('fm-disable-cache', __("Try to disable caching (set DONOTCACHEPAGE) for forms", 'wordpress-form-manager'), (get_option('fm-disable-cache') == "YES")); ?>
</table>

<h3><?php _e("Scripts", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('fm-shortcode-scripts', __("Include scripts in the shortcode (instead of the footer)", 'wordpress-form-manager'), (get_option('fm-shortcode-scripts') == "YES")); ?>
</table>

<h3><?php _e("CSS", 'wordpress-form-manager');?></h3>
<table class="form-table">
<?php helper_checkbox_field('fm-disable-css', __("Disable the default CSS", 'wordpress-form-manager'), (get_option('fm-disable-css') == "YES")); ?>
</table>


<input type="hidden" id="remove-template-filename" name="remove-template-filename" value="" />

</div>

<p class="submit"><input type="submit" name="submit-settings" id="submit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" onclick="return fm_saveSettingsAdvanced()" /></p>
</form>