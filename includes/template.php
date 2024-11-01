<?php
// Pre-migration checks

function woo_sm_check_product_sku_exists() {

	global $wpdb;

	$meta_key = 'sku';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_price_exists() {

	global $wpdb;

	$meta_key = 'regular_price';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_sale_price_exists() {

	global $wpdb;

	$meta_key = 'sale_price';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_sale_price_period_exists() {

	global $wpdb;

	$meta_key = 'sale_price_dates_from';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_featured_exists() {

	global $wpdb;

	$post_type = 'product';
	$meta_key = 'featured';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_stock_exists() {

	global $wpdb;

	$meta_key = 'stock';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_weight_exists() {

	global $wpdb;

	$meta_key = 'weight';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_dimension_exists() {

	global $wpdb;

	$meta_key = 'length';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_external_url_exists() {

	global $wpdb;

	$meta_key = 'external_url';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_visibility_exists() {

	global $wpdb;

	$meta_key = 'visibility';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_customizable_exists() {

	global $wpdb;

	$meta_key = 'customizable';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_tax_status_exists() {

	global $wpdb;

	$meta_key = 'tax_status';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_attributes_exists() {

	global $wpdb;

	$meta_key = 'product_attributes';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_variations_exists() {
	
	global $wpdb;

	$meta_key = 'variation_data';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;
}

function woo_sm_check_product_downloadable_exists() {

	global $wpdb;

	$meta_key = 'file_path';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_product_categories_exists() {

	global $wpdb;

	$term = 'simple';
	$count_sql = $wpdb->prepare( "SELECT COUNT(term_id) FROM `" . $wpdb->prefix . "terms` WHERE `slug` = %s OR `slug` = 'external' OR `slug` = 'grouped' OR `slug` = 'variable' OR `slug` = 'downloadable' OR `slug` = 'virtual'", $term );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_order_key_exists() {

	global $wpdb;

	$meta_key = 'order_key';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_order_customer_exists() {

	global $wpdb;

	$meta_key = 'customer_user';
	$count_sql = $wpdb->prepare( "SELECT COUNT(post_id) FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_order_status_exists() {

	global $wpdb;

	$term_taxonomy = 'shop_order_status';
	$count_sql = $wpdb->prepare( "SELECT COUNT(term_id) FROM `" . $wpdb->prefix . "term_taxonomy` WHERE `taxonomy` = %s", $term_taxonomy );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_order_currency_exists() {

	$post_type = 'shop_order';

	$orders = array();
	$meta_key = 'order_currency';
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '1',
		'post_status' => 'any',
		'fields' => 'ids',
		'meta_key' => $meta_key
	);
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	return count( $orders );

}

function woo_sm_check_products_exist() {

	global $wpdb;

	$post_type = 'product';
	$count_sql = $wpdb->prepare( "SELECT COUNT(posts.ID) FROM `" . $wpdb->posts . "` as posts WHERE posts.post_type = %s", $post_type );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_orders_exist() {

	global $wpdb;

	$post_type = 'shop_order';
	$count_sql = $wpdb->prepare( "SELECT COUNT(posts.ID) FROM `" . $wpdb->posts . "` as posts WHERE posts.post_type = %s", $post_type );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_order_discount_exists() {

	global $wpdb;

	$order_item_type = 'coupon';
	$count_sql = $wpdb->prepare( "SELECT COUNT(order_item_id) FROM  `" . $wpdb->prefix . "woocommerce_order_items` WHERE `order_item_type` = %s", $order_item_type );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_order_post_meta_exists() {

	$post_type = 'shop_order';

	$orders = array();
	$meta_key = '_order_total';
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => '1',
		'post_status' => 'any',
		'fields' => 'ids',
		'meta_key' => $meta_key
	);
	$orders_query = new WP_Query( $args );
	if( $orders_query->posts ) {
		$orders = $orders_query->posts;
	}
	return count( $orders );

}

function woo_sm_check_order_download_exists() {

	global $wpdb;

	if( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "jigoshop_downloadable_product_permissions'" ) ) {
		$count_sql = "SELECT COUNT(product_id) FROM `" . $wpdb->prefix . "jigoshop_downloadable_product_permissions`";
		$count = $wpdb->get_var( $count_sql );
		return $count;
	}

}

function woo_sm_check_coupon_type_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'type';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_amount_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'amount';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_expiry_date_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'date_to';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_usage_limit_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'usage_limit';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_free_shipping_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'free_shipping';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` IN ( '1', '0' )", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_individual_use_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'individual_use';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` IN ( '1', '0' )", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_maximum_amount_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'order_total_max';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_minimum_amount_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'order_total_min';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_include_products_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'include_products';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_exclude_products_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'exclude_products';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_exclude_categories_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'exclude_categories';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_include_categories_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'include_categories';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_usage_count_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'usage';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

function woo_sm_check_coupon_used_by_exists() {

	global $wpdb;

	$post_type = 'shop_coupon';
	$meta_key = 'used_by';
	$count_sql = $wpdb->prepare( "SELECT COUNT(postmeta.post_id) FROM `" . $wpdb->posts . "` as posts, `" . $wpdb->postmeta . "` as postmeta WHERE posts.ID = postmeta.post_id AND posts.post_type = '" . $post_type . "' AND postmeta.`meta_key` = %s AND postmeta.`meta_value` != ''", $meta_key );
	$count = $wpdb->get_var( $count_sql );
	return $count;

}

/**
 * Show the variable product price notice
 */
function custom_variation_price( $price, $product ) {

	$price = 'Variable Product Price Not Announced';

	/*if ( !$product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) $price .= '<div class="lbwWooVariaPrice"> <span class="from">' . _x('From', 'min_price', 'woocommerce') . ' </span>';
		$price .= woocommerce_price($product->get_price());

	if ( $product->max_variation_price && $product->max_variation_price !== $product->min_variation_price ) {
		$price .= '<span class="to"> ' . _x('to', 'max_price', 'woocommerce') . ' </span>';
		$price .= woocommerce_price($product->max_variation_price) . '</div>';
	}*/

	return $price;

}
// add_filter( 'woocommerce_variable_price_html', 'custom_variation_price', 10, 2 );

/**
 * Shows the 'Free' product price notice
 */ 

// add_filter( 'woocommerce_variable_free_price_html', 'hide_free_price_notice', 10, 2 );
// add_filter( 'woocommerce_free_price_html', 'hide_free_price_notice', 10, 2 );
// add_filter( 'woocommerce_variation_free_price_html', 'hide_free_price_notice', 10, 2 );

function hide_free_price_notice( $price, $product ) { 

	return 'Free product'; 
	
}

/**
 * Shows the 'NULL' product price notice
 */ 
function notice_null_price_html( $price, $product ) {

	if( $price == '' ) {
		return 'Price Not Announced'; //Product without price
	} else {
		return $price;
	}

}
// add_filter( 'woocommerce_get_price_html', 'notice_null_price_html', 10, 2 );
?>