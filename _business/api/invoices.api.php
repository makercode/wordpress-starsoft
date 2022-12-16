<?php 

class InvoicesApi {

	public function __construct() {
		$this->apiUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterOrder";
		// $this->apiUrl = "http://192.168.1.108:8063/Api/RegisterOrder";
	}

	public function getInvoiceJson( $invoiceId ) {

		// return db field if exist
		$invoicesDatabase = new InvoicesDatabase();
		$order = $invoicesDatabase->getInvoice("{$invoiceId}");
		// var_dump($invoicesDatabase);

		if ( sizeof($order)>=1 ) {
			$json_data = $order['0']['OrderJson'];
			return $json_data;
		}

		// calculate json from actual 
		$order_object = wc_get_order( $invoiceId );
		$order_data = $order_object->get_data();

		$currency = $order_object->get_currency();
		$currency_starsoft = 'MN';
		if($currency!=='PEN') {
			$currency_starsoft = 'ME';
		}

		$products_list = '';
		$index = 0;

		$order_total_discount_price = 0;

		foreach ( $order_object->get_items() as $product_id => $product_order_data ) {
			$product_data = new WC_Product( $product_order_data->get_data()['product_id'] );
			$quantity_order_line = $product_order_data->get_data()['quantity'];

			if ($index !== 0) {
				$products_list .= ',';
			}

			$unit_sale_discount_price = intval($product_data->get_regular_price()) - intval($product_data->get_price());
			// var_dump( intval($product_data->get_price()) );


			// $total_line_sale_discount_price = 0;
			$total_line_sale_discount_price = $unit_sale_discount_price*$quantity_order_line;


			$total_regular_price =  $product_order_data->get_data()['total']+$total_line_sale_discount_price;
			
			$unit_sale_price = $product_order_data->get_data()['total']/$product_order_data->get_data()['quantity'];
			$unit_regular_price = $total_regular_price/$quantity_order_line;

			$total_line_sale_discount_percent = $unit_sale_discount_price*100/$unit_regular_price;
			// var_dump($unit_sale_discount_price);
			// var_dump($unit_sale_price);

			$products_list .= '
				{
					"Product_Id": "'.$product_data->get_sku().'",
					"Order_Id": "'.$product_order_data->get_data()['order_id'].'", // id de orden
					"Product_Line_Quantity": '.$product_order_data->get_quantity().',
					"Product_Unit_Price": '.$unit_regular_price.',
					"Product_Line_Total_Price" : '.$total_regular_price.', // unid * cant
					"Product_Line_Discount_Amount": '.$total_line_sale_discount_price.', // Descuento total
					"Product_Line_Discount_Percentage": '.$total_line_sale_discount_percent.' // Descuento porcentaje total
				}
			';
			// var_dump($products_list);
			$index++;
			$order_total_discount_price += $total_line_sale_discount_price;
		}

		$order_customer_identifier = get_post_meta($invoiceId, '_billing_identifier', true);
		$order_customer_identifier_type = get_post_meta($invoiceId, '_billing_identifier_type', true);

		$joined_address = $order_object->get_billing_address_1().'-'.$order_object->get_billing_address_2().'-'.$order_object->get_billing_city().'-'.$order_object->get_billing_country();

		// var_dump($order_object);
		// var_dump($order_object->get_subtotal());
		// var_dump($order_object->get_shipping_total());

		$json_data = '{
			"Customer": {
				"Customer_Id": "'.$order_customer_identifier.'",
				"Address": "'.$joined_address.'",
				"Customer_Id_Type": "'.$order_customer_identifier_type.'", // 1 = dni
				"Customer_Id_Number": "'.$order_customer_identifier.'",
				"Business_Name": "'.$order_object->get_billing_company().'", //razon social
				"First_Name": "'.$order_object->get_billing_first_name().'",
				"Second_Name": "",
				"First_Surname": "'.$order_object->get_billing_last_name().'",
				"Second_Surname": "",
				"Email": "'.$order_object->get_billing_email().'"
			},
			"OrderStarsoft": {
				"OrderHeader": {
					"Order_Id": "'.$order_object->get_id().'", // order id
					"Order_Date": "'.$order_object->get_date_created()->getTimestamp().'",
					"Order_Subtotal_Amount": '.$order_object->get_subtotal().', // precio sin shipping
					"Order_Disccount_Subtotal_Amount": '.$order_data['discount_total'].', // Descuento
					"Order_Shipping_Subtotal_Amount": '.$order_data['shipping_total'].', // Descuento
					"Order_Shipping": '.$order_object->get_shipping_total().',
					"Order_Total_Amount": '.$order_data['total'].', // precio final
					"Order_Currency_Type": "'.$currency_starsoft.'",
					"Order_Discount_Amount": '.$order_total_discount_price.', // descuento porsiaca
					"Order_Gloss": "Pedidos Wordpress - '.$order_object->get_id().'",
					"Order_Address": "'.$joined_address.'"
				},
				"orderDetails": [
					'.$products_list.'
				]
			}
		}';
		var_dump($json_data);
		return $json_data;
	}

	public function setInvoice( $invoiceId ) {

		$settingsDatabase = new SettingsDatabase;
		$token = $settingsDatabase->getToken();
		
		$json_data = $this->getInvoiceJson( $invoiceId );
		$result = wp_remote_post(
			$this->apiUrl,
			array(
				'method' => 'POST',
				'headers' => array(
					'Authorization' =>  "Bearer {$token}",
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				),
				'body' => $json_data
			)
		);
		// var_dump($result);
		if( !is_wp_error( $result ) ) {
			if( $result['body'] ) {
				return $result['body'];
			}
		}
		return false;
	}

}
