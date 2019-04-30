
function showForm(formSaraData){

	/*var valor=$(campo).val();*/
	$.ajax({
	type: 'GET',
	url: formSaraData,
	/*data: "&xajax="+opcion+"&valor="+valor,*/
	success: function(respuesta) {
		$("#main .container-fluid").html(respuesta);
	}
	});
}

function getMunicipio(id,formSaraData,selector){
  	$.ajax({
      type: 'GET',
      url: formSaraData,
      data: "&id="+id,
      success: function(json) {
      $(selector).html("");
      json = JSON.parse(json); 
        $.each(json, function(i, value) {
            $(selector).append($('<option>').text(value).attr('value', value));
        });
	}
	});
}

function Delete(formSaraData,location){

	/*var valor=$(campo).val();*/
	$.ajax({
	type: 'GET',
	url: formSaraData,
	success: function(respuesta) {

		if(respuesta=="true"){
			alert("El usuario se elimino correctamente");
		}else{
			alert("El usuario no pudo ser eliminado, intente mas tarde");

		}

		
	}
	});

	location.reload();
}

