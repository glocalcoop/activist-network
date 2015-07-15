var WPformManagerDialog = {
	init : function(ed) {
		var dom = ed.dom, f = document.forms[0], n = ed.selection.getNode(), w;
	},

	update : function() {
		var ed = tinyMCEPopup.editor, h, f = document.forms[0], st = '';
		var select_form=f.form_slug;
		var x = select_form.selectedIndex;
		var form_=select_form.options[x].value;
	
		h='[' + fm_shortcode + ' '+form_+']';
	

		ed.execCommand("mceInsertContent", false, h);
		tinyMCEPopup.close();
	}
};
function setFormValue(name, value) {
	document.forms[0].elements[name].value = value;
}
tinyMCEPopup.requireLangPack();
tinyMCEPopup.onInit.add(WPformManagerDialog.init, WPformManagerDialog);
