<?php 

class InvoicesApi {

  public function __construct() {
    $this->apiRegisterUrl = "http://www.starsoftweb.com/ApiWooCommerce/Api/RegisterOrder";
  }

  public function setInvoice ( $post_data ) {
	$order = wc_get_order($post_data);

  	$json_data = "{
	  'Client': {
	    'Identifier: '{$ok}',
	    'Address: "Av. Naranjal 1584 - Los Olivos",
	    'Document_Type: "1",
	    'Document_Identification: "44381344",
	    'Business_Name: "",
	    'First_Name: "Jorge",
	    'Second_Name: "Armando",
	    'Last_Name: "Moreno",
	    'Last_Mother_Name: "Sialer",
	    'Number_Ruc: "",
	    'Email: "jmoreno@starsoft.com.pe"
	  },
	  'OrderStarsoft': {
	    'OrderHeader': {
	      'Order_Number': "1", 
	      'Sale_Date': "20221027", 
	      'Total_Amount': 50,
	      'Currency': "MN", 
	      'Discount_Value': 0, 
	      'Gloss': "Envio Wordpress Api", 
	      'Address': "Av. Naranjal 1584 - Los Olivos" 
	    },
	    'orderDetails': [
	      {
	        'Order_number: "1", 
	        'Identifier_Product: "SK0000001", 
	        'Quantity: 1,
	        'Price_Sale: 25,
	        'Subtotal : 25,
	        'Discount_Value: 0, 
	        'Percentage_Discount: 0 
	      },
	      {
	        'Order_number': "1", 
	        'Identifier_Product': "SK0000002", 
	        'Quantity': 1,
	        'Price_Sale': 25, 
	        'Subtotal' : 25,
	        'Discount_Value': 0, 
	        'Percentage_Discount': 0 
	      }
	    ]
	  }
	}";

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
