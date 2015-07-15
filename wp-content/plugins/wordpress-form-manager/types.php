<?php
include 'formelements/formelements.php';

///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//associative array: 'type name' => 'class name'
// the keys in this array are used in the 'addItem' AJAX to create new items, and as the 'type' db field for form items
$fm_controlTypes = array('default' => 			'fm_controlBase',
						'text' => 				'fm_textControl',
						'metatext' => 			'fm_metaTextControl',						
						'textarea' => 			'fm_textareaControl',
						'metatextarea' => 		'fm_metaTextareaControl',
						'checkbox' => 			'fm_checkboxControl',
						'metacheckbox' => 		'fm_metaCheckboxControl',
						'custom_list' => 		'fm_customListControl',
						'metacustom_list' =>	'fm_metaCustomListControl',
						'separator' => 			'fm_separatorControl',
						'note' => 				'fm_noteControl',
						'recaptcha' => 			'fm_recaptchaControl',
						'file' => 				'fm_fileControl',
						'metaidnumber' =>		'fm_metaIDNumberControl',
						'metatracknumber' =>	'fm_metaTrackNumberControl',
);
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//control base class
include 'types/base.php';

//control types
include 'types/separator.php';
include 'types/text.php';
include 'types/textarea.php';
include 'types/checkbox.php';
include 'types/list.php';
include 'types/note.php';
include 'types/recaptcha.php';
include 'types/file.php';

//'panel' helpers
include 'types/panelhelper.php';

///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

function fm_showControlScripts(){
	?><script type="text/javascript">
//<![CDATA[
	function fm_showEditDivCallback(itemID, callback){		
		var editDiv = document.getElementById(itemID + '-edit-div');
		var editClick = document.getElementById(itemID + '-edit');
				
		if(editClick.innerHTML == '<?php _e("Edit", 'wordpress-form-manager');?>'){
			if(callback != "") eval(callback + '(itemID,false);');
			editClick.innerHTML = '<?php _e("Hide", 'wordpress-form-manager');?>';
			Effect.BlindDown(itemID + '-edit-div', { duration: 0.2 });
		}
		else{
			if(callback != "") eval(callback + '(itemID,true);');
			editClick.innerHTML = '<?php _e("Edit", 'wordpress-form-manager');?>';
			Effect.BlindUp(itemID + '-edit-div', { duration: 0.2 });
		}	
	}
//]]>
</script><?php
}	

////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

function fm_buildControlTypes($controlTypes){
	$arr = array();
	foreach($controlTypes as $name=>$class){
		$arr[$name] = new $class();
	}
	
	$arr = apply_filters( 'fm_control_types', $arr );
	
	return $arr;
}

global $fm_controls;

$fm_controls = fm_buildControlTypes($fm_controlTypes);

?>