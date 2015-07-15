<?php

/* 
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

$meeting_agenda = wpautop( get_post_meta($post->ID, 'meeting_agenda', true), true );
$meeting_links = get_post_meta($post->ID, 'meeting_links', true);
$meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) );
$meeting_type = get_the_term_list( $post->ID, 'anp_meetings_type', '<span class="category">', ', ', '</span>' );
$meeting_tags = get_the_term_list( $post->ID, 'anp_meetings_tag', '<span class="tags">', ', ', '</span>' );

/* 
 * Before the_content()
 * Content that appears before the_content()
 */

$meeting_pre_content = '';

$meeting_pre_content .= '<div class="pre-content">';

$meeting_pre_content .= '<p class="meta"><span class="meta-label">' . __( 'Date:', 'anp_meetings' ) . '</span> ' . $meeting_date . '</p>';
$meeting_pre_content .= '<p class="meta"><span class="meta-label">' . __( 'Type:', 'anp_meetings' ) . '</span> ' . $meeting_type . '</p>';

if( !empty($meeting_agenda) ) {
	$meeting_pre_content .= '<div class="meeting-agenda">';
	$meeting_pre_content .= '<h2>' . __( 'Agenda', 'anp_meetings' ) . '</h2>';
	$meeting_pre_content .= $meeting_agenda;
	$meeting_pre_content .= '</div>';
}
$meeting_pre_content .= '</div>';

$meeting_pre_content .= '<div class="post-content">';
$meeting_pre_content .= '<h2 id="meeting-notes">' . __( 'Notes', 'anp_meetings' ) . '</h2>';

/* 
 * After the_content()
 * Content that appears after the_content()
 */

$meeting_post_content = '';
$meeting_post_content .= '<footer class="post-footer">';

if( !empty($meeting_links) ) {
	$meeting_post_content .= '<h3 id="meeting-links">Associated Content</h3>';
	$meeting_post_content .= '<ul class="meeting-links">';

	foreach($meeting_links as $link) {
		$meeting_post_content .= '<li>';
		$meeting_post_content .= '<a href="' . get_permalink( $link ) . '">';
		$meeting_post_content .= get_the_title( $link );
		$meeting_post_content .= '</a>';
		$meeting_post_content .= '</li>';
	}

	$meeting_post_content .= '</ul>';
}

$meeting_post_content .= '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'anp_meetings' ) . '</span> ';
$meeting_post_content .= $meeting_tags;
$meeting_post_content .= '</p>';

$meeting_post_content .= '</footer>';
$meeting_post_content .= '</div>';


?>