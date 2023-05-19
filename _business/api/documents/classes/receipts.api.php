<?php 

class ReceiptsApi {

	public function __construct() {

		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterReceipt";
		// $this->apiUrl = "http://192.168.1.108:8063/Api/RegisterReceipt";
	}


	public function getReceiptJson( $orderId ) {

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

		$orderSumProductDiscount = 0;
		$orderSumCouponDiscount = 0;

		foreach ( $orderObject->get_items() as $orderLineId => $productOrderData ) {

			$productData = wc_get_product( $productOrderData->get_data()['product_id'] );
			$productSku = $productData->get_data()['sku'];
			$LineTotal = floatval( $productOrderData->get_data()['total'] );
			$LineSubtotal = floatval( $productOrderData->get_data()['subtotal'] );
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

				$productSku = $variation->get_sku();
			}
			// if product
			else {
				$regularPrice = floatval( $productData->get_regular_price() );
				if(!$regularPrice) {
					$regularPrice = floatval( $productData->get_price() );
				}
				$listPrice = floatval( $productData->get_sale_price() );
				if(!$listPrice) {
					$listPrice = floatval( $productData->get_price() );
				}
			}


			// Start Support plugin currency switcher data
			$currencySwitchPenRegularPrice =  $productData->get_meta('_regular_currency_prices');
			$currencySwitchPenSalePrice = $productData->get_meta('_sale_currency_prices');

			if($currencySwitchPenRegularPrice) {
				$currencySwitchPenRegularPriceFloat = floatval( json_decode( $currencySwitchPenRegularPrice )->PEN );
				$regularPrice = $currencySwitchPenRegularPriceFloat;
			}
			if($currencySwitchPenSalePrice) {
				$currencySwitchPenSalePriceFloat = floatval( json_decode( $currencySwitchPenSalePrice )->PEN );
				$listPrice = $currencySwitchPenSalePriceFloat;
			}
			// End Support plugin currency switcher data


			$salePrice = $LineTotal/$LineQuantity;


			// Product facade discount
			$unitProductDiscountPrice = $regularPrice-$listPrice;
			$lineProductDiscountPrice = $unitProductDiscountPrice*$LineQuantity;
			$LineProductDiscountPercent = 0;
			if($unitProductDiscountPrice!==0) {
				$lineProductDiscountPercent = ($unitProductDiscountPrice*100)/$regularPrice; 
			}

			// Product coupons and others disccounts
			$unitCouponDiscountPrice = $listPrice-$salePrice;
			$lineCouponDiscountPrice = $unitCouponDiscountPrice*$LineQuantity;
			$lineCouponDiscountPercent = 0;
			if($unitCouponDiscountPrice!==0) {
				$lineCouponDiscountPercent = ($unitCouponDiscountPrice*100)/$listPrice;
			}

			$lineSumRegularPrice = $regularPrice*$LineQuantity;


			// Add before each non 0 element
			if ($index !== 0) {
				$productsList .= ',';
			}

			$productsList .= '
				{
					"Product_Id": "'.$productSku.'",
					"Order_Id": "'.$orderId.'",
					"Product_Line_Quantity": '.$LineQuantity.',
					"Product_Original_Price": '.$regularPrice.',
					"Product_Unit_Price": '.$salePrice.',
					"Product_Line_Total_Price" : '.$lineSumRegularPrice.',
					"Product_Line_Total_Sale" : '.$LineTotal.',
					"Product_Line_Product_Discount_Amount": '.$lineProductDiscountPrice.',
					"Product_Line_Product_Discount_Percentage": '.$lineProductDiscountPercent.',
					"Product_Line_Coupon_Discount_Amount": '.$lineCouponDiscountPrice.',
					"Product_Line_Coupon_Discount_Percentage": '.$lineCouponDiscountPercent.'
				}
			';
			$index++;
			$orderSumProductDiscount += $lineProductDiscountPrice;
			$orderSumCouponDiscount += $lineCouponDiscountPrice;

		}

		$typeReceipt = get_post_meta($orderId, '_billing_document_type', true);
		$orderCustomerIdentifier = get_post_meta($orderId, '_billing_identifier', true);
		$orderCustomerIdentifierType = get_post_meta($orderId, '_billing_identifier_type', true);

		$orderCustomerIdentifierTypeFormated = "";


		if($orderCustomerIdentifierType=="DNI" || $orderCustomerIdentifierType=="1" ) {
			$orderCustomerIdentifierTypeFormated = "1";
		}
		if($orderCustomerIdentifierType=="C. DE EXTRANJERÍA" || $orderCustomerIdentifierType=="4" ) {
			$orderCustomerIdentifierTypeFormated = "4";
		}
		if($orderCustomerIdentifierType=="RUC" || $orderCustomerIdentifierType=="6" ) {
			$orderCustomerIdentifierTypeFormated = "6";
		}



		$joinedAddress = $orderObject->get_billing_address_1().'-'.$orderObject->get_billing_address_2().'-'.$orderObject->get_billing_city().'-'.$orderObject->get_billing_country();


		$orderSyncJson = '{
			"Customer": {
				"Customer_Id": "'.$orderCustomerIdentifier.'",
				"Address": "'.$joinedAddress.'",
				"Customer_Id_Type": "'.$orderCustomerIdentifierTypeFormated.'",
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
					"Order_Discount_Subtotal_Amount": '.( $orderSumProductDiscount+$orderSumCouponDiscount ).',
					"Order_Shipping_Subtotal_Amount": '.$orderData['shipping_total'].',
					"Order_Total_Amount": '.$orderData['total'].', 
					"Order_Currency_Type": "'.$currencyStarsoft.'",
					"Order_Discount_Product_Amount": '.$orderSumProductDiscount.', 
					"Order_Discount_Coupon_Amount": '.$orderSumCouponDiscount.',
					"Order_Gloss": "Pedidos Wordpress - '.$orderObject->get_id().'",
					"Order_Address": "'.$joinedAddress.'",
					"Type_Receipt": "'.$typeReceipt.'"
				},
				"orderDetails": [
					'.$productsList.'
				]
			}
		}';
		return $orderSyncJson;
	}


	public function setReceipt( $orderId ) {

		$settingsDatabase = new SettingsDatabase;
		$token = $settingsDatabase->getToken();
		
		$orderSyncJson = $this->getReceiptJson( $orderId );
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

		if( is_wp_error( $result ) ) {
			return false;
		}
		if( $result['response']['code'] == 200 ) {
			// var_dump($result['body']);
			if($result['body'] == "true") {
				return true;
			}
		}

		return false;
	}
}
