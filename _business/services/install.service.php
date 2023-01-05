<?php

require_once dirname(__file__).'/../database/customers.database.php';
require_once dirname(__file__).'/../database/products.database.php';
require_once dirname(__file__).'/../database/settings.database.php';

class InstallService {

	public function __construct() {
		$this->settingsDatabase = new SettingsDatabase;
		$this->customersDatabase = new CustomersDatabase;
		$this->productsDatabase = new ProductsDatabase;
		// this calls directly the two instances
		$this->orderDocumentsDatabase = new DocumentsDatabase(new OrdersDatabaseAdapter);
		$this->receiptDocumentsDatabase = new DocumentsDatabase(new ReceiptsDatabaseAdapter);
		// $this->documentsDatabase = new DocumentsDatabase(new OrdersDatabaseAdapter);
		// $this->invoicesDatabase = new InvoicesDatabase;
	}


	public function init() {
		// Create tables if not exist.
		$this->createTables();

		// Create and Set settings necessary fields in settings table.
		$this->upsertSettings();
	}


	private function createTables() {
		$this->settingsDatabase->createTable();
		$this->customersDatabase->createTable();
		$this->productsDatabase->createTable();
		$this->orderDocumentsDatabase->createTable();
		$this->receiptDocumentsDatabase->createTable();
	}


	private function upsertSettings() {
		$this->settingsDatabase->upsertValidatedData();
		$this->settingsDatabase->upsertLoggedData();
		$this->settingsDatabase->upsertTokenData();
		$this->settingsDatabase->upsertDocumentTypeData();
		$this->settingsDatabase->upsertChoosedData();
	}
}
