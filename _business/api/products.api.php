<?php 

class ProductsApi {

	public function __construct() {
		// $this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/VerificationProducts";
		$this->apiUrl = "http://192.168.1.108:8063/Api/VerificationProducts";
	}

	public function verifyProducts( $post_data ) {

		$json_post_data = json_encode( $post_data );
		// var_dump($post_data);

		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' => 'Bearer xxx',
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $json_post_data
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
