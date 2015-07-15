<?php

/**************************************************************/
/******* AJAX *************************************************/

//post form data
add_action( 'wp_ajax_fm_post_form', 'fm_postFormAjax' );
function fm_postFormAjax() {
	echo fm_doFormBySlug( $_POST[ 'slug' ] );	
	die();
}

//form editor 'save' button
add_action( 'wp_ajax_fm_save_form', 'fm_saveFormAjax' );
global $fm_save_had_error;

function fm_saveFormAjax() {
	global $fmdb;
	global $fm_save_had_error;
	
	$fm_save_had_error = false;
	
	$formInfo = fm_saveHelperGatherFormInfo();
	
	foreach ( $formInfo['items'] as $k=>$item ) {
		$formInfo['items'][$k]['set'] = 0;
	}
	
	//check if the shortcode is a duplicate
	$scID = $fmdb->getFormID( $formInfo[ 'shortcode' ] );
	if( !( $scID == false 
			|| $scID == $_POST[ 'id' ] 
			|| trim( $formInfo[ 'shortcode' ] ) == "" ) 
		) {
		//get the old shortcode
		$formInfo[ 'shortcode' ] = $fmdb->getFormShortcode( $_POST[ 'id' ] );			
		//save the rest of the form
		$fmdb->updateForm( $_POST[ 'id' ], $formInfo );
		
		//now tell the user there was an error
		printf(
			__("Error: the shortcode '%s' is already in use. (other changes were saved successfully)", 'wordpress-form-manager'), 
			$formInfo[ 'shortcode' ]
			);
		
		die();
	}
			
	//no errors: save the form, return '1'
	$fmdb->updateForm( $_POST[ 'id' ], $formInfo );
	
	if(!$fm_save_had_error)
		echo "1";
		
	die();
}

add_action( 'wp_ajax_fm_save_submission_meta', 'fm_saveSubmissionMetaAjax' );

function fm_saveSubmissionMetaAjax() {
	global $fmdb;
	
	$formInfo = array();
	$formInfo['items'] = fm_saveHelperGatherItems();
	
	foreach ( $formInfo['items'] as $k=>$item ) {
		$formInfo['items'][$k]['set'] = 1;
	}
	
	$fmdb->updateForm( $_POST[ 'id' ], $formInfo, 1 );
	echo "1";
	
	die();
}

function fm_saveHelperGatherFormInfo(){
	global $fm_save_had_error;
	
	//collect the posted information
	$formInfo = array();
	$formInfo['title'] = $_POST['title'];
	$formInfo['labels_on_top'] = $_POST['labels_on_top'];
	$formInfo['submitted_msg'] = $_POST['submitted_msg'];
	$formInfo['submit_btn_text'] = $_POST['submit_btn_text'];
	$formInfo['show_title'] = ($_POST['show_title']=="true"?1:0);
	$formInfo['show_border'] = ($_POST['show_border']=="true"?1:0);
	$formInfo['shortcode'] = sanitize_title($_POST['shortcode']);
	$formInfo['label_width'] = $_POST['label_width'];
	$formInfo['required_msg'] = $_POST['required_msg'];
	$formInfo['template_values'] = $_POST['template_values'];	
	$formInfo['show_summary'] = ($_POST['show_summary']=="true"?1:0);
	$formInfo['email_user_field'] = $_POST['email_user_field'];
	$formInfo['email_subject'] = $_POST['email_subject'];
	$formInfo['email_from'] = $_POST['email_from'];
	$formInfo['auto_redirect'] = ($_POST['auto_redirect']=="true"?1:0);
	$formInfo['auto_redirect_page'] = $_POST['auto_redirect_page'];
	$formInfo['auto_redirect_timeout'] = $_POST['auto_redirect_timeout'];
	
	//build the notification email list
	$emailList = explode(",", $_POST['email_list']);
	$valid = true;
	for($x=0;$x<sizeof($emailList);$x++){
		$emailList[$x] = trim($emailList[$x]);		
		if($emailList[$x] != "" && !preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $emailList[$x])){
			$valid = false;
			$x = sizeof($emailList);
		}	
	}
		
	if($valid){
		$temp = array();
		foreach($emailList as $email)
			if($email != "") $temp[] = $email;
		$formInfo['email_list'] = implode(",", $temp);
	}
	else{
		/* translators: this error is given when saving the form, if there was a problem with the list of e-mails under 'E-Mail Notifications'. */
		_e("Error: There was a problem with the notification e-mail list.  Other settings were updated.", 'wordpress-form-manager');
		$fm_save_had_error = true;
	}
		
	//build the items list
	$formInfo['items'] = fm_saveHelperGatherItems();
	
	return $formInfo;
}

function fm_saveHelperGatherItems(){
	$items = array();
	if(isset($_POST['items'])){
		foreach($_POST['items'] as $item){			
			if(!is_serialized($item['extra'])){ //if not a serialized array, hopefully a parseable php array definition..								
				$item['extra'] = stripslashes(stripslashes($item['extra'])); //both javascript and $_POST add slashes
				//make sure the code to be eval'ed is safe (otherwise this would be a serious security risk)
				if(is_valid_array_expr($item['extra']))				
					eval("\$newExtra = ".$item['extra'].";"); 				
				else{
					/* translators: This error occurs if the save script failed for some reason. */
					_e("Error: Save posted an invalid array expression.", 'wordpress-form-manager')."<br />";
					echo $item['extra'];
					die();
				}					
				$item['extra'] = $newExtra;
			}			
			$items[] = $item;			
		}
	}
	
	return $items;
}

