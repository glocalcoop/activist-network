<?php
/**************************************************************/
/******* API **************************************************/

//the attributes that can be specified per item.  The attribute takes the name (item nickname)_(att)
// so width becomes (item nickname)_width, with a default value of 'auto'.
$fm_tablePerItemAttributes = array(
	'width' => 'auto',
	'class' => '',
);

function fm_doPaginatedSummariesBySlugCallback($formSlug, $template, $callback, $orderBy = 'timestamp', $ord = 'DESC', $dataPerPage = 30, $options=array()){
	global $fmdb;
	
	parse_str($_SERVER['QUERY_STRING'], $queryVars);
	
	// make sure the slug is valid
	$formID = $fmdb->getFormID($formSlug);
	if($formID === false) return "(form ".(trim($formSlug)!=""?"'{$formSlug}' ":"")."not found".")";
	
	// see if 'orderby' is a valid unique name
	if($orderBy != 'timestamp' &&
		$orderBy != 'user' &&
		$orderBy != 'user_ip'){
		
		$orderByItem = $fmdb->getFormItem($orderBy);
		if($orderByItem === false) // not a valid unique name, but could be a nickname
			$orderByItem = $fmdb->getItemByNickname($formID, $orderBy);
		if($orderByItem === false) return "(orderby) ".$orderBy." not found";
		
		$orderBy = $orderByItem['unique_name'];
	}
	
	$currentPage = (isset($_REQUEST['fm-data-page']) ? $_REQUEST['fm-data-page'] : 0);
	$currentStartIndex = $currentPage * $dataPerPage;
	
	$submissionCount = $fmdb->getSubmissionDataCount($formID);
	$numPages = ceil($submissionCount / $dataPerPage);
	$pageLinkStr = "";
	
	$pageRoot = fm_helper_form_action();
	$pageRoot = substr($pageRoot, 0, strpos($pageRoot, "?"));
	
	// navigation 
	$pageLinkStr = "";
	if($numPages > 1){
		$pageLinkStr = "<p class=\"fm-data-nav\">";
		if($currentPage != 0)
			$pageLinkStr.= "<a href=\"".$pageRoot."?".http_build_query(array_merge($queryVars, array('fm-data-page' => ($currentPage - 1))))."\"><</a>&nbsp;";
		for($x=0;$x<$numPages;$x++){
			if($currentPage == $x)
				$pageLinkStr.= "<strong>".($x+1)."&nbsp;</strong>";
			else
				$pageLinkStr.= "<a href=\"".$pageRoot."?".http_build_query(array_merge($queryVars, array('fm-data-page' => $x)))."\">".($x+1)."</a>&nbsp;";		
		}
		if($currentPage != ($numPages - 1))
			$pageLinkStr.= "<a href=\"".$pageRoot."?".http_build_query(array_merge($queryVars, array('fm-data-page' => ($currentPage + 1))))."\">></a>&nbsp;";
		$pageLinkStr.= "</p>";
	}
	
	// process the form structure
	$showcols = isset($options['show']) ? explode(',', $options['show']) : false;
	$hidecols = isset($options['hide']) ? explode(',', $options['hide']) : false;
	$showmeta = isset($options['showprivate']) ? explode(',', $options['showprivate']) : false;
	$formInfo = $fmdb->getForm($formID);
	$formInfo['meta'] = $fmdb->getFormItems($formID,'1');
	
	// unset the hidden columns form structure
	foreach($formInfo['items'] as $key => $item){
		$lbl = ($item['nickname'] != "") ? $item['nickname'] : $item['unique_name'];				
		if (!fm_helper_is_shown_col($showcols, $hidecols, $lbl)) {			
			unset($formInfo['items'][$key]);
		}		
	}
	
	// the 'items' array uses integer keys. unsetting the key will leave a 'blank spot' in the array:
	$newItems = array();
	$index = 0;
	foreach( $formInfo['items'] as $item ){
		$item['index'] = $index;
		$newItems[$index++] = $item;
	}
	// add any private fields if they are selected
	if( $showmeta !== false ){
		foreach ( $formInfo['meta'] as $item ){
			$lbl = ($item['nickname'] != "") ? $item['nickname'] : $item['unique_name'];
			if( fm_helper_is_shown_col( $showmeta, false, $lbl) ){
				$item['set'] = 0;
				$item['index'] = $index;
				$newItems[$index++] = $item;
			}
		}
	}
	$formInfo['items'] = $newItems;
	
	// render the summary
	$summaryListStr = $callback($formID, $formInfo, $template, $orderBy, $ord, $currentStartIndex, $dataPerPage, $options);
	
	// put it all together
	return  $pageLinkStr.
			$summaryListStr.
			$pageLinkStr;
}

