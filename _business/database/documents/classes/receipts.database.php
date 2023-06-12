<?php

class ReceiptsDatabase {

	public function __construct() {

		global $wpdb;
		$this->table = "{$wpdb->prefix}starsoft_sync_receipts";
	}


	public function createTable() {

		global $wpdb;
		
		// The text characters 
		$receiptsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`DocumentSyncId` INT NOT NULL AUTO_INCREMENT,
			`OrderId` VARCHAR(45) NULL,
			`OrderJson` TEXT NULL,
			`CustomerIdType` VARCHAR(11) NULL,
			`CustomerId` VARCHAR(45) NULL,
			`OrderDate` date NULL,
			`OrderState` INT(11) NULL,
			`OrderSync` INT(11) NULL,
			`ReceiptType` VARCHAR(11) NULL,
			`ReceiptNumber` VARCHAR(20) NULL,
			`ReceiptState` INT(11) NULL,
			PRIMARY KEY (`DocumentSyncId`)
		)";
		$wpdb->query($receiptsTable);
	}


	public function getReceipts() {

		global $wpdb;

        $startDate = date('Y-m-d',strtotime("-90 days"));
        $endDate   = date('Y-m-d',strtotime("+1 days"));
		$GetReceiptsQuery = "SELECT * FROM {$this->table} WHERE OrderDate BETWEEN '$startDate' AND '$endDate'";
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
