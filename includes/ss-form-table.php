<?php
/**
 * WP List Table to show form messages
 */
class SS_Form_Table extends WP_List_Table {

	public $per_page = 20;

	/**
	 * Get Brochures
	 *
	 * @return array $brochures, variable which store brochures data to display in table
	 * @author Yanuar
	 */
	function get_data() {

		global $wpdb;
		$offset = $this->get_pagenum() > 1 ? ($this->get_pagenum() - 1) * $this->per_page : 0;
		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'ss_form LIMIT ' . $this->per_page . ' OFFSET ' . $offset;
		$results = $wpdb->get_results($sql, ARRAY_A);

		return $results;
	}

	public function get_total()
	{
		global $wpdb;
		return $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'ss_form');
	}

	/**
	 * Set Columns to display in table
	 */
	function get_columns() {
		$columns = array(
			// 'location_id'=> 'Location ID',
			'name' => 'Nama',
			'email' => 'Email',
			'message' => 'Message',
		);

		return $columns;
	}

	/**
	 * Prepare Items
	 */
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();

        $currentPage = $this->get_pagenum();
        $this->set_pagination_args( array(
            'total_items' => $this->get_total(),
            'per_page'    => $this->per_page
        ) );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $this->get_data();

	}

	/**
	 * Set default column
	 */
	function column_default( $item, $column_name ) {
		switch ($column_name) {
			case 'name':
				return sanitize_text_field($item[$column_name]);
			case 'email':
				return sanitize_email($item[$column_name]);
			case 'message':
				return sanitize_textarea_field($item[$column_name]);
				break;

			default:
				return print_r($item, true) ; // show whole array for troubleshooting
		}
	}

	/**
	 * Add extra markup in the toolbars before and after the list
	 *
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == 'top' ) {

		}
		if ( $which == 'bottom' ) : ?>
			<style type="text/css">
				/*
				style goes here
				*/
			</style>
		<?php
		endif;
	}
}
