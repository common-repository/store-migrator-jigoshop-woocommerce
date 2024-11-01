<!--<ul class="subsubsub">
	<li><a href="#migrate-products"><?php //_e( 'Migrate Products', 'woo_sm' ); ?></a> |</li>
	<li><a href="#migrate-orders"><?php //_e( 'Migrate Orders', 'woo_sm' ); ?></a> |</li>
	<li><a href="#migrate-coupons"><?php //_e( 'Migrate Coupons', 'woo_sm' ); ?></a></li>
</ul>-->
<!-- .subsubsub -->
<br class="clear" />

<form method="post" action="<?php the_permalink(); ?>">

	<h3><?php _e( 'Migrate Products', 'woo_sm' ); ?></h3>
	<!--<p><?php //_e( 'To migrate Products select the actions you want to run:', 'woo_sm' ); ?></p>
	<p><a id="select-products" class="checkall" href="javascript:void(0)"><?php //_e( 'Select All', 'woo_sm' ); ?></a> | <a id="unselect-products" class="uncheckall" href="javascript:void(0)"><?php //_e( 'Un-select all', 'woo_sm' ); ?></a></p>-->
	<ul class="ul-disc ul-product">
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_sku"<?php if( empty( $product_sku_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product SKU', 'woo_sm' ); ?></label> <?php if( !empty( $product_sku_exists ) ) { ?><span class="count"><?php echo $product_sku_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_price"<?php if( empty( $product_price_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Price', 'woo_sm' ); ?></label> <?php if( !empty( $product_price_exists ) ) { ?><span class="count"><?php echo $product_price_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_sales_price"<?php if( empty( $product_sale_price_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Sales Price', 'woo_sm' ); ?></label> <?php if( !empty( $product_sale_price_exists ) ) { ?><span class="count"><?php echo $product_sale_price_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_sales_price_period"<?php if( empty( $product_sale_price_period_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Sales Price Period', 'woo_sm' ); ?></label> <?php if( !empty( $product_sale_price_period_exists ) ) { ?><span class="count"><?php echo $product_sale_price_period_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_featured"<?php if( empty( $product_featured_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Featured', 'woo_sm' ); ?></label> <?php if( !empty( $product_featured_exists ) ) { ?><span class="count"><?php echo $product_featured_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_stock"<?php if( empty( $product_stock_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Stock', 'woo_sm' ); ?></label> <?php if( !empty( $product_stock_exists ) ) { ?><span class="count"><?php echo $product_stock_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_weight"<?php if( empty( $product_weight_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Weight', 'woo_sm' ); ?></label> <?php if( !empty( $product_weight_exists ) ) { ?><span class="count"><?php echo $product_weight_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_dimensions"<?php if( empty( $product_dimension_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Dimensions', 'woo_sm' ); ?></label> <?php if( !empty( $product_dimension_exists ) ) { ?><span class="count"><?php echo $product_dimension_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_url"<?php if( empty( $product_external_url_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product URL', 'woo_sm' ); ?></label> <?php if( !empty( $product_external_url_exists ) ) { ?><span class="count"><?php echo $product_external_url_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_visibility"<?php if( empty( $product_visibility_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Visibility', 'woo_sm' ); ?></label> <?php if( !empty( $product_visibility_exists ) ) { ?><span class="count"><?php echo $product_visibility_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li><!-- NOT NECESSARY-->
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_customizable"<?php if( empty( $product_customizable_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Customizable', 'woo_sm' ); ?></label> <?php if( !empty( $product_customizable_exists ) ) { ?><span class="count"><?php echo $product_customizable_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li><!-- NOT NECESSARY-->
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_tax_status"<?php if( empty( $product_tax_status_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Tax Status', 'woo_sm' ); ?></label> <?php if( !empty( $product_tax_status_exists ) ) { ?><span class="count"><?php echo $product_tax_status_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li><!-- NOT NECESSARY-->
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_total_sales"<?php if( empty( $product_total_sales_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Total Sales', 'woo_sm' ); ?></label> <?php if( !empty( $product_total_sales_exists ) ) { ?><span class="count"><?php echo $product_total_sales_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li><!-- NOT NECESSARY-->
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_purchase_note"<?php if( empty( $product_purchase_note_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Purchase Note', 'woo_sm' ); ?></label> <?php if( !empty( $product_purchase_note_exists ) ) { ?><span class="count"><?php echo $product_purchase_note_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li><!-- NOT NECESSARY-->
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_virtual"<?php if( empty( $product_virtual_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Virtual', 'woo_sm' ); ?></label> <?php if( !empty( $product_virtual_exists ) ) { ?><span class="count"><?php echo $product_virtual_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_attributes"<?php if( empty( $product_attribute_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Attributes', 'woo_sm' ); ?></label> <?php if( !empty( $product_attribute_exists ) ) { ?><span class="count"><?php echo $product_attribute_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_variations"<?php if( empty( $product_variations_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Variations', 'woo_sm' ); ?></label> <?php if( !empty( $product_variations_exists ) ) { ?><span class="count"><?php echo $product_variations_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_downloadable"<?php if( empty( $product_downloadable_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Product Downloadable', 'woo_sm' ); ?></label> <?php if( !empty( $product_downloadable_exists ) ) { ?><span class="count"><?php echo $product_downloadable_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<!--<li><label><input type="hidden" name="woo_sm_migrate[]" value="product_categories"<?php //if( empty( $product_categories_exists ) ) { ?> disabled="disabled"<?php //} ?> /><?php //_e( 'Product Categories', 'woo_sm' ); ?></label> <?php //if( !empty( $product_categories_exists ) ) { ?><!--<span class="count"><?php //echo $product_categories_exists; ?></span><?php //} ?></li>-->
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_products[]" value="product_tax_classes" disabled="disabled" /><?php _e( 'Change Product Tax Classes', 'woo_sm' ); ?></label></li> --><!-- NOT A PART OF THE FIRST RELEASE -->
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_products[]" value="product_group" disabled="disabled" /><?php _e( 'Change Product Groups', 'woo_sm' ); ?></label></li> --><!-- NOT NECESSARY-->
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_products[]" value="product_cust_length" disabled="disabled"  /><?php _e( 'Change Product Customized Length', 'woo_sm' ); ?></label></li> --><!-- NOT NECESSARY-->
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_products[]" value="product_type" disabled="disabled" /><?php _e( 'Change Product Type', 'woo_sm' ); ?></label></li> --><!-- NOT NECESSARY-->
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_products[]" value="product_tags" disabled="disabled" /><?php _e( 'Change Product Tags', 'woo_sm' ); ?></label></li> --><!-- NOT NECESSARY-->
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_products[]" value="product_sort_order" disabled="disabled" /><?php _e( 'Change Product Sort Order', 'woo_sm' ); ?></label></li> --><!-- NOT NECESSARY-->
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="jigoshop_metas_unmapped" /><?php _e( 'Delete unmapped Jigoshop Product Meta', 'woo_sm' ); ?></label></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="jigoshop_metas_mapped" /><?php _e( 'Delete mapped Jigoshop Product Meta', 'woo_sm' ); ?></label></li>
	</ul>
	
	<?php 
		if( empty( $product_sku_exists ) && empty( $product_price_exists ) && empty( $product_sale_price_exists ) && empty( $product_sale_price_period_exists ) && empty( $product_featured_exists ) && empty( $product_stock_exists ) && empty( $product_weight_exists ) && empty( $product_dimension_exists ) && empty( $product_external_url_exists ) && empty( $product_visibility_exists ) && empty( $product_customizable_exists ) && empty( $product_tax_status_exists ) && empty( $product_total_sales_exists ) && empty( $product_purchase_note_exists ) && empty( $product_virtual_exists ) && empty( $product_attribute_exists ) && empty( $product_variations_exists ) && empty( $product_downloadable_exists ) ) { 
			$dis_prod = "disabled=\"disabled\"";
		}else $dis_prod = "";
	?>
	<!--<p class="submit">
		<input type="submit" value="<?php //_e( 'Migrate Products', 'woo_sm' ); ?>" class="button-primary" <?php //echo $dis_prod; ?> />
	</p>-->
		

<hr />


	<h3 id="migrate-orders"><?php _e( 'Migrate Orders', 'woo_sm' ); ?></h3>
	<!--<p><?php //_e( 'To migrate Orders select the actions you want to run, actions that are disabled do not need to be applied:', 'woo_sm' ); ?></p>
	<p><a id="select-orders" class="checkall" href="javascript:void(0)"><?php //_e( 'Select All', 'woo_sm' ); ?></a> | <a id="unselect-orders" class="uncheckall" href="javascript:void(0)"><?php //_e( 'Un-select all', 'woo_sm' ); ?></a></p>-->
	<ul class="ul-disc ul-order">
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_status"<?php if( empty( $order_status_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Status', 'woo_sm' ); ?></label> <?php if( !empty( $order_status_exists ) ) { ?><span class="count"><?php echo $order_status_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_key"<?php if( empty( $order_key_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Key', 'woo_sm' ); ?></label> <?php if( !empty( $order_key_exists ) ) { ?><span class="count"><?php echo $order_key_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_customer"<?php if( empty( $order_customer_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Customer', 'woo_sm' ); ?></label> <?php if( !empty( $order_customer_exists ) ) { ?><span class="count"><?php echo $order_customer_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_currency"<?php if( !empty( $order_currency_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Currency', 'woo_sm' ); ?></label> <?php if( !empty( $order_currency_exists ) ) { ?><span class="count"><?php echo $order_currency_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_shipping_method"<?php if( empty( $order_shipping_method_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Shipping Method', 'woo_sm' ); ?></label> <?php if( !empty( $order_shipping_method_exists ) ) { ?><span class="count"><?php echo $order_shipping_method_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_discount"<?php if( !empty( $order_discount_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Discount', 'woo_sm' ); ?></label> <?php if( !empty( $order_discount_exists ) ) { ?><span class="count"><?php echo $order_discount_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_meta"<?php if( empty( $order_post_meta_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Post meta', 'woo_sm' ); ?></label> <?php if( !empty( $order_post_meta_exists ) ) { ?><span class="count"><?php echo $order_post_meta_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_copy_billing_shipping_address"<?php if( empty( $order_copy_billing_shipping_address_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Billing Address to Shipping Address', 'woo_sm' ); ?></label> <?php if( !empty( $order_copy_billing_shipping_address_exists ) ) { ?><span class="count"><?php echo $order_copy_billing_shipping_address_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_items"<?php if( empty( $order_item_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Order Items', 'woo_sm' ); ?></label> <?php if( !empty( $order_item_exists ) ) { ?><span class="count"><?php echo $order_item_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="order_file_downloads"<?php if( empty( $order_download_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Assign file downloads to Orders', 'woo_sm' ); ?></label> <?php if( !empty( $order_download_exists ) ) { ?><span class="count"><?php echo $order_download_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<!-- <li><label><input type="checkbox" name="woo_sm_migrate_orders[]" value="order_user_downloads" disabled="disabled" /><?php _e( 'Re-link file downloads to Users', 'woo_sm' ); ?></label></li> -->
	</ul>
	
	<?php 
		if( empty( $order_status_exists ) && empty( $order_key_exists ) && empty( $order_customer_exists ) && empty( $order_currency_exists ) && empty( $order_shipping_method_exists ) && empty( $order_discount_exists ) && empty( $order_post_meta_exists ) && empty( $order_copy_billing_shipping_address_exists ) && empty( $order_item_exists ) && empty( $order_download_exists ) ) { 
			$dis_ord = "disabled=\"disabled\"";
		}else $dis_ord = "";
	?>
	
	<!--<p class="submit">
		<input type="submit" value="<?php //_e( 'Migrate Orders', 'woo_sm' ); ?>" class="button-primary" <?php //echo $dis_ord; ?> />
	</p>-->
	

<hr />


	<h3 id="migrate-coupons"><?php _e( 'Migrate Coupons', 'woo_sm' ); ?></h3>
	<!--<p><?php //_e( 'To migrate Coupons select the actions you want to run:', 'woo_sm' ); ?></p>
	<p><a id="select-coupons" class="checkall" href="javascript:void(0)"><?php //_e( 'Select All', 'woo_sm' ); ?></a> | <a id="unselect-coupons" class="uncheckall" href="javascript:void(0)"><?php //_e( 'Un-select all', 'woo_sm' ); ?></a></p>-->
	<ul class="ul-disc ul-coupon">
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_type"<?php if( empty( $coupon_type_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Type', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_type_exists ) ) { ?><span class="count"><?php echo $coupon_type_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_amount"<?php if( empty( $coupon_amount_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Amount', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_amount_exists ) ) { ?><span class="count"><?php echo $coupon_amount_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_expiry_date"<?php if( empty( $coupon_expiry_date_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Expiry Date', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_expiry_date_exists ) ) { ?><span class="count"><?php echo $coupon_expiry_date_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_usage_limit"<?php if( empty( $coupon_usage_limit_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Usage Limit', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_usage_limit_exists ) ) { ?><span class="count"><?php echo $coupon_usage_limit_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>     	
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_individual_use"<?php if( empty( $coupon_individual_use_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Individual Use', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_individual_use_exists ) ) { ?><span class="count"><?php echo $coupon_individual_use_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_free_shipping"<?php if( empty( $coupon_free_shipping_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Free Shipping', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_free_shipping_exists ) ) { ?><span class="count"><?php echo $coupon_free_shipping_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_minimum_amount"<?php if( empty( $coupon_minimum_amount_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Minimum Amount', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_minimum_amount_exists ) ) { ?><span class="count"><?php echo $coupon_minimum_amount_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_maximum_amount"<?php if( empty( $coupon_maximum_amount_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Maximum Amount', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_maximum_amount_exists ) ) { ?><span class="count"><?php echo $coupon_maximum_amount_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_exclude_categories"<?php if( empty( $coupon_exclude_categories_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Exclude Categories', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_exclude_categories_exists ) ) { ?><span class="count"><?php echo $coupon_exclude_categories_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_include_categories"<?php if( empty( $coupon_include_categories_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Include Categories', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_include_categories_exists ) ) { ?><span class="count"><?php echo $coupon_include_categories_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_exclude_products"<?php if( empty( $coupon_exclude_products_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Exclude Products', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_exclude_products_exists ) ) { ?><span class="count"><?php echo $coupon_exclude_products_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_include_products"<?php if( empty( $coupon_include_products_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Include Products', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_include_products_exists ) ) { ?><span class="count"><?php echo $coupon_include_products_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_usage_count"<?php if( empty( $coupon_usage_count_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Usage Count', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_usage_count_exists ) ) { ?><span class="count"><?php echo $coupon_usage_count_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
		<li><label><input type="hidden" name="woo_sm_migrate[]" value="coupon_usage_members"<?php if( empty( $coupon_used_by_exists ) ) { ?> disabled="disabled"<?php } ?> /><?php _e( 'Coupon Usage Members', 'woo_sm' ); ?></label> <?php if( !empty( $coupon_used_by_exists ) ) { ?><span class="count"><?php echo $coupon_used_by_exists; ?></span><?php }else{ ?><span class="valid"></span><?php } ?></li>
	</ul>
	
	<?php 
		if( empty( $coupon_type_exists ) && empty( $coupon_amount_exists ) && empty( $coupon_expiry_date_exists ) && empty( $coupon_usage_limit_exists ) && empty( $coupon_individual_use_exists ) && empty( $coupon_free_shipping_exists ) && empty( $coupon_minimum_amount_exists ) && empty( $coupon_maximum_amount_exists ) && empty( $coupon_exclude_categories_exists ) && empty( $coupon_include_categories_exists ) && empty( $coupon_exclude_products_exists ) && empty( $coupon_include_products_exists ) && empty( $coupon_usage_count_exists ) && empty( $coupon_used_by_exists ) ) { 
			$dis_coup = "disabled=\"disabled\"";
		}else $dis_coup = "";
	?>
	


    <p class="submit">
		<input type="button" value="<?php _e( 'Go Pro', 'woo_sm' ); ?>" class="button button-primary button-hero" onclick="window.location.href='https://gajelabs.com/product/jigoshop-woocommerce-migrator/'" <?php if( !empty($dis_prod) && !empty($dis_ord) && !empty($dis_coup) ) echo "disabled=\"disabled\""; ?> />
	</p>
	<input type="hidden" name="action" value="migrate" />


</form>