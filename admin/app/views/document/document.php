<?php 
	$settingsDatabase = new SettingsDatabase;
	$isChoosed = $settingsDatabase->isChoosed();

	$document_type_value;
	if( isset($_POST['document_type']) ) {
		$document_type_value=$_POST['document_type'];
		// echo $document_type_value;
		$settingsDatabase->setDocumentType($document_type_value);
		$settingsDatabase->setTrueChoosed();
	}


	$capabilities = ['1'];

?>

<div class="wrap">
	<h1 class="wp-heading-inline">
		Sincronización de pedidos para clientes Starsoft
	</h1>
	<?php include dirname(__file__).'/../../includes/stepbar.php'; ?>
	<?php if( !isset($_POST['document_type']) ) { ?>
		<div class="choose-content">
			<form method="post" action="">
				<div class="document-type-choose">
					<?php if (in_array('0',$capabilities,true)) { ?>
						<div class="document-type-choose-item">
							<input type="radio" name="document_type" value="0" id="order" class="document_type" checked='checked'/> 
							<label for="order">
								Enviar como <br>
								<b>Pedido Starsoft</b>
							</label>
						</div>
					<?php } ?>
					<?php if (in_array('1',$capabilities,true)) { ?>
						<div class="document-type-choose-item">
							<input type="radio" name="document_type" value="1" id="receipt" class="document_type"/> 
							<label for="receipt">
								Enviar como <br>
								<b>Factura/Boleta Starsoft</b>
							</label>
						</div>
					<?php } ?>
				</div>
				<div class="document-type-choose-send">
					<button class="primary-button" type="submit">
						Enviar
					</button>
				</div>
			</form>
		</div>
	<?php } else { ?>
		<div>
			<p class="notification">
				Guardando configuración...
			</p>
		</div>
	<?php } ?>
	
</div>
<?php if( isset( $document_type_value ) ) { ?>
	<script type="text/javascript">
		//if sended value then -> reload
		window.location.reload();
	</script>
<?php } ?>