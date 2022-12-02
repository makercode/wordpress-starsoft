<?php 

	require_once dirname(__file__).'/../../../_business/database/invoices.database.php';
	require_once dirname(__file__).'/../../../_business/database/products.database.php';
	require_once dirname(__file__).'/../../../_business/database/settings.database.php';

	require_once dirname(__file__).'/../../../_business/api/products.api.php';
	require_once dirname(__file__).'/../../../_business/helpers/products.helpers.php';

	require_once dirname(__file__).'/../../../_business/api/invoices.api.php';


	// writting products from woocommerce to sync
	$productsDatabase = new ProductsDatabase();

	$createProductTable = $productsDatabase->createTable();
	$wcProductsSync = $productsDatabase->getWCProductsSyncData();
	$wcProducts = $productsDatabase->getWCProductsData();
	$syncProducts = $productsDatabase->setProductsSyncData($wcProductsSync);

	$settingsDatabase = new SettingsDatabase;
	// $isValidated = $settingsDatabase->isValidated()[0]->SettingValue;
	// $isLogged = $settingsDatabase->isLogged()[0]->SettingValue;
	$isValidated = $settingsDatabase->isValidated()[0];
	$isLogged = $settingsDatabase->isLogged()[0];

	if($isLogged->SettingValue=='0') {
		// var_dump("aguante megadeth - logged");
		include dirname(__file__).'/login/login.php';
		return;
	}

	if($isValidated=='1') {
		// var_dump("aguante megadeth - validated");
		include dirname(__file__).'/sync/sync.php';
		return;
	}

	$productsApi = new ProductsApi();
	$responseSyncProdsJson = $productsApi->verifyProducts($wcProducts);
	$responseSyncProdsObj = json_decode($responseSyncProdsJson, true);
	// var_dump("-");
	// var_dump($responseSyncProdsJson);
	// var_dump($responseSyncProdsObj);

	$productsHelpers = new ProductsHelpers();
	$productNotSyncList = $productsHelpers->getNonexistentProducts($responseSyncProdsObj, $wcProductsSync);
	// var_dump($productNotSyncList); // return false when 
	// check if is array, empty or with elements
	if( is_array($productNotSyncList) ) {
		if( sizeof( $productNotSyncList, 0 ) >= 1 ) {
			// this include require starsoft service communication, we try use less posible
			include dirname(__file__).'/validation/validation.php';
			return;
		}
	} else {
		echo "No hemos podido conectar con el servidor de Starsoft contacte al area de soporte. <br> NO SE PUDO INSTALAR.";
		return;
	}

	// save Validated true
	$settingsDatabase->setTrueValidated();
	include dirname(__file__).'/sync/sync.php';