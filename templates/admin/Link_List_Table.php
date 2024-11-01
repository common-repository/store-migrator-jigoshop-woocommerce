<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once( WOO_SM_PATH . 'includes/matching.php' );
class Link_List_Table extends WP_List_Table {


    private $matcher ;
	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Plugin', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Plugins', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );
        $this->matcher = new matching() ;
	}

    /**
     * plugin list filtered to be jisgoshop dependant
     */
	public   function woo_sm_get_plugins(){

	    $result = get_plugins() ;
	    $final = array() ;


	    foreach ($result as $key=>$value){

	        if(strpos( strtolower($value['Description']),"jigoshop") ||strpos( strtolower($value['Title']),"jigoshop")
                || strpos(strtolower($value['Name']),"jigoshop")|| strpos(strtolower($key),"jigoshop") ){
	            if(strtolower($key) != "jigoshop-woocommerce-migrator-free/jigoshop-migrator.php"
                    && strtolower($key) != "jigoshop-woocommerce-migrator/jigoshop-migrator.php")
                {
                    $value["Equivalent"] = $this->matcher->getData(substr($key,0,strpos($key,"/"))) ;
	                $final[$key] = $value ;
                }
            }
        }
        return $final ;
    }




	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public  function record_count() {

        return count($this->woo_sm_get_plugins());
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No Plugin available.', 'woo_sm' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		return $item[$column_name];
	}



	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [

			'Name'    => __( 'Name', 'woo_sm' ),
			'Equivalent' =>__( 'Equivalent', 'woo_sm' )
		];

		return $columns;
	}




	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}*/


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		//$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'plugins_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		//<input type='button' value='Download' class='button'>
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        
		//$this->items = self::get_customers( $per_page, $current_page );
		$this->items = $this->woo_sm_get_plugins();
	}
        
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'ID' => array( 'true', true ),
			
		);

		return $sortable_columns;
	}



}



