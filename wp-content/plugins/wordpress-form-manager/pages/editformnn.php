<?php
/* translators: the following are from the form's advanced section */

global $wp_roles;

global $fmdb;
global $fm_display;
global $fm_controls;
global $fm_templates;
global $fm_form_behavior_types;

global $fm_DEBUG;
global $fm_MEMBERS_EXISTS;

$form = null;
if($_REQUEST['id']!="")
	$form = $fmdb->getForm($_REQUEST['id']);

$metaForm = $fmdb->getForm( $_REQUEST['id'], 1 );

/////////////////////////////////////////////////////////////////////////////////////
// Process settings changes

if(isset($_POST['fm-form-id'])){
	
	$formInfo = array();	
	
	$formInfo['items'] = $form['items'];
	foreach($form['items'] as $index => $item){
		$formInfo['items'][$index]['nickname'] = sanitize_title($_POST[$item['unique_name'].'-edit-nickname']);		
	}
	$fmdb->updateForm($_POST['fm-form-id'], $formInfo);
	
	foreach($metaForm['items'] as $index => $item){
		$metaForm['items'][$index]['nickname'] = sanitize_title($_POST[$item['unique_name'].'-edit-nickname']);		
	}
	$fmdb->updateForm($_POST['fm-form-id'], $metaForm, 1 );	
		
	$form = $fmdb->getForm($_POST['fm-form-id']);
	$metaForm = $fmdb->getForm( $_REQUEST['id'], 1 );
}


/////////////////////////////////////////////////////////////////////////////////////

$fm_globalSettings = $fmdb->getGlobalSettings();

?>

<form name="fm-main-form" id="fm-main-form" action="" method="post">
<input type="hidden" value="1" name="message" id="message-post" />
<input type="hidden" value="<?php echo $form['ID'];?>" name="fm-form-id" id="fm-form-id" />

<div class="wrap" style="padding-top:15px;">

<div style="float:right;">
<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
<input type="button" name="submit-form-settings" id="submit-form-settings" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" onclick="fm_saveSubmissionMetaItems()" />&nbsp;&nbsp;
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
	
<h3><?php _e("Item Nicknames", 'wordpress-form-manager');?></h3>
<table>
<tr><td colspan="2"><span class="description"><?php _e("Giving a nickname to form items makes it easier to access their information within custom e-mail notifications and templates", 'wordpress-form-manager');?></span></td></tr>
</table>
<br />
<table class="form-table">
<tr><th><strong><?php _e("Item Label", 'wordpress-form-manager');?></strong></th><th><strong><?php _e("Nickname", 'wordpress-form-manager');?></strong></th></tr>
<?php 
foreach($form['items'] as $item){
	helper_text_field($item['unique_name'].'-edit-nickname', htmlspecialchars($item['label']), $item['nickname']);
}
?>
<tr><td><hr /></td><td>&nbsp;</td></tr>
<?php
foreach($metaForm['items'] as $item){
	helper_text_field($item['unique_name'].'-edit-nickname', htmlspecialchars($item['label']), $item['nickname']);
}
?>
</table>

<?php
$form = $metaForm;
?>

<h3><?php _e("Private Fields", 'wordpress-form-manager');?></h3>
<table>
<tr><td colspan="2"><span class="description"><?php _e("Private fields only appear in the submission data table, and can be used to attach extra information to form submissions.", 'wordpress-form-manager');?></span></td></tr>
</table>

<div id="fm-private-fields-container">

	<div id='editorcontainer'>
		<div id="quicktags">
			<div class="fm-editor-controls">			
			<?php
				$types=array();
				foreach($fm_controls as $controlKey=>$controlType){
					if($controlKey != 'default' && $controlType->isSubmissionMeta())
						$types[]="<a class=\"edit-form-button\" onclick=\"fm_addItem('{$controlKey}')\">".$controlType->getTypeLabel()."</a>";
				}
				echo implode(" | \n", $types);
			?>			
		</div>
		<div class="fm-editor">		
			
			<ul id="form-list">
			<?php foreach($form['items'] as $item): ?>
			<?php	echo "<li class=\"edit-form-menu-item postbox\" id=\"".$item['unique_name']."\">".$fm_display->getEditorItem($item['unique_name'], $item['type'], $item, true)."</li>\n"; ?>
			<?php endforeach; ?>	
			</ul>
		</div>
	</div>	
	
	<script type="text/javascript">	
	fm_initEditor();
	</script>	
</div>

<p class="submit">
<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
<input type="button" name="submit-form-settings" id="submit-form-settings" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" onclick="fm_saveSubmissionMetaItems()" />
</p>

</div>

</form>