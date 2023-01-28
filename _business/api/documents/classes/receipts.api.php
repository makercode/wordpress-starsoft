<?php 

class ReceiptsApi {

	public function __construct() {

		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterOrder";
		// $this->apiUrl = "http://192.168.1.108:8063/Api/RegisterOrder";
	}


	public function getReceiptJson( $receiptId ) {

		// return db field if exist
		$receiptsDatabase = new ReceiptsDatabase();
		$order = $receiptsDatabase->getReceipt("{$receiptId}");
		// var_dump($receiptsDatabase);

		if ( sizeof($order)>=1 ) {
			$orderSyncJson = $order['0']['OrderJson'];
			return $orderSyncJson;
		}

		// calculate json from actual 
		$orderObject = wc_get_order( $receiptId );
		$orderData = $orderObject->get_data();

		$currency = $orderObject->get_currency();
		$currencyStarsoft = 'MN';
		if($currency!=='PEN') {
			$currencyStarsoft = 'ME';
		}

		$productsList = '';
		$index = 0;

		$orderTotalDiscountPrice = 0;

		foreach ( $orderObject->get_items() as $productId => $productOrderData ) {
			$productData = new WC_Product( $productOrderData->get_data()['product_id'] );
			$quantityOrderLine = $productOrderData->get_data()['quantity'];
			// var_dump($productOrderData->get_data());
			// var_dump($productOrderData->get_data()['subtotal']);
			// var_dump($productOrderData->get_data()['total']);

			if ($index !== 0) {
				$productsList .= ',';
			}

			$unitSaleDiscountPrice = intval($productData->get_regular_price()) - intval($productData->get_price());
			// var_dump( intval($productData->get_price()) );


			// $totalLineProductSaleDiscountPrice = 0;
			$totalLineProductSaleDiscountPrice = $unitSaleDiscountPrice*$quantityOrderLine;
			// $totalLineCouponSaleDiscountPrice = 


			$totalRegularPrice = $productOrderData->get_data()['total']+$totalLineProductSaleDiscountPrice;

			$totalLineCouponSaleDiscountPrice = number_format($productOrderData->get_data()['subtotal']) - number_format($productOrderData->get_data()['total']);
			$totalLineCouponSaleDiscountPercent = $totalLineCouponSaleDiscountPrice*100 / number_format($productOrderData->get_data()['subtotal']);

			// var_dump($totalLineCouponSaleDiscountPrice);
			// var_dump($totalLineCouponSaleDiscountPercent);
			
			$unit_sale_price = $productOrderData->get_data()['total']/$productOrderData->get_data()['quantity'];
			$unitRegularPrice = $totalRegularPrice / $quantityOrderLine;

			$totalLineProductSaleDiscountPercent = $unitSaleDiscountPrice*100/$unitRegularPrice;
			// var_dump($unitSaleDiscountPrice);
			// var_dump($unit_sale_price);

			$productsList .= '
				{
					"Product_Id": "'.$productData->get_sku().'",
					"Order_Id": "'.$productOrderData->get_data()['order_id'].'", // id de orden
					"Product_Line_Quantity": '.$productOrderData->get_quantity().',
					"Product_Unit_Price": '.$unitRegularPrice.',
					"Product_Line_Total_Price" : '.$totalRegularPrice.', // unid * cant
					"Product_Line_Product_Discount_Amount": '.$totalLineProductSaleDiscountPrice.', // Descuento Producto subtotal linea 
					"Product_Line_Product_Discount_Percentage": '.$totalLineProductSaleDiscountPercent.', // Descuento Producto porcentaje total por producto
					"Product_Line_Coupon_Discount_Amount": '.$totalLineCouponSaleDiscountPrice.', // Descuento total
					"Product_Line_Coupon_Discount_Percentage": '.$totalLineCouponSaleDiscountPercent.' // Descuento porcentaje total
				}
			';
			// var_dump($productsList);
			$index++;
			$orderTotalDiscountPrice += $totalLineProductSaleDiscountPrice;
		}

		$orderCustomerIdentifier = get_post_meta($receiptId, '_billing_identifier', true);
		$orderCustomerIdentifierType = get_post_meta($receiptId, '_billing_identifier_type', true);

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
		// var_dump($orderSyncJson);
		return $orderSyncJson;
	}


	public function setReceipt( $receiptId ) {

		$settingsDatabase = new SettingsDatabase;
		$token = $settingsDatabase->getToken();
		
		$orderSyncJson = $this->getReceiptJson( $receiptId );
		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' =>  "Bearer {$token}",
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $orderSyncJson
			)
		);
		// var_dump($result);
		if( !is_wp_error( $result ) ) {
			var_dump($result);
			var_dump($result['response']['code']);
			if( $result['response']['code'] == 200 ) {
				var_dump($result['body']);
				if($result['body'] == "true") {
					return true;
				}
			}
		}
		return false;
	}
}
