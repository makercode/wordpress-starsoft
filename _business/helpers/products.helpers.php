<?php 

class ProductsHelpers {

	public function __construct() {
	}

	function getNonexistentProducts ( $responseSyncProdsObj, $wcProductsSync ) {

		// var_dump("wcProductsSync");
		// var_dump($wcProductsSync);
		function findPostIdBySku($sku, $wcProductsSync) {
			foreach ( $wcProductsSync as $product ) {
				if ( $sku == $product->sku ) {
					return $product->variantId;
				}
			}
			return false;
		}

		function findParentIdBySku($sku, $wcProductsSync) {
			foreach ( $wcProductsSync as $product ) {
				if ( $sku == $product->sku ) {
					return $product->parentId;
				}
			}
			return false;
		}

		$NoSyncProductList = array();
		// check if product exists in db
		// Aqui ocurre un problema cuando se activa el 
		if(!$responseSyncProdsObj){
			// if response sync prods obj is null then cancel helper
			return false;
		}
		// var_dump($responseSyncProdsObj);
		if( count($responseSyncProdsObj)<=0 ) {
			// if response sync dont have any iem then cancel helper
			return false;
		}
		foreach ($responseSyncProdsObj as $key => $product) {

			$productNotSyncItem = new stdClass();
			$productNotSyncItem->sku = $product['SKU'];
			$productNotSyncItem->exists = $product['Exists'];
			$productNotSyncItem->parentId = findParentIdBySku($product['SKU'], $wcProductsSync);
			$productNotSyncItem->variantId = findPostIdBySku($product['SKU'], $wcProductsSync);
			$productNotSyncItem->empty = $product['SKU'] === '';
			$productNotSyncItem->postLinkToEdit = get_edit_post_link($productNotSyncItem->parentId);
			$productNotSyncItem->title = get_the_title($productNotSyncItem->parentId);
			$productNotSyncItem->message = "No se encontró este SKU en starsoft";

			if( !$productNotSyncItem->exists ) {
				array_push(
					$NoSyncProductList,
					$productNotSyncItem
				);
			}
		}
		return $NoSyncProductList;
	}

	function getNonSkuProducts ( $evaluableProducts ) {

		$noSkuProductList = array();

		foreach ( $evaluableProducts as $key => $noSkuProduct ) {
			// var_dump($noSkuProduct);
			if( empty($noSkuProduct->sku) ) {

				$noSkuProductObj = new stdClass();
				$noSkuProductObj->sku = $noSkuProduct->sku;
				$noSkuProductObj->exists = false;
				$noSkuProductObj->parentId = $noSkuProduct->parentId;
				$noSkuProductObj->variantId = $noSkuProduct->variantId;
				$noSkuProductObj->empty = $noSkuProduct->sku === '';
				$noSkuProductObj->postLinkToEdit = get_edit_post_link($noSkuProduct->parentId);
				$noSkuProductObj->title = get_the_title($noSkuProduct->parentId);
				$noSkuProductObj->message = "No se admiten SKU vacios";

				array_push($noSkuProductList, $noSkuProductObj);
			}
		}
		return $noSkuProductList;
	}

}