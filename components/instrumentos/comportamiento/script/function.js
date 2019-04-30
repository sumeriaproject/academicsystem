
function actualizarNota(formSaraData,obj){

	estudiante = obj.attr('class');
	sum=0;

	nota=obj.val();
	nota=nota.replace(",",".");
	obj.val(nota);

	if(nota!="" && Number(nota)<3){
		alert("La nota minima es 3");
		obj.css("background","red");
		return false;
	}

	obj.css("background","transparent");

	if(Number(obj.val())>5){
		alert("La nota maxima es 5");
		obj.css("background","red");
		return false;
	}

	$.ajax({
		type: 'GET',
		url: formSaraData,
		data: "&estudiante="+estudiante+"&nota="+obj.val(),
		success: function(respuesta) {
			alert(respuesta);
			//respuesta=jQuery.parseJSON(respuesta);
			/*if(typeof respuesta.error=="undefined" || respuesta.error==""){

			}else{

			}*/
		}
	});
}

function actualizarObservacion(formSaraData,obj){

	estudiante = obj.attr('class');
	sum=0;

	nota = obj.val();

	obj.css("background","transparent");

	$.ajax({
		type: 'GET',
		url: formSaraData,
		data: "&estudiante="+estudiante+"&obs="+obj.val(),
		success: function(respuesta) {
			alert(respuesta);
			//respuesta=jQuery.parseJSON(respuesta);
			/*if(typeof respuesta.error=="undefined" || respuesta.error==""){

			}else{

			}*/
		}
	});
}

