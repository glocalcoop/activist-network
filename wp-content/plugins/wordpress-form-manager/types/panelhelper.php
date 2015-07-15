<?php
/* helper classes for the basic control type;  returns a table row (unless the $notags is set to true) */
class fm_editPanelItemBase{
	//$options:
	//	'value': value of text field
	var $uniqueName;
	var $itemName;
	var $itemLabel;
	var $options;
	var $notags;
	
	function __construct($uniqueName, $itemName, $itemLabel, $options, $notags = false){
		$this->uniqueName = $uniqueName;
		$this->itemName = $itemName;
		$this->itemLabel = $itemLabel;
		$this->options = $options;
		$this->notags = $notags;	
	}	
	function getPanelItem(){
		$str = "";
		if(!$this->notags) $str.="<tr><td style=\"vertical-align:top;\">";
		$str.= "<label>{$this->itemLabel}</label>";
		if(!$this->notags) $str.="</td><td>";
		$str.=$this->getPanelItemInner();
		if(!$this->notags) $str.="</td></tr>";
		return $str;
	}
	function getPanelItemInner(){
		return "<input type=\"text\" id=\"{$this->uniqueName}-{$this->itemName}\" value=\"".htmlspecialchars($this->options['value'])."\" />";
	}
}

class fm_editPanelItemMultiText extends fm_editPanelItemBase{
	function getPanelItemInner(){
		$arr = array();
		$x=0;
		foreach($this->options['fields'] as $field){
			$arr[] = "{$field['label']}<input type=\"text\" id=\"{$this->uniqueName}-{$field['name']}\" value=\"".htmlspecialchars($this->options['value'][$field['name']])."\" style=\"width:{$field['size']};\">";
		}
		return implode($this->options['separator'], $arr);
	}	
}

class fm_editPanelTextArea extends fm_editPanelItemBase{
	function getPanelItemInner(){
		return "<textarea rows=\"".$this->options['rows']."\" cols=\"".$this->options['cols']."\" id=\"{$this->uniqueName}-{$this->itemName}\" >".htmlspecialchars($this->options['value'])."</textarea>";
	}
}

class fm_editPanelItemCheckbox extends fm_editPanelItemBase{
	//$options:
	//	'checked': value of 'checked' attribute (true/false, 1/0)
	function getPanelItemInner(){
		return "<input type=\"checkbox\" id=\"{$this->uniqueName}-{$this->itemName}\" ".(($this->options['checked']==true || $this->options['checked']==1)?"checked=\"checked\"":"")."/>";
	}
}

class fm_editPanelItemDropdown extends fm_editPanelItemBase{
	function getPanelItemInner(){
		$str="<select id=\"{$this->uniqueName}-{$this->itemName}\">";
		foreach($this->options['options'] as $k=>$v){
			if($this->options['value'] == $k) $str.="<option value=\"{$k}\" selected=\"selected\">".htmlspecialchars($v)."</option>";
			else $str.="<option value=\"{$k}\">{$v}</option>";
		}
		$str.="</select>";
		return $str;
	}	
}

class fm_editPanelItemMulti extends fm_editPanelItemBase{
	function getPanelItemInner(){
		$str.="<table>";
		/* translators: this is for the list element options */
		$str.="<tr><td><input type=\"button\" value=\"".__("Enter Items as Text", 'wordpress-form-manager')."\" onclick=\"js_multi_item_text_entry('multi-panel-{$this->uniqueName}','".$this->options['get_item_value_script']."','".$this->options['get_item_script']."')\"/></td></tr>";
		$str.="</table>";
		$str.="<ul id=\"multi-panel-{$this->uniqueName}\">";
		$str.="</ul>";
		$str.="<table><tr><td>";
		$str.="<input type=\"button\" value=\"".__("Add", 'wordpress-form-manager')."\" onclick=\"js_multi_item_add('multi-panel-{$this->uniqueName}','".$this->options['get_item_script']."','')\"/>";
		$str.="</td></tr>";		
		$str.="</table>";
		$str.="<script type=\"text/javascript\">";
		$str.="js_multi_item_create('multi-panel-{$this->uniqueName}');";
		if(is_array($this->options['options']) && sizeof($this->options['options'])>0){
			foreach($this->options['options'] as $opt)
				$str.="js_multi_item_add('multi-panel-{$this->uniqueName}','".$this->options['get_item_script']."', '".format_string_for_js($opt)."');";
		}
		$str.="</script>";
		return $str;
	}
}

class fm_editPanelItemNote extends fm_editPanelItemBase{
	function getPanelItem(){
		return "<tr><td colspan=\"2\">".
				$this->itemLabel.
				"</td></tr>";
	}
}
?>