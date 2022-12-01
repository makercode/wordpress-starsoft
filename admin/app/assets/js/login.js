jQuery(document).ready( function($) {
	
	console.log('rdy4pty');
	console.log(AjaxRequest);

	/* Agregar dinamicamente preguntas a la encuesta desde el administrador de wordpress */
	let count = 0;

	$( "#login-form" ).submit( function(event) {
		let licence 	= $('#ruc-licence').val();
		let code 		= $('#code-licence').val();
		let username 	= $('#username-licence').val();
		let password 	= $('#password-licence').val();
		console.log("licence");
		console.log(licence);
		console.log(code);
		console.log(username);
		console.log(password);
		let url = AjaxRequest.url;
		$.ajax({
			type: "POST",
			url: url,
			data: {
				action: "starsoftlogin",
				token: AjaxRequest.token,
				licence: licence,
				code: code,
				username: username,
				password: password
			},
			success: function (data) {
				console.log(data);
				location.reload();
			},
		    error: function(XMLHttpRequest, textStatus, errorThrown) {
		        alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		    }
		})
		/**/
		return false;
	})


});