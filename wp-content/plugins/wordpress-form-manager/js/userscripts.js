var fm_registered_form_items = [];
var fm_registered_forms = [];

/* PHP passes the form structure to JS using 'fm_register_form_item' and 'fm_register_form' */
//itemDef is an 'associative array', the item's database entry (unpacked)
function fm_register_form_item(formID, itemDef){
	itemDef.formID = formID;
	fm_registered_form_items.push(itemDef);
}

// returns the 'registered' information about the form element, essentially the DB entry
function fm_get_form_item_info(itemID){
	for(var x=0;x<fm_registered_form_items.length;x++){
		if(fm_registered_form_items[x].unique_name == itemID
			|| fm_registered_form_items[x].nickname == itemID){
			return fm_registered_form_items[x];
		}
	}
}

//returns the form element (unless it is a checkbox/radio list)
function fm_get_form_item(itemID){
	var itemInfo = fm_get_form_item_info(itemID);
	return document.getElementById('fm-form-' + itemInfo.ID)[itemInfo.unique_name];
}

//the same as the above, but for checkbox/radio lists
function fm_get_form_item_indexed(itemID, index){
	var itemInfo = fm_get_form_item_info(itemID);
	return document.getElementById('fm-form-' + itemInfo.ID)[itemInfo.unique_name + '-' + index];
}	

//does nothing for now; appropriate script should be attached to the button to begin with
function fm_register_form(formID){

}

function fm_submit_onclick(formID){
	if(!fm_check_required_items(formID)) return false;
	if(!fm_check_text_validation(formID)) return false;
	return true;
}

function fm_submit_onclick_ajax(formID, formSlug){
	if(fm_registered_forms[formID]) return false;
	fm_registered_forms[formID] = true;
	
	if(!fm_check_required_items(formID)) return false;
	if(!fm_check_text_validation(formID)) return false;
	
	var data = {
		action: 'fm_post_form',
		slug: formSlug,
		fm_id: document.getElementById('fm-form-' + formID)['fm_id'].value,
		fm_nonce: document.getElementById('fm-form-' + formID)['fm_nonce'].value
	};		
	var temp;
	
	for(var x=0;x<fm_registered_form_items.length;x++){
		if(fm_registered_form_items[x].formID == formID){
			eval("temp = " + fm_registered_form_items[x].getter_script + "(\'" + formID + "\', \'" + fm_registered_form_items[x].unique_name + "\');");
			data[fm_registered_form_items[x].unique_name] = temp;
		}
	}
	
	var ajaxurl = fm_user_I18n.ajaxurl;
	
	jQuery.post(ajaxurl, data, function(response){
		var formEL = document.getElementById('fm-form-' + formID);
		jQuery(formEL).after(response).remove();
	});
	
	return false;
}

/* Validation checks */

//check text validation
function fm_check_text_validation(formID){
	var msg = "";
	for(var x=0;x<fm_registered_form_items.length;x++){
		if(fm_registered_form_items[x].formID == formID && 
			fm_registered_form_items[x].type == 'text' &&
			!fm_item_validation_satisfied(fm_registered_form_items[x])){
			if(!temp){
				if(msg != "") msg += "\n";
				msg += fm_registered_form_items[x].validation_msg;	
			}
		}
	}
	if(msg != ""){
		alert(msg);
		return false;
	}
	return true;
}

function fm_item_validation_satisfied(itemDef){
	if(itemDef.validation_callback != ""){		
		eval("temp = " + itemDef.validation_callback + "('" + itemDef.formID + "', '" + itemDef.unique_name + "', '" + itemDef.validation_type + "');");
		return temp;
	}
	return true;
}



//check required items
function fm_check_required_items(formID){
	var msg = "";
	for(var x=0;x<fm_registered_form_items.length;x++){
		if(fm_registered_form_items[x].formID == formID && 
			!fm_item_required_satisfied(fm_registered_form_items[x])){
			if(!temp){
				if(msg != "") msg += "\n";
				msg += fm_registered_form_items[x].required_msg;	
			}
		}
	}
	if(msg != ""){
		alert(msg);
		return false;
	}
	return true;
}


