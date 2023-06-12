<?php 

class DocumentsApi implements IDocumentsApi {

	private $documentsApiAdapter;


	public function __construct( IDocumentsApi $documentsApiAdapter ) {
		return $this->documentsApiAdapter = $documentsApiAdapter;
	}


	public function getDocumentJson( $orderId ) {
		return $this->documentsApiAdapter->getDocumentJson( $orderId );
	}


	public function sendDocument( $orderId ) {
		return $this->documentsApiAdapter->sendDocument( $orderId );
	}
}
