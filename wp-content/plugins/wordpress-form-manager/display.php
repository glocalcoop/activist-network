<?php

include 'types.php';

class fm_display_class{

// template variables
var $currentFormInfo;
var $currentFormOptions;
var $currentFormValues;
var $currentFormData;
var $currentItemIndex;
var $nextItemIndex;

// make sure only one instance of a form is displayed per page
var $formsDisplayed;

function __construct() {
	$this->formsDisplayed = array();
}
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//options:
//	class - 'class' attribute for the <form> tag
//	action - 'action' attribute for the <form> tag
// 'params' is an associative array of hidden values inserted into the form
function displayForm($formInfo, $options=array(), $values=array()){
	global $fm_templates;
	global $fm_controls;
	global $fmdb;

	$templateFile = $formInfo['form_template'];
	if($templateFile == '') $templateFile = $fmdb->getGlobalSetting('template_form');
	if($templateFile == '') $templateFile = get_option('fm-default-form-template');	
	
	// generate the form 
	if(file_exists($fm_templates->templatesDir.'/'.$templateFile))
		$str = $this->displayFormTemplate($templateFile, $formInfo, $options, $values);
	else
		$str = $this->displayFormTemplate(get_option('fm-default-form-template'), $formInfo, $options, $values);

	$str .= $this->displayFormEtc( $formInfo );
	
	return $str;
}

function displayFormEtc( $formInfo ){
	global $fm_DEBUG;
	
	$str = "";
	
	// now that we have completely generated the form, set a flag
	$this->formsDisplayed[$formInfo['ID']] = true;	
	
	return $str;	
}


//shows an unordered list of left-labeled form items, no form tags, no submit button, no validation scripts
function displayFormBare($formInfo, $options=array(), $values=array()){
	global $msg;
	global $fmdb;
	global $fm_controls;	
	
	////////////////////////////////////////////////////////////////////////////////////////
	
	$defaults = array('label_width' => '200',
						'ul_class' => '',
						'li_class' => '',
						'exclude_types' => array(),
						'include_types' => array(),
						'display_callbacks' => array(),
						'unique_name_suffix' => '',
					);	
	foreach($defaults as $key => $default)
		if(!isset($options[$key])) $options[$key] = $default;
		
	///////////////////////////////////////////////////////////////////////////////////////
		
	$str.= "<ul".($options['ul_class'] != '' ? " class=\"".$options['ul_class']."\"" : "").">\n";
	
		foreach($formInfo['items'] as $item){
			
			//if override $item['extra']['value'] if the unique_name is in $values
			if(isset($values[$item['unique_name']]))
				$item['extra']['value'] = $values[$item['unique_name']];
			
			if( (!in_array($item['type'], $options['exclude_types']) || in_array($item['type'], $options['include_types']))
				&& !fm_is_private_item( $item )
			){
			
				$str.= "<li".($options['li_class'] != '' ? " class=\"".$options['li_class']."\"" : "").">";
				
				////////////////////////////////////////////////////////////////////////////////////////
				
					$str.='<table><tr>';
					$str.='<td style="width:'.$options['label_width'].'px"><label>'.(trim($item['nickname']) == "" ? $item['label'] : $item['nickname']);
					if($item['required']=='1')	$str.= '&nbsp;<em>*</em>';
					$str.='</label>';
					$str.='</td>';
					$str.='<td>';
					
					reset($options['display_callbacks']);
					if(array_key_exists($item['type'], $options['display_callbacks']))
						$str.= call_user_func($options['display_callbacks'][$item['type']], $item['unique_name'].$options['unique_name_suffix'], $item);
					else
						$str.= $fm_controls[$item['type']]->showItem($item['unique_name'].$options['unique_name_suffix'], $item);
					
					$str.='</td>';
					$str.='</tr></table>';			
							
				////////////////////////////////////////////////////////////////////////////////////////
				
				$str.= "</li>\n";
			}
		}
	
	$str.= "</ul>\n";	
	
	return $str;
}

function displayFormTemplate($template, $formInfo, $options=array(), $values=array()){
	global $msg;
	global $fmdb;
	global $fm_controls;
	global $fm_templates;
	global $fm_template_controls;
	
	$templateInfo = $fm_templates->getTemplateAttributes($template);
	
	//if override $item['extra']['value'] if the unique_name is in $values	
	foreach($formInfo['items'] as $k=>$item)
		if(isset($values[$item['unique_name']]))
			$formInfo['items'][$k]['extra']['value'] = $values[$item['unique_name']];	
			
	if(!isset($options['class'])) $options['class'] = 'fm-form';
		
	$this->currentFormInfo = $formInfo;
	$this->currentFormOptions = $options;
	$this->currentFormValues = $values;
	$this->currentItemIndex = -1;		
	
	if(isset($templateInfo['options']))
		foreach($templateInfo['options'] as $option){
			$varName = substr($option['var'],1);
			if(!isset($formInfo['template_values'][$varName])) $value = $option['default'];
			else $value = $fm_template_controls[$option['type']]->parseStoredValue($formInfo['template_values'][$varName], $option);
			${$varName} = $value;
		}
	
	$str = "";
			
	ob_start();	
	
	// load the template
	include $fm_templates->templatesDir."/".$template;	
	
	$str.= ob_get_contents();
	ob_end_clean();
	
	// show the support scripts, validation, etc. only once
	$scripts = new fm_script_display_class($formInfo, $options);
	
	if ( get_option( 'fm-shortcode-scripts') != 'YES' && !isset( $this->formsDisplayed[$formInfo['ID']] ) ){	
		add_action('wp_footer', array($scripts, 'showBeforeFormScripts'));
		add_action('wp_footer', array($scripts, 'showAfterFormScripts'));
	} else if( get_option( 'fm-shortcode-scripts' ) == 'YES' ) {
		ob_start();
		$scripts-> showBeforeFormScripts();
		$scripts-> showAfterFormScripts();
		$str.= ob_get_clean();
	}
	
	return $str;
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

function displayDataSummary($type, $formInfo, $data){
	global $fmdb;
	global $fm_templates;
	
	$templateFile = "";
	
	if($type == 'email'){
		$templateFile = $formInfo['email_template'];
		if($templateFile == '') $templateFile = $fmdb->getGlobalSetting('template_email');
		if($templateFile == '') $templateFile = get_option('fm-default-summary-template');
	}
	else if($type == 'summary'){
		$templateFile = $formInfo['summary_template'];
		if($templateFile == '') $templateFile = $fmdb->getGlobalSetting('template_summary');
		if($templateFile == '') $templateFile = get_option('fm-default-summary-template');
	}
	else if($fm_templates->isTemplate($type))	
		$templateFile = $type;
	else if($fm_templates->isTemplate($type.".php"))	
		$templateFile = $type.".php";
	
	if($templateFile != "")
		return $this->displayDataSummaryTemplate($templateFile, $formInfo, $data);
	
	return "The template '".$type."' was not found.";	
}

function displayDataSummaryNotemplate($formInfo, $data, $before = "", $after = "", $userAndTimestamp = false){
	global $fm_controls;
	global $fmdb;
	
	if( $formInfo['summary_hide_empty'] == '1' ){
		fm_helper_cleanEmptyFields($formInfo, $data);
	}
	
	$str = "";
	$str.= "<div class=\"fm-data-summary\">\n";
	$str.= $before;
	$str.= "<ul>\n";
	if($userAndTimestamp){
		$str.= "<li><span class=\"fm-data-summary-label\">".__("User",'wordpress-form-manager').":&nbsp;&nbsp;</span><span class=\"fm-data-summary-value\">".$data['user']."</span></li>\n";
		$str.= "<li><span class=\"fm-data-summary-label\">".__("Timestamp",'wordpress-form-manager').":&nbsp;&nbsp;</span><span class=\"fm-data-summary-value\">".$data['timestamp']."</span></li>\n";
	}
	foreach($formInfo['items'] as $item){
		if($fmdb->isDataCol($item['unique_name']))
			$str.= "<li><span class=\"fm-data-summary-label\">".$item['label'].":&nbsp;&nbsp;</span><span class=\"fm-data-summary-value\">".$fm_controls[$item['type']]->parseData($item['unique_name'], $item, $data[$item['unique_name']])."</span></li>\n";
	}
	$str.= "</ul>\n";
	$str.= $after;
	$str.= "</div>";
	return $str;
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

function displayDataSummaryTemplate($template, $formInfo, $data){
	global $fm_templates;
	
	if( $formInfo['summary_hide_empty'] == '1' ){
		fm_helper_cleanEmptyFields($formInfo, $data);
	}
	
	$this->currentFormInfo = $formInfo;
	$this->currentFormData = $data;
	$this->currentItemIndex = -1;
	
	ob_start();
	
	include $fm_templates->templatesDir."/".$template;
	
	$str = ob_get_contents();
	ob_end_clean();
	return $str;
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

function getEditorItem($uniqueName, $type, $itemInfo, $isMeta = false){
	global $fm_controls;
	
	if(isset($fm_controls[$type]))
		$control = $fm_controls[$type];
	else
		$control = $fm_controls['default'];
	
	// a new item
	if($itemInfo == null) $itemInfo = $control->itemDefaults();
	
	$itemInfo['type'] = $type;
	$itemInfo['unique_name'] = $uniqueName;
	
	$str = "<table class=\"editor-item-table\">".
			"<tr>".	
			"<td class=\"editor-item-container\">".$control->showEditorItem($uniqueName, $itemInfo)."</td>".
			"<td class=\"editor-item-buttons\"><a class=\"edit-form-button\" onclick=\"fm_showEditDivCallback('{$uniqueName}','".$control->getShowHideCallbackName()."')\" id=\"{$uniqueName}-edit\"/>".__("Edit",'wordpress-form-manager')."</a></td>".
			"<td class=\"editor-item-buttons\">"."<a class=\"edit-form-button\" onclick=\"fm_deleteItem('{$uniqueName}')\">".__("Delete",'wordpress-form-manager')."</a>"."</td>".
			"</tr>".
			"</table>".
			"<input type=\"hidden\" id=\"{$uniqueName}-type\" value=\"{$type}\" />";
	
	return $str;
}

///////////////////////////////////////////////////////////////////////////////////////////////
}

class fm_script_display_class{
	var $formInfo;
	var $options;
	var $counter;
	
	function __construct($_formInfo, $_options){
		$this->formInfo = &$_formInfo;
		$this->options = $_options;
	}
	
	public function showBeforeFormScripts(){
		global $fm_controls;
		
		$formInfo = &$this->formInfo;
		$options = $this->options;
		
		foreach ( $fm_controls as $control ){
			$control->showUserScripts();	
		}
		
		?><script type="text/javascript">
//<![CDATA[

fm_current_form = <?php echo $formInfo['ID'];?>;	
<?php
	foreach($formInfo['items'] as $item){
		$item['required_callback'] = $fm_controls[$item['type']]->getRequiredValidatorName();
		$item['required_msg'] = sprintf($formInfo['required_msg'], $item['label']);
		$item['validation_callback'] = $fm_controls[$item['type']]->getGeneralValidatorName();
		$item['validation_msg'] = sprintf($fm_controls[$item['type']]->getGeneralValidatorMessage($item['extra']['validation']), $item['label']);
		$item['validation_type'] = $item['extra']['validation'];
		$item['getter_script'] = $fm_controls[$item['type']]->getElementValueGetterName();
		
		echo "fm_register_form_item('".$formInfo['ID']."', ".json_encode($item).");\n";
	}
?>

//]]>
</script><?php
	}
	
	public function showAfterFormScripts(){
		global $fm_controls;
		
		$formInfo = &$this->formInfo;
		$options = $this->options;
		
		?><script type="text/javascript">
/* <![CDATA[ */
fm_register_form(<?php echo $formInfo['ID'];?>);
<?php
	//below is a workaround: the 'default value' for a text item is displayed as a placeholder.  In some instances, this should be an actual value in the field.  The script below takes care of this.	
	if(isset($options['use_placeholders']) && $options['use_placeholders'] === false)
		echo "fm_remove_placeholders();\n"; //this will convert placeholders into values; used to re-populate a form after a bad submission, for user profile style, etc., where the 'value' field needs to be the fields' value rather than a placeholder
		
	//condition handlers
	
	echo $this->getConditionHandlerScripts();
?>
/* ]]> */
</script><?php
	
	}
	
	protected function getValidConditions($conditions){
		foreach($conditions as $key => $cond) {
			if($cond['rule'] == "none") unset($conditions[$key]);
		}	
		return $conditions;
	}
	
	protected function getConditionHandlerScripts(){
				
		$formInfo = &$this->formInfo;
		
		if(!is_array($formInfo['conditions'])) return "";
		
		$formInfo['conditions'] = $this->getValidConditions($formInfo['conditions']);
		
		if(sizeof($formInfo['conditions']) == 0) return "";
		
		//build an array of the item types and nicknames
		$itemTypes = array();
		$itemNames = array();
		$itemObjects = array();
		foreach($formInfo['items'] as $item){
			$itemTypes[$item['unique_name']] = $item['type'];
			$itemNames[$item['unique_name']] = ($item['nickname'] != "" ? $item['nickname'] : $item['unique_name']);
			$itemObjects[$item['unique_name']] = $item;
		}
		
		$itemDependents = array();
	
		$str = "";
		$str.= "var temp;\n";
		//loop through each condition, making a javascript function to test if the condition is satisfied
		foreach($formInfo['conditions'] as $condition){
			$fn = str_replace('-', '_', $condition['id']);
			$str.="function ".$fn."(){\n";
			
			//first figure out how to get the value from the item, based on its type
			for($x=0;$x<sizeof($condition['tests']);$x++){
				$test = $condition['tests'][$x];
				$str.="var t".$x." = ";
				switch($itemTypes[$test['unique_name']]){				
					case 'checkbox':
						$str.= "document.getElementById('".$test['unique_name']."').checked;\n";
						break;
					case 'custom_list':
						switch($itemObjects[$test['unique_name']]['extra']['list_type']){
							case 'checkbox':
								$obj = $itemObjects[$test['unique_name']];
								$str .= "[];\n";
								for($y=0;$y<sizeof($obj['extra']['options']);$y++)
									$str .= "t".$x.".push( document.getElementById('".$test['unique_name']."-".$y."').checked ? ".json_encode((string)$obj['extra']['options'][$y])." : \"\") ;\n";
								break;
							case 'radio':
								//document.forms['radioExampleForm'].elements['number']
								$str .= "\"\";\n var list".$x." = document.forms['fm-form-".$formInfo['ID']."'].elements['".$test['unique_name']."'];\n";
								$str .= "for(var i=0; i<list".$x.".length; i++){\n";
								$str .=		"if(list{$x}[i].checked) t{$x} = document.getElementById('".$test['unique_name']."-' + i + '-value').value;\n";
								$str .= "}\n";
								break;
							default:
								$str.= "\"\"; var x".$x." = document.getElementById('".$test['unique_name']."').selectedIndex;\n";
								$str.= "if(x".$x." == -1) t".$x." = '';\n";
								$str.= "else t".$x." = document.getElementById('".$test['unique_name']."').options[x".$x."].text;\n";
						}
						break;
					case 'text':
					case 'textarea':
					case 'file':
						$str.= "document.getElementById('".$test['unique_name']."').value;\n";
						break;
					default:					
						$str.= "false;\n";
				}
			}

			//now do the logical tests
			
			$str.="var res = (";
			for($x=0;$x<sizeof($condition['tests']);$x++){
				$test = $condition['tests'][$x];
				if($x>0) $str.= ($test['connective'] == 'and' ? " && " : " || ");
				
				if($test['unique_name'] == '__always__'){
					$str.= 'true';
				}
				elseif($test['unique_name'] == '__never__'){
					$str.= 'false';
				}
				elseif($itemTypes[$test['unique_name']] == 'custom_list' 
				&& $itemObjects[$test['unique_name']]['extra']['list_type'] == 'checkbox') {
					switch($test['test']){
						case "eq":			$str.= "fm_array_contains( t".$x.", '".$test['val']."' )";
							break;
						case "neq":			$str.= "(! fm_array_contains( t".$x.", '".$test['val']."' ))";
							break;
					}
				} else {				
					switch($test['test']){
						case "eq": 			$str.= "t".$x." == '".$test['val']."'"; 
							break;
						case "neq":			$str.= "t".$x." != '".$test['val']."'";
							break;
						case "lt":			$str.= "(t".$x." < ".$test['val']." && t".$x." !== '')"; 
							break;
						case "gt":			$str.= "(t".$x." > ".$test['val']." && t".$x." !== '')"; 
							break;
						case "lteq":		$str.= "(t".$x." <= ".$test['val']." && t".$x." !== '')"; 
							break;
						case "gteq":		$str.= "(t".$x." >= ".$test['val']." && t".$x." !== '')"; 
							break;
						case "isempty":		$str.= "t".$x." === ''";
							break;
						case "nisempty":	$str.= "t".$x." !== ''";
							break;
						case "checked": 	$str.= "t".$x;
							break;
						case "unchecked": 	$str.= "!t".$x;
							break;
						default:			$str.= "false";							
							break;
					}
				}
			}
			$str.=");\n";
			
			
			//last do the action corresponding to the condition on the listed items
			foreach($condition['items'] as $item){
				switch($condition['rule']){
					case "onlyshowif":
						$str.= "document.getElementById('fm-item-".$itemNames[$item]."').style.display = res ? 'block' : 'none';\n";	
						break;
					case "showif":
						$str.= "if(res) document.getElementById('fm-item-".$itemNames[$item]."').style.display = 'block';\n";
						break;
					case "hideif":
						$str.= "if(res) document.getElementById('fm-item-".$itemNames[$item]."').style.display = 'none';\n";
						break;
					case "requireonlyif":
						$str.= "fm_set_required('".$item."', (res ? 1 : 0));\n";
						break;	
					case "addrequireif":
						$str.= "if(res) fm_set_required('".$item."', 1);\n";
						break;
					case "removerequireif":
						$str.= "if(res) fm_set_required('".$item."', 0);\n";
						break;
				}
			}
			
			$str.="}\n";
			
			//this keeps track of which test functions we create depend on which items, so we can make an onchange event appropriately. 
			foreach($condition['tests'] as $test){
				switch($test['unique_name']){
					case '__always__':
					case '__never__':
						break;
					default:
						if(!isset($itemDependents[$test['unique_name']])) $itemDependents[$test['unique_name']] = array();
						$itemDependents[$test['unique_name']][] = $fn;
				}
			}
			
			//make sure the form displays according to the conditions initially, so run the tests in order
			$str.= $fn."();\n";
		}
			
		//now set the onchange event handlers
		foreach($itemDependents as $uniqueName => $itemDeps){			
			
			// build the event handler
			$tmpFnName = "fn_".uniqid();
			$str.= "function ".$tmpFnName."() { \n";			
				foreach($itemDeps as $dep){
					$str.= $dep."();\n";
				}
				// a workaround for simulating the HTML5 placeholder attribute in text fields
				$str.= "this.ph_hasEdit = true;\n";
				$str.= "}\n";
			
			$itemType = $itemTypes[ $uniqueName ];
			
			// IE behaves strangely, as per usual, so we need to hook into different events for IE
				global $is_IE;
				if ( $is_IE )
					$changeEvent = 'onclick';
				else
					$changeEvent = 'onchange';
			
			switch( $itemType ){
				
				case 'text':
				case 'textarea':
					$str.= "document.getElementById('".$uniqueName."').onchange = ".$tmpFnName.";\n";
					break;
				case 'checkbox':
					$str.= "document.getElementById('".$uniqueName."').".$changeEvent." = ".$tmpFnName.";\n";
					break;
				case 'custom_list':
					$listItem = $itemObjects[ $uniqueName ];
					switch ( $listItem['extra']['list_type'] ) {
						case 'select':
						case 'list':
							$str.= "document.getElementById('".$uniqueName."').".$changeEvent." = ".$tmpFnName.";\n";
							break;
						case 'checkbox':
						case 'radio':
							$numItems = sizeof($listItem['extra']['options']);
							for ( $x=0; $x<$numItems; $x++) {
								$str.= "document.getElementById('".$uniqueName."-".$x."').".$changeEvent." = ".$tmpFnName.";\n";
							}
							break;
						default:
							break;
					}
					break;
				default:
					break;
			}
		}	
	
		return $str;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////
///// FORM TEMPLATE FUNCTIONS /////////////////////////////////////////////////////////////////

function fm_form_start(){
	global $fm_display;

	echo "<form enctype=\"multipart/form-data\" class=\"".$fm_display->currentFormOptions['class']."\" ".
			"method=\"post\" action=\"".$fm_display->currentFormOptions['action']."\" ".
			"name=\"fm-form-".$fm_display->currentFormInfo['ID']."\" id=\"fm-form-".$fm_display->currentFormInfo['ID']."\" " .
			($fm_display->currentFormInfo['enable_autocomplete']=="1"?"autocomplete=\"on\"":""). 
			" >\n";	
}
function fm_form_class(){
	global $fm_display;
	return $fm_display->currentFormOptions['class'];
}
function fm_form_action(){
	global $fm_display;
	return $fm_display->currentFormOptions['action'];
}
function fm_form_end(){
	global $fm_display;
	$str = "</form>\n";
	echo $str;
}
function fm_form_hidden(){
	global $fm_display;
	global $post;
	
	$str = "<input type=\"hidden\" name=\"fm_nonce\" id=\"fm_nonce\" value=\"".wp_create_nonce('fm-nonce')."\" />\n";
	$str.= "<input type=\"hidden\" name=\"fm_id\" id=\"fm_id\" value=\"".$fm_display->currentFormInfo['ID']."\" />\n";
	// this is to prevent submitting the same instance of a form more than once
	$str.= "<input type=\"hidden\" name=\"fm_uniq_id\" id=\"fm_uniq_id\" value=\"fm-".uniqid()."\" />\n";
	$str.= "<input type=\"hidden\" name=\"fm_parent_post_id\" id=\"fm_parent_post_id\" value=\"".$post->ID."\" />\n";
	return $str;
}

function fm_form_ID(){
	global $fm_display;
	return "fm-form-".$fm_display->currentFormInfo['ID'];
}
function fm_form_submit_btn_script(){
	global $fm_display;
	return "return fm_submit_onclick(".$fm_display->currentFormInfo['ID'].")";
}

function fm_form_the_title(){
	global $fm_display;
	return $fm_display->currentFormInfo['title'];
}

function fm_form_the_submit_btn(){
	global $fm_display;
	if(!isset($fm_display->currentFormOptions['show_submit']) 
	|| $fm_display->currentFormOptions['show_submit'] !== false)
	{
		return "<input type=\"submit\" ".
				"name=\"".fm_form_submit_btn_name()."\" ".
				"id=\"".fm_form_submit_btn_id()."\" ".
				"class=\"submit\" ".
				"value=\"".fm_form_submit_btn_text()."\" ".
				"onclick=\"".fm_form_submit_btn_script()."\" ".
				" />\n";
	}
}

function fm_form_submit_btn_name(){
	return "fm_form_submit";
}

function fm_form_submit_btn_id(){
	return "fm_form_submit";
}

function fm_form_submit_btn_text(){
	global $fm_display;
	return htmlspecialchars($fm_display->currentFormInfo['submit_btn_text']);
}

function fm_form_have_items(){
	global $fm_display;
	$done = false;
	$index = $fm_display->currentItemIndex;
	
	while(!$done) {
		$index++;
		if( $index >= sizeof($fm_display->currentFormInfo['items']) ) {
			return false;
		}
		
		$item = $fm_display->currentFormInfo['items'][$index];
		if( $item['set'] == 0 ) {
			$done = true;
			$fm_display->nextItemIndex = $index;
		}
	}
	return true;
}

function fm_form_the_item(){
	global $fm_display;
	$fm_display->currentItemIndex = $fm_display->nextItemIndex;
}

function fm_form_the_label(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['label'];
}

function fm_form_the_input(){
	global $fm_display;
	global $fm_controls;
	$item = fm_form_get_item(); 
	if(isset($fm_display->currentFormValues[$item['unique_name']]))
		$item['extra']['value'] = $fm_display->currentFormValues[$item['unique_name']];				
	return $fm_controls[$item['type']]->showItem($item['unique_name'], $item);
}
function fm_form_the_ID(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['unique_name'];
}
function fm_form_is_separator(){
	return (fm_form_item_type() == 'separator');
}

function fm_form_is_note(){
	return (fm_form_item_type() == 'note');
}

function fm_form_item_type(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['type'];
}

function fm_form_is_required(){
	global $fm_display;
	return ($fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['required'] == '1');
}

function fm_form_the_nickname(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['nickname'];
}

function fm_form_get_item(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]; 
}

function fm_form_get_item_input($nickname){
	global $fm_display;
	global $fm_controls;
	$item = fm_summary_get_item($nickname);
	if(isset($fm_display->currentFormValues[$item['unique_name']]))
		$item['extra']['value'] = $fm_display->currentFormValues[$item['unique_name']];				
	return $fm_controls[$item['type']]->showItem($item['unique_name'], $item);
}

function fm_form_get_item_label($nickname){
	return fm_summary_get_item_label($nickname);
}

///////////////////////////////////////////////////////////////////////////////////////////////
///// EMAIL TEMPLATE FUNCTIONS ////////////////////////////////////////////////////////////////

function fm_summary_have_items(){
	return fm_form_have_items();
}

function fm_summary_the_item(){
	return fm_form_the_item();
}

function fm_summary_the_label(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['label'];
}

function fm_summary_the_type(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['type'];
}

function fm_summary_has_data(){
	global $fm_display;
	global $fmdb;
	return $fmdb->isDataCol($fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['unique_name']);
}

function fm_summary_the_value(){
	global $fm_display;
	global $fm_controls;
	$item = $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex];
	return $fm_controls[$item['type']]->parseData($item['unique_name'], $item, $fm_display->currentFormData[$item['unique_name']]);
}

function fm_summary_the_timestamp(){
	return fm_summary_get_value('timestamp');
}

function fm_summary_the_user(){
	return fm_summary_get_value('user');
}

function fm_summary_the_nickname(){
	global $fm_display;
	return $fm_display->currentFormInfo['items'][$fm_display->currentItemIndex]['nickname'];
}

function fm_summary_the_IP(){
	return fm_summary_get_value('user_ip');
}

function fm_summary_the_parent(){
	return fm_summary_get_value('parent_post_id');
}

function fm_summary_the_title(){
	global $fm_display;
	return $fm_display->currentFormInfo['title'];
}

function fm_summary_get_item_label($nickname){
	$item = fm_summary_get_item($nickname);
	return $item['label'];
}

function fm_summary_get_item_value($nickname){
	global $fm_display;
	$item = fm_summary_get_item($nickname);
	return $fm_display->currentFormData[$item['unique_name']];
}

function fm_summary_get_item($nickname){
	global $fmdb;
	global $fm_display;
	return $fmdb->getItemByNickname($fm_display->currentFormInfo['ID'], $nickname);
}

function fm_summary_get_form_info(){
	global $fm_display;
	return $fm_display->currentFormInfo;
}

function fm_summary_get_value($uniqueName){
	global $fm_display;
	return $fm_display->currentFormData[$uniqueName];
}
?>