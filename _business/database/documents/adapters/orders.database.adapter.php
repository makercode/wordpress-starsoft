<?php 

class OrdersDatabaseAdapter implements IDocumentsDatabase {

	protected $ordersDatabase;

	public function __construct() {
		$this->ordersDatabase = new OrdersDatabase;
	}

	public function createTable() {
		return $this->ordersDatabase->createTable();
	}


	public function getDocuments() {
		return $this->ordersDatabase->getOrders();
	}


	public function getDocument( $postId ) {
		return $this->ordersDatabase->getOrder($postId);
	}


	public function updateDocument( $info, $orderId ) {
		return $this->ordersDatabase->updateOrder($info, $orderId);
	}


	public function setDocument( $info, $orderId ) {
		return $this->ordersDatabase->setOrder($info, $orderId);
	}
}
