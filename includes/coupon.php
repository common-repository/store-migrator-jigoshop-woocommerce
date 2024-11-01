<?php 
//(1) Migrate types for coupons
function woo_sm_migrate_coupons_change_coupon_type() {

	$post_type = 'shop_coupon';
	
	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Type
			$meta_value = get_post_meta( $coupon, 'type', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'discount_type', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'type' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Type updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Type\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}
	
}

//(2) Migrate amounts for coupons
function woo_sm_migrate_coupons_change_coupon_amount() {

	$post_type = 'shop_coupon';
	
	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Amount
			$meta_value = get_post_meta( $coupon, 'amount', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'coupon_amount', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'amount' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Amount updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Amount\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}
}

//(3) Migrate Expiry Date for coupons
function woo_sm_migrate_coupons_change_coupon_expiry_date() {

	$post_type = 'shop_coupon';
	
	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Expiry Date
			$meta_value = get_post_meta( $coupon, 'date_to', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				// Start of date transform: jigoshop date format in UNIX Timestamp e.g 1417132799 to YYYY-MM-DD of WooCommerce
				$output = date( 'Y-m-d', $meta_value );
				update_post_meta( $coupon, 'expiry_date', $output );
				$updated++;
			}
			delete_post_meta( $coupon, 'date_to' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Expiry Date updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Expiry Date\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}	
}

