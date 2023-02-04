<?php 

class OrdersApi {

	public function __construct() {

		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterOrder";
		// $this->apiUrl = "http://192.168.1.107:8063/Api/RegisterOrder";
	}


	public function getOrderJson( $orderId ) {

		// return db field if exist
		$settingsGlobal = new SettingsGlobal;
		$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();
		$order = $documentsDatabase->getDocument("{$orderId}");
		// var_dump($documentsDatabase);

		if ( sizeof($order)>=1 ) {
			$orderSyncJson = $order['0']['OrderJson'];
			return $orderSyncJson;
		}

		// calculate json from actual 
		$orderObject = wc_get_order( $orderId );
		$orderData = $orderObject->get_data();

		$currency = $orderObject->get_currency();
		$currencyStarsoft = 'MN';
		if($currency!=='PEN') {
			$currencyStarsoft = 'ME';
		}

		$productsList = '';
		$index = 0;

		$orderTotalDiscountPrice = 0;

		foreach ( $orderObject->get_items() as $orderLineId => $productOrderData ) {
			$productData = wc_get_product( $productOrderData->get_data()['product_id'] );
			$LineTotal = $productOrderData->get_data()['total'];
			$LineQuantity = $productOrderData->get_data()['quantity'];


			$regularPrice  = 0;
			$listPrice     = 0;
			$salePrice     = 0;


			// Obtain variant ID
			$variantChildId = $productOrderData->get_data()['variation_id'];
			// if variant
			if($variantChildId!==0) {
				$variation = new WC_Product_Variation($variantChildId);

				$regularPrice = floatval($variation->get_regular_price());
				if(!$regularPrice) {
					$regularPrice = floatval($variation->get_price());
				}
				$listPrice = floatval($variation->get_sale_price());
				if(!$listPrice) {
					$listPrice = floatval($variation->get_price());
				}

			}
			// if product
			else {
				$regularPrice = floatval($productData->get_regular_price());
				if(!$regularPrice) {
					$regularPrice = floatval($productData->get_price());
				}
				$listPrice = floatval($productData->get_sale_price());
				if(!$listPrice) {
					$listPrice = floatval($productData->get_price());
				}
			}
			$salePrice = $LineTotal/$LineQuantity;

			var_dump($regularPrice);
			var_dump($listPrice);
			var_dump($salePrice);


			$parentProductId = $productData->get_parent_id();
			var_dump($parentProductId);
			if($parentProductId!==0) {
				var_dump( "Este producto es variacion" );
			}

			if( !$regularPrice ) {
				$regularPrice = $productData->get_price();
			}

			// var_dump( $productOrderData->get_data() );
			// var_dump($productOrderData->get_data()['subtotal']);
			// var_dump($productOrderData->get_data()['total']);

			if ($index !== 0) {
				$productsList .= ',';
			}

			$unitSaleDiscountProductPrice = number_format($regularPrice) - number_format($productData->get_price());


			$quantityOrderLine = $productOrderData->get_data()['quantity'];
			// $totalLineProductSaleDiscountPrice = 0;
			$totalLineProductSaleDiscountPrice = $unitSaleDiscountProductPrice*$quantityOrderLine;
			var_dump($unitSaleDiscountProductPrice);
			var_dump($quantityOrderLine);


			$totalRegularPrice = $productOrderData->get_data()['total'];//+$totalLineProductSaleDiscountPrice

			$totalLineCouponSaleDiscountPrice = number_format($productOrderData->get_data()['subtotal'], 2) - number_format($productOrderData->get_data()['total'], 2);
			$totalLineCouponSaleDiscountPercent = $totalLineCouponSaleDiscountPrice*100/number_format($productOrderData->get_data()['subtotal'], 2);

			$unitSalePrice = $productOrderData->get_data()['total']/$productOrderData->get_data()['quantity'];
			$unitRegularPrice = $totalRegularPrice/$quantityOrderLine;

			// descuento en porcentaje solo del descuento por producto
			// var_dump($unitSaleDiscountProductPrice);
			// var_dump(number_format($productData->get_regular_price()));


			// precio de venta


			$totalLineProductSaleDiscountPercent = ($unitSaleDiscountProductPrice*100/number_format($regularPrice)); 
			// var_dump($totalLineProductSaleDiscountPercent);
			// var_dump($unitSalePrice);



			$productsList .= '
				{
					"Product_Id": "'.$productData->get_sku().'",
					"Order_Id": "'.$productOrderData->get_data()['order_id'].'", // id de orden
					"Product_Line_Quantity": '.$productOrderData->get_quantity().',
					"Product_Original_Price": '.$regularPrice.',
					"Product_Unit_Price": '.$unitRegularPrice.',
					"Product_Line_Total_Price" : '.$totalRegularPrice.', // unid * cant
					"Product_Line_Product_Discount_Amount": '.$totalLineProductSaleDiscountPrice.', // Descuento Producto subtotal linea 
					"Product_Line_Product_Discount_Percentage": '.$totalLineProductSaleDiscountPercent.', // Descuento Producto porcentaje total por producto
					"Product_Line_Coupon_Discount_Amount": '.$totalLineCouponSaleDiscountPrice.', // Descuento cupon producto
					"Product_Line_Coupon_Discount_Percentage": '.$totalLineCouponSaleDiscountPercent.' // Descuento porcentaje cupon producto
				}
			';
			// var_dump($productsList);
			$index++;
			$orderTotalDiscountPrice += $totalLineProductSaleDiscountPrice;
		}

		$orderCustomerIdentifier = get_post_meta($orderId, '_billing_identifier', true);
		$orderCustomerIdentifierType = get_post_meta($orderId, '_billing_identifier_type', true);

		$joinedAddress = $orderObject->get_billing_address_1().'-'.$orderObject->get_billing_address_2().'-'.$orderObject->get_billing_city().'-'.$orderObject->get_billing_country();

		// var_dump($orderObject->get_subtotal());
		// var_dump($orderObject->get_shipping_total());

		$orderSyncJson = '{
			"Customer": {
				"Customer_Id": "'.$orderCustomerIdentifier.'",
				"Address": "'.$joinedAddress.'",
				"Customer_Id_Type": "'.$orderCustomerIdentifierType.'", // 1 = dni
				"Customer_Id_Number": "'.$orderCustomerIdentifier.'",
				"Business_Name": "'.$orderObject->get_billing_company().'", //razon social
				"First_Name": "'.$orderObject->get_billing_first_name().'",
				"Second_Name": "",
				"First_Surname": "'.$orderObject->get_billing_last_name().'",
				"Second_Surname": "",
				"Email": "'.$orderObject->get_billing_email().'"
			},
			"OrderStarsoft": {
				"OrderHeader": {
					"Order_Id": "'.$orderObject->get_id().'", // order id
					"Order_Date": "'.$orderObject->get_date_created()->getTimestamp().'",
					"Order_Subtotal_Amount": '.$orderObject->get_subtotal().', // precio sin shipping
					"Order_Discount_Subtotal_Amount": '.( $orderTotalDiscountPrice+$orderData['discount_total'] ).', // Descuentos totales de prods y coupon
					"Order_Shipping_Subtotal_Amount": '.$orderData['shipping_total'].', // shipping valor
					"Order_Total_Amount": '.$orderData['total'].', // precio final
					"Order_Currency_Type": "'.$currencyStarsoft.'",
					"Order_Discount_Product_Amount": '.$orderTotalDiscountPrice.', // descuento solo de productos
					"Order_Discount_Coupon_Amount": '.$orderData['discount_total'].', // descuento solo de cupones
					"Order_Gloss": "Pedidos Wordpress - '.$orderObject->get_id().'",
					"Order_Address": "'.$joinedAddress.'"
				},
				"orderDetails": [
					'.$productsList.'
				]
			}
		}';
		return $orderSyncJson;
	}


	public function setOrder( $orderId ) {

		$settingsDatabase = new SettingsDatabase;
		$token = $settingsDatabase->getToken();
		
		$orderSyncJson = $this->getOrderJson( $orderId );
		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' => "Bearer {$token}",
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $orderSyncJson
			)
		);
		var_dump($result);

		if( is_wp_error( $result ) ) {
			return false;
		}
		// var_dump($result['response']['code']);
		if( $result['response']['code'] == 200 ) {
			// var_dump($result['body']);
			if($result['body'] == "true") {
				return true;
			}
		}

		return false;
	}
}
