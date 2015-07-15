<?php

class fm_textControl extends fm_controlBase{
	var $validators;
	var $showValueAsPlaceholder;
	
	function __construct(){
		$this->validators = array();
	}
	
	public function getTypeName(){ return "text"; }
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){ return __("Text", 'wordpress-form-manager'); }
	
	public function showItem($uniqueName, $itemInfo){
		global $fm_display;
		
		$elem=array('type' => 'text',
					'attributes' => array('name' => $uniqueName,
											'id'=> $uniqueName,																			
											'style' => "width:".$itemInfo['extra']['size']."px;",
											)
					);
		if(trim($itemInfo['extra']['maxlength']) != "")
			$elem['attributes']['maxlength'] = $itemInfo['extra']['maxlength'];
		
		if(isset($fm_display->currentFormOptions['use_placeholders']) && $fm_display->currentFormOptions['use_placeholders'] === false)
			$elem['attributes']['value'] = htmlspecialchars(strip_tags($itemInfo['extra']['value']));			
		else
			$elem['attributes']['placeholder'] = htmlspecialchars(strip_tags($itemInfo['extra']['value']));
		
		return fe_getElementHTML($elem);
	}
	
	//returns an associative array keyed by the item db fields; used in the AJAX for creating a new form item in the back end / admin side
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("New Text", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array('size' => '300');
		$itemInfo['nickname'] = '';
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "DATA";
		
		return $itemInfo;
	}
	
	public function getColumnType(){
		return "TEXT";
	}
	
	public function editItem($uniqueName, $itemInfo){
		return "<input id=\"{$uniqueName}-edit-value\" type=\"text\" readonly=\"readonly\" value=\"".htmlspecialchars($itemInfo['extra']['value'])."\" />";
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'value', __('Placeholder', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['value']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'size', __('Width (in pixels)', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['size']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'maxlength', __('Max characters', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['maxlength']));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'required', __('Required', 'wordpress-form-manager'), array('checked'=>$itemInfo['required']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'validation', __('Validation', 'wordpress-form-manager'), array('options' => array_merge(array('none' => "..."), $this->getValidatorList()), 'value' => $itemInfo['extra']['validation']));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = $this->extraScriptHelper(array('value'=>'value', 'size'=>'size', 'validation'=>'validation', 'maxlength'=>'maxlength'));
		$opt['required'] = $this->checkboxScriptHelper('required');		
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_text_show_hide";
	}
	
	public function getRequiredValidatorName(){ 
		return 'fm_base_required_validator';
	}
	
	public function getGeneralValidatorName(){
		return 'fm_text_validation';	
	}
	
	public function getGeneralValidatorMessage($type){
		return $this->validators[$type]['message'];
	}
	
	protected function showExtraScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_text_show_hide(itemID, isDone){
			if(isDone){
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
				document.getElementById(itemID + '-edit-value').value = document.getElementById(itemID + '-value').value;
				if(document.getElementById(itemID + '-required').checked)
					document.getElementById(itemID + '-edit-required').innerHTML = "<em>*</em>";
				else
					document.getElementById(itemID + '-edit-required').innerHTML = "";
			}
		}
//]]>
</script>
		<?php
	}
	
	public function showUserScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_text_validation(formID, itemID, valType){
			var itemValue = fm_base_get_value(formID, itemID);
			if(fm_trim(itemValue) == "") return true;
			switch(valType){
				<?php foreach($this->validators as $val): ?>
				case "<?php echo $val['name'];?>":
					return itemValue.match(<?php echo $val['regexp'];?>);
				<?php endforeach; ?>
			}
			return false;
		}
//]]>
</script><?php
	}

	protected function getPanelKeys(){
		return array('label','required');
	}
	
	protected function getValidatorList(){
		$list = array();
		foreach($this->validators as $val){
			$list[$val['name']] = $val['label'];
		}
		return $list;
	}	
	
	public function initValidators(){
		global $fmdb;
		$this->validators = $fmdb->getTextValidators();
	}
}

