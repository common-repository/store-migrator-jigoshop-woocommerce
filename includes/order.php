<?php
// Check if the shop_order_status from Jigoshop exists; it shouldn't
function woo_sm_register_jigo_order_status_taxonomy() {

	$term_taxonomy = 'shop_order_status';
	$response = taxonomy_exists( $term_taxonomy );
	if( $response == false ) {
		register_taxonomy('shop_order_status',
			array('shop_order'),
			array(
				'hierarchical' => true,
				'update_count_callback' => '_update_post_term_count',
				'labels' => array(
					'name' => __('Order statuses', 'jigoshop'),
					'singular_name' => __('Order status', 'jigoshop'),
					'search_items' => __('Search Order statuses', 'jigoshop'),
					'all_items' => __('All  Order statuses', 'jigoshop'),
					'parent_item' => __('Parent Order status', 'jigoshop'),
					'parent_item_colon' => __('Parent Order status:', 'jigoshop'),
					'edit_item' => __('Edit Order status', 'jigoshop'),
					'update_item' => __('Update Order status', 'jigoshop'),
					'add_new_item' => __('Add New Order status', 'jigoshop'),
					'new_item_name' => __('New Order status Name', 'jigoshop')
				),
				'public' => false,
				'show_ui' => false,
				'show_in_nav_menus' => false,
				'query_var' => true,
				'rewrite' => false,
			)
		);
	}

}

// Change the Jigoshop Order Status to match WooCommerce
function woo_sm_migrate_orders_change_order_status() {

	$post_type = 'shop_order';
	$term_taxonomy = 'shop_order_status';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => $term_taxonomy,
				'field' => 'slug',
				'terms' => array( 'pending', 'on-hold', 'processing', 'completed', 'cancelled', 'refunded' )
			)
		)
	);
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $order );

			$term_taxonomy = 'shop_order_status';
			$status = wp_get_object_terms( $order, $term_taxonomy );
			if( !empty( $status ) && is_wp_error( $status ) == false ) {
				$size = count( $status );
				for( $i = 0; $i < $size; $i++ ) {
					if( $term = get_term( $status[$i]->term_id, $term_taxonomy ) ) {
						if( !empty( $term->slug ) ) {
							$args = array(
								'ID' => $order,
								'post_status' => woo_sm_format_wc_order_status( $term->slug )
							);
							$response = wp_update_post( $args );
							if( $response )
								$updated++;
						}
						break;
					}
					unset( $term );
				}
			}

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Orders have had their Order Status updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Status\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_delete_order_status_terms() {

	$term_taxonomy = 'shop_order_status';

	$updated = 0;
	$args = array(
		'hide_empty'        => false,
		'fields'            => 'ids'
	); 
	$terms = get_terms( $term_taxonomy, $args );
	if( !empty( $terms ) && !is_wp_error( $terms ) ) {
		foreach( $terms as $term ) {

			$response = wp_delete_term( $term, $term_taxonomy );
			if( $response !== false )
				$updated++;

		}
	}

}

function woo_sm_format_wc_order_status( $sale_status ) {

	switch( $sale_status ) {

		case 'pending':
			$output = 'wc-pending';
			break;

		case 'on-hold':
			$output = 'wc-on-hold';
			break;

		case 'processing':
			$output = 'wc-processing';
			break;

		case 'completed':
			$output = 'wc-completed';
			break;

		case 'cancelled':
			$output = 'wc-cancelled';
			break;

		case 'refunded':
			$output = 'wc-refunded';
			break;

	}
	return $output;

}

function woo_sm_unregister_jigo_order_status_taxonomy() {

	$term_taxonomy = 'shop_order_status';

	// De-register customer taxonomy
	register_taxonomy( $term_taxonomy, array() );

}

