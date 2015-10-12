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

		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		

		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_".$this->getTypeName()."_show_hide";
	}
	
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