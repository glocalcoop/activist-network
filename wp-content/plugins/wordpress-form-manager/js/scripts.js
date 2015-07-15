////////////////////////////////////////////////////////////
///// MAIN FORMS PAGE //////////////////////////////////////

//user clicks on 'delete' for a form
function fm_deleteFormClick(formID){
	document.getElementById('fm-action').value = "delete";
	document.getElementById('fm-id').value = formID;			
	document.getElementById('fm-main-form').submit();
}	

////////////////////////////////////////////////////////////
//// FORM EDITOR ///////////////////////////////////////////

//AJAX

function fm_saveForm(){
	var doSave = true;
	if(fm_itemsWereDeleted){
		doSave = confirm(fm_I18n.save_with_deleted_items);
	}
	
	if(doSave){
		fm_save_error = false;
		document.getElementById('ajax-loading').style.visibility = 'visible';

		var data = {
				action: 'fm_save_form',
				id: document.getElementById('form-id').value,
				title: document.getElementById('title').value,				
				submitted_msg: document.getElementById('submitted_msg').value,
				submit_btn_text: document.getElementById('submit_btn_text').value,				
				shortcode: document.getElementById('shortcode').value,	
				required_msg: document.getElementById('required_msg').value,				
				show_summary: document.getElementById('show_summary').checked,
				email_list: document.getElementById('email_list').value,
				email_subject: document.getElementById('email_subject').value,
				email_from: document.getElementById('email_from').value,
				email_user_field: document.getElementById('email_user_field').value,
				auto_redirect: document.getElementById('auto_redirect').checked,
				auto_redirect_page: document.getElementById('auto_redirect_page').value,
				auto_redirect_timeout: document.getElementById('auto_redirect_timeout').value,
				template_values: { },
				items: fm_getFormItems('form-list')
		};	
		
		for(var x=0;x<fm_save_extra_vars.length;x++){
			var extraVal = fm_getItemValue(fm_save_extra_vars[x].id, fm_save_extra_vars[x].value);	
			var id = fm_save_extra_vars[x].id.toString();
			id = id.substr(3);
			data.template_values[id] = extraVal;			
		}
		
		if(!fm_save_error){		
			jQuery.post(ajaxurl, data, function(response){
				document.getElementById('message-post').value = response;
				document.getElementById('fm-main-form').submit();
			});	
		}
	}
}

var fm_save_error;
var fm_save_validators = [];

function fm_registerSaveValidator(_itemType, _fn){
	fm_save_validators[_itemType] = _fn;	
}

var fm_save_extra_vars = [];

function fm_registerExtraSaveVar(elementId, val){
	var newVar = {
		id: elementId,
		value: val
	};
	fm_save_extra_vars.push(newVar);
}

function fm_getItemValue(id, val){
	try{
		return document.getElementById(id)[val];
	}
	catch(err){
		return null;
	}
}

function fm_loadFields(){
	return confirm(fm_I18n.unsaved_changes);
}

function fm_initEditor(){
	Sortable.create('form-list',{handles:$$('a.handle')});
}

//forms editor item functions

function fm_addItem(type){
	var listUL = document.getElementById('form-list');
	var newLI = document.createElement('li');
	
	var data = {
		action: 'fm_new_item',
		type: type
	};	
	
	jQuery.post(ajaxurl, data, function(response){
		eval('itemInfo = ' + response + ';');
		newLI.innerHTML = decodeURIComponent((itemInfo['html'] + '').replace(/\+/g, '%20'));
		newLI.id = itemInfo['uniqueName'];
		newLI.className = "edit-form-menu-item postbox";
		listUL.appendChild(newLI);
		fm_initEditor();
	});
}

var fm_itemsWereDeleted = false;
function fm_deleteItem(itemID){
	var listItem = document.getElementById(itemID);
	listItem.parentNode.removeChild(listItem);
	fm_itemsWereDeleted = true;
}

