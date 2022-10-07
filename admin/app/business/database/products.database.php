<?php

class ProductsDatabase {

  public function createTable () {
    global $wpdb;
    
    $setSettingsTable = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sync_settings(
      `SettingId` INT NOT NULL AUTO_INCREMENT,
      `SettingProperty` VARCHAR(45) NULL,
      `SettingValue` INT(11) NULL,
      PRIMARY KEY (`SettingId`)
    )";
    $wpdb->query($setSettingsTable);


    $setProductsTable = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sync_products(
      `ProductId` INT NOT NULL AUTO_INCREMENT,
      `Sku` VARCHAR(45) NULL,
      `Price` VARCHAR(45) NULL,
      `Sync` INT(11) NULL,
      `Valid` INT(11) NULL,
      PRIMARY KEY (`ProductId`)
    )";
    $wpdb->query($setProductsTable);


    $setCustomersTable = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sync_customers(
      `CustomerId` INT NOT NULL AUTO_INCREMENT,
      `Dni` VARCHAR(45) NULL,
      `Ruc` VARCHAR(11) NULL,
      `Sync` INT(11) NULL,
      `Valid` INT(11) NULL,
      PRIMARY KEY (`CustomerId`)
    )";
    $wpdb->query($setCustomersTable);
  }

  public function upsertProductsData () {
  }

}
