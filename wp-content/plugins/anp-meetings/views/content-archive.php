<?php

/* 
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

// $meeting_agenda = wpautop( get_post_meta($post->ID, 'meeting_agenda', true), true );
// $meeting_links = get_post_meta($post->ID, 'meeting_links', true);
// $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, 'meeting_date', true) ) );
// $meeting_type = get_the_term_list( $post->ID, 'anp_meetings_type', '<span class="category">', ', ', '</span>' );
// $meeting_tags = get_the_term_list( $post->ID, 'anp_meetings_tag', '<span class="tags">', ', ', '</span>' );

/* 
 * Before the_content()
 * Content that appears before the_content()
 */

$meeting_pre_content = '';

/* 
 * After the_content()
 * Content that appears after the_content()
 */

$meeting_post_content = '';

// $meeting_single_post_content .= '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'anp_meetings' ) . '</span> ';
// $meeting_single_post_content .= $meeting_tags;
// $meeting_single_post_content .= '</p>';

?>