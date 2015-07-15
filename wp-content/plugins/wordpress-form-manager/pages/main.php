<?php

/* translators: the following are used on the main page, (the one that lists all forms) */

global $fmdb;
global $wpdb;

global $fm_MEMBERS_EXISTS;

$currentDialog = "main";

/////////////////////////////////////////////////////////////////////////////
// PROCESS POST /////////////////////////////////////////////////////////////

//ADD NEW
//for now just add blank forms for 'Add New'
if( (!$fm_MEMBERS_EXISTS || current_user_can('form_manager_add_forms')) && isset($_POST['fm-add-new']))
	$fmdb->createForm(null);
	
	
//APPLY ACTION$wpdb->prefix.get_option('fm-data-table-prefix')
if(isset($_POST['fm-doaction'])){
	//check for 'delete'
	if($_POST['fm-action-select'] == "delete"){
		//get a list of selected IDs
		$fList = $fmdb->getFormList();
		$deleteIds = array();
		foreach($fList as $form){
			if(isset($_POST['fm-checked-'.$form['ID']])) $deleteIds[] = $form['ID'];
		}		
		if(sizeof($deleteIds)>0) $currentDialog = "verify-delete";
	}
}

//SINGLE DELETE
if((!$fm_MEMBERS_EXISTS || current_user_can('form_manager_delete_forms')) && isset($_POST['fm-action']) && $_POST['fm-action'] == "delete"){
	$deleteIds = array();
	$deleteIds[0] = $_POST['fm-id'];
	$currentDialog = "verify-delete";
}

//VERIFY DELETE
if((!$fm_MEMBERS_EXISTS || current_user_can('form_manager_delete_forms')) && isset($_POST['fm-delete-yes'])){
	$index=0;
	while(isset($_POST['fm-delete-id-'.$index])){
		$fmdb->deleteForm($_POST['fm-delete-id-'.$index]);
		$index++;
	}
}

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// DISPLAY UI

$formList = $fmdb->getFormList();

?>

<?php

/////////////////////////////////////////////////////////////////////////////
// FORM EDITOR //////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// VERIFY DELETE ////////////////////////////////////////////////////////////

if($currentDialog == "verify-delete"):?>
<form name="fm-main-form" id="fm-main-form" action="" method="post">
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div>
	<h2 style="margin-bottom:20px"><?php _e("Forms", 'wordpress-form-manager');?></h2>
	<div class="form-wrap">
		<h3><?php _e("Are you sure you want to delete:", 'wordpress-form-manager');?> </h3>
	
		<ul style="list-style-type:disc;margin-left:30px;">
		<?php
		foreach($formList as $form){
			if(in_array($form['ID'], $deleteIds, true)){
			echo "<li>".$form['title']."</li>";	
			}
		}
		?>
		</ul>
		
		<br />
		<?php $index=0; foreach($deleteIds as $id): ?>
			<input type="hidden" value="<?php echo $id;?>" name="fm-delete-id-<?php echo $index++;?>" />
		<?php endforeach; ?>
		<input type="submit" value="<?php _e("Yes", 'wordpress-form-manager');?>" name="fm-delete-yes" />
		<input type="submit" value="<?php _e("Cancel", 'wordpress-form-manager');?>" name="fm-delete-cancel"  />
	</div>
</div>
</form>
<?php

/////////////////////////////////////////////////////////////////////////////
// MAIN EDITOR //////////////////////////////////////////////////////////////

else: ?>

<?php if(get_option('fm-last-version') == '1.5.29'): ?>
	<div class="fm-message">
	<?php echo _x("The 'Submission Data' page has changed. You may want to review permissions if you are using the 'Members' plugin.", 'upgrade-notice', 'wordpress-form-manager');?>
	</div>
<?php endif; ?>

