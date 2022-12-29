<?php

require_once dirname(__file__).'/../database/customers.database.php';
require_once dirname(__file__).'/../database/products.database.php';
require_once dirname(__file__).'/../database/settings.database.php';

class InstallService {

	public function __construct() {
		$this->settingsDatabase = new SettingsDatabase;
		$this->customersDatabase = new CustomersDatabase;
		$this->productsDatabase = new ProductsDatabase;
		$this->documentsDatabase = new DocumentsDatabase(new OrdersDatabaseAdapter);
		// $this->documentsDatabase = new DocumentsDatabase(new OrdersDatabaseAdapter);
		// $this->invoicesDatabase = new InvoicesDatabase;
	}


	public function init() {
		// Create tables if not exist.
		$this->settingsDatabase->createTable();
		$this->customersDatabase->createTable();
		$this->productsDatabase->createTable();
		$this->documentsDatabase->createTable();

		// Create and Set settings necessary fields in settings table.
		$this->settingsDatabase->upsertValidatedData();
		$this->settingsDatabase->upsertLoggedData();
		$this->settingsDatabase->upsertTokenData();
	}


	private function createTables() {
		$this->settingsDatabase->createTable();
		$this->customersDatabase->createTable();
		$this->productsDatabase->createTable();
		$this->documentsDatabase->createTable();
	}


	private function Settings() {
		$this->settingsDatabase->upsertValidatedData();
		$this->settingsDatabase->upsertLoggedData();
		$this->settingsDatabase->upsertTokenData();
	}
}
