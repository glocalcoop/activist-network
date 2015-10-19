<?php 

function anp_meetings_connection_types() {
    p2p_register_connection_type( array(
        'name' => 'meeting_to_agenda',
        'from' => 'anp_meetings',
        'to' => 'anp_agenda'
    ) );

    p2p_register_connection_type( array(
        'name' => 'meeting_to_summary',
        'from' => 'anp_meetings',
        'to' => 'anp_summary'
    ) );

    p2p_register_connection_type( array(
        'name' => 'meeting_to_proposals',
        'from' => 'anp_meetings',
        'to' => 'anp_proposal'
    ) );

}
add_action( 'p2p_init', 'anp_meetings_connection_types' );

?>