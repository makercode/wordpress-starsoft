<?php 

class OrdersApiAdapter implements IDocumentsApi {

	protected $ordersApi;


	public function __construct() {
		$this->ordersApi = new OrdersApi;
	}


	public function getDocumentJson( $orderId ) {
		return $this->ordersApi->getOrderJson($orderId);
	}


	public function setDocument( $orderId ) {
		return $this->ordersApi->setOrder($orderId);
	}
}
