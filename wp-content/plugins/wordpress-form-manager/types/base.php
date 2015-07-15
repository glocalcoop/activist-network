<?php

class fm_controlBase{
	
	public function __construct(){
	}
	
	//used to name javascript functions and such
	public function getTypeName(){
		return "basic";
	}
	
	//user friendly name for the control type; used in the editor UI (the default/base control type is not listed, however)
	public function getTypeLabel(){
		return "Basic";
	}
	
	//HTML returned for the front end (user) version of the form
	public function showItem($uniqueName, $itemInfo){
		return "<input type=\"text\" id=\"{$uniqueName}\" />";
	}	
	
	//HTML returned for the back end (admin) version of the form. Should be the item with no formatting, and use default values rather than placeholders, etc.
	public function showItemSimple($uniqueName, $itemInfo){
		return "<input type=\"text\" id=\"{$uniqueName}\" name=\"{$uniqueName}\" value=\"".$itemInfo['extra']['value']."\" />";
	}
	
	//HTML returned for the editor (admin) version of the form
	public function editItem($uniqueName, $itemInfo){
		return "<input id=\"{$uniqueName}-edit-value\" type=\"text\" readonly=\"readonly\" value=\"".htmlspecialchars($itemInfo['extra']['value'])."\" />";
	}
	
	//returns an array of fm_editPanelItemBase (or derived) objects that define the editor 'panel'
	public function getPanelItems($uniqueName, $itemInfo){
		global $fmdb;
		$arr=array();
		foreach($fmdb->itemKeys as $key=>$v){
			$arr[] = new fm_editPanelItemBase($uniqueName, $key, $key, array('value'=>$itemInfo[$key]));
		}
		return $arr;
	}
	
