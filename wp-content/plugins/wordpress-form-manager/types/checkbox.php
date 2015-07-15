<?php

class fm_checkboxControl extends fm_controlBase{
	public function getTypeName(){ return "checkbox"; }
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){ return __("Checkbox", 'wordpress-form-manager'); }
	
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("New Checkbox", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array();
		$itemInfo['nickname'] = '';
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "DATA";
		
		return $itemInfo;
	}
	
	public function getColumnType(){
		return "VARCHAR( 10 ) DEFAULT ''";
	}
	
	public function showItem($uniqueName, $itemInfo){
		$isChecked = $itemInfo['extra']['value'] == 'checked';
		$elem=array('type' => 'checkbox',
					'attributes' => array('name' => $uniqueName,
											'id'=> $uniqueName,
											'style'=> ($itemInfo['extra']['position'] == "right" ? "float:right;" : "")
											),
					'checked'=> $isChecked,				
					);											
		return fe_getElementHTML($elem);
	}
	
	public function showItemSimple($uniqueName, $itemInfo){
		$isChecked = $itemInfo['extra']['value'] == __("yes",'wordpress-form-manager');
		$elem=array('type' => 'checkbox',
					'attributes' => array('name' => $uniqueName,
											'id'=> $uniqueName,
											),
					'checked'=> $isChecked,
					);
		return fe_getElementHTML($elem);
	}
	
	public function processPost($uniqueName, $itemInfo){
		if(isset($_POST[$uniqueName]))
			return $_POST[$uniqueName]=="on"?__("yes",'wordpress-form-manager'):__("no",'wordpress-form-manager');
		return __("no",'wordpress-form-manager');
	}
	
	public function editItem($uniqueName, $itemInfo){
		return "<input id=\"{$uniqueName}-edit-value\" type=\"checkbox\" disabled ".$itemInfo['extra']['value']." />";
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'position', __('Position', 'wordpress-form-manager'), array('options' => array('left' => __("Left", 'wordpress-form-manager'), 'right' => __("Right", 'wordpress-form-manager')), 'value' => $itemInfo['extra']['position']));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'value', __('Checked by Default', 'wordpress-form-manager'), array('checked'=>($itemInfo['extra']['value']=='checked')));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'required', __('Required', 'wordpress-form-manager'), array('checked'=>$itemInfo['required']));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = "\"array('value' => '\" + ".$this->checkboxScriptHelper('value',array('onValue'=>'checked', 'offValue'=>""))." + \"', 'position' => '\" + fm_fix_str(fm_get_item_value(itemID, 'position')) + \"')\"";
		$opt['required'] = $this->checkboxScriptHelper('required');
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_checkbox_show_hide";
	}
	
	protected function showExtraScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_checkbox_show_hide(itemID, isDone){
			if(isDone){
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
				document.getElementById(itemID + '-edit-value').checked = document.getElementById(itemID + '-value').checked;
				if(document.getElementById(itemID + '-required').checked)
					document.getElementById(itemID + '-edit-required').innerHTML = "<em>*</em>";
				else
					document.getElementById(itemID + '-edit-required').innerHTML = "";
			}
		}
//]]>
</script><?php
	}
	
	public function getRequiredValidatorName(){ 
		return "fm_checkbox_required_validator";
	}
		
	protected function getPanelKeys(){
		return array('label', 'required');
	}
}

class fm_metaCheckboxControl extends fm_checkboxControl {
	public function isSubmissionMeta() { return true; }
	public function isFormField() { return false; }
	
	public function getTypeName(){ return "metacheckbox"; }
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'value', __('Checked by Default', 'wordpress-form-manager'), array('checked'=>($itemInfo['extra']['value']=='checked')));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = "\"array('value' => '\" + ".$this->checkboxScriptHelper('value',array('onValue'=>'checked', 'offValue'=>""))." + \"')\"";
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_metatext_show_hide";
	}
	
	public function processPost($uniqueName, $itemInfo){
		$val = '';
		if(isset($_POST[$uniqueName])) {
			$val = $_POST[$uniqueName];
		} else if ( isset($_POST['fm_form_submit']) 
		&& ( is_array( $itemInfo['extra'] ) && isset( $itemInfo['extra']['value'] ) ) ) {
			$val = $itemInfo['extra']['value'] == 'checked' ? 'on' : '';
		}
		
		return $val=="on"?__("yes",'wordpress-form-manager'):__("no",'wordpress-form-manager');
	}
	
	protected function showExtraScripts(){ }
	
	protected function getPanelKeys(){ 
		return array('label');
	}
}
?>