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
add_action('woocommerce_admin_process_product_object', 'mandatory_product_sku');


*/
// add_action('woocommerce_admin_process_variation_object', 'mandatory_product_sku');

function wpb_admin_notice_warn() {

	if ( array_key_exists('page', $_GET) ) {
		if ($_GET["page"] == 'starsoft/admin/app/views/index.php') {
			return false;
		}
	}

	$validatedGuard = new ValidatedGuard();
	if ($validatedGuard->isValidated()=="0") {
		echo '<div class="notice notice-error is-dismissible">
			<p> STARSOFT PEDIDOS: Para poder sincronizar tus pedidos debes terminar de configurar el plugin </p>
		</div>'; 
	}
}
add_action( 'admin_notices', 'wpb_admin_notice_warn' );



function action_admin_enqueue_scripts_css( $hook ) {

	wp_enqueue_style(
		'adminCss',
		plugins_url('/assets/css/styles.css',__FILE__)
	);
};
add_action('admin_enqueue_scripts','action_admin_enqueue_scripts_css');



function action_admin_enqueue_scripts_js($hook) {

	wp_enqueue_script(
		'outterJs',
		plugins_url('/assets/js/login.js?v1.7',__FILE__),
		array('jquery')
	);
	wp_localize_script(
		'outterJs',
		'AjaxRequest',
		[
			'url' 	=> admin_url('admin-ajax.php'),
			'token' => wp_create_nonce('seg')
		]
	);
};
add_action('admin_enqueue_scripts','action_admin_enqueue_scripts_js');





/*
*************************************

			Admin fields

*************************************
*/

function bbloomer_billing_sales_checkout_display( $order ) {

	$billing_identifier_type = $order->get_meta( '_billing_identifier_type' );
	?>
		<div class="address">
			<p <?php if( ! $billing_identifier_type ) { echo ' class="none_set"'; } ?>>
				<strong>Tipo de Identificación( DNI:1 CE:4 RUC:6 ):</strong>
				<?php echo $billing_identifier_type ? esc_html( $billing_identifier_type ) : 'Ningun tipo de identificación.' ?>
			</p>
		</div>
		<div class="edit_address">
			<?php
				woocommerce_wp_select( array(
					'id' => '_billing_identifier_type',
					'label' => 'Tipo de Identificación',
					'wrapper_class' => 'form-field-wide',
					'value' => $billing_identifier_type,
					'options' => array(
						'-' => '-',
						'1' => 'DNI',
						'6' => 'RUC',
						'4' => 'CARNET DE EXTRANJERÍA'
					)
				) );
			?>
		</div>
	<?php


	$billing_identifier = $order->get_meta( '_billing_identifier' );
	?>
		<div class="address">
			<p <?php if( ! $billing_identifier ) { echo ' class="none_set"'; } ?>>
				<strong>Numero de Identificación:</strong>
				<?php echo $billing_identifier ? esc_html( $billing_identifier ) : 'Ningun tipo de identificación.' ?>
			</p>
		</div>
		<div class="edit_address">
			<?php
				woocommerce_wp_text_input( array(
					'id' => '_billing_identifier',
					'label' => 'Numero de Identificación',
					'wrapper_class' => 'form-field-wide',
					'value' => $billing_identifier
				) );
			?>
		</div>
	<?php
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'bbloomer_billing_sales_checkout_display' );



function misha_save_general_details( $order_id ) {
	
	update_post_meta( $order_id, '_billing_identifier', wc_clean( $_POST[ '_billing_identifier' ] ) );
	update_post_meta( $order_id, '_billing_identifier_type', wc_clean( $_POST[ '_billing_identifier_type' ] ) );
	
}
add_action( 'woocommerce_process_shop_order_meta', 'misha_save_general_details' );
