<?php 

class ReceiptsApi {

	public function __construct() {

		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterOrder";
		// $this->apiUrl = "http://192.168.1.107:8063/Api/RegisterOrder";
	}


	public function getReceiptJson( $receiptId ) {


		// return db field if exist
		$settingsGlobal = new SettingsGlobal;
		$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();
		$order = $documentsDatabase->getDocument("{$receiptId}");
		// var_dump($documentsDatabase);

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

			// var_dump( $productOrderData->get_data() );
			// var_dump($productOrderData->get_data()['subtotal']);
			// var_dump($productOrderData->get_data()['total']);

			if ($index !== 0) {
				$productsList .= ',';
			}

			// var_dump($productData->get_regular_price());
			// var_dump($productData->get_price());
			$unitSaleDiscountProductPrice = intval($productData->get_regular_price()) - intval($productData->get_price());


			// $totalLineProductSaleDiscountPrice = 0;
			$totalLineProductSaleDiscountPrice = $unitSaleDiscountProductPrice*$quantityOrderLine;
			// $totalLineCouponSaleDiscountPrice = 


			$totalRegularPrice = $productOrderData->get_data()['total'];//+$totalLineProductSaleDiscountPrice

			$totalLineCouponSaleDiscountPrice = number_format($productOrderData->get_data()['subtotal'], 2) - number_format($productOrderData->get_data()['total'], 2);
			$totalLineCouponSaleDiscountPercent = $totalLineCouponSaleDiscountPrice*100/number_format($productOrderData->get_data()['subtotal'], 2);

			$unitSalePrice = $productOrderData->get_data()['total']/$productOrderData->get_data()['quantity'];
			$unitRegularPrice = $totalRegularPrice/$quantityOrderLine;

			// descuento en porcentaje solo del descuento por producto
			// var_dump($unitSaleDiscountProductPrice);
			// var_dump(intval($productData->get_regular_price()));

			$totalLineProductSaleDiscountPercent = ($unitSaleDiscountProductPrice*100/intval($productData->get_regular_price())); 
			// var_dump($totalLineProductSaleDiscountPercent);
			// var_dump($unitSalePrice);

			// precio de venta
			$regularPrice = $productData->get_regular_price();
			if( $regularPrice ) {
				$regularPrice = $productData->get_price();
			}

			$productsList .= '
				{
					"Product_Id": "'.$productData->get_sku().'",
					"Order_Id": "'.$productOrderData->get_data()['order_id'].'",
					"Product_Line_Quantity": '.$productOrderData->get_quantity().',
					"Product_Original_Price": '.$regularPrice.',
					"Product_Unit_Price": '.$unitRegularPrice.',
					"Product_Line_Total_Price" : '.$totalRegularPrice.',
					"Product_Line_Product_Discount_Amount": '.$totalLineProductSaleDiscountPrice.', 
					"Product_Line_Product_Discount_Percentage": '.$totalLineProductSaleDiscountPercent.',
					"Product_Line_Coupon_Discount_Amount": '.$totalLineCouponSaleDiscountPrice.', 
					"Product_Line_Coupon_Discount_Percentage": '.$totalLineCouponSaleDiscountPercent.'
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
				"Customer_Id_Type": "'.$orderCustomerIdentifierType.'",
				"Customer_Id_Number": "'.$orderCustomerIdentifier.'",
				"Business_Name": "'.$orderObject->get_billing_company().'",
				"First_Name": "'.$orderObject->get_billing_first_name().'",
				"Second_Name": "",
				"First_Surname": "'.$orderObject->get_billing_last_name().'",
				"Second_Surname": "",
				"Email": "'.$orderObject->get_billing_email().'"
			},
			"OrderStarsoft": {
				"OrderHeader": {
					"Order_Id": "'.$orderObject->get_id().'",
					"Order_Date": "'.$orderObject->get_date_created()->getTimestamp().'",
					"Order_Subtotal_Amount": '.$orderObject->get_subtotal().',
					"Order_Discount_Subtotal_Amount": '.( $orderTotalDiscountPrice+$orderData['discount_total'] ).',
					"Order_Shipping_Subtotal_Amount": '.$orderData['shipping_total'].', 
					"Order_Total_Amount": '.$orderData['total'].',
					"Order_Currency_Type": "'.$currencyStarsoft.'",
					"Order_Discount_Product_Amount": '.$orderTotalDiscountPrice.',
					"Order_Discount_Coupon_Amount": '.$orderData['discount_total'].',
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
		var_dump($result);

		if( is_wp_error( $result ) ) {
			return false;
		}
		if( $result['response']['code'] == 200 ) {
			var_dump($result['body']);
			if($result['body'] == "true") {
				return true;
			}
		}
		return false;
	}
}
