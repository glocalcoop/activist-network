<?php

/* 
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

$meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) );
$meeting_type = get_the_term_list( $post->ID, 'anp_meetings_type', '<span class="category">', ', ', '</span>' );
$meeting_tags = get_the_term_list( $post->ID, 'anp_meetings_tag', '<span class="tags">', ', ', '</span>' );

/* 
 * Before the_content()
 * Content that appears before the_content()
 */

$meeting_pre_content = '';

$meeting_pre_content .= '<div class="pre-content">';

// $meeting_pre_content .= '<p class="meta"><span class="meta-label">' . __( 'Date:', 'anp_meetings' ) . '</span> ' . $meeting_date . '</p>';
// $meeting_pre_content .= '<p class="meta"><span class="meta-label">' . __( 'Type:', 'anp_meetings' ) . '</span> ' . $meeting_type . '</p>';

$meeting_pre_content .= '</div>';

$meeting_pre_content .= '<div class="post-content">';
$meeting_pre_content .= '<h2 id="meeting-notes">' . __( 'Notes', 'anp_meetings' ) . '</h2>';

/* 
 * After the_content()
 * Content that appears after the_content()
 */

$meeting_post_content = '';
$meeting_post_content .= '<footer class="post-footer">';

$meeting_post_content .= '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'anp_meetings' ) . '</span> ';
$meeting_post_content .= $meeting_tags;
$meeting_post_content .= '</p>';

$meeting_post_content .= '</footer>';
$meeting_post_content .= '</div>';


?>