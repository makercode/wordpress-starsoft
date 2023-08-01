<?php 

// Detect when order change to completed
function action_woocommerce_order_status_completed( $orderId ) {

	$validatedGuard = new ValidatedGuard;
	$choosedGuard = new ChoosedGuard;
	if( $validatedGuard->isValidated()=="1" && $choosedGuard->isChoosed() ) {
		$info = [
			'OrderState' => 1
		];

		$settingsGlobal = new SettingsGlobal;
		$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();
		$result = $documentsDatabase->updateDocument( $info, $orderId );

		return $result;
	}

}
add_action( 'woocommerce_order_status_completed', 'action_woocommerce_order_status_completed', 10, 1 );



function action_woocommerce_order_refunded( $orderId, $refundId ) {

	$validatedGuard = new ValidatedGuard;
	$choosedGuard = new ChoosedGuard;
	if( $validatedGuard->isValidated()=="1" && $choosedGuard->isChoosed() ) {
		$info = [
			'OrderState' => -1
		];

		$settingsGlobal = new SettingsGlobal;
		$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();
		$result = $documentsDatabase->updateDocument( $info, $orderId );

		return $result;
	}
	
}
add_action( 'woocommerce_order_refunded', 'action_woocommerce_order_refunded', 10, 2 );




function on_variant_save( $variantId ) {
	require_once dirname(__file__).'/../../_business/api/products.api.php';
	$message = __( 'GUARDADO COMO BORRADOR. El SKU es obligatorio para sincronizar en Starsoft.', 'woocommerce' );

	$variant = wc_get_product( $variantId );
	$parentProductId = $variant->get_parent_id();


	$variantSku = $variant->get_sku();


	if( !$variant->get_sku() ) {
		remove_action( 'woocommerce_update_product_variation', 'on_variant_save' );
		wp_update_post( array( 'ID' => $parentProductId, 'post_status' => 'draft' ) );
		add_action( 'woocommerce_update_product_variation', 'on_variant_save' );
		WC_Admin_Meta_Boxes::add_error( $message );
		return;
	}
	
	$productsApi = new ProductsApi;

	$variantsStack = array();
	$productSkuDTO = new ProductSkuDTO( $variantSku );
	array_push($variantsStack, $productSkuDTO);

	$isProductInStarsoft = $productsApi->verifyProducts( $variantsStack );

	if( !$isProductInStarsoft ) {
		remove_action( 'woocommerce_update_product_variation', 'on_variant_save' );
		wp_update_post( array( 'ID' => $parentProductId, 'post_status' => 'draft' ) );
		add_action( 'woocommerce_update_product_variation', 'on_variant_save' );
		WC_Admin_Meta_Boxes::add_error( 'GUARDADO COMO BORRADOR. Variante: El SKU '.$variantSku.' no se encontró en Starsoft.' );
		return;
	}

	$isProductInStarsoft_obj = json_decode($isProductInStarsoft);
	if(!$isProductInStarsoft_obj[0]->Exists) {
		remove_action( 'woocommerce_update_product_variation', 'on_variant_save' );
		wp_update_post( array( 'ID' => $parentProductId, 'post_status' => 'draft' ) );
		add_action( 'woocommerce_update_product_variation', 'on_variant_save' );
		WC_Admin_Meta_Boxes::add_error( 'GUARDADO COMO BORRADOR. Variante: El SKU '.$variantSku.' no se encontró en Starsoft.' );
		return;
	}

	// var_dump($parentProductId);
	// var_dump("guardado!");
}
add_action( 'woocommerce_update_product_variation', 'on_variant_save', 10, 1 );



