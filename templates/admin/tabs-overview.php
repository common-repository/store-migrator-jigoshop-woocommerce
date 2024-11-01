<div class="overview-left">

	<ul class="subsubsub">
		<li><a href="<?php echo add_query_arg( 'tab', 'backup' ); ?>"><?php _e( 'Backup', 'woo_sm' ); ?></a> |</li>
		<li><a href="<?php echo add_query_arg( 'tab', 'migrate' ); ?>"><?php _e( 'Migrate', 'woo_sm' ); ?></a></li>
		<!--<li><a href="<?php //echo add_query_arg( 'tab', 'settings' ); ?>"><?php //_e( 'Settings', 'woo_sm' ); ?></a> |</li>-->
		<!--<li><a href="<?php //echo add_query_arg( 'tab', 'upcoming' ); ?>"><?php //_e( 'Upcoming', 'woo_sm' ); ?></a></li>-->
	</ul>
	<!-- .subsubsub -->
	<br class="clear" />

	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">
			<h3><?php _e( 'Welcome to the last time you use Jigoshop!', 'woo_sm' ); ?></h3>
			<p class="about-description"><?php _e( 'We\'ve assembled some steps to get you moving', 'woo_sm' ); ?>:</p>
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<h4><?php _e( '1. Backup', 'woo_sm' ); ?></h4>
					<p><?php _e( 'Please backup your Jigoshop store database so you can roll back changes safely in case something goes wrong along the way.', 'woo_sm' ); ?></p>
					<a class="button button-primary button-hero" href="<?php echo add_query_arg( 'tab', 'backup' ); ?>"><?php _e( 'Backup Your Site', 'woo_sm' ); ?></a>
					<p class="hide-if-no-customize"><?php _e( 'If you\'ve already backed up everything jump over to the Migrate panel.', 'woo_sm' ); ?></a></p>
				</div>
				<div class="welcome-panel-column">
					<h4><?php _e( '2. Migration Time', 'woo_sm' ); ?></h4>
					<p><?php _e( 'Migrate your datas safely by one click and enjoy it. Ensure the complete success of the migration in the review.', 'woo_sm' ); ?></p>
					<a class="button button-primary button-hero" href="<?php echo add_query_arg( 'tab', 'migrate' ); ?>"><?php _e( 'Jump To Migrate', 'woo_sm' ); ?></a>
					<ul>
						<!--<li><a href="<?php //echo add_query_arg( 'tab', 'migrate' ); ?>" class="welcome-icon welcome-add-page"><?php //_e( 'Migrate Products', 'woo_sm' ); ?></a></li>
						<li><a href="<?php //echo add_query_arg( 'tab', 'migrate' ); ?>#migrate-orders" class="welcome-icon welcome-add-page"><?php //_e( 'Migrate Orders', 'woo_sm' ); ?></a></li>
						<li><a href="<?php //echo add_query_arg( 'tab', 'migrate' ); ?>#migrate-coupons" class="welcome-icon welcome-add-page"><?php //_e( 'Migrate Coupons', 'woo_sm' ); ?></a></li>
						<li><a href="<?php //echo add_query_arg( 'tab', 'migrate' ); ?>#migrate-settings" class="welcome-icon welcome-add-page"><?php //_e( 'Migrate Jigoshop Settings', 'woo_sm' ); ?></a></li>-->
					</ul>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
					<h4><?php _e( '3. Review', 'woo_sm' ); ?></h4>
					<ul>
						<li><a href="<?php echo add_query_arg( 'post_type', 'shop_order', 'edit.php' ); ?>" class="welcome-icon welcome-learn-more"><?php _e( 'Check Orders', 'woo_sm' ); ?></a></li>
						<li><a href="<?php echo add_query_arg( 'post_type', 'shop_coupon', 'edit.php' ); ?>" class="welcome-icon welcome-learn-more"><?php _e( 'Check Coupons', 'woo_sm' ); ?></a></li>
						<li><a href="<?php echo add_query_arg( 'post_type', 'product', 'edit.php' ); ?>" class="welcome-icon welcome-learn-more"><?php _e( 'Check Products', 'woo_sm' ); ?></a></li>
						<li><div class="welcome-icon welcome-widgets-menus"><?php _e( 'Check', 'woo_sm' ); ?> <a href="<?php echo add_query_arg( 'page', 'wc-settings', 'admin.php' ); ?>"><?php _e( 'WooCommerce Settings', 'woo_sm' ); ?></a></div></li>
					</ul>
				</div>
			</div>
			
			<div class="home"></div>
		</div>
	</div>

	<form id="skip_overview_form" method="post">
		<label><input type="checkbox" id="skip_overview" name="skip_overview"<?php checked( $skip_overview ); ?> /> <?php _e( 'Jump to Migrate screen in the future', 'woo_sm' ); ?></label>
		<input type="hidden" name="action" value="skip_overview" />
	</form>

</div>
<!-- .overview-left -->