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

		$productNotSyncList = array();
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
			$productNotSyncItem->parentid = findParentIdBySku($product['SKU'], $wcProductsSync);
			$productNotSyncItem->postid = findPostIdBySku($product['SKU'], $wcProductsSync);
			$productNotSyncItem->empty = $product['SKU'] === '';
			$productNotSyncItem->postLinkToEdit = get_edit_post_link($productNotSyncItem->parentid);

			$productNotSyncItem->message = "No se encontró este SKU en starsoft";
			if($productNotSyncItem->empty) {
				$productNotSyncItem->message = "No se admiten SKU vacios";
			}

			if( !$productNotSyncItem->exists ) {
				array_push(
					$productNotSyncList,
					$productNotSyncItem
				);
			}
		}
		return $productNotSyncList;
	}

}