// Revisar esta funcion
function action_woocommerce_new_and_update_product( $postId ) {

	require_once dirname(__file__).'/../../_business/api/products.api.php';
	global $wpdb;

	$post = get_post( $postId );
	$product = wc_get_product( $postId );

	$validatedGuard = new ValidatedGuard;
	$choosedGuard = new ChoosedGuard;

	if (get_post_status( $postId ) !== 'trash' ) {
		if( $validatedGuard->isValidated()=="1" && $choosedGuard->isChoosed() ) {

			if( get_post_type($post)=="product" ) {

				if ( $product->is_type( 'simple' ) ) {

					// for variable products
					$message = __( 'GUARDADO COMO BORRADOR. Producto: El SKU es obligatorio para sincronizar en Starsoft.', 'woocommerce' );
					$message_no_found = __( 'GUARDADO COMO BORRADOR. Producto: El SKU que elegiste no se encontró en Starsoft.', 'woocommerce' );

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
					if(!$isProductInStarsoft_obj[0]->Exists) {
						remove_action( 'save_post', 'action_woocommerce_new_and_update_product' );
						wp_update_post( array( 'ID' => $postId, 'post_status' => 'draft' ) );
						add_action( 'save_post', 'action_woocommerce_new_and_update_product' );
						WC_Admin_Meta_Boxes::add_error( $message_no_found );
						return;
					}

				}

				if( $product->is_type( 'variable' ) ) {

					$message = __( 'GUARDADO COMO BORRADOR. Producto: El SKU es obligatorio para sincronizar en Starsoft.', 'woocommerce' );

					$parentProductId = $product->get_parent_id();

					// var_dump("product");

					// only if product variable is the father, child are listened in another function above
					if( $parentProductId == 0 ) {
						// var_dump("entered");
						// var_dump($parentProductId);
						$productsStack = array();
						foreach ( $product->get_children() as $child_id ) {
							// get an instance of the WC_Variation_product Object
							$variation = wc_get_product( $child_id ); 


							$variation_sku = $variation->get_sku();


							if( !$variation->get_sku() ) {
								remove_action( 'save_post', 'action_woocommerce_new_and_update_product' );
								wp_update_post( array( 'ID' => $postId, 'post_status' => 'draft' ) );
								add_action( 'save_post', 'action_woocommerce_new_and_update_product' );
								WC_Admin_Meta_Boxes::add_error( $message );
								return;
							}
							

							$variationSkuDTO = new ProductSkuDTO( $variation_sku );
							array_push( $productsStack, $variationSkuDTO );
						}

						$productsApi = new ProductsApi;
						$areProductsInStarsoft = $productsApi->verifyProducts( $productsStack );
						$areProductsInStarsoft_obj = json_decode( $areProductsInStarsoft );

						foreach ( $areProductsInStarsoft_obj as $isProductInStarsoft ) {

							if(!$isProductInStarsoft->Exists) {
								remove_action( 'save_post', 'action_woocommerce_new_and_update_product' );
								wp_update_post( array( 'ID' => $postId, 'post_status' => 'draft' ) );
								add_action( 'save_post', 'action_woocommerce_new_and_update_product' );
								$message = 'GUARDADO COMO BORRADOR. Producto: El SKU '.$isProductInStarsoft->SKU.' no se encontró en Starsoft.';
								WC_Admin_Meta_Boxes::add_error( $message );
								return;
							}
						}
					} 
					
				}
			}
		}
	}

}
add_action( 'save_post', 'action_woocommerce_new_and_update_product', 10, 1 );

/*  */

// Detect when order change to processing
function action_woocommerce_order_processing( $orderId ) {

	$validatedGuard = new ValidatedGuard;
	$choosedGuard = new ChoosedGuard;
	if( $validatedGuard->isValidated()=="1" && $choosedGuard->isChoosed() ) {
		global $wpdb;

		$info = [
			'OrderState' => 0
		];

		$settingsGlobal = new SettingsGlobal;
		$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();
		$result = $documentsDatabase->updateDocument( $info, $orderId );

		return $result;
	}

}
add_action( 'woocommerce_order_status_processing', 'action_woocommerce_order_processing' );
