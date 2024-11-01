<?php
function wpsc_sm_html_page() {

	global $migrate;

	woo_sm_template_header( __( 'Store Migrator', 'woo_sm' ) );
	$action = woo_get_action();
	switch( $action ) {

		case 'migrate-sales':
			$message = sprintf( __( '%d Sales have been migrated.' ), $migrate->count );
			$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
			echo $output;

			if( $migrate->output ) {
				echo '<h3>' . __( 'Corrupt file downloads', 'jigo_sm' ) . '</h3>';
				echo '<p>' . __( 'These customers do not have User accounts and will need to be notified in order to download Plugin updates via My Account.', 'jigo_sm' ) . '</p>';
				$i = 0;
echo '
				<table>
					<thead>
						<tr>
							<th>Product ID</th>
							<th>Session ID</th>
							<th>E-mail</th>
						</tr>
					</thead>
					<tbody>';
				foreach( $migrate->output as $output_log ) {
					$i++;
					$post = get_post( $output_log['product_id'] );
echo '
						<tr>
							<td>' . $post->post_title . '</td>
							<td>' . $output_log['order_key'] . '</td>
							<td><a href="' . $output_log['user_email'] . '">' . $output_log['user_email'] . '</a></td>
						</tr>
';
				}
				echo '
					</tbody>
					<thead>
						<tr>
							<td colspan="3">Total: ' . $i . '</td>
						</tr>
					</thead>
				</table>';
			}

			wpsc_sm_default_html_page();
			break;

		case 'migrate-coupons':
			$message = sprintf( __( '%d Coupons have been migrated.' ), $migrate->count );
			$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
			echo $output;

			wpsc_sm_default_html_page();
			break;

		default:
			wpsc_sm_default_html_page();
			break;

	}
	woo_sm_template_footer();

}

