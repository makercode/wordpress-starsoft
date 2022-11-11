<?php

class InvoicesDatabase {

	public function __construct() {
		global $wpdb;
		
		$this->table = "{$wpdb->prefix}sync_invoices";
	}

	public function createTable() {
		global $wpdb;
		
		// The text characters 
		$invoicesTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`InvoiceId` INT NOT NULL AUTO_INCREMENT,
			`OrderId` VARCHAR(45) NULL,
			`OrderJson` TEXT NULL,
			`CustomerIdType` VARCHAR(11) NULL,
			`CustomerId` VARCHAR(45) NULL,
			`OrderDate` VARCHAR(45) NULL,
			`OrderState` INT(11) NULL,
			`OrderSync` INT(11) NULL,
			`ReceiptType` VARCHAR(11) NULL,
			`ReceiptNumber` VARCHAR(20) NULL,
			`ReceiptState` INT(11) NULL,
			PRIMARY KEY (`InvoiceId`)
		)";
		$wpdb->query($invoicesTable);
	}

	public function getInvoices() {
		global $wpdb;

		$GetInvoicesQuery = "SELECT * FROM {$this->table}";
		$invoices_array = $wpdb->get_results($GetInvoicesQuery, ARRAY_A);

		if( empty($invoices_array) ) {
			$invoices_array = array();
		}

		return $invoices_array;
	} 

	public function updateInvoice( $info, $order_id ) {
		global $wpdb;

		$table = $this->table;
		$info = $info;
		$where = [
			'OrderId' => $order_id
		];

		$updateResult = $wpdb->update( $table, $info, $where );

		// var_dump($updateResult);
	}

	public function setInvoice( $info, $order_id ) {
		global $wpdb;

		$table = $this->table;
		$info = $info;
		$where = [
			'OrderId' => $order_id
		];

		$updateResult = $wpdb->insert( $table, $info );

		return $updateResult;
	}

}
