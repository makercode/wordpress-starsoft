<?php 


function starsoft_order_sync_cron_hook_action() {
	// if has orders with sync false, then send sync
	$documentsDatabase = new DocumentsDatabase( new OrdersDatabaseAdapter );
	$documentsArray = $documentsDatabase->getDocuments();

	foreach ($documentsArray as $key_document => $document) {
		if ($document['OrderSync'] == '0') {
			$documentSyncId = $document['DocumentSyncId'];
			$orderId = $document['OrderId'];

			$documentsApi = new DocumentsApi( new OrdersApiAdapter );
			$responseDocumentSetted = $documentsApi->setDocument( $orderId );
			error_log( 'Mi evento ejecuto el envio de invoice: '.$documentSyncId.'para la orden'.$orderId.'con resultado:'.$responseDocumentSetted );
					
			if($responseDocumentSetted==true) {
				$info = [
					'OrderSync'   => 1
				];
				$documentsDatabase->updateDocument( $info, $orderId );
			}
		}
	};
	error_log('ejecutado');

}
add_action( 'starsoft_order_sync_cron_hook', 'starsoft_order_sync_cron_hook_action' );
