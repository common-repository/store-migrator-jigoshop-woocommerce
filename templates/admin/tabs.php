<div id="content">

	<h2 class="nav-tab-wrapper">
		<a data-tab-id="overview" class="nav-tab<?php woo_sm_admin_active_tab( 'overview' ); ?>" href="<?php echo add_query_arg( array( 'page' => 'woo_sm', 'tab' => 'overview' ), 'admin.php' ); ?>"><?php _e( 'Overview', 'woo_sm' ); ?></a>
		<a data-tab-id="backup" class="nav-tab<?php woo_sm_admin_active_tab( 'backup' ); ?>" href="<?php echo add_query_arg( array( 'page' => 'woo_sm', 'tab' => 'backup' ), 'admin.php' ); ?>"><?php _e( 'Backup', 'woo_sm' ); ?></a>
		<a data-tab-id="migrate" class="nav-tab<?php woo_sm_admin_active_tab( 'migrate' ); ?>" href="<?php echo add_query_arg( array( 'page' => 'woo_sm', 'tab' => 'migrate' ), 'admin.php' ); ?>"><?php _e( 'Migrate', 'woo_sm' ); ?></a>
		<!--<a data-tab-id="settings" class="nav-tab<?php //woo_sm_admin_active_tab( 'settings' ); ?>" href="<?php //echo add_query_arg( array( 'page' => 'woo_sm', 'tab' => 'settings' ), 'admin.php' ); ?>"><?php //_e( 'Settings', 'woo_sm' ); ?></a>-->
		<a data-tab-id="Plugins" class="nav-tab<?php woo_sm_admin_active_tab( 'plugins' ); ?>" href="<?php echo add_query_arg( array( 'page' => 'woo_sm', 'tab' => 'plugins' ), 'admin.php' ); ?>"><?php _e( 'Plugins', 'woo_sm' ); ?></a>
	</h2>
	<?php woo_sm_tab_template( $tab ); ?>

</div>
<!-- #content -->