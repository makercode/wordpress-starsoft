<?php 

class ReceiptsApiAdapter implements IDocumentsApi {

	protected $receiptsApi;


	public function __construct() {
		$this->receiptsApi = new ReceiptsApi;
	}
	

	public function getDocumentJson( $orderId ) {
		return $this->receiptsApi->getReceiptJson($orderId);
	}


	public function setDocument( $orderId ) {
		return $this->receiptsApi->setReceipt($orderId);
	}
}
