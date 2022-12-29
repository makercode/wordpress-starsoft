<?php 

class ReceiptsDatabaseAdapter implements IDocumentsDatabase {

	protected $receiptsDatabase;

	public function __construct() {
		$this->receiptsDatabase = new ReceiptsDatabase;
	}

	public function createTable() {
		return $this->receiptsDatabase->createTable();
	}


	public function getDocuments() {
		return $this->receiptsDatabase->getReceipts();
	}


	public function getDocument( $postId ) {
		return $this->receiptsDatabase->getReceipt($postId);
	}


	public function updateDocument( $info, $orderId ) {
		return $this->receiptsDatabase->updateReceipt($info, $orderId);
	}


	public function setDocument( $info, $orderId ) {
		return $this->receiptsDatabase->setReceipt($info, $orderId);
	}
}
