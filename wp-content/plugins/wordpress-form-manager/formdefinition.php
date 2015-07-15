<?php

class fm_form_definition_class{

var $attributeKeys = array('text', 'textarea', 'checkbox', 'custom_list', 'separator', 'note', 'recaptcha', 'file',
						'label', 'required', 'placeholder', 'width', 'validation',
						'height',
						'default',
						'content',
						'max-size', 'allow-only', 'exclude',
						'style',
						'options',
						'unique-name'						
					);					
					
////////////////////////////////////////////////////////////////////////////////////////////////////
// READING

function parseFormDefinition($inputStr){
	$fields = array();
	foreach($this->attributeKeys as $key)
		$fields[$key] = $key;
	$atts = fm_get_str_data($inputStr, $fields);
	$formElements = array();
	$nextElement = false;
	foreach($atts as $att){
		switch($att['field']){
			case 'text':
			case 'textarea':
			case 'checkbox':
			case 'separator':
			case 'note':
			case 'recaptcha':
			case 'custom_list':
			case 'file':
				if($nextElement !== false)
					$formElements[] = $nextElement;
				$nextElement = array();
				$nextElement['type'] = $att['field'];
				break;
			default:
				$nextElement[$att['field']] = $att['value'];
		}
	}
	$formElements[] = $nextElement;
	return $formElements;
}
			
function createFormInfo($inputStr){	
	global $fmdb;
	
	$parsedInfo = $this->parseFormDefinition($inputStr);
	
	$formInfo = array();
	$formInfo['items'] = array();
	
	$index = 0;
	foreach($parsedInfo as $parsed){
		$newItem = array();
		$newItem['ID'] = $_POST['fm-form-id'];
		$newItem['index'] = $index++;
		$newItem['unique_name'] = (isset($parsed['unique-name']) ? $parsed['unique-name'] : $fmdb->getUniqueItemID($parsed['type']));
		$newItem['type'] = $parsed['type'];
		$newItem['extra'] = array();
		$newItem['nickname'] = "";
		$newItem['label'] = $parsed['label'];			
		$newItem['required'] = "";
		$newItem['db_type'] = "DATA";
		$newItem['description'] = "";
		$newItem = array_merge($newItem, call_user_func(array($this, "parseItem_".$parsed['type']), $parsed));
		$formInfo['items'][] = $newItem;
	}
	return $formInfo;
}		
////////////////////////////////////////////////////////////////////////////////////////////////////
// PRINTING

function printFormAtts($items, $sep = ""){
	$str = "";
	foreach($items as $item){
		$str.= $this->printFormItemAtts($item).$sep;
	}
	return $str;
}

function printFormItemAtts($item){
	$str = "";
	$str.= $item['type'].":\n";
	$str.= "label: ".$item['label']."\n";
	$str.= "unique-name: ".$item['unique_name']."\n";
	$printFn = "printItem_".$item['type'];
	
	$str.= call_user_func(array($this,$printFn), $item)."\n";
	
	return $str;
}

// TEXT ////////////////////////////////////////////////////////////////////////////////////////////
function printItem_text($item){
	$str.= "required: ".($item['required'] == 1 ? "yes":"no")."\n";
	$str.= "placeholder: ".$item['extra']['value']."\n";
	$str.= "width: ".$item['extra']['size']."\n";
	$str.= "validation: ".$item['extra']['validation']."\n";
	return $str;
}
function parseItem_text($item){
	$newItem = array();
	$newItem['extra'] = array('value' => $item['placeholder'],
							'size' => $item['width'],
							'validation' => $item['validation']
							);
	$newItem['required'] = ($item['required']=="yes"?1:0);
	return $newItem;
}

// TEXTAREA ////////////////////////////////////////////////////////////////////////////////////////////
function printItem_textarea($item){
	$str.= "required: ".($item['required'] == 1 ? "yes":"no")."\n";
	$str.= "placeholder: ".$item['extra']['value']."\n";
	$str.= "height: ".$item['extra']['rows']."\n";
	$str.= "width: ".$item['extra']['cols']."\n";
	return $str;
}
function parseItem_textarea($item){
	$newItem = array();
	$newItem['extra'] = array('value' => $item['placeholder'],
							'rows' => $item['height'],
							'cols' => $item['width']
						);
	$newItem['required'] = ($item['required']=="yes"?1:0);
	return $newItem;
}
// CHECKBOX ////////////////////////////////////////////////////////////////////////////////////////////
function printItem_checkbox($item){
	$str.= "required: ".($item['required'] == 1 ? "yes":"no")."\n";
	$str.= "default: ".$item['extra']['value']."\n";
	return $str;
}
function parseItem_checkbox($item){
	$newItem = array();
	$newItem['extra'] = array('value' => $item['default']);
	$newItem['required'] = ($item['required']=="yes"?1:0);
	return $newItem;
}

// SEPARATOR ////////////////////////////////////////////////////////////////////////////////////////////
function printItem_separator($item){
	return "";
}
function parseItem_separator($item){
	$newItem = array();
	$newItem['db_type'] = "NONE";
	return $newItem;
}

// NOTE /////////////////////////////////////////////////////////////////////////////////////////////////
function printItem_note($item){
	$str.= "content: ".$item['extra']['content']."\n";
	return $str;
}
function parseItem_note($item){
	$newItem = array();
	$newItem['extra'] = array('content' => $item['content']);
	$newItem['db_type'] = "NONE";
	return $newItem;
}

// RECAPTCHA ////////////////////////////////////////////////////////////////////////////////////////////
function printItem_recaptcha($item){
	return "";
}
function parseItem_recaptcha($item){
	$newItem = array();
	$newItem['db_type'] = "NONE";
	return $newItem;
}

// FILE /////////////////////////////////////////////////////////////////////////////////////////////////
function printItem_file($item){
	$str.= "required: ".($item['required'] == 1 ? "yes":"no")."\n";
	$str.= "max-size: ".$item['extra']['max_size']."\n";
	$str.= "allow-only: ".$item['extra']['restrict']."\n";
	$str.= "exclude: ".$item['extra']['exclude']."\n";
	return $str;
}
function parseItem_file($item){
	$newItem = array();
	$newItem['extra'] = array('restrict' => $item['allow-only'],
							'exclude' => $item['exclude'],
							'max_size' => $item['max-size']
							);
	$newItem['db_type'] = "DATA";
	return $newItem;
}

// CUSTOM LIST /////////////////////////////////////////////////////////////////////////////////////////
function printItem_custom_list($item){
	$str.= "required: ".($item['required'] == 1 ? "yes":"no")."\n";
	$str.= "width: ".$item['extra']['size']."\n";
	$str.= "style: ".$item['extra']['list_type']."\n";
	$str.= "options: ";
	$opt = array();
	foreach($item['extra']['options'] as $k=>$v){
		$opt[] = addslashes($v);
	}
	$str.=implode(", ",$opt)."\n";
	return $str;
}
function parseItem_custom_list($item){
	$newItem = array();
	$opts = explode(",", $item['options']);
	foreach($opts as $k=>$v)
		$opts[$k] = trim($v);
		
	$newItem['extra'] = array('size' => $item['width'],
							'list_type' => $item['style'],
							'options' => $opts);
	$newItem['required'] = ($item['required']=="yes"?1:0);
	return $newItem;
}

}



?>