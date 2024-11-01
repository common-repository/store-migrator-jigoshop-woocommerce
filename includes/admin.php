<?php
// Register Jigoshop Post Type & Term Taxonomy

// @mod - WooCommerce no longer uses the shop_order_status Term Taxonomy so it needs to be turned on just for Round 1, then can go away

/*
register_post_type("shop_order",
	array(
		'labels' => array(
			'name' => __('Orders', 'jigoshop'),
			'singular_name' => __('Order', 'jigoshop'),
			'all_items' => __('All Orders', 'jigoshop'),
			'add_new' => __('Add New', 'jigoshop'),
			'add_new_item' => __('New Order', 'jigoshop'),
			'edit' => __('Edit', 'jigoshop'),
			'edit_item' => __('Edit Order', 'jigoshop'),
			'new_item' => __('New Order', 'jigoshop'),
			'view' => __('View Order', 'jigoshop'),
			'view_item' => __('View Order', 'jigoshop'),
			'search_items' => __('Search Orders', 'jigoshop'),
			'not_found' => __('No Orders found', 'jigoshop'),
			'not_found_in_trash' => __('No Orders found in trash', 'jigoshop'),
			'parent' => __('Parent Orders', 'jigoshop')
		),
		'description' => __('This is where store orders are stored.', 'jigoshop'),
		'public' => false,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'capability_type' => 'shop_order',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => false,
		'query_var' => true,
		'supports' => array('title', 'comments'),
		'has_archive' => false,
		'menu_position' => 58,
		'menu_icon' => 'dashicons-clipboard',
	)
);

register_post_type("shop_coupon",
	array(
		'labels' => array(
			'menu_name' => __('Coupons', 'jigoshop'),
			'name' => __('Coupons', 'jigoshop'),
			'singular_name' => __('Coupon', 'jigoshop'),
			'add_new' => __('Add Coupon', 'jigoshop'),
			'add_new_item' => __('Add New Coupon', 'jigoshop'),
			'edit' => __('Edit', 'jigoshop'),
			'edit_item' => __('Edit Coupon', 'jigoshop'),
			'new_item' => __('New Coupon', 'jigoshop'),
			'view' => __('View Coupons', 'jigoshop'),
			'view_item' => __('View Coupon', 'jigoshop'),
			'search_items' => __('Search Coupons', 'jigoshop'),
			'not_found' => __('No Coupons found', 'jigoshop'),
			'not_found_in_trash' => __('No Coupons found in trash', 'jigoshop'),
			'parent' => __('Parent Coupon', 'jigoshop')
		),
		'description' => __('This is where you can add new coupons that customers can use in your store.', 'jigoshop'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'shop_coupon',
		'map_meta_cap' => true,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'hierarchical' => false,
		'rewrite' => false,
		'query_var' => true,
		'supports' => array('title', 'editor'),
		'show_in_nav_menus' => false,
		'show_in_menu' => 'jigoshop'
	)
);
*/

// Display admin notice on screen load
function woo_sm_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {

	if( $priority == false || $priority == '' )
		$priority = 'updated';
	if( $message <> '' ) {
		ob_start();
		woo_sm_admin_notice_html( $message, $priority, $screen );
		$output = ob_get_contents();
		ob_end_clean();
		// Check if an existing notice is already in queue
		$existing_notice = get_transient( WOO_SM_PREFIX . '_notice' );
		if( $existing_notice !== false ) {
			$existing_notice = base64_decode( $existing_notice );
			$output = $existing_notice . $output;
		}
		set_transient( WOO_SM_PREFIX . '_notice', base64_encode( $output ), MINUTE_IN_SECONDS );
		add_action( 'admin_notices', 'woo_sm_admin_notice_print' );
	}

}

// HTML template for admin notice
function woo_sm_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) {

	// Check if this is a resumed migration
	if( woo_get_action() == 'resume-migration' ) {
		woo_sm_admin_notice( $message, $priority, $screen );
		return;
	}

	// Display admin notice on specific screen
	if( !empty( $screen ) ) {

		global $pagenow;

		if( is_array( $screen ) ) {
			if( in_array( $pagenow, $screen ) == false )
				return;
		} else {
			if( $pagenow <> $screen )
				return;
		}

	} ?>
<div id="message" class="<?php echo $priority; ?>">
	<p><?php echo $message; ?></p>
</div>
<?php

}

// Grabs the WordPress transient that holds the admin notice and prints it
function woo_sm_admin_notice_print() {

	$output = get_transient( WOO_SM_PREFIX . '_notice' );
	if( $output !== false ) {
		delete_transient( WOO_SM_PREFIX . '_notice' );
		$output = base64_decode( $output );
		echo $output;
	}

}

