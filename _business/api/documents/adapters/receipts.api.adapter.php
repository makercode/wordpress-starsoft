<?php 

class RecepitsApiAdapter implements IDocumentsApi {

	public function __construct(ReceiptsApi $receiptsApi) {
	}
	
	public function getDocumentJson( $orderId ) {
		$ordersApi->getReceiptJson($orderId);
	}

	public function setDocument( $orderId ) {
		$ordersApi->setReceipt($orderId);
	}
}
