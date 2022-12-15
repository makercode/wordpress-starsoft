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
	$isValidated = $settingsDatabase->isValidated();
	$isLogged = $settingsDatabase->isLogged();


	if($isLogged=="0" && $isValidated=="0") {

		if($isLogged=='0') {
			// no token
			include dirname(__file__).'/login/login.php';
			return;
		}
	}

	if($isLogged=="1" && $isValidated=="0") {

		if( count($wcProducts)==0 ) {
			// no products
			$settingsDatabase->setTrueValidated();
			include dirname(__file__).'/synchronization/synchronization.php';
			return;
		}



		$productsApi = new ProductsApi();
		// var_dump("------------------------------------------------------");
		// var_dump($wcProducts);
		$responseSyncProdsJson = $productsApi->verifyProducts($wcProducts);
		// var_dump("------------------------------------------------------");
		
		$responseSyncProdsObj = json_decode($responseSyncProdsJson, true);

		$productsHelpers = new ProductsHelpers();

		$noSyncedProductList = $productsHelpers->getNonexistentProducts($responseSyncProdsObj, $wcProductsSync);
		$noSkuProductList = $productsHelpers->getNonSkuProducts($wcProductsSync);

		$draftableProducts = array_merge($noSyncedProductList, $noSkuProductList);

		// check if is array, empty or with elements
		if( is_array($draftableProducts) ) {
			if( sizeof( $draftableProducts, 0 ) >= 1 ) {
				// this include require starsoft service communication, we try use less posible
				include dirname(__file__).'/validation/validation.php';
				return;
			}
		} else {
			echo "No hemos podido conectar con el servidor de Starsoft contacte al area de soporte. <br> NO SE PUDO INSTALAR.";
			return;
		}
		$settingsDatabase->setTrueValidated();
	}

	if($isLogged=="1" && $isValidated=="1") {

		// no valid skus
		include dirname(__file__).'/synchronization/synchronization.php';
		return;
	}
