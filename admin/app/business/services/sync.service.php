<?php

require_once dirname(__file__).'/../database/invoices.database.php';

class SyncService {

  public function init() {

    // if (!settings->valid_sync) : return false

    $invoicesDatabase = new InvoicesDatabase;
    // Set sku validation to false
    $invoicesDatabase->createTable();
  }

}
