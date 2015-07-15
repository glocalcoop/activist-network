<?php
function fm_disable_caching(){
	global $fm_DEBUG;
	if ( $fm_DEBUG ){
		echo '<pre>CACHING DISABLED</pre>';
	}
	if ( !defined( 'DONOTCACHEPAGE' ) ){
		define( 'DONOTCACHEPAGE', true );
	}
}

//adds slashes for single quotes; useful for putting text from a database into javascript functions
function format_string_for_js($str){
	return preg_replace("/(['])/","\\\\\${0}",preg_replace("/[\\\\]/","\\\\\\\\",$str));
}

//allows valid array expressions (and broken array expressions that will not evaluate anyway).  
function is_valid_array_expr($exprStr){
	$dbl_quote_str_lit = '[\\\\]*"([^"\\\\]|\\\\.)*[\\\\]*"';	//quotes may be slashed; this is okay
	$sngl_quote_str_lit = "[\\\\]*'([^'\\\\]|\\\\.)*[\\\\]*'";
	
	$arr_item_list_expr = "(".$dbl_quote_str_lit."|".$sngl_quote_str_lit."|[0-9]+|[(),]|array|=>|\s)*";
	return (preg_match("/^".$arr_item_list_expr."$/", $exprStr) > 0);
}

//shortens a string to a specified width; if $useEllipse is true (default), three of these characters will be '...'
function fm_restrictString($string, $length, $useEllipse = true){
	if(strlen($string)<=$length) return $string;
	if($length > 3 && $useEllipse)	return substr($string, 0, $length-3)."...";
	else return substr($string, 0, $length);
}