function fm_getFormItems(editorID){
	var listUL = document.getElementById(editorID);
	var arr = [];
	var fail;
	for(var index=0; index<listUL.childNodes.length; index++){
		if(typeof listUL.childNodes[index].id != 'undefined'){				
			var itemID = listUL.childNodes[index].id;
			var newItem = fm_getFormItem(itemID,index);
			
			arr.push( newItem );			
		}
	}
	return arr;
}
function fm_getFormItem(itemID,index){	
	var fn = document.getElementById(itemID + '-get').value;
	var type = document.getElementById(itemID + '-type').value;
	var valid;

	eval('newItem = ' + fn + ';');
	if(typeof(fm_save_validators[type]) != 'undefined'){
		eval("valid = " + fm_save_validators[type] + "('" + 	itemID + "');");
		if(!valid)
			fm_save_error = true;
	}
		
	return newItem;
}


//helpers for scripts for saving / editing individual items
function fm_get_item_value(itemID, key){
	return document.getElementById(itemID + '-' + key).value;
}
function fm_set_item_value(itemID, key, value){
	document.getElementById(itemID + '-' + key).value = value;
}


////////////////////////////////////////////////////////////
//// DATA PAGE /////////////////////////////////////////////

function fm_dataCBColChange(){
	var rows = document.getElementById('fm-num-rows').value;
	if(rows==0) return 0;
	var c =	document.getElementById('cb-col-top').checked;
	var cbEl;
	for(var x=0;x<rows;x++){		
		cbEl = document.getElementById('cb-' + x).value;
		document.getElementById('cb-' + cbEl).checked = c;
	}
}

function fm_downloadCSV(){
	document.getElementById('csv-working').style.visibility = 'visible';
	document.getElementById('fm-csv-download-link').innerHTML = "";
	
	var data = {
		action: 'fm_create_csv',
		id: document.getElementById('form-id').value,
		title: document.getElementById('title').value
	};	

	jQuery.post(ajaxurl, data, function(response){		
		//window.open(encodeURI(response),'Download');
		document.getElementById('csv-working').style.visibility = 'hidden';
		document.getElementById('fm-csv-download-link').href = encodeURI(response);
		document.getElementById('fm-csv-download-link').innerHTML = fm_I18n.click_here_to_download;
	});	
}

function fm_downloadFile(_formID, _itemID, _subID){
	var data = {
		action: 'fm_download_file',
		id: document.getElementById('form-id').value,
		itemid: _itemID,
		subid: _subID
	};
	
	jQuery.post(ajaxurl, data, function(response){		
		window.open(response,'Download');				
	});
}

function fm_downloadAllFiles(_formID, _itemID){
	
	var data = {
		action: 'fm_download_all_files',
		id: _formID,
		itemid: _itemID			
	};
	
	jQuery.post(ajaxurl, data, function(response){
		switch(response){
			case "empty":
				alert(fm_I18n.there_are_no_files);
				break;
			case "fail":
				alert(fm_I18n.unable_to_create_zip);
			default:
				window.open(response,'Download');
		}
	});
}

function fm_toggleMoreDataOptions(){
	Effect.toggle('fm-data-more-options', 'Blind', {duration:0});
	var show = document.getElementById('fm-data-show-options');
	show.value = (show.value == "no" ? "yes" : "no");
}

function fm_pageLinkClick(pagenum){
	fm_linkAsButton('fm-data-current-page', pagenum, 'fm-main-form');
}
function fm_viewSubmissionLinkClick(subID){
	fm_linkAsButton('fm-data-view-sub-id', subID, 'fm-main-form');
}

/***************************************************************************/