function wpsc_sm_migrate_orders() {

	$migrate = new stdClass;
	$wpsc_sales_sql = "SELECT *, `id` as ID FROM `" . $wpdb->prefix . "wpsc_purchase_logs`";
	$wpsc_sales = $wpdb->get_results( $wpsc_sales_sql );

	$migrate->sql = $wpsc_sales_sql;
	$migrate->count = $wpdb->num_rows;
	$migrate->output = array();
	if( $wpsc_sales ) {
		$jigo_sales = array();
		$i = 0;
		$wpsc_store_checkout_sql = "SELECT *, `unique_name` as wpsc_unique_name FROM `" . $wpdb->prefix . "wpsc_checkout_forms` WHERE `checkout_set` = 0 AND `active` = 1";
		$wpsc_store_checkout = $wpdb->get_results( $wpsc_store_checkout_sql );
		$wpsc_store_checkout_temp = array();
		foreach( $wpsc_store_checkout as $wpsc_store_field_key => $wpsc_store_field )
			$wpsc_store_checkout_temp[$wpsc_store_field->id] = $wpsc_store_field;
		$wpsc_store_checkout = $wpsc_store_checkout_temp;
		foreach( $wpsc_sales as $wpsc_key => $wpsc_sale ) {
			$wpsc_order_data = array();
			if( $wpsc_store_checkout ) {
				$wpsc_order_checkout = array();
				$jigo_order_checkout = array();
				foreach( $wpsc_store_checkout as $wpsc_store_field_key => $wpsc_store_field ) {
					$wpsc_order_checkout_field_sql = $wpdb->prepare( "SELECT `value` FROM `" . $wpdb->prefix . "wpsc_submited_form_data` WHERE `log_id` = %d AND `form_id` = %d LIMIT 1", $wpsc_sale->ID, $wpsc_store_field->id );
					$wpsc_store_field->value = $wpdb->get_var( $wpsc_order_checkout_field_sql );
					$wpsc_order_checkout[$wpsc_store_field_key] = $wpsc_store_field;
					switch( $wpsc_store_field->wpsc_unique_name ) {

						case 'billingfirstname':
							$wpsc_store_field->jigo_unique_name = 'billing_first_name';
							break;

						case 'billinglastname':
							$wpsc_store_field->jigo_unique_name = 'billing_last_name';
							break;

						case 'billingaddress':
						$wpsc_store_field->jigo_unique_name = 'billing_address_1';
							break;

						case 'billingcity':
							$wpsc_store_field->jigo_unique_name = 'billing_city';
							break;

						case 'billingstate':
							$wpsc_store_field->jigo_unique_name = 'billing_state';
							break;

						case 'billingcountry':
							$wpsc_store_field->jigo_unique_name = 'billing_country';
							break;

						case 'billingpostcode':
							$wpsc_store_field->jigo_unique_name = 'billing_postcode';
							break;

						case 'billingemail':
							$wpsc_store_field->jigo_unique_name = 'billing_email';
							break;

						case 'billingphone':
							$wpsc_store_field->jigo_unique_name = 'billing_phone';
							break;

						case 'shippingfirstname':
							$wpsc_store_field->jigo_unique_name = 'shipping_first_name';
							break;

						case 'shippinglastname':
							$wpsc_store_field->jigo_unique_name = 'shipping_last_name';
							break;

						case 'shippingaddress':
							$wpsc_store_field->jigo_unique_name = 'shipping_address_1';
							break;

						case 'shippingcity':
							$wpsc_store_field->jigo_unique_name = 'shiping_city';
							break;

						case 'shippingstate':
							$wpsc_store_field->jigo_unique_name = 'shipping_state';
							break;

						case 'shippingcountry':
							$wpsc_store_field->jigo_unique_name = 'shipping_country';
							break;

						case 'shippingpostcode':
							$wpsc_store_field->jigo_unique_name = 'shipping_postcode';
							break;

					}
					if( $wpsc_store_field->jigo_unique_name )
						$jigo_order_checkout[$wpsc_store_field->jigo_unique_name] = $wpsc_store_field->value;
				}
				unset( $wpsc_store_field );
			}
			$wpsc_order_cart_contents_sql = $wpdb->prepare( "SELECT * FROM `" . $wpdb->prefix . "wpsc_cart_contents` WHERE `purchaseid` = %d", $wpsc_sale->ID );
			$wpsc_order_cart_contents = $wpdb->get_results( $wpsc_order_cart_contents_sql );
			$wpsc_order_data[$wpsc_key] = array(
				'cart_contents' => $wpsc_order_checkout,
				'order_key' => $wpsc_sale->sessionid,
				'customer_user' => $wpsc_sale->user_ID,
				'order_items' => $wpsc_order_cart_contents
			);
			$jigo_order_discounts = array();
			if( $wpsc_sale->discount_data ) {
				$wpsc_coupon_sql = $wpdb->prepare( "SELECT * FROM `" . $wpdb->prefix . "wpsc_coupon_codes` WHERE  `coupon_code` =  '%s' LIMIT 1", $wpsc_sale->discount_data );
				$wpsc_coupon = $wpdb->get_row( $coupon_sql );
				$jigo_order_discounts[] = array( 
					'id' => $wpsc_coupon->ID,
					'code' => $wpsc_sale->discount_data,
					'type' => null,
					'amount' => $wpsc_sale->discount_value,
					'date_from' => $wpsc_coupon->start,
					'date_to' => $wpsc_coupon->expiry,
					'usage_limit' => null,
					'usage' => null,
					'free_shipping' => null,
					'individual_use' => null,
					'order_total_min' => null,
					'order_total_max' => null,
					'include_products' => array(),
					'exclude_products' => array(),
					'include_categories' => array(),
					'exclude_categories' => array(),
					'pay_methods' => array()
				);
				unset( $wpsc_coupon );
			}
			$jigo_order_items = array();
			$wpsc_order_items_sql = $wpdb->prepare( "SELECT * FROM `" . $wpdb->prefix . "wpsc_cart_contents` WHERE  `purchaseid` = %d", $wpsc_sale->ID );
			$wpsc_order_items = $wpdb->get_results( $wpsc_order_items_sql );
			if( $wpsc_order_items ) {
				foreach( $wpsc_order_items as $wpsc_cart_item ) {
					$wpsc_cart_item->category = jigo_sm_return_category( $wpsc_cart_item->name );
					$wpsc_cart_item->name = jigo_sm_strip_product_name( $wpsc_cart_item->name );
					$post_type = 'product';
					if( $wpsc_cart_item->name ) {
						// Get the Post ID of the Base Product (e.g. Product Importer Deluxe)
						$post_id_sql = "SELECT ID FROM `" . $wpdb->posts . "` WHERE post_title = '" . $wpsc_cart_item->name . "' AND post_type = '" . $post_type . "' LIMIT 1";
						$wpsc_cart_item->post_id = $wpdb->get_var( $post_id_sql );
						unset( $post_id_sql );
					}
					$wpsc_cart_item->variant_id = '';
					if( $wpsc_cart_item->category && $wpsc_cart_item->post_id ) {
						// Get the Post ID of the Product Variant (e.g. Product Importer Deluxe: WP e-Commerce)
						// Assign prodid to the Post ID of the Base Product
						$wpsc_cart_item->prodid = $wpsc_cart_item->post_id;
						$post_type = "product_variation";
						$post_id_sql = "SELECT ID FROM `" . $wpdb->posts . "` WHERE `post_title` = '" . $wpsc_cart_item->name . " - [platform: " . $wpsc_cart_item->category . "]' AND `post_type` = '" . $post_type . "' AND `post_parent` = " . $wpsc_cart_item->post_id . " LIMIT 1";
						$wpsc_cart_item->variant_id = $wpdb->get_var( $post_id_sql );
						unset( $post_id_sql );
					} else {
						// Assign prodid to the Post ID of the Base Product, there's no Variant
						$wpsc_cart_item->prodid = $wpsc_cart_item->post_id;
					}
					$post_data = get_post( $wpsc_cart_item->prodid );
					if( $post_data ) {
						// If the Base Product Post ID is not a Product, ignore it
						if( $post_data->post_type <> 'product' )
							$wpsc_cart_item->category = '';
					}
					if( $wpsc_cart_item->category ) {
						$jigo_order_items[] = array(
							'id' => $wpsc_cart_item->prodid,
							'variation_id' => $wpsc_cart_item->variant_id,
							'variation' => '',
							'customization' => '',
							'name' => $wpsc_cart_item->name,
							'qty' => $wpsc_cart_item->quantity,
							'cost' => $wpsc_cart_item->price,
							'cost_inc_tax' => -1,
							'taxrate' => ''
						);
					}
				}
				if( !$jigo_order_items ) {
					$wpsc_sale->notes = __( 'A non e-Commerce Plugin item was purchased (e.g. Zune HD, Zune Pass, etc.).', 'jigo_sm' );
				}
				unset( $wpsc_order_items );
			}
			$jigo_sales[] = array(
				'order_data' => array(
					'order_discount_coupons' => $jigo_order_discounts,
					'billing_first_name' => $jigo_order_checkout['billing_first_name'],
					'billing_last_name' => $jigo_order_checkout['billing_last_name'],
					'billing_company' => null,
					'billing_address_1' => $jigo_order_checkout['billing_address_1'],
					'billing_address_2' => null,
					'billing_city' => $jigo_order_checkout['billing_city'],
					'billing_postcode' => $jigo_order_checkout['billing_postcode'],
					'billing_country' => $jigo_order_checkout['billing_country'],
					'billing_state' => $jigo_order_checkout['billing_state'],
					'billing_email' => $jigo_order_checkout['billing_email'],
					'billing_phone' => $jigo_order_checkout['billing_phone'],
					'shipping_first_name' => $jigo_order_checkout['shipping_first_name'],
					'shipping_last_name' => $jigo_order_checkout['shipping_last_name'],
					'shipping_company' => null,
					'shipping_address_1' => $jigo_order_checkout['shipping_address_1'],
					'shipping_address_2' => null,
					'shipping_city' => $jigo_order_checkout['shipping_city'],
					'shipping_postcode' => $jigo_order_checkout['shipping_postcode'],
					'shipping_country' => $jigo_order_checkout['shipping_country'],
					'shipping_state' => $jigo_order_checkout['shipping_state'],
					'shipping_method' => 'free_shipping',
					'shipping_service' => 'Free Shipping',
					'payment_method' => wpsc_sm_get_gateway_method( $wpsc_sale->gateway ),
					'payment_method_title' => wpsc_sm_get_gateway_label( $wpsc_sale->gateway ),
					'order_subtotal' => $wpsc_sale->totalprice,
					'order_discount_subtotal' => $wpsc_sale->totalprice,
					'order_shipping' => $wpsc_sale->base_shipping,
					'order_discount' => $wpsc_sale->discount_value,
					'order_tax' => 'jigoshop_zero_rate:amount^0,rate^0,compound^,display^Tax',
					'order_tax_divisor' => 100,
					'order_shipping_tax' => 0.00,
					'order_total' => $wpsc_sale->totalprice,
					'order_total_prices_per_tax_class_ex_tax' => array(
						'jigoshop_zero_rate' => 200
					)
				),
				'date' => jigo_sm_convert_sale_date( $wpsc_sale->date ),
				'old_id' => $wpsc_sale->ID,
				'sale_status' => $wpsc_sale->processed,
				'order_key' => $wpsc_sale->sessionid,
				'customer_user' => $wpsc_sale->user_ID,
				'order_items' => $jigo_order_items,
				'notes' => $wpsc_sale->notes
			);
			unset( $jigo_order_discounts, $jigo_order_items );
			$i++;
		}
	}

	$post_type = 'shop_order';
	foreach( $jigo_sales as $jigo_key => $jigo_sale ) {
		$post_data = array(
			'post_author' => $user_ID,
			'post_date' => $jigo_sale['date'],
			'post_date_gmt' => $jigo_sale['date'],
			'post_title' => sprintf( __( 'Order &ndash; %s', 'jigo_sm' ), date() ),
			'post_excerpt' => $jigo_sale['notes'],
			'post_status' => 'publish',
			'comment_status' => 'open',
			'ping_status' => 'open',
			'post_type' => $post_type
		);
		$duplicate_exists_args = array(
			'post_type' => $post_type,
			'meta_key' => 'old_id',
			'meta_value' => $jigo_sale['old_id'],
			'numberposts' => 1,
			'post_status' => 'publish'
		);
		$jigo_sale['duplicate_exists'] = get_posts( $duplicate_exists_args );
		if( !$jigo_sale['duplicate_exists'] ) {
			// New Sale to be generated
			$post_id = wp_insert_post( $post_data );
			if( $post_id ) {
				update_post_meta( $post_id, 'order_items', $jigo_sale['order_items'] );
				update_post_meta( $post_id, 'order_data', $jigo_sale['order_data'] );
				update_post_meta( $post_id, 'customer_user', $jigo_sale['customer_user'] );
				update_post_meta( $post_id, 'order_key', $jigo_sale['order_key'] );
				update_post_meta( $post_id, 'old_id', $jigo_sale['old_id'] );
			}
			$jigo_sales[$key]['original_sale_status'] = $jigo_sale['sale_status'];
			$jigo_sale['sale_status'] = jigo_sm_convert_sale_status( $jigo_sale['sale_status'] );
			if( is_array( $jigo_sale['sale_status'] ) ) {
				$term_taxonomy = 'shop_order_status';
				wp_set_post_terms( $post_id, array( (int)$jigo_sale['sale_status']['term_id'] ), $term_taxonomy, true );
			}
		} else {
			// Refresh Sale details (e.g. downloadable files, etc.), run this after a successful import
			$post_id = $jigo_sale['duplicate_exists'][0]->ID;
			if( $jigo_sale['order_items'] ) {
				$size = count( $jigo_sale['order_items'] );
				for( $i = 0; $i < $size; $i++ ) {
					$downloadable_products_sql = $wpdb->prepare( "SELECT COUNT(product_id) FROM `" . $wpdb->prefix . "jigoshop_downloadable_product_permissions` WHERE `order_key` = '%s'", $jigo_sale['order_key'] );
					$downloadable_products = $wpdb->get_var( $downloadable_products_sql );
					if( $jigo_sale['order_items'][$i]['variation_id'] )
						$product_id = $jigo_sale['order_items'][$i]['variation_id'];
					else
						$product_id = $jigo_sale['order_items'][$i]['id'];
					if( $downloadable_products == 0 && $product_id ) {
						$post = get_post( $product_id );
						if( $post->post_type == 'product' || $post->post_type == 'product_variation' ) {
							if( $jigo_sale['sale_status'] == 3 || $jigo_sale['sale_status'] == 4 || $jigo_sale['sale_status'] == 5 ) {
								$wpdb->insert( $wpdb->prefix . 'jigoshop_downloadable_product_permissions', array(
									'product_id' => $product_id,
									'user_email' => $jigo_sale['order_data']['billing_email'],
									'user_id' => $jigo_sale['customer_user'],
									'order_key' => $jigo_sale['order_key'],
									'downloads_remaining' => ''
								) );
							}
						}
					} else {
						// @mod - For the file download to be valid the e-mail address must match the User e-mail
						$check_downloadable_user_email_sql = $wpdb->prepare( "SELECT `user_email`, `user_id` FROM `" . $wpdb->prefix . "jigoshop_downloadable_product_permissions` WHERE `order_key` = '%s'", $jigo_sale['order_key'] );
						$check_downloadable_user_email = $wpdb->get_results( $check_downloadable_user_email_sql );
						if( $check_downloadable_user_email ) {
							foreach( $check_downloadable_user_email as $check_download_user_email ) {
								if( $check_download_user_email->user_id > 0 ) {
									$user_info = get_userdata( $check_download_user_email->user_id );
									if( $user_info->user_email <> $check_download_user_email->user_email ) {
										$user_info = get_user_by( 'email', $check_download_user_email->user_email );
										if( $user_info ) {
											// Has an incorrect User ID assigned to it, found an account that matches
											$wpdb->update( $wpdb->prefix . 'jigoshop_downloadable_product_permissions', array(
												'user_email' => $user_info->user_email,
												'user_id' => $user_info->ID
											), array(
												'product_id' => $product_id,
												'user_email' => $check_download_user_email->user_email,
												'user_id' => $jigo_sale['customer_user'],
												'order_key' => $jigo_sale['order_key']
											) );
										}
									}
								} else {
									$user_info = get_user_by( 'email', $check_download_user_email->user_email );
									if( $user_info ) {
										// Doesn\'t have a User assigned to the Sale, found a User though matching the e-mail address.
										$wpdb->update( $wpdb->prefix . 'jigoshop_downloadable_product_permissions', array(
											'user_id' => $user_info->ID,
											'user_email' => $user_info->user_email
										), array(
											'product_id' => $product_id,
											'user_email' => $check_download_user_email->user_email,
											'user_id' => $jigo_sale['customer_user'],
											'order_key' => $jigo_sale['order_key']
										) );
									} else {
										// Couldn't find a User mathing that e-mail address.
										if( !$product_id ) {
											$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "jigoshop_downloadable_product_permissions` WHERE `product_id` = '' AND `order_key` = '" . $jigo_sale['order_key'] . "' AND `user_email` = '" . $check_download_user_email->user_email . "'" );
										} else {
											$migrate->output[] = array(
												'product_id' => $product_id,
												'order_key' => $jigo_sale['order_key'],
												'user_email' => $check_download_user_email->user_email
											);
										}
									}
								}
							}
						}
					}
					unset( $product_id );
				}
			}
		}
		unset( $jigo_sale );
	}
	unset( $jigo_sales );

}

