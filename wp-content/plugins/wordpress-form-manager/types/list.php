<?php

class fm_customListControl extends fm_controlBase{
	
	public function getTypeName(){
		return "custom_list";
	}
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){
		return __("List", 'wordpress-form-manager');
	}		
	
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = __("New List", 'wordpress-form-manager');
		$itemInfo['description'] = __("Item Description", 'wordpress-form-manager');
		$itemInfo['extra'] = array('list_type' => 'select');
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
	
	public function showItem($uniqueName, $itemInfo){
		$fn = $itemInfo['extra']['list_type']."_showItem";
		
		if ( !isset( $itemInfo['extra']['value'] ) && isset( $itemInfo['extra']['default'] ) )
			$itemInfo['extra']['value'] = $itemInfo['extra']['default'];
		
		return $this->$fn($uniqueName, $itemInfo).
				"<input type=\"hidden\" id=\"".$uniqueName."-list-style\" value=\"".$itemInfo['extra']['list_type']."\" />".
				"<input type=\"hidden\" id=\"".$uniqueName."-count\" value=\"".sizeof($itemInfo['extra']['options'])."\" />";
		
	}
		public function select_showItem($uniqueName, $itemInfo, $disabled = false){
			
			$elem=array('type' => 'select',
						'attributes' => array('name' => $uniqueName,
												'id'=> $uniqueName
											),
						'value' => $itemInfo['extra']['value'],	
						'options' => $itemInfo['extra']['options']
						);
			
			if(isset($itemInfo['extra']['size'])) $elem['attributes']['style'] = "width:".$itemInfo['extra']['size']."px;";
			
			if($itemInfo['required'] == "1")
				$elem['options'] = array_merge(array('-1' => "..."), $elem['options']);
			if($disabled)
				$elem['attributes']['disabled'] = 'disabled';				
			return fe_getElementHTML($elem);
		}	
		public function list_showItem($uniqueName, $itemInfo, $disabled = false){
			$elem=array('type' => 'select',
						'attributes' => array('name' => $uniqueName,
												'id'=> $uniqueName,																					
												'size' => sizeof($itemInfo['extra']['options'])
											),
						'value' => $itemInfo['extra']['value'],	
						'options' => $itemInfo['extra']['options']
						);
			if(isset($itemInfo['extra']['size'])) $elem['attributes']['style'] = "width:".$itemInfo['extra']['size']."px;";
			
			if($disabled)
				$elem['attributes']['disabled'] = 'disabled';								
			return fe_getElementHTML($elem);
		}
		public function radio_showItem($uniqueName, $itemInfo, $disabled = false){
			$elem=array('type' => 'radio',
						'attributes' => array('name' => $uniqueName,
												'id'=> $uniqueName
											),
						'separator' => '<br>',
						'options' => $itemInfo['extra']['options'],
						'value' => $itemInfo['extra']['value']
						);	
			if($disabled)
				$elem['attributes']['disabled'] = 'disabled';										
			return fe_getElementHTML($elem);
		}
		public function checkbox_showItem($uniqueName, $itemInfo, $disabled = false){
			$elem=array('type' => 'checkbox_list',						
						'separator' => '<br>',
						'value' => $itemInfo['extra']['value']
						);
			$elem['options'] = array();
			for($x=0;$x<sizeof($itemInfo['extra']['options']);$x++)
				$elem['options'][$uniqueName."-".$x] = $itemInfo['extra']['options'][$x];
			if($disabled)
				$elem['attributes']['disabled'] = 'disabled';
			return '<div class="fm-checkbox-list">'.fe_getElementHTML($elem).'</div>'.
					'<input type="hidden" name="'.$uniqueName.'" id="'.$uniqueName.'" value="'.sizeof($itemInfo['extra']['options']).'" />';
		}
		
	public function showItemSimple($uniqueName, $itemInfo){
		unset($itemInfo['extra']['size']);
		return $this->showItem($uniqueName, $itemInfo);
	}
	
	public function editItem($uniqueName, $itemInfo){	
		$fn = $itemInfo['extra']['list_type']."_showItem";
		$itemInfo['extra']['value'] = $itemInfo['extra']['default'];
		unset($itemInfo['extra']['size']);
		return "<div id=\"".$itemInfo['unique_name']."-edit-value\">".$this->$fn($uniqueName, $itemInfo, true)."</div>";
	}
	
	public function processPost($uniqueName, $itemInfo){
		if(!isset($_POST[$uniqueName]))
			return NULL;
		
		$fn = $itemInfo['extra']['list_type']."_processPost";
		return $this->$fn($uniqueName, $itemInfo);
	}
		public function select_processPost($uniqueName, $itemInfo){
			if(isset($_POST[$uniqueName])){
				if($itemInfo['required'] == "1")
					return addslashes($itemInfo['extra']['options'][$_POST[$uniqueName]-1]);
				else
					return addslashes($itemInfo['extra']['options'][$_POST[$uniqueName]]);			
			}
			return "";
		}
		public function list_processPost($uniqueName, $itemInfo){
			return $this->select_processPost($uniqueName, $itemInfo);
		}
		public function radio_processPost($uniqueName, $itemInfo){
			if(isset($_POST[$uniqueName]))
				return addslashes($itemInfo['extra']['options'][$_POST[$uniqueName]]);
			return "";
		}
		public function checkbox_processPost($uniqueName, $itemInfo){
			$arr=array();
			for($x=0;$x<sizeof($itemInfo['extra']['options']);$x++){
				if(isset($_POST[$uniqueName."-".$x]))
					$arr[] = ($_POST[$uniqueName."-".$x]=="on"?$itemInfo['extra']['options'][$x]:"");
			}
			return addslashes(implode(", ", $arr));
		}
		
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();		
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'list_type', __('Style', 'wordpress-form-manager'), array('options' => array('select' => __("Dropdown", 'wordpress-form-manager'), 'list' => __("List Box", 'wordpress-form-manager'), 'radio' => __("Radio Buttons", 'wordpress-form-manager'), 'checkbox' => __("Checkboxes", 'wordpress-form-manager')), 'value' => $itemInfo['extra']['list_type']));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'size', __('Width (in pixels)', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['size']));
		$arr[] = new fm_editPanelItemCheckbox($uniqueName, 'required', __('Required', 'wordpress-form-manager'), array('checked'=>$itemInfo['required']));
		$arr[] = new fm_editPanelItemMulti($uniqueName, 'options', __('List Items', 'wordpress-form-manager'), array('options' => $itemInfo['extra']['options'], 'get_item_script' => 'fm_custom_list_options_panel_item', 'get_item_value_script' => 'fm_custom_list_option_get'));
		$arr[] = new fm_editPanelItemBase($uniqueName, 'default', __('Default', 'wordpress-form-manager'), array('value' => $itemInfo['extra']['default']));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = "\"array('options' => \" + js_multi_item_get_php_array('multi-panel-' + itemID, 'fm_custom_list_option_get') + \", 'size' => '\" + fm_get_item_value(itemID, 'size') + \"', 'list_type' => '\" + fm_get_item_value(itemID, 'list_type') + \"', 'default' => '\" + fm_get_item_value(itemID, 'default') + \"' )\"";
		$opt['required'] = $this->checkboxScriptHelper('required');
		
		return $opt;
	}
	
	//called when displaying a required form item in the user form; returns the name of a javascript function that should return 'true' only if the input is not blank
	public function getRequiredValidatorName(){ 
		return "fm_custom_list_required_validator";
	}
	
	protected function showExtraScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_custom_list_show_hide(itemID, isDone){
			if(isDone){				
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
				if(document.getElementById(itemID + '-required').checked)
					document.getElementById(itemID + '-edit-required').innerHTML = "<em>*</em>";
				else
					document.getElementById(itemID + '-edit-required').innerHTML = "";
				var listType = fm_get_item_value(itemID, 'list_type');
				var listOptions = js_multi_item_get('multi-panel-' + itemID, 'fm_custom_list_option_get');
				var defaultValue = fm_get_item_value(itemID, 'default');
				
				var itemDef;
				switch(listType){
					case "radio":
						itemDef = fm_get_radio_list_preview(itemID, listOptions);
						break;
					case "checkbox": 
						itemDef = fm_get_checkbox_list_preview(itemID, listOptions);
						break;
					case "select":
						itemDef = fm_get_select_list_preview(itemID, listOptions);
						break;
					case "list":
						itemDef = fm_get_list_list_preview(itemID, listOptions);
						break;
				}

				if(fm_trim(defaultValue) != "")
					itemDef.value = defaultValue;
				
				var data = {
					action: 'fm_create_form_element',
					elem: itemDef
				};		
				
				jQuery.post(ajaxurl, data, function(response){		
					document.getElementById(itemID + '-edit-value').innerHTML = response;
				});
			}
		}
		function fm_get_radio_list_preview(itemID, listOptions){		
			var data = {	
					type: 'radio',
					separator: '<br />',
					options: listOptions,
					attributes: { disabled: 'disabled' }
			};		
					
			return data;
		}
		function fm_get_checkbox_list_preview(itemID, listOptions){
			var data = {	
					type: 'checkbox_list',
					separator: '<br />',
					options: listOptions,
					attributes: { disabled: 'disabled' }
			};		
					
			return data;	
		}
		function fm_get_select_list_preview(itemID, listOptions){
			if(document.getElementById(itemID + '-required').checked){
				var newList = new Array();
				newList.push('...');
				for(x=0;x<listOptions.length;x++)
					newList.push(listOptions[x]);
				listOptions = newList;
			}
			
			var data = {	
					type: 'select',
					options: listOptions,
					attributes: { disabled: 'disabled' }
			};		
					
			return data;
		}
	
		
		function fm_get_list_list_preview(itemID, listOptions){
			var data = {	
					type: 'select',
					options: listOptions,
					attributes: { disabled: 'disabled', size: listOptions.length }
			};		
					
			return data;
		}

		function fm_get_select_list(itemID, listOptions){
			if(document.getElementById(itemID + '-required').checked){
				var newList = new Array();
				newList.push('...');
				for(x=0;x<listOptions.length;x++)
					newList.push(listOptions[x]);
				listOptions = newList;
			}
			
			var data = {	
					type: 'select',
					options: listOptions,
					attributes: { id: itemID, name: itemID }
			};		
					
			return data;
		}
		
		// for the multi-item library ////////////
		//script to generate the 'options' items
		function fm_custom_list_options_panel_item(itemID, optionID, optValue){
			return "<input id=\"" + optionID + "-text\" type=\"text\" value=\"" + fm_htmlEntities(optValue) + "\" style=\"width:100px;\"/>";
		}
		//script to collect info from the 'options' items
		function fm_custom_list_option_get(optionID){
			var textInput = document.getElementById(optionID + "-text");
			return textInput.value;
		}
