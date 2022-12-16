<?php 

class ProductsApi {

	public function __construct() {
		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/VerificationProducts";
		// $this->apiUrl = "http://192.168.1.108:8063/Api/VerificationProducts";
	}

	public function verifyProducts( $productSkuList ) {

		$settingsDatabase = new SettingsDatabase;
		$token = $settingsDatabase->getToken();

		if(count($productSkuList)<=0) {
			$productSkuList = array();
		}

		foreach ($productSkuList as $key => $productSku) {
			// var_dump($productSku->sku);
			if( empty($productSku->sku) ) {
				// var_dump($productSkuList[$key]);
				unset($productSkuList[$key]);
			}
		}

		// reindex the array indexes after deleted
		$productSkuList = array_values( $productSkuList );

		$productSkuListJson = json_encode( $productSkuList );

		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' => "Bearer {$token}",
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $productSkuListJson
			)
		);
		// var_dump($result);
		if( !is_wp_error( $result ) ) {
			if( $result['body'] ) {
				return $result['body'];
			}
		}
		// var_dump($result);
		return false;
	}

}
