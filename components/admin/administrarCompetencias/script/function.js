function showForm(formSaraData){
	$.ajax({
	type: 'GET',
	url: formSaraData,
	/*data: "&xajax="+opcion+"&valor="+valor,*/
	success: function(respuesta) {
		$("#main .container-fluid").html(respuesta);
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

function changeGrado(grado) {
	if(grado != 1 ) {
		$("#desempenios").show();
	} else {
		$("#desempenios").hide();
	}
}



