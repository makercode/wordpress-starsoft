<?php

require_once dirname(__file__).'/../models/product.sync.dto.model.php';
require_once dirname(__file__).'/../models/product.sku.dto.model.php';

class ProductsDatabase {

	public function __construct() {

		global $wpdb;
		$this->table = "{$wpdb->prefix}sync_products";
	}


	public function createTable () {

		global $wpdb;
		$setProductsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
			`ProductSyncId` INT NOT NULL AUTO_INCREMENT,
			`ProductParentId` INT NULL,
			`ProductVariantId` INT NULL,
			`ProductSku` VARCHAR(45) NOT NULL,
			`ProductSync` INT(11) NULL,
			PRIMARY KEY (`ProductSyncId`),
			UNIQUE KEY (`ProductSku`)
		)";
		$wpdb->query($setProductsTable);
	}


	public function getWCProductsSyncData () {

		$args = array(
			'orderby'  => 'name',
			'limit' => -1
		);
		$products = wc_get_products( $args );

		$productsStack = array();
		foreach ($products as $key_product => $value_product) {

			$_temp_product = new ProductSyncDTO(
				$value_product->get_id(),
				$value_product->get_id(),
				$value_product->get_sku(),
				'0'
			);

			if($value_product->get_status()=="publish") {
				if ($value_product->get_type() == "grouped") {
					continue;
				}
				if ($value_product->get_type() == "external") {
					continue;
				}
				if ($value_product->get_type() == "variable") {
					foreach ( $value_product->get_children() as $child_id ) {
						// get an instance of the WC_Variation_product Object
						$variation = wc_get_product( $child_id ); 

						if ( ! $variation || ! $variation->exists() ) {
							continue;
						}

						$_temp_product = new ProductSyncDTO(
							$value_product->get_id() ,
							$variation->get_id() ,
							$variation->get_sku(),
							'0'
						);
						array_push($productsStack, $_temp_product);
					}
				}
				if ($value_product->get_type() == "simple") {
					array_push($productsStack, $_temp_product);
				}
			}
		}
		return $productsStack;
	}


	public function getWCProductsData () {

		$args = array(
			'orderby'  => 'name',
			'limit' => -1
		);
		$products = wc_get_products( $args );

		$productsStack = array();
		foreach ($products as $key_product => $value_product) {
			// var_dump($value_product);
			if($value_product->get_status()=="publish") {
				$_temp_product = new ProductSkuDTO($value_product->get_sku());
				if ($value_product->get_type() == "grouped") {
					continue;
				}
				if ($value_product->get_type() == "external") {
					continue;
				}
				if ($value_product->get_type() == "variable") {
					foreach ( $value_product->get_children() as $child_id ) {
						// get an instance of the WC_Variation_product Object
						$variation = wc_get_product( $child_id ); 

						if ( ! $variation || ! $variation->exists() ) {
							continue;
						}

						$_temp_product = new ProductSkuDTO($variation->get_sku());
						array_push($productsStack, $_temp_product);
					}
				}
				if ($value_product->get_type() == "simple") {
					array_push($productsStack, $_temp_product);
				}
			}
		}
		return $productsStack;
	}


	public function setWCProductDraft ($postid) {

		update_post_meta( $postid, 'status', 'draft' );
	}


	public function setProductsSyncData ($productsStack) {

		global $wpdb;

		$values = '';

		foreach($productsStack as $key => $product) {
			if ($key !== 0) {
				$values .= ',';
			}
			$values .= '("';
			$values .= $product->parentId;
			$values .= '"';
			$values .= ',';
			$values .= '"';
			$values .= $product->variantId;
			$values .= '"';
			$values .= ',';
			$values .= '"';
			$values .= $product->sku;
			$values .= '"';
			$values .= ',';
			$values .= '"';
			$values .= $product->sync;
			$values .= '"';
			$values .= ')';
		}

		if(count($productsStack)>0) {
			$query = "
				INSERT INTO {$this->table}
					(ProductParentId, ProductVariantId ,ProductSku , ProductSync)
				VALUES
					{$values}
				ON DUPLICATE KEY UPDATE 
					ProductSync=VALUES(ProductSync)
			";

			return $wpdb->query($query);
		}
		return false;
	}


	public function setProductSyncData () {
	}
}
