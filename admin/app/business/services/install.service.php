<?php

require_once dirname(__file__).'/../database/customers.database.php';
require_once dirname(__file__).'/../database/products.database.php';
require_once dirname(__file__).'/../database/settings.database.php';

class InstallService {
  public function init() {
    $settingsDatabase = new SettingsDatabase;
    $customersDatabase = new CustomersDatabase;
    $productsDatabase = new ProductsDatabase;

    // Create tables if not exist.
    $settingsDatabase->createTable();
    $customersDatabase->createTable();
    $productsDatabase->createTable();

    // Set sku validation to false
    $settingsDatabase->upsertSettingsData($data);
  }
}
