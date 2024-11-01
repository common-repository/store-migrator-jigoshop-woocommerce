<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	include_once( WOO_SM_PATH . 'includes/admin.php' );

	function woo_sm_migrate() {

	}

	// Locks a migration option so that it cannot be re-run (e.g. coupon usage limit)
	function woo_sm_lock_migrate_action( $action = '' ) {

		// Check an action has been provided
		if( !empty( $action ) ) {
			woo_sm_update_option( sprintf( 'lock_action-%s', $action ), 1 );
		}

	}

	function woo_sm_unlock_migrate_action( $action = '' ) {

		// Check an action has been provided
		if( !empty( $action ) ) {
			$separator = '_';
			delete_option( WOO_SM_PREFIX . $separator . sprintf( 'lock_action-%s', $action ) );
		}

	}

	function woo_sm_record_current_actions( $action = '' ) {

		// Check an action has been provided
		if( !empty( $action ) ) {
			$separator = '_';
			update_option( WOO_SM_PREFIX . $separator . 'current_actions', $action );
		}

	}

	function woo_sm_record_current_action( $action = '' ) {

		// Check an action has been provided
		if( !empty( $action ) ) {
			$separator = '_';
			update_option( WOO_SM_PREFIX . $separator . 'current_action', $action );
		}

	}

	function woo_sm_clear_last_actions() {

		// Clearing the current action option indicates that the migration process has completed; for that action at least
		$separator = '_';
		delete_option( WOO_SM_PREFIX . $separator . 'current_actions' );

	}

	function woo_sm_clear_last_action() {

		// Clearing the current action option indicates that the migration process has completed
		$separator = '_';
		delete_option( WOO_SM_PREFIX . $separator . 'current_action' );

	}

	function woo_sm_record_current_id( $id = 0 ) {

		// Check an ID has been provided
		if( !empty( $id ) ) {
			$separator = '_';
			update_option( WOO_SM_PREFIX . $separator . 'current_id', $id );
		}

	}

	function woo_sm_clear_last_id() {

		// Clearing the current ID option indicates that the migration process has completed for that action; for that action at least
		$separator = '_';
		delete_option( WOO_SM_PREFIX . $separator . 'current_id' );

	}

	/* End of: WordPress Administration */

}

function woo_sm_bump_request_timeout() {

	return 60;

}

function woo_sm_get_option( $option = null, $default = false, $allow_empty = false ) {

	$output = '';
	if( isset( $option ) ) {
		$separator = '_';
		$output = get_option( WOO_SM_PREFIX . $separator . $option, $default );
		if( $allow_empty == false && $output != 0 && ( $output == false || $output == '' ) )
			$output = $default;
	}
	return $output;

}

function woo_sm_update_option( $option = null, $value = null ) {

	$output = false;
	if( isset( $option ) && isset( $value ) ) {
		$separator = '_';
		$output = update_option( WOO_SM_PREFIX . $separator . $option, $value );
	}
	return $output;

}
?>
