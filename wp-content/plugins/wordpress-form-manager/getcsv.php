<?php

function include_wp_head($src)
{
    $paths = array(
        ".",
        "..",
        "../..",
        "../../..",
        "../../../..",
        "../../../../..",
        "../../../../../..",
        "../../../../../../.."
    );
   
    foreach ($paths as $path) {
        if(file_exists($path . '/' . $src)) {
            return $path . '/' . $src;
        }
    }
}

$include = include_wp_head('wp-load.php');

include_once($include);

global $fmdb;

if ( isset( $_REQUEST[ 'q' ] ) ){
	$key = $_REQUEST[ 'q' ];
	$query = get_option( 'fm-csv-query-'.$key );
	$csvData = $fmdb->getFormSubmissionDataCSV($_REQUEST['id'], $query);
	
	$formInfo = $fmdb->getForm($_REQUEST['id']);
	
	header("Content-type: application/csv");
	header("Content-Disposition: attachment; filename=\"".$formInfo['title'].".csv\"");
	header("Pragma: no-cache");
	header("Expires: 0");

	echo $csvData;
}	

?>