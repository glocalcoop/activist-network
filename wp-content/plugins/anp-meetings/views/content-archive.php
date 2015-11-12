<?php

/* 
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

global $wp_query;

$post_type = get_post_type( get_the_ID() );

// Meeting Meta
$meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( get_the_ID(), 'meeting_date', true ) ) );
$meeting_type = get_the_term_list( get_the_ID(), 'meeting_type', '<span class="category">', ', ', '</span>' );
$meeting_tags = get_the_term_list( get_the_ID(), 'meeting_tag', '<span class="tags">', ', ', '</span>' );

// Proposal Meta
$approval_date = $meeting_date;
$effective_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( get_the_ID(), 'proposal_date_effective', true ) ) );
$proposal_status = get_the_term_list( get_the_ID(), 'proposal_status', '<span class="tags">', ', ', '</span>' );

// Associated Content

/* 
 * Before the_content()
 * Content that appears before the_content()
 */

$meeting_pre_content = '';

if( 'meeting' == $post_type ) {

    $agendas = ( function_exists( 'meeting_get_agenda' ) ) ? meeting_get_agenda( get_the_ID() ) : '';

    $summaries = ( function_exists( 'meeting_get_summary' ) ) ? meeting_get_summary( get_the_ID() ) : '';

    $proposals = ( function_exists( 'meeting_get_proposal' ) ) ? meeting_get_proposal( get_the_ID() ) : '';


    if( $agendas || $summaries || $proposals ) {

        $meeting_pre_content .= '<ul class="connected-content">';

        $meeting_pre_content .= ( $agendas ) ? $agendas : '';

        $meeting_pre_content .= ( $summaries ) ? $summaries : '';

        $meeting_pre_content .= ( $proposals ) ? $proposals : '';

        $meeting_pre_content .= '</ul>';

    }
}

if( 'agenda' == $post_type ) {

    $agendas = ( function_exists( 'meeting_get_agenda' ) ) ? meeting_get_agenda( get_the_ID() ) : '';

    if( $agendas ) {

        $meeting_pre_content .= '<ul class="connected-content meeting">';

        $meeting_pre_content .= ( $agendas ) ? $agendas : '';

        $meeting_pre_content .= '</ul>';

    }

}

if( 'summary' == $post_type ) {

    $summaries = ( function_exists( 'meeting_get_summary' ) ) ? meeting_get_summary( get_the_ID() ) : '';

    if( $summaries ) {

        $meeting_pre_content .= '<ul class="connected-content meeting">';

        $meeting_pre_content .= ( $summaries ) ? $summaries : '';

        $meeting_pre_content .= '</ul>';

    }
}

/* 
 * After the_content()
 * Content that appears after the_content()
 */

$meeting_post_content = '';

// $meeting_single_post_content .= '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'meeting' ) . '</span> ';
// $meeting_single_post_content .= $meeting_tags;
// $meeting_single_post_content .= '</p>';

?>