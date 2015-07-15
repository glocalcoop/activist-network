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

global $fm_dataPageSettings;
$fm_dataPageSettings = $fmdb->getDataPageSettings($form['ID']);

$fm_dateRangeOptions = array(
	'all' => _x('All', 'date-range', 'wordpress-form-manager'),
	'month' => _x('This month', 'date-range', 'wordpress-form-manager'),
	'week' => _x('Past 7 days', 'date-range', 'wordpress-form-manager'),
	'today' => _x('Today', 'date-range', 'wordpress-form-manager'),
	'other' => _x('Range...', 'date-range', 'wordpress-form-manager')
	);

/////////////////////////////////////////////////////////////////////////////////////////////
/// HOOK ////////////////////////////////////////////////////////////////////////////////////

do_action( 'fm_data_page_head' );

/////////////////////////////////////////////////////////////////////////////////////////////

function fm_colSelect($name, $cols, $selected = NULL){
	if($selected === NULL)
		$selected = $_POST[$name];
?><select name="<?php echo $name;?>" id="<?php echo $name;?>">
		<?php foreach ( $cols as $col ): ?>
			<?php if(!$col['hidden']): ?>
				<option value="<?php echo $col['key'];?>" <?php if($selected == $col['key']) echo 'selected="selected"';?>>
					<?php echo $col['value'];?>
				</option>
			<?php endif; ?>
		<?php endforeach; ?>
	</select><?php
}

function fm_getSafeColKey($var, $cols){
	foreach($cols as $col)
		if($col['key'] == $var) 
			return $var;
	return false;
}

function outputTableHead($cols){
	global $fm_rowIndex;
	global $fm_MEMBERS_EXISTS;
	$fm_rowIndex = 0;	
	?>	
		<thead>
			<tr>
			<th class="manage-column column-cb check-column"><input type="checkbox" id="cb-col-top" onchange="fm_dataCBColChange()"/></th>
			<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_summary')): ?>
				<th class="fm-data-actions-col">&nbsp</th>
			<?php endif; ?>
	<?php foreach($cols as $col): ?>
		<?php if( fm_userCanViewCol($col) ):?>
			<?php if(!isset($col['attributes'])): ?>
				<th><?php echo $col['value'];?></th>
			<?php else: ?>
				<th <?php foreach($col['attributes'] as $att=>$val): echo $att.'="'.$val.'" '; endforeach; ?>><?php echo $col['value'];?></th>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach;?>
			</tr>
		</thead>
	<?php
}

function outputTableFoot($cols){
	global $fm_rowIndex;
	global $fm_MEMBERS_EXISTS;
	?>	
		<tfoot>
			<tr>
			<th class="manage-column column-cb check-column"><input type="checkbox" id="cb-col-top" onchange="fm_dataCBColChange()"/></th>
			<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_summary')): ?>
				<th>&nbsp</th>
			<?php endif; ?>
	<?php foreach($cols as $col): ?>
		<?php if( fm_userCanViewCol($col) ):?>
			<?php if(!isset($col['attributes'])): ?>
				<th><?php echo $col['value'];?></th>
			<?php else: ?>
				<th <?php foreach($col['attributes'] as $att=>$val): echo $att.'="'.$val.'" '; endforeach; ?>><?php echo $col['value'];?></th>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach;?>
			</tr>
		</tfoot>
		<input type="hidden" name="fm-num-rows" id="fm-num-rows" value="<?php echo $fm_rowIndex;?>" />
	<?php
}
function fm_echoDataTableRow($cols, $dbRow, &$form){
	global $fm_controls;
	global $fm_rowIndex;
	global $fm_MEMBERS_EXISTS;
	?>
	<tr>
		<td>
			<input type="checkbox" name="cb-<?php echo $dbRow['unique_id'];?>" id="cb-<?php echo $dbRow['unique_id'];?>" />
			<input type="hidden" name="cb-<?php echo $fm_rowIndex; ?>" id="cb-<?php echo $fm_rowIndex; ?>" value="<?php echo $dbRow['unique_id'];?>" />
		</td>
		<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_summary')): ?>
			<td>
				<a href="<?php echo get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=datasingle&id=".$form['ID']."&sub=".$dbRow['unique_id'];?>"><?php _e("View", 'wordpress-form-manager');?></a>
			</td>
		<?php endif; ?>
		<?php foreach($cols as $col): ?>
			<?php if( fm_userCanViewCol($col) ):?>
				<?php if(isset($col['show-callback'])): ?>
					<td><?php echo $col['show-callback']($col, $dbRow);?></td>
				<?php elseif(isset($col['item'])): ?>
					<td><?php echo $fm_controls[$col['item']['type']]->parseData($col['key'], $col['item'], $dbRow[$col['key']]);?></td>
				<?php else: ?>
					<td><?php echo $dbRow[$col['key']]; ?></td>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</tr>
	<?php
	
	$fm_rowIndex++;
}

