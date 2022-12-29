<?php

class DocumentsDatabase implements IDocumentsDatabase {

	private $documentsDatabaseAdapter;


	public function __construct( IDocumentsDatabase $documentsDatabaseAdapter ) {
		return $this->documentsDatabaseAdapter = $documentsDatabaseAdapter;
	}


	public function createTable() {
		return $this->documentsDatabaseAdapter->createTable();
	}


	public function getDocuments() {
		return $this->documentsDatabaseAdapter->getDocuments();
	}


	public function getDocument( $orderId ) {
		return $this->documentsDatabaseAdapter->getDocument( $orderId );
	}


	public function updateDocument( $info, $orderId ) {
		return $this->documentsDatabaseAdapter->updateDocument( $info, $orderId );
	}


	public function setDocument( $info, $orderId ) {
		return $this->documentsDatabaseAdapter->setDocument( $info, $orderId );
	}
}
