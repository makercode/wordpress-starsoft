<?php

class OrdersDatabase {

	public function __construct() {

		global $wpdb;
		$this->table = "{$wpdb->prefix}starsoft_sync_orders";
	}


	public function createTable() {

		global $wpdb;

		// The text characters 
		$ordersTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
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
		$wpdb->query($ordersTable);
	}


	public function getOrders() {

		global $wpdb;

        $startDate = date('Y-m-d',strtotime("-90 days"));
        $endDate   = date('Y-m-d',strtotime("+1 days"));
		$GetOrdersQuery = "SELECT * FROM {$this->table} WHERE OrderDate BETWEEN '$startDate' AND '$endDate'";
		$ordersList = $wpdb->get_results($GetOrdersQuery, ARRAY_A);

		if( empty($ordersList) ) {
			$ordersList = array();
		}

		return $ordersList;
	}


	public function getOrder( $postId ) {

		global $wpdb;

		$GetOrderQuery = "SELECT * FROM {$this->table} WHERE OrderId={$postId}";
		$orderList = $wpdb->get_results($GetOrderQuery, ARRAY_A);

		if( empty($orderList) ) {
			$orderList = array();
		}

		return $orderList;
	}


	public function updateOrder( $info, $orderId ) {

		global $wpdb;

		$table = $this->table;
		$info = $info;
		$where = [
			'OrderId' => $orderId
		];

		$updateResult = $wpdb->update( $table, $info, $where );

		return $updateResult;
	}


	public function setOrder( $info, $orderId ) {

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