function woo_sm_migrate_orders_change_order_key() {

	$post_type = 'shop_order';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_order_customer_exists();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Order Key
			$meta_value = get_post_meta( $order, 'order_key', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $order, '_order_key', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $order, 'order_key' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Orders have had their Order Key updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Key\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_change_order_customer() {

	$post_type = 'shop_order';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_order_customer_exists();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Order Customer
			$meta_value = get_post_meta( $order, 'customer_user', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			update_post_meta( $order, '_customer_user', $meta_value );
			if( !empty( $meta_value ) ) {
				$updated++;
			}
			delete_post_meta( $order, 'customer_user' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Orders have had their Order Customer updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Customer\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_add_order_currency() {

	$post_type = 'shop_order';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_orders_exist();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			if( function_exists( 'get_woocommerce_currency' ) ) {
				update_post_meta( $order, '_order_currency', get_woocommerce_currency(), true );
				$updated++;
			}

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Orders have had their Order Currency updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Currency\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_change_shipping_method() {

	global $wpdb;

	$post_type = 'shop_order';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_orders_exist();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Order data
			$meta_value = get_post_meta( $order, 'order_data', true );

                        if(array_key_exists('shipping_method', $meta_value) && array_key_exists('shipping_service', $meta_value)){
                            $shipping_method = $meta_value['shipping_method'];
                            $shipping_service = $meta_value['shipping_service'];

                            if( !empty( $shipping_method ) ) {

                                    // Generate the new Order Items
                                    $item_id = $wpdb->insert(
                                            $wpdb->prefix . 'woocommerce_order_items',
                                            array(
                                                    'order_item_name' => ( isset( $shipping_service ) ? $shipping_service : 'Free Shipping' ),
                                                    'order_item_type' => 'shipping',
                                                    'order_id' => $order
                                            )
                                    );
                                    wc_update_order_item_meta( $item_id, 'method_id', $shipping_method );
                                    wc_update_order_item_meta( $item_id, 'cost', 0 );

                                    // Re-save the Jigoshop Order Data Post meta
                                    unset( $meta_value['shipping_method'], $meta_value['shipping_service'] );
                                    update_post_meta( $order, 'order_data', $meta_value );

                                    unset( $meta_value, $shipping_service, $shipping_method, $term_id );
                                    $updated++;

                            }
                        
                   }

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'order_shipping_method' );

		$message = sprintf( '%d of %d Orders have had their Shipping Method updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Shipping Method\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_change_discount() {

	global $wpdb;

	$post_type = 'shop_order';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_orders_exist();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Order data
			$meta_value = get_post_meta( $order, 'order_data', true );

			$order_discounts = ( isset( $meta_value['order_discount_coupons'] ) ? $meta_value['order_discount_coupons'] : '' );

			// Generate the new Order Items
			if( !empty( $order_discounts ) ) {
				foreach( $order_discounts as $key => $coupon ) {

					// Generate the new Order Items
					$item_id = $wpdb->insert(
						$wpdb->prefix . 'woocommerce_order_items',
						array(
							'order_item_name' => ( isset( $coupon['code'] ) ? $coupon['code'] : '' ),
							'order_item_type' => 'coupon',
							'order_id' => $order
						)
					);
					wc_update_order_item_meta( $item_id, 'discount_amount', get_post_meta( $order, '_order_discount', true ) );
					unset( $meta_value['order_discount_coupons'][$key] );
					$updated++;

				}

				unset( $coupon, $key );
			}
			unset( $order_discounts );

			// Re-save the Jigoshop Order Data Post meta
			//unset($meta_value['order_discount_coupons']);
                        $meta_value = array_diff($meta_value, [$meta_value['order_discount_coupons']]);
			update_post_meta( $order, 'order_data', $meta_value );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'order_discount' );

		$message = sprintf( '%d of %d Orders have had their Order Discount updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Discount\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_copy_billing_shipping_address() {

	$post_type = 'shop_order';
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_orders_exist();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Takes the WooCommerce billing address
			$first_name = get_post_meta( $order, '_billing_first_name', true );
			$last_name = get_post_meta( $order, '_billing_last_name', true );
			$company = get_post_meta( $order, '_billing_company', true );
			$address_1 = get_post_meta( $order, '_billing_address_1', true );
			$address_2 = get_post_meta( $order, '_billing_address_2', true );
			$city = get_post_meta( $order, '_billing_city', true );
			$postcode = get_post_meta( $order, '_billing_postcode', true );
			$country = get_post_meta( $order, '_billing_country', true );
			$state = get_post_meta( $order, '_billing_state', true );

			// Only set the shipping meta if the existing shipping is empty
			if( get_post_meta( $order, '_shipping_first_name', true ) == false )
				update_post_meta( $order, '_shipping_first_name', $first_name );
			if( get_post_meta( $order, '_shipping_last_name', true ) == false )
				update_post_meta( $order, '_shipping_last_name', $last_name );
			if( get_post_meta( $order, '_shipping_company', true ) == false )
				update_post_meta( $order, '_shipping_company', $company );
			if( get_post_meta( $order, '_shipping_address_1', true ) == false )
				update_post_meta( $order, '_shipping_address_1', $address_1 );
			if( get_post_meta( $order, '_shipping_address_2', true ) == false )
				update_post_meta( $order, '_shipping_address_2', $address_2 );
			if( get_post_meta( $order, '_shipping_city', true ) == false )
				update_post_meta( $order, '_shipping_city', $city );
			if( get_post_meta( $order, '_shipping_postcode', true ) == false )
				update_post_meta( $order, '_shipping_postcode', $postcode );
			if( get_post_meta( $order, '_shipping_country', true ) == false )
				update_post_meta( $order, '_shipping_country', $country );
			if( get_post_meta( $order, '_shipping_state', true ) == false )
				update_post_meta( $order, '_shipping_state', $state );
			$updated++;

			unset( $first_name, $last_name, $company, $address_1, $address_2, $city, $postcode, $country, $state );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'order_copy_billing_shipping_address' );

		$message = sprintf( '%d of %d Orders have had their Shipping Address updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_change_order_items() {

	global $wpdb;

	$post_type = 'shop_order';
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_order_customer_exists();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Order data
			$meta_value = get_post_meta( $order, 'order_items', true );
			// Check if the Order Data is filled for this Order
			if( !empty( $meta_value ) ) {
				$total_tax = 0;
				foreach( $meta_value as $order_item ) {
					// Generate the new Order Items
					$item_id = wc_add_order_item( $order, array(
							'order_item_name' => ( isset( $order_item['name'] ) ? $order_item['name'] : '' ),
							'order_item_type' => 'line_item'
						)
					);
					// Set the basic Order Item details about the Product
					wc_update_order_item_meta( $item_id, '_qty', $order_item['qty'] );
					wc_update_order_item_meta( $item_id, '_tax_class', '' );
					wc_update_order_item_meta( $item_id, '_product_id', $order_item['id'] );
					wc_update_order_item_meta( $item_id, '_variation_id', $order_item['variation_id'] );
					// If it's a variation then create the pa_... meta key
					// @mod - only applicable for VLShop, will need a general solution for generic Jigoshop stores
					if( !empty( $order_item['variation'] ) ) {
						foreach( $order_item['variation'] as $attribute_taxonomy => $attribute ) {
							wc_update_order_item_meta( $item_id, 'pa_' . str_replace( 'tax_', '', $attribute_taxonomy ), $attribute );
						}
						unset( $attribute, $attribute_taxonomy );
					}
					// Set the line sub-total and totals
					wc_update_order_item_meta( $item_id, '_line_subtotal', wc_format_decimal( $order_item['cost'] ) );
					wc_update_order_item_meta( $item_id, '_line_subtotal_tax', 0 );
					wc_update_order_item_meta( $item_id, '_line_total', wc_format_decimal( $order_item['cost_inc_tax'] ) );
					wc_update_order_item_meta( $item_id, '_line_tax', 0 );
					wc_update_order_item_meta( $item_id, '_line_tax_data', array( 'total' => array(), 'subtotal' => array() ) );
					// Update the GST amount for WooCommerce
					// @mod - only applicable for VLShop, will need a general solution for generic Jigoshop stores
					if( $order_item['taxrate'] > 0 ) {
						$total_tax += $order_item['cost'] / $order_item['taxrate'];
						wc_update_order_item_meta( $item_id, '_line_subtotal_tax', $order_item['cost'] / $order_item['taxrate'] );
						wc_update_order_item_meta( $item_id, '_line_tax', $order_item['cost'] / $order_item['taxrate'] );
						wc_update_order_item_meta( $item_id, '_line_tax_data', array( 'total' => $order_item['cost'] / $order_item['taxrate'], 'subtotal' => $order_item['cost'] / $order_item['taxrate'] ) );
						// Create a line item for tax that WooCommerce uses
						$tax_id = wc_add_order_item( $order, array(
								'order_item_name' => 'AU-GST-1',
								'order_item_type' => 'tax'
							)
						);
						wc_update_order_item_meta( $tax_id, 'rate_id', 2 );
						wc_update_order_item_meta( $tax_id, 'label', 'GST' );
						wc_update_order_item_meta( $tax_id, 'compound', 0 );
						wc_update_order_item_meta( $tax_id, 'tax_amount', $order_item['cost'] / $order_item['taxrate'] );
						wc_update_order_item_meta( $tax_id, 'shipping_tax_amount', 0 );
						unset( $tax_id );
					}
					unset( $item_id );

				}

				// Order data
				$meta_value = get_post_meta( $order, 'order_data', true );
				// Check if the Order Data is filled for this Order
				if( !empty( $meta_value ) ) {
					update_post_meta( $order, '_order_tax', $total_tax );
					unset( $meta_value['order_tax'] );
					unset( $meta_value['order_tax_no_shipping_tax'] );
					unset( $meta_value['order_tax_divisor'] );
					unset( $meta_value['order_total_prices_per_tax_class_ex_tax'] );
					// Re-save the Jigoshop Order Data Post meta
					update_post_meta( $order, 'order_data', $meta_value );
				}
				$updated++;
			}
			update_post_meta( $order, '_prices_include_tax', 'no' );
			// delete_post_meta( $order, 'order_items' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'order_items' );

		$message = sprintf( '%d of %d Orders have had their Order Items updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Item\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

function woo_sm_migrate_orders_change_order_meta() {

	$post_type = 'shop_order';

	$orders = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	// Include an offset if we are resuming a migration
	if( woo_get_action() == 'resume-migration' ) {
		$args['offset'] = woo_sm_get_option( 'current_id', 0 );
		$args['posts_per_page'] = woo_sm_check_orders_exist();
	}
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	$updated = 0;
	if( !empty( $orders ) ) {

		// Jigoshop to WooCommerce Order Data keys
		$order_data_keys = array(
			// order_discount_coupons
			'billing_first_name' => '_billing_first_name',
			'billing_last_name' => '_billing_last_name',
			'billing_company' => '_billing_company',
			'billing_address_1' => '_billing_address_1',
			'billing_address_2' => '_billing_address_2',
			'billing_city' => '_billing_city',
			'billing_postcode' => '_billing_postcode',
			'billing_country' => '_billing_country',
			'billing_state' => '_billing_state',
			'billing_email' => '_billing_email',
			'billing_phone' => '_billing_phone',
			'shipping_first_name' => '_shipping_first_name',
			'shipping_last_name' => '_shipping_last_name',
			'shipping_company' => '_shipping_company',
			'shipping_address_1' => '_shipping_address_1',
			'shipping_address_2' => '_shipping_address_2',
			'shipping_city' => '_shipping_city',
			'shipping_postcode' => '_shipping_postcode',
			'shipping_country' => '_shipping_country',
			'shipping_state' => '_shipping_state',
			'payment_method' => '_payment_method',
			'payment_method_title' => '_payment_method_title',
			'order_shipping' => '_order_shipping',
			'order_discount' => '_order_discount',
			'order_shipping_tax' => '_order_shipping_tax',
			'order_total' => '_order_total'
		);
		foreach( $orders as $key => $order ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $key );

			// Order Data
			$meta_value = get_post_meta( $order, 'order_data', true );

			// Check if the Order Data is filled for this Order
			if( !empty( $meta_value ) ) {

				// Scan each known Jigoshop Order Data key
				foreach( $order_data_keys as $jigoshop_key => $wc_key ) {
					// Check if the Order Data key exists
					if( isset( $meta_value[$jigoshop_key] ) ) {
						update_post_meta( $order, $wc_key, $meta_value[$jigoshop_key] );
						// unset( $meta_value[$jigoshop_key] );
					}
				}

				// Manually set WC Post meta
				update_post_meta( $order, '_cart_discount', 0 );

				// Unused Jigoshop Post meta
				unset( $meta_value['order_subtotal'] );
				unset( $meta_value['order_discount_subtotal'] );

				// Re-save the Jigoshop Order Data Post meta
				update_post_meta( $order, 'order_data', $meta_value );
				$updated++;

			}

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'order_meta' );

		$message = sprintf( '%d of %d Orders have had their Order Post meta updated', $updated, count( $orders ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order Post meta\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}
	
}

function woo_sm_migrate_orders_change_order_downloads() {

	global $wpdb;

	// Get the full list of downloads
	$downloads = array();
	if( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "jigoshop_downloadable_product_permissions'" ) ) {
		$downloads_sql = "SELECT * FROM `" . $wpdb->prefix . "jigoshop_downloadable_product_permissions` ORDER BY user_id";
		$downloads = $wpdb->get_results( $downloads_sql );
	}
	$updated = 0;
	if( !empty( $downloads ) ) {
		foreach( $downloads as $download_id => $download ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $download_id );

			// Ignore downloads without a e-mail address
			if( empty( $download->user_email ) )
				continue;

			// Get the Order ID based on the Order Key
			$post_type = 'shop_order';
			$order = 0;
			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => '-1',
				'post_status' => 'any',
				'fields' => 'ids',
				'meta_key' => '_order_key',
				'meta_value' => $download->order_key,
			);
			$order_query = new WP_Query( $args );
			if( !empty( $order_query->posts ) ) {
				$order = absint( $order_query->posts[0] );
			}
			unset( $order_query );

			$download->order_id = ( isset( $order ) ? $order : 0 );
			// Ignore downloads without a Order ID
			if( empty( $download->order_id ) )
				continue;

			if( !empty( $download->order_id ) ) {

				$order = get_post( $download->order_id );

				$file_downloads = get_post_meta( $download->product_id, '_downloadable_files', true );
				if( !empty( $file_downloads ) ) {

					// WooCommerce Products can contain multiple downloads, give the customer access to all attachments
					$file_downloads = array_keys( $file_downloads );
					foreach( $file_downloads as $file_download ) {

						// Insert the new download permissions item
						$wpdb->insert( $wpdb->prefix . 'woocommerce_downloadable_product_permissions', array(
							'download_id' => $file_download,
							'product_id' => $download->product_id,
							'order_id' => $download->order_id,
							'order_key' => $download->order_key,
							'user_email' => $download->user_email,
							'user_id' => $download->user_id,
							'downloads_remaining' => $download->downloads_remaining,
							'access_granted' => ( isset( $order->post_date ) ? $order->post_date : current_time( 'mysql' ) ),
							'download_count' => 0
						) );
						$updated++;

					}
				}
				unset( $file_downloads, $file_download );
			}

		}
		// Empty the jigoshop_downloadable_product_permissions table
		$wpdb->query( "TRUNCATE TABLE " . $wpdb->prefix . "jigoshop_downloadable_product_permissions" );

		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Download Permissions have been updated', $updated, count( $downloads ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Order File Download\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}
?>