function woo_sm_template_header( $title = '' ) { ?>
<div id="woo-sm" class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2><?php echo $title; ?></h2>
<?php

}

function woo_sm_template_footer() { ?>
</div>
<?php
}

// WordPress Administration menu
function woo_sm_admin_menu() {

	$page = add_submenu_page( 'woocommerce', __( 'Jigoshop &raquo; WooCommerce Store Migrator', 'woo_sm' ), __( 'Store Migrator', 'woo_sm' ), 'manage_options', 'woo_sm', 'woo_sm_html_page' );
	add_action( 'admin_print_styles-' . $page, 'woo_sm_enqueue_scripts' );

}
add_action( 'admin_menu', 'woo_sm_admin_menu', 11 );

// Load CSS and jQuery scripts for Store Migrator screen
function woo_sm_enqueue_scripts( $hook ) {

	// Common
	wp_enqueue_style( 'woo_sm_styles', plugins_url( '/templates/admin/migrate.css', WOO_SM_RELPATH ) );
	wp_enqueue_script( 'woo_sm_scripts', plugins_url( '/templates/admin/migrate.js', WOO_SM_RELPATH ), array( 'jquery' ) );
	wp_enqueue_style( 'dashicons' );

}

// HTML active class for the currently selected tab on the Migrate Store screen
function woo_sm_admin_active_tab( $tab_name = null, $tab = null ) {

	if( isset( $_GET['tab'] ) && !$tab )
		$tab = $_GET['tab'];
	else if( !isset( $_GET['tab'] ) && woo_sm_get_option( 'skip_overview', false ) )
		$tab = 'migrate';
	else
		$tab = 'overview';

	$output = '';
	if( isset( $tab_name ) && $tab_name ) {
		if( $tab_name == $tab )
			$output = ' nav-tab-active';
	}
	echo $output;

}

