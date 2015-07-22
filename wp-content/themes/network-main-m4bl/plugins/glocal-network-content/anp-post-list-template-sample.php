<?php
/*
* Template for the output of the Network Posts List
* Override by placing a file called plugins/anp-post-list-template.php in your active theme
*/ 

$html .= '<li class="type-post list-item siteid-' . $post_detail['site_id'] . '">';
if($show_thumbnail && $post_detail['post-image']) {
	//Show image
	$html .= '<a href="' . $post_detail['permalink'] . '" class="post-thumbnail">';
	$html .= '<img class="attachment-post-thumbnail wp-post-image item-image" src="' . $post_detail['post_image'] . '">';
	$html .= '</a>';
}
$html .= '<p class="post-title">';
$html .= '<a href="' . $post_detail['permalink'] . '">';
$html .= $post_detail['post_title'];
$html .= '</a>';
$html .= '</p>';

if($show_meta) {
	$html .= '<div class="meta">';
	if($show_site_name) {
		$html .= '<span class="blog-name"><a href="' . $post_detail['site_link'] . '">';
		$html .= $post_detail['site_name'];
		$html .= '</a></span>';
	}
	$html .= '<span class="post-date posted-on date"><time class="entry-date published updated" datetime="' . $post_detail['post-date'] . '">';
	$html .= date_i18n( get_option( 'date_format' ), strtotime( $post_detail['post_date'] ) );
	$html .= '</time></span>';
	$html .= '<span class="post-author byline author vcard"><a href="' . $post_detail['site_link'] . '/author/' . $post_detail['post_author'] . '">';
	$html .= $post_detail['post_author'];
	$html .= '</a></span>';
	$html .= '</div>';
}
if($show_excerpt) {
	$html .= '<div class="post-excerpt" itemprop="articleBody">' . $post_detail['post_excerpt'] . '</div>';
}
if($show_meta) {
	$html .= '<div class="meta">';
	$html .= '<div class="post-categories cat-links tags">' . $post_categories . '</div>';
	$html .= '</div>';
}
$html .= '</li>';

?>