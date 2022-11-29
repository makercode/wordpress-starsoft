<?php 

class ProductSyncDTO {
	public $parentId;
	public $variantId;
	public $sku;
	public $sync;

	public function __construct( string $parentId, string $variantId, string $sku, string $sync) {
		$this->parentId = $parentId;
		$this->variantId = $variantId;
		$this->sku = $sku;
		$this->sync = $sync;
	}
}
