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

function anp_meetings_connection_types() {
    p2p_register_connection_type( array(
        'name' => 'meeting_to_agenda',
        'from' => 'anp_meetings',
        'to' => 'anp_agenda',
        'reciprocal' => true,
        'cardinality' => 'one-to-one',
        'admin_column' => true,
        'admin_dropdown' => 'to',
        'sortable' => 'any',
        // 'title' => __( 'Agenda', 'anp_meetings' ),
    ) );

    p2p_register_connection_type( array(
        'name' => 'meeting_to_summary',
        'from' => 'anp_meetings',
        'to' => 'anp_summary',
        'reciprocal' => true,
        'cardinality' => 'one-to-one',
        'admin_column' => true,
        'admin_dropdown' => 'to',
        'sortable' => 'any',
        // 'title' => __( 'Summary', 'anp_meetings' ),
    ) );

    p2p_register_connection_type( array(
        'name' => 'meeting_to_proposals',
        'from' => 'anp_meetings',
        'to' => 'anp_proposal',
        'reciprocal' => true,
        'cardinality' => 'one-to-many',
        'admin_column' => true,
        'admin_dropdown' => 'any',
        'sortable' => 'any',
        // 'title' => __( 'Proposals', 'anp_meetings' ),
    ) );

}
add_action( 'p2p_init', 'anp_meetings_connection_types' );

?>