var js_multi_item_count = [];
function js_multi_item_create(ulID){	
	js_multi_item_count[ulID] = 0;	
}
function js_multi_item_init(ulID){
	Sortable.create(ulID,{handles:$$('a.handle-' + ulID)});
}
function js_multi_item_add(ulID,callback,val){
	
	if(typeof js_multi_item_count[ulID] == 'undefined')
		js_multi_item_create(ulID);
	
	var UL = document.getElementById(ulID);
	var newLI = document.createElement('li');
	var newItemID = ulID + '-item-' + js_multi_item_count[ulID];
	eval("var HTML = " + callback + "('" + ulID + "', '" + newItemID + "', val);");
	newLI.innerHTML = "<table><tr><td><a class=\"handle-" + ulID + "\" style=\"cursor: move;\">" + fm_I18n.move_button + "</a></td><td>" + HTML + "</td><td><a onclick=\"js_multi_item_remove('" + newItemID + "')\">" + fm_I18n.delete_button + "</a></td></tr></table>";
	newLI.id = newItemID;
	UL.appendChild(newLI);
	js_multi_item_count[ulID]++;
	js_multi_item_init(ulID);
}
function js_multi_item_remove(itemID){
	var listItem = document.getElementById(itemID);
	listItem.parentNode.removeChild(listItem);
}
function js_multi_item_get(ulID,itemCallback){
	var UL = document.getElementById(ulID);
	var arr = [];
	var itemValue = "";
	for(var i=0;i<UL.childNodes.length;i++){
		if(typeof UL.childNodes[i].id != 'undefined'){
			eval("itemValue = " + itemCallback + "('" + UL.childNodes[i].id + "');");
			arr.push(itemValue);			
		}
	}
	return arr;
}
function js_multi_item_get_index(ulID, itemCallback, index){
	var UL = document.getElementById(ulID);
	eval("var itemValue = " + itemCallback + "('" + UL.childNodes[index].id + "');");
	return itemValue;	
}
function js_multi_item_get_php_array(ulID, itemCallback){
	var optArr = js_multi_item_get(ulID, itemCallback);
	var str = "array(";
	for(var i=0;i<optArr.length;i++){
		str += "'" + fm_fix_str(optArr[i]) + "'";
		if(i<optArr.length-1) str += ", ";
	}
	str += ")";
	return str;
}

function js_multi_item_clear(ulID){
	var UL = document.getElementById(ulID);		
	for(var i=UL.childNodes.length-1;i>=0;i--){
		if(typeof UL.childNodes[i].id != 'undefined'){
			js_multi_item_remove(UL.childNodes[i].id);
		}
	}	
}

function js_multi_item_text_entry(ulID, getcallback, setcallback){
	var UL = document.getElementById(ulID);	
	var listItems = js_multi_item_get(ulID, getcallback);
	var listItemsText = "";
	for(var x=0; x<listItems.length;x++){		
		if(x>0) listItemsText += ", ";		
		listItemsText += listItems[x];
	}
	var newListItemsText = prompt(fm_I18n.enter_items_separated_by_commas, listItemsText);
	
	var neverHappens = "@%#$*&))(";
	newListItemsText = newListItemsText.replace(/\\,/, neverHappens);
	newListItems = newListItemsText.split(",");
	
	js_multi_item_clear(ulID);
	
	var tempStr;
	for(var x=0; x<newListItems.length;x++){
		tempStr = jQuery.trim(newListItems[x].replace(neverHappens, ","));
		js_multi_item_add(ulID, setcallback, tempStr);	
	}
}

/////////////////////////////////////////////////////
//// CONDITIONS EDITOR //////////////////////////////

var fm_conditions = [];
var fm_next_new_ID = 0;

function fm_initConditionEditor(){
	Sortable.create('fm-conditions',{handles:$$('a.handle')});
}


function fm_newCondition(){
	conditionInfo = fm_getNewConditionInfo('new-' + fm_next_new_ID++);
	fm_addCondition(conditionInfo);
}
function fm_addCondition(conditionInfo){			
	var listUL = document.getElementById('fm-conditions');
	var newLI = document.createElement('li');	
	
	newLI.className = "edit-form-menu-item postbox";	
	newLI.innerHTML = fm_getNewConditionHTML(conditionInfo);	
	newLI.id = 'fm-condition-' + conditionInfo.id;	
	listUL.appendChild(newLI);	
	
	fm_initConditionBox(conditionInfo);	
	fm_initConditionEditor();		
}

