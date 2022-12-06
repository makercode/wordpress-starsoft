<?php
	// get invoices
	$invoicesDatabase = new InvoicesDatabase();
	$invoicesArray = $invoicesDatabase->getInvoices();

?>
<?php // print_r($order_object); ?>
<div class="stepper">
  <div class="stepbar">
    <div href="#" class="step step-login active">
      <span class="step-position">
        ✓
      </span>
      Identificado
    </div>
    <div href="#" class="step step-validation active">
      <span class="step-position">
        ✓
      </span>
      Validado
    </div>
    <div href="#" class="step step-synchronization active">
      <span class="step-position">
        ✓
      </span>
      Sincronizado
    </div>
  </div>
</div>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo get_admin_page_title(); ?>
	</h1>
	<table class="wp-list-table widefat fixed striped pages">
	<thead>
		<th style="width:5%">
			ID
		</th>
		<th>
			Fecha
		</th>
		<th>
			Nro de Orden WordPress
		</th>
		<th>
			Tipo de documento de Comprador
		</th>
		<th>
			Numero de documento de Comprador
		</th>
		<th>
			Pedido completo
		</th>
		<th>
			Pedido sincronizado a Starsoft
		</th>
		<th>
			Tipo de comprobante
		</th>
		<!--
		<th>
			Numero de comprobante
		</th>
		<th>
			Estado de comprobante
		</th>
		-->
	</thead>
	<tbody>
		<?php 
			// var_dump($invoicesArray);
			foreach ($invoicesArray as $key_invoice => $invoice) {
				$id = $invoice['InvoiceId'];

				$date = date('m/d/Y', $invoice['OrderDate']);

				$orderid = $invoice['OrderId'];

				$customeridtype = 'DNI';
				if( $invoice['CustomerIdType']=='4' ) { $customeridtype = 'CARNET DE EXTRANJERÍA'; };
				if( $invoice['CustomerIdType']=='6' ) { $customeridtype = 'RUC'; };

				$customerid = $invoice['CustomerId'];

				$orderState = "Pendiente";
				if( $invoice['OrderState']==  '1' ) { $orderState = 'Completo'; };
				if( $invoice['OrderState']== '-1' ) { $orderState = 'Reembolsado'; };

				$ordersync = ($invoice['OrderSync']) ? "SI": "x";

				$documenttype = 'Boleta';
				if( $invoice['DocumentType']=='1' ) { $documenttype = 'Factura'; };

				$documentnumber = ($invoice['DocumentNumber']) ? "SI": "x";

				$documentstate = ($invoice['DocumentState']) ? "SI": "x";

				echo "
				<tr>
					<td>
						{$id}
					</td>
					<td>
						{$date}
					</td>
					<td>
						{$orderid}
					</td>
					<td>
						{$customeridtype}
					</td>
					<td>
						{$customerid}
					</td>
					<td>
						{$orderState}
					</td>
					<td>
						{$ordersync}
					</td>
					<td>
						{$documenttype}
					</td>
					<!--
					<td>
						{$documentnumber}
					</td>
					<td>
						{$documentstate}
					</td>
					-->
				</tr>
				";
			};
		?>
	</tbody>
	</table>
</div>
