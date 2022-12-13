<?php 

class LoginApi {

	public function __construct() {
		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/LoginAccountWooCommerce";
		// $this->apiUrl = "http://192.168.1.108:8063/Api/LoginAccountWooCommerce";
	}

	public function getToken( $licence, $code, $username, $password ) {

		$json_login_data .= '
			{
				"User": "'.$username.'",
				"Password": "'.$password.'",
				"Domain": "'.$_SERVER['SERVER_NAME'].'",
				"RUC": "'.$licence.'",
				"Bussines_Code": "'.$code.'",
				"token": ""
			}
		';
		// var_dump($post_data);

		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $json_login_data
			)
		);
		if( !is_wp_error( $result ) ) {
			if( $result['body'] ) {
				return json_decode($result['body'])->Session;
			}
		}
		// var_dump($result);
		return false;
	}

}
