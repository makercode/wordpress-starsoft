<?php 

// SKU required
/*
function mandatory_product_sku( $product ) {
	if( ! $product->get_sku() ) {
		$message = __( 'El SKU es obligatorio para sincronizar y facturar los pedidos.', 'woocommerce' );
		
		if( $product->get_status('edit') === 'publish' ) {
			$product->set_status('draft');
			$message .= ' ' . __('El producto se envio a "Borrador".', 'woocommerce' );
		}
		WC_Admin_Meta_Boxes::add_error( $message );
	}
}
add_action('woocommerce_admin_process_product_object', 'mandatory_product_sku');*/
// add_action('woocommerce_admin_process_variation_object', 'mandatory_product_sku');


// Revisar esta funcion
function action_save_post( $post_id ) {
	$message = __( 'El SKU es obligatorio para sincronizar y facturar los pedidos.', 'woocommerce' );

	$product = wc_get_product( $post_id );
	if( !$product->get_sku() ) {
		remove_action( 'save_post', 'action_save_post' );
		wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );
		add_action( 'save_post', 'action_save_post' );
		WC_Admin_Meta_Boxes::add_error( $message );
	}
}
add_action( 'save_post', 'action_save_post', 10);


// Admin orders Billing DNI/RUC editable field and display
function admin_order_billing_identifier_editable_field( $fields ) {
	global $post;
	$order = wc_get_order( $post->ID );
	var_dump($order);
	$fields['identifier_type'] = array(
		'type'        => 'select',
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'label'       => __('Tipo de identificación', 'woocommerce'),
		'options'     => array(
			''			=> __( 'Selecciona un tipo de documento', '' 	),
			'DNI' 		=> __( 'DNI'							, 'DNI' ),
			'RUC'		=> __( 'RUC'   							, 'RUC' ),
			'CE'		=> __( 'Carnet de Extranjería'			, 'CE' 	)
		),
		'style' => ''
	);
	$fields['identifier'] = array(
		'label' => __('DNI/RUC/CE', 'woocommerce'),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => ''
	);

	return $fields;
}
add_filter('woocommerce_admin_billing_fields', 'admin_order_billing_identifier_editable_field');


function action_admin_enqueue_scripts( $hook ) {
	wp_enqueue_style(
		'adminCss',
		plugins_url('/assets/css/styles.css',__FILE__)
	);
};
add_action('admin_enqueue_scripts','action_admin_enqueue_scripts');
