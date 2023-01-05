<div class="wrap">
	<h1 class="wp-heading-inline">
		Sincronización de pedidos para clientes Starsoft
	</h1>
	<?php include dirname(__file__).'/../../includes/stepbar.php'; ?>
	<div class="login-content">
		<?php 
			$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		 ?>
		<form action="<?php echo $actual_link ?>" method="post" class="card" id="login-form">

			<div class="field-set">
				<div class="row">
					<div class="col-70">
					    <label for="uname"><b>Licencia Ruc</b></label>
					    <input type="text" placeholder="Ruc" name="ruc" id="ruc-licence" required>
					</div>
					<div class="col-30">
					    <label for="uname"><b>Codigo de Negocio</b></label>
					    <input type="text" placeholder="Codigo" name="code" id="code-licence" required>
					</div>
				</div>

				<div class="field-set">
					<label for="uname"><b>Username</b></label>
					<input type="text" placeholder="Enter Username" name="uname" id="username-licence" required>
				</div>

				<div class="field-set">
					<label for="psw"><b>Password</b></label>
					<input type="password" placeholder="Enter Password" name="password" id="password-licence" required>
				</div>

				<button type="submit" id="login-button" class="primary-button">Ingresar</button>
			</div>

		</form>
	</div>
	
</div>