function fm_getNewConditionInfo(id){
	var newCondition = {
		id: id,
		rule: '',
		tests: [],
		items: []
	};
	return newCondition;
}

function fm_showHideCondition(id){
	Effect.toggle(id + '-div', 'Blind', {duration:0.1});
	var str = document.getElementById(id + '-showhide').innerHTML;
	if(str == fm_I18n.show_button)
		document.getElementById(id + '-showhide').innerHTML = fm_I18n.hide_button;
	else
		document.getElementById(id + '-showhide').innerHTML = fm_I18n.show_button;
}

function fm_removeCondition(id){
	var LI = document.getElementById('fm-condition-' + id);
	LI.parentNode.removeChild(LI);
}

function fm_removeTest(id, index){
	var LI = document.getElementById(id + '_test_li_' + index);
	LI.parentNode.removeChild(LI);
	fm_fixConnectives(document.getElementById(id + '-tests'), id);
}

function fm_removeItem(id, index){
	var LI = document.getElementById(id + '_item_li_' + index);
	LI.parentNode.removeChild(LI);
}


function fm_addConditionTest(id){
	var listUL = document.getElementById(id + '-tests');
	var newLI = document.createElement('li');
	var count = document.getElementById(id + '-test-count').value++;
	
	newLI.className = "postbox condition-test";	
	newLI.id = id + '_test_li_' + count;
	newLI.innerHTML = fm_getTestHTML(id, false, count);
	
	listUL.appendChild(newLI);
	
	Sortable.create(id + '-tests', {onUpdate: function(el){fm_fixConnectives(el,id);}});
}
function fm_fixConnectives(el,condID){
	var str = "";
	var id = el.childNodes[0].id.toString();
	var index = id.substr(id.indexOf('_test_li_') + 9);
	
	document.getElementById(condID + '-condition-td-' + index).style.visibility = 'hidden';
	for(var x=1;x<el.childNodes.length;x++){
		id = el.childNodes[x].id.toString();
		index = id.substr(id.indexOf('_test_li_') + 9);
		document.getElementById(condID + '-condition-td-' + index).style.visibility = 'visible';
	}
}

function fm_initConditionBox(conditionInfo){
	document.getElementById('fm-condition-' + conditionInfo.id).onchange = function(){ fm_conditionBoxOnChange(conditionInfo); }
	
	Sortable.create(conditionInfo.id + '-tests', {onUpdate: function(el){fm_fixConnectives(el,conditionInfo.id);}});
	Sortable.create(conditionInfo.id + '-items');
	fm_fixConnectives(document.getElementById(conditionInfo.id + '-tests'), conditionInfo.id);
	document.getElementById('fm-condition-' + conditionInfo.id).onchange();
}
function fm_conditionBoxOnChange(conditionInfo){
	var currTestLI;
	var id;
	var index;
	var type;
	var test;
	var itemID;
	var testUL = document.getElementById(conditionInfo.id + '-tests');		

	for(var y=0;y<testUL.childNodes.length;y++){
		currTestLI = testUL.childNodes[y];
		id = currTestLI.id;			
		index = id.substr(id.indexOf('_test_li_') + 9);
		itemID = document.getElementById(conditionInfo.id + '-test-itemID-' + index).value;
		
		if(itemID == '__always__' || itemID == '__never__'){
			document.getElementById(conditionInfo.id + '-test-' + index).style.display = 'none';
			document.getElementById(conditionInfo.id + '-test-val-' + index).style.display = 'none';
		}
		else{
			type = fm_getItemType(itemID);
			testSelect = document.getElementById(conditionInfo.id + '-test-' + index);
			test = testSelect.value;
			testSelect.style.display = 'block';
			document.getElementById(conditionInfo.id + '-test-val-' + index).style.display = 'block';
			
			switch(type){
				case 'checkbox':
					if(document.getElementById(conditionInfo.id + '-test-' + index).options[1].value != 'checked'){
						jQuery(testSelect).after(fm_getCheckboxTestSelect(conditionInfo.id + '-test-' + index, '')).remove();					
					}
					break;
				default:
					if(document.getElementById(conditionInfo.id + '-test-' + index).options[1].value == 'checked'){
						jQuery(testSelect).after(fm_getTestSelect(conditionInfo.id + '-test-' + index, '')).remove();					
					}
			}
		}
	}
}

