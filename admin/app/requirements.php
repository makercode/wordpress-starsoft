<?php 

// SKU required
function mandatory_product_sku( $product ) {
  if( ! $product->get_sku( 'edit' ) ) {
    $message = __( 'El SKU es onbligatorio para sincronizar y facturar los pedidos.', 'woocommerce' );
    
    if( $product->get_status('edit') === 'publish' ) {
      $product->set_status('draft');
      $message .= ' ' . __('Product has been saved as "DRAFT".', 'woocommerce' );
    }
    WC_Admin_Meta_Boxes::add_error( $message );
  }
}
add_action('woocommerce_admin_process_product_object', 'mandatory_product_sku');
add_action('woocommerce_admin_process_variation_object', 'mandatory_product_sku');


// Admin orders Billing DNI/RUC editable field and display
function admin_order_billing_identifier_editable_field( $fields ) {
  global $the_order;
  $fields['identifier'] = array(
    'label' => __('Dni/Ruc', 'woocommerce'),
    'show'  => true,
    'wrapper_class' => 'form-field-wide',
    'style' => ''
  );

  return $fields;
}
add_filter('woocommerce_admin_billing_fields', 'admin_order_billing_identifier_editable_field');
