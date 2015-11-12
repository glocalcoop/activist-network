<?php

/**
 * ANP Meetings Summaries Post Type
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */


/************* CUSTOM FIELDS *****************/

if( function_exists( "register_field_group" ) ) {

    register_field_group( array(
        'id' => 'acf_meeting-date',
        'title' => 'Meeting Date',
        'fields' => array(
            array(
                'key' => 'field_56257bc5c5ea1',
                'label' => 'Meeting Date',
                'name' => 'meeting_date',
                'type' => 'date_picker',
                'required' => 1,
                'date_format' => 'yymmdd',
                'display_format' => 'mm/dd/yy',
                'first_day' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'meeting',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'agenda',
                    'order_no' => 0,
                    'group_no' => 1,
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'summary',
                    'order_no' => 0,
                    'group_no' => 2,
                ),
            ),
        ),
        'options' => array(
            'position' => 'acf_after_title',
            'layout' => 'no_box',
            'hide_on_screen' => array(
            ),
        ),
        'menu_order' => 0,
    ));



    register_field_group( array(
        'id' => 'acf_proposal-detail-page',
        'title' => 'Proposal Detail Page',
        'fields' => array(
            array(
                'key' => 'field_562008d369f6b',
                'label' => 'Status',
                'name' => 'proposal_status',
                'type' => 'taxonomy',
                'taxonomy' => 'proposal_status',
                'field_type' => 'select',
                'allow_null' => 1,
                'load_save_terms' => 1,
                'return_format' => 'id',
                'multiple' => 0,
            ),
            array(
                'key' => 'field_562008263445d',
                'label' => 'Date Accepted',
                'name' => 'meeting_date',
                'type' => 'date_picker',
                'date_format' => 'yymmdd',
                'display_format' => 'mm/dd/yy',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_5620088b2365a',
                'label' => 'Date Effective',
                'name' => 'proposal_date_effective',
                'type' => 'date_picker',
                'date_format' => 'yymmdd',
                'display_format' => 'mm/dd/yy',
                'first_day' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'proposal',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array(
            'position' => 'acf_after_title',
            'layout' => 'default',
            'hide_on_screen' => array(
                0 => 'custom_fields',
                1 => 'featured_image',
            ),
        ),
        'menu_order' => 0,
    ));

}

?>
