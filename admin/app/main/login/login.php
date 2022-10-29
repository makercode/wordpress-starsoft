
<div class="login-content">
	<h1>
		Ingresa tus datos Starsoft
	</h1>
	<form action="action_page.php" method="post" class="card">

		<div class="field-set">
			<div class="row">
				<div class="col-70">
				    <label for="uname"><b>Licencia Ruc</b></label>
				    <input type="text" placeholder="Ruc" name="uname" required>
				</div>
				<div class="col-30">
				    <label for="uname"><b>Codigo de Negocio</b></label>
				    <input type="text" placeholder="Codigo" name="uname" required>
				</div>
			</div>

			<div class="field-set">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="uname" required>
			</div>

			<div class="field-set">
				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="psw" required>
			</div>

			<button type="submit">Login</button>
		</div>

	</form>
</div>


<style type="text/css">
	
	.row {
		display: flex;
		flex-align: row;
	}
	.col-70 {
		width: 65%;
		margin-right: 5%;
	}
	.col-30 {
		width: 30%;
	}
	.login-content {
		margin: 48px auto;
		max-width: 520px;
	}
	.card {
		margin: 32px auto;
	}
	.card form {
		border:  none;
	}
	form {
	  border: 3px solid #f1f1f1;
	}

	/* Full-width inputs */
	input[type=text], input[type=password] {
	  width: 100%;
	  padding: 12px 20px;
	  margin: 8px 0;
	  display: inline-block;
	  border: 1px solid #ccc;
	  box-sizing: border-box;
	}

	/* Set a style for all buttons */
	button {
	  background-color: #2271b1;
	  color: white;
	  padding: 14px 20px;
	  margin: 8px 0;
	  border: none;
	  cursor: pointer;
	  width: 100%;
	}

	/* Add a hover effect for buttons */
	button:hover {
	  opacity: 0.8;
	}

	/* Extra style for the cancel button (red) */
	.cancelbtn {
	  width: auto;
	  padding: 10px 18px;
	  background-color: #f44336;
	}

	/* Center the avatar image inside this container */
	.imgcontainer {
	  text-align: center;
	  margin: 24px 0 12px 0;
	}

	/* Avatar image */
	img.avatar {
	  width: 40%;
	  border-radius: 50%;
	}

	/* Add padding to containers */
	.container {
	  padding: 16px;
	}

	/* The "Forgot password" text */
	span.psw {
	  float: right;
	  padding-top: 16px;
	}

	/* Change styles for span and cancel button on extra small screens */
	@media screen and (max-width: 300px) {
	  span.psw {
	    display: block;
	    float: none;
	  }
	  .cancelbtn {
	    width: 100%;
	  }
	}

</style>