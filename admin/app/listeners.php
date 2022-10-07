<?php 

require_once dirname(__file__).'/business/services/install.service.php';
require_once dirname(__file__).'/business/services/sync.service.php';


// Detect when plugin is activated
function ActivePlugin() {
  $installService = new InstallService();
  $installService->init();
}
register_activation_hook(__file__, 'ActivePlugin');


// Detect when plugin is disabled
function DisablePlugin() {
}
register_deactivation_hook(__file__, 'DisablePlugin');


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

// Detect when order change to completed
function mysite_woocommerce_order_status_completed( $order_id ) {
  global $wpdb;

  $order       = wc_get_order( $order_id );
  $order_data  = $order->get_data();

  $table = "{$wpdb->prefix}sync_invoices";
  $info = [
    'Paid' => 1
  ];
  $where = [
    'OrderId' => $order_id
  ];

  $updateResult = $wpdb->update( $table, $info, $where );
  var_dump($updateResult);

  return $updateResult;
}
add_action( 'woocommerce_order_status_completed', 'mysite_woocommerce_order_status_completed', 10, 1 );


// Detect when order change to refunded
function action_woocommerce_order_refunded( $order_id, $refund_id )
{ 
  // Your code here
}
add_action( 'woocommerce_order_refunded', 'action_woocommerce_order_refunded', 10, 2 );
