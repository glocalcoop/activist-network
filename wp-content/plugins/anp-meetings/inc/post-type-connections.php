<?php 

/**
 * ANP Meetings Posts 2 Posts Connections
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

if(! function_exists( 'anp_meetings_connection_types' ) ) {
    
    function anp_meetings_connection_types() {
        p2p_register_connection_type( array(
            'name' => 'meeting_to_agenda',
            'from' => 'meeting',
            'to' => 'agenda',
            'reciprocal' => true,
            'cardinality' => 'one-to-one',
            'admin_column' => true,
            'admin_dropdown' => 'to',
            'sortable' => 'any',
            'title' => array( 'from' => __( 'Agenda', 'meeting' ), 'to' => __( 'Meeting', 'meeting' ) ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'meeting_to_summary',
            'from' => 'meeting',
            'to' => 'summary',
            'reciprocal' => true,
            'cardinality' => 'one-to-one',
            'admin_column' => true,
            'admin_dropdown' => 'to',
            'sortable' => 'any',
            'title' => array( 'from' => __( 'Summary', 'meeting' ), 'to' => __( 'Meeting', 'meeting' ) ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'meeting_to_proposal',
            'from' => 'meeting',
            'to' => 'proposal',
            'reciprocal' => true,
            'cardinality' => 'one-to-many',
            'admin_column' => true,
            'admin_dropdown' => 'any',
            'sortable' => 'any',
            'title' => array( 'from' => __( 'Proposals', 'meeting' ), 'to' => __( 'Meeting', 'meeting' ) ),
        ) );

    }
    add_action( 'p2p_init', 'anp_meetings_connection_types' );

}


?>