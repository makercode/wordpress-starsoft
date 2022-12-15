<?php 
	// Catch post
	if(isset($_POST['btndraft'])) {
		$postid = $_POST['postid'];
		// echo $postid;
		wp_update_post(array('ID' => intval($postid), 'post_status'   =>  'draft'));

		// Delete deleted old items 
		// Reload if the list is empty (So you will se the configured invoices view) 
		$script = "
			<script>
				jQuery( document ).ready(function() {
					jQuery(`.wp-list-table tr[data-id='{$postid}']`).fadeOut(1000, function() {
						jQuery(this).remove();
						let trLength = jQuery(`.wp-list-table tbody tr`).length;
						console.log(trLength);
						// If not more item, then reload website
						if(trLength<1) {
							location.reload();
						}
					});
					console.log({$postid});
				});
			</script>
		";
		echo $script;
	}
?>

<div class="wrap">
	<h1 class="wp-heading-inline">
		Sincronizacion para clientes Starsoft
	</h1>
	<?php include dirname(__file__).'/../../includes/stepbar.php'; ?>

	<div class="">

		<div class="w-100">
			<p class="">
				Aun no se encontraron los codigos SKU de los siguientes productos en Starsoft, completalos en starsoft o despublicalos en wordpress.
				<a href="javascript:window.location.reload(true)" data-bs-toggle="modal" data-bs-target="#exampleModal" class="page-title-action d-inline-block w-auto m-0 top-0">
					Comprobar nuevamente
				</a>
			</p>
		</div>

		<div class="">
			<table class="wp-list-table widefat fixed striped pages">
				<thead>
					<th>
						SKU
					</th>
					<th>
						Estado
					</th>
					<th width="10%">
						Id de Producto
					</th>
					<th width="10%">
						Id de Variante
					</th>
					<th>
						Acciones
					</th>
				</thead>
				<tbody>
					<?php 
						foreach ($productNotSyncList as $key => $product) {
							echo "
							<tr data-id='{$product->parentid}'>
								<td>
									{$product->sku}
								</td>
								<td>
									{$product->message}
								</td>
								<td>
									{$product->parentid}
								</td>
								<td>
									{$product->postid}
								</td>
								<td>
									<form method='POST'>
										<input type='hidden' value='{$product->parentid}' href='#' name='postid' id='postid'>
										<button href='{$product->postLinkToEdit}' type='submit' name='btndraft' id='btndraft' value='btndraft' class='page-title-action d-inline-block w-auto m-0 top-0'>
											Despublicar
										</button>
									</form>
								</td>
							</tr>
							";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>