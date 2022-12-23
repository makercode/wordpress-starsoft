<?php

class CustomersDatabase {

	public function __construct() {

		global $wpdb;
		$this->table = "{$wpdb->prefix}sync_customers";
	}

	public function createTable () {

		global $wpdb;

		$setCustomersTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`CustomerSyncId` INT NOT NULL AUTO_INCREMENT,
			`Dni` VARCHAR(45) NULL,
			`Ruc` VARCHAR(11) NULL,
			`Sync` INT(11) NULL,
			`Valid` INT(11) NULL,
			PRIMARY KEY (`CustomerId`)
		)";
		$wpdb->query($setCustomersTable);
	}

	public function upsertCustomersData ($data) {
		
		// Customers are created on order details
		return true;
	}

}
