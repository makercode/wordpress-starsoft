<?php
	// get invoices
	$settingsGlobal = new SettingsGlobal;
	$documentsDatabase = $settingsGlobal->getDocumentsDatabaseInstance();
	$documentsArray = $documentsDatabase->getDocuments();

	$documentType = $settingsDatabase->getDocumentType();
?>
<?php // print_r($order_object); ?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		Sincronización de pedidos para clientes Starsoft
	</h1>
	<?php include dirname(__file__).'/../../includes/stepbar.php'; ?>
	<div class="">
		<h2 class="wp-heading-inline">
			Sincronización está activa
		</h2>
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<th style="width:5%">
					ID
				</th>
				<th>
					Nro de Orden WordPress
				</th>
				<th>
					Json
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
				<?php if( $documentType=="1" ) { ?>
					<th>
						Tipo de comprobante
					</th>
				<?php } ?>
				<th>
					Pedido sincronizado a Starsoft
				</th>
			</thead>
			<tbody>
				<?php 
					foreach ($documentsArray as $keyDocument => $document) {
						// var_dump($document);
						$id = $document['DocumentSyncId'];

						$date = date('m/d/Y', $document['OrderDate']);

						$orderId = $document['OrderId'];
						$orderJson = $document['OrderJson'];

						$customerIdType = 'Anonimo';
						// var_dump($document['CustomerIdType']);
						if( $document['CustomerIdType']=='1' ) { $customerIdType = 'DNI'; };
						if( $document['CustomerIdType']=='4' ) { $customerIdType = 'CARNET DE EXTRANJERÍA'; };
						if( $document['CustomerIdType']=='6' ) { $customerIdType = 'RUC'; };

						$customerId = $document['CustomerId'];

						$orderState = "Pendiente";
						if( $document['OrderState']==  '1' ) { $orderState = 'Completo'; };
						if( $document['OrderState']== '-1' ) { $orderState = 'Reembolsado'; };

						$orderSync = ($document['OrderSync']) ? "<span class='green-synced'>SI<span>": "NO";

						$receiptType = '';
						
						if( $document['ReceiptType']=='1' ) { $receiptType = 'Factura'; };
						if( $document['ReceiptType']=='3' ) { $receiptType = 'Boleta'; };

						$receiptNumber = ($document['ReceiptNumber']) ? "SI": "NO";

						$receiptState = ($document['ReceiptState']) ? "SI": "NO";

						echo "
						<tr>";

						echo "
							<td>
								{$id}
							</td>
							<td>
								{$orderId}
							</td>
							<td>
								<div style='max-height: 100px; overflow: scroll;'>
									{$orderJson}
								</div>
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
							</td>";

						if( $documentType=="1" ) {
							echo "
							<td>
								{$receiptType}
							</td>
							";
						}

						echo "
							<td>
								{$orderSync}
							</td>";

						echo "
						</tr>";
					};
				?>
			</tbody>
		</table>
	</div>
</div>