<form name="fm-main-form" id="fm-main-form" action="" method="post">
	<div class="wrap">
		<div id="icon-edit-pages" class="icon32"></div>
		
		<h2 style="margin-bottom:20px"><?php _e("Forms", 'wordpress-form-manager');?>
		<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_add_forms')): ?>
		<input type="submit" class="button-secondary" name="fm-add-new" value="<?php _e("Add New", 'wordpress-form-manager');?>" />
		<?php endif; ?>
		</h2>
		<?php if(sizeof($formList)>0): ?>
		<div class="tablenav">
		
			<div class="alignleft actions">
				<select name="fm-action-select">
				<option value="-1" selected="selected"><?php _e("Bulk Actions", 'wordpress-form-manager');?></option>
				<option value="delete"><?php _e("Delete", 'wordpress-form-manager');?></option>
				</select>
				<input type="submit" value="<?php _e("Apply", 'wordpress-form-manager');?>" name="fm-doaction" id="fm-doaction" class="button-secondary action" />
			</div>
				
			<div class="clear"></div>
		</div>		

		<table class="widefat post fixed">
			<thead>
			<tr>
				<th scope="col" class="manage-column column-cb check-column">&nbsp;</th>
				<th><?php _e("Name", 'wordpress-form-manager');?></th>
				<th><?php _e("Slug", 'wordpress-form-manager');?></th>
				<th><?php _e("Submission count", 'wordpress-form-manager');?></th>
				<th><?php _e("Last Submission", 'wordpress-form-manager');?></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th scope="col" class="manage-column column-cb check-column">&nbsp;</th>
				<th><?php _e("Name", 'wordpress-form-manager');?></th>
				<th><?php _e("Slug", 'wordpress-form-manager');?></th>
				<th><?php _e("Submission count", 'wordpress-form-manager');?></th>
				<th><?php _e("Last Submission", 'wordpress-form-manager');?></th>
			</tr>
			</tfoot>
			<?php	 foreach($formList as $form): ?>
				<tr class="alternate author-self status-publish iedit">
					<td><input type="checkbox" name="fm-checked-<?php echo $form['ID'];?>"/></td>
					<td class="post-title column-title">
						<strong><a class="row-title" href="<?php echo get_admin_url(null, 'admin.php')."?page=fm-edit-form&id=".$form['ID'];?>"><?php echo $form['title'];?></a></strong>						
						<div class="row-actions">
						<?php if(!$fm_MEMBERS_EXISTS): ?>
							<span class='edit'>
							<a href="<?php echo get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=design&id=".$form['ID'];?>" title="<?php _e("Edit this form", 'wordpress-form-manager');?>"><?php _e("Edit", 'wordpress-form-manager');?></a> | 
							<a href="<?php echo get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=advanced&id=".$form['ID'];?>" title="<?php _e("Advanced form settings", 'wordpress-form-manager');?>"><?php _e("Advanced", 'wordpress-form-manager');?></a> | 
							<a href="<?php echo get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=data&id=".$form['ID'];?>" title="<?php _e("View form data", 'wordpress-form-manager');?>"><?php _e("Data", 'wordpress-form-manager');?></a> | 
							<a href="#" title="<?php _e("Delete this form", 'wordpress-form-manager');?>" onClick="fm_deleteFormClick('<?php echo $form['ID'];?>');return false"><?php _e("Delete", 'wordpress-form-manager');?></a>
							</span>
						<?php else: ?>
							<span class='edit'>
							<?php $editOptions = array(); ?>
							<?php 
							if(current_user_can('form_manager_forms'))
								$editOptions[] = "<a href=\"".get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=design&id=".$form['ID']."\" title=\"".__("Edit this form", 'wordpress-form-manager')."\">".__("Edit", 'wordpress-form-manager')."</a>";
							if(current_user_can('form_manager_forms_advanced'))
								$editOptions[] = "<a href=\"".get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=advanced&id=".$form['ID']."\" title=\"".__("Advanced form settings", 'wordpress-form-manager')."\">".__("Advanced", 'wordpress-form-manager')."</a>";							
							if(current_user_can('form_manager_data'))
								$editOptions[] = "<a href=\"".get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=data&id=".$form['ID']."\" title=\"".__("View form data", 'wordpress-form-manager')."\">".__("Data", 'wordpress-form-manager')."</a>";
							if(current_user_can('form_manager_delete_forms'))
								$editOptions[] = "<a href=\"#\" title=\"".__("Delete this form", 'wordpress-form-manager')."\" onClick=\"fm_deleteFormClick('".$form['ID']."');return false\">".__("Delete", 'wordpress-form-manager')."</a>";
								
							echo implode("&nbsp;|&nbsp;", $editOptions);
							?>
							</span>
						</div>
						<?php endif; ?>
					</td>
					<td><?php echo $form['shortcode'];?></td>
					<td><?php echo $fmdb->getSubmissionDataNumRows($form['ID']);?></td>
					<td><?php $sub = $fmdb->getLastSubmission($form['ID']); echo $sub['timestamp'];?></td>
				</tr>
			<?php endforeach; ?>			
			<input type="hidden" value="" id="fm-action" name="fm-action"/>
			<input type="hidden" value="" id="fm-id" name="fm-id"/>
		</table>	
	<?php else: ?><?php /* translators: the following is displayed if there are no forms to list */ _e(" No forms yet...", 'wordpress-form-manager');?><?php endif; ?>
	</div>
</form>
<?php endif; //end if main editor ?>