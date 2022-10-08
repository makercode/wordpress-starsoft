<?php 

// Detect when order created
function order_created( $order_id ) {
  global $wpdb;

  // Getting an instance of the order object
  $order       = wc_get_order( $order_id );
  $order_data  = $order->get_data();

  $orderid     = $order->get_id();
  $dniorruc    = get_post_meta($order_id, '_billing_identifier', true);
  $date        = $order_data['date_created']->getTimestamp();
  $paid        = ( $order->has_status('completed') ) ? 1 : 0 ;
  $sync        = 0;
  $valid       = 0;

  $table = "{$wpdb->prefix}sync_invoices";
  $info = [
    'OrderId'    => $orderid,
    'CustomerId' => $dniorruc,
    'Date'       => $date,
    'Paid'       => $paid,
    'Sync'       => $sync,
    'Valid'      => $valid
  ];

  $response = $wpdb->insert($table,$info);
  return $response;
}
add_action('woocommerce_new_order', 'order_created', 10, 1);

// Save the custom billing fields (once order is placed)
function save_custom_billing_fields( $order, $data ) {
  if ( isset( $_POST['billing_identifier'] ) && ! empty( $_POST['billing_identifier'] ) ) {
    $order->update_meta_data('_billing_identifier', sanitize_text_field( $_POST['billing_identifier'] ) );
    update_user_meta( $order->get_customer_id(), 'billing_identifier', sanitize_text_field( $_POST['billing_identifier'] ) );
  }
}
add_action( 'woocommerce_checkout_create_order', 'save_custom_billing_fields', 20, 2 );
