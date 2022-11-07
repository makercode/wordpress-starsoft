<?php
	// get invoices
	$invoicesDatabase = new InvoicesDatabase();
	$invoicesArray = $invoicesDatabase->getInvoices();

?>
<?php // print_r($order_object); ?>

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
		NRO Orden WP
		</th>
		<th>
		DNI/RUC cliente
		</th>
		<th>
		Completado
		</th>
		<th>
		Sincronizado
		</th>
	</thead>
	<tbody>
		<?php 
		foreach ($invoicesArray as $key => $invoice) {
			$id = $invoice['InvoiceId'];
			$date = date('m/d/Y', $invoice['Date']);
			$orderid = $invoice['OrderId'];
			$customerid = $invoice['CustomerId'];
			$paid = ($invoice['Paid']) ? "SI": "x";
			$sync = ($invoice['Sync']) ? "SI": "x";
			$valid = ($invoice['Valid']) ? "SI": "x";
			$canceled = ($invoice['Canceled']) ? "SI": "x";
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
				{$customerid}
				</td>
				<td>
				{$paid}
				</td>
				<td>
				{$sync}
				</td>
			</tr>
			";
		};
		?>
	</tbody>
	</table>
</div>
