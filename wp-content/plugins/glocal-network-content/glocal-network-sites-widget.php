<?php

class ANP_Network_Sites_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'network-sites-list',
			__( 'Network Sites', 'glocal-network-content' ),
			array(
				'description' => __( 'Display list of sites in your network.', 'glocal-network-content' ),
				'classname'   => 'network-sites-list',
			)
		);

	}

	public function widget( $args, $instance ) {

		echo $before_widget;
        
        // if the title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        
        // Use glocal_networkwide_sites function to display sites
        if( function_exists( 'glocal_networkwide_sites_module' ) ) {
            echo glocal_networkwide_sites_module( $instance );
        }
		        
        echo $after_widget;

	}

	public function form( $instance ) {

		// Set default values
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '',
			'number_sites' => '',
			'exclude_sites' => '',
			'sort_by' => '',
			'default_image' => '',
		) );

		// Retrieve an existing value from the database
		$title = !empty( $instance['title'] ) ? $instance['title'] : '';
		$number_sites = !empty( $instance['number_sites'] ) ? $instance['number_sites'] : '';
		$exclude_sites = !empty( $instance['exclude_sites'] ) ? $instance['exclude_sites'] : '';
		$sort_by = !empty( $instance['sort_by'] ) ? $instance['sort_by'] : '';
		$default_image = !empty( $instance['default_image'] ) ? $instance['default_image'] : '';

		// Form fields
		echo '<p>';
		echo '	<label for="title" class="title_label">' . __( 'Title', 'glocal-network-content' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" class="widefat" placeholder="' . esc_attr__( 'Enter Widget Title', 'glocal-network-content' ) . '" value="' . esc_attr( $title ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="number_sites" class="number_sites_label">' . __( 'Number of Sites', 'glocal-network-content' ) . '</label>';
		echo '	<input type="number" id="' . $this->get_field_id( 'number_sites' ) . '" name="' . $this->get_field_name( 'number_sites' ) . '" class="widefat" placeholder="' . esc_attr__( '0-100', 'glocal-network-content' ) . '" value="' . esc_attr( $number_sites ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="exclude_sites" class="exclude_sites_label">' . __( 'Exclude Sites', 'glocal-network-content' ) . '</label>';
		echo '	<select id="' . $this->get_field_id( 'exclude_sites' ) . '[]" name="' . $this->get_field_name( 'exclude_sites' ) . '" class="widefat">';
		echo '		<option value="value" ' . selected( $exclude_sites, 'value', false ) . '> ' . __( 'label', 'glocal-network-content' );
		echo '	</select>';
		echo '</p>';

		echo '<p>';
		echo '	<label for="sort_by" class="sort_by_label">' . __( 'Sort By', 'glocal-network-content' ) . '</label>';
		echo '	<select id="' . $this->get_field_id( 'sort_by' ) . '" name="' . $this->get_field_name( 'sort_by' ) . '" class="widefat">';
		echo '		<option value="blogname" ' . selected( $sort_by, 'blogname', false ) . '> ' . __( 'Alphabetical, last_updated', 'glocal-network-content' );
		echo '	</select>';
		echo '</p>';

		echo '<p>';
		echo '	<label for="default_image" class="default_image_label">' . __( 'Default Image', 'glocal-network-content' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'default_image' ) . '" name="' . $this->get_field_name( 'default_image' ) . '" class="widefat" placeholder="' . esc_attr__( 'Enter path/url of default image', 'glocal-network-content' ) . '" value="' . esc_attr( $default_image ) . '">';
		echo '</p>';

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = !empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['number_sites'] = !empty( $new_instance['number_sites'] ) ? strip_tags( $new_instance['number_sites'] ) : '';
		$instance['exclude_sites'] = !empty( $new_instance['exclude_sites'] ) ? $new_instance['exclude_sites'] : '';
		$instance['sort_by'] = !empty( $new_instance['sort_by'] ) ? $new_instance['sort_by'] : '';
		$instance['default_image'] = !empty( $new_instance['default_image'] ) ? strip_tags( $new_instance['default_image'] ) : '';

		return $instance;

	}

}

function anp_register_widgets() {
	register_widget( 'ANP_Network_Sites_Widget' );
}

add_action( 'widgets_init', 'anp_register_widgets' );


?>