function wpsc_sm_migrate_coupons() {

	$migrate = new stdClass;
	$wpsc_coupons_sql = "SELECT *, `id` as ID FROM `" . $wpdb->prefix . "wpsc_coupon_codes`";
	$wpsc_coupons = $wpdb->get_results( $wpsc_coupons_sql );
	$migrate->sql = $wpsc_coupons_sql;
	$migrate->count = $wpdb->num_rows;
	if( $wpsc_coupons ) {
		$post_type = 'shop_coupon';
		foreach( $wpsc_coupons as $wpsc_coupon ) {
			$post_data = array(
				'post_author' => $user_ID,
				'post_date' => $wpsc_coupon['start'],
				'post_date_gmt' => $wpsc_coupon['start'],
				'post_title' => $wpsc_coupon['coupon_code'],
				'post_excerpt' => '',
				'post_status' => 'publish',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_type' => $post_type
			);
			$duplicate_exists_args = array(
				'name' => $wpsc_coupon['coupon_code'],
				'post_type' => $post_type,
				'numberposts' => 1,
				'post_status' => 'publish'
			);
			$wpsc_coupon->duplicate_exists = get_posts( $duplicate_exists_args );
			if( !$wpsc_coupon->duplicate_exists ) {
				/* New Coupon to be generated */
				$post_id = wp_insert_post( $post_data );
				update_post_meta( $post_id, 'date_from', strtotime( 'Y-m-d H:i:s', $wpsc_coupon->start ) );
				update_post_meta( $post_id, 'date_to', strtotime( 'Y-m-d H:i:s', $wpsc_coupon->expiry ) );
				update_post_meta( $post_id, 'usage_limit', '' );
				update_post_meta( $post_id, 'amount', $wpsc_coupon->value );
				update_post_meta( $post_id, 'individual_use', 1 );
				update_post_meta( $post_id, 'type', '' );
				update_post_meta( $post_id, 'free_shipping', '' );
				update_post_meta( $post_id, 'order_total_min', '' );
				update_post_meta( $post_id, 'order_total_max', '' );
				update_post_meta( $post_id, 'include_products', 'a:0:{}' );
				update_post_meta( $post_id, 'exclude_products', 'a:0:{}' );
				update_post_meta( $post_id, 'include_categories', 'a:0:{}' );
				update_post_meta( $post_id, 'exclude_categories', 'a:0:{}' );
				update_post_meta( $post_id, 'pay_methods', 'a:0:{}' );
			}
		}
	}

}