//(4) Migrate Usage Limit for coupons
function woo_sm_migrate_coupons_change_coupon_usage_limit() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			$updated++;

			// Coupon Usage Limit
			$meta_value = get_post_meta( $coupon, 'usage_limit', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				// @mod - Don't do anything, Jigoshop and WooCommerce play nice for once!
				// update_post_meta( $coupon, 'usage_limit', $meta_value );
				$updated++;
			}

		}
		// Forget the last action
		woo_sm_clear_last_id();

		// Lock this migration action so it can't be run again
		woo_sm_lock_migrate_action( 'coupon_usage_limit' );

		$message = sprintf( '%d of %d Coupons have had their Coupon Usage Limit updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Usage Limit\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(5) Migrate Individual Use for coupons
function woo_sm_migrate_coupons_change_coupon_individual_use() {

	global $wpdb;

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Expiry Date
			$meta_value = get_post_meta( $coupon, 'individual_use', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {

				// Transform individual use value before storing: 1 -> yes; 0 -> no.
				$output = ( $meta_value == 1 ) ? "yes" : "no";

				update_post_meta( $coupon, 'individual_use', $output );
/*
				$wpdb->update( $wpdb->prefix . 'postmeta', array(
					'meta_value' => $output
				), array(
					'post_id' => $coupon,
					'meta_key' => 'individual_use'
				) );
*/
				$updated++;
			}
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Individual Use updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Individual Use\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(6) Migrate Free Shipping values for Coupons
function woo_sm_migrate_coupons_change_coupon_free_shipping() {

	global $wpdb;

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Free Shipping
			$meta_value = get_post_meta( $coupon, 'free_shipping', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {

				// Transform free shipping value before storing: 1 -> yes; 0 -> no.
				$output = ( $meta_value == 1 ) ? "yes" : "no";

/*
				$wpdb->update( $wpdb->prefix . 'postmeta', array(
					'meta_value' => $output
				), array(
					'post_id' => $coupon,
					'meta_key' => 'free_shipping'
				) );
*/
				update_post_meta( $coupon, 'free_shipping', $coupon );
				$updated++;
			}
		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Free Shipping updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Free Shipping\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}	

}

//(7) Migrate Minimal Amount values for Coupons
function woo_sm_migrate_coupons_change_coupon_minimum_amount() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Minimum Amount
			$meta_value = get_post_meta( $coupon, 'order_total_min', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'minimum_amount', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'order_total_min' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Minimum Amount updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Minimum Amount\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(8) Migrate Maximum Amount values for Coupons
function woo_sm_migrate_coupons_change_coupon_maximum_amount() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Maximum Amount
			$meta_value = get_post_meta( $coupon, 'order_total_max', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'maximum_amount', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'order_total_max' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Maximum Amount updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Maximum Amount\'s were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}
	
}

//(9) Migrate Exclude Categories values for Coupons
function woo_sm_migrate_coupons_change_coupon_exclude_categories() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Exclude Categories
			$meta_value = get_post_meta( $coupon, 'exclude_categories', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'exclude_product_categories', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'exclude_categories' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Exclude Categories updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Exclude Categories were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(10) Migrate Include Categories values for Coupons
function woo_sm_migrate_coupons_change_coupon_include_categories() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Include Categories
			$meta_value = get_post_meta( $coupon, 'include_categories', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'product_categories', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'include_categories' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Include Categories updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Include Categories were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(11) Migrate Exclude Products values for Coupons
function woo_sm_migrate_coupons_change_coupon_exclude_products() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Exclude Product
			$meta_value = get_post_meta( $coupon, 'exclude_products', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				$delimiter = '"';
				$woo_entry = '';
				$splits = explode( $delimiter, implode ( ',', $meta_value ) ); 
				$i = 2;
				foreach( $splits as $split ) {
					if( ( $woo_entry == '' ) && ( $i % 2 == 0 ) ) {
						$woo_entry = $split;
						$i++;
						continue;
					} else if( ( $woo_entry != '' ) && ( $i % 2 == 0 ) ) {
						$woo_entry = $woo_entry . "," . $split;
					}
					$i++;
				}
				add_post_meta( $coupon, 'exclude_product_ids', $woo_entry, true );
				// update_post_meta( $coupon, 'exclude_product_ids', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'exclude_products' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Exclude Products updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon Exclude Products were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(12) Migrate Include Products values for Coupons
function woo_sm_migrate_coupons_change_coupon_include_products() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Include Product
			$meta_value = get_post_meta( $coupon, 'include_products', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				$delimiter = '"';
				$woo_entry = '';
				$splits = explode( $delimiter, implode ( ',', $meta_value ) ); 
				$i = 2;
				foreach( $splits as $split ) {
					if( ( $woo_entry == '' ) && ( $i % 2 == 0 ) ) {
						$woo_entry = $split;
						$i++;
						continue;
					} else if (  ($woo_entry != '') && ($i % 2 == 0) ) {
						$woo_entry = $woo_entry . "," . $split;
					}
					$i++;
				}
				add_post_meta( $coupon, 'product_ids', $woo_entry, true );
				$updated++;
			}
			delete_post_meta( $coupon, 'include_products' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Include Products updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
		
	} else {
		$message = 'No Coupon Include Products were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}
	
}

//(13) Migrate Usage Count for Coupons
function woo_sm_migrate_coupons_change_coupon_usage_count() {

	$post_type = 'shop_coupon';

	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Usage Limit
			$meta_value = get_post_meta( $coupon, 'usage', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, 'usage_count', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'usage' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Usage Count updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon\'s Usage Count were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}

//(14) Migrate Usage Members for Coupons
function woo_sm_migrate_coupons_change_coupon_usage_members() {

	$post_type = 'shop_coupon';
	
	$coupons = array();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '-1',
		'post_status' => 'any',
		'fields' => 'ids'
	);
	$coupons_query = new WP_Query( $args );
	if( $coupons_query->posts ) {
		$coupons = $coupons_query->posts;
	}
	$updated = 0;
	if( !empty( $coupons ) ) {
		foreach( $coupons as $coupon ) {

			// Save the current ID to the db
			woo_sm_record_current_id( $coupon );

			// Coupon Usage Limit
			$meta_value = get_post_meta( $coupon, 'used_by', true );

			if( empty( $meta_value ) )
				$meta_value = '';

			if( !empty( $meta_value ) ) {
				update_post_meta( $coupon, '_used_by', $meta_value );
				$updated++;
			}
			delete_post_meta( $coupon, 'used_by' );

		}
		// Forget the last action
		woo_sm_clear_last_id();

		$message = sprintf( '%d of %d Coupons have had their Coupon Usage Members updated', $updated, count( $coupons ) );
		woo_sm_admin_notice_html( $message );
	} else {
		$message = 'No Coupon\'s Usage Member were updated';
		woo_sm_admin_notice_html( $message, 'error' );
	}

}
?>