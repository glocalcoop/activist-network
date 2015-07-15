<?php
/*
* Template for the output of the Network Posts List as blocks
* Override by placing a file called plugins/glocal-network-content/anp-post-block-template.php in your active theme
*/ 

$html .= '<article id="post-' . $post_id . '" class="post hentry list-item TESTIFY" role="article">';

$html .= '<header class="article-header">';
if($show_thumbnail && $post_detail['post_image']) {
	//Show image
	$html .= '<div class="item-image thumbnail">';
	$html .= '<a href="' . $post_detail['permalink'] . '" class="post-thumbnail">';
	$html .= '<img class="attachment-post-thumbnail wp-post-image item-image" src="' . $post_detail['post_image'] . '">';
	$html .= '</a>';
	$html .= '</div>';
}
$html .= '<h3 class="post-title">';
$html .= '<a href="' . $post_detail['permalink'] . '">';
$html .= $post_detail['post_title'];
$html .= '</a>';
$html .= '</h3>';

if($show_meta) {
	$html .= '<div class="meta">';

	if($show_site_name) {
		$html .= '<span class="blog-name"><a href="' . $post_detail['site_link'] . '">';
		$html .= $post_detail['site_name'];
		$html .= '</a></span>';
	}

	$html .= '<span class="post-date posted-on date"><time class="entry-date published updated" datetime="' . $post_detail['post_date'] . '">';
	$html .= date_i18n( get_option( 'date_format' ), strtotime( $post_detail['post_date'] ) );
	$html .= '</time></span>';
	$html .= '<span class="post-author author vcard"><a href="' . $post_detail['site_link'] . '/author/' . $post_detail['post_author'] . '">';
	$html .= $post_detail['post_author'];
	$html .= '</a></span>';

	$html .= '</div>';
}
$html .= '</header>';

$html .= '<section class="entry-content">';
$html .= '<div class="post-excerpt" itemprop="articleBody">' . $post_detail['post_excerpt'] . '</div>';
$html .= '</section>';

if($show_meta) {
	$html .= '<footer class="article-footer">';
	$html .= '<div class="meta">';
	$html .= '<div class="post-categories cat-links tags">' . $post_categories . '</div>';
	$html .= '</div>';
	$html .= '</footer>';
}

$html .= '</article>';

?>