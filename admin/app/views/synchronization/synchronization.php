<?php
	// get invoices
	$invoicesDatabase = new InvoicesDatabase();
	$invoicesArray = $invoicesDatabase->getInvoices();

?>
<?php // print_r($order_object); ?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		Sincronización de pedidos para clientes Starsoft
	</h1>
	<?php include dirname(__file__).'/../../includes/stepbar.php'; ?>
	<div class="">
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
				<!--
				<th>
					Tipo de comprobante
				</th>
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

						$orderSync = ($invoice['OrderSync']) ? "SI": "NO";

						$receiptType = 'Boleta';
						if( $invoice['ReceiptType']=='1' ) { $receiptType = 'Factura'; };

						$receiptNumber = ($invoice['ReceiptNumber']) ? "SI": "NO";

						$receiptState = ($invoice['ReceiptState']) ? "SI": "NO";

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
								{$orderSync}
							</td>
							<!--
							<td>
								{$receiptType}
							</td>
							<td>
								{$receiptNumber}
							</td>
							<td>
								{$receiptState}
							</td>
							-->
						</tr>
						";
					};
				?>
			</tbody>
		</table>
	</div>
</div>