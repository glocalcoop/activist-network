<?php

class fm_dummyControl extends fm_controlBase{
	
	public function getTypeName(){ return "dummy"; }
	
	public function getTypeLabel(){ return "Dummy"; }
	
	public function showItem($uniqueName, $itemInfo){
		return "";
	}	

	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("Item Label", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array();
		$itemInfo['nickname'] = __("Item Nickname", 'wordpress-form-manager');
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
		return "";
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();
		
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		/*
		$arr[] = new fm_editPanelItemBase($uniqueName, 'value', 'Default Value', array('value' => $itemInfo['extra']['value']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'size', 'Width (in pixels)', array('value' => $itemInfo['extra']['size']));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'required', 'Required', array('checked'=>$itemInfo['required']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'validation', 'Validation', array('options' => array_merge(array('none' => "..."), $this->getValidatorList()), 'value' => $itemInfo['extra']['validation']));		
		*/
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		/*$opt['extra'] = $this->extraScriptHelper(array('value'=>'value', 'size'=>'size', 'validation'=>'validation'));
		$opt['required'] = $this->checkboxScriptHelper('required');		
		*/
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_".$this->getTypeName()."_show_hide";
	}
	
	/*
	public function getRequiredValidatorName(){ 
		return 'fm_base_required_validator';
	}*/
		
	protected function showExtraScripts(){
		?>
<script type="text/javascript">
//<![CDATA[
		function fm_<?php echo $this->getTypeName(); ?>_show_hide(itemID, isDone){
			if(isDone){
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
			}
		}
//]]>
</script>
		<?php
	}
	
	public function showUserScripts(){
		
	}

	protected function getPanelKeys(){
		return array('label');
	}
}
?>