function fm_echoDataTableRowEdit($cols, $dbRow){
	global $fm_controls;
	global $fm_rowIndex;
	global $fm_MEMBERS_EXISTS;
	?>
	<tr>
		<td>
			<input type="hidden" name="cb-<?php echo $dbRow['unique_id'];?>" id="cb-<?php echo $dbRow['unique_id'];?>" value="edit" />
			<input type="hidden" name="cb-<?php echo $fm_rowIndex; ?>" id="cb-<?php echo $fm_rowIndex; ?>" value="<?php echo $dbRow['unique_id'];?>" />
		</td>
		<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_summary')): ?>
			<td>&nbsp;</td>
		<?php endif; ?>
		<?php foreach($cols as $col): ?>
			<?php if( fm_userCanViewCol($col) ):?>
				<?php if(isset($col['show-callback'])): ?>
					<td><?php echo $col['show-callback']($col, $dbRow);?></td>
				<?php elseif(isset($col['item']) && $col['editable']): ?>
					<?php if( fm_userCanEditCol($col) ): ?>
						<td><?php
						$item = $col['item'];					
						$item['extra']['value'] = $dbRow[$col['key']];
						
						// a special exception for how we display file elements
						if($item['type'] == 'file')
							echo $fm_controls[$col['item']['type']]->parseData($col['key'], $col['item'], $dbRow[$col['key']])."<br />";
							
						echo $fm_controls[$item['type']]->showItemSimple($dbRow['unique_id'].'-'.$item['unique_name'], $item);
						?></td>
					<?php else: ?>
						<td><?php echo $fm_controls[$col['item']['type']]->parseData($col['key'], $col['item'], $dbRow[$col['key']]);?></td>
					<?php endif; ?>
				<?php else: ?>
					<td><?php echo $dbRow[$col['key']]; ?></td>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</tr>
	<?php
	
	$fm_rowIndex++;
}

////////////////////////////////////////////////////////////////////////////////////////

$cols = fm_getDefaultDataCols();

fm_dataBuildTableCols($form, $subMetaFields, $cols);

$fm_notEditable = array();
foreach($cols as $col){
	if(!$col['editable']) $fm_notEditable[] = $col['key'];
}

////////////////////////////////////////////////////////////////////////////////////////

/// data page options

if(isset($_POST['submit-col-options'])){
	$hide=array();
	$noedit=array();
	$caps=array();
	$showcaps=array();
	$nosummary=array();
	foreach($cols as $col){
		if(!isset($_POST['fm-show-'.$col['key']]))
			$hide[] = $col['key'];
		if(!isset($_POST['fm-edit-'.$col['key']]))
			$noedit[] = $col['key'];
		if(isset($_POST['fm-edit-'.$col['key'].'-capability']))
			$caps[$col['key']] = stripslashes($_POST['fm-edit-'.$col['key'].'-capability']);
		if(isset($_POST['fm-show-'.$col['key'].'-capability']))
			$showcaps[$col['key']] = stripslashes($_POST['fm-show-'.$col['key'].'-capability']);
		if(!isset($_POST['fm-show-'.$col['key'].'-summary']))
			$nosummary[] = $col['key'];
	}
	
	$fm_dataPageSettings['hide'] = $hide;
	$fm_dataPageSettings['noedit'] = $noedit;
	$fm_dataPageSettings['nosummary'] = $nosummary;

	if($fm_MEMBERS_EXISTS){
		$fm_dataPageSettings['edit_capabilities'] = $caps;
		$fm_dataPageSettings['show_capabilities'] = $showcaps;
	}
	
	update_option('fm-ds-'.$form['ID'], $fm_dataPageSettings);
}