//insert a new form item
add_action('wp_ajax_fm_new_item', 'fm_newItemAjax');
function fm_newItemAjax(){
	global $fm_display;
	global $fmdb;
	
	$olderr = error_reporting();
	error_reporting(E_ALL ^ E_NOTICE);
	
	$uniqueName = $fmdb->getUniqueItemID($_POST['type']);

	$str = "{".
		"'html':\"".addslashes(urlencode($fm_display->getEditorItem($uniqueName, $_POST['type'], null)))."\",".
		"'uniqueName':'".$uniqueName."'".
		"}";
	
	echo $str;
	
	error_reporting($olderr);
	
	die();
}

//Use the 'formelements' helpers
add_action('wp_ajax_fm_create_form_element', 'fm_createFormElement');
function fm_createFormElement(){
	//echo "<pre>".print_r($elem,true)."</pre>";
	echo fe_getElementHTML($_POST['elem']);
	die();
}

//Download an uploaded file stored in the database
add_action('wp_ajax_fm_download_file', 'fm_downloadFile');
function fm_downloadFile(){
	global $fmdb;
	
	$tmpDir =  fm_getTmpPath();
	
	$formID = $_POST['id'];
	$itemID = $_POST['itemid'];
	$subID = $_POST['subid'];
	
	$dataRow = $fmdb->getSubmissionByID($formID, $subID, "`".$itemID."`");
	
	$fileInfo = unserialize($dataRow[$itemID]);	
	
	fm_write_file( $tmpDir.$fileInfo['filename'], $fileInfo['contents'] );
	
	echo fm_getTmpURL().$fileInfo['filename'];		
	
	die();
}

add_action('wp_ajax_fm_download_all_files', 'fm_downloadAllFiles');
function fm_downloadAllFiles(){
	global $fmdb;
	global $fm_controls;
	
	$tmpDir =  fm_getTmpPath();
	
	$formID = $_POST['id'];	
	$itemID = $_POST['itemid'];
	
	$formInfo = $fmdb->getForm($formID);
	
	foreach($formInfo['items'] as $item){
		if($item['unique_name'] == $itemID){
			$itemLabel = $item['label'];
			$fileItem = $item;
		}
	}
	
	$formData = $fmdb->getFormSubmissionDataRaw($formID, 'timestamp', 'DESC', 0, 0);
	$files = array();
	foreach($formData as $dataRow){
		$fileInfo = unserialize($dataRow[$itemID]);
		if(sizeof($fileInfo) > 1){		
			if(!isset($fileInfo['upload_dir'])){
				$fname = "(".$dataRow['timestamp'].") ".$fileInfo['filename'];
				$files[] = $tmpDir.$fname;
				fm_write_file( $tmpDir.$fname, $fileInfo['contents'] );
			}
			else{
				$files[] = $fm_controls['file']->parseUploadDir($fileItem['extra']['upload_dir']).$fileInfo['filename'];
			}
		}
	}
	
	if(sizeof($files) > 0){
	
		$zipFileName = $formInfo['title']." - ".$itemLabel.".zip";
		$zipFullPath =  $tmpDir.sanitize_title($zipFileName);	
		fm_createZIP($files, $zipFullPath); 
		 
		$fp = fopen(fm_getTmpPath()."download.php", "w");	
		fwrite($fp, fm_createDownloadFileContents($zipFullPath, $zipFileName));	
		fclose($fp); 
		
		echo fm_getTmpURL()."download.php";
		die();
	}
	else{
		die();
	}
	die();
}

function fm_createDownloadFileContents($localFileName, $downloadFileName){
	$str = "";
	
	$str.= "<?php\n";
	$str.= "header('Content-Disposition: attachment; filename=\"".$downloadFileName."\"');\n";
	$str.= "readfile('".$localFileName."');\n";
	$str.= "?>";
 
	return $str;
}

/* Below is from David Walsh (davidwalsh.name), slightly modified. Thanks Dave! */
function fm_createZIP($files = array(),$destination = '') {
   
  //vars
  $valid_files = array();
  //if files were passed in...
  if(is_array($files)) {
    //cycle through each file
    foreach($files as $file) {
      //make sure the file exists
      if(file_exists($file)) {
        $valid_files[] = $file;
      }
    }
  }
  //if we have good files...
  if(count($valid_files)) {
    //create the archive
    $zip = new ZipArchive();
    if($zip->open($destination, ZIPARCHIVE::OVERWRITE | ZIPARCHIVE::CREATE | ZIPARCHIVE::FL_NODIR) !== true) {
	  return false;
    }
    //add the files
    foreach($valid_files as $file) {
      $zip->addFile($file,basename($file));
    }
    //debug
    //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
    
    //close the zip -- done!
    $zip->close();
    
    //check to make sure the file exists
	return file_exists($destination);
  }
  else
  { 
    return false;
  }
}
?>