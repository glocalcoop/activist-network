<?php

/**
 * Calendar Month List Table
 *
 * @package Calendar/ListTables/Month
 *
 * @see WP_Posts_List_Table
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Calendar Month List Table
 *
 * This list table is responsible for showing events in a traditional table,
 * even though it extends the `WP_List_Table` class. Tables & lists & tables.
 *
 * @since 0.1.0
 */
class WP_Event_Calendar_Month_Table extends WP_Event_Calendar_List_Table {

	/**
	 * The main constructor method
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );

		// Set the mode
		$this->mode = 'month';

		// Setup the view ranges
		$this->view_start = "{$this->year}-{$this->month}-01 00:00:00";
		$this->view_end   = "{$this->year}-{$this->month}-31 00:00:00";
	}

	/**
	 * Setup the list-table's columns
	 *
	 * @see WP_List_Table::single_row_columns()
	 *
	 * @return array An associative array containing column information
	 */
	public function get_columns() {
		return array(
			'sunday'    => $GLOBALS['wp_locale']->get_weekday( 0 ),
			'monday'    => $GLOBALS['wp_locale']->get_weekday( 1 ),
			'tuesday'   => $GLOBALS['wp_locale']->get_weekday( 2 ),
			'wednesday' => $GLOBALS['wp_locale']->get_weekday( 3 ),
			'thursday'  => $GLOBALS['wp_locale']->get_weekday( 4 ),
			'friday'    => $GLOBALS['wp_locale']->get_weekday( 5 ),
			'saturday'  => $GLOBALS['wp_locale']->get_weekday( 6 )
		);
	}

	/**
	 * Get a list of CSS classes for the list table table tag.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return array List of CSS classes for the table tag.
	 */
	protected function get_table_classes() {
		return array( 'widefat', 'fixed', 'striped', 'calendar', 'month', $this->_args['plural'] );
	}

	/**
	 * Return filtered query arguments
	 *
	 * @since 0.1.1
	 *
	 * @return array
	 */
	protected function main_query_args( $args = array() ) {
		return parent::main_query_args( $args );
	}

	/**
	 * Start the week with a table row
	 *
	 * @since 0.1.0
	 */
	protected function get_row_start() {
		?>

			<tr>

		<?php
	}

	/**
	 * End the week with a closed table row
	 *
	 * @since 0.1.0
	 */
	protected function get_row_end() {
		?>

			</tr>

		<?php
	}

	/**
	 * Start the week with a table row
	 *
	 * @since 0.1.0
	 */
	protected function get_row_pad( $iterator = 1, $start_day = 1 ) {
		?>

			<th class="padding <?php echo $this->get_day_classes( $iterator, $start_day ); ?>"></th>

		<?php
	}

	/**
	 * Start the week with a table row
	 *
	 * @since 0.1.0
	 */
	protected function get_row_cell( $iterator = 1, $start_day = 1 ) {

		// Calculate the day of the month
		$day_of_month = (int) ( $iterator - (int) $start_day + 1 ) ?>

		<td class="<?php echo $this->get_day_classes( $iterator, $start_day ); ?>">
			<span class="day-number">
				<?php echo (int) $day_of_month; ?>
			</span>

			<div class="events-for-day">
				<?php echo $this->get_posts_for_cell( $day_of_month ); ?>
			</div>
		</td>

		<?php
	}

	/**
	 * Display a calendar by month and year
	 *
	 * @since 0.1.0
	 */
	protected function display_mode() {

		// Get timestamp
		$timestamp  = mktime( 0, 0, 0, $this->month, 1, $this->year );
		$max_day    = date_i18n( 't', $timestamp );
		$this_month = getdate( $timestamp );
		$start_day  = $this_month['wday'];

		// Loop through days of the month
		for ( $i = 0; $i < ( $max_day + $start_day ); $i++ ) {

			// New row
			if ( ( $i % 7 ) === 0  ) {
				$this->get_row_start();
			}

			// Pad day
			if ( $i < $start_day ) {
				$this->get_row_pad( $i, $start_day );

			// Month day
			} else {
				$this->get_row_cell( $i, $start_day );
			}

			if ( ( $i % 7 ) === 6 ) {
				$this->get_row_end();
			}
		}
	}

	/**
	 * Paginate through months & years
	 *
	 * @since 0.1.0
	 *
	 * @param array $args
	 */
	protected function pagination( $args = array() ) {

		// Parse args
		$args = wp_parse_args( $args, array(
			'small'  => '1 month',
			'large'  => '1 year',
			'labels' => array(
				'next_small' => esc_html__( 'Next Month',     'wp-event-calendar' ),
				'next_large' => esc_html__( 'Next Year',      'wp-event-calendar' ),
				'prev_small' => esc_html__( 'Previous Month', 'wp-event-calendar' ),
				'prev_large' => esc_html__( 'Previous Year',  'wp-event-calendar' )
			)
		) );

		// Return pagination
		return parent::pagination( $args );
	}
}