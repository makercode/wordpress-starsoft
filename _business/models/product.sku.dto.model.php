<?php 

class ProductSkuDTO {
	public $sku;

	public function __construct(string $sku) {
		$this->sku = $sku;
	}
}
