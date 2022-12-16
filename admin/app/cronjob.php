<?php 


function starsoft_order_sync_cron_hook_action() {
	// if has orders with sync false, then send sync
	$invoicesDatabase = new InvoicesDatabase();
	$invoicesArray = $invoicesDatabase->getInvoices();

	foreach ($invoicesArray as $key_invoice => $invoice) {
		if ($invoice['OrderSync'] == '0') {
			$orderId = $invoice['OrderId'];
			$invoiceId = $invoice['InvoiceId'];

			$invoicesApi = new InvoicesApi;
			$responseInvoiceSetted = $invoicesApi->setInvoice( $orderId );
			error_log( 'Mi evento ejecuto el envio de invoice: '.$invoiceId.'para la orden'.$orderId.'con resultado:'.$responseInvoiceSetted );
					
			if($responseInvoiceSetted==true) {
				$info = [
					'OrderSync'   => 1
				];
				$invoicesDatabase->updateInvoice( $info, $orderId );
			}
		}
	};
	error_log('ejecutado');

}
add_action( 'starsoft_order_sync_cron_hook', 'starsoft_order_sync_cron_hook_action' );
