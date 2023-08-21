<?php

function action_public_enqueue_scripts( $hook ) {
	wp_enqueue_style(
		'publicCss',
		plugins_url('/assets/css/styles.css',__FILE__)
	);
};
add_action('wp_enqueue_scripts','action_public_enqueue_scripts');



function action_condition_checkout() {
	global $wpdb;
	if ( is_checkout() && empty( $wpdb->query_vars['order-pay'] ) && ! isset( $wpdb->query_vars['order-received'] ) ) {
		echo '
			<script>
				function resetIdentifierTypeValue() {
					jQuery("#billing_identifier_type").val("");
				}
				function resetIdentifierValue() {
					jQuery("#billing_identifier").val("");
				}
				function checkDocumentType() {
					$identifier_type_input = jQuery("#billing_identifier_type");
					$document_type_input = jQuery("#billing_document_type");
					$document_type = $document_type_input.val();

					if($document_type == "1" || $document_type == "FACTURA" ) {
						$identifier_type_input.html(`
							<option value="6">RUC</option>
						`)
					} else {
						$identifier_type_input.html(`
							<option value="-">ANONIMO</option>
							<option value="1" selected="selected">DNI</option>
							<option value="6">RUC</option>
							<option value="4">C. DE EXTRANJERÍA</option>
						`)
					}
				}
				function checkIdentifierType() {
					$identifier_type_input = jQuery("#billing_identifier_type");
					$identifier_type = $identifier_type_input.val();

					if($identifier_type == "-" || $identifier_type == "ANONIMO" || $identifier_type == "") {
						jQuery("#billing_identifier_field").hide();
						jQuery("#billing_company").val("");
						jQuery("#billing_company_field").hide();
						jQuery("#billing_identifier").removeAttr("required");
					} else {
						jQuery("#billing_company").val("");
						jQuery("#billing_company_field").hide();
						jQuery("#billing_identifier").attr("required", "required");

						if($identifier_type == "1" || $identifier_type == "DNI") {
							jQuery("#billing_identifier").attr("Placeholder", "Nro de DNI");
							jQuery("#billing_identifier").attr("minlength", "8");
							jQuery("#billing_identifier").attr("maxlength", "8");
						}
						if($identifier_type == "4" || $identifier_type == "CARNET DE EXTRANJERÍA" || $identifier_type == "C. DE EXTRANJERÍA") {
							jQuery("#billing_identifier").attr("Placeholder", "Nro de CARNET DE EXTRANJERÍA");
							jQuery("#billing_identifier").attr("minlength", "6");
							jQuery("#billing_identifier").attr("maxlength", "15");
						}
						if($identifier_type == "6" || $identifier_type == "RUC") {
							jQuery("#billing_identifier").attr("Placeholder", "Nro de RUC");
							jQuery("#billing_identifier").attr("minlength", "11");
							jQuery("#billing_identifier").attr("maxlength", "11");
							jQuery("#billing_company_field").show();
						}

						jQuery("#billing_identifier_field").show();
					}
				}
				jQuery("#billing_document_type").change( function() {
					resetIdentifierTypeValue();
					resetIdentifierValue();
					checkDocumentType();
					checkIdentifierType();
				});
				jQuery("#billing_identifier_type").change( function() {
					resetIdentifierValue();
					checkIdentifierType();
				});
				jQuery( document ).ready(
					function() {
						// resetIdentifierValue();
						// checkDocumentType();
						checkIdentifierType();
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

	if (get_option('woocommerce_currency') == "PEN") {
		
		$settingsDatabase = new SettingsDatabase;
		$typeDocument = $settingsDatabase->getDocumentType();

		if($typeDocument=='1') {
			$billing_fields['billing_document_type'] = array(
				'type'    		=> 'select',
				'label'   		=> __('Tipo de Comprobante'),
				'class'   		=> array('form-row-wide'),
				'priority'		=> 1001,
				'options' 		=> array(
					'3'			=> __( 'BOLETA'					, '3' ),
					'1'			=> __( 'FACTURA'   				, '1' )
				),
				'required'		=> true,
				'clear'   		=> true,
			);
		}
		
		$billing_fields['billing_identifier_type'] = array(
			'type'    		=> 'select',
			'label'   		=> __('Tipo de identificación'),
			'class'   		=> array('form-row-wide'),
			'priority'		=> 1002,
			'options' 		=> array(
				'-'				=> __( 'ANONIMO'  , '-' ),
				'1'				=> __( 'DNI'					, '1' ),
				'6'				=> __( 'RUC'   					, '6' ),
				'4'				=> __( 'C. DE EXTRANJERÍA'		, '4' )
			),
			'required'		=> true,
			'clear'   		=> true,
		);

		$billing_fields['billing_identifier'] = array(
			'type'       	=> 'text',
			'label'      	=> __('Numero de identificación'),
			'class'      	=> array('form-row-wide'),
			'priority'   	=> 1003,
			'placeholder'	=> "DNI/RUC/CE",
			'maxlength'  	=> 15,
			'required'   	=> true,
			'clear'      	=> true,
		);

		$billing_fields['billing_company']['priority'] = 1004;

	}
	return $billing_fields;
}


add_filter( 'woocommerce_billing_fields', 'display_identifier_billing_field', 20, 1 );