// HTML template for each tab on the Migrate Store screen
function woo_sm_tab_template( $tab = '' ) {

	global $migrate;

	if( !$tab )
		$tab = 'overview';

	$troubleshooting_url = 'http://gajelabs.com/documentation/jigoshop-woocommerce-store-migrator_doc/';

	switch( $tab ) {

		case 'overview':
			$skip_overview = woo_sm_get_option( 'skip_overview', false );
			break;

		case 'migrate':

			// Check if required WooCommerce functions are available
			$required_functions = array( 'wc_update_order_item_meta', 'wc_add_order_item', 'get_woocommerce_currency', 'woocommerce_price' );
			foreach( $required_functions as $required_function ) {
				if( !function_exists( $required_function ) ) {
					$message = sprintf( __( 'The required WooCommerce function <code>%s()</code> could not be found, please update WooCommerce to the latest release and re-open this screen.', 'woo_sm' ), $required_function );
					woo_sm_admin_notice_html( $message, 'error' );
				}
			}

			// Check if a resumed migration is possible
			$current_actions = woo_sm_get_option( 'current_actions', '' );
			$current_id = woo_sm_get_option( 'current_id', 0 );
			if( !empty( $current_actions ) ) {

				// Chill out for a few seconds to see if the current_id count changes confirming that a migration script is running
				sleep( 2 );

				// Check if Apache is still running a migration process
				wp_cache_flush();
				if( $current_id == woo_sm_get_option( 'current_id', 0 ) ) {
					$dismiss_url = add_query_arg( 'action', 'dismiss-resume' );
					$dismiss = sprintf( '<span style="float:right;"><a href="%s">' . __( 'Dismiss', 'woo_sm' ) . '</a></span>', $dismiss_url );
					$resume_url = add_query_arg( 'action', 'resume-migration' );
					$resume = sprintf( '<br /><br /><a href="%s" class="button-primary">' . __( 'Resume migration', 'woo_ce' ) . '</a>', $resume_url );
					$message = $dismiss . sprintf( __( 'It looks like a previous migration process - <em><attr title="%s">%s</attr></em> may have timed out and failed... <strong>:(</strong><br /><br />Never fear, it is possible to resume from where the migratior left off by clicking the Resume migration button below; otherwise hit Dismiss to hide this message.', 'woo_sm' ), sprintf( __( 'Migration process failed at #%d', 'woo_ce' ), $current_id ), $current_actions ) . $resume;
					woo_sm_admin_notice_html( $message, 'error' );
				} else {
					$message = sprintf( __( 'It looks like an existing migration process - <em>%s</em> - is still running in the background, this can happen when the browser closes a session but the server keeps on truckin\'. Refresh this screen in a few minutes and this notice will hide as soon as the current process has completed.', 'woo_ce' ), $current_actions );
					woo_sm_admin_notice_html( $message );
				}
			}

			// Product
			$product_sku_exists = woo_sm_check_product_sku_exists();
			$product_price_exists = woo_sm_check_product_price_exists();
			$product_sale_price_exists = woo_sm_check_product_sale_price_exists();
			$product_sale_price_period_exists = woo_sm_check_product_sale_price_period_exists();
			$product_featured_exists = woo_sm_check_product_featured_exists();
			$product_stock_exists = woo_sm_check_product_stock_exists();
			$product_weight_exists = woo_sm_check_product_weight_exists();
			$product_dimension_exists = woo_sm_check_product_dimension_exists();
			$product_external_url_exists = woo_sm_check_product_external_url_exists();
			$product_visibility_exists = woo_sm_check_product_visibility_exists();
			$product_customizable_exists = woo_sm_check_product_customizable_exists();
			$product_tax_status_exists = woo_sm_check_product_tax_status_exists();
			$product_total_sales_exists = ( woo_sm_get_option( 'lock_action-product_total_sales', 0 ) == false ? woo_sm_check_products_exist() : 0 );
			$product_purchase_note_exists = ( woo_sm_get_option( 'lock_action-product_purchase_note', 0 ) == false ? woo_sm_check_products_exist() : 0 );
			$product_attribute_exists = woo_sm_check_product_attributes_exists();
			$product_variations_exists = woo_sm_check_product_variations_exists();
			$product_downloadable_exists = woo_sm_check_product_downloadable_exists();
			$product_categories_exists = woo_sm_check_product_categories_exists();
			$product_virtual_exists = ( woo_sm_get_option( 'lock_action-product_virtual', 0 ) == false ? woo_sm_check_products_exist() : 0 );

			// Order
			$order_key_exists = woo_sm_check_order_key_exists();
			$order_customer_exists = woo_sm_check_order_customer_exists();
			$order_status_exists = woo_sm_check_order_status_exists();
			$order_download_exists = woo_sm_check_order_download_exists();
			$order_currency_exists = woo_sm_check_order_currency_exists();
			$order_shipping_method_exists = ( woo_sm_get_option( 'lock_action-order_shipping_method', 0 ) == false ? woo_sm_check_orders_exist() : 0 );
			$order_discount_exists = ( woo_sm_get_option( 'lock_action-order_discount', 0 ) == false ? woo_sm_check_order_discount_exists() : 0 );
			$order_post_meta_exists = ( woo_sm_get_option( 'lock_action-order_meta', 0 ) == false ? woo_sm_check_orders_exist() : 0 );
			$order_copy_billing_shipping_address_exists = ( woo_sm_get_option( 'lock_action-order_copy_billing_shipping_address', 0 ) == false ? woo_sm_check_orders_exist() : 0 );
			$order_item_exists = ( woo_sm_get_option( 'lock_action-order_items', 0 ) == false ? woo_sm_check_orders_exist() : 0 );

			// Coupon
			$coupon_type_exists = woo_sm_check_coupon_type_exists();
			$coupon_amount_exists = woo_sm_check_coupon_amount_exists();
			$coupon_expiry_date_exists = woo_sm_check_coupon_expiry_date_exists();
			$coupon_usage_limit_exists = ( woo_sm_get_option( 'lock_action-coupon_usage_limit', 0 ) == false ? woo_sm_check_coupon_usage_limit_exists() : 0 );
			$coupon_free_shipping_exists = woo_sm_check_coupon_free_shipping_exists();
			$coupon_individual_use_exists = woo_sm_check_coupon_individual_use_exists();
			$coupon_minimum_amount_exists = woo_sm_check_coupon_minimum_amount_exists();
			$coupon_maximum_amount_exists = woo_sm_check_coupon_maximum_amount_exists();
			$coupon_exclude_categories_exists = woo_sm_check_coupon_exclude_categories_exists();
			$coupon_include_categories_exists = woo_sm_check_coupon_include_categories_exists();
			$coupon_exclude_products_exists = woo_sm_check_coupon_exclude_products_exists();
			$coupon_include_products_exists = woo_sm_check_coupon_include_products_exists();
			$coupon_usage_count_exists = woo_sm_check_coupon_usage_count_exists();
			$coupon_used_by_exists = woo_sm_check_coupon_used_by_exists();
			break;

	}
	if( $tab ) {
		if( file_exists( WOO_SM_PATH . 'templates/admin/tabs-' . $tab . '.php' ) ) {
			include_once( WOO_SM_PATH . 'templates/admin/tabs-' . $tab . '.php' );
		} else {
			$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woo_sm' ), 'tabs-' . $tab . '.php', WOO_SM_PATH . 'templates/admin/...' );
			woo_sm_admin_notice_html( $message, 'error' );
			ob_start(); ?>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woo_sm' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woo_sm' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woo_sm' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woo_sm' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woo_sm' ); ?></p>
<?php
			ob_end_flush();
		}
	}

}
?>