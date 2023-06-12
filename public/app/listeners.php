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


		$document = $documentsDatabase->getDocument("{$orderId}");


		// ¡ATENCIÓN! Antes de enviar a la api y guardarlos, debe verificar si ya ha sido sincronizado.
		if(sizeof($document) > 0) {
			if( $document[0]['OrderSync'] == '1' ) {
				// var_dump("syncronized");
				return;
			}
			if( $document[0]['OrderId'] == $orderId ) {
				// var_dump("duplicated");
				return;
			}
		}

		// Getting an instance of the order object
		$order          = wc_get_order($orderId);
		$orderData      = $order->get_data();


		if($order->get_currency() !== "PEN") {
			return;
		}
		/*
		*/

		$orderId        = $order->get_id();
		$orderJson  	= $documentsApi->getDocumentJson($orderId);
		$customerIdType = get_post_meta($orderId, '_billing_identifier_type', true);
		$customerId     = get_post_meta($orderId, '_billing_identifier', true);
		$documentType   = get_post_meta($orderId, '_billing_document_type', true);
		$orderDate      = date( 'Y-m-d.', strtotime( $order->get_date_created() ));
		$orderState     = ( $order->has_status('completed') ) ? 1 : 0 ;
		$orderSync      = 0;
		$receiptType    = ''; 
		// var_dump($documentType);
		if( $documentType=='FACTURA' || $documentType=='1' ){ $receiptType = '1'; };
		if( $documentType=='BOLETA'  || $documentType=='3' ){ $receiptType = '3'; };
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


		$responseInvoiceSetted = $documentsApi->sendDocument( $orderId );

		echo "<!--";
		echo $responseInvoiceSetted;
		echo "-->";

		if($responseInvoiceSetted) {
			if($responseInvoiceSetted===true){
				$info = [
					'OrderSync'   => 1
				];
				$documentsDatabase->updateDocument( $info, $orderId );
			}
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
	if ( isset( $_POST['billing_document_type'] ) && ! empty( $_POST['billing_document_type'] ) ) {
		$order->update_meta_data('_billing_document_type', sanitize_text_field( $_POST['billing_document_type'] ) );
		update_user_meta( $order->get_customer_id(), 'billing_document_type', sanitize_text_field( $_POST['billing_document_type'] ) );
	}
}
add_action( 'woocommerce_checkout_create_order', 'save_custom_billing_fields', 20, 2 );



function woocommerce_checkout_validator() {
    // Check if set, if its not set add an error.

    if ( ! $_POST['billing_document_type'] ) {
    	// document type order
	    if ( $_POST['billing_identifier_type']=="1" || $_POST['billing_identifier_type']=="DNI" ) {
		    if ( ! $_POST['billing_identifier'] ) {
		        wc_add_notice( __( 'No ha especificado el numero de DNI.' ), 'error' );
		    }
	    }
	    if ( $_POST['billing_identifier_type']=="6" || $_POST['billing_identifier_type']=="RUC" ) {
		    if ( ! $_POST['billing_identifier'] ) {
		        wc_add_notice( __( 'No ha especificado el numero de RUC.' ), 'error' );
		    }
		    if ( ! $_POST['billing_company'] ) {
		        wc_add_notice( __( 'No ha especificado la razón social.' ), 'error' );
		    }
	    }
	    if ( $_POST['billing_identifier_type']=="4" || $_POST['billing_identifier_type']=="C. DE EXTRANJERIA" ) {
		    if ( ! $_POST['billing_identifier'] ) {
		        wc_add_notice( __( 'No ha especificado el numero de CARNET DE EXTRANJERIA' ), 'error' );
		    }
	    }
    } else {
    	// document type receipt
	    if ( $_POST['billing_identifier_type']=="1" || $_POST['billing_identifier_type']=="DNI" ) {
		    if ( ! $_POST['billing_identifier'] ) {
		        wc_add_notice( __( 'No ha especificado el numero de DNI.' ), 'error' );
		    }
	    }
	    if ( $_POST['billing_identifier_type']=="6" || $_POST['billing_identifier_type']=="RUC" ) {
		    if ( ! $_POST['billing_identifier'] ) {
		        wc_add_notice( __( 'No ha especificado el numero de RUC.' ), 'error' );
		    }
		    if ( ! $_POST['billing_company'] ) {
		        wc_add_notice( __( 'No ha especificado la razón social.' ), 'error' );
		    }
	    }
	    if ( $_POST['billing_identifier_type']=="4" || $_POST['billing_identifier_type']=="C. DE EXTRANJERIA" ) {
		    if ( ! $_POST['billing_identifier'] ) {
		        wc_add_notice( __( 'No ha especificado el numero de CARNET DE EXTRANJERIA' ), 'error' );
		    }
	    }
    }
}

add_action('woocommerce_checkout_process', 'woocommerce_checkout_validator');
