<?php

// This include gives us all the WordPress functionality

$options = get_option('rss_pi_feeds', array());

function array2csv(array &$array) {

	if (count($array) == 0) {
		return null;
	}
	ob_start();
	$df = fopen("php://output", 'w');
	reset($array);
	$arrayhead = array_keys($array[0]);
	$include_data = array( 'url', 'name', 'max_posts', 'author_id', 'strip_html' );
	$include_data_arrays = array( 'category_id', 'tags_id', 'keywords' );
	$include_data = array_merge( $include_data, $include_data_arrays );
	$arrayhead = array_intersect( $arrayhead, $include_data );

	fputcsv($df, $arrayhead);

	foreach ( $array as $row ) {
		$row = array_intersect_key( $row, array_flip($include_data) );
		foreach ( $row as $key => $value ) {
			if ( in_array( $key, $include_data_arrays ) ) {
				$row[$key] = implode(',',$value);
			}
		}
		fputcsv($df, $row);
	}
	fclose($df);

	return ob_get_clean();
}

function download_send_headers($filename) {

	// disable caching
	$now = gmdate("D, d M Y H:i:s");
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	header("Last-Modified: {$now} GMT");

	// force download  
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	// disposition / encoding on response body
	header("Content-Disposition: attachment;filename={$filename}");
	header("Content-Transfer-Encoding: binary");
}

if ( isset($_POST['csv_download']) && $options['settings']['is_key_valid'] ) {

	download_send_headers("data_export_" . date("Y-m-d") . ".csv");
	echo array2csv($options['feeds']);
	die();
}
/* echo "<pre>";

  print_r($options);
  exit; */
?>