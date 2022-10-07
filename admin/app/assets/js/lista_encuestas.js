jQuery(document).ready( function($) {
	
	console.log('rdy4pty');
	console.log(SolicitudesAjax);

	/* Agregar dinamicamente preguntas a la encuesta desde el administrador de wordpress */
	let count = 0;

	function plantilla ( index ) {
		return `
	      <tr class="question question-${index}">
	        <td>
	          <label class="me-2" for="q-${index}" class="col-form-label col">
	            Pregunta ${index}
	          </label>
	        </td>
	        <td>
	          <div class="me-2">
	            <input type="text" name="name[]" id="q-${index}" class="form-control" name_list>
	          </div>
	        </td>
	        <td>
	          <div class="me-2">
	              <select name="type[]" id="type" class="form-control" type_list>
	                <option value="1" selected>
	                  SI/NO
	                </option>
	                <option value="2">
	                  RANGO
	                </option>
	              </select>
              </div>
            </td>
	        <td class="action-column">
	          <a href="#" class="btn btn-danger js-remove">
	            X
	          </a>
	        </td>
	      </tr>
		`;
	}

	$("#add").click( function (e) {
		count++;
		$('#dynamic-fields').append(plantilla(count));
		return false;
	})

	$( "#dynamic-fields" ).on( "click", ".js-remove", function(e) {
		$(this).closest(".question").remove();
		return false;
	})

	$( document ).on( "click", ".js-remove-survey", function(e) {
		let id = $(this)[0].dataset.id;
		let url = SolicitudesAjax.url;
		$.ajax({
			type: "POST",
			url: url,
			data: {
				action: "peticioneliminar",
				nonce: SolicitudesAjax.seguridad,
				id: id
			},
			success: function (data) {
				alert("Datos borrados");
				console.log(data)
				location.reload();
			}
		})
		return false;
	})


});