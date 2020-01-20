{literal}
<style>

		form[name="formax"]{
				padding : 10px;
				}
		select.articulos{
				width : 200px;
				}
		select.movimientos{
				width : 250px;
				}
		.fechas{
				width : 249px;
				}
		input[type="radio"] {
	
			outline: none;
			width: 40px;
			display: inline-block;
			margin: 3px;
			background-color: #000;
			border: 0;
			border-bottom: 1px solid rgba(255,255,255,0.1);
		}
 
		
</style>
{/literal}
<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />
<link href="../../css/gridSW.css" rel="stylesheet" type="text/css" />
<!-- Aquí va el tipo de documento -->
{include file="_header.tpl" pagetitle="$contentheader"}

<script language="JavaScript" type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>

<div id="content-sistema" class="content-sistema">
	<br/><br/>
	<h1>{$titulo}</h1>
	<br/>	
	<div id="buscador" class="buscador" style="background-color:#CCC">	
		<form action="#" method="post" id="formax" name="formax">
		<input type="hidden" name="titulo" value="{$titulo}">
		<input type="hidden" name="campoTexto" value="">
		<input type="hidden" name="selSuc" id="selSuc" value="-1"  />
        <input type="hidden" name="reporte" id="reporte"  value="{$reporte}" />
        <br />
		<table cellspacing="20" border="0" style="margin:0 auto">

			{if $reporte eq 1 or $reporte eq 4 or $reporte eq 9  or $reporte eq 10}
				<tr>
					<td>
						<h4>Familia de Productos</h4>
						<select name="slct_familia" onChange="obtenTipoProducto(); " class="campos_req movimientos" id="slct_familia" >
							<option value="0">Todos</option>
							{html_options values=$familia_id output=$familia_nombre }
						</select>
					</td>
                    <td></td>
					<td>
						<h4>Tipos de Productos</h4>
						<select name="slct_tipo" class="campos_req movimientos" id="slct_tipo" >
							<option value="0">Seleccione Familia...</option>							
						</select>
					</td>
                  </tr>	                  
                  <tr>
					<td>
						<h4>Modelos de Productos</h4>
						<select name="slct_modelo" class="campos_req movimientos" id="slct_modelo" >
							<option value="0">Seleccione Tipo...</option>	
                            {html_options values=$modelo_id output=$modelo_nombre }						
						</select>
					</td>
                    <td>&nbsp;</td>
                 	<td>
						<h4>Caracteristicas de Productos</h4>
						<select name="slct_caracteristica" class="campos_req movimientos" id="slct_caracteristica" >
							<option value="0">Seleccione Familia...</option>	
                            {html_options values=$caracteristica_id output=$caracteristica_nombre }					
						</select>
					</td>
                  </tr>	
                 
                  {if $reporte eq 4}  
                  
                   <tr>
					<td>
						<h4>Almacenes</h4>
						<select name="slct_almacenes" class="campos_req movimientos" id="slct_almacenes"  multiple="multiple">
							{html_options values=$almacenes_id output=$almacenes_nombre }						
						</select>
					</td>
                    <td>&nbsp;</td>
                 	<td>
						.
					</td>
                  </tr>	
                  {/if}
                  
                  
                  	
                  <tr>
				<td colspan="3">
						<h4>Productos</h4>
						<select id="slct_productos" class="campos_req" multiple="multiple" name="slct_productos" style="width: 518px; height:400px;">
							
						</select>
                       <!-- <input type="button" id="filtros" onClick="filtros" value="Ver Filtros">-->
					</td>
			</tr>		
			{/if}
            
            {if $reporte eq 2}
				<tr>
					<td>
						<h4>Sucursal Alta</h4>
						<select name="slct_sucursal" onChange="obtenCategoria();" class="campos_req movimientos" id="slct_sucursal" >
							<option value="0">Todas</option>
							{html_options values=$sucursal_id output=$sucursal_nombre }
						</select>
					</td>
					<td style="padding-left : 15px;">
						<h4>Categoria</h4>
						<select name="slct_categoria" onChange="" class="campos_req movimientos" id="slct_categoria" >
							<option value="0">Todas</option>

						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4>Clientes</h4>
						<select id="slct_clientes" class="campos_req"  multiple="multiple" size="12" name="slct_clientes" style="width: 515px;">
						</select>
					</td>
				</tr>
			{/if}

            {if $reporte eq 3}
				
                            
              <tr>
    <td><h4>Sucursal</h4></td>
    <td><select name="slct_sucursal_cliente" class="campos_req movimientos" id="slct_sucursal_cliente" >
      <option value="0">Todos</option>
      
							{html_options values=$sucursal_id output=$sucursal_nombre }
						
    </select></td>
    <td>&nbsp;</td>
    <td><h4>Pedido</h4></td>
    <td><input type="text" style=" width:250px" name="txt_pedido" id="txt_pedido" class="campos_req movimientos"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h4>Vendedor</h4></td>
    <td><select name="slct_vendedor"  class="campos_req movimientos" id="slct_vendedor" >
      <option value="0">Todos</option>
      
							{html_options values=$vendedor_id output=$vendedor_nombre }
						
    </select></td>
    <td>&nbsp;</td>
    <td><h4>Cliente</h4></td>
    <td><input type="text" style=" width:250px" name="txt_cliente" id="txt_cliente" class="campos_req movimientos" size="28" /></td>
    <td align="right"><input type="button" class="boton" value="Buscar" onClick="obtenClienteClic();"/></td>
  </tr>
  <tr>
    <td colspan="2"><h4>Fecha Creación</h4> </td>
    <td>&nbsp;</td>
    <td colspan="3" rowspan="4">
    <select id="slct_cliente_ajax" class="campos_req" multiple="multiple" name="slct_cliente_ajax" style="width: 380px; height:150px; display:none ">
                          <option value="0" >No hay coincidencias</option>
					      </select>
    <input type="hidden" name="id_cliente_ajax" id="id_cliente_ajax" /></td>
  </tr>
  <tr>
    <td><h4>del </h4>
      <input id="fec_ini" class="campos_req fechas" type="text" style="width:100px;" title="Tipo fecha (dd/mm/aaaa)" 
      readonly onfocus="calendario(this);" size="18" maxlength="14" name="fec_ini">
    </td>
    <td><h4>al </h4>
      <input id="fec_fin" class="campos_req fechas" type="text" style="width:100px;" title="Tipo fecha (dd/mm/aaaa)" 
      readonly onfocus="calendario(this);" size="18" maxlength="14" name="fec_fin">
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h4>Estatus Pago</h4></td>
    <td>
    <select name="slct_estatus_pago" class="campos_req movimientos" id="slct_estatus_pago" >
      <option value="0">Todos</option>      
							{html_options values=$estatus_pago_id output=$estatus_nombre }						
    </select>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h4>Estatus Pedido</h4>
    </td>
    <td>
    <select name="slct_estatus_pedido"  class="campos_req movimientos" id="slct_estatus_pedido" >
      <option value="0">Todos</option>
      
							{html_options values=$estatus_id output=$estatus }
						
    </select>
    </td>
    <td>&nbsp;</td>
  </tr>
                   
			{/if}
            
            
            
            
            {if $reporte eq 5}
				<tr>
					<td>
						<h4>Sucursal </h4>
						<select name="slct_sucursal" onChange="muestraListaPreciosEtiqueta();" class="campos_req movimientos" id="slct_sucursal" >
							 <option value="0">Seleccione una sucursal</option>
							{html_options values=$sucursal_id output=$sucursal_nombre }
						</select>
					</td>
					<td style="padding-left : 15px;">
						<h4>Lista de Precios</h4>
						<select name="slct_lista_precios" onChange="" class="campos_req movimientos" id="slct_lista_precios" style="width:500px" >
							{html_options values=$listaPrecios_id output=$listaPrecios_nombre }

						</select>
					</td>
				</tr>
			
			{/if}
            {if $reporte eq 6}
				
                <tr>
					<td>
						<h4>Tipo de Egresos</h4>						
					</td>
					<td style="padding-left : 15px;">
						<select name="slct_tipo_egreso" class="campos_req movimientos" id="slct_tipo_egreso" >
							 <option value="0">Seleccione Tipo de Egreso...</option>
							{html_options values=$tipo_egreso_id output=$tipo_egreso_nombre }
						</select>
					</td>
				</tr>
                <tr>
					<td>
						<h4>Pedido</h4>						
					</td>
					<td style="padding-left : 15px;">
						<input type="text" name="txt_pedido" id="txt_pedido" style="width:250px"  class="campos_req" />
					</td>
				</tr>   
                <tr>
					<td>
                    	 <h4>Fecha de Egreso</h4>
					</td>
					<td style="padding-left : 15px;" height="30">
						<h4>de <input type="text" name="fecha_egreso_de" id="fecha_egreso_de" size="15" onfocus="calendario(this);"	 />&nbsp;
                        	a <input type="text" name="fecha_egreso_a" id="fecha_egreso_a"	 size="15" onfocus="calendario(this);" />
                        </h4>
					</td>
				</tr>
                <tr>
					<td>
                    	 <h4>Total</h4>
					</td>
					<td style="padding-left : 15px;" height="30">
						<h4>de <input type="text" name="total_de" id="total_de"	size="15" />&nbsp;
                        	a <input type="text" name="total_a" id="total_a"	size="15" />
                        </h4>
					</td>
				</tr>
                <tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
			{/if}
            {if $reporte eq 7}				
                <tr>
					<td>
						<h4>Tipo de Egresos</h4>						
					</td>
					<td style="padding-left : 15px;">
						<select name="slct_tipo_egreso" class="campos_req movimientos" id="slct_tipo_egreso" >
							 <option value="0">Seleccione Tipo de Egreso...</option>
							{html_options values=$tipo_egreso_id output=$tipo_egreso_nombre }
						</select>
					</td>
				</tr>
                <tr>
					<td>
						<h4>Pedido</h4>						
					</td>
					<td style="padding-left : 15px;">
						<input type="text" name="txt_pedido" id="txt_pedido" style="width:250px"  class="campos_req" />
					</td>
				</tr>   
                <tr>
					<td>
                    	 <h4>Fecha de Egreso</h4>
					</td>
					<td style="padding-left : 15px;" height="30">
						<h4>de <input type="text" name="fecha_egreso_de" id="fecha_egreso_de" size="15" onfocus="calendario(this);"	 />&nbsp;
                        	a <input type="text" name="fecha_egreso_a" id="fecha_egreso_a"	 size="15" onfocus="calendario(this);" />
                        </h4>
					</td>
				</tr>
                <tr>
					<td>
                    	 <h4>Total</h4>
					</td>
					<td style="padding-left : 15px;" height="30">
						<h4>de <input type="text" name="total_de" id="total_de"	size="15" />&nbsp;
                        	a <input type="text" name="total_a" id="total_a"	size="15" />
                        </h4>
					</td>
				</tr>
                <tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
			{/if}   
            {if $reporte eq 8}	
              <tr height="30">
                <td><h4>Sucursal</h4></td>
                <td colspan="3">
	                <select name="slct_sucursal" class="campos_req movimientos" id="slct_sucursal" >
						{if $usuarioSuc eq 0 || $usuarioSuc eq 1}
						<option value="0">Seleccione una Sucursal</option>
						{/if}
						{html_options values=$sucursal_id output=$sucursal_nombre }
					</select>
                </td>
           
              </tr>
              <tr height="30">
                <td>
	                <h4>Fecha del &nbsp;</h4>                
                </td>
                <td>
                  
                    	<input type="text" name="fecha_del" id="fecha_del" style="width:94px" size="15" onfocus="calendario(this);"	 />                   	
                  
                </td>
                <td align="right">
	                <h4>&nbsp;&nbsp; al&nbsp;</h4>
                </td>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" name="fecha_al" id="fecha_al"	 style="width:94px" size="15" onfocus="calendario(this);" />
                </td>
              </tr>
              <tr height="30">
                <td><h4>Versión</h4></td>
                <td colspan="3" align="justify">
                	{html_radios name="version" values=$version_id output=$version_nombre selected=$version_default  separator="&nbsp;&nbsp;&nbsp;"}
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>	
			{/if}    
			{if $reporte eq 11}
				<tr>
					<td>
						<h4>Sucursal </h4>
						<select name="slct_sucursal" onChange="muestraListaPrecios();" class="campos_req movimientos" id="slct_sucursal" >
							{if $sessionSuc eq 0}
									<option value="0">Seleccione una sucursal</option>
							{/if}
							{html_options values=$sucursal_id output=$sucursal_nombre }
						</select>
					</td>
					
				</tr>
			
			{/if}

						
		</table>
		</form>	
	</div>	
	<br />
	<table cellpadding="0" cellspacing="0" width="100%">
				<!-- Reportes que contendran el campo de orden -->
				{if 0}
				<tr style="height:60px;">
					<td>&nbsp;</td>
				</tr>
				{/if}
				<tr valign="middle">
					<td height="26">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr align="left">
                            <!--Botonera-->
								<td align="left" class="user"><br>
                                <input type="button" value="Generar reporte" class="boton" onclick="generaReporte(1,{$reporte})"/>
                                <input type="button" value="Exportar a Excel" class="boton" onclick="generaReporte(2,{$reporte})"/>                            
                                </td>
                                <!--Fin de botonera-->                      
						  </tr>
						</table>
				  </td>
				</tr>
				<tr valign="top">
					<td class="lines" height="1"><img src="{$imgpathmaster}/space.gif" alt="" width="1" height="1"></td>
				</tr>
			</table>
{literal}
<script type="text/javascript" language="javascript">
// Refresca Select Multiple
//----
var reporte	 = document.getElementById("reporte").value;