// update the date range box and other search options
// (this value is always posted by the data page)
if(isset($_POST['fm-data-date-range'])){
	$date = array(
		'range' => $_POST['fm-data-date-range'],
		'start' => $_POST['fm-data-date-start'],
		'end' => $_POST['fm-data-date-end'],
		);
	
	$search = array(
		'search' => $_POST['fm-data-search'],
		'column' => $_POST['fm-data-search-column'],
	);
	
	$results = array(
		'perpage' => $_POST['fm-data-per-page'],
		'sortby' => $_POST['fm-data-sort-by'],
		'sortorder' => $_POST['fm-data-sort-order'],
	);
	
	$fm_dataPageSettings['date'] = $date;
	$fm_dataPageSettings['search'] = $search;
	$fm_dataPageSettings['results'] = $results;
	
	update_option('fm-ds-'.$form['ID'], $fm_dataPageSettings);
}

// pass the settings on to the $col array

fm_applyColSettings($fm_dataPageSettings, $cols);

/// data edit / delete
$fm_showEditRows = false;

if(isset($_POST['fm-doaction'])){
	$checked = fm_getCheckedItems();
	switch($_POST['fm-action-select']){
		case 'delete':
			foreach($checked as $subID){
				$fmdb->deleteSubmissionDataByID($form['ID'], $subID);
			}
			break;
		case 'delete_all':
			$fmdb->clearSubmissionData($form['ID']);
			break;
		case 'edit':
			$fm_showEditRows = true;
			break;
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////
/// HOOK ////////////////////////////////////////////////////////////////////////////////////

do_action( 'fm_data_process_checked', $checked );

/////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['submit-edit'])){
	$checked = fm_getEditItems();
	foreach($checked as $subID){
		$newData = fm_getEditPost($subID, $cols);
		if(sizeof($newData) > 0)
			$fmdb->updateDataSubmissionRowByID($form['ID'], $subID, $newData);
	}
}

/// build the query
$dataPerPage = isset($_POST['fm-data-per-page']) ? $_POST['fm-data-per-page'] : 30;
$dataSortBy = isset($_POST['fm-data-sort-by']) ? $_POST['fm-data-sort-by'] : 'timestamp';
if(trim($dataSortBy) == "") $dataSortBy = 'timestamp';

$dataSortOrder = $_POST['fm-data-sort-order'] == 'asc' ? 'ASC' : 'DESC';
$dataCurrentPage = isset($_POST['fm-data-current-page']) ? $_POST['fm-data-current-page'] : 1;

$dataQuery = "SELECT `unique_id`, ".fm_getColQueryList( $cols )." FROM `".$form['data_table']."` ";
$allQuery = $dataQuery;

$countQuery = "SELECT COUNT(*) as cnt FROM `".$form['data_table']."` ";

$queryClauses = array();

// search
if(!trim($_POST['fm-data-search']) == ""){
	$colID = fm_getSafeColKey($_POST['fm-data-search-column'], $cols);	
	if($colID !== false)
		$queryClauses[] = $wpdb->prepare("`".$colID."` LIKE %s ", "%".$_POST['fm-data-search']."%");
}

//date range
switch ( $_POST['fm-data-date-range'] ) {
		
	case 'month':
		$queryClauses[] = "MONTH(`timestamp`) = MONTH(CURDATE())";
		$queryMessage = __("Showing data for the current month", 'wordpress-form-manager');
		break;
		
	case 'week':
		$queryClauses[] = "`timestamp` > DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
		$queryMessage = __("Showing data from the past seven days", 'wordpress-form-manager');
		break;

	case 'today':
		$queryClauses[] = "DATE(`timestamp`) = CURDATE()";
		$queryMessage = __("Showing data for today", 'wordpress-form-manager');
		break;
		
	case 'other':
		/*translators: this is a date format. See php.net for more about that. */
		$dateFormat = __('Y-m-d', 'wordpress-form-manager');
		$start = date($dateFormat, strtotime($_POST['fm-data-date-start']));
		$end = (trim($_POST['fm-data-date-end']) == "" ? "CURDATE()" : date($dateFormat, strtotime($_POST['fm-data-date-end'])));
		$queryClauses[] = "DATE(`timestamp`) >= DATE('".$start."') AND DATE(`timestamp`) <= DATE('".$end."')";
		$queryMessage = sprintf(__("Showing data from %s to %s", 'wordpress-form-manager'), $start, $end);
		break;
		
	default:
}

/////////////////////////////////////////////////////////////////////////////////////////////
/// HOOK ////////////////////////////////////////////////////////////////////////////////////

$queryClauses = apply_filters( 'fm_data_query_clauses', $queryClauses );

/////////////////////////////////////////////////////////////////////////////////////////////

if(sizeof($queryClauses) > 0){
	$clauses = " WHERE ".implode(" AND ", $queryClauses);
	$dataQuery .= $clauses;
	$countQuery .= $clauses;
}

//get the full query count
$dataCount = $fmdb->get_results($countQuery);
$dataCount = $dataCount[0]['cnt'];

//sort the results
$dataQuery .= " ORDER BY `".$dataSortBy."` ".$dataSortOrder;

//'search results' query is the data query without the LIMIT clause
$searchQuery = $dataQuery;

//page range
$dataQuery .= " LIMIT ".(($dataCurrentPage-1)*$dataPerPage).", ".$dataPerPage;

$results = $fmdb->get_results( $dataQuery );

//create a CSV download file

if(isset($_POST['submit-download-csv']) && fm_userCanGetCSV()){
	
	$csvQuery = "";
	switch($_POST['fm-data-download-csv-type']){
		case 'all':
			$csvQuery = $allQuery." ORDER BY `timestamp` DESC";
			break;
		case 'allfields':
			if(!$fm_MEMBERS_EXISTS || current_user_can( 'form_manager_data_csv_all' ))
				$csvQuery = "SELECT * FROM `".$form['data_table']."` ORDER BY `timestamp` DESC";
			else
				$csvQuery = $allQuery." ORDER BY `timestamp` DESC";
			break;
		case 'current-search':
			$csvQuery = $searchQuery;
			break;
		case 'current-page':
			$csvQuery = $dataQuery;
			break;
	}
	if($csvQuery != ""){
		/*
		$filename = 'data.csv';
		$fullpath = fm_getTmpPath().$filename;
		$CSVFileURL = fm_getTmpURL().$filename;		
	
		$err = fm_createCSVFile($form['ID'], $csvQuery, $filename);
		if($err != 0) echo '<em>'.$err.'</em>';
		*/
		
		$key = fm_createCSVDownload($form['ID'], $csvQuery);
		$CSVFileURL = WP_PLUGIN_URL.'/wordpress-form-manager/getcsv.php?q='.$key.'&id='.$form['ID'];
	}
}

/// build the page links

$dataNumPages = ceil($dataCount / $dataPerPage);

$dataFirstRow = (($dataCurrentPage-1)*$dataPerPage) + 1;
$dataLastRow = $dataCurrentPage*$dataPerPage;
if($dataLastRow > $dataCount) $dataLastRow = $dataCount;

$dataPageLinks=array();
for($x=1;$x<=$dataNumPages;$x++){
	if($x==$dataCurrentPage)
		$dataPageLinks[] = $x;
	else
		$dataPageLinks[] = '<a class="edit-form-button" onclick="fm_pageLinkClick(\''.$x.'\')">'.$x.'</a>';
}

/////

$bulkActions = array(
	'none' => __("...", 'wordpress-form-manager'),
);
if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_delete_data')){
	$bulkActions['delete'] = __("Delete Selected", 'wordpress-form-manager');
	$bulkActions['delete_all'] = __("Delete All Submission Data", 'wordpress-form-manager');
}
if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_edit_data')){
	$bulkActions['edit'] = __("Edit Selected", 'wordpress-form-manager');
}

