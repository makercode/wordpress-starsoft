<div class="stepper">
	<div class="stepbar">
		<div href="#" class="step step-login processing">
			<span class="step-position">
				*
			</span>
			Identificando...
		</div>
		<div href="#" class="step step-validation">
			<span class="step-position">
				!
			</span>
			Validado
		</div>
		<div href="#" class="step step-synchronization">
			<span class="step-position">
				!
			</span>
			Sincronizado
		</div>
	</div>
</div>
<div class="login-content">
	<h1 class="text-center login-title">
		Exclusivo para clientes Starsoft
		<br>
		<small>
			Ingresa y Configura tu sincronización
		</small>
	</h1>
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

			<button type="submit" id="login-button">Ingresar</button>
		</div>

	</form>
</div>
