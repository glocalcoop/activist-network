<?php 

/* Grab our products */

$url = 'http://kraftpress.it/edd-api/products/';
$params = array('key' => 'ae263077f0e205f2b8a719bec86c9e3c', 'token' => '3f9e2a4a9ad1b9c9115844e31df6720f');
$url .= '?'.http_build_query($params);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$data = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if($status == 200) {

	$products = json_decode($data, true);
	
	$promotions = array();
	
	foreach($products['products'] as $p) {
		if($p['info']['status'] == 'publish' && $p['info']['slug'] != $plugin) {
			foreach($p['info']['tags'] as $tag) {
				if($tag['slug'] == 'cross-promote' || $tag['slug'] == $plugin.'-extension') {
					$promotions[] = $p;
				}
			}
		}
	}
}

$output = '';

$output .= '<img id="kp-logo" src="http://kraftpress.it/wp-content/themes/kraftpress/img/kraftpress-logo.png" alt="Kraftpress Premium WordPress Plugins" width="80%" height="auto" />
				<h3>Premium WordPress Plugins</h3><p>BUILT FROM SCRATCH WITH ❤</p>';

if(isset($promotions)) {

$output .= '<ul id="kp-cross-promote">';			
	
	foreach($promotions as $promo) {
		$output .= '
		
		<li>
		
			<a target="_BLANK" href="'.$promo['info']['link'].'" title="'.$promo['info']['title'].'"><img src="'.$promo['info']['thumbnail'].'" alt="'.$promo['info']['title'].'" /></a>
		
			<a target="_BLANK" href="'.$promo['info']['link'].'" title="'.$promo['info']['title'].'">'.$promo['info']['title'].'</a>
		
		</li>';
	}

$output .= '</ul><hr>';

}

$output .= '<p><a href="http://kraftpress.it" title="Kraftpress Premium WordPress Plugins & Themes">see more @ kraftpress.it</a></p>';

echo $output;
