<?php
/*
* Template for the output of the Network Sites as list
* Override by placing a file called plugins/glocal-network-content/anp-sites-list-template.php in your active theme
*/


$html .= '<li id="site-' . $site_id . '" data-posts="' . $site['post_count'] . '" data-slug="' . $slug . '" data-id="' . $site_id . '" data-updated="' . $site['last_updated'] . '">' ;
if($show_image) {
	$html .= '<a href="' . $site['siteurl'] . '" class="item-image site-image" title="' . $site['blogname'] . '" style="background-image:url(\''. $site['site-image'] .' \')">';
	$html .= '</a>';
}
$html .= '';
$html .= '<h3 class="site-name">';
$html .= '<a href="' . $site['siteurl'] . '">';
$html .= $site['blogname'];
$html .= '</a>';
$html .= '</h3>';
if($show_meta) {
	$html .= '<div class="meta">';

	$html .= '<time>';
	$html .= date_i18n( get_option( 'date_format' ), strtotime( $site['last_updated'] ) );
	$html .= '</time>';

	$html .= '<div class="recent_post">';
	$html .= '<a href="'. $site['recent_post']['permalink'] .'">';
	$html .= $site['recent_post']['post_title'];
	$html .= '</a>';
	$html .= '<div class="post-meta">';
	$html .= '<time>';
	$html .= date_i18n( get_option( 'date_format' ), strtotime( $site['recent_post']['post_date'] ) );
	$html .= '</time>';
	$html .= '</div>';
	$html .= '</div>';

	$html .= '</div>';
}

$html .= '</li>';

?>