
function actualizarNota(formSaraData,obj){

	criterio=obj.attr('id');  
	estudiante=obj.attr('class');
	porcentaje=obj.attr('porcentaje');
	sum=0;	
	
	nota=obj.val();
	nota=nota.replace(",",".");
	obj.val(nota);
	
	$("#not_final_"+estudiante).html(""); 
	$("#not_final_porcentaje_"+estudiante).html(""); 
	$("#not_final_desempenio_"+estudiante).html(""); 
				
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

	$("."+estudiante).each(function() {
		 
		$("#not_final_"+estudiante).append("\n("+sum+")");         
		sum+=Number($(this).val())*Number($(this).attr('porcentaje')/100);
	});
	
	$("#not_final_"+estudiante).html(sum.toFixed(2));      
	
	$.ajax({
		type: 'GET',
		url: formSaraData,
		data: "&estudiante="+estudiante+"&criterio="+criterio+"&nota="+obj.val(),    
		success: function(respuesta) {
			respuesta=jQuery.parseJSON(respuesta); 
			if(typeof respuesta.error=="undefined" || respuesta.error==""){
				$("#not_final_"+estudiante).html(respuesta.notaFinal.toFixed(2)); 
				$("#not_final_porcentaje_"+estudiante).html(respuesta.notaFinalPorcentaje.toFixed(2)); 
				$("#not_final_desempenio_"+estudiante).html(respuesta.desempenio); 
			}else{
				$("#not_final_"+estudiante).html(sum.toFixed(2)); 
				$("#not_final_porcentaje_"+estudiante).html(""); 
				$("#not_final_desempenio_"+estudiante).html(""); 			
			}
		}
	});
}