function wpsc_sm_get_gateway_method( $gateway_name ) {

	$output = '';
	switch( $gateway_name ) {

		case 'wpsc_merchant_paypal_standard':
			$output = 'paypal';
			break;

	}
	return $output;

}

function wpsc_sm_get_gateway_label( $gateway_name ) {

	global $nzshpcrt_gateways;

	$output = '';
	$gateways = $nzshpcrt_gateways;
	// If WP e-Commerce is active
	if( $gateways ) {
		foreach( $gateways as $gateway ) {
			if( $gateway['internalname'] == $gateway_name ) {
				$output = $gateway['name'];
				break;
			}
		}
	} else {
		switch( $gateway_name ) {

			case 'wpsc_merchant_paypal_standard':
				$output = 'PayPal';
				break;

		}
	}
	return $output;

}

function wpsc_sm_strip_product_name( $product_name ) {

	$output = '';
	// $product_name = strtolower( $product_name );
	if( strstr( $product_name, 'WP E-Commerce' ) )
		$output = str_replace( ' for WP E-Commerce', '', $product_name );
	if( strstr( $product_name, 'WP e-Commerce' ) )
		$output = str_replace( ' for WP e-Commerce', '', $product_name );
	if( strstr( $product_name, 'Jigoshop' ) )
		$output = str_replace( ' for Jigoshop', '', $product_name );
	if( strstr( $product_name, 'WooCommerce' ) )
		$output = str_replace( ' for WooCommerce', '', $product_name );
	return $output;

}

