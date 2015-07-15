<?php
/* translators: the following are from the form's advanced section */

global $fmdb;
global $fm_display;
global $fm_templates;
global $fm_form_behavior_types;

global $fm_DEBUG;
global $fm_MEMBERS_EXISTS;

$form = null;
if($_REQUEST['id']!="")
	$form = $fmdb->getForm($_REQUEST['id']);
	
/////////////////////////////////////////////////////////////////////////////////////
// Process settings changes

if(isset($_POST['submit-form-settings'])){
	$formInfo = array();	
	$formInfo['conditions'] = processConditionsPost();
	
	$fmdb->updateForm($_POST['fm-form-id'], $formInfo);
	
	$form = $fmdb->getForm($_POST['fm-form-id']);
}

//takes the posted info and converts it to the proper associative array structure to be stored in the DB
function processConditionsPost(){
	global $fmdb;
	
	if(strlen($_POST['fm-conditions-ids']) == 0) return false;

	$conditionIDs = explode(",", $_POST['fm-conditions-ids']);
	$condInfo = array();
	
	foreach($conditionIDs as $condID){
		if(substr($condID,0,3) == "new")
			$newCondID = $fmdb->getUniqueItemID('cond');
		else
			$newCondID = $condID;
		$tempInfo = array('rule' => $_POST[$condID.'-rule'], 'id' => $newCondID, 'tests' => array(), 'items' => array());
		$testOrder = explode(",", $_POST[$condID.'-test-order']);
		for($x=0;$x<sizeof($testOrder);$x++){
			$tempInfo['tests'][] = array('test' => $_POST[$condID.'-test-'.$testOrder[$x]],
										'unique_name' => $_POST[$condID.'-test-itemID-'.$testOrder[$x]],
										'val' => stripslashes($_POST[$condID.'-test-val-'.$testOrder[$x]]),
										'connective' => $_POST[$condID.'-test-connective-'.$testOrder[$x]]
									);
		}
		for($x=0;$x<$_POST[$condID.'-item-count'];$x++){
			$temp = $_POST[$condID.'-item-'.$x];
			if($temp != "")
				$tempInfo['items'][] = $temp;
		}
		$condInfo[$newCondID] = $tempInfo;							
	}
	return $condInfo;
}
	
/////////////////////////////////////////////////////////////////////////////////////

$fm_globalSettings = $fmdb->getGlobalSettings();

?>

<form name="fm-main-form" id="fm-main-form" action="" method="post">
<input type="hidden" value="1" name="message" id="message-post" />
<input type="hidden" value="<?php echo $form['ID'];?>" name="fm-form-id" />

<div class="wrap" style="padding-top:15px;">

<div style="float:right;">
<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
<input type="submit" name="submit-form-settings" id="submit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" onclick="return fm_saveConditions();" />&nbsp;&nbsp;

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

<div id="fm-conditions-container">
	<input type="button" onclick="fm_newCondition()" class="button secondary" value="<?php _e("Add", 'wordpress-form-manager');?>" />
	<ul id="fm-conditions">
	
	</ul>
	<input type="hidden" id="fm-conditions-ids" name="fm-conditions-ids" value="" />
</div>
<script type="text/javascript">
<?php 
foreach($form['items'] as $item){
		?>
fm_register_form_item(<?php echo json_encode($item);?>);
		<?php
	
}

if(is_array($form['conditions'])){
	foreach($form['conditions'] as $condition){
?>
fm_addCondition(<?php echo json_encode($condition); ?>);
<?php
	}
}
?>
fm_initConditionEditor();
</script>

<p class="submit">
<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
<input type="submit" name="submit-form-settings" id="submit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>"  onclick="return fm_saveConditions();" />
</p>

</div>

</form>