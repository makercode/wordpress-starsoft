<?php 

class InvoicesApi {

  public function __construct() {
    $this->apiRegisterUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterOrder";
  }

  public function setInvoice ( $post_id ) {
	$order = wc_get_order($post_id);

	$order_identifier = get_post_meta($post_id, '_billing_identifier', true);

	$products_list = "";

	foreach ( $order->get_items() as $item_id => $item ) {
		if ($item_id !== 0) {
			$products_list .= ',';
		}
		$discount_money = $item->get_date_on_sale_from()-$item->get_date_on_sale_to();
		$discount_percent = ($discount_money*100)/$item->get_subtotal();

	    $products_list .= '
			{
				"Order_number": "'.$post_id.'", // id de orden
				"Identifier_Product": "'.$item->get_sku().'",
				"Quantity": '.$item->get_quantity().',
				"Price_Sale": '.$item->get_sale_price().',
				"Subtotal" : '.$item->get_subtotal().', // unid * cant
				"Discount_Value": '.$discount_item.', // Descuento total
				"Percentage_Discount": '.$discount_percent.' // Descuento porcentaje total
			}
	    ';
	}

  	$json_data = '{
	  "Client": {
	    "Identifier": "'.$order_identifier.'",
	    "Address": "Av. Naranjal 1584 - Los Olivos",
	    "Document_Type": "1",
	    "Document_Identification": "'.$order_identifier.'",
	    "Business_Name": "", //razon s
	    "First_Name": "Desde",
	    "Second_Name": "Levento",
	    "Last_Name": "Moreno",
	    "Last_Mother_Name": "Sialer",
	    "Number_Ruc": "",
	    "Email": "jmoreno@starsoft.com.pe"
	  },
	  "OrderStarsoft": {
	    "OrderHeader": {
	      "Order_Number": "1", // pedido numero id
	      "Sale_Date": "20221027",
	      "Total_Amount": 50, // precio final
	      "Currency": "MN",
	      "Discount_Value": 0, // descuento porsiaca
	      "Gloss": "Envio Wordpress Api",
	      "Address": "Av. Naranjal 1584 - Los Olivos"
	    },
	    "orderDetails": [
	    	'.$products_list.'
	    ]
	  }
	}';

    $result = wp_remote_post(
      $this->apiRegisterUrl,
      array(
        'method' => 'POST',
        'headers' => array(
            'Authorization' => 'Bearer xxx',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ),
        'body' => $json_data
      )
    );
    return $result['body'];
	/*
    */
  }

}
