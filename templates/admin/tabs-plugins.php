<?php
require 'Link_List_Table.php';
if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$all_plugins = get_plugins();
$wp_list_table = new Link_List_Table();
$wp_list_table->prepare_items();

$wp_list_table->display();