if (reporte == 1 )
{
	$("#slct_familia, #slct_tipo, #slct_modelo, #slct_caracteristica").on("change", function(){

	var slct_familia		= $("#slct_familia").find("option:selected").val();
	var slct_tipo			= $("#slct_tipo").find("option:selected").val();
	var slct_modelo			= $("#slct_modelo").find("option:selected").val();
	var slct_caracteristica = $("#slct_caracteristica").find("option:selected").val();
	
    var filtros = "slct_familia = " 		+ slct_familia		+
				  "&slct_tipo = "			+ slct_tipo 		+
				  "&slct_modelo = "			+ slct_modelo 		+
				  "&slct_caracteristica = " + slct_caracteristica;
				  
	var url = "../ajax/getRepProductos.php"; // El script a dónde se realizará en envio.
	
    $( "select option:selected" ).each(function() {
//     filtros = $("#formax").serialize(); 
// alert(filtros);
//	 return false;
	    $.ajax({
	           type: "POST",
	           url: url,
	           data: filtros, // Adjuntar los campos del formulario enviado.			   
	           success: function(data)
	           {
//				   alert(data);
				   $("#slct_productos option").remove();
					$("#slct_productos").append(data);
	           }
	         });	 
	    return false; // Evitar ejecutar el submit del formulario.  	  
    });
	
  })
  .trigger( "change" );
	
}
if (reporte == 2 )
{
	$("#slct_sucursal, #slct_categoria").on("change", function(){
	
		var slct_sucursal	= $("#slct_sucursal").find("option:selected").val();
		var slct_categoria	= $("#slct_categoria").find("option:selected").val();
		
		alert('sucursal: '+slct_sucursal+' categoria: '+slct_categoria);
		var filtros = "slct_sucursal = " 	+ slct_sucursal	+
				  	  "&slct_categoria = "	+ slct_categoria;
				  
		var url = "../ajax/getRepClientesFiltros.php?opcion=llenarClientes"; // El script a dónde se realizará en envio.
		
		$( "select option:selected" ).each(function() {
//		 filtros = $("#formax").serialize(); 
	//	 alert(filtros);
	//	 return false;
			$.ajax({
				   type: "POST",
				   url: url,
				   data: filtros, // Adjuntar los campos del formulario enviado.			   
				   success: function(data)
				   {
	//				   alert(data);
					   $("#slct_clientes option").remove();
						$("#slct_clientes").append(data);
				   }
				 });	 
			return false; // Evitar ejecutar el submit del formulario.  	  
	  });
	});		
}