/*

	str += '<td>' + fm_getItemSelect(id + '-test-itemID-' + index, itemID) + '</td>';
	str += '<td>' + fm_getTestSelect(id + '-test-' + index, test) + '</td>';
*/

function fm_addConditionItem(id){
	var listUL = document.getElementById(id + '-items');
	var newLI = document.createElement('li');
	
	var count = document.getElementById(id + '-item-count').value++;
	
	newLI.className = "condition-item";
	newLI.innerHTML = fm_getItemHTML(id, '', count);
	newLI.id = id + '_item_li_' + count;
	
	listUL.appendChild(newLI);
	
	Sortable.create(id + '-items');
}

/* 

Rule types:

onlyshowif - only show the listed elements if X
showif - set the listed elements to 'show' if X
hideif - set the listed elements to 'hide' if X
addrequireif - make the listed elements required if X
removerequireif - make the listed elements not required if X
requiregroup - a list of elements collectively considered required, as in only one of the group needs to be populated

*/ 


/* helpers */



function fm_getNewConditionHTML(conditionInfo){
	var str = "";
	var temp;
	
	str += '<table class="condition-buttons">';
	
	str += '<tr><td class="condition-move"><a class="handle edit-form-button">' + fm_I18n.move_button + '</a></td>';
	
	str += '<td>' + fm_getRuleSelect(conditionInfo.id + '-rule', conditionInfo.rule) + '</td><td><a class="edit-form-button" id="' + conditionInfo.id + '-showhide" onclick="fm_showHideCondition(\'' + conditionInfo.id + '\')">' + fm_I18n.hide_button + '</a></td><td><a class="edit-form-button" onclick="fm_removeCondition(\'' + conditionInfo.id + '\')">' + fm_I18n.delete_button + '</a></td></tr>';
	
	str += '</table>';
	str += '<div id="' + conditionInfo.id + '-div">';
	str += '<table>';
	
	str += '<tr><td>';
		str += '<div class="condition-tests-div">';
		str += '<ul id="' + conditionInfo.id + '-tests' + '" class="condition-test-list">';
			
			for(var x=0;x<conditionInfo.tests.length;x++){
				str += '<li class="postbox condition-test" id="' + conditionInfo.id + '_test_li_' + x + '">' + fm_getTestHTML(conditionInfo.id, conditionInfo.tests[x], x) + '</li>';
//alert('bar');
			}
			
			if(conditionInfo.tests.length == 0)
				str += '<li class="postbox condition-test" id="' + conditionInfo.id + '_test_li_' + x + '">' + fm_getTestHTML(conditionInfo.id, false, x) + '</li>';

		str += '</ul>';
		str += '<input type="button" class="button secondary" value="' + fm_I18n.add_test + '" onclick="fm_addConditionTest(\'' + conditionInfo.id + '\')"/>';
		str += '<input type="hidden" name="' + conditionInfo.id + '-test-count" id="' + conditionInfo.id + '-test-count" value="' + (conditionInfo.tests.length + 1) + '" />';
		str += '<input type="hidden" name="' + conditionInfo.id + '-test-order" id="' + conditionInfo.id + '-test-order" value="" />';
		str += '</div>';
		
		str += '<div class="condition-items-div">';
		str += fm_I18n.applies_to + ':';
		str += '<ul id="' + conditionInfo.id + '-items' + '" class="condition-item-list">';
			for(var x=0;x<conditionInfo.items.length;x++)
				str += '<li class="condition-item" id="' + conditionInfo.id + '_item_li_' + x + '">' + fm_getItemHTML(conditionInfo.id, conditionInfo.items[x], x) + '</li>';
				
			if(conditionInfo.items.length == 0)
				str += '<li class="condition-item" id="' + conditionInfo.id + '_item_li_' + x + '">' + fm_getItemHTML(conditionInfo.id, false, x) + '</li>';			

		str += '</ul>';
		str += '<input type="button" class="button secondary" value="' + fm_I18n.add_item + '" onclick="fm_addConditionItem(\'' + conditionInfo.id + '\')"/>';
		str += '<input type="hidden" name="' + conditionInfo.id + '-item-count" id="' + conditionInfo.id + '-item-count" value="' + (conditionInfo.items.length + 1) + '" />';
		str += '</div>';
		
	str += '</td></tr>';
	
	str += '</table>';
	str += '</div>';
	
	return str;
}

