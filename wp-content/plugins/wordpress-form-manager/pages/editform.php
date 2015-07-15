<?php
global $fm_MEMBERS_EXISTS;
global $fmdb;

parse_str($_SERVER['QUERY_STRING'], $queryVars);

$pages = array( 'design' =>
					array('sec' => 'design',
							'title' => __("Edit this form", 'wordpress-form-manager'),
							'linktext' => __("Edit", 'wordpress-form-manager'),
							'capability' => 'form_manager_forms',
							'page' => 'editformdesign.php'
					),
				'data' => 
				 	array('sec' => 'data',
							'title' => __("View form data", 'wordpress-form-manager'),
							'linktext' => __("Submission Data", 'wordpress-form-manager'),
							'capability' => 'form_manager_data',
							'page' => 'formdata.php'
					),
				'datasingle' => 
				 	array('sec' => 'datasingle',
						'title' => __("View form data", 'wordpress-form-manager'),
						'linktext' => __("Submission Data", 'wordpress-form-manager'),
						'capability' => 'form_manager_data_summary',
						'page' => 'formdatasingle.php',
						'parent' => 'data'
					),
				'nicknames' =>
					array('sec' => 'nicknames',
						'title' => __("Form extra", 'wordpress-form-manager'),
						'linktext' => __("Form Extra", 'wordpress-form-manager'),
						'capability' => 'form_manager_nicknames',
						'page' => 'editformnn.php'
					),
				'conditions' =>
					array('sec' => 'conditions',
						'title' => __("Conditional behavior", 'wordpress-form-manager'),
						'linktext' => __("Conditions", 'wordpress-form-manager'),
						'capability' => 'form_manager_conditions',
						'page' => 'editformcond.php'
					),
				'advanced' => 
					array('sec' => 'advanced',
						'title' => __("Advanced form settings", 'wordpress-form-manager'),
						'linktext' => __("Advanced", 'wordpress-form-manager'),
						'capability' => 'form_manager_forms_advanced',
						'page' => 'editformadv.php'
					)				
		);
		
$pages = apply_filters( 'fm_form_editor_tabs', $pages );

// show the tabs

?>
<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div>
	<h2><?php _e("Edit Form", 'wordpress-form-manager'); ?></h2>
	<div id="fm-editor-tabs-wrap">
		<?php
		$arr = array();
		foreach($pages as $key => $page){
			if(!$fm_MEMBERS_EXISTS || current_user_can($page['capability'])){
				if(!isset($page['parent'])){
					if(isset($_REQUEST['sec']) && $_REQUEST['sec'] == $page['sec'])
						$arr[] = "<a class=\"nav-tab nav-tab-active\" href=\"".get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=".$page['sec']."&id=".$_REQUEST['id']."\" title=\"".$page['title']."\" />".$page['linktext']."</a>";
					else
						$arr[] = "<a class=\"nav-tab nav-tab-inactive\" href=\"".get_admin_url(null, 'admin.php')."?page=fm-edit-form&sec=".$page['sec']."&id=".$_REQUEST['id']."\" title=\"".$page['title']."\" />".$page['linktext']."</a>";	
				}
			}
		}
		
		foreach($arr as $a){
			echo '<span class="fm-editor-tab">'.$a.'</span>';
		}
		
		?>
	</div>
	
</div>

<?php 

// show the appropriate page
$found = false;
foreach($pages as $page)
	if(isset($queryVars['sec']) && $queryVars['sec'] == $page['sec'] &&
		(!$fm_MEMBERS_EXISTS || current_user_can($page['capability']))){
			if(isset( $page['page_function'] )){
				$page['page_function']();
			}
			else{
				include dirname(__FILE__).'/'.$page['page'];
			}
			$found = true;
	}


if (!$found && (!$fm_MEMBERS_EXISTS || current_user_can('form_manager_forms')))
	include dirname(__FILE__).'/editformdesign.php';

?>