class fm_metaTextControl extends fm_textControl {
	public function isSubmissionMeta() { return true; }
	public function isFormField() { return false; }
	public function showUserScripts(){ }
	
	public function getTypeName(){ return "metatext"; }
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'value', __('Default', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['value']));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = $this->extraScriptHelper(array('value'=>'value'));
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_metatext_show_hide";
	}
	protected function showExtraScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_metatext_show_hide(itemID, isDone){
			if(isDone){
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
			}
		}
//]]>
</script>
		<?php
	}
	
	protected function getPanelKeys(){
		return array('label');
	}
	
	public function processPost($uniqueName, $itemInfo){
		if(isset($_POST[$uniqueName])){
			return fm_strip_tags($_POST[$uniqueName]);
		}
		else if ( is_array( $itemInfo['extra'] ) && isset( $itemInfo['extra']['value'] ) ) {
			return $itemInfo['extra']['value'];
		}
		return null; 
	}
}

class fm_metaIDNumberControl extends fm_metaTextControl {
	public function getTypeName(){ return "metaidnumber"; }
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){ return __("ID Number", 'wordpress-form-manager'); }
	
	public function processPost($uniqueName, $itemInfo){
		global $fmdb;
		
		if(isset($_POST[$uniqueName]))
			return fm_strip_tags($_POST[$uniqueName]);
		
		$fmdb->query("LOCK TABLES `".$fmdb->itemsTable."` WRITE");		
		$itemInfo = $fmdb->getFormItem($itemInfo['unique_name']);
		$ret = $itemInfo['extra']['next'];
		$fmdb->updateFormItem($itemInfo['ID'], $itemInfo['unique_name'], array( 'extra' => array( 'next' => $ret + 1 ) ) );
		$fmdb->query("UNLOCK TABLES");
		
		return $ret;
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'next', __('Next Number', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['next']));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = $this->extraScriptHelper(array('next' => 'next'));
		return $opt;
	}
	
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("New ID Number", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array('next' => '1');
		$itemInfo['nickname'] = '';
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "DATA";
		
		return $itemInfo;
	}
}

class fm_metaTrackNumberControl extends fm_metaTextControl {
	public function getTypeName(){ return "metatracknumber"; }
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){ return __("Tracking Number", 'wordpress-form-manager'); }
	
	public function parseData($uniqueName, $itemInfo, $data){
		if(!trim($data) == ""){
			switch($itemInfo['extra']['carrier']){
				case 'fedex': return $this->getFedex($data); break;
				case 'ups': return $this->getUPS($data); break;
				case 'usps': return $this->getUSPS($data); break;
				case 'dhl': return $this->getDHL($data); break;
			}
		}	
		return "";
	}
	
	protected function getFedex($data){
		return '<a href="http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers='.
			$data.'" target="_blank">'.$data.'</a>';
	}
	
	protected function getUPS($data){
		return '<a href="http://wwwapps.ups.com/etracking/tracking.cgi?tracknum='.
			$data.
			'&accept_UPS_license_agreement=yes" target="_blank">'.$data.'</a>';
	}
	
	protected function getUSPS($data){
		return '<a href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum='.
			$data.'" target="_blank">'.$data.'</a>';	
	}
	
	protected function getDHL($data){
		return '<a href="http://track.dhl-usa.com/TrackByNbr.asp?ShipmentNumber='.$data.'" target="_blank">'.
			$data.'</a>';
	}
	
	public function processPost($uniqueName, $itemInfo){
		if(isset($_POST[$uniqueName]))
			return fm_strip_tags($_POST[$uniqueName]);
		return NULL;
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'carrier', __('Carrier', 'wordpress-form-manager'), 
			array('options' => array( 'none' => '...', 'dhl' => 'DHL', 'fedex' => 'FedEx', 'ups' => 'UPS', 'usps' => 'USPS' ), 
			'value' => $itemInfo['extra']['carrier']));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = $this->extraScriptHelper(array('carrier' => 'carrier'));
		return $opt;
	}
	
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("New Tracking Number", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array('size' => '300');
		$itemInfo['nickname'] = '';
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "DATA";
		
		return $itemInfo;
	}
}

?>