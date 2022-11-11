<?php 


// Detect when order change to completed
function action_woocommerce_order_status_completed( $order_id ) {
	$info = [
		'Paid' => 1,
		'Cancelled' => 0
	];

	$invoicesDatabase = new InvoicesDatabase;
	$result = $invoicesDatabase->updateInvoice( $info, $order_id );

	return $result;
}
add_action( 'woocommerce_order_status_completed', 'action_woocommerce_order_status_completed', 10, 1 );

/*



function action_woocommerce_update_product( $product_id ) {
	global $wpdb;

	$info = [
		'SettingId'        => '3',
		'SettingProperty'  => 'product_id',
		'SettingValue'     => $product_id
	];
	$where = [
		'SettingId'  => '3'
	];
	$settings_table = "{$wpdb->prefix}sync_settings";

	// update
	$result = $wpdb->update( $settings_table, $info, $where );
	// or insert
	if ($result === FALSE || $result < 1) {
		$wpdb->insert($settings_table, $info);
	}
}
add_action( 'woocommerce_update_product', 'action_woocommerce_update_product', 10, 4 );


// Detect when order change to refunded
function action_woocommerce_order_refunded( $order_id, $refund_id ) {
	$info = [
		'Cancelled' => 1
	];

	$invoicesDatabase = new InvoicesDatabase;
	$result = $invoicesDatabase->updateInvoice( $info, $order_id );

	return $result;
}
add_action( 'woocommerce_order_refunded', 'action_woocommerce_order_refunded', 10, 2 );

*/



// Detect when order change to processing
function action_woocommerce_order_processing( $order_id ) {
	global $wpdb;

	$invoicesApi = new InvoicesApi();
	$responseInvoiceSetted = $invoicesApi->setInvoice( $order_id );


	// script de prueba solamente
	/*
	$info = [
		'SettingId'        => '3',
		'SettingProperty'  => 'order_id',
		'SettingValue'     => $responseInvoiceSetted
	];
	$where = [
		'SettingId'  => '3'
	];
	$settings_table = "{$wpdb->prefix}sync_settings";


	// update
	$result = $wpdb->update( $settings_table, $info, $where );
	// or insert
	if ($result === FALSE || $result < 1) {
		$wpdb->insert($settings_table, $info);
	}
	*/


	$info = [
		'Paid' => 0,
		'Cancelled' => 0
	];

	$invoicesDatabase = new InvoicesDatabase;
	$result = $invoicesDatabase->updateInvoice( $info, $order_id );

	return $result;
}
add_action( 'woocommerce_order_status_processing', 'action_woocommerce_order_processing' );


