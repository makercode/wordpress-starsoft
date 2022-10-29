<?php 

// SKU required
function mandatory_product_sku( $product ) {
  if( ! $product->get_sku( 'edit' ) ) {
    $message = __( 'El SKU es obligatorio para sincronizar y facturar los pedidos.', 'woocommerce' );
    
    if( $product->get_status('edit') === 'publish' ) {
      $product->set_status('draft');
      $message .= ' ' . __('El producto se envio a "Borrador".', 'woocommerce' );
    }
    WC_Admin_Meta_Boxes::add_error( $message );
  }
}
add_action('woocommerce_admin_process_product_object', 'mandatory_product_sku');
// add_action('woocommerce_admin_process_variation_object', 'mandatory_product_sku');


// revisar esta funcion
function action_save_post( $post_id ) {
  $product = wc_get_product( $post_id );
  if( ! $product->get_sku( 'edit' ) ) {
    $message = __( 'El SKU es obligatorio para sincronizar y facturar los pedidos.', 'woocommerce' );
    
    if( $product->get_status('edit') === 'publish' ) {
      $product->set_status('draft');
      $message .= ' ' . __('El producto se envio a "Borrador".', 'woocommerce' );
    }
    WC_Admin_Meta_Boxes::add_error( $message );
  }
}
add_action( 'save_post', 'action_save_post', 10);

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

function action_admin_enqueue_scripts($hook) {
  wp_enqueue_style(
    'bootstrapCss',
    plugins_url('/assets/css/styles.css',__FILE__)
  );
};
add_action('admin_enqueue_scripts','action_admin_enqueue_scripts');