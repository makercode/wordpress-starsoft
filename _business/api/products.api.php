<?php 

class ProductsApi {

	public function __construct() {
		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/VerificationProducts";
		// $this->apiUrl = "http://192.168.1.108:8063/Api/VerificationProducts";
	}

	public function verifyProducts( $product_sku_list ) {

		$settingsDatabase = new SettingsDatabase;
		$token = $settingsDatabase->getToken();

		if(count($product_sku_list)<=0) {
			$product_sku_list = array();
		}

		foreach ($product_sku_list as $key => $product_sku) {
			// var_dump($product_sku->sku);
			if( empty($product_sku->sku) ) {
				// var_dump($product_sku_list[$key]);
				unset($product_sku_list[$key]);
			}
		}

		// reindex the array indexes after deleted
		$product_sku_list = array_values( $product_sku_list );

		$json_data = json_encode( $product_sku_list );

		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' => "Bearer {$token}",
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $json_data
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
