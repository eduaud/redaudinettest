<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
<script type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>
<SCRIPT Language=Javascript SRC="{$rooturl}js/funciones_especiales.js"></SCRIPT>
{literal}<script>
function GuardaNumerosDeSerie(){
	var texto=$("#numeros_serie").val();
	{/literal}parent.document.getElementById("detalleMovimientosAlmacen_26_{$numeroRenglon}").s{literal}etAttribute("valor",texto);
	parent.$.fancybox.close();
}{/literal}
</script>
<div id="captura_numero_serie">
	<h1 class="encabezado">&nbsp;&nbsp;Captura de  n&uacute;meros de serie</h1>
	<form id="form2" name="form2" method="post">
		<div align="center">
			<table width="500" border="0" id="tabla_captura" name="tabla_captura">
		    	<tr>
					<td width="99" align="center" colspan="3">
						<label><input type="text" name="TxtValor" id="TxtValor" value="" maxlength="17" style="text-transform:uppercase"/></label>
						<label for="Submit"></label>
						<input type="submit" name="BtnAgregar" value="Agregar" id="BtnAgregar" onclick="agregarALista(form2.TxtValor.value, '{$cantidadAIngresar}'); return false;" />
					</td>
		      	</tr>
		      	<tr>
		      		<td colspan="2"><br /><div id="lista_datos" class="lista_datos" name="lista_datos"><!-- SE MUESTRAN LOS DATOS CAPTURADOS --></div></td>
		  		</tr>
				<tr>
			 		<td>
			  			<br />
				  		<input type="hidden" name="cantidadAIngresar" id="cantidadAIngresar" value="{$cantidadAIngresar}">
				  		<!-- <input type="" name="id_carga" id="id_carga"> -->
					  	<label>
						{if $modulo neq 'Almacen'}<input name="BtnValidar" type="submit" id="Validar" value="Verificar" onclick="if(validaCapturaNumerosDeSerie({$idOrdenCompra}, {$idAlmacen}, {$idProducto}, {$idPlaza}, {$numeroRenglon}, {$idDetalleOrdenCompra})) return false; else return false; "/>{else}
						<input name="BtnGuardaIRDS" type="button" id="BtnGuardaIRDS" value="Guardar IRDS" onclick="GuardaNumerosDeSerie()"/>
						{/if}</label>
						<!-- <label><input name="BtnGuardar" type="submit" id="Guardar" value="Guardar" onclick="if(guardarCapturaNumerosDeSerie({$idOrdenCompra}, {$idAlmacen}, {$idProducto}, {$idPlaza})) return false; else return false; " style="display:none;"/></label> -->
					</td>
					<td align="right">
					  Capturados:&nbsp;
				  <input type="text" name="TxtContador" id="TxtContador" value="0" class="texto_numeros_serie" size="2" readonly="true"/></td>
				  <input type="hidden" id="numeros_serie" />
				</tr>
		    </table><br />
   		 	<div id="lista_errores" name="lista_errores" class="lista_errores" style="display:none">		 	
   		 	<!-- SE MUESTRAN LOS ERRORES EN CASO DE EXISTIR -->
   		 	</div>
		</div>
	</form>
</div>
<br /><br /><br /><br /><br />
<div id="correcto" style="display:none">
	<table class="mensajes_log_exito" id="mensajes_log_exito">
		<tr>
			<th>
				<b>La informaci&oacute;n se valid&oacute; correctamente y est&aacute; lista para ser guardada con la entrada de la orden de compra<br /><br />Por favor cierre esta ventana</b>
				<input type="hidden" name="cantidadAIngresar" id="cantidadAIngresar" value="{$cantidadAIngresar}">
				<input type="hidden" name="idCarga" id="idCarga" value="0">
			</th>
		</tr>
	</table>
<script type="text/javascript">
{if $modulo neq 'Almacen'}
	window.top.cantidadIAux{$idOrdenCompra}{$numeroRenglon}.value = document.getElementById("cantidadAIngresar").value;
	window.top.numeroCarga{$idOrdenCompra}{$numeroRenglon}.value = document.getElementById("idCarga").value;
{else}
	{if $irds neq ''}
		var irds='{$irds}';
		var seriesIRDS=irds.split(',');{literal}
		$("#numeros_serie").val(irds);
		var cadena="";
		for(i=0;i<seriesIRDS.length;i++){
			cadena = cadena + '<div class="div_numero_serie" id="div_numero_serie'+i+'">';
				cadena = cadena + '<span class="texto_numeros_serie" name="TxtValorLista'+i+'" id="TxtValorLista'+i+'" align="center">'+seriesIRDS[i].toUpperCase()+'</span>&nbsp;';
				cadena = cadena + '<a class="close" name="BtnEliminar'+i+'" id="BtnEliminar'+i+'" ><img src="{/literal}{$rooturl}/imagenes/x2.png{literal}" width="10" height="10" onClick="eliminarDeLista('+i+','+seriesIRDS.length+');"></a>';
				cadena = cadena + '</div>';
				$('#lista_datos').append(cadena); 
				document.getElementById('TxtContador').value = i+1;
			cadena="";
		}{/literal}
	{/if}
{/if}
</script>
</div>