function fm_doDataTableBySlug($formSlug, $template, $orderBy = 'timestamp', $ord = 'DESC', $dataPerPage = 30, $options=array()){
	return fm_doPaginatedSummariesBySlugCallback($formSlug, $template, 'fm_getFormDataTable', $orderBy, $ord, $dataPerPage, $options);
}

function fm_getFormDataTable($formID, $formInfo, $template, $orderBy = 'timestamp', $ord = 'DESC', $startIndex = 0, $numItems = 30, $options=array()){
	global $fmdb;
	global $fm_display;
	global $fm_controls;
	
	$formData = $fmdb->getFormSubmissionData($formID, $orderBy, strtoupper($ord), $startIndex, $numItems);
	$atts = fm_helper_extractColumnAtts($formInfo, $options);
	$hasPosts = $fmdb->dataHasPublishedSubmissions($formInfo['ID']);
	
	$showcols = isset($options['show']) ? explode(',', $options['show']) : false;
	$hidecols = isset($options['hide']) ? explode(',', $options['hide']) : false;
	
	$str = "";
	$str .= '<table class="fm-data">';
	
	$tbllbl = '<tr>';
		$class = "";
		if(isset($options['col_class']))
			$class = $options['col_class'];
		
		$universalCols = array(
			'timestamp' => __("Timestamp", 'wordpress-form-manager'), 
			'user' => __("User", 'wordpress-form-manager'),
			'post' => __("Post", 'wordpress-form-manager'),
		);
		
		if($hasPosts === false) unset($universalCols['post']);
		
		foreach($universalCols as $col => $lbl){
			if (fm_helper_is_shown_col($showcols, $hidecols, $col)){
				$tbllbl.= '<th class="fm-item-header-'.$col.'" style="width:'.(isset($atts[$col.'_width']) ? $atts[$col.'_width'] : $atts['col_width']).';" ';
				$tmp = $class.' '.$atts[$col.'_class'];
				if(trim($tmp) != "") {
					$tbllbl.= ' class="'.$tmp.'"';
				}
				$tbllbl.= '>'.$lbl.'</th>';
			}
		}	
		
		foreach($formInfo['items'] as $item){
			if($fmdb->isDataCol($item['unique_name'])){
				$lbl = ($item['nickname'] != "") ? $item['nickname'] : $item['unique_name'];				
						
				$width = ' style="width:'.$atts[$item['nickname'].'_width'].';"';	
				
				$tbllbl.= '<th class="fm-item-header-'.$lbl.'"'.$width.'>'.htmlspecialchars($item['label']).'</th>';
			}
		}
	$tbllbl.= '</tr>';
	
	$str.= '<thead>'.$tbllbl.'</thead>';
	$str.= '<tfoot>'.$tbllbl.'</tfoot>';
	
	foreach($formData['data'] as $dataRow){
		$height = (isset($options['row_height'])) ? ' style="height:'.$options['row_height'].';"' : '';
		$class = (isset($options['row_class'])) ? ' class="'.$options['row_class'].'"' : '';
			
		$str .= '<tr'.$height.$class.'>';
		
		if(fm_helper_is_shown_col($showcols, $hidecols, 'timestamp'))
			$str.= '<td class="fm-item-cell-timestamp">'.$dataRow['timestamp'].'</td>';
		if(fm_helper_is_shown_col($showcols, $hidecols, 'user'))
			$str.= '<td class="fm-item-cell-user">'.$dataRow['user'].'</td>';
		if($hasPosts && fm_helper_is_shown_col($showcols, $hidecols, 'post')){
			if($dataRow['post_id'] > 0)
				$str.= '<td class="fm-item-cell-post"><a href="'.get_permalink($dataRow['post_id']).'">'.get_the_title($dataRow['post_id']).'</a></td>';
			else
				$str.= '<td class="fm-item-cell-post">&nbsp;</td>';
		}
			
		foreach($formInfo['items'] as $item){
			$lbl = ($item['nickname'] != "") ? $item['nickname'] : $item['unique_name'];
			if($fmdb->isDataCol($item['unique_name'])){				
				$tmp = $dataRow[$item['unique_name']];
				$str.=  '<td class="fm-item-cell-'.$lbl.'">'.$tmp.'</td>';
			}		
		}
		$str.= '</tr>';
	}

	$str.= '</table>';
	
	return $str;
}

function fm_helper_extractColumnAtts($formInfo, $options){
	global $fm_tablePerItemAttributes;
	$colAtts = array();
	
	foreach($fm_tablePerItemAttributes as $att => $val){
		$colAtts['timestamp_'.$att] = $val;
		$colAtts['user_'.$att] = $val;
		$colAtts['post_'.$att] = $val;
	}
			
	foreach($formInfo['items'] as $item){
		if($item['nickname'] != ""){
			foreach($fm_tablePerItemAttributes as $att => $val){
				$colAtts[$item['nickname'].'_'.$att] = $val;
			}
		}
	}
	
	$atts = shortcode_atts( $colAtts, $options );
	return $atts;
}

