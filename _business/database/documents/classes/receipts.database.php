<?php

class ReceiptsDatabase {

	public function __construct() {

		global $wpdb;
		$this->table = "{$wpdb->prefix}sync_receipts";
	}


	public function createTable() {

		global $wpdb;
		
		// The text characters 
		$receiptsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`ReceiptSyncId` INT NOT NULL AUTO_INCREMENT,
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
			PRIMARY KEY (`ReceiptId`)
		)";
		$result = $wpdb->query($receiptsTable);
		return $result;
	}


	public function getReceipts() {

		global $wpdb;

		$GetReceiptsQuery = "SELECT * FROM {$this->table}";
		$receiptsArray = $wpdb->get_results($GetReceiptsQuery, ARRAY_A);

		if( empty($receiptsArray) ) {
			$receiptsArray = array();
		}

		return $receiptsArray;
	}


	public function getReceipt( $orderId ) {

		global $wpdb;

		$GetReceiptQuery = "SELECT * FROM {$this->table} WHERE OrderId={$orderId}";
		$receiptArray = $wpdb->get_results($GetReceiptQuery, ARRAY_A);

		if( empty($receiptArray) ) {
			$receiptArray = array();
		}

		return $receiptArray;
	}


	public function updateReceipt( $info, $orderId ) {

		global $wpdb;

		$table = $this->table;
		$info = $info;
		$where = [
			'OrderId' => $orderId
		];

		$updateResult = $wpdb->update( $table, $info, $where );

		return $updateResult;
	}


	public function setReceipt( $info, $orderId ) {

		global $wpdb;

		$table = $this->table;
		$info = $info;
		$where = [
			'OrderId' => $orderId
		];

		$updateResult = $wpdb->insert( $table, $info );

		return $updateResult;
	}
}
