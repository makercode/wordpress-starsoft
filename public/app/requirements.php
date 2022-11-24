<?php

function action_public_enqueue_scripts( $hook ) {
	wp_enqueue_style(
		'publicCss',
		plugins_url('/assets/css/styles.css',__FILE__)
	);
};
add_action('wp_enqueue_scripts','action_public_enqueue_scripts');



function action_condition_checkout() {
	global $wp;
	if ( is_checkout() && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) ) {
		echo '
			<script>
				function resetIdValue() {
					jQuery("#billing_identifier").val("");
				}
				function checkInput() {
					$value = jQuery("#billing_identifier_type").val();
					if($value == "-") {
						jQuery("#billing_identifier_field").hide();
						jQuery("#billing_company").val("");
						jQuery("#billing_company_field").hide();
						jQuery("#billing_identifier").removeAttr("required");
					} else {
						jQuery("#billing_company").val("");
						jQuery("#billing_company_field").hide();
						jQuery("#billing_identifier").attr("required", "required");

						if($value == "1") {
							jQuery("#billing_identifier").attr("Placeholder", "DNI");
							jQuery("#billing_identifier").attr("minlength", "8");
							jQuery("#billing_identifier").attr("maxlength", "8");
						}
						if($value == "4") {
							jQuery("#billing_identifier").attr("Placeholder", "CARNET DE EXTRANJERÍA");
							jQuery("#billing_identifier").attr("minlength", "6");
							jQuery("#billing_identifier").attr("maxlength", "15");
						}
						if($value == "6") {
							jQuery("#billing_identifier").attr("Placeholder", "RUC");
							jQuery("#billing_identifier").attr("minlength", "11");
							jQuery("#billing_identifier").attr("maxlength", "11");
							jQuery("#billing_company_field").show();
						}

						jQuery("#billing_identifier_field").show();
					}
				}
				jQuery("#billing_identifier_type").change( function() {
					resetIdValue();
					checkInput();
				});
				jQuery( document ).ready( 
					function() {
						checkInput();
						console.log("rdy4pty");
					}
				);
			</script>
		';
   }
}
add_action( 'wp_footer', 'action_condition_checkout', 9999 );



// Saving DNI/RUC in admin order
function display_identifier_billing_field( $billing_fields ) {
	$billing_fields['billing_identifier_type'] = array(
		'type'        => 'select',
		'label'       => __('Tipo de identificación'),
		'class'       => array('form-row-wide'),
		'priority'    => 25,
		'options'     => array(
			'-'			=> __( 'Selecciona un tipo de documento'  , '' ),
			'1' 		=> __( 'DNI'					, 'DNI' ),
			'6'		=> __( 'RUC'   					, 'RUC' ),
			'4'		=> __( 'Carnet de Extranjería'	, 'CE' )
		),
		'required'    => false,
		'clear'       => true,
	);
	$billing_fields['billing_identifier'] = array(
		'type'        => 'text',
		'label'       => __('Numero de identificación'),
		'class'       => array('form-row-wide'),
		'priority'    => 25,
		'placeholder' => "DNI/RUC/CE",
		'maxlength'   => 20,
		'required'    => false,
		'clear'       => true,
	);
	return $billing_fields;
}
add_filter( 'woocommerce_billing_fields', 'display_identifier_billing_field', 20, 1 );