$("#filtros").click(function(){
	alert(filtros);
	});
// funciones generales para los campos

function obtenTipoProducto()
{

		var selectHijo = "slct_tipo";
		var opcion = $("#slct_familia").find("option:selected").val();
		var urlAjax = "llenaDato.php";
		var envio_datos = 'id=' + opcion + "&dato=tipoProducto";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);				// Funcion JQuery 
		
}
function obtenModeloProducto()
{
		var selectHijo = "slct_modelo";
		var opcion = $("#slct_tipo").find("option:selected").val();
		var urlAjax = "llenaDato.php";
		var envio_datos = 'id=' + opcion + "&dato=modeloProducto";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);				// Funcion JQuery 
}
function obtenCaracteristicasProducto()
{
		var selectHijo = "slct_caracteristica";
		var opcion = $("#slct_familia").find("option:selected").val();
		var urlAjax = "llenaDato.php";
		var envio_datos = 'id=' + opcion + "&dato=caracteristicaProducto";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);				// Funcion JQuery 
}

function obtenClienteClic(){
	var selectHijo = "slct_cliente_ajax";
	var opcion = $("#txt_cliente").val();
//	alert(opcion);
	var urlAjax = "llenaDato.php";
	var envio_datos = 'id=' + opcion+'&dato=clientes_select_multiple';  // Se arma la variable de datos que procesara el php
	ajaxCombos2(urlAjax, envio_datos, selectHijo);
}








