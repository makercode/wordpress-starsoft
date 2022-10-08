<?php 

// Detect when order change to completed
function mysite_woocommerce_order_status_completed( $order_id ) {
  $info = [
    'Paid' => 1,
    'Cancelled' => 0
  ];

  $invoicesDatabase = new InvoicesDatabase;
  $result = $invoicesDatabase->updateInvoice( $info, $order_id );

  return $result;
}
add_action( 'woocommerce_order_status_completed', 'mysite_woocommerce_order_status_completed', 10, 1 );


// Detect when order change to refunded
function action_woocommerce_order_refunded( $order_id, $refund_id ) {
  $info = [
    'Cancelled' => 1
  ];

  $invoicesDatabase = new InvoicesDatabase;
  $result = $invoicesDatabase->updateInvoice( $info, $order_id );

  return $result;
}
add_action( 'woocommerce_order_refunded', 'action_woocommerce_order_refunded', 10, 2 );


// Detect when order change to processing
function action_woocommerce_order_processing( $order_id ) {
  $info = [
    'Paid' => 0,
    'Cancelled' => 0
  ];

  $invoicesDatabase = new InvoicesDatabase;
  $result = $invoicesDatabase->updateInvoice( $info, $order_id );

  return $result;
}
add_action( 'woocommerce_order_status_processing', 'action_woocommerce_order_processing' );