function fm_getTestHTML(id, testInfo, index){
	var str = "";
	var itemID = "";
	var test = "";
	var connective = ( index == 0 ? "" : "and" );
	var val = "";
	if(testInfo != false){						
		itemID = testInfo.unique_name;
		test = testInfo.test;
		connective = testInfo.connective;
		val = testInfo.val;		
	}
		
	str += '<table><tr>';
	
	str += '<td id="' + id + '-condition-td-' + index + '"'; 
	if(connective == ""){ str += ' style="visibility:hidden;"';} 
	str += '>' + fm_getSelect(id + '-test-connective-' + index, ['and', 'or'], [fm_I18n.and_connective, fm_I18n.or_connective], connective) + '</td>';
	
	str += '<td>' + fm_getItemSelect(id + '-test-itemID-' + index, itemID) + '</td>';
	if(fm_getItemType(itemID) == 'checkbox')
		str += '<td>' + fm_getCheckboxTestSelect(id + '-test-' + index, test) + '</td>';
	else
		str += '<td>' + fm_getTestSelect(id + '-test-' + index, test) + '</td>';
	var textID = id + '-test-val-' + index;
	str += '<td><input type="text" size="20" id="' + textID + '" name="' + textID + '" class="test-value-input" value="' + fm_htmlEntities(val) + '"/></td>';
	str += '<td><a class="edit-form-button" onclick="fm_removeTest(\'' + id + '\', \'' + index + '\')" >&nbsp;&nbsp;&nbsp;' + fm_I18n.delete_button + '</a></td>';
	str += '</tr></table>';
	
	return str;
}

function fm_getItemHTML(id, itemID, index){
	return '<table><tr><td>' + fm_getAllItemsSelect(id + '-item-' + index, itemID) + '</td><td><a class="edit-form-button" onclick="fm_removeItem(\'' + id + '\', \'' + index + '\')">' + fm_I18n.delete_button + '</a></td></tr></table>';
}

function fm_getRuleSelect(id, rule){
	var str = "";
	
	var ruleKeys = 		['none', 'onlyshowif', 'showif', 'hideif', 'requireonlyif', 'addrequireif', 'removerequireif'];
	var ruleNames = 	[fm_I18n.choose_a_rule_type, fm_I18n.only_show_elements_if, fm_I18n.show_elements_if, fm_I18n.hide_elements_if, fm_I18n.only_require_elements_if, fm_I18n.require_elements_if, fm_I18n.do_not_require_elements_if];

	str += fm_getSelect(id, ruleKeys, ruleNames, rule);
	
	return str;
}

function fm_getTestSelect(id, test){
	var keys = 	['', 'eq', 'neq', 'lt', 'gt', 'lteq', 'gteq', 'isempty', 'nisempty'];
	var names =	[fm_I18n.empty_test, fm_I18n.equals, fm_I18n.does_not_equal, fm_I18n.is_less_than, fm_I18n.is_greater_than, fm_I18n.is_lt_or_equal_to, fm_I18n.is_gt_or_equal_to, fm_I18n.is_empty, fm_I18n.is_not_empty];
	
	return fm_getSelect(id, keys, names, test);
}

