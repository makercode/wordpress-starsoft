<?php

class CustomersDatabase {

  public function createTable () {
    global $wpdb;

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

  public function upsertCustomersData ($data) {
    return true;
  }

}
