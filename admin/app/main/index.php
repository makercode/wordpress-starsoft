<?php 

  require_once dirname(__file__).'/../business/database/invoices.database.php';
  require_once dirname(__file__).'/../business/database/products.database.php';
  require_once dirname(__file__).'/../business/database/settings.database.php';

  require_once dirname(__file__).'/../business/api/products.api.php';
  require_once dirname(__file__).'/../business/helpers/products.helpers.php';


  // writting products from woocommerce to sync
  $productsDatabase = new ProductsDatabase();

  $createProductTable = $productsDatabase->createTable();
  $wcProductsSync = $productsDatabase->getWCProductsSyncData();
  $wcProducts = $productsDatabase->getWCProductsData();
  $syncProducts = $productsDatabase->setProductsSyncData($wcProductsSync);

  $settingsDatabase = new SettingsDatabase;
  $isValidated = $settingsDatabase->isValidated()[0]['SettingValue'];
  $isLogged = $settingsDatabase->isLogged()[0]['SettingValue'];
  // var_dump($isValidated);

  if($isLogged=='0') {
    include dirname(__file__).'/login/login.php';
    return;
  }

  if($isValidated!=='0') {
    include dirname(__file__).'/sync/invoices-list.php';
    return;
  }

  $productsApi = new ProductsApi();
  $responseSyncProdsJson= $productsApi->verifyProducts($wcProducts);
  $responseSyncProdsObj = json_decode($responseSyncProdsJson, true);

  $productsDatabase = new ProductsHelpers();
  $productNotSyncList = $productsDatabase->getNonexistentProducts($responseSyncProdsObj, $wcProductsSync);

  if( sizeof( $productNotSyncList, 0 ) >= 1 ) {
    // this include require starsoft service communication, we try use less posible
    include dirname(__file__).'/validation/tosync-products-list.php';
    return;
  }

  // save Validated true
  $settingsDatabase->setTrueValidated();
  include dirname(__file__).'/sync/invoices-list.php';