function obtenCategoria()
{
		var selectHijo = "slct_categoria";
		var opcion = $("#slct_sucursal").find("option:selected").val();
		var urlAjax = "../ajax/getRepClientes.php";
		var envio_datos = 'id=' + opcion + "&filtro=sucursal";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);				// Funcion JQuery 
}














function obtenCliente()
{
		var selectHijo = "slct_cliente";
		var opcion = $("#slct_sucursal_cliente").find("option:selected").val();
		var urlAjax = "llenaDato.php";
		var envio_datos = 'id=' + opcion + "&dato=clientes";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);				// Funcion JQuery 
}


function muestraListaPrecios()
{
	//llenamos la lista de precios publica
	var selectHijo = "slct_lista_precios";
		var opcion = $("#slct_sucursal").find("option:selected").val();
		var urlAjax = "llenaDato.php";
		var envio_datos = 'id=' + opcion + "&dato=listaPrecios";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);	
	
}

function muestraListaPreciosEtiqueta()
{
	//llenamos la lista de precios publica
	var selectHijo = "slct_lista_precios";
		var opcion = $("#slct_sucursal").find("option:selected").val();
		var urlAjax = "llenaDato.php";
		var envio_datos = 'id=' + opcion + "&dato=listaPreciosConEtiquetas";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);	
	
}

