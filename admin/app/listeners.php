<?php 

// Detect when order change to completed
function action_woocommerce_order_status_completed( $order_id ) {
	$info = [
		'OrderState' => 1
	];

	$invoicesDatabase = new InvoicesDatabase;
	$result = $invoicesDatabase->updateInvoice( $info, $order_id );

	return $result;
}
add_action( 'woocommerce_order_status_completed', 'action_woocommerce_order_status_completed', 10, 1 );



function action_woocommerce_order_refunded( $order_id, $refund_id ) {
	$info = [
		'OrderState' => -1
	];

	$invoicesDatabase = new InvoicesDatabase;
	$result = $invoicesDatabase->updateInvoice( $info, $order_id );

	return $result;
}
add_action( 'woocommerce_order_refunded', 'action_woocommerce_order_refunded', 10, 2 );



// Revisar esta funcion
function action_save_post( $post_id ) {
	global $wpdb;

	$post = get_post($post_id);
	if ( get_post_type( $post ) == 'product' ) {
		require_once dirname(__file__).'/../../_business/api/products.api.php';
		$message = __( 'GUARDADO COMO BORRADOR. El SKU es obligatorio para sincronizar en Starsoft.', 'woocommerce' );
		$message_no_found = __( 'GUARDADO COMO BORRADOR. El SKU que elegiste no se encuentra en Starsoft.', 'woocommerce' );

		$product = wc_get_product( $post_id );
		$product_sku = $product->get_sku();

		if( !$product->get_sku() ) {
			remove_action( 'save_post', 'action_save_post' );
			wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );
			add_action( 'save_post', 'action_save_post' );
			WC_Admin_Meta_Boxes::add_error( $message );
			return;
		}
		
		$productsApi = new ProductsApi;

		$productsStack = array();
		$productSkuDTO = new ProductSkuDTO( $product_sku );
		array_push($productsStack, $productSkuDTO);

		$isProductInStarsoft = $productsApi->verifyProducts( $productsStack );
		if( $isProductInStarsoft ) {
			remove_action( 'save_post', 'action_save_post' );
			wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );
			add_action( 'save_post', 'action_save_post' );
			WC_Admin_Meta_Boxes::add_error( $message_no_found );
			return;
		}
	}
}
add_action( 'save_post', 'action_save_post', 10);



// Detect when order change to processing
function action_woocommerce_order_processing( $order_id ) {
	/* Real */

	global $wpdb;

	$info = [
		'Paid' => 0,
		'Cancelled' => 0
	];

	$invoicesDatabase = new InvoicesDatabase;
	$result = $invoicesDatabase->updateInvoice( $info, $order_id );

	return $result;
}
add_action( 'woocommerce_order_status_processing', 'action_woocommerce_order_processing' );