/////////////////////////////////////////////////////////////////////////////////////////////
/// HOOK ////////////////////////////////////////////////////////////////////////////////////

$bulkActions = apply_filters( 'fm_data_bulk_actions', $bulkActions );

/////////////////////////////////////////////////////////////////////////////////////////////

?>
<form enctype="multipart/form-data" name="fm-main-form" id="fm-main-form" action="" method="post">
	<input type="hidden" value="<?php echo $form['ID'];?>" name="form-id" id="form-id"/>
	<input type="hidden" value="" name="message" id="message-post" />
	<input type="hidden" value="" name="fm-data-view-sub-id" id="fm-data-view-sub-id" />
	<input type="hidden" value="<?php echo $fm_dataPageSettings['showoptions'];?>" name="fm-data-show-options" id="fm-data-show-options" />
	
	<?php if(isset($_POST['submit-edit'])): ?>
		<div id="message-container">
			<div id="message-success" class="updated"><p><?php _e("Data updated.", 'wordpress-form-manager');?></p></div>
		</div>
	<?php endif; ?>

	<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_options')): ?>
		<div class="fm-data-options-show-btn">
			<a class="edit-form-button" onclick="fm_toggleMoreDataOptions()"><?php _e("Options", 'wordpress-form-manager'); ?></a>
		</div>
		<div id="fm-data-more-options" style="display:none;">			
			<div class="postbox fm-data-options">
				<h3><?php _e("Column Options", 'wordpress-form-manager'); ?></h3>
				<table>
					<tr>					
						<th>&nbsp;</th>
						<th><?php _e("Show", 'wordpress-form-manager');?></th>
						<?php if($fm_MEMBERS_EXISTS) : ?>
							<th><?php _e("Show capability", 'wordpress-form-manager');?></th>
						<?php endif; ?>
						<th><?php _e("Editable", 'wordpress-form-manager');?></th>
						<?php if($fm_MEMBERS_EXISTS) : ?>
							<th><?php _e("Edit capability", 'wordpress-form-manager');?></th>
						<?php endif; ?>
						<th><?php _e("Summary", 'wordpress-form-manager');?></th>						
					</tr>
					
					<?php foreach($cols as $col): ?>
						<tr>
						<td class="field-title"><label for="fm-show-<?php echo $col['key'];?>"><?php echo strip_tags($col['value']);?></label></td>
						<td><input type="checkbox" name="fm-show-<?php echo $col['key'];?>" <?php if(!$col['hidden']) echo 'checked="checked"';?> /></td>
						
						<?php if($fm_MEMBERS_EXISTS):?>
							<td><input type="text" name="fm-show-<?php echo $col['key'];?>-capability" value="<?php echo htmlspecialchars($col['show_capability']);?>" /></td>
						<?php endif; ?>
						
						<?php if(!in_array($col['key'], $fm_notEditable)): ?>
							<td><input type="checkbox" name="fm-edit-<?php echo $col['key'];?>" <?php if($col['editable']) echo 'checked="checked"';?> /></td>
						<?php else: ?>
							<td>&nbsp;</td>
						<?php endif; ?>

						<?php if($fm_MEMBERS_EXISTS):?>
							<?php if(!in_array($col['key'], $fm_notEditable)):?>
								<td><input type="text" name="fm-edit-<?php echo $col['key'];?>-capability" value="<?php echo htmlspecialchars($col['edit_capability']);?>" /></td>
							<?php else: ?>
								<td>&nbsp;</td>
							<?php endif; ?>
						<?php endif; ?>

						<td><input type="checkbox" name="fm-show-<?php echo $col['key'];?>-summary" <?php if(!$col['nosummary']) echo 'checked="checked"';?> /></td>

						</tr>
					<?php endforeach; ?>

				</table>
				<div class="fm-data-option-submit-btn">
					<input type="submit" name="submit-col-options" id="submit-col-options" class="button-primary" value="<?php echo _x("Update", 'date-range', 'wordpress-form-manager');?>" />
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<?php if( fm_userCanGetCSV() ): ?>
		<div class="tablenav" style="float:right; clear:right; padding-right:10px;" >
			<label for="fm-data-download-csv-type"><?php _e("Download Data (.csv)", 'wordpress-form-manager');?>:</label>
			<select name="fm-data-download-csv-type" id="fm-data-download-csv-type" >
				<?php if(!$fm_MEMBERS_EXISTS || current_user_can('form_manager_data_csv_all')): ?>
					<option value="allfields"><?php _e("All entries (including hidden fields)", 'wordpress-form-manager'); ?></option>
				<?php endif; ?>
				<option value="all"><?php _e("All entries", 'wordpress-form-manager');?></option>
				<option value="current-search"><?php _e("Search results (all pages)", 'wordpress-form-manager');?></option>
				<option value="current-page"><?php _e("Search results (current page only)", 'wordpress-form-manager');?></option>				
			</select>
			<input type="submit" name="submit-download-csv" id="submit-download-csv" class="button-primary" value="<?php _e("Download", 'wordpress-form-manager');?>" />
		</div>		
		
		<?php if(!empty($csvQuery)): ?>
			<div class="fm-message" style="float:right; clear:right; margin-right:10px;">
				<a href="<?php echo $CSVFileURL;?>"><?php _e("Click here to download", 'wordpress-form-manager');?></a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	
	<div class="postbox fm-data-options" style="float:right; clear:right;">
		<h3><?php _e("Date Range", 'wordpress-form-manager');?></h3>
		<table>
			<tr>
				<td>
					<label for="fm-data-date-range"><?php echo _x("Show", 'date-range', 'wordpress-form-manager'); ?>:</label>
					<select name="fm-data-date-range" id="fm-data-date-range" >
						<?php foreach($fm_dateRangeOptions as $k=>$v): ?>
							<option value="<?php echo $k;?>" <?php if($k==$_POST['fm-data-date-range']) echo 'selected="selected"';?>><?php echo $v;?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo _x("From", 'date-range', 'wordpress-form-manager');?>: <input type="text" name="fm-data-date-start" id="fm-data-date-start" value="<?php echo htmlspecialchars($_POST['fm-data-date-start']);?>"/></td>
			</tr>
			<tr>
				<td><?php echo _x("To", 'date-range', 'wordpress-form-manager');?>: <input type="text" name="fm-data-date-end" id="fm-data-date-end" value="<?php echo htmlspecialchars($_POST['fm-data-date-end']);?>"/></td>
			</tr>
		</table>
	</div>
	
	<div class="postbox fm-data-options" style="float:right;">
		<h3><?php _e("Search", 'wordpress-form-manager');?></h3>
		<table>
			<tr>
				<td><?php _e("Search for", 'wordpress-form-manager');?>:</td>
				<td><input type="text" name="fm-data-search" id="fm-data-search" value="<?php echo htmlspecialchars($_POST['fm-data-search']); ?>" /></td>
			</tr>
			<tr>
				<td><?php _e("Field", 'wordpress-form-manager');?>:</td>
				<td><?php fm_colSelect('fm-data-search-column', $cols, $_POST['fm-data-search-column']); ?></td>
			</tr>
		</table>
	</div>

	<div class="tablenav" style="float:right; clear:right;">
		<div class="alignleft actions">
			<label for="fm-data-per-page"><?php _e("Results per page", 'wordpress-form-manager'); ?>:</label>
			<input type="text" id="fm-data-per-page" name="fm-data-per-page" value="<?php echo $dataPerPage; ?>"/>
			
			<label for="fm-data-sort-by"><?php _e("Sort by", 'wordpress-form-manager');?>:</label>
			<?php fm_colSelect('fm-data-sort-by', $cols, $_POST['fm-data-sort-by']); ?>
			
			<label for="fm-data-sort-order"><?php _e("Order", 'wordpress-form-manager');?>:</label>
			<select name="fm-data-sort-order" id="fm-data-sort-order">
				<option value="desc"><?php _e("Descending", 'wordpress-form-manager');?></option>
				<option value="asc" <?php if($_POST['fm-data-sort-order']=='asc') echo 'selected="selected"';?>><?php _e("Ascending", 'wordpress-form-manager');?></option>
			</select>
			
			<input type="submit" name="submit-ok" id="submit-ok" class="button secondary" value="<?php _e("Update", 'wordpress-form-manager');?>" />
		</div>
	</div>
	
	<div style="clear:both;"></div>

	<div id="fm-data-basic-actions">
		<?php if ( $fm_showEditRows ): ?>
		<div style="float:right;">
			<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
			<input type="submit" name="submit-edit" id="submit-edit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" />&nbsp;&nbsp;
		</div>
		<?php endif; ?>
	
		<div class="tablenav" style="float:left; clear:none;">			
			<div class="alignleft actions">
				<select name="fm-action-select" id="fm-action-select">
					<?php foreach ( $bulkActions as $value => $label ): ?>
						<option value="<?php echo $value;?>"><?php echo $label;?></option>
					<?php endforeach; ?>
				</select>
				<input type="submit" value="<?php _e("Apply", 'wordpress-form-manager');?>" name="fm-doaction" id="fm-doaction" onclick="return fm_confirmSubmit()" class="button-secondary action" />							
				<script type="text/javascript">
					function fm_confirmSubmit(){
						if(action != '-1') return selected;
						return false;
					}
				</script>
			</div>
		</div>
		
		<div class="tablenav" style="float:right; clear:none;" >
			<div class="fm-data-pagination">
				<input type="hidden" name="fm-data-current-page" id="fm-data-current-page" value="<?php echo $dataCurrentPage;?>" />
				<?php echo sprintf(__("Showing row(s) %s - %s out of %s", 'wordpress-form-manager'), $dataFirstRow, $dataLastRow, $dataCount);?>
				<?php if(sizeof($dataPageLinks)>1) echo '&nbsp;&nbsp;&nbsp;'._x("Pages", 'data-page-select', 'wordpress-form-manager').':&nbsp;'.implode("&nbsp;|&nbsp", $dataPageLinks); ?>
			</div>
		</div>
	</div>
	
	<div style="clear:both;"></div>
	
	<?php if ( $queryMessage != "" ): ?>
		<div class="fm-message">
		<?php echo $queryMessage; ?>
		</div>
	<?php endif; ?>
	
	<div class="wrap">
		<table class="widefat post fixed" style="width:auto;">
			<?php outputTableHead($cols); ?>
			<?php foreach( $results as $row ): ?>
				<?php if($fm_showEditRows && in_array($row['unique_id'], $checked)): ?>
					<?php fm_echoDataTableRowEdit($cols, $row); ?>
				<?php else: ?>
					<?php fm_echoDataTableRow($cols, $row, $form); ?>
				<?php endif; ?>			
			<?php endforeach; ?>
			<?php outputTableFoot($cols); ?>
		</table>
	</div>
	
	<?php if ( $fm_showEditRows ): ?>
	<div style="float:right;">
		<input type="submit" name="cancel" class="button secondary" value="<?php _e("Cancel Changes", 'wordpress-form-manager');?>" />
		<input type="submit" name="submit-edit" id="submit-edit" class="button-primary" value="<?php _e("Save Changes", 'wordpress-form-manager');?>" />&nbsp;&nbsp;
	</div>
	<?php endif; ?>
	
</form>