//if (reporte == 8 )
//{
	/**********************************************
	* funcion para mostrar/ocultar cajas de texto *
	* dependiendo de la opcion seleccionada 	  *
	**********************************************/   	
//	$('input[name$="version"]').click(function () {	
		
//		var radio_value = $(this).val();
//		alert(radio_value);
//		if (radio_value == 1) {		
//			$('#version').fadeIn();		
//		}
//		else
//		{
//			$('#version').fadeOut();
//		}
		
		
//	});
	
//}
</script>
{/literal}
{literal}
<script type="text/javascript" language="javascript">
function obtenIdsSeleccionados(id_control)
{
	//var obj=document.getElementById(id_control);	
	var combo = document.getElementById(id_control);
  	var selected ="0";
	var tam=combo.length;  
	for(var i=0; i <tam; i++ )
   	{
		if(combo.options[i].selected==true)
		{
			selected=selected+'|'+combo.options[i].value
		 }
	}   	
	//eliminamos el ultimo |
	//selected=selected.substring(0, selected.length-1);	
	return selected;
}
function obtenParametrosReporte(idRep)
{
	var parametros="";
	
	if(idRep==1 )
	{	
		var familiaProducto = obtenIdsSeleccionados("slct_familia");
		var tipoProducto	= obtenIdsSeleccionados("slct_tipo");
		var modeloProducto	= obtenIdsSeleccionados("slct_modelo");
		var caracteristicaProducto = obtenIdsSeleccionados("slct_caracteristica");
		var productos = obtenIdsSeleccionados("slct_productos");
		parametros="familiaProducto@"			+ familiaProducto
					+"~tipoProducto@"			+ tipoProducto
					+"~modeloProducto@"			+ modeloProducto
					+"~caracteristicaProducto@" + caracteristicaProducto
					+"~productos@" + productos;
	}
	else if(idRep==2)
	{	
		var sucursalCliente = obtenIdsSeleccionados("slct_sucursal");
		var categoriaCliente= obtenIdsSeleccionados("slct_categoria");
		var clientes		= obtenIdsSeleccionados("slct_clientes");
		parametros="sucursalCliente@"		+ sucursalCliente
					+"~categoriaCliente@"	+ categoriaCliente
					+"~clientes@"			+ clientes;
	}	
	else if(idRep==3)
	{	
		var pedido	 = document.getElementById("txt_pedido").value;
		var sucursal = document.getElementById("slct_sucursal_cliente").options.selectedIndex;
		var vendedor = document.getElementById("slct_vendedor").options.selectedIndex;
		var clientes = obtenIdsSeleccionados("slct_cliente_ajax");
		var fechaDel = document.getElementById("fec_ini").value;
		var fechaAl  = document.getElementById("fec_fin").value;
		var estatusPago   = document.getElementById("slct_estatus_pago").options.selectedIndex;
		var estatusPedido = document.getElementById("slct_estatus_pedido").options.selectedIndex;	
		
		parametros= "pedido@"			+ pedido
					+"~sucursal@"		+ sucursal
					+"~vendedor@"		+ vendedor
					+"~clientes@"		+ clientes
					+"~fechaDel@"		+ fechaDel
					+"~fechaAl@"		+ fechaAl
					+"~estatusPago@"	+ estatusPago
					+"~estatusPedido@"	+ estatusPedido;
	}
	//alert(parametros);
	else if(idRep==4 )
	{	
		var familiaProducto = obtenIdsSeleccionados("slct_familia");
		var tipoProducto	= obtenIdsSeleccionados("slct_tipo");
		var modeloProducto	= obtenIdsSeleccionados("slct_modelo");
		var caracteristicaProducto = obtenIdsSeleccionados("slct_caracteristica");
		var productos = obtenIdsSeleccionados("slct_productos");
		var almacenes = obtenIdsSeleccionados("slct_almacenes");
		

		
		parametros="familiaProducto@"			+ familiaProducto
					+"~tipoProducto@"			+ tipoProducto
					+"~modeloProducto@"			+ modeloProducto
					+"~caracteristicaProducto@" + caracteristicaProducto
					+"~productos@" + productos
					+"~almacenes@" + almacenes;
	}
	else if(idRep==5 )
	{	
		var sucursal = obtenIdsSeleccionados("slct_sucursal");
		var listaPrecios = obtenIdsSeleccionados("slct_lista_precios");
		
		parametros="sucursal@"			+ sucursal
					+"~listaPrecios@"			+ listaPrecios
				;
	}
   else if(idRep==8 )
	{	
		
		var sucursal = $("#slct_sucursal").find("option:selected").val();
		var fecha_del= $("#fecha_del").val();
		var fecha_al = $("#fecha_al").val();
		var version = $('input[name$="version"]:checked').val();
		var forma_pago = $("#slct_forma_pago").val();
		parametros="sucursal@" + sucursal
					+"~fecha_del@"	+ fecha_del
					+"~fecha_al@" 	+ fecha_al
					+"~version@"  	+ version
					+"~forma_pago@" + forma_pago;
			
	}	
	else if(idRep==9 )
	{	
		var familiaProducto = obtenIdsSeleccionados("slct_familia");
		var tipoProducto	= obtenIdsSeleccionados("slct_tipo");
		var modeloProducto	= obtenIdsSeleccionados("slct_modelo");
		var caracteristicaProducto = obtenIdsSeleccionados("slct_caracteristica");
		var productos = obtenIdsSeleccionados("slct_productos");
		

		
		parametros="familiaProducto@"			+ familiaProducto
					+"~tipoProducto@"			+ tipoProducto
					+"~modeloProducto@"			+ modeloProducto
					+"~caracteristicaProducto@" + caracteristicaProducto
					+"~productos@" + productos;
				;
	}
	else if(idRep==10)
	{	
		var familiaProducto = obtenIdsSeleccionados("slct_familia");
		var tipoProducto	= obtenIdsSeleccionados("slct_tipo");
		var modeloProducto	= obtenIdsSeleccionados("slct_modelo");
		var caracteristicaProducto = obtenIdsSeleccionados("slct_caracteristica");
		var productos = obtenIdsSeleccionados("slct_productos");
		
		
		parametros="familiaProducto@"			+ familiaProducto
					+"~tipoProducto@"			+ tipoProducto
					+"~modeloProducto@"			+ modeloProducto
					+"~caracteristicaProducto@" + caracteristicaProducto
					+"~productos@" + productos;
				;
	}
	
	else if(idRep==11)
	{	
		var idSucursal = obtenIdsSeleccionados("slct_sucursal");
		parametros = "sucursal@" + idSucursal;
		}
	
	return parametros;
}

