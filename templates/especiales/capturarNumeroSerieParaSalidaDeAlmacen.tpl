<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
<script type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>
<SCRIPT Language=Javascript SRC="{$rooturl}js/funciones_especiales.js"></SCRIPT>


<div id="captura_numero_serie">
	<h1 class="encabezado">&nbsp;&nbsp;Captura de  n&uacute;meros de serie</h1>
	<form id="form2" name="form2" method="post">
		<div align="center">
			<table width="500" border="0" id="tabla_captura" name="tabla_captura">
		    	<tr>
					<td width="99" align="center" colspan="3">
						<label><input type="text" name="TxtValor" id="TxtValor" value="" maxlength="17" size="30" style="text-transform:uppercase"/></label>
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
					  	<label><input name="BtnValidar" type="submit" id="Validar" value="Verificar" onclick="if(validaCapturaNumerosDeSerieParaSalidaDeAlmacen({$idAlmacen}, {$idProducto}, {$numeroRenglon})) return false; else return false; "/></label>
					</td>
					<td align="right">
					  Capturados:&nbsp;
				  <input type="text" name="TxtContador" id="TxtContador" value="0" class="texto_numeros_serie" size="2" readonly="true"></td>
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
				<b>La informaci&oacute;n se valid&oacute; correctamente y est&aacute; lista para la salida del almacen<br /><br />Por favor cierre esta ventana</b>
				<!-- <input type="" name="quitar" id="quitar" value=""> -->
				<input type="hidden" name="cantidadAIngresar" id="cantidadAIngresar" value="{$cantidadAIngresar}">
				<input type="hidden" name="idCarga" id="idCarga" value="0">
			</th>
		</tr>
	</table>
<script type="text/javascript">
	//window.top.cantidadIAux{$idOrdenCompra}{$numeroRenglon}.value = document.getElementById("cantidadAIngresar").value;
	//window.top.numeroCarga{$idOrdenCompra}{$numeroRenglon}.value = document.getElementById("idCarga").value;
</script>
</div>