function fm_helper_is_shown_col($showcols, $hidecols, $lbl){
	$lbl = trim($lbl);
	if (($showcols !== false && in_array($lbl, $showcols))
		|| ($hidecols !== false && !in_array($lbl, $hidecols))
		|| ($showcols === false && $hidecols === false)) {
		return true;
	}
	return false;
}

//takes a form's slug as a string, returns paginated 
function fm_doDataListBySlug($formSlug, $template, $orderBy = 'timestamp', $ord = 'DESC', $dataPerPage = 30, $options = array()){
	return fm_doPaginatedSummariesBySlugCallback($formSlug, $template, 'fm_getFormDataSummaries', $orderBy, $ord, $dataPerPage, $options);
}

//takes a form's slug as a string, returns formatted data summaries, using the 'summary' template.
function fm_getFormDataSummaries($formID, $formInfo, $template, $orderBy = 'timestamp', $ord = 'DESC', $startIndex = 0, $numItems = 30, $options){
	global $fmdb;
	global $fm_display;
	
	// figure out which template to use
	if ( $template == '' )
		$template = $formInfo['summary_template'];
	if ( $template == '' )
		$template = $fmdb-> getGlobalSetting('template_summary');
		
	$formData = $fmdb->getFormSubmissionDataRaw($formID, $orderBy, strtoupper($ord), $startIndex, $numItems);
			
	$strArray = array();
	foreach($formData as $dataRow){
		$strArray[] = $fm_display->displayDataSummary($template, $formInfo, $dataRow);
	}
	
	return '<p class="fm-data">'.implode('</p><p class="fm-data">', $strArray).'</p>';
}

function fm_getFormID($formSlug){
	global $fmdb;
	return $fmdb->getFormID($formSlug);
}

//takes a form's slug as a string.  It has the same behavior as using the shortcode.  Displays the form (according to the set behavior)

function fm_doFormBySlug($formSlug, $options = array()){
	global $fm_display;
	global $fm_globals;
	global $fmdb;	
	global $current_user;

	// ask to not cache this page, if enabled
	if ( get_option( 'fm-disable-cache' ) == 'YES' ){		
		fm_disable_caching();
	}

	// error checking
	$formID = $fmdb->getFormID($formSlug);
	if($formID === false) return sprintf(__("(form  %s not found)", 'wordpress-form-manager'), (trim($formSlug)!=""?"'{$formSlug}' ":""));
	
	if ( !isset($fm_globals['form_info'][$formID]) ){
		$formInfo = $fmdb->getForm($formID);
		$formInfo['behaviors'] = fm_helper_parseBehaviors($formInfo['behaviors']);
	}
	else
		$formInfo = $fm_globals['form_info'][$formID];

	if ( isset($fm_globals['post_data'][$formID]) )
		$postData = $fm_globals['post_data'][$formID];
	else
		$postData = null;
		
	return fm_displayForm( $formInfo, $options, $postData );
}

// processes submitted data and calls the 'fm_form_submission' action
function fm_processPost( $formInfo ) {
	global $current_user;
	global $fmdb;
	
	// check if the form is restricted to registered users	
	if(isset($formInfo['behaviors']['reg_user_only']) && $current_user->user_login == "")
		return false;
	
	// if this is a single submission form and there is already a submission, do nothing
	if(isset($formInfo['behaviors']['single_submission'])){
		$userDataCount = $fmdb->getUserSubmissionCount($formInfo['ID'], $current_user->user_login);
		if( $userDataCount > 0 )
			return false;
	}
	
	
	if( get_option( 'fm-nonce-check' ) == "YES" ){
		
		// verify the nonce
		if( ! wp_verify_nonce($_POST['fm_nonce'],'fm-nonce') )
			return false;	
	}
			
	// process the post
	
	get_currentuserinfo();		
	$overwrite = (isset($formInfo['behaviors']['display_summ']) || isset($formInfo['behaviors']['overwrite']));
	
	// this will do the processing and the database insertion.
	$postData = $fmdb->processPost(
		$formInfo['ID'],
		array('user'=>$current_user->user_login,
			'user_ip' => fm_get_user_IP(),
			'unique_id' => $_POST['fm_uniq_id'],
			'parent_post_id' => $_POST['fm_parent_post_id'],
			),
		$overwrite
		);
		
	if ( $postData === false )
		return false;
		
	//strip slashes so the action hooks get nice data
	foreach($formInfo['items'] as $item){			
		$postData[$item['unique_name']] = stripslashes($postData[$item['unique_name']]);
	}
		
	// index the data by nickname as well
	$niceData = $postData;
	foreach( $formInfo['items'] as $item ){
		if($item['nickname'] != "")
			$niceData[$item['nickname']] = $postData[$item['unique_name']];
	}
	
	// if there was a failure, we need to stop
	if($fmdb->processFailed())
		return $postData;
		
	do_action( 'fm_form_submission', array('form' => $formInfo, 'data' => $niceData) );
	
	return $postData;
}