	//returns an associative array (keyed by the db field names for items) of javascript for use in the 'get' script; see showPanelScript()
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = "fm_get_item_value(itemID, 'extra')";		
		return $opt;
	}
	
	//returns the name of a javascript function to be called whenever the user clicks the 'edit'/'done' button; 
	//	the function is passed two variables: the first is the itemID, the second is a boolean indicating if 'edit' or 'done' was clicked (true or false respectively)
	public function getShowHideCallbackName(){
		return "";
	}
	
	//returns the name of a javascript function called when saving the form.  If the function returns false, that means the save failed; otherwise, it should return true.
	//The save validators are responsible for displaying any alerts
	public function getSaveValidatorName(){
		return "";
	}
	
	//returns the name of a javascript function to get the value of the form element
	public function getElementValueGetterName(){
		return "fm_base_get_value";
	}
	
	//this function is called in the admin header; you can place scripts here (like whatever getShowHideCallbackName() returns)  etc. 
	protected function showExtraScripts(){}
	
	//called when displaying the user form; used for validation scripts, etc.
	public function showUserScripts(){}
	
	//called when displaying a required form item in the user form; returns the name of a javascript function that should return 'true' only if the input is not blank
	public function getRequiredValidatorName(){ 
		if($this->getTypeName() == "basic") return 'fm_base_required_validator';
		return "";
	}
	
	//gets the name of a general validator function, which is passed the form ID,item's unique name, and whatever is stored in $item['extra']['validation']
	public function getGeneralValidatorName(){
		return "";
	}
	
	public function getGeneralValidatorMessage($type){
		return "";
	}
		
	//called when processing a submission from the user version of the form; $itemInfo is an associative array of the db row defining the form item
	//items can return boolean false to indicate a failure, or NULL to indicate information should not be updated.
	public function processPost($uniqueName, $itemInfo){
		if(isset($_POST[$uniqueName]))
			return fm_strip_tags($_POST[$uniqueName]);
		return NULL;
	}
	
	//called when viewing submission data. $data contains (hopefully) the same value (in string form) as the value returned from processPost()
	public function parseData($uniqueName, $itemInfo, $data){
		return $data;
	}
	
	//in case the data needs to be shown in a special way for the CSV download, such as for file types
	public function parseDataCSV($uniqueName, $itemInfo, $data){
		return $this->parseData($uniqueName, $itemInfo, $data);
	}
	
	//returns an associative array keyed by the item db fields; used in the AJAX for creating a new form item in the back end / admin side
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("Item Label", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array();
		$itemInfo['nickname'] = "";
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "NONE";
		
		return $itemInfo;
	}
	
	public function getColumnType(){
		return "";
	}
	
	//item keys that are handled in the 'panel'
	protected function getPanelKeys(){
		return array();
	}
	
	//whether or not the item can be used as a 'submission meta' column
	public function isSubmissionMeta(){
		return false;
	}
	
	//whether or not the item type is for actual forms
	public function isFormField(){
		return true;
	}
	
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	
	public function showScripts(){
		$this->showExtraScripts();	
		$this->showPanelScript();
	}
	protected function getPanelScriptName(){
		return "fm_".$this->getTypeName()."_panel_get";
	}
	protected function extraScriptHelper($items){
		$str = "\"array(";
		foreach($items as $k=>$v){
			if(strpos($v, "cb:") !== false)
				$items[$k] = "'{$k}'=>'\" + ".$this->checkboxScriptHelper(substr($v,3), array('onValue'=>'checked', 'offValue'=>""))." + \"'";
			else
				$items[$k] = "'{$k}'=>'\" + fm_fix_str(fm_get_item_value(itemID, '{$v}')) + \"'";
		}
		$str.=implode(", ",$items);
		$str.= ")\"";
		return $str;
	}
	//$options: 'onValue', 'offValue'
	protected function checkboxScriptHelper($name, $options = null){
		if($options == null) $options = array('onValue'=>'1', 'offValue'=>'0');		
		return "((document.getElementById(itemID + '-{$name}').checked==true)?'".$options['onValue']."':'".$options['offValue']."')";
	}
	protected function getPanelScriptOptionDefaults(){
		global $fmdb;
		$opt=array();
		foreach($fmdb->itemKeys as $key=>$value){
			$opt[$key] = "fm_get_item_value(itemID, '{$key}')";
		}
		$opt['index'] = 'index';
		$opt['extra'] = "\"array()\"";
		return $opt;
	}	
	public function showPanelScript(){	
		$items=array();
		$items['unique_name'] = 'itemID';
		$items['index'] = 'index';
		$items = array_merge($items, $this->getPanelScriptOptions());		
		foreach($items as $k=>$v){
			$items[$k] = "'{$k}': {$v}";
		}		
?><script type="text/javascript">
//<![CDATA[
		function <?php echo $this->getPanelScriptName();?>(itemID, index){
			var newItem = {
				<?php echo implode(",\n",$items);?>
			};
			return newItem;
		}
		<?php if($this->getSaveValidatorName() != ""):?>
		fm_registerSaveValidator('<?php echo $this->getTypeName(); ?>', '<?php echo $this->getSaveValidatorName();?>');
		<?php endif; ?>
//]]>
</script><?php
	}
		
	public function showEditorItem($uniqueName, $itemInfo){
		$str="";
		$str.="<table class=\"editor-item\">".
				"<tr>".
				"<td class=\"editor-item-label\"><label id=\"{$uniqueName}-edit-label\">".($itemInfo['label']==""?"&nbsp;":htmlspecialchars($itemInfo['label']))."</label><span id=\"{$uniqueName}-edit-required\" >".(($itemInfo['required']=='1')?'<em>*</em>':"")."</span></td>".
				"<td class=\"editor-item-main\">".$this->editItem($uniqueName, $itemInfo)."</td>".
				"</tr>";	
		$str.="</table>";	
		$str.="<div id=\"{$uniqueName}-edit-div\" name=\"{$uniqueName}-edit-div\" class=\"editor-item-panel\" style=\"display:none;\">".$this->editPanel($uniqueName, $itemInfo)."</div>";
		$str.= $this->showHiddenVars($uniqueName, $itemInfo, $this->getPanelKeys(),  $this->getPanelScriptName()."(itemID, index)");
		return $str;
	}
	
	public function editPanel($uniqueName, $itemInfo){
		global $fm_editPanelItems;		
		$str="";
		$str.="<table class=\"editor-panel-table\">";
		$str.="<tr><td colspan=\"2\"><hr class=\"edit-panel-sep\" /></td></tr>";			
		$items = $this->getPanelItems($uniqueName, $itemInfo);
		foreach($items as $item){
			$str.=$item->getPanelItem();
		}					
		$str.="</table>";		
		return $str;
	}
	
	function showHiddenVars($uniqueName, $itemInfo, $hideKeys = null, $script = "fm_base_get(itemID, index)"){
		global $fmdb;
		$str = "";
		$itemInfo['extra'] = serialize($itemInfo['extra']);
		if($hideKeys==null) $hideKeys = array();
		$str.= $this->getScriptHidden($uniqueName, $script)."\n";
		$str.= $this->getTypeHidden($uniqueName, $itemInfo);
		foreach($fmdb->itemKeys as $key=>$value){
			if(!in_array($key,$hideKeys,true))
				$str.= "<input type=\"hidden\" id=\"{$uniqueName}-{$key}\" value=\"".htmlspecialchars(isset($itemInfo[$key]) ? $itemInfo[$key] : "")."\" />";
		}
		return $str;
	}
	
	protected function getScriptHidden($uniqueName, $script){
		return "<input type=\"hidden\" id=\"{$uniqueName}-get\" value=\"{$script}\" />";
	}
	protected function getHiddenValue($uniqueName, $key, $value){
		return "<input type=\"hidden\" id=\"{$uniqueName}-{$key}\" value=\"{$value}\" />";
	}
	protected function getTypeHidden($uniqueName, $itemInfo){
		return "<input type=\"hidden\" id=\"{$uniqueName}-type\" value=\"".$itemInfo['type']."\" />";
	}
}

?>