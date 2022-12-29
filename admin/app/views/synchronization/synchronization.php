<?php
	// get invoices
	$documentsDatabase = new DocumentsDatabase( new OrdersDatabaseAdapter );
	$documentsArray = $documentsDatabase->getDocuments();

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
					Nro de Orden WordPress
				</th>
				<th>
					Fecha
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
					foreach ($documentsArray as $keyDocument => $document) {
						var_dump($document);
						$id = $document['InvoiceId'];

						$date = date('m/d/Y', $document['OrderDate']);

						$orderId = $document['OrderId'];

						$customerIdType = 'DNI';
						if( $document['CustomerIdType']=='4' ) { $customerIdType = 'CARNET DE EXTRANJERÍA'; };
						if( $document['CustomerIdType']=='6' ) { $customerIdType = 'RUC'; };

						$customerId = $document['CustomerId'];

						$orderState = "Pendiente";
						if( $document['OrderState']==  '1' ) { $orderState = 'Completo'; };
						if( $document['OrderState']== '-1' ) { $orderState = 'Reembolsado'; };

						$orderSync = ($document['OrderSync']) ? "SI": "NO";

						$receiptType = 'Boleta';
						if( $document['ReceiptType']=='1' ) { $receiptType = 'Factura'; };

						$receiptNumber = ($document['ReceiptNumber']) ? "SI": "NO";

						$receiptState = ($document['ReceiptState']) ? "SI": "NO";

						echo "
						<tr>
							<td>
								{$id}
							</td>
							<td>
								{$orderId}
							</td>
							<td>
								{$date}
							</td>
							<td>
								{$customerIdType}
							</td>
							<td>
								{$customerId}
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