//]]>
</script>
		<?php
	}
	public function getShowHideCallbackName(){
		return "fm_custom_list_show_hide";
	}
	
	protected function getPanelKeys(){
		return array('label','required');
	}
}

class fm_metaCustomListControl extends fm_customListControl {
	public function isSubmissionMeta() { return true; }
	public function isFormField() { return false; }
	
	protected function showExtraScripts() { }
	
	public function getShowHideCallbackName(){
		return "fm_metatext_show_hide";
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();		
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'list_type', __('Style', 'wordpress-form-manager'), array('options' => array('select' => __("Dropdown", 'wordpress-form-manager'), 'list' => __("List Box", 'wordpress-form-manager'), 'radio' => __("Radio Buttons", 'wordpress-form-manager'), 'checkbox' => __("Checkboxes", 'wordpress-form-manager')), 'value' => $itemInfo['extra']['list_type']));
		$arr[] = new fm_editPanelItemMulti($uniqueName, 'options', __('List Items', 'wordpress-form-manager'), array('options' => $itemInfo['extra']['options'], 'get_item_script' => 'fm_custom_list_options_panel_item', 'get_item_value_script' => 'fm_custom_list_option_get'));
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();		
		$opt['extra'] = "\"array('options' => \" + js_multi_item_get_php_array('multi-panel-' + itemID, 'fm_custom_list_option_get') + \", 'list_type' => '\" + fm_get_item_value(itemID, 'list_type') + \"')\"";
		return $opt;
	}
	
	public function getTypeName(){
		return "metacustom_list";
	}
	
	protected function getPanelKeys(){
		return array('label');
	}
}