// show the form, summary, or acknowledgment message
function fm_displayForm( $formInfo, $options, $postData = null ){
	global $current_user;
	global $fmdb;
	global $fm_display;
	
	$formAction = fm_helper_form_action( $formInfo );	
	
	// see if this is a restricted form
	if(isset($formInfo['behaviors']['reg_user_only']) && $current_user->user_login == ""){
		$msg = empty($formInfo['reg_user_only_msg']) ? $fmdb->getGlobalSetting('reg_user_only_msg') : $formInfo['reg_user_only_msg'];
		
		// show the form, but no submit button. No danger, since this same check is done on processPost()
		if(isset($formBehaviors['allow_view'])){
			return sprintf($msg, $formInfo['title']).
			'<br/>'.
			$fm_display->displayForm($formInfo, array_merge($options, array('action' => $formAction, 'show_submit' => false)));
		}			
		else
			return sprintf($msg, $formInfo['title']);
	}
		
	// if this was a failed process, show the error message and a repopulated form
	if($fmdb->processFailed()){
		return '<em>'.$fmdb->getErrorMessage().'</em>'.
			$fm_display->displayForm($formInfo, array_merge($options, array('action' => $formAction, 'use_placeholders' => false)), $postData);
	}
	
	// 'User Profile' mode has its own quirks (used to be called 'summary' mode)
	if(isset($formInfo['behaviors']['display_summ']))
		return fm_helper_displaySummaryMode( $formInfo, $options, $postData );
	
	// otherwise, a non-null $postData indicates there was a successful submission
	if($postData !== null){		
		// show the acknowledgement
		return fm_helper_displayAck( $formInfo, $postData );
	}	
	
	return $fm_display->displayForm($formInfo, array_merge($options, array( 'action' => $formAction )));	
}

function fm_helper_displayAck( $formInfo, $postData ){
	global $fm_display;
	
	$ack = fm_getSubmissionDataShortcoded($formInfo['submitted_msg'], $formInfo, $postData);
	$output = '<p>'.$ack.'</p>';
	
	//show the automatic redirection script
	if($formInfo['auto_redirect'] == 1){
		$output.=	"<script language=\"javascript\"><!--\n".
					"setTimeout('location.replace(\"".get_permalink($formInfo['auto_redirect_page'])."\")', ".($formInfo['auto_redirect_timeout']*1000).");\n".
					"//-->\n".
					"</script>\n";
	}
	
	//show the data summary
	if( $formInfo['show_summary'] == 1 )
		$output.= $fm_display->displayDataSummary('summary', $formInfo, $postData);
		
	return $output;
}

function fm_helper_displaySummaryMode( $formInfo, $options, $postData ){
	global $current_user;
	global $fmdb;
	global $fm_display;
	
	$formID = $formInfo['ID'];
	
	$userData = $fmdb->getUserSubmissions($formID, $current_user->user_login, true);
		
	if(sizeof($userData) > 0){		//only display a summary if there is a previous submission by this user
		if(!$_REQUEST['fm-edit-'.$formID] == '1'){							
			if(!isset($formInfo['behaviors']['edit']))
				return $fm_display->displayDataSummary('summary', $formInfo, $userData[0]);
			else{
				$currentPage = get_permalink();
				$parsedURL = parse_url($currentPage);
				if(trim($parsedURL['query']) == "")
					$editLink = $currentPage."?fm-edit-".$formID."=1";
				else
					$editLink = $currentPage."&fm-edit-".$formID."=1";
				
				return $output.
						$fm_display->displayDataSummary('summary', $formInfo, $userData[0]).
						"<span class=\"fm-data-summary-edit\"><a href=\"".$editLink."\">Edit '".$formInfo['title']."'</a></span>";
			}				
		}
		else
			return $fm_display->displayForm($formInfo, array_merge($options, array('action' => fm_helper_form_action(), 'use_placeholders' => false)), $userData[0]);
	}
	
	return $fm_display->displayForm($formInfo, array_merge($options, array('action' => fm_helper_form_action(), 'use_placeholders' => false)));
}

/*****************************/
/*****************************/

function fm_helper_parseBehaviors($behaviorString){
	$arr = explode(",", $behaviorString);
	$formBehaviors = array();
	foreach($arr as $v){
		$formBehaviors[$v] = true;
	}
	return $formBehaviors;
}

?>