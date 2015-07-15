<?php

/////////////////////////////////////////////////////////////////////////
// Class to manipulate the advanced email definitions

class fm_advanced_email_class{

	var $formInfo;
	var $formData;
	var $shortcodeList = array('admin',
								'user',								
								'timestamp',
								'label',
								'item',
								'data',
								'template',
								'form');
					
	function __construct($formInfo, $formData){
		$this->formInfo = $formInfo;
		$this->formData = $formData;
	}
	
	function generateEmails($inputStr, $parseShortcodes = true){
		$parser = new fm_custom_shortcode_parser($this->shortcodeList, array($this, 'emailShortcodeCallback'));
		
		if($parseShortcodes)
			$shortCoded = $parser->parse($inputStr);
		else
			$shortCoded = $inputStr;
		
		//split into multiple definitions
		$definitions = $this->splitIntoDefinitions($shortCoded);
		return $definitions;		
	}
	
	function splitIntoDefinitions($shortCoded){
		preg_match_all('/@start/i', $shortCoded, $matches, PREG_OFFSET_CAPTURE);
		$messageStarts = array();
		foreach($matches[0] as $match)	$messageStarts[] = $match[1] + strlen($match[0]);
		
		preg_match_all('/@end/i', $shortCoded, $matches, PREG_OFFSET_CAPTURE);
		$messageEnds = array();
		foreach($matches[0] as $match)	$messageEnds[] = $match[1];
		
		$defs = array();
		foreach($messageStarts as $index=>$start){
			$temp = substr($shortCoded, $start, $messageEnds[$index] - $start);
			$defs[] = $this->divideDefinition($temp);
		}
		return $defs;
	}
	
	function divideDefinition($defStr){
		$def = array();
		preg_match('/@message\s+start/i', $defStr, $messageStart, PREG_OFFSET_CAPTURE);
		preg_match('/@message\s+end/i', $defStr, $messageEnd, PREG_OFFSET_CAPTURE);

		$headerEnd = $messageStart[0][1];
		$start = $headerEnd + strlen($messageStart[0][0]);
		$end = $messageEnd[0][1];
		$def['message'] = substr($defStr, $start, $end - $start);
		$def['headers'] = substr($defStr, 0, $headerEnd);
		
		$attParser = new fm_custom_attribute_parser();
		$atts = $attParser->getAttributes($def['headers']);
		$def['to'] = $atts['To'];
		$def['subject'] = $atts['Subject'];
		
		if(isset($atts['FM-EMAIL-NAME'])){
			$def['email-name'] = $atts['FM-EMAIL-NAME'];
			unset($atts['FM-EMAIL-NAME']);
		}
	
		unset($atts['To']);
		unset($atts['Subject']);
		$def['headers'] = $atts;
		
		return $def;
	}
		
	function emailShortcodeCallback($matches){		
		global $current_user;
		global $fmdb;
		global $fm_controls;
		global $fm_display;
		
		//return print_r($matches, true);
		switch(trim($matches[1])){
			case "admin":
				switch(trim($matches[2])){
					case "email": return get_option('admin_email');
					default: return $matches[0];
				}
				break;
			case "form":
				switch(trim($matches[2])){
					case "title": return $this->formInfo['title'];
					case "id": return $this->formInfo['ID'];
					default: return $matches[0];
				}
				break;
			case "user":
				$userData = get_userdatabylogin($this->formData['user']);
				switch(trim($matches[2])){
					case "ip": return $this->formData['user_ip'];
					case "email": return $userData->user_email;
					case "fullname": return $userData->first_name." ".$userData->last_name;
					default: return $this->formData['user'];
				}
				break;
			case "timestamp": 
				if($matches[2] != ""){
					$format = substr($matches[2],2,strlen($matches[2])-3);
					return date($format, strtotime($this->formData['timestamp']));
				}
				return $this->formData['timestamp'];			
				break;
			case "data":
				switch(trim($matches[2])){
					case "list": return $this->emailDataAsList();
					case "table": return $this->emailDataAsTable();
				}
				break;
			case "item":
				$name = trim($matches[2]);
				$item = $fmdb->getItemByNickname($this->formInfo['ID'], $name);
				switch($name){
					case 'unique_id': 		return $this->formData['unique_id'];
					
					case 'post_id': 		return $this->formData['post_id'];
					case 'post_url':		return get_permalink( $this->formData['post_id'] );
									
					case 'parent_post_id':	return $this->formData['parent_post_id'];
					case 'parent_post_url':	return get_permalink( $this->formData['parent_post_id'] );
					
					case 'user_ip': 		return $this->formData['user_ip'];
					case 'user': 			return $this->formData['user'];
					case 'timestamp': 		return $this->formData['timestamp'];
					default:				
						if($item === false)
							$item = $fmdb->getFormItem($name);
						if($item !== false)
							return $fm_controls[$item['type']]->parseData($item['unique_name'], $item, $this->formData[$item['unique_name']]);
				}
				break;
			case "label":
				$name = trim($matches[2]);
				$item = $fmdb->getItemByNickname($this->formInfo['ID'], $name);
				if($item === false)
					$item = $fmdb->getFormItem($name);
				if($item !== false)
					return $item['label'];
				break;
			case "template":
				$templateType = trim($matches[2]);
				switch($templateType){
					case "summary":
					case "email":
						return $fm_display->displayDataSummary($templateType, $this->formInfo, $this->formData);
				}
				return $fm_display->displayDataSummary('email', $this->formInfo, $this->formData);
				break;
			default: return $matches[0];
		}
		return "";
	}
	function emailDataAsList(){		
		global $fm_controls;
		
		$str = "<ul>\n";
		foreach($this->formInfo['items'] as $item){
			$str.="<li>".$item['label'].": ".$fm_controls[$item['type']]->parseData($item['unique_name'], $item, $this->formData[$item['unique_name']])."</li>\n";
		}	
		$str.= "</ul>\n";
		return $str;
	}
	
	function emailDataAsTable(){
		global $fm_controls;
		
		$str = "<table cellspacing=\"5\">\n";
		foreach($this->formInfo['items'] as $item){
			$str.="<tr><td>".$item['label']."</td><td>".$fm_controls[$item['type']]->parseData($item['unique_name'], $item, $this->formData[$item['unique_name']])."</td></tr>\n";
		}	
		$str.= "</table>\n";
		return $str;
	}
}
?>