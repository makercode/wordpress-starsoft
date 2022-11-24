<?php 

class ProductSyncDTO {
	public $parentid;
	public $variantId;
	public $sku;
	public $sync;

	public function __construct(string $sku, string $parentid, string $variantId, string $sync) {
		$this->parentid = $parentid;
		$this->variantId = $variantId;
		$this->sku = $sku;
		$this->sync = $sync;
	}
}
