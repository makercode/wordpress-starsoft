<?php 

// Detect when order created
function action_woocommerce_thankyou( $orderId ) {
	$validatedGuard = new ValidatedGuard;
	$choosedGuard = new ChoosedGuard;
	if( $validatedGuard->isValidated()=="1" && $choosedGuard->isChoosed()=="1" ) {
		global $wpdb;

		$settingsGlobal = new SettingsGlobal;
		$documentsApi = $settingsGlobal->getDocumentsApiInstance();
		$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();


		$order = $documentsDatabase->getDocument("{$orderId}");

		// ¡ATENCIÓN! Antes de enviar a la api y guardarlos, debe verificar si ya ha sido sincronizado.
		if(sizeof($order) > 0) {
			if( $order[0]['OrderSync'] == '1' ) {
				// var_dump("syncronized");
				return;
			}
			if( $order[0]['OrderId'] == $orderId ) {
				// var_dump("duplicated");
				return;
			}
		}

		// Getting an instance of the order object
		$order          = wc_get_order($orderId);
		$orderData      = $order->get_data();

		$orderId        = $order->get_id();
		$orderJson  	= $documentsApi->getDocumentJson($orderId);
		$customerIdType = get_post_meta($orderId, '_billing_identifier_type', true);
		$customerId     = get_post_meta($orderId, '_billing_identifier', true);
		$orderDate      = $orderData['date_created']->getTimestamp();
		$orderState     = ( $order->has_status('completed') ) ? 1 : 0 ;
		$orderSync      = 0;
		$receiptType    = '3'; if( $customerIdType=='RUC' ){ $receiptType = '1'; };
		$receiptState   = 0;


		// $table = "{$wpdb->prefix}sync_invoices";
		$info = [
			'OrderId'         => $orderId,
			'OrderJson'  	  => $orderJson, // Generated JSON (temporal)
			'CustomerIdType'  => $customerIdType, // (SUNAT TABLA_2) DNI:1, RUC:6, CE: 4
			'CustomerId'      => $customerId, // ( DNI || RUC || CE ) NUMBER
			'OrderDate'       => $orderDate, // Timestamp
			'OrderState'      => $orderState, // Complete 1, pending 0
			'OrderSync'       => $orderSync, // Successfully sent to starsoft 0: false, 1: true
			'ReceiptType'     => $receiptType, // (SUNAT TABLA_10) BOLETA: 3, FACTURA: 1
			'ReceiptState'    => $receiptState // Deny: 0, Accepted: 1, nulled: -1
		];

		$documentsDatabase->setDocument( $info, $orderId );


		$responseInvoiceSetted = $documentsApi->setDocument( $orderId );

		// var_dump($responseInvoiceSetted);

		if($responseInvoiceSetted) {
			$info = [
				'OrderSync'   => 1
			];
			$documentsDatabase->updateDocument( $info, $orderId );
		}

		// var_dump($documentsDatabase);

		return true;
	}
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
