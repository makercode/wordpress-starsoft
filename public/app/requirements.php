<?php

// Saving DNI/RUC in admin order
function display_identifier_billing_field( $billing_fields ) {
  $billing_fields['billing_identifier'] = array(
    'type'        => 'text',
    'label'       => __('Dni/Ruc'),
    'class'       => array('form-row-wide'),
    'priority'    => 25,
    'maxlength'   => 12,
    'required'    => false,
    'clear'       => true,
  );
  return $billing_fields;
}
add_filter( 'woocommerce_billing_fields', 'display_identifier_billing_field', 20, 1 );