function fm_item_required_satisfied(itemDef){
	if(itemDef.required == 1 && itemDef.required_callback != ""){
		eval("temp = " + itemDef.required_callback + "('" + itemDef.formID + "', '" + itemDef.unique_name + "');");
		return temp;
	}
	return true;
}

function fm_set_required(itemID, req){
	for(var x=0;x<fm_registered_form_items.length;x++){
		temp = fm_registered_form_items[x];
		if(temp.unique_name == itemID){			
			fm_registered_form_items[x].required = req;
			tempID = 'fm-item-' + (temp.nickname != "" ? temp.nickname : temp.unique_name);
			EMs = document.getElementById(tempID).getElementsByTagName('em');
			if (EMs[0] !== undefined){
				EMs[0].style.display = (req == 1 ? 'inline' : 'none');
			}
		}
	}
	
}

function fm_supports_placeholder(){
	placeholderSupport = ("placeholder" in document.createElement("input"));
	return placeholderSupport;
}


function fm_remove_placeholders(){	
	if(!fm_supports_placeholder()){
		for(var i=0;i<fm_registered_form_items.length;i++){
			switch(fm_registered_form_items[i].type) {
				case 'text':
				case 'textarea':
					formID = fm_registered_form_items[i].formID;
					itemID = fm_registered_form_items[i].unique_name;
					var textItem = document.getElementById('fm-form-' + formID)[itemID];
					textItem.value = fm_base_get_value(formID, itemID);
				break;
			}
		}
	}
}

////////////////////////////////////////////////////////////
//// HELPERS ///////////////////////////////////////////////

function fm_trim(str){
	return str.replace(/^\s+|\s+$/g,"");
}
function fm_fix_str(str){
	return str.replace(/[\\]/g,'\\\\\\\\').replace(/[']/g,'\\\\\\$&');
}
function fm_htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
function fm_array_contains(haystack, needle){
	for(var x=0;x<haystack.length;x++){
		if( haystack[x] == needle ) return true;
	}
	return false;
}

////////////////////////////////////////////////////////////
//// FORM ELEMENTS /////////////////////////////////////////

// form element base class
function fm_base_required_validator(formID, itemID){
	var e = document.getElementById('fm-form-' + formID)[itemID];
	if ( typeof(e.ph_hasEdit) != 'undefined' ){
		return ( e.ph_hasEdit );
	}	
	return (fm_trim(e.value) != "");
}
function fm_base_get_value(formID, itemID){
	var e = document.getElementById('fm-form-' + formID)[itemID];
	if ( typeof(e.ph_hasEdit) != 'undefined' ){
		if ( e.ph_hasEdit == true )
			return fm_trim(e.value);
		return "";
	}
	return fm_trim(e.value);
}

// checkbox
function fm_checkbox_required_validator(formID, itemID){
	return document.getElementById('fm-form-' + formID)[itemID].checked;
}

// list
function fm_custom_list_required_validator(formID, itemID){
	var listType = document.getElementById('fm-form-' + formID)[itemID + '-list-style'].value;
	switch(listType){
		case "radio":
			return fm_radio_list_required_validator(formID, itemID);
		case "checkbox": 
			return fm_checkbox_list_required_validator(formID, itemID);
		default:
			return fm_select_list_required_validator(formID, itemID);
	}
	return false;
}
function fm_select_list_required_validator(formID, itemID){			
	return (document.getElementById('fm-form-' + formID)[itemID].value != 0);
}
function fm_radio_list_required_validator(formID, itemID){	
	var radioList = document.getElementById('fm-form-' + formID)[itemID];
	for(var x=0;x<radioList.length;x++)
		if(radioList[x].checked == true) return true;		
	return false;
}
function fm_checkbox_list_required_validator(formID, itemID){
	var count = document.getElementById('fm-form-' + formID)[itemID + '-count'].value;
	for(var x=0;x<count;x++){
		if(document.getElementById('fm-form-' + formID)[itemID + '-' + x].checked) return true;
	}
	return false;
}