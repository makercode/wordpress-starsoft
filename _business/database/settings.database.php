<?php

class SettingsDatabase {

	public function __construct() {
		global $wpdb;
		$this->table = "{$wpdb->prefix}sync_settings";
	}

	public function createTable() {
		global $wpdb;
		
		$setSettingsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`SettingId` INT NOT NULL AUTO_INCREMENT,
			`SettingProperty` VARCHAR(45) NULL,
			`SettingValue` INT(45) NULL,
			PRIMARY KEY (`SettingId`)
		)";

		$result = $wpdb->query($setSettingsTable);

		return $result;
	}

	public function upsertValidatedData() {
		global $wpdb;

		$info = [
			'SettingId'  => 1,
			'SettingProperty'  => 'validated',
			'SettingValue'     => 0
		];
		$where = [
			'SettingId'  => 1
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
			'SettingId'  => 2,
			'SettingProperty'  => 'logged',
			'SettingValue'     => 0
		];
		$where = [
			'SettingId'  => 2
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
			'SettingId'  => 3,
			'SettingProperty'  => 'token',
			'SettingValue'     => ''
		];
		$where = [
			'SettingId'  => 3
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

		$result = $wpdb->get_results("SELECT SettingValue FROM {$settings_table} WHERE SettingId=2");
		// var_dump($result);

		return $result[0]->SettingValue;
	}

	public function isValidated() {
		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results( "SELECT SettingValue FROM {$settings_table} WHERE SettingId=1");

		return $result[0]->SettingValue;
	}

	public function setTrueValidated() {
		global $wpdb;

		$info = [
			'SettingProperty'  => 'validated',
			'SettingValue'     => 1
		];
		$where = [
			'SettingId'  => 1
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}

	public function setFalseValidated() {
		global $wpdb;

		$info = [
			'SettingProperty'  => 'validated',
			'SettingValue'     => 0
		];
		$where = [
			'SettingId'  => 1
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setTrueLogged() {
		global $wpdb;

		$info = [
			'SettingProperty'  => 'logged',
			'SettingValue'     => 1
		];
		$where = [
			'SettingId'  => 2
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}

	public function setFalseLogged() {
		global $wpdb;

		$info = [
			'SettingProperty'  => 'logged',
			'SettingValue'     => 0
		];
		$where = [
			'SettingId'  => 2
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function getToken() {
		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results( "SELECT SettingValue FROM {$settings_table} WHERE SettingId=3");

		return $result[0]->SettingValue;
	}
	
	public function setToken($token) {
		global $wpdb;

		$info = [
			'SettingValue'	=> md5($token)
		];
		$where = [
			'SettingId'  => 3
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}
}