function fm_getCheckboxTestSelect(id, test){
	var keys = 	['', 'checked', 'unchecked'];
	var names =	[fm_I18n.empty_test, fm_I18n.is_checked, fm_I18n.is_not_checked];
	
	return fm_getSelect(id, keys, names, test);
}

function fm_getItemSelect(id, itemID){
	var itemIDs = ['', '__always__', '__never__'];
	var itemNames = ['...', '(' + fm_I18n.always + ')' , '(' + fm_I18n.never + ')' ];
	for(var x=0;x<fm_form_items.length;x++){
		if(fm_form_items[x].type != 'separator' &&
			fm_form_items[x].type != 'note' &&
			fm_form_items[x].type != 'recaptcha'){
				itemIDs.push(fm_form_items[x].unique_name);
				if(fm_form_items[x].nickname != "")
					itemNames.push(fm_form_items[x].nickname);
				else
					itemNames.push(fm_form_items[x].label);
			}
	}
	
	return fm_getSelect(id, itemIDs, itemNames, itemID);
}

function fm_getAllItemsSelect(id, itemID){
	var itemIDs = [''];
	var itemNames = ['...'];
	for(var x=0;x<fm_form_items.length;x++){		
		itemIDs.push(fm_form_items[x].unique_name);
		if(fm_form_items[x].nickname != "")
			itemNames.push(fm_form_items[x].nickname);
		else
			itemNames.push(fm_form_items[x].label);
	}
	
	return fm_getSelect(id, itemIDs, itemNames, itemID);
}

function fm_getSelect(id, keys, names, selected){
	var str = "";
	str += '<select id="' + id + '" name="' + id + '">';	
	for(var x=0;x<keys.length;x++){
		str += '<option value="' + keys[x] + '"';
		if(keys[x] == selected) str += ' selected="selected" ';
		str += '>' + fm_htmlEntities(names[x]) + '</option>';
	}
	str += '</select>';
	return str;
}

/* functions to register form items with javascript */

var fm_form_items = [];

function fm_register_form_item(itemInfo){
	fm_form_items.push(itemInfo);
}

function fm_getItemType(itemID){
	for(var x=0;x<fm_form_items.length;x++){
		if(fm_form_items[x].unique_name == itemID || fm_form_items[x].nickname == itemID) 
			return fm_form_items[x].type;
	}
}


/* save script */

function fm_saveConditions(){
	var mainUL = document.getElementById('fm-conditions');
	
	var str = "";
	var IDstr = "";
	
	var currCondID;
	var currCondTestUL;
	var currTestLI;
	
	var testIDs;
	var id;
	
	for(var x=0;x<mainUL.childNodes.length;x++){
		//prefix is 'fm-condition-'
		currCondID = mainUL.childNodes[x].id.substr(13);
		if(x>0) IDstr += ",";
		IDstr += currCondID;
		
		currTestUL = document.getElementById(currCondID + '-tests');
		
		str = "";
		for(var y=0;y<currTestUL.childNodes.length;y++){
			currTestLI = currTestUL.childNodes[y];
			id = currTestLI.id;
			if(y>0) str += ",";
			str += id.substr(id.indexOf('_test_li_') + 9);
		}
		
		document.getElementById(currCondID + '-test-order').value = str;
	}
	document.getElementById('fm-conditions-ids').value = IDstr;	
	
	return true;
}

////////////////////////////////////////////////////////////
//// SUBMISSION META EDITOR ////////////////////////////////

function fm_saveSubmissionMetaItems(){

	var data = {
			action: 'fm_save_submission_meta',
			id: document.getElementById('fm-form-id').value,
			items: fm_getFormItems('form-list')
	};	

	jQuery.post(ajaxurl, data, function(response){		
		document.getElementById('message-post').value = response;
		document.getElementById('fm-main-form').submit();
	});	
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
function fm_linkAsButton(hiddenID, hiddenVal, formID){
	document.getElementById(hiddenID).value = hiddenVal;
	document.getElementById(formID).submit();
}

////////////////////////////////////////////////////////////
//// FORM ELEMENTS /////////////////////////////////////////

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