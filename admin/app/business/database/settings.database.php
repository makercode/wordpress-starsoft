<?php

class SettingsDatabase {

  public function __construct() {
    global $wpdb;
    $this->table = "{$wpdb->prefix}sync_settings";
  }

  public function createTable () {
    global $wpdb;
    
    $setSettingsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
      `SettingId` INT NOT NULL AUTO_INCREMENT,
      `SettingProperty` VARCHAR(45) NULL,
      `SettingValue` INT(11) NULL,
      PRIMARY KEY (`SettingId`)
    )";

    $wpdb->query($setSettingsTable);
  }

  public function upsertSettingsData () {
    global $wpdb;

    $info = [
      'SettingId'  => '1',
      'SettingProperty'  => 'validated',
      'SettingValue'     => 0
    ];
    $where = [
      'SettingId'  => '1'
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
    $sql = $wpdb->prepare( "SELECT SettingValue FROM {$settings_table} WHERE SettingId=2");
    $result = $wpdb->get_results( $sql , ARRAY_A );

    return $result;
  }

  public function isValidated() {
    global $wpdb;

    $settings_table = "{$this->table}";
    $sql = $wpdb->prepare( "SELECT SettingValue FROM {$settings_table} WHERE SettingId=1");
    $result = $wpdb->get_results( $sql , ARRAY_A );

    return $result;
  }

  public function setTrueValidated () {
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

  public function setFalseValidated () {
    global $wpdb;

    $info = [
      'SettingProperty'  => 'validated',
      'SettingValue'     => 0
    ];
    $where = [
      'SettingId'  => '1'
    ];
    $settings_table = "{$this->table}";

    // update
    $result = $wpdb->update($settings_table, $info, $where);

    return $result;
  }

}
