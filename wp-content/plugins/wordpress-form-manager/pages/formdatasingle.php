<?php

global $wpdb;
global $fmdb;
global $fm_display;
global $fm_controls;

global $fm_SLIMSTAT_EXISTS;
global $fm_MEMBERS_EXISTS;

$queryMessage = "";

$form = null;
if($_REQUEST['id']!="")
	$form = $fmdb->getForm($_REQUEST['id']);

$subMetaFields = $fmdb->getFormItems($_REQUEST['id'], 1);

$dbRow = $fmdb->getSubmissionByID($form['ID'], $_REQUEST['sub']);

global $fm_dataPageSettings;
$fm_dataPageSettings = $fmdb->getDataPageSettings($form['ID']);

////////////////////////////////////////////////////////////////////////////////////////

$cols = fm_getDefaultDataCols();

fm_dataBuildTableCols($form, $subMetaFields, $cols);

fm_applyColSettings($fm_dataPageSettings, $cols);

////////////////////////////////////////////////////////////////////////////////////////

if ( isset($_POST['submit-changes']) && (!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_summary_edit'))) {
	$postData = fm_getEditPost($_REQUEST['sub'], $cols, true);
	if(sizeof($postData) > 0){
		$fmdb->updateDataSubmissionRowByID($form['ID'], $_REQUEST['sub'], $postData);
		$dbRow = $fmdb->getSubmissionByID($form['ID'], $_REQUEST['sub']);
	}
}

$editMode = false;
if ( isset($_POST['submit-edit']) ) $editMode = true;

?>
<form enctype="multipart/form-data" name="fm-main-form" id="fm-main-form" action="" method="post">
	<input type="hidden" value="<?php echo $form['ID'];?>" name="form-id" id="form-id"/>
	<input type="hidden" value="" name="message" id="message-post" />

	<?php if(isset($_POST['submit-changes'])): ?>
		<div id="message-container">
			<div id="message-success" class="updated"><p><?php _e("Data updated.", 'wordpress-form-manager');?></p></div>
		</div>
	<?php endif; ?>

	<div class="wrap" style="padding-top:15px;">

	<div style="float:right;">
		<?php if( $editMode ): ?>
			<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
			<input type="submit" name="submit-changes" id="submit-changes" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" />
		<?php elseif(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_summary_edit')): ?>
			<input type="submit" name="submit-edit" id="submit-changes" class="button secondary" value="<?php _e("Edit", 'wordpress-form-manager');?>" />
		<?php endif; ?>
	</div>

	<h3><?php _e("Summary", 'wordpress-form-manager');?></h3>

	<table class="fm-data-summary-table">

		<?php foreach ( $cols as $col ): ?>
			<?php if( fm_userCanViewCol( $col , true) ):?>
				<tr>
					<?php if(isset($col['item'])): ?>
						<td><strong><?php echo $col['item']['nickname'] == "" ? $col['value'] : $col['item']['nickname'];?><strong></td>
						<?php if( $editMode && fm_userCanEditCol( $col , true)): ?>
							<td><?php
							$item = $col['item'];					
							$item['extra']['value'] = $dbRow[$col['key']];
							echo $fm_controls[$item['type']]->showItemSimple($dbRow['unique_id'].'-'.$item['unique_name'], $item);
							?></td>
						<?php elseif(isset($col['show-callback'])): ?>
							<td><?php echo $col['show-callback']($col, $dbRow);?></td>
						<?php else: ?>
							<td><?php echo $fm_controls[$col['item']['type']]->parseData($col['key'], $col['item'], $dbRow[$col['key']]);?></td>
						<?php endif; ?>
					<?php else: ?>
						<td><strong><?php echo $col['value'];?></strong></td>						
						<?php if(isset($col['show-callback'])): ?>
							<td><?php echo $col['show-callback']($col, $dbRow);?></td>
						<?php else: ?>
							<td><?php echo $dbRow[$col['key']]; ?></td>
						<?php endif; ?>
					<?php endif; ?>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
	</table>

</form>