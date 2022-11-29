<?php 

// Detect when order created
function action_woocommerce_thankyou( $order_id ) {
	global $wpdb;

	$invoicesApi = new InvoicesApi();
	$invoicesDatabase = new InvoicesDatabase();


	$order = $invoicesDatabase->getInvoice("{$order_id}");

	// ¡ATENCIÓN! Antes de enviar a la api y guardarlos, debe verificar si ya ha sido sincronizado.
	if(sizeof($order) > 0) {
		if( $order[0]['OrderSync'] == '1' ) {
			// var_dump("syncronized");
			return;
		}
		if( $order[0]['OrderId'] == $order_id ){
			// var_dump("duplicated");
			return;
		}
	}

	// Getting an instance of the order object
	$order          = wc_get_order($order_id);
	$order_data     = $order->get_data();

	$orderId        = $order->get_id();
	$orderJson  	= $invoicesApi->getInvoiceJson($order_id);
	$customerIdType = get_post_meta($order_id, '_billing_identifier_type', true);
	$customerId     = get_post_meta($order_id, '_billing_identifier', true);
	$orderDate      = $order_data['date_created']->getTimestamp();
	$orderState     = ( $order->has_status('completed') ) ? 1 : 0 ;
	$orderSync      = 0;
	$documentType   = '3'; if( $customerIdType=='RUC' ){ $documentType = '1'; };
	$documentState  = 0;


	// $table = "{$wpdb->prefix}sync_invoices";
	$info = [
		'OrderId'         => $orderId,
		'OrderJson'  	  => $orderJson, // Generated JSON (temporal)
		'CustomerIdType'  => $customerIdType, // (SUNAT TABLA_2) DNI:1, RUC:6, CE: 4
		'CustomerId'      => $customerId, // ( DNI || RUC || CE ) NUMBER
		'OrderDate'       => $orderDate, // Timestamp
		'OrderState'      => $orderState, // Complete 1, pending 0
		'OrderSync'       => $orderSync, // Successfully sent to starsoft 0: false, 1: true
		'DocumentType'    => $documentType, // (SUNAT TABLA_10) BOLETA: 3, FACTURA: 1
		'DocumentState'   => $documentState // Deny: 0, Accepted: 1, nulled: -1
	];

	$invoicesDatabase->setInvoice( $info, $order_id );


	$responseInvoiceSetted = $invoicesApi->setInvoice( $order_id );

	// var_dump($responseInvoiceSetted);

	if($responseInvoiceSetted) {
		$info = [
			'OrderSync'   => 1
		];
		$invoicesDatabase->updateInvoice( $info, $order_id );
	}

	// var_dump($invoicesDatabase);

	return true;
}
add_action('woocommerce_thankyou', 'action_woocommerce_thankyou', 10, 1);



// Save the custom billing fields (once order is placed)
function save_custom_billing_fields( $order, $data ) {
	if ( isset( $_POST['billing_identifier'] ) && ! empty( $_POST['billing_identifier'] ) ) {
		$order->update_meta_data('_billing_identifier', sanitize_text_field( $_POST['billing_identifier'] ) );
		update_user_meta( $order->get_customer_id(), 'billing_identifier', sanitize_text_field( $_POST['billing_identifier'] ) );
	}
	if ( isset( $_POST['billing_identifier_type'] ) && ! empty( $_POST['billing_identifier_type'] ) ) {
		$order->update_meta_data('_billing_identifier_type', sanitize_text_field( $_POST['billing_identifier_type'] ) );
		update_user_meta( $order->get_customer_id(), 'billing_identifier_type', sanitize_text_field( $_POST['billing_identifier_type'] ) );
	}
}
add_action( 'woocommerce_checkout_create_order', 'save_custom_billing_fields', 20, 2 );
