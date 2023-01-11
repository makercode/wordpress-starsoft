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

		$wpdb->query($setSettingsTable);

		// return $result;
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

		$row = $wpdb->get_results( "SELECT SettingSyncProperty FROM {$this->table} WHERE SettingSyncProperty = 'validated'" );
		if(empty($row)) {
			$result = $wpdb->insert($settings_table, $info);
			return $result;
		}

		// or update
		$result = $wpdb->update($settings_table, $info, $where);
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


		$row = $wpdb->get_results( "SELECT SettingSyncProperty FROM {$this->table} WHERE SettingSyncProperty = 'logged'" );
		if(empty($row)) {
			$result = $wpdb->insert($settings_table, $info);
			return $result;
		}

		// or update
		$result = $wpdb->update($settings_table, $info, $where);
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
	

		$row = $wpdb->get_results( "SELECT SettingSyncProperty FROM {$this->table} WHERE SettingSyncProperty = 'token'" );
		if(empty($row)) {
			$result = $wpdb->insert($settings_table, $info);
			return $result;
		}

		// or update
		$result = $wpdb->update($settings_table, $info, $where);
		return $result;
	}


	public function upsertDocumentTypeData() {

		global $wpdb;

		$info = [
			'SettingSyncId'  => 4,
			'SettingSyncProperty'  => 'DocumentType',
			'SettingSyncValue'     => '0'
		];
		$where = [
			'SettingSyncId'  => 4
		];
		$settings_table = "{$this->table}";


		$row = $wpdb->get_results( "SELECT SettingSyncProperty FROM {$this->table} WHERE SettingSyncProperty = 'DocumentType'" );
		if(empty($row)) {
			$result = $wpdb->insert($settings_table, $info);
			return $result;
		}

		// or update
		$result = $wpdb->update($settings_table, $info, $where);
		return $result;
	}


	public function upsertChoosedData() {

		global $wpdb;

		$info = [
			'SettingSyncId'  => 5,
			'SettingSyncProperty'  => 'choosed',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 5
		];
		$settings_table = "{$this->table}";
	
		// update
		


		$row = $wpdb->get_results( "SELECT SettingSyncProperty FROM {$this->table} WHERE SettingSyncProperty = 'choosed'" );
		if(empty($row)) {
			$result = $wpdb->insert($settings_table, $info);
			return $result;
		}

		// or insert
		$result = $wpdb->update($settings_table, $info, $where);
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


	public function isChoosed() {

		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results("SELECT SettingSyncValue FROM {$settings_table} WHERE SettingSyncId=5");
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


	public function setTrueChoosed() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'choosed',
			'SettingSyncValue'     => 1
		];
		$where = [
			'SettingSyncId'  => 5
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function setFalseChoosed() {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'choosed',
			'SettingSyncValue'     => 0
		];
		$where = [
			'SettingSyncId'  => 5
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


	public function setDocumentType(string $documentTypeId="0") {

		global $wpdb;

		$info = [
			'SettingSyncProperty'  => 'type',
			'SettingSyncValue'     => $documentTypeId
		];
		$where = [
			'SettingSyncId'  => 4
		];
		$settings_table = "{$this->table}";

		// update
		$result = $wpdb->update($settings_table, $info, $where);

		return $result;
	}


	public function getDocumentType() {

		global $wpdb;

		$settings_table = "{$this->table}";
		$result = $wpdb->get_results("SELECT SettingSyncValue FROM {$settings_table} WHERE SettingSyncId=4");

		return $result[0]->SettingSyncValue;
	}

}
