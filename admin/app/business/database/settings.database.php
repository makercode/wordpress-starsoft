<?php

class SettingsDatabase {

  public function createTable () {
    global $wpdb;
    
    $setSettingsTable = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sync_settings(
      `SettingId` INT NOT NULL AUTO_INCREMENT,
      `SettingProperty` VARCHAR(45) NULL,
      `SettingValue` INT(11) NULL,
      PRIMARY KEY (`SettingId`)
    )";
    $wpdb->query($setSettingsTable);
  }

  public function upsertSettingsData ($data) {
    global $wpdb;

    $info = [
      'SettingProperty'  => 'valid',
      'SettingValue'     => 0
    ];
    $where = [
      'SettingId'  => '1'
    ];
    $settings_table = "{$wpdb->prefix}sync_settings";

    // update
    $result = $wpdb->update($settings_table, $info, $where );
    // or insert
    if ($result === FALSE || $result < 1) {
      $wpdb->insert($settings_table, $info);
    }
  }

  public function productsValidation () {
    
  }

}
