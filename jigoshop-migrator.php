<?php
/*
Plugin Name: Jigoshop - WooCommerce Store Migrator
Plugin URI: http://gajelabs.com/product/jigoshop-woocommerce-store-migrator/
Description: The real store migrator, taking your existing Jigoshop store and migrating to WooCommerce. 
Version: 3.4.2
Author: GaJeLabs
Author URI: http://gajelabs.com/about/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WOO_SM_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'WOO_SM_RELPATH', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'WOO_SM_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOO_SM_PREFIX', 'woo_sm' );

// This is the secret key for API authentication. You configured it in the settings menu of the license manager plugin.
define('WOO_SM_API_KEY', '59151a5fe416f7.79063992'); //Rename this constant name so it is specific to your plugin or theme.

// This is the URL where API query request will be sent to. This should be the URL of the site where you have installed the main license manager plugin. Get this value from the integration help page.
define('WOO_SM_SERVER_URL', 'https://gajelabs.com/'); //Rename this constant name so it is specific to your plugin or theme.

// This is a value that will be recorded in the license manager data so you can identify licenses for this item/product.
define('WOO_SM_ITEM_REFERENCE', 'JIGO_SHOP_MIGRATOR'); //Rename this constant name so it is specific to your plugin or theme.

load_plugin_textdomain( 'woo_sm', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( WOO_SM_PATH . 'includes/functions.php' );
include_once( WOO_SM_PATH . 'includes/template.php' );

include_once( WOO_SM_PATH . 'includes/common.php' );

if( is_admin() ) {

    


	/* Start of: WordPress Administration */

	function woo_sm_init() {

		// Check if WooCommerce is activated
		if( !class_exists( 'WooCommerce' ) ) {
			$message = sprintf( __( 'Jigoshop Store Migrator requires that the WooCommerce Plugin be installed and activated. Please install and activate the free <a href="%s" target="_blank">WooCommerce Plugin</a> then open WooCommerce > Store Migrator from the WordPress Administration menu.', 'woo_sm' ), 'http://wordpress.org/plugins/woocommerce/' );
			woo_sm_admin_notice( $message, 'error', 'plugins.php' );
			return;
		}

		global $wpdb, $user_ID, $migrate;

		$action = woo_get_action();
		if( in_array( $action, array( 'migrate' ) ) ) {

			// Increase the timeout to unlimited
			if( !ini_get( 'safe_mode' ) )
				set_time_limit( 0 );

			// Increase the timeout to 60 seconds
			add_filter( 'http_request_timeout', 'woo_sm_bump_request_timeout' );

			// wp_suspend_cache_invalidation( true );

			// wp_defer_term_counting( true );
			// wp_defer_comment_counting( true );

		}

		// Override the actions if we are resuming a migration
		if( $action == 'resume-migration' )
			$action = woo_sm_get_option( 'current_action', '' );

		// woo_sm_unlock_migrate_action( 'product_total_sales' );
		switch( $action ) {

			case 'skip_overview':
				woo_sm_update_option( 'skip_overview', ( isset( $_POST['skip_overview'] ) ? 1 : 0 ) );
				break;

			case 'dismiss-resume':
				$separator = '_';
				delete_option( WOO_SM_PREFIX . $separator . 'current_action' );
				delete_option( WOO_SM_PREFIX . $separator . 'current_actions' );
				delete_option( WOO_SM_PREFIX . $separator . 'current_id' );

				// Refresh the URL sans action
				$url = add_query_arg( 'action', null );
				wp_redirect( $url );
				exit();
				break;

			case 'migrate':
				if( woo_get_action() != 'resume-migration' )
					woo_sm_record_current_action( $action );
				
				woo_sm_migrate();
				/*woo_sm_migrate_products();
				woo_sm_migrate_orders();
				woo_sm_migrate_coupons();*/
				
				woo_sm_clear_last_action();
				break;

			/*case 'migrate-orders':
				if( woo_get_action() != 'resume-migration' )
					woo_sm_record_current_action( $action );
				woo_sm_migrate_orders();
				woo_sm_clear_last_action();
				break;

			case 'migrate-coupons':
				woo_sm_record_current_action( $action );
				woo_sm_migrate_coupons();
				woo_sm_clear_last_action();
				break;*/

		}

		if( in_array( $action, array( 'migrate' ) ) ) {

			// wp_suspend_cache_invalidation( false );
/*
			wp_cache_flush();
			foreach( get_taxonomies() as $tax ) {
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}
*/

			// wp_defer_term_counting( false );
			// wp_defer_comment_counting( false );

		}
		if( woo_get_action() == 'resume-migration' ) {
			$separator = '_';
			delete_option( WOO_SM_PREFIX . $separator . 'current_action' );
			delete_option( WOO_SM_PREFIX . $separator . 'current_actions' );
			delete_option( WOO_SM_PREFIX . $separator . 'current_id' );

			// Refresh the URL sans action
			$url = add_query_arg( 'action', null );
			wp_redirect( $url );
			exit();
		}

	}
	add_action( 'admin_init', 'woo_sm_init', 11 );

	function woo_sm_html_page() {

		global $migrate;

		woo_sm_template_header( __( 'Jigoshop &raquo; WooCommerce Store Migrator', 'woo_sm' ) );
		$action = woo_get_action();
		switch( $action ) {

			default:
				woo_sm_default_html_page();
				break;

		}
		woo_sm_template_footer();
		woo_sm_template_footer();

	}

	// HTML template for Store Migrate screen
	function woo_sm_default_html_page() {


		$tab = false;
		if( isset( $_GET['tab'] ) ) {
			$tab = sanitize_text_field( $_GET['tab'] );
		} else if( woo_sm_get_option( 'skip_overview', false ) ) {
			// If Skip Overview is set then jump to Migrate screen
			$tab = 'migrate';
		}
		$url = add_query_arg( 'page', 'woo_sm' );

		include_once( WOO_SM_PATH . 'templates/admin/tabs.php' );


	}





}
?>
