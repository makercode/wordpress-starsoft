<?php


class SyncronizerService {

	public function __construct() {
		// if has orders with sync false, then send sync
		$this->settingsGlobal = new SettingsGlobal;
	}


	public function syncronizeOrders() {
		$documentsDatabase = $this->settingsGlobal->getDocumentsDatabaseInstance();
		$documentsArray = $documentsDatabase->getDocuments();

		foreach ($documentsArray as $key_document => $document) {
			if ($document['OrderSync'] == '0') {
				$documentSyncId = $document['DocumentSyncId'];
				$orderId = $document['OrderId'];

				$documentsApi = $this->settingsGlobal->getDocumentsApiInstance();
				$responseDocumentSetted = $documentsApi->sendDocument( $orderId );

				if($responseDocumentSetted==true) {
					$info = [
						'OrderSync'   => 1
					];
					$documentsDatabase->updateDocument( $info, $orderId );
				}
				error_log( 'Mi evento ejecuto el envio de invoice: '.$documentSyncId.'para la orden'.$orderId.'con resultado:'.$responseDocumentSetted );
			}
		};
		error_log('ejecutado syncronizeOrders');
	}


	public function syncronizeOrder($orderId) {
		$documentsDatabase = $this->settingsGlobal->getDocumentsDatabaseInstance();
		$documentsArray = $documentsDatabase->getDocuments();

		$documentsApi = $this->settingsGlobal->getDocumentsApiInstance();
		$responseDocumentSetted = $documentsApi->sendDocument( $orderId );

		if($responseDocumentSetted==true) {
			$info = [
				'OrderSync'   => 1
			];
			$documentsDatabase->updateDocument( $info, $orderId );
		}

		error_log('ejecutado syncronizeOrder');
	}
}
