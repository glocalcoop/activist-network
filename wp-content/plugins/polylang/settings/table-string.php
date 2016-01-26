<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); // since WP 3.1
}

/*
 * a class to create the strings translations table
 * Thanks to Matt Van Andel ( http://www.mattvanandel.com ) for its plugin "Custom List Table Example" !
 *
 * @since 0.6
 */
class PLL_Table_String extends WP_List_Table {
	protected $languages, $groups, $group_selected;

	/*
	 * constructor
	 *
	 * @since 0.6
	 *
	 * @param array $groups
	 * @param string $group_selected
	 */
	function __construct( $args ) {
		parent::__construct( array(
			'plural'   => 'Strings translations', // do not translate ( used for css class )
			'ajax'	 => false,
		) );

		$this->languages = $args['languages'];
		$this->groups = $args['groups'];
		$this->group_selected = $args['selected'];
	}

	/*
	 * displays the item information in a column ( default case )
	 *
	 * @since 0.6
	 *
	 * @param array $item
	 * @param string $column_name
	 * @return string
	 */
	function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}

	/*
	 * displays the checkbox in first column
	 *
	 * @since 1.1
	 *
	 * @param array $item
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="cb-select-%1$s">%2$s</label><input id="cb-select-%1$s" type="checkbox" name="strings[]" value="%1$s" %3$s />',
			esc_attr( $item['row'] ),
			/* translators: %s is a string potentially in any language */
			sprintf( __( 'Select %s' ), format_to_edit( $item['string'] ) ),
			empty( $item['icl'] ) ? 'disabled' : '' // only strings registered with WPML API can be removed
		);
	}

	/*
	 * displays the string to translate
	 *
	 * @since 1.0
	 *
	 * @param array $item
	 * @return string
	 */
	function column_string( $item ) {
		return format_to_edit( $item['string'] ); // don't interpret special chars for the string column
	}

	/*
	 * displays the translations to edit
	 *
	 * @since 0.6
	 *
	 * @param array $item
	 * @return string
	 */
	function column_translations( $item ) {
		$out = '';
		foreach ( $item['translations'] as $key => $translation ) {
			$input_type = $item['multiline'] ?
				'<textarea name="translation[%1$s][%2$s]" id="%1$s-%2$s">%4$s</textarea>' :
				'<input type="text" name="translation[%1$s][%2$s]" id="%1$s-%2$s" value="%4$s" />';
			$out .= sprintf( '<div class="translation"><label for="%1$s-%2$s">%3$s</label>'.$input_type.'</div>'."\n",
				esc_attr( $key ),
				esc_attr( $item['row'] ),
				esc_html( $this->languages[ $key ] ),
			format_to_edit( $translation ) ); // don't interpret special chars
		}
		return $out;
	}

	/*
	 * gets the list of columns
	 *
	 * @since 0.6
	 *
	 * @return array the list of column titles
	 */
	function get_columns() {
		return array(
			'cb'           => '<input type="checkbox" />', //checkbox
			'string'       => __( 'String', 'polylang' ),
			'name'         => __( 'Name', 'polylang' ),
			'context'      => __( 'Group', 'polylang' ),
			'translations' => __( 'Translations', 'polylang' ),
		);
	}

	/*
	 * gets the list of sortable columns
	 *
	 * @since 0.6
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		return array(
			'string'  => array( 'string', false ),
			'name'    => array( 'name', false ),
			'context' => array( 'context', false ),
		);
	}

	/*
	 * Sort items
	 *
	 * @since 0.6
	 *
	 * @param object $a The first object to compare
	 * @param object $b The second object to compare
	 * @return int -1 or 1 if $a is considered to be respectively less than or greater than $b.
	 */
	protected function usort_reorder( $a, $b ) {
			$result = strcmp( $a[ $_GET['orderby'] ], $b[ $_GET['orderby'] ] ); // determine sort order
			return ( empty( $_GET['order'] ) || 'asc' == $_GET['order'] ) ? $result : -$result; // send final sort direction to usort
	}

	/*
	 * prepares the list of items for displaying
	 *
	 * @since 0.6
	 *
	 * @param array $data
	 */
	function prepare_items( $data = array() ) {
		$per_page = $this->get_items_per_page( 'pll_strings_per_page' );
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		if ( ! empty( $_GET['orderby'] ) ) {// no sort by default
			usort( $data, array( &$this, 'usort_reorder' ) );
		}

		$total_items = count( $data );
		$this->items = array_slice( $data, ( $this->get_pagenum() - 1 ) * $per_page, $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'	=> $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		) );
	}

	/*
	 * get the list of possible bulk actions
	 *
	 * @since 1.1
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		return array( 'delete' => __( 'Delete','polylang' ) );
	}

	/*
	 * get the current action selected from the bulk actions dropdown.
	 * overrides parent function to avoid submit button to trigger bulk actions
	 *
	 * @since 1.8
	 *
	 * @return string|false The action name or False if no action was selected
	 */
	public function current_action() {
		return empty( $_POST['submit'] ) ? parent::current_action() : false;
	}

	/*
	 * displays the dropdown list to filter strings per group
	 *
	 * @since 1.1
	 *
	 * @param string $which only 'top' is supported
	 */
	function extra_tablenav( $which ) {
		if ( 'top' != $which ) {
			return;
		}

		echo '<div class="alignleft actions">';
		printf( '<label class="screen-reader-text" for="select-group" >%s</label>', __( 'Filter by group', 'polylang' ) );
		echo '<select id="select-group" name="group">' . "\n";
		printf(
			'<option value="-1"%s>%s</option>' . "\n",
			-1 == $this->group_selected ? ' selected="selected"' : '',
			__( 'View all groups', 'polylang' )
		);

		foreach ( $this->groups as $group ) {
			printf(
				'<option value="%s"%s>%s</option>' . "\n",
				esc_attr( urlencode( $group ) ),
				$this->group_selected == $group ? ' selected="selected"' : '',
				esc_html( $group )
			);
		}
		echo '</select>'."\n";

		submit_button( __( 'Filter' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
		echo '</div>';
	}
}
