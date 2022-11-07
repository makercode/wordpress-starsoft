<?php

class InvoicesDatabase {

	public function __construct() {
		global $wpdb;
		$this->table = "{$wpdb->prefix}sync_invoices";
	}

	public function createTable() {
		global $wpdb;
		
		$setInvoicesTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`InvoiceId` INT NOT NULL AUTO_INCREMENT,
			`OrderId` VARCHAR(45) NULL,
			`CustomerId` VARCHAR(45) NULL,
			`Date` VARCHAR(45) NULL,
			`Paid` INT(11) NULL,
			`Sync` INT(11) NULL,
			`Valid` INT(11) NULL,
			`Cancelled` INT(11) NULL,
			PRIMARY KEY (`InvoiceId`)
		)";
		$wpdb->query($setInvoicesTable);
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
	}

}
