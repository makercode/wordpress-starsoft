<?php 

// Detect when order change to completed
function action_woocommerce_order_status_completed( $orderId ) {
	$validatedGuard = new ValidatedGuard;
	if( $validatedGuard->isValidated()=="1" ) {
		$info = [
			'OrderState' => 1
		];

		$invoicesDatabase = new InvoicesDatabase;
		$result = $invoicesDatabase->updateInvoice( $info, $orderId );

		return $result;
	}

}
add_action( 'woocommerce_order_status_completed', 'action_woocommerce_order_status_completed', 10, 1 );



function action_woocommerce_order_refunded( $orderId, $refund_id ) {
	$validatedGuard = new ValidatedGuard;
	if( $validatedGuard->isValidated()=="1" ) {
		$info = [
			'OrderState' => -1
		];

		$invoicesDatabase = new InvoicesDatabase;
		$result = $invoicesDatabase->updateInvoice( $info, $orderId );

		return $result;
	}
}
add_action( 'woocommerce_order_refunded', 'action_woocommerce_order_refunded', 10, 2 );



// Revisar esta funcion
function action_woocommerce_new_and_update_product( $postId ) {
	$loggedGuard = new LoggedGuard;
	if( $loggedGuard->isLogged()=="1") {
		global $wpdb;

		$post = get_post($postId);
		if ( get_post_type( $post ) == 'product' ) {
			require_once dirname(__file__).'/../../_business/api/products.api.php';
			$message = __( 'GUARDADO COMO BORRADOR. El SKU es obligatorio para sincronizar en Starsoft.', 'woocommerce' );
			$message_no_found = __( 'GUARDADO COMO BORRADOR. El SKU que elegiste no se encontró en Starsoft.', 'woocommerce' );

			$product = wc_get_product( $postId );
			$product_sku = $product->get_sku();


			if( !$product->get_sku() ) {
				remove_action( 'save_post', 'action_woocommerce_new_and_update_product' );
				wp_update_post( array( 'ID' => $postId, 'post_status' => 'draft' ) );
				add_action( 'save_post', 'action_woocommerce_new_and_update_product' );
				WC_Admin_Meta_Boxes::add_error( $message );
				return;
			}
			
			$productsApi = new ProductsApi;

			$productsStack = array();
			$productSkuDTO = new ProductSkuDTO( $product_sku );
			array_push($productsStack, $productSkuDTO);

			$isProductInStarsoft = $productsApi->verifyProducts( $productsStack );

			if( !$isProductInStarsoft ) {

				remove_action( 'save_post', 'action_woocommerce_new_and_update_product' );
				wp_update_post( array( 'ID' => $postId, 'post_status' => 'draft' ) );
				add_action( 'save_post', 'action_woocommerce_new_and_update_product' );
				WC_Admin_Meta_Boxes::add_error( $message_no_found );
				return;
			}

			$isProductInStarsoft_obj = json_decode($isProductInStarsoft);
			if(!$isProductInStarsoft_obj[0]->Exists){
				remove_action( 'save_post', 'action_woocommerce_new_and_update_product' );
				wp_update_post( array( 'ID' => $postId, 'post_status' => 'draft' ) );
				add_action( 'save_post', 'action_woocommerce_new_and_update_product' );
				WC_Admin_Meta_Boxes::add_error( $message_no_found );
				return;
			}
		}
	}
}
add_action( 'woocommerce_update_product', 'action_woocommerce_new_and_update_product', 10, 1 );


// Detect when order change to processing
function action_woocommerce_order_processing( $orderId ) {

	$validatedGuard = new ValidatedGuard;
	if( $validatedGuard->isValidated()=="1") {
		global $wpdb;

		$info = [
			'OrderState' => 0
		];

		$invoicesDatabase = new InvoicesDatabase;
		$result = $invoicesDatabase->updateInvoice( $info, $orderId );

		return $result;
	}

}
add_action( 'woocommerce_order_status_processing', 'action_woocommerce_order_processing' );
