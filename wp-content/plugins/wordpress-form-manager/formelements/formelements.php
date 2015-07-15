<?php
/*****

PHP classes for defining and displaying form elements

provides an instantiation $fe_formElements that converts from form definitions (arrays) to html of the form item

array definition:
'type' 			: type of element ('text', 'select', 'textarea', 'checkbox', 'radio', 'button', 'submit')
'default' 		: default value for select, radio, and textarea
'options' 		: for select/radio, an associative array of options (key=>value)
'attributes' 	: an associative array of attributes for the input/select/textarea tag
'separator' 	: for radio button list; html to separate radio options


*****/

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//textarea
function fe_getTextareaHTML($elementDef){
	return "<textarea ".fe_getAttributeString($elementDef['attributes']).">".htmlspecialchars($elementDef['default'])."</textarea>";
}

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//radio buttons; can be a single button, or a group
function fe_getRadioHTML($elementDef){
	if(!isset($elementDef['options'])) //single radio button
		return "<input type=\"radio\" ".fe_getAttributeString($elementDef['attributes'])." ".($elementDef['value']?'checked':'')."/>";
	//multiple radio options
	$arr=array();
	$index = 0;
	$idPrefix = $elementDef['attributes']['id'];
	foreach($elementDef['options'] as $k=>$v){
		$elementDef['attributes']['id'] = $idPrefix.'-'.$index;
		$arr[] = "<input type=\"radio\" ".fe_getAttributeString($elementDef['attributes'])." value=\"".htmlspecialchars($k)."\" ".
				 ((isset($elementDef['value']) && $elementDef['value']==$v)?'checked':'')."/>&nbsp;&nbsp;".htmlspecialchars($v).
				 "<input type=\"hidden\" id=\"".$idPrefix.'-'.$index."-value\" value=\"".htmlspecialchars($v)."\" />";
				
		$index++;
	}
	$str = implode($elementDef['separator'],$arr);
	return $str;
}

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//dropdown menus
//'value' : key of the default option
//'options' : associative array (key=>value) of options
function fe_getSelectHTML($elementDef){
	if(!(isset($elementDef['options']) && is_array($elementDef['options']))) return "";	
	$default = (isset($elementDef['value'])?$elementDef['value']:"");
	$str="<select ".fe_getAttributeString($elementDef['attributes'])." >";
	foreach($elementDef['options'] as $k=>$v){
		if($v==$default) 	$str.="<option value=\"".htmlspecialchars($k)."\" selected=\"selected\">".htmlspecialchars($v)."</option>";
		else				$str.="<option value=\"".htmlspecialchars($k)."\">".htmlspecialchars($v)."</option>";
	}
	$str.="</select>";	
	return $str;
}


////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//an <input ... >

function fe_getInputHTML($elementDef){				
	return "<input type=\"".$elementDef['type']."\" ".fe_getAttributeString($elementDef['attributes'])." />";
}

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

// checkbox
function fe_getCheckboxHTML($elementDef){
	return "<input type=\"checkbox\" ".fe_getAttributeString($elementDef['attributes'])." ".($elementDef['checked']?"checked=\"checked\"":"")."/>";
}

// checkbox lists
function fe_getCheckboxListHTML($elementDef){
	//the 'value' option is a set of values separated by ", " (like "Item 1, Item 3, Item 4")
	$vals = fe_parseCHeckboxValue($elementDef);	

	if(!(isset($elementDef['options']) && is_array($elementDef['options']))) return "";
	$arr=array();
	foreach($elementDef['options'] as $k=>$v){
		$arr[] = "<input type=\"checkbox\" ".fe_getAttributeString($elementDef['attributes'])." id=\"".htmlspecialchars($k)."\" name=\"".htmlspecialchars($k)."\" ".($vals[$k]?'checked':'')."/>&nbsp;&nbsp;".htmlspecialchars($v)."";
	}
	$str = implode($elementDef['separator'],$arr);
	return $str;
}

function fe_parseCheckboxValue($elementDef){
	//unless the user is trying to break the system, the following should retrieve the selections (if they exist)
	$val = ", ".$elementDef['value'].", ";
	$opt = array();
	foreach($elementDef['options'] as $k=>$v){
		if(strpos($val, ", ".$v.", ") !== false)
			$opt[$k] = true;
		else
			$opt[$k] = false;
	}
	return $opt;
}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//helper function to compile an 'attribute' string from an associative array
function fe_getAttributeString($atts){
	if(!is_array($atts)) return "";
	$arr = array();
	foreach($atts as $k=>$v)
		$arr[]= "{$k}=\"{$v}\"";
	return implode(" ",$arr);
}

function fe_getElementHTML($elementDef){
	switch($elementDef['type']){
		case 'radio': return fe_getRadioHTML($elementDef);
		case 'select': return fe_getSelectHTML($elementDef);
		case 'textarea': return fe_getTextareaHTML($elementDef);
		case 'checkbox': return fe_getCheckboxHTML($elementDef);
		case 'checkbox_list': return fe_getCheckboxListHTML($elementDef);
		case 'text':		
		case 'button':
		case 'submit':
		default: return fe_getInputHTML($elementDef);
	}
}

?>