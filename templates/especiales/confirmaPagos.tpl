{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>   
   <script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/yahoo.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/event.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/dom.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/fix.js"></script>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

 <br>
<h1>Confirmación de Pagos </h1> </div>
<p />
<br /> 

<table>
	<tr>
		<td>Sucursal: </td>
		<td><select name="sucursal"> <option value="0"> -- Selecciona una Sucursal -- </option></select></td>
	</tr>
	












<br> 
Prepedidos
  <table>
<tr>
<td width="1000">
<div style="overflow-x:auto; width:985px; padding:0; height:300px">    
 <table id="productosproyectos" cellpadding="0" cellspacing="0" border="1" Alto="110"
  conScroll="S" validaNuevo="false" despuesInsertar="" AltoCelda="25" auxiliar="0" ruta="../../../imagenes/general/"
  validaElimina="false"  verFooter="N"  listado="N" class="tabla_Grid_RC" estilos_header="buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|" scrollH="N">
                	
		<tr class="HeaderCell">                      
				
			   
	   <td tipo="texto" modificable="N" mascara="" align="left" formula="" depende="" onChange="" largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="100" offsetwidth="130" on_Click="">Vendedor</td>
		<td tipo="texto" modificable="N" mascara="" align="left" formula="" depende="" onChange="" largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="90" offsetwidth="150" on_Click="">Cliente</td>
				
		  <td tipo="texto" modificable="N" mascara="" align="left" formula=""  depende="" onChange="" largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="157" offsetwidth="75" on_Click="">Fecha y Hora</td>
				

		  
		 
		  <td tipo="texto" modificable="N" mascara="" align="left" formula=""  depende="" onChange="" largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="150" offsetwidth="200" on_Click="">Monto</td>
		  <td tipo="texto" modificable="N" mascara="" align="left" formula=""  depende="" onChange="" largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="100" offsetwidth="200" on_Click="">Agregar</td>                        	
		
		</tr>       
		</table>
             </div>
             <script>	  	
                CargaGrid('productosproyectos');
			</script> 
</td>
</tr>


</table>





<!-- Listado de información de presurtido --><br /><br /><br />

<script>
{literal}


function valida(id_pedido,realiza)
{
				
	var f=document.forma2;	
			
	if(realiza==1)
	{
		var fecha=document.getElementById('fecha_'+id_pedido).value;
		if(fecha=='')
		{
			alert("La fecha de entrega es requerida");
			document.getElementById('fecha_'+id_pedido).focus();
			return false;
			
		}
		
		if(!confirm("¿Desea autorizar el pedido?"))
		{
			return false;
		}
		
		document.getElementById('fecha').value=fecha;
		document.getElementById('realiza').value=1;
		
	}
	else
	{
		var razon =document.getElementById('razon_'+id_pedido).value;
		//validamos las razònes de rechazo
		if(razon=='')
		{
			alert("La razón de rechazo es requerida");
			document.getElementById('razon_'+id_pedido).focus();
			return false;
			
		}
		if(!confirm("¿Desea rechazar el pedido?"))
		{
			return false;
		}
		document.getElementById('razon').value=razon;
		document.getElementById('realiza').value=2;
		
		
	}
	document.getElementById('id_pedido').value=id_pedido;
	f.submit();
					
}

function buscar()
{	
	var f_inicial = new Date($("#fecha_inicio").val());
	var f_final = new Date($("#fecha_fin").val());
	
	if(f_inicial > f_final){
		alert("La fecha inicial es mayor a la fecha final, vuelve a seleccionar");
		return false;
	}
	
	var f=document.forma1;
	document.getElementById("accion").value = 'ver';	
	f.submit();

}

function generarSalida(cant_productos,id_control_ord_servicio){
	
	var listaSalidasCotizacion = new Array();
	var validarCantidadSurtir = new Array();
	
	//Verifica que todos los campos de Surtir esten llenos, si no asigna CERO como valor y los mete a un arreglo
	for(var k=0;k<=cant_productos;k++){
		
		var cantidad_surtir_pedido_aux 	= $("#" + id_control_ord_servicio + "cantidad_surtir" + k ).val();
		
		if(cantidad_surtir_pedido_aux == ""){
			cantidad_surtir_pedido_aux = 0;
		}
		
		if(cantidad_surtir_pedido_aux == 0){
			validarCantidadSurtir.push({cantidad_surtir_valida:parseInt(cantidad_surtir_pedido_aux)});
		}
		
	}
	
	// Valida que todos los campos de Surtir no tengan valor de CERO
	var todosVacios = validarCantidadSurtir.length - 1;
	if (cant_productos == todosVacios){
		alert('Debes Surtir al menos un artículo para generar la salida');
		return false;
	}
	
	for(var i=0;i<=cant_productos;i++){
		var id_control_orden_servicio_aux = id_control_ord_servicio;
		var cant_solicitada_aux 					= $("#"+ id_control_ord_servicio + "cantidad_solicitada"+ i ).val();
		var existencia_alm_aux					= $("#" + id_control_ord_servicio + "existencia" + i ).val();
		var cantidad_surtir_pedido_aux 	= $("#" + id_control_ord_servicio + "cantidad_surtir" + i ).val();
		var id_detalle_aux							= $("#" + id_control_ord_servicio + "id_detalle" + i ).val();
		var id_articulo_aux			 				= $("#" + id_control_ord_servicio + "id_articulo" + i ).val();
		var descripcion_aux							= $("#" + id_control_ord_servicio + "descripcion" + i ).val();
		var pendiente_por_entregar_aux	= $("#" + id_control_ord_servicio + "pendiente_entregar" + i ).val();
		
		if(cantidad_surtir_pedido_aux == ""){
			cantidad_surtir_pedido_aux = 0;
		}
		
		if(parseInt(cantidad_surtir_pedido_aux) <= parseInt(cant_solicitada_aux) && parseInt(cantidad_surtir_pedido_aux) <= parseInt(existencia_alm_aux) && parseInt(cantidad_surtir_pedido_aux) <= parseInt(pendiente_por_entregar_aux)){
					listaSalidasCotizacion.push({id_detalle:parseInt(id_detalle_aux),id_articulo:parseInt(id_articulo_aux),cantidad_surtir:parseInt(cantidad_surtir_pedido_aux),descripcion:descripcion_aux,id_control_orden_servicio:parseInt(id_control_orden_servicio_aux)});
		}
		else{
			alert('Una de las cantidades a surtir es mayor a la SOLICITADA, PENDIENTE POR ENTREGAR o la EXISTENCIA, favor de corregir la cantidad');
			return false;
		}
	}
	
	var generarSalidaAlmacenOk = confirm('¿Esta seguro de surtir los productos del almacen? \n ');
	if(generarSalidaAlmacenOk == true){
		
		$.ajax({
			type: "POST",
			url: "../ajax/especiales/validaNuevamenteProductosAlmacen.php",
			dataType: "html",
			data: {'arreglo':listaSalidasCotizacion},
			success: function (mensaje){
				
				var respuesta = mensaje;
				
				if(respuesta != ''){
					alert(mensaje);
					
					$.ajax({
								type: "POST",
								url: "../ajax/especiales/actualizaTablaSalidaProductosAlmacen.php",
								data: "id_control_orden_servicio=" + id_control_ord_servicio,
								success: function (actualizarTabla){
								
									$( "#datosOrdenesServicio" ).html(actualizarTabla);
									
								}
							});	
				}
				
				if(respuesta == ''){
					
					$.ajax({
						type: "POST",
						url: "../ajax/especiales/generaSalidaProductosAlmacen.php",
						dataType: "html",
						data: {'arreglo':listaSalidasCotizacion},
						success: function (accion){
							var idControl = accion;
							alert('Cantidad de articulos actualizados');
							document.location="../general/encabezados.php?t=cmFjX21vdmltaWVudG9zX2FsbWFjZW4=&k=" + idControl + "&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakI4MQ==&cadP2=MDI0WlhCbGZqQjhhWEJsZmpCOFozQmxmakE9MQ==";
							/*
							$.ajax({
								type: "POST",
								url: "../ajax/especiales/actualizaTablaSalidaProductosAlmacen.php",
								data: "id_control_orden_servicio=" + id_control_ord_servicio,
								success: function (actualizarTabla){
								
									
									//$( "#datosOrdenesServicio" ).html(actualizarTabla);
									
								}
							});
							*/
							
						}
					});
				}
				
			}
		});
	}
	else{
		return false;
	}
}

{/literal}
</script>

{include file="_footer.tpl" aktUser=$username}