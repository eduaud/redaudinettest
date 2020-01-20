 $(document).ready(function() {
     
	 
	// change usado  para modicar el valor del campo costo del grid
	$('#factor_costo_total_producto').change(function(){
		llenarFactorCosto('detalleArticulos', 10);
		llenarFactorCosto('detalleArticulosBasicos', 11);
		llenarFactorCosto('detalleArticulosEspeciales', 13);
		llenarFactorCosto('detalleArticulosProduccion', 7);
		llenarFactorCosto('detalleArticulosCompra', 7);
	})
	   
	
	
	
	if($('#t').val() != "YWRfcGVkaWRvcw==" 			&& 
	   $('#t').val() != "bmFfdmFsZXNfcHJvZHVjdG9z"	&& 
	   $('#t').val() != "bmFfZmFjdHVyYXM="	&& 
	   $('#t').val() != "YWRfZmFjdHVyYXM=="){
			
			$('#id_cliente').change(function(){
				 var array = new Object(); 
					array['accion'] =1;
					array['id_cliente'] =$('#id_cliente').val();
					array['id_tipo_servicio'] =$('#id_tipo_pedido').val();
					$.ajax({
							url: '../../code/ajax/getPreciosArticulos.php',
							data:array,
							type: 'GET',
						success: function(resp){ 
								//PARA LOS INDICES MOVIDOS D---
								llenarPorcentaje('detalleArticulos',9,resp);
								llenarPorcentaje('detalleArticulosBasicos',11,resp);
								llenarPorcentaje('detalleArticulosEspeciales',12,resp);
								llenarPorcentaje('detalleArticulosProduccion',6,resp);
								llenarPorcentaje('detalleArticulosCompra',6,resp);
								$("#porcentaje").val(resp);
						},
						error: function(data) {
							alert('Error de al conectar con el servidor'); //or whatever
						}
					 });
				
			 });
		}
     });
	




    
	
//Abrir formato para un contacto nuevo 
function abrirThickBox(ruta){
	var obj=document.getElementById('thickbox_href');
	obj.setAttribute('href',ruta);
	obj.click();		
 }
	


//FUNCION QUE  ABRE  LA PANTALLA  VER LOS PRODUCTOS SUSTITUTOS
function articulos_sustitutos_existencia(id){
	    //sacar la fila donde se encuentran los datos
	var idRenglonArticulo='#detalleArticulos_2_'+id;
	var idRenglonNombre='#detalleArticulos_3_'+id;
	    //Sacar id articulo y  el nombre
	var articulo=$(idRenglonArticulo).attr('valor');
	var  nombre=$(idRenglonNombre).attr('valor');
	
	    //Se valida que esxista una fecha
	if( $('#fecha_entrega_articulos').val()!='' ){
		var ruta="../especiales/detalle_articulos.php?action=1&height=550&width=1100&modal=false&valor="+articulo+"&nombre="+nombre;
		abrirThickBox(ruta);
	}else{
		alert("Debe seleccionar una fecha de entrega"); 
	}
}

//FUNCION QUE  ABRE  LA PANTALLA  VER LA LOCALIZACIÓN DE LOS ARTICULOS
function ver_localizacion(id){
	var col = id;
	var articulo=$("#detalleArticulos_3_"+id).text();
	var cantidad=$("#detalleArticulos_5_"+id).text();
	var cotizacionesCanc = $("#detalleArticulos_26_" + id).attr('valor') + $("#detalleArticulos_27_" + id).attr('valor');
	var id = $("#detalleArticulos_2_"+id).attr('valor');
	var id_control_pedido=$('#id_control_pedido').val();
	var op = $("#op").val();
	var v = $("#v").val();
	
	var fecha_entrega_articulos = $("#fecha_entrega_articulos").val();
	var hora_entrega = $("#hora_entrega").val();
	var fecha_recoleccion = $("#fecha_recoleccion").val();
	var hora_recoleccion = $("#hora_recoleccion").val();
	var tipo_evento = $("#id_tipo_evento_localizacion").val();
	
	//alert(cotizacionesCanc);
	
	var ruta="../especiales/detalle_articulos.php?action=0&height=550&width=1100&modal=false&articulo="+articulo+"&cantidad="+cantidad+"&col="+col+"&id="+id+"&id_control_pedido="+id_control_pedido+"&bloquear=" + cotizacionesCanc + "&op=" + op + "&v=" + v + "&fecha_entrega_articulos=" + fecha_entrega_articulos + "&hora_entrega=" + hora_entrega + "&fecha_recoleccion=" + fecha_recoleccion + "&hora_recoleccion=" + hora_recoleccion + "&tipo_evento=" + tipo_evento;
	abrirThickBox(ruta);
}


//FUNCION PARA SACR EL NUMEROS DE FILAS DEL GRID
function NumFilas(tabla)
{	
	var obj=document.getElementById('Body_'+tabla);
	if(!obj)
		return null;
	var Trs=obj.getElementsByTagName('tr');
	return Trs.length;
}

//FUNCION PARA AHREGAR EL  FACTOR DE COSTO
function llenarFactorCosto(grid,numCampo)
{
	var idTabla='';
	for(i=0;i<=NumFilas(grid);i++)
	{
		 valorXY(grid,numCampo,i,$('#factor_costo_total_producto').val());
		 calculaSurtir('detalleArticulos', i)
	}	
	
}
//FUNCION PARA AHREGAR EL  PORCENTAJE
function llenarPorcentaje(grid, numCampo, porcentaje)
{
	var idTabla='';
	for(i=0; i <= NumFilas(grid); i++)
	{
		  valorXY(grid, numCampo, i, porcentaje);
		  calculaSurtir(grid, i);
	}	
	
}


function buscadorDirecciones(){
	direcciones();
}


function direcciones(){
	 var ruta="../especiales/buscador_Direccion.php?action=1&height=600&width=1100&modal=false";
	 abrirThickBox(ruta);	
}

function validaCantidad(idElemento)
{
	var id = idElemento.split("_");
	cantExist = parseFloat($("#existExist_" + id[1]).attr("valor"));
	cantSolic = parseFloat($("#" + idElemento).val());
	
	if(cantExist < cantSolic)
	{
		$("#" + idElemento).val("");
		alert("La cantidad solicitada es mayor a la que hay disponible");
	}
}