function wpsc_sm_return_category( $product_name ) {

	$output = '';
	$product_name = strtolower( $product_name );
	if( strstr( $product_name, 'wp e-commerce' ) )
		$output = 'wp-e-commerce';
	if( strstr( $product_name, 'jigoshop' ) )
		$output = 'jigoshop';
	if( strstr( $product_name, 'woocommerce' ) )
		$output = 'woocommerce';
	return $output;

}

function wpsc_sm_convert_sale_date( $date ) {

	$output = '';
	$output = date( 'Y-m-d H:i:s', $date );
	return $output;

}

function wpsc_sm_convert_sale_status( $sale_status ) {

	$output = false;
	$term_taxonomy = 'shop_order_status';
	switch( $sale_status ) {

		case 1:
			$term_name = 'pending';
			break;

		case 2:
			$term_name = 'processing';
			break;

		case 3:
			$term_name = 'completed';
			break;

		case 4:
			$term_name = 'completed';
			break;

		case 5:
			$term_name = 'completed';
			break;

		case 6:
			$term_name = 'cancelled';
			break;

		case 7:
			$term_name = 'refunded';
			break;

	}
	if( $term_name )
		$term_id = term_exists( $term_name, $term_taxonomy );
	if( $term_id )
		$output = $term_id;
	return $output;

}
?>