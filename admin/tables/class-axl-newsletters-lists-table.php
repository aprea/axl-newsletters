<?php

/**
 * Lists admin table.
 *
 * @link        http://wpaxl.com
 * @since       1.0.0
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/admin/tables
 */

// WP_List_Table is not loaded automatically so we need to load it manually
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Lists admin table.
 *
 * Defines methods responsible for the rendering
 * the table displayed on the lists admin page.
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/admin/tables
 * @author      Chris Aprea <chris@wpaxl.com>
 */
final class Axl_Newsletters_Lists_Table extends WP_List_Table {

	public function __construct( $args = array() ) {

		parent::__construct( $args );
		$this->prepare_items();
	}

	/**
	 * Prepare the items for the table to process
	 *
	 * @since  1.0.0
	 * @return Void
	 */
	public function prepare_items() {

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();
		usort( $data, array( &$this, 'sort_data' ) );

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		) );

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table.
	 *
	 * @since  1.0.0
	 * @return Array
	 */
	public function get_columns() {

		$columns = array(
			'list_id'  => _x( 'ID',       'Column name.', 'axl-newsletters' ),
			'name'     => _x( 'Name',     'Column name.', 'axl-newsletters' ),
			'created'  => _x( 'Created',  'Column name.', 'axl-newsletters' ),
			'modified' => _x( 'Modified', 'Column name.', 'axl-newsletters' ),
		);

		return $columns;
	}

	/**
	 * Define which columns are hidden.
	 *
	 * @since  1.0.0
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Define the sortable columns.
	 *
	 * @since  1.0.0
	 * @return Array
	 */
	public function get_sortable_columns() {
		return array(
			'name'	   => 'name',
			'created'  => array( 'created', true ),
			'modified' => 'modified'
		);
	}

	/**
	 * Get the table data.
	 *
	 * @since  1.0.0
	 * @return Array
	 */
	private function table_data() {
		global $wpdb;

		$sql =
	   "SELECT *
		FROM   {$wpdb->prefix}axn_lists";

		$results = $wpdb->get_results( $sql, ARRAY_A );

		return $results;
	}

	/**
	 * Define what data to show on each column of the table.
	 *
	 * @since  1.0.0
	 * @param  Array   $item         Data
	 * @param  String  $column_name  Current column name
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'list_id' :
				return $item[ $column_name ];
			break;

			case 'name' :
				return $item[ $column_name ];
			break;

			case 'created' :
			case 'modified' :
				if ( '0000-00-00 00:00:00' === $item[ $column_name ] ) {
					return '&mdash;';
				}

				$date_mysql = $item[ $column_name ];
				$date       = strtotime( $date_mysql );
				$diff       = time() - $date;

				if ( $diff > 0 && $diff < DAY_IN_SECONDS ) {
					$date = sprintf( _x( '%s ago', 'Human readable date format e.g. 5 hours ago.', 'axl-newsletters' ), human_time_diff( $date ) );
				} else {
					$date = get_date_from_gmt( $date_mysql, _x( 'Y/m/d', 'Date format.', 'axl-newsletters' ) );
				}

				if ( 'created' === $column_name ) {
					$date = apply_filters( 'axl_newsletters_lists_created_column_date',  $date, $item['list_id'], $date_mysql );
				} else {
					$date = apply_filters( 'axl_newsletters_lists_modified_column_date', $date, $item['list_id'], $date_mysql );
				}

				$date_long = get_date_from_gmt( $date_mysql, _x( 'Y/m/d g:i:s A', 'Date format.', 'axl-newsletters' ) );

				return sprintf( '<abbr title="%s">%s</abbr>', $date_long, $date );
			break;

			default:
				return print_r( $item, true );
			break;
		}
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET.
	 *
	 * @since  1.0.0
	 * @param  Mixed  $a  Data set a.
	 * @param  Mixed  $b  Data set b.
	 * @return Mixed
	 */
	private function sort_data( $a, $b ) {

		// Set defaults
		$orderby = 'created';
		$order   = 'asc';

		// If orderby is set, use this as the sort column
		if ( ! empty( $_GET['orderby'] ) ) {
			$orderby = $_GET['orderby'];
		}

		// If order is set use this as the order
		if ( ! empty( $_GET['order'] ) ) {
			$order = $_GET['order'];
		}

		$result = strnatcmp( $a[ $orderby ], $b[ $orderby ] );

		if ( $order === 'asc' ) {
			return $result;
		}

		return -$result;
	}
}