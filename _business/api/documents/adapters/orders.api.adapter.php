<?php 

class OrdersApiAdapter implements IDocumentsApi {

	public function __construct(OrdersApi $ordersApi) {
	}

	public function getDocumentJson( $orderId ) {
		$ordersApi->getOrderJson($orderId);
	}


	public function setDocument( $orderId ) {
		$ordersApi->setOrder($orderId);
	}

}
