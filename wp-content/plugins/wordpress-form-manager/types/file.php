<?php

class fm_fileControl extends fm_controlBase{
	
	public function getTypeName(){ return "file"; }
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){ return __("File", 'wordpress-form-manager'); }
	
	public function showItem($uniqueName, $itemInfo){
		return "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".($itemInfo['extra']['max_size']*1024)."\" />".
				"<input name=\"".$uniqueName."\" id=\"".$uniqueName."\" type=\"file\" />";
	}
	
	public function showItemSimple($uniqueName, $itemInfo){
		return $this->showItem($uniqueName, $itemInfo);
	}

	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("New File Upload", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array('max_size' => 10000,
									'upload_url' => '%wp_uploads_url%',
									'upload_dir' => '%wp_uploads_dir%',
									'name_format' => get_option('fm-file-name-format'),
									'media_type' => 'none',
									);
		$itemInfo['nickname'] = '';
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "DATA";
		
		return $itemInfo;
	}
	
	public function getColumnType(){
		return "LONGBLOB";
	}

	public function editItem($uniqueName, $itemInfo){
		return "<input type=\"file\" disabled>";
	}
	
	public function processPost($uniqueName, $itemInfo){
		global $fmdb;

		if(!isset($_FILES[$uniqueName]['error'])) return NULL;
		
		//if no file uploaded
		if($_FILES[$uniqueName]['error'] == 4) return NULL;
		
		if($_FILES[$uniqueName]['error'] > 0){
			if($_FILES[$uniqueName]['error'] == 2 || $_FILES[$uniqueName]['error'] == 1 )
				$fmdb->setErrorMessage("(".$itemInfo['label'].") ".__("File upload exceeded maximum allowable size.", 'wordpress-form-manager'));
			else if($_FILES[$uniqueName]['error'] == 4) // no file
				return "";
			$fmdb->setErrorMessage("(".$itemInfo['label'].") ".__("There was an error with the file upload.", 'wordpress-form-manager')." (".$_FILES[$uniqueName]['error'].")");
			return false;
		}
		
		$ext = pathinfo($_FILES[$uniqueName]['name'], PATHINFO_EXTENSION);
		if(strpos($itemInfo['extra']['exclude'], $ext) !== false){
			/* translators: this will be shown along with the item label and file extension, as in, "(File Upload) Cannot be of type '.txt'" */
			$fmdb->setErrorMessage("(".$itemInfo['label'].") ".__("Cannot be of type", 'wordpress-form-manager')." '.".$ext."'");
			return false;
		}
		else if(trim($itemInfo['extra']['restrict'] != "") && strpos($itemInfo['extra']['restrict'], $ext) === false){
		/* translators: this will be shown along with the item label and a list of file extensions, as in, "(File Upload) Can only be of types '.txt, .doc, .pdf'" */
			$fmdb->setErrorMessage("(".$itemInfo['label'].") ".__("Can only be of types", 'wordpress-form-manager')." ".$itemInfo['extra']['restrict']);
			return false;
		}
			
		if(trim($itemInfo['extra']['upload_dir']) == ""){ //keep the upload in the form manager database
			$filename = $_FILES[$uniqueName]['tmp_name'];			
			
			$handle = fopen($filename, "rb");
			$contents = fread($handle, filesize($filename));
			$filetype = wp_check_filetype($filename);
			fclose($handle);
		
			$saveVal = array('filename' => $_FILES[$uniqueName]['name'],
								'mimetype' => $filetype['type'],
								'contents' => $contents,
								'size' => $_FILES[$uniqueName]['size']);
								
			return addslashes(serialize($saveVal));
		}
		else{
			//make sure to add a trailing slash if this was forgotten.
			$uploadDir = $this->parseUploadDir($itemInfo['extra']['upload_dir']);
			
			$newFileName = $this->getFormattedFileName($uniqueName, $itemInfo);
			
			$fullPath = $uploadDir . $newFileName;
			
			$uploadURL = $this-> parseUploadURL($itemInfo['extra']['upload_url']);

			$filetype = wp_check_filetype($_FILES[$uniqueName]['tmp_name']);
			
			move_uploaded_file($_FILES[$uniqueName]['tmp_name'], $fullPath);
			$saveVal = array('filename' => $newFileName,
								'mimetype' => $filetype['type'],
								'contents' => '',
								'upload_dir' => true,
								'upload_url' => $uploadURL,
								'size' => $_FILES[$uniqueName]['size']);
			return addslashes(serialize($saveVal));
		}	
	}
	
	protected function getFormattedFileName($uniqueName, $itemInfo){
		$pathInfo = pathinfo($_FILES[$uniqueName]['name']);
		$fileNameFormat = trim($itemInfo['extra']['name_format']) == "" ? get_option( 'fm-file-name-format' ) : trim($itemInfo['extra']['name_format']);
		if($fileNameFormat == "%filename%"){
			$newFileName = $pathInfo['filename'];
		}
		else{
			$fileNamePos = strpos($fileNameFormat, '%filename%');
			if($fileNamePos !== false){
				if($fileNamePos > 0){
					$before = substr($fileNameFormat, 0, $fileNamePos);
					$after = substr($fileNameFormat, $fileNamePos + 10, strlen($fileNameFormat) - $fileNamePos - 10);
				}
				else{
					$before = "";
					$after = substr($fileNameFormat, -1 * (strlen($fileNameFormat) - 10));
				}
			}		
			$newFileName = "";
			if($before != "")
				$newFileName.= fm_get_time($before);
			if($fileNamePos !== false)
				$newFileName.= $pathInfo['filename'];
			if($after != "")
				$newFileName.= fm_get_time($after);
		}
		$newFileName.= '.'.$pathInfo['extension'];
		
		return $newFileName;
	}
	
	public function parseData($uniqueName, $itemInfo, $data){
						
		if(trim($data) == "") return "";
		
		$fileInfo = unserialize($data);
				
		if($fileInfo['size'] < 1024)
			$sizeStr = $fileInfo['size']." B";
		else
			$sizeStr = ((int)($fileInfo['size']/1024))." kB";

		// if the file is stored in the database, then it can't be downloaded directly
		if(!isset($fileInfo['upload_dir']) || trim($itemInfo['extra']['upload_url']) == "") 
			return $fileInfo['filename']." (".$sizeStr.")";
		// file is stored in the filesystem:
		else{
			if( isset( $fileInfo['upload_url'] ) ){
				$uploadURL = $fileInfo['upload_url'];
			}
			else {
				$uploadURL = $this->parseUploadURL($itemInfo['extra']['upload_url']);
			}

			// change how we show files according to the media type
			if ( isset( $itemInfo['extra']['media_type'] ) && $itemInfo['extra']['media_type'] == 'image' ){
				return '<img src="'.$uploadURL.$fileInfo['filename'].'">';
			}

			return '<a class="fm-download-link" href="'.$uploadURL.$fileInfo['filename'].'">'.$fileInfo['filename'].' ('.$sizeStr.')'.'</a>';
		}
	}
	
	public function parseDataCSV($uniqueName, $itemInfo, $data){
	if(trim($data) == "") return "";
		
		$fileInfo = unserialize($data);
				
		if($fileInfo['size'] < 1024)
			$sizeStr = $fileInfo['size']." B";
		else
			$sizeStr = ((int)($fileInfo['size']/1024))." kB";
		
		if(!isset($fileInfo['upload_dir']) || trim($itemInfo['extra']['upload_url']) == "") 
			return $fileInfo['filename']." (".$sizeStr.")";
		else{
			if( isset( $fileInfo['upload_url'] ) ){
				$uploadURL = $fileInfo['upload_url'];
			}
			else {
				$uploadURL = $this->parseUploadURL($itemInfo['extra']['upload_url']);
			}
			return $uploadURL.$fileInfo['filename'];
		}
	}
	
	public function parseUploadDir($dir){
		$dir = str_replace("%doc_root%", $_SERVER['DOCUMENT_ROOT'], $dir);
		$uploads = wp_upload_dir();
		$dir = str_replace("%wp_uploads_dir%", $uploads['path'], $dir);
		if(substr($dir, -1) != "/" && substr($dir, -1) != "\\") $dir.="/";			
		return $dir;
	}
	
	public function parseUploadURL($url){
		$url = str_replace("%site_url%", get_bloginfo('url'), $url);
		$uploads = wp_upload_dir();
		$url = str_replace("%wp_uploads_url%", $uploads['url'], $url);
		if(substr($url, -1) != "/") $url.="/";	
		return $url;
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'required', __('Required', 'wordpress-form-manager'), array('checked'=>$itemInfo['required']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'max_size', __('Max file size (in kB)', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['max_size']));
		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span class=\"fm-small\" style=\"padding-bottom:10px;\">".__("Your host restricts uploads to", 'wordpress-form-manager')." ".ini_get('upload_max_filesize')."B</span>", '');

		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span style=\"font-weight:bold;\">".__("File Types", 'wordpress-form-manager')."</span>", '');
		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span class=\"fm-small\" style=\"padding-bottom:10px;\">".__("Enter a list of extensions separated by commas, e.g. \".txt, .rtf, .doc\"", 'wordpress-form-manager')."</span>", '');
		$arr[] = new fm_editPanelItemBase($uniqueName, 'restrict', __('Only allow', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['restrict']));		
		$arr[] = new fm_editPanelItemBase($uniqueName, 'exclude', __('Do not allow', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['exclude']));

		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<div style=\"font-weight:bold;padding-top:10px;\">".__("Uploads", 'wordpress-form-manager')."</div>", '');
		$arr[] = new fm_editPanelItemBase($uniqueName, 'upload_dir', __('Upload directory', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['upload_dir']));
		if(trim($itemInfo['extra']['upload_dir']) != "" && !is_dir($this->parseUploadDir($itemInfo['extra']['upload_dir'])))
			$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span class=\"fm-small\" >".__("<em>This does not appear to be a valid directory</em>", 'wordpress-form-manager')."</span>", '');	
		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<div class=\"fm-small\" style\"padding-bottom:15px;\">".__("Using an upload directory will allow you to post links to uploaded files.  Otherwise, Form Manager will manage the uploaded files for you in the database.", 'wordpress-form-manager')."</div>", '');
		$arr[] = new fm_editPanelItemBase($uniqueName, 'upload_url', __('Upload URL', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['upload_url']));
		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span class=\"fm-small\" style=\"padding-bottom:10px;\">".__("This will be the base URL used for links to the uploaded files.  If left blank, no links will be generated.", 'wordpress-form-manager')."</span>", '');
		$arr[] = new fm_editPanelItemBase($uniqueName, 'name_format', __('Name format', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['name_format']));
		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span class=\"fm-small\" style=\"padding-bottom:10px;\">".__("This only applies if you specify an upload directory. Insert %filename% where you want the filename to appear. The rest will be used as a PHP timestamp format.", 'wordpress-form-manager')."</span>", '');

		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<div style=\"font-weight:bold;padding-top:10px;\">".__("Media", 'wordpress-form-manager')."</div>", '');
		$mediaTypeOptions = array(
			'none' => '...',
			'image' => __("Image",'wordpress-form-manager'),
			);
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'media_type', __('Type', 'wordpress-form-manager'), array('options' => $mediaTypeOptions, 'value' => $itemInfo['extra']['media_type']));
		$arr[] = new fm_editPanelItemNote($uniqueName, '', "<span class=\"fm-small\" style=\"padding-bottom:10px;\">".__("Choosing a media type will cause files to be shown according to their type, rather than a download link.", 'wordpress-form-manager')."</span>", '');
		
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = $this->extraScriptHelper(array('restrict' => 'restrict', 'exclude' => 'exclude', 'max_size' => 'max_size', 'upload_dir' => 'upload_dir', 'upload_url' => 'upload_url', 'name_format' => 'name_format', 'media_type' => 'media_type' ));
		$opt['required'] = $this->checkboxScriptHelper('required');	
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_file_show_hide";
	}
	
	public function getSaveValidatorName(){
		return "fm_file_save_validator";
	}
	
	public function getRequiredValidatorName(){ 
		return 'fm_base_required_validator';
	}
	
	protected function showExtraScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_file_show_hide(itemID, isDone){
			if(isDone){
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
				if(document.getElementById(itemID + '-required').checked)
					document.getElementById(itemID + '-edit-required').innerHTML = "<em>*</em>";
				else
					document.getElementById(itemID + '-edit-required').innerHTML = "";		
			}
		}		
		
		function fm_file_save_validator(itemID){
			var itemLabel = document.getElementById(itemID + '-label').value;
			var restrictExtensions = document.getElementById(itemID + '-restrict').value.toString();
			var excludeExtensions = document.getElementById(itemID + '-restrict').value.toString();
				
			if(!restrictExtensions.match(/^(\s*\.[a-zA-Z0-9]+\s*)?(,\s*\.[a-zA-Z0-9]+\s*)*$/)){
				alert(itemLabel + ": <?php _e("'Only allow' must be a list of extensions separated by commas", 'wordpress-form-manager');?>");
				return false;
			}
			if(!excludeExtensions.match(/^(\s*\.[a-zA-Z0-9]+\s*)?(,\s*\.[a-zA-Z0-9]+\s*)*$/)){
				alert(itemLabel + ": <?php _e("'Do not allow' must be a list of extensions separated by commas", 'wordpress-form-manager');?>");
				return false;
			}
			
			return true;
		}
//]]>
</script>
		<?php
	}

	protected function getPanelKeys(){
		return array('label','required');
	}
}
?>