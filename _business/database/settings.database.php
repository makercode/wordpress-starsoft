<?php

class SettingsDatabase {

	public function __construct() {

		global $wpdb;
		$this->table = "{$wpdb->prefix}sync_settings";
	}


	public function createTable() {

		global $wpdb;
		
		$setSettingsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`SettingSyncId` INT NOT NULL AUTO_INCREMENT,
			`SettingSyncProperty` VARCHAR(45) NULL,
			`SettingSyncValue` VARCHAR(120) NULL,
			PRIMARY KEY (`SettingSyncId`)
		)";

		$result = $wpdb->query($setSettingsTable);

		return $result;
	}


	public function upsertValidatedData() {

		global $wpdb;

		$info = [
			'SettingSyncId'  => 1,
			'SettingSyncProperty'  => 'validated',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 1
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);
		// or insert
		if ($result === FALSE || $result < 1) {
			$wpdb->insert($settings_table, $info);
		}

		return $result;
	}


	public function upsertLoggedData() {

		global $wpdb;

		$info = [
			'SettingSyncId'  => 2,
			'SettingSyncProperty'  => 'logged',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 2
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);
		// or insert
		if ($result === FALSE || $result < 1) {
			$wpdb->insert($settings_table, $info);
		}

		return $result;
	}


	public function upsertTokenData() {

		global $wpdb;

		$info = [
			'SettingSyncId'  => 3,
			'SettingSyncProperty'  => 'token',
			'SettingSyncValue'     => ''
		];
		$where = [
			'SettingSyncId'  => 3
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);
		// or insert
		if ($result === FALSE || $result < 1) {
			$wpdb->insert($settings_table, $info);
		}

		return $result;
	}


	public function upsertDestinyData() {

		global $wpdb;

		$info = [
			'SettingSyncId'  => 3,
			'SettingSyncProperty'  => 'token',
			'SettingSyncValue'     => ''
		];
		$where = [
			'SettingSyncId'  => 3
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);
		// or insert
		if ($result === FALSE || $result < 1) {
			$wpdb->insert($settings_table, $info);
		}

		return $result;
	}


	public function isLogged() {

		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results("SELECT SettingSyncValue FROM {$settings_table} WHERE SettingSyncId=2");
		return $result[0]->SettingSyncValue;
	}


	public function isValidated() {

		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results("SELECT SettingSyncValue FROM {$settings_table} WHERE SettingSyncId=1");
		return $result[0]->SettingSyncValue;
	}


	public function setTrueValidated() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'validated',
			'SettingSyncValue'     => 1
		];
		$where = [
			'SettingSyncId'  => 1
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setFalseValidated() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'validated',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 1
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setTrueLogged() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'logged',
			'SettingSyncValue'     => 1
		];
		$where = [
			'SettingSyncId'  => 2
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setFalseLogged() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'logged',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 2
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function getToken() {

		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results("SELECT SettingSyncValue FROM {$settings_table} WHERE SettingSyncId=3");

		return $result[0]->SettingSyncValue;
	}


	public function setToken($token) {

		global $wpdb;

		$info = [
			'SettingSyncValue'	=> md5($token)
		];
		$where = [
			'SettingSyncId'  => 3
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setReceiptTypeDocument() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'type',
			'SettingSyncValue'     => 1
		];
		$where = [
			'SettingSyncId'  => 4
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setOrderTypeDocument() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'type',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 4
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}
}
