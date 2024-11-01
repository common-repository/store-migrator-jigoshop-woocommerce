<?php
include_once( WOO_SM_PATH . 'includes/functions.php' );

global $delete_meta_keys;

$delete_meta_keys = array(
	array( '-1', 'sample_key' )
);

global $download_url;

$download_url = '';

//(1) Migrate prices for products
function woo_sm_migrate_products_change_product_price() {

	$post_type = array( 'product', 'product_variation' );
	
	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_price_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Update Regular Product Price
			$meta_value = get_post_meta( $product, 'regular_price', true );
			// Case Zero
			if( empty( $meta_value ) && !is_null( $meta_value ) ) {
				update_post_meta( $product, '_regular_price', $meta_value, true );
				update_post_meta( $product, '_price', $meta_value, true );
				delete_post_meta( $product, 'regular_price' );
				$updated++;
				continue;
			}

			// Case NULL
			if( empty( $meta_value ) ) {
				$meta_value = '';
				update_post_meta( $product, '_regular_price', $meta_value, true );
				update_post_meta( $product, '_price', $meta_value, true );
				$updated++;
			}

			// OTHERWISE: Simple and Multi
			if( !empty( $meta_value ) ) {
				update_post_meta( $product, '_regular_price', $meta_value, true );
				update_post_meta( $product, '_price', $meta_value, true );
/*
				// Update Product Price Info without filtering
				$product_downloadable_sql = " SELECT terms.`name` FROM `" . $wpdb->posts . "` posts INNER JOIN `" . $wpdb->term_relationships . "` relationships ON posts.`ID` = relationships.`object_id` AND posts.`post_type` = 'product' INNER JOIN `" . $wpdb->term_taxonomy . "` taxonomy ON relationships.`term_taxonomy_id` = taxonomy.`term_taxonomy_id` AND taxonomy.`taxonomy` = 'product_type' AND taxonomy.`count` <> 0 INNER JOIN `" . $wpdb->terms . "` terms ON taxonomy.`term_id` = terms.`term_id` WHERE posts.`ID` = '" . $product . "' ";
				$product_type = $wpdb->get_var( $product_downloadable_sql );
			    	if(  $product_type == 'variable' ) {
					$price = 'Variable Product Price Not Announced';
				} else {
					update_post_meta( $product, '_regular_price', $meta_value, true );
					update_post_meta( $product, '_price', $meta_value, true );
				}
*/
				$updated++;
			}
			delete_post_meta( $product, 'regular_price' );
			delete_post_meta( $product, 'price' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Product Price updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product Price\'s were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(2) Migrate sales prices for products
function woo_sm_migrate_products_change_product_sales_price() {

	global $delete_meta_keys;

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_sale_price_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Sales Price
			$meta_value = get_post_meta( $product, 'sale_price', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, '_sale_price', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'sale_price' );
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Products have had their Product Sales Price updated', $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = 'No Product Sales Prices were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(3) Migrate sales prices periods for products
function woo_sm_migrate_products_change_product_sales_price_period() {

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_sale_price_period_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Sales Price From and To
			$meta_value_from = get_post_meta( $product, 'sale_price_dates_from', true );
			$meta_value_to = get_post_meta( $product, 'sale_price_dates_to', true );

			if( empty( $meta_value_from ) )
				$meta_value_from = '';

			if( empty( $meta_value_to ) )
				$meta_value_to = '';

			update_post_meta( $product, '_sale_price_dates_from', $meta_value_from );
			update_post_meta( $product, '_sale_price_dates_to', $meta_value_to );
			if( !empty( $meta_value_from ) || !empty( $meta_value_to ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'sale_price_dates_from' );
			delete_post_meta( $product, 'sale_price_dates_to' );
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Product Sales Price Period updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product Sales Prices Period were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(4) Migrate featured values for products
function woo_sm_migrate_products_change_product_featured() {

	global $delete_meta_keys;

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_featured_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Featured Value
			$meta_value = get_post_meta( $product, 'featured', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {

				// Transform featured value before storing: 1 -> yes; 0 -> no.
				$output = ( $meta_value == 1 ) ? "yes" : "no";

				update_post_meta( $product, '_featured', $output );
				$updated++;
				delete_post_meta( $product, 'featured' );

			}
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Product Featured updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s Featured were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

// (5) Migrate customizable values for products
// Take it over to WooCommerce as a customer value
// Thus the customer don't looses the info after migrating
// Can be updated after customers requests
function woo_sm_migrate_products_change_product_customizable() {

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_customizable_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Customizable Value
			$meta_value = get_post_meta( $product, 'customizable', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, '_customizable', $meta_value ); 
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'customizable' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Product Customizable updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );
		
	} else {
		$message = __( 'No Product\'s Customizable were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(6) Migrate tax status for products
// Take it over to WooCommerce as a customer value
// Thus the customer don't looses the info after migrating
// Can be updated after customers requests
function woo_sm_migrate_products_change_product_tax_status() {

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_tax_status_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Tax Status
			$meta_value = get_post_meta( $product, 'tax_status', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, 'tax_status', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'tax_status' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Product Tax Status updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );
		
	} else {
		$message = __( 'No Product\'s Tax Status were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(7) Migrate product weights
function woo_sm_migrate_products_change_product_weight() {

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_weight_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Weight
			$meta_value = get_post_meta( $product, 'weight', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, '_weight', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'weight' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Weight updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s Weight were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(8) Migrate product dimensions : length, width, height
function woo_sm_migrate_products_change_product_dimensions() {

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_dimension_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Dimensions
			$length = get_post_meta( $product, 'length', true );
			$width = get_post_meta( $product, 'width', true );
			$height = get_post_meta( $product, 'height', true );

			if( empty( $length ) )
				$length = '';

			if( empty( $width ) )
				$width = '';

			if( empty( $height ) )
				$height = '';

			update_post_meta( $product, '_length', $length );
			update_post_meta( $product, '_width', $width );
			update_post_meta( $product, '_height', $height );
			if( !empty( $length ) || !empty( $width ) || !empty( $height ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'length' );
			delete_post_meta( $product, 'width' );
			delete_post_meta( $product, 'height' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Dimensions updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s Dimensions were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(9) Migrate product skus
function woo_sm_migrate_products_change_product_sku() {

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_sku_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product SKU
			$meta_value = get_post_meta( $product, 'sku', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, '_sku', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'sku' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their SKUs updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s SKU were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

// (10) Migrate product stock
function woo_sm_migrate_products_change_product_stock() {

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_stock_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Stock
			$stock = get_post_meta( $product, 'stock', true );
			$manage_stock = get_post_meta( $product, 'manage_stock', true );
			$stock_status = get_post_meta( $product, 'stock_status', true );

			if( empty( $stock ) )
				$stock = '';

			if( empty( $manage_stock ) )
				$manage_stock = 'no';

			if( empty( $stock_status ) )
				$stock_status = '';

			update_post_meta( $product, '_stock', $stock );
			update_post_meta( $product, '_manage_stock', $manage_stock );
			update_post_meta( $product, '_stock_status', $stock_status );
			if( !empty( $stock ) || !empty( $manage_stock ) || !empty( $stock_status ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'stock' );
			delete_post_meta( $product, 'manage_stock' );
			delete_post_meta( $product, 'stock_status' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Stock updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s stock were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(11) Migrate visibility for products
function woo_sm_migrate_products_change_product_visibility() {

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_visibility_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product Visibility
			$meta_value = get_post_meta( $product, 'visibility', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, '_visibility', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, 'visibility' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their Visibility updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s Visibility were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(12) Migrate Total sales for products
function woo_sm_migrate_products_add_product_total_sales() {

	global $wpdb;

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_products_exist();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product SKU
			$meta_value = get_post_meta( $product, '_js_total_sales', true );

			if( empty( $meta_value ) ) {

				// Let's see how many Orders have been made and use that number
				$order_item_type = 'line_item';
				$count_sql = $wpdb->prepare( "SELECT COUNT(order_itemmeta.meta_id) FROM  `" . $wpdb->prefix . "woocommerce_order_items` as order_items, `" . $wpdb->prefix . "woocommerce_order_itemmeta` as order_itemmeta WHERE order_items.order_item_id = order_itemmeta.order_item_id AND order_items.`order_item_type` = %s AND order_itemmeta.`meta_key` IN ('_product_id', '_variation_id') AND order_itemmeta.`meta_value` = %d", $order_item_type, $product );
				$count = $wpdb->get_var( $count_sql );

				if( $count > 0 )
					$meta_value = absint( $count );
				else
					$meta_value = 0;

			}

			if( get_post_meta( $product, 'total_sales', true ) == false ) {
				update_post_meta( $product, 'total_sales', $meta_value );
				$updated++;
			}
			delete_post_meta( $product, '_js_total_sales' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'product_total_sales' );

		$message = sprintf( __( '%d of %d Products have had their Total Sales updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s Total Sales were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(13) Migrate purchase note for products
function woo_sm_migrate_products_add_product_purchase_note() {

	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_products_exist();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			$meta_value = '';

			if( get_post_meta( $product, '_purchase_note', true ) == false ) {
				update_post_meta( $product, '_purchase_note', $meta_value );
				$updated++;
			}

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'product_purchase_note' );

		$message = sprintf( __( '%d of %d Products have had their Purchase Note updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s Purchase Note were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

// (14) Migrate tax classes for products NOTE: NOT PART OF THE FIRST RELEASE
/*
function woo_sm_migrate_products_change_product_tax_classes() {

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $product );

			// Product Tax Classes
			$meta_value = get_post_meta( $product, 'tax_classes', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				
				//Transform the value
				if ( $meta_value == 'a:1:{i:0;s:1:"*";}' ) {
				
				    echo $meta_value;
					$output = "Standard";
					add_post_meta( $product, 'tax classes', $output, false );
					
				} else if ( $meta_value == 'a:2:{i:0;s:1:"*";i:1;s:12:"reduced-rate";}' ) {
				
					echo $meta_value;
					$output_standard = "Standard";
					add_post_meta( $product, 'tax classes', $output_standard, false );
					$output_reduced = "Reduced Rate";
					add_post_meta( $product, 'tax classes', $output_reduced, false );
					
				} else { 
				
					echo $meta_value;
					$output_standard = "Standard";
					add_post_meta( $product, 'tax classes', $output_standard, false );
					$output_reduced = "Reduced Rate";
					add_post_meta( $product, 'tax classes', $output_reduced, false );
					$output_zero = "Zero Rate";
					add_post_meta( $product, 'tax classes', $output_zero, false );
					
				}
				$updated++;
			}
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Products have had their Visibility updated', $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = 'No Product\'s Visibility were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}
*/

//(15) Migrate Url for products
function woo_sm_migrate_products_change_product_url() {

	global $delete_meta_keys;

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_external_url_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Product URL
			$meta_value = get_post_meta( $product, 'external_url', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $product, '_product_url', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $product, '_product_url' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( __( '%d of %d Products have had their URL updated', 'woo_sm' ), $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Product\'s URL were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(16) Migrate Attributes of products
function woo_sm_migrate_products_change_product_attributes() {

	global $delete_meta_keys;

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids',
		'meta_key' => 'product_attributes',
		'meta_value' => null
	);
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $product );

			// Product Attribute
			$meta_value = get_post_meta( $product, 'product_attributes', true );

			if( empty( $meta_value ) )
				$meta_value = array();

			if( !empty( $meta_value ) ) {
				foreach( $meta_value as $attribute_key => $attribute ) {
					// Update the array keys
					//$new_attribute_key = sprintf( 'pa_%s', $attribute_key );
					$new_attribute_value = str_replace( ',', ' |', $attribute['value'] );
					// Add WooCommerce elements
					$meta_value[$attribute_key] = $attribute;
					$meta_value[$attribute_key]['name'] = $attribute_key;
					$meta_value[$attribute_key]['value'] = $new_attribute_value;
					$meta_value[$attribute_key]['is_visible'] = ( isset( $attribute['visible'] ) ? $attribute['visible'] : 0 );
					$meta_value[$attribute_key]['is_variation'] = ( isset( $attribute['variation'] ) ? $attribute['variation'] : 0 );
					// Remove legacy Jigoshop elements from array
					unset( $meta_value[$attribute_key]['visible'], $meta_value[$attribute_key]['variation'] );
				}
				update_post_meta( $product, '_product_attributes', $meta_value );
				$updated++;
			} else {
				update_post_meta( $product, '_product_attributes', $meta_value );
			}
			delete_post_meta( $product, 'product_attributes' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		if( $updated == 0 ) {
			$message = __( 'No Products had their Product Attributes updated', 'woo_sm' );
			woo_sm_admin_notice_html( $message, 'error' );
		} else {
			$message = sprintf( '%d of %d Products have had their Product Attributes updated', $updated, count( $products ) );
			woo_sm_admin_notice_html( $message );
		}

	} else {
		$message = __( 'No Product Attributes were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(17) Migrate Variation of products
function woo_sm_migrate_products_change_product_variations() {
	
	$post_type = 'product_variation';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids',
		'meta_query' => array(
			array(
				'key' => 'variation_data',
				'value' => null,
				'compare' => '!='
			)
		)
	);
	
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_variations_exists();
	}
	
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $product );

			$meta_value = get_post_meta( $product, 'variation_data', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				foreach( $meta_value as $attribute_key => $attribute ) {
					$new_attribute_key = sprintf( 'attribute_%s', str_replace( 'tax_', '', $attribute_key ) );
					update_post_meta( $product, $new_attribute_key, $attribute );
				}
				$updated++;
			}
			
			delete_post_meta( $product, 'variation_data' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Variants have had been updated', $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = __( 'No Variants were updated', 'woo_sm' );
		woo_sm_admin_notice_html( $message, 'error' );
	}
}

//(18) Delete the old plugins products keys for which there is no existing WooCommerce key
// ACTUAL DECISION: They aren't migrated as custom fields
function woo_sm_migrate_products_delete_unmapped_product_meta() {

	global $delete_meta_keys;

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $product );

			// Delete the old unmapped Jigoshop product meta
			// In the end add them to the same data structure for deletion

			array_push( $delete_meta_keys, array( $product, 'brand' ) );
			array_push( $delete_meta_keys, array( $product, 'customizable' ) );
			array_push( $delete_meta_keys, array( $product, 'customized_length' ) );
			array_push( $delete_meta_keys, array( $product, 'gtin' ) );
			array_push( $delete_meta_keys, array( $product, 'mpn' ) );
			array_push( $delete_meta_keys, array( $product, 'tax_status' ) );
			array_push( $delete_meta_keys, array( $product, 'visibility' ) );
			array_push( $delete_meta_keys, array( $product, 'stock_status' ) );
			array_push( $delete_meta_keys, array( $product, 'manage_stock' ) );
			array_push( $delete_meta_keys, array( $product, 'product_attributes' ) );

			// Mapped data, not available for a specific product
			array_push($delete_meta_keys, array( $product, 'regular_price' ) );

			array_push($delete_meta_keys, array( $product, 'sale_price' ) );
			array_push($delete_meta_keys, array( $product, 'sale_price_dates_from' ) );
			array_push($delete_meta_keys, array( $product, 'sale_price_dates_to' ) );
			array_push($delete_meta_keys, array( $product, 'featured' ) );
			array_push($delete_meta_keys, array( $product, 'weight' ) );
			array_push($delete_meta_keys, array( $product, 'length' ) );
			array_push($delete_meta_keys, array( $product, 'width' ) );
			array_push($delete_meta_keys, array( $product, 'height' ) );
			array_push($delete_meta_keys, array( $product, 'sku' ) );
			array_push($delete_meta_keys, array( $product, 'external_url' ) );
			array_push($delete_meta_keys, array( $product, 'download_limit' ) );
			array_push($delete_meta_keys, array( $product, 'file_path' ) );
			array_push($delete_meta_keys, array( $product, 'quantity_sold' ) );
			array_push($delete_meta_keys, array( $product, '%_demo_link' ) );
			array_push($delete_meta_keys, array( $product, '%_faq' ) );
			array_push($delete_meta_keys, array( $product, '%_icon_colour' ) );
			array_push($delete_meta_keys, array( $product, '%_icon_path' ) );
			array_push($delete_meta_keys, array( $product, '%_roadmap' ) );
			array_push($delete_meta_keys, array( $product, '%_sidebar_featured' ) );
			array_push($delete_meta_keys, array( $product, '%_sidebar_one_liner' ) );
			array_push($delete_meta_keys, array( $product, '%_support' ) );
			array_push($delete_meta_keys, array( $product, '%_testimonial_%' ) );
			array_push($delete_meta_keys, array( $product, 'version_last_updated_%' ) );
			array_push($delete_meta_keys, array( $product, '%_total_downloads' ) );

			$updated++;

		}
		// Forget the last action
		woo_sm_clear_last_id();

		//$message = sprintf( '%d of %d Products have had their Product Attributes updated', $updated, count( $products ) );
		//woo_sm_admin_notice_html( $message );

	} else {
		$message = 'No Product\'s unmapped old meta were deleted';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(19) Delete the old plugins products keys for which there is an existing WooCommerce key as well
function woo_sm_migrate_products_delete_mapped_product_meta() {

	global $wpdb, $delete_meta_keys;

	$updated = 0;
	$former_id = 0;

	if( !empty( $delete_meta_keys ) ) {
		foreach( $delete_meta_keys as $delete_meta_key ) {
			// Delete the old mapped Jigoshop product meta
			$post_id = $delete_meta_key[0];
			$meta_key = $delete_meta_key[1];
			
			if ( strpos($meta_key, "%") === false )
			{
				$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE `post_id` = '" . $post_id . "' AND `meta_key` = '" . $meta_key . "'" );
			}
			else
			{
				$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE `post_id` = '" . $post_id . "' AND `meta_key` LIKE '" . $meta_key . "'" );
			}
			
			
			if ( $post_id == '-1' )
			{
			 	continue;
			}
			else if ( $post_id == $former_id )
			{
				continue;
			}
			else
			{
				$former_id = $post_id ;
				$updated++;
			}
			
			unset( $post_id );
			unset( $meta_key );
			
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Products old meta have been deleted', $updated, $updated );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = 'No Product\'s old meta were deleted';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(20) Migrate downloadable for products
function woo_sm_migrate_products_change_product_downloadable() {

	global $wpdb, $download_url;

 	$post_type = array( 'product', 'product_variation' );

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_product_downloadable_exists();
	}
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		foreach( $products as $key => $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Check for a downloadable product
			$meta_value = get_post_meta( $product, 'file_path', true );

			if( !empty( $meta_value ) ) {

				$output = "yes";

/*
				$wpdb->insert( $wpdb->prefix . 'postmeta', array(
					'post_id' => $product,
					'meta_key' => '_downloadable',
					'meta_value' => $output,
				) );
*/
				update_post_meta( $product, '_downloadable', $output );
				update_post_meta( $product, '_virtual', $output );

				// Update the download limit
				$download_limit = get_post_meta( $product, 'download_limit', true );

				if( empty( $download_limit ) )
					$download_limit = '';

				if( !empty( $download_limit ) ) {
					update_post_meta( $product, '_download_limit', $download_limit, true );
				}

				// Update the download path
/*
				$download_path_begin = 'a:1:{s:32:"043f3fe0fc05f0b3e8bc36343e20fad3";a:2:{s:4:"name";s:8:"name";s:4:"file";s:31:"';
				$download_path_end ='";}}';
				$download_path = $download_path_begin.$meta_value.$download_path_end
				$wpdb->insert( $wpdb->prefix . 'postmeta', array(
					'meta_value' => $meta_value,
					'post_id' => $product,
					'meta_key' => '_downloadable_files'//,
					// 'meta_id' => '0'
				) );
*/

				// file paths will be stored in an array keyed off md5(file path)
				$download_url = $meta_value;
				$download_array = array(
					'name' => basename( $download_url ),
					'file' => $download_url
				);
				$file_path = md5( $download_url );
				unset( $download_url );
				$_file_paths[$file_path] = $download_array;
				unset( $file_path, $download_array );
				update_post_meta( $product, '_downloadable_files', $_file_paths );
				unset( $_file_paths );

				$updated++;

			}
			delete_post_meta( $product, 'file_path' );
			delete_post_meta( $product, 'download_limit' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Products have had their Product Downloadable updated', $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = 'No Product\'s Downloadable were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(21) Migrate virtual for products
function woo_sm_migrate_products_change_product_virtual() {

	global $wpdb;

	$post_type = 'product';

	$products = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$products_query = new WP_Query( $args );
	if( $products_query->posts ) {
		$products = $products_query->posts;
	}
	$updated = 0;
	if( !empty( $products ) ) {
		$term_taxonomy = 'product_type';
		foreach( $products as $product ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $product );

			// Check for a virtual product
			$product_virtual_sql = $wpdb->prepare( "SELECT terms.`name` FROM `" . $wpdb->posts . "` posts INNER JOIN `" . $wpdb->term_relationships . "` relationships ON posts.`ID` = relationships.`object_id` AND posts.`post_type` = %s INNER JOIN `" . $wpdb->term_taxonomy . "` taxonomy ON relationships.`term_taxonomy_id` = taxonomy.`term_taxonomy_id` AND taxonomy.`taxonomy` = %s AND taxonomy.`count` <> 0 INNER JOIN `" . $wpdb->terms . "` terms ON taxonomy.`term_id` = terms.`term_id` WHERE posts.`ID` = %d", $post_type, $term_taxonomy, $product );
			$meta_value = $wpdb->get_var( $product_virtual_sql );
			unset( $product_virtual_sql );

			$output = 'no';
			if( $meta_value == 'virtual' ) {
				$output = "yes";
				$updated++;
			}
			update_post_meta( $product, '_virtual', $output );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'product_virtual' );

		$message = sprintf( '%d of %d Products have had their Product Virtual updated', $updated, count( $products ) );
		woo_sm_admin_notice_html( $message );

	} else {
		$message = 'No Product\'s Virtual were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}


//(22) Migrate categories for products
function woo_sm_migrate_products_change_product_categories() {

	$number = 6;
	$message = sprintf( '%d of %d Products have had their Product Categories updated', $number, $number );
	woo_sm_admin_notice_html( $message );

}
?>