<?php
	
class SettingsGlobal {

	public function __construct() {
	}

	public function getDocumentsDatabaseInstance() {

		$typeDocument = (new SettingsDatabase)->getDocumentType();
		if($typeDocument=="0") {
			return new DocumentsDatabase( new OrdersDatabaseAdapter );
		}
		else if($typeDocument=="1") {
			return new DocumentsDatabase( new ReceiptsDatabaseAdapter );
		}
	}

	public function getDocumentsApiInstance() {

		$typeDocument = (new SettingsDatabase)->getDocumentType();
		if($typeDocument=="0") {
			return new DocumentsApi( new OrdersApiAdapter );
		}
		else if($typeDocument=="1") {
			return new DocumentsApi( new ReceiptsApiAdapter );
		}
	}
}