// get a properly adjusted for GMT date/time
function fm_get_time($format = 'Y-m-d H:i:s'){
	return gmdate( $format , ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
}
function helper_text_field($id, $label, $value, $desc = ""){
	global $fm_globalSettings;
	?>
<tr valign="top">
	<th scope="row"><label for="<?php echo $id;?>"><?php echo $label;?></label></th>
	<td><input name="<?php echo $id;?>" type="text" id="<?php echo $id;?>"  value="<?php echo $value;?>" class="regular-text" />
	<span class="description"><?php echo $desc;?></span>
	</td>
</tr>
<?php
}

function helper_checkbox_field($id, $label, $checked, $desc = ""){
	global $fm_globalSettings;
	?>
<tr valign="top">
	<th scope="row"><label for="<?php echo $id;?>"><?php echo $label;?></label></th>
	<td><input name="<?php echo $id;?>" type="checkbox" id="<?php echo $id;?>"  <?php echo $checked===true?"checked":"";?> class="regular-text" />
	<span class="description"><?php echo $desc;?></span>
	</td>
</tr>
<?php
}

function helper_option_field($id, $label, $options, $value = false, $desc = ""){
	?>
<tr valign="top">
	<th scope="row"><label for="<?php echo $id;?>"><?php echo $label;?></label></th>
	<td>
		<select name="<?php echo $id;?>" type="text" id="<?php echo $id;?>"/>
		<?php foreach($options as $k=>$v): ?>
			<option value="<?php echo $k;?>" <?php echo ($value==$k)?"selected=\"selected\"":"";?> ><?php echo $v;?></option>
		<?php endforeach; ?>
		</select>
	<span class="description"><?php echo $desc;?></span>
	</td>
</tr>
	<?php
}

function fm_write_file($fullPath, $fileData, $text = true){

	add_filter('filesystem_method', 'fm_getFSMethod');
	if(! WP_Filesystem( ) ){
		return "Could not initialize WP filesystem";
	}	
	remove_filter('filesystem_method', '_return_direct');
	
	global $wp_filesystem;
	if(! $wp_filesystem->put_contents( $fullPath, $fileData, FS_CHMOD_FILE ) ) {
		return "Error writing file";
	}
	
	return 0;
}
function fm_getFSMethod($autoMethod) {
	$method = get_option('fm-file-method'); 
	if($method == 'auto') return $autoMethod;
	return $method;
}

function fm_get_file_data( $file, $fields) {
	
	$fp = fopen( $file, 'r' );
	$file_data = fread( $fp, 8192 );
	fclose( $fp );
	
	$file_vars = fm_get_str_data($file_data, $fields);
	
	return $file_vars;
}

function fm_get_str_data($str, $fields){
	$file_vars = array();
	foreach ( $fields as $field => $regex ) {
		$matches = array();
		preg_match_all( '/^[ \t\/*#@]*' . $regex . ':(.*)$/mi', $str, $matches, PREG_OFFSET_CAPTURE);
		
		foreach($matches[1] as $match){
			$arr = array('field' => $field,
							'value' => trim($match[0])
							);
			$file_vars[$match[1]] = $arr;
		}
	}
	
	ksort($file_vars);
	
	return $file_vars;
}

function fm_get_user_IP(){
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $IPAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else $IPAddr=$_SERVER['REMOTE_ADDR'];
	return $IPAddr;
}

function fm_get_slimstat_IP_link($queryVars, $ipAddr){
	return "<a href=\"".get_admin_url(null, 'index.php')."?".http_build_query(array_merge($queryVars, array('page' => 'wp-slimstat/view/index.php', 'slimpanel' => 4, 'ip' => $ipAddr, 'ip-op' => 'equal', 'direction' => 'DESC')))."\">".$ipAddr."</a>";
}

function fm_is_private_item($itemInfo){
	return $itemInfo['set'] != 0;
}


/////////////////////////////////////////////////////////////////////////
// Data page

function fm_getDefaultDataCols(){
	$cols = array(); 
				
	$cols[] = array('attributes' => 
					array( 'class' => 'timestamp-column' ),
					'value' => __("Timestamp", 'wordpress-form-manager'),
					'key' => 'timestamp',
					'editable' => false,
					);
	
	$cols[] = array('attributes' => 
					array( 'class' => 'user-column' ),
					'value' => __("User", 'wordpress-form-manager'),
					'key' => 'user',
					'editable' => false,
					);
	
	$cols[] = array('attributes' =>
					array( 'class' => 'ip-column' ),
					'value' => __("IP Address", 'wordpress-form-manager'),
					'key' => 'user_ip',
					'editable' => false,
					);
	$cols[] = array('attributes' =>
					array( 'class' => 'parent-post-column' ),
					'value' => __("Parent", 'wordpress-form-manager'),
					'key' => 'parent_post_id',
					'editable' => false,
					'show-callback' => 'fm_getPostLink',
					);
	$cols[] = array('attributes' =>
					array( 'class' => 'post-column' ),
					'value' => __("Post Link", 'wordpress-form-manager'),
					'key' => 'post_id',
					'editable' => false,
					'show-callback' => 'fm_getPostLink',
					);
	return $cols;
}

function fm_getPostLink($col, $dbRow){
	$postID = $dbRow[$col['key']];
	$post = get_post( $postID );  
	if($postID != 0)
		return '<a href="'.get_permalink($postID).'">'.$post->post_title.'</a>';
	else
		return "";
}

function fm_getFileLink($col, $dbRow){
	global $fm_controls;
	$link = $fm_controls['file']->parseData($col['key'], $col['item'], $dbRow[$col['key']]);
	if(strpos($link, "<a ") !== 0)
		$link = '<a class="fm-download-link" onclick="fm_downloadFile(\''.$col['item']['ID'].'\', \''.$col['item']['unique_name'].'\', \''.$dbRow['unique_id'].'\')" >'.$link.'</a>';
	return $link;
}

function fm_getTableCol($item){
	$col = array( 
		'value' => (empty($item['nickname']) ? htmlspecialchars($item['label']) : $item['nickname']),
		'key' => $item['unique_name'],
		'item' => $item,
		'editable' => true,
		);
	
	if($item['type'] == 'file'){
		$col['show-callback'] = 'fm_getFileLink';
		$col['value'] = '<a class="fm-download-link" onclick="fm_downloadAllFiles(\''.$col['item']['ID'].'\', \''.$col['item']['unique_name'].'\')" >'.$col['value'].'</a>';
	}
	
	return $col;
}

function fm_dataBuildTableCols($form, $subMetaFields, &$cols){
	foreach($form['items'] as $item){
		if($item['db_type'] != "NONE"){
			$newCol = fm_getTableCol($item);
			$cols[] = $newCol;
		}
	}
	
	foreach($subMetaFields as $item){
		$newCol = fm_getTableCol($item);
		$cols[] = $newCol;
	}
	
	$cols = apply_filters( 'fm_data_columns' , $cols );
}

function fm_applyColSettings($fm_dataPageSettings, &$cols){
	global $fm_MEMBERS_EXISTS;
	foreach($cols as $i=>$col){
		$cols[$i]['hidden'] = in_array($col['key'], $fm_dataPageSettings['hide']);
		$cols[$i]['editable'] = !in_array($col['key'], $fm_dataPageSettings['noedit']);
		$cols[$i]['nosummary'] = in_array($col['key'], $fm_dataPageSettings['nosummary']);
		
		if($fm_MEMBERS_EXISTS){
			$cols[$i]['edit_capability'] = $fm_dataPageSettings['edit_capabilities'][$cols[$i]['key']];
			$cols[$i]['show_capability'] = $fm_dataPageSettings['show_capabilities'][$cols[$i]['key']];
		}
	}
}

function fm_userCanEditCol( $col , $summaryPage = false ){
	global $fm_MEMBERS_EXISTS;
		
	if(!fm_userCanViewCol( $col, $summaryPage )) return false;
	if(!$summaryPage && !$col['editable']) return false;
	return (!$fm_MEMBERS_EXISTS || trim($col['edit_capability']) == "" || current_user_can($col['edit_capability']));
}

function fm_userCanViewCol( $col , $summaryPage = false ){
	global $fm_MEMBERS_EXISTS;
	
	if($summaryPage && $col['nosummary']) return false;
	elseif(!$summaryPage && $col['hidden']) return false;
	
	if($fm_MEMBERS_EXISTS 
		&& ! (trim($col['show_capability']) == "" 
			|| current_user_can($col['show_capability'])) ){
		return false;
	}
	return true;
}

function fm_userCanGetCSV(){
	global $fm_MEMBERS_EXISTS;
	if(!$fm_MEMBERS_EXISTS) return true;
	if(current_user_can('form_manager_data_csv')) return true;
	return false;
}

function fm_userCanViewData(){
	global $fm_MEMBERS_EXISTS;
	if(!$fm_MEMBERS_EXISTS) return true;
	if(current_user_can('form_manager_data')) return true;
	return false;
}

function fm_getColQueryList( &$cols ){
	$list = array();
	foreach($cols as $col){
		if(trim($col['key']) != "" 
		&& !in_array($col['key'], $list)
		&& fm_userCanViewCol( $col )){
			$list[] = '`'.$col['key'].'`';
		}
	}
	return implode(", ", $list);
}

// post processing

function fm_getCheckedItems(){
	$numrows = $_POST['fm-num-rows'];
	$checked=array();
	for($x=0;$x<$numrows;$x++){
		$rowID = $_POST['cb-'.$x];
		if(isset($_POST['cb-'.$rowID])){
			$checked[] = $rowID;
		}
	}
	return $checked;
}

function fm_getEditItems(){
	$numrows = $_POST['fm-num-rows'];
	$edit = array();
	for($x=0;$x<$numrows;$x++){
		$rowID = $_POST['cb-'.$x];
		if($_POST['cb-'.$rowID] == 'edit'){
			$edit[] = $rowID;
		}
	}
	return $edit;
}

function fm_getEditPost($subID, $cols, $isSummary = false){
	global $fm_controls;
	global $fm_MEMBERS_EXISTS;
	
	$data=array();
	foreach($cols as $col){
		if(isset($col['item'])
		&& (fm_userCanEditCol( $col, $isSummary ))
		){
			$item = $col['item'];
			$postName = $subID.'-'.$item['unique_name'];
			$processed = $fm_controls[$item['type']]->processPost($postName, $item);
			if($processed !== NULL)
				$data[$item['unique_name']] = $processed;
		}
	}
	return $data;
}

function fm_createCSVDownload($formID, $query){
	global $fmdb;
	
	$optKey = uniqid();
	$optName = 'fm-csv-query-'.$optKey;

	wp_schedule_single_event(time()+3600, 'fm_clear_temp', $optName);

	update_option( $optName, $query );
	//$csvData = $fmdb->getFormSubmissionDataCSV($formID, $query);
	
	return $optKey;
}

function fm_createCSVFile($formID, $query, $fullpath){
	global $fmdb;

	$csvData = $fmdb->getFormSubmissionDataCSV($formID, $query);
	
	return fm_write_file( $fullpath, $csvData );
}

add_action('fm_clear_temp','fm_clearTempOption');
function fm_clearTempOption($optName){
	delete_option( $optName );
}

function fm_getTmpPath(){
	return  WP_PLUGIN_DIR."/".dirname(plugin_basename(__FILE__))."/".get_option("fm-temp-dir")."/";
}

function fm_getTmpURL(){
	return WP_PLUGIN_URL."/".dirname(plugin_basename(__FILE__))."/".get_option("fm-temp-dir")."/";
}

function fm_strip_tags($str){
	if ( get_option('fm-strip-tags') == "YES" ){
		return strip_tags($str, get_option('fm-allowed-tags'));
	} else {
		return htmlspecialchars($str);
	}
}

function fm_helper_sendEmail($formInfo, $postData){
	global $fmdb;
	global $current_user;
	global $fm_display;
	
	if($formInfo['use_advanced_email'] == 1){
		$metaForm = $formInfo;
		$metaItems = $fmdb->getFormItems( $formInfo['ID'], 1 );
		$metaForm['items'] = array_merge( $formInfo['items'], $metaItems );
		
		$advEmail = new fm_advanced_email_class($metaForm, $postData);

		$emails = $advEmail->generateEmails($formInfo['advanced_email']);
						
		foreach($emails as $email){				
			$headerStr = "";
			foreach($email['headers'] as $header => $value)
				$headerStr.= $header.": ".$value."\r\n";
			fm_sendEmail($email['to'], $email['subject'], $email['message'], $headerStr);
		}
		return true;
	}
	
	$formInfo['email_list'] = trim($formInfo['email_list']) ;
	$formInfo['email_user_field'] = trim($formInfo['email_user_field']);		
		
	if($formInfo['email_list'] != ""
	|| $formInfo['email_user_field'] != "" 
	|| $fmdb->getGlobalSetting('email_admin') == "YES"
	|| $fmdb->getGlobalSetting('email_reg_users') == "YES"){
	
		$subject = fm_getSubmissionDataShortcoded($formInfo['email_subject'], $formInfo, $postData);	
		$message = $fm_display->displayDataSummary('email', $formInfo, $postData);
		$message = '<html><body>'.$message.'</body></html>';
		$headers  = 'From: '.fm_getSubmissionDataShortcoded($formInfo['email_from'], $formInfo, $postData)."\r\n".
					'Reply-To: '.fm_getSubmissionDataShortcoded($formInfo['email_from'], $formInfo, $postData)."\r\n".
					'MIME-Version: 1.0'."\r\n".
					'Content-type: text/html; charset=utf-8'."\r\n".
					'Content-Transfer-Encoding: 8bit'."\r\n";
		
		$temp = "";
		if($fmdb->getGlobalSetting('email_admin') == "YES")
			fm_sendEmail(get_option('admin_email'), $subject, $message, $headers);
			
		if($fmdb->getGlobalSetting('email_reg_users') == "YES"){
			if(trim($current_user->user_email) != ""){
				if( ($fmdb->getGlobalSetting('email_admin') == "YES" && $current_user->user_email != get_option('admin_email') )
					|| $fmdb->getGlobalSetting('email_admin') != "YES" ){
						fm_sendEmail($current_user->user_email, $subject, $message, $headers);
				}
			}
		}
		if($formInfo['email_list'] != "")
			fm_sendEmail($formInfo['email_list'], $subject, $message, $headers);
			
		if($formInfo['email_user_field'] != "")
			fm_sendEmail($postData[$formInfo['email_user_field']], $subject, $message, $headers);
	}
}

function fm_helper_publishPost($formInfo, &$postData){
	global $fm_display;
	global $fmdb;
	
	//use the same shortcodes as the e-mails
	$advEmail = new fm_advanced_email_class($formInfo, $postData);
	$parser = new fm_custom_shortcode_parser($advEmail->shortcodeList, array($advEmail, 'emailShortcodeCallback'));
	$postTitle = $parser->parse($formInfo['publish_post_title']);
	
	$newPost = array(
		'post_title' => sprintf($postTitle, $formInfo['title']),
		'post_content' => $fm_display->displayDataSummary('summary', $formInfo, $postData),
		'post_status' => (trim($formInfo['publish_post_status']) == "" ? 'publish' : $formInfo['publish_post_status']),
		'post_author' => 1,
		'post_category' => array($formInfo['publish_post_category'])
	);
	
	// Insert the post into the database
	$postID = wp_insert_post($newPost, false);
	if($postID != 0){					
		$fmdb->updateDataSubmissionRow($formInfo['ID'], $postData['timestamp'], $postData['user'], $postData['user_ip'], array('post_id' => $postID));
	}
	
	$postData['post_id'] = $postID;
}

function fm_helper_form_action( $formInfo = null ){
	if ( is_array( $formInfo ) 
		&& trim($formInfo['exact_form_action']) != "" ) {
		
		return $formInfo['exact_form_action'];
			
	}
	return get_permalink();		
}

/////////////////////////////////////////////////////////////////////////
// Custom shortcode processor

class fm_custom_shortcode_parser{

	var $shortcodeList;
	var $shortcodeCallback; 
	
	function __construct($shortcodeList, $shortcodeCallback){
		$this->shortcodeList = $shortcodeList;
		$this->shortcodeCallback = $shortcodeCallback;
	}
	
	function parse($inputStr){		
		return $this->parseShortcodes($inputStr);
	}
	
	/////////////////////////////////////////////////////////////////////////
	// Parse Shortcodes
	
	function getShortcodeRegexp() {		
		$regexp = implode('|', $this->shortcodeList);
		return '/\[('.$regexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s';
	}
	
	function parseShortcodes($inputStr){		
		return preg_replace_callback($this->getShortcodeRegexp(), $this->shortcodeCallback, $inputStr);
	}
}

class fm_custom_attribute_parser{	
	function getAttributes($str){
		$vars = array();
		$matches = array();		
		preg_match_all( '/^[ \t\/*#@]*([a-zA-Z0-9\-]+):(.*)$/mi', $str, $matches, PREG_OFFSET_CAPTURE);		
		foreach($matches[2] as $index => $match)
			$vars[$matches[1][$index][0]] = $match[0];
		return $vars;
	}
}

function fm_getSubmissionDataShortcoded($inputStr, &$formInfo, &$subData){
	$email = new fm_advanced_email_class($formInfo, $subData);
	
	$parser = new fm_custom_shortcode_parser($email->shortcodeList, array($email, 'emailShortcodeCallback'));
	
	$shortCoded = $parser->parse($inputStr);
	
	return $shortCoded;
}

function fm_sendEmail($to, $subject, $message, $headers){
	$method = get_option('fm-email-send-method');
	
	switch($method){
		case 'mail': mail($to, $subject, $message, $headers);
			break;
		case 'off':
			break;
		case 'wp_mail':
		default:
			wp_mail($to, $subject, $message, $headers);
	}
}

function fm_helper_cleanEmptyFields( &$formInfo, &$data ){
	foreach( $formInfo['items'] as $key => $item ){
		if( trim($data[$item['unique_name']]) == "" ){
			unset($formInfo['items'][$key]);
		}
	}
	$newItems = array();
	$index = 0;
	foreach( $formInfo['items'] as $item ){
		$newItems[$index++] = $item;
	}
	$formInfo['items'] = $newItems;
}
?>