function generaReporte(opcion,idRep)
{
	
	// VALIDACIONES CORTE DE CAJA
	
	if(idRep==8)
	{
		var form =document.forms.formax;
			
		if (form.fecha_del.value =="" ^ form.fecha_al.value =="")
		{
			if (form.fecha_del.value =="")
			{
				alert('Favor de capturar los dos campos de fecha');
//				form.fecha_del.focus(); 
				return false;
			}
			if (form.fecha_al.value =="")
			{
				alert('Por favor de capturar los dos campos de fecha');
//				form.fecha_al.focus(); 
				return false;
			}		
		}			
		
		var version = $('input[name$="version"]:checked').val();
		
		if (typeof (version) == "undefined") 
		{
			alert ("Favor de seleccionar la version");			
			return false;		
		}
		/*else
		{
			if(version==1)
			{
				
				if (form.slct_forma_pago.value==0)
				{
					alert ("Favor de seleccionar una forma de pago");				
					form.slct_forma_pago.focus();
					return false;
				}
			}
		}*/
	}				
	
	var centroAncho = (screen.width/2) - 400;
	var centroAlto = (screen.height/2) - 300;
	var parametros=obtenParametrosReporte(idRep);
	
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,width=800,height=600,resizable=yes"
	var titulo="ventanaEmergente";
	
	window.open("procesaReportes.php?opcion="+opcion+"&idRep="+idRep+"&parametros="+parametros,"_blank", especificaciones);
	
	//vamos a ver si ya existe la ventana abierta
	return true;
}

</script>
{/literal}
</div>

{include file="_footer.tpl" aktUser=$username}