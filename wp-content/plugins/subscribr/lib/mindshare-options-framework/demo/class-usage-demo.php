<?php
/**
 * Just require the class in your plugin or theme and you're all set...
 */

require_once("mindshare-options-framework.php");

$config = array(
	'menu'             => 'theme', //sub page to settings page
	'page_title'       => wp_get_theme().' Theme Settings', //The name of this page
	'menu_title'       => 'Theme Settings', // text to use on the menu link
	'capability'       => 'edit_theme_options', // The capability needed to view the page
	'option_group'     => 'demo_options', //the name of the option to create in the database
	'id'               => 'mcms-api-settings-temp', // meta box id, unique per page
	'fields'           => array(), // list of fields (can be added by field arrays)
	'project_path'     => 'PLUGIN', // 'THEME', 'PLUGIN', or custom path string, default is 'PLUGIN'
	'project_name'     => 'My Plugin or Theme Name', // Used for customizing text for the uninstall confirmation. Defaults to 'this'
	'google_fonts'     => TRUE,
	'reset_button'     => FALSE,
	'uninstall_button' => TRUE
);

$options_panel = new mindshare_options_framework($config);
$options_panel->OpenTabs_container('');
$options_panel->TabsListing(
	array(
		 'links' => array(
			 'options_1' => __('Contact Settings'),
			 'options_2' => __('Fancy Options'),
			 'options_3' => __('Editor Options'),
			 'options_4' => __('WordPress Options'),
			 'options_5' => __('Advanced Options'),
			 'options_6' => __('Import Export'),
		 )
	)
);

$options_panel->OpenTab('options_1');
$options_panel->Title("Contact Settings");
$options_panel->addParagraph("This is a simple paragraph");
$options_panel->addText('text_field_id', array('name' => 'My Text ', 'std' => 'std TEXT'));
$options_panel->addTextarea('textarea_field_id', array('name' => 'My Textarea ', 'std' => 'std TEXTarea'));
$options_panel->addCheckbox('checkbox_field_id', array('name' => 'My Checkbox ', 'std' => TRUE));
$options_panel->addSelect('select_field_id', array('selectkey1' => 'Select Value1', 'selectkey2' => 'Select Value2'), array('name' => 'My select ', 'std' => array('selectkey2')));
$options_panel->addRadio('radio_field_id', array('radiokey1' => 'Radio Value1', 'radiokey2' => 'Radio Value2'), array('name' => 'My Radio Filed', 'std' => array('radiokey2')));
$options_panel->CloseTab();

$options_panel->OpenTab('options_2');
$options_panel->Title("Fancy Options");
$options_panel->addTypo('typography_field_id', array('name' => "My Typography", 'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal')));
$options_panel->addImage('image_field_id', array('name' => 'My Image ', 'preview_height' => '120px', 'preview_width' => '440px'));
$options_panel->addDate('date_field_id', array('name' => 'My Date '));
$options_panel->addTime('time_field_id', array('name' => 'My Time '));
$options_panel->addColor('color_field_id', array('name' => 'My Color '));
$options_panel->CloseTab();

$options_panel->OpenTab('options_3');
$options_panel->Title("Editor Options");
$options_panel->addWysiwyg('wysiwyg_field_id', array('name' => 'My wysiwyg Editor '));
$options_panel->addCode('code_field_id', array('name' => 'Code Editor ', 'syntax' => 'php'));
$options_panel->CloseTab();

$options_panel->OpenTab('options_4');
$options_panel->Title("WordPress Options");
$options_panel->addTaxonomy('taxonomy_field_id', array('taxonomy' => 'category'), array('name' => 'My Taxonomy Select'));
$options_panel->addPosts('posts_field_id', array('post_type' => 'post'), array('name' => 'My Posts Select'));
$options_panel->addRoles('roles_field_id', array(), array('name' => 'My Roles Select'));
$options_panel->addTaxonomy('taxonomy2_field_id', array('taxonomy' => 'category', 'type' => 'checkbox_list'), array('name' => 'My Taxonomy Checkboxes'));
$options_panel->addPosts('posts2_field_id', array('post_type' => 'post', 'type' => 'checkbox_list'), array('name' => 'My Posts Checkboxes'));
$options_panel->addRoles('roles2_field_id', array('type' => 'checkbox_list'), array('name' => 'My Roles Checkboxes'));
$options_panel->CloseTab();

$options_panel->OpenTab('options_5');
$options_panel->Title("Advanced Options");
$options_panel->addSortable('sortable_field_id', array('1' => 'One', '2' => 'Two', '3' => 'three', '4' => 'four'), array('name' => "my sortable field"));

/*
 * To Create a repeater Block first create an array of fields
 * use the same functions as above but add true as a last param
 */
$repeater_fields[] = $options_panel->addText('re_text_field_id', array('name' => 'My Text '), TRUE);
$repeater_fields[] = $options_panel->addTextarea('re_textarea_field_id', array('name' => 'My Textarea '), TRUE);
$repeater_fields[] = $options_panel->addImage('image_field_id', array('name' => 'My Image '), TRUE);
/*
 * Then just add the fields to the repeater block
 */
$options_panel->addRepeaterBlock('re_', array('sortable' => TRUE, 'inline' => TRUE, 'name' => 'This is a Repeater Block', 'fields' => $repeater_fields));

/**
 * To Create a Conditional Block first create an array of fields (just like a repeater block
 * use the same functions as above but add true as a last param
 */
$Conditinal_fields[] = $options_panel->addText('con_text_field_id', array('name' => 'My Text '), TRUE);
$Conditinal_fields[] = $options_panel->addTextarea('con_textarea_field_id', array('name' => 'My Textarea '), TRUE);
$Conditinal_fields[] = $options_panel->addImage('con_image_field_id', array('name' => 'My Image '), TRUE);

/**
 * Then just add the fields to the repeater block
 */
$options_panel->addCondition(
	'conditional_fields',
	array(
		 'name'   => __('Enable conditional fields? '),
		 'desc'   => __('<small>Turn ON if you want to enable the <strong>conditional fields</strong>.</small>'),
		 'fields' => $Conditinal_fields,
		 'std'    => FALSE
	)
);
$options_panel->CloseTab();

$options_panel->OpenTab('options_6');
$options_panel->Title("Import Export");
$options_panel->addImportExport();
$options_panel->CloseTab();
$options_panel->CloseTab();

// Help Tabs
$options_panel->HelpTab(
	array(
		 'id'      => 'tab_id',
		 'title'   => 'My help tab title',
		 'content' => '<p>This is my Help Tab content</p>'
	)
);
$options_panel->HelpTab(
	array(
		 'id'       => 'tab_id2',
		 'title'    => 'My 2nd help tab title',
		 'callback' => 'help_tab_callback_demo'
	)
);

function help_tab_callback_demo() {
	echo '<p>This is my 2nd Help Tab content from a callback function</p>';
}
