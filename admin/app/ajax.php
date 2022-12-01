<?php 

function action_wp_ajax_starsoftlogin() {
	$token = $_POST['token'];
	if(!wp_verify_nonce($token, 'seg')) {
	    header('HTTP/1.1 401 Unauthorized');
	    header('Content-Type: application/json; charset=UTF-8');
	    die(
	    	json_encode(
		    	array(
		    		'message' => 'No tienes permisos para ejecutar esa acción ajax, intentalo denuevo', 
		    		'code' => 401
		    	)
		    )
		);
	}
	$licence 	= $_POST['licence'];
	$code 		= $_POST['code'];
	$username 	= $_POST['username'];
	$password 	= $_POST['password'];

	$loginApi = new LoginApi;
	$token = $loginApi->getToken($licence, $code, $username, $password);
	if( !$token ){
	    header('HTTP/1.1 401 Unauthorized');
	    header('Content-Type: application/json; charset=UTF-8');
	    die(
	    	json_encode(
	    		array(
	    			'message' => 'Datos incorrectos', 
	    			'code' => 401
	    		)
	    	)
	    );
	}
	if( strlen($token) >= 1 ){
		$settingsDatabase = new SettingsDatabase;
		$tokenizer  = $settingsDatabase->setToken($token);
		$loggerized = $settingsDatabase->setTrueLogged();
	}
	echo $token;
	die();
}

add_action('wp_ajax_starsoftlogin', 'action_wp_ajax_starsoftlogin');