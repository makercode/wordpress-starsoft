<?php

require_once dirname(__file__).'/../database/customers.database.php';
require_once dirname(__file__).'/../database/products.database.php';
require_once dirname(__file__).'/../database/settings.database.php';

class InstallService {

	public function __construct() {
		$this->settingsDatabase = new SettingsDatabase;
		$this->customersDatabase = new CustomersDatabase;
		$this->productsDatabase = new ProductsDatabase;
	}

	public function init() {
		// Create tables if not exist.
		$this->settingsDatabase->createTable();
		$this->customersDatabase->createTable();
		$this->productsDatabase->createTable();

		// Set sku validation to false
		$this->settingsDatabase->upsertSettingsData($data);
	}
}
