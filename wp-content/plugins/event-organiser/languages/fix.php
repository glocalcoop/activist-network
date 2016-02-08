<?php

$pattern = dirname( __FILE__ ) . '/*.po';

$files = glob( $pattern );

foreach( $files as $file ) {

	echo "processing {$file}\n";

	$contents = file_get_contents( $file );
	
	if ( preg_match( '/msgid "Start Date\/Time"\nmsgstr "(.*)"/im', $contents, $matches ) ) {
		$msgstr = $matches[1];
		
		if ( $msgstr ) {
			$replacemenet = "msgid \"Start Date/Time:\"\nmsgstr \"".$msgstr."\"";
			$contents = preg_replace('/msgid \"Start Date\/Time:\"\nmsgstr (\"\")/im', $replacemenet, $contents );
			file_put_contents( $file, $contents );
		}
		
	
	
	}
	


}
