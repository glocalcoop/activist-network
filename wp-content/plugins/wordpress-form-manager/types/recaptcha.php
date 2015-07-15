<?php

class fm_recaptchaControl extends fm_controlBase{
	
	var $err;
		
	public function getTypeName(){ return "recaptcha"; }
	
	/* translators: this appears in the 'Add Form Element' menu */
	public function getTypeLabel(){ return __("reCAPTCHA", 'wordpress-form-manager'); }
	
	public function getThemeList(){
		return array('red' => __("Red", 'wordpress-form-manager'), 'white' => __("White", 'wordpress-form-manager'), 'blackglass' => __("Black", 'wordpress-form-manager'), 'clean' => __("Clean", 'wordpress-form-manager'));
	}
	
	public function showItem($uniqueName, $itemInfo){
		global $fmdb;
		$publickey = $fmdb->getGlobalSetting('recaptcha_public'); 
		if($publickey == "") return __("(No reCAPTCHA API public key found)", 'wordpress-form-manager');
		
		if(!function_exists('recaptcha_get_html'))
			require_once('recaptcha/recaptchalib.php');
		
		$theme = $itemInfo['extra']['theme'];
		$themeList = $this->getThemeList();
		
		if ( ! (isset($theme) && isset($themeList[$theme]) )){
			$theme = $fmdb->getGlobalSetting('recaptcha_theme');
		}		
		
		$lang = $fmdb->getGlobalSetting('recaptcha_lang');
		if ( $lang == "" ) $lang = "en";
		
		return "<div dir=\"ltr\">" .
				"<script type=\"text/javascript\"> var RecaptchaOptions = { ".
				"theme : '".$theme."', ".
				"lang : '".$lang."', tabindex : 100 }; </script>".
				recaptcha_get_html($publickey).
				(isset($_POST['recaptcha_challenge_field'])?"<br /> <em> ".__("The reCAPTCHA was incorrect.", 'wordpress-form-manager')." </em>":"") .
				"</div>";
	}	
	
	public function processPost($uniqueName, $itemInfo){
		global $fmdb;
		$publickey = $fmdb->getGlobalSetting('recaptcha_public'); 
		$privatekey = $fmdb->getGlobalSetting('recaptcha_private');
		if($privatekey == "" || $publickey == "" ) return "";
		
		if(!function_exists('recaptcha_check_answer'))			
			require_once('recaptcha/recaptchalib.php');		
	
		$resp = recaptcha_check_answer ($privatekey,
									$_SERVER["REMOTE_ADDR"],
									$_POST["recaptcha_challenge_field"],
									$_POST["recaptcha_response_field"]);
		
		//return false;
		if (!$resp->is_valid === true) {
			// What happens when the CAPTCHA was entered incorrectly
			$this->err = $resp->error;
				return false;
		} 
		$this->err = false;
		return "";
	}
	
	public function itemDefaults(){
		$itemInfo = array();
		$itemInfo['label'] = "New reCAPTCHA";
		$itemInfo['description'] = "Item Description";
		$itemInfo['extra'] = array( 'theme' => 'default' );
		$itemInfo['nickname'] = '';
		$itemInfo['required'] = 0;
		$itemInfo['validator'] = "";
		$ItemInfo['validation_msg'] = "";
		$itemInfo['db_type'] = "NONE";
		
		return $itemInfo;
	}

	public function editItem($uniqueName, $itemInfo){
		global $fmdb;
		$publickey = $fmdb->getGlobalSetting('recaptcha_public');
		$privatekey = $fmdb->getGlobalSetting('recaptcha_private');
		if($publickey == "" || $privatekey == "") return __("You need reCAPTCHA API keys.", 'wordpress-form-manager')." <br /> ".__("Fix this in", 'wordpress-form-manager')." <a href=\"".get_admin_url(null, 'admin.php')."?page=fm-global-settings\">".__("Settings", 'wordpress-form-manager')."</a>.";
		return __("(reCAPTCHA field)", 'wordpress-form-manager');
	}
	
	public function getPanelItems($uniqueName, $itemInfo){
		$arr=array();		
		$arr[] = new fm_editPanelItemBase($uniqueName, 'label', __('Label', 'wordpress-form-manager'), array('value' => $itemInfo['label']));
		$arr[] = new fm_editPanelItemDropdown($uniqueName, 'theme', __('Style', 'wordpress-form-manager'), 
						array('options' => array_merge( array( 'default' => '...' ), $this->getThemeList() ),
							'value' => $itemInfo['extra']['theme'])
						);
		return $arr;
	}
	
	public function getPanelScriptOptions(){
		$opt = $this->getPanelScriptOptionDefaults();
		$opt['extra'] = $this->extraScriptHelper(array('theme' => 'theme'));
		return $opt;
	}
	
	public function getShowHideCallbackName(){
		return "fm_recaptcha_show_hide";
	}

	protected function showExtraScripts(){
		?><script type="text/javascript">
//<![CDATA[
		function fm_recaptcha_show_hide(itemID, isDone){
			if(isDone){
				document.getElementById(itemID + '-edit-label').innerHTML = document.getElementById(itemID + '-label').value;
			}
		}
//]]>
</script>
		<?php
	}
	
	public function showUserScripts(){
		
	}

	protected function getPanelKeys(){
		return array('label');
	}
}
?>