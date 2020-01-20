{include file="_header.tpl" pagetitle="$contentheader"}
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos_especiales.css"/>
{literal}
<script>
	function BuscarCajasComisiones(){
		var datos="";
		if($("#caja").val()!='0'&&datos!="")
			datos+="&caja="+$("#caja").val();
		else if($("#caja").val()!='0')
			datos+="caja="+$("#caja").val();
		if($("#clave").val()!='0'&&datos!="")
			datos+="&clave="+$("#clave").val();
		else if($("#clave").val()!='0')
			datos+="clave="+$("#clave").val();
			
		if($("#tipoProducto").val()!='0'&&datos!="")
			datos+="&tipoProducto="+$("#tipoProducto").val();
		else if($("#tipoProducto").val()!='0')
			datos+="tipoProducto="+$("#tipoProducto").val();
		if($("#promocion").val()!='0'&&datos!="")
			datos+="&promocion="+$("#promocion").val();
		else if($("#promocion").val()!='0')
			datos+="promocion="+$("#promocion").val();
		if($("#funcionalidad").val()!='0'&&datos!="")
			datos+="&funcionalidad="+$("#funcionalidad").val();
		else if($("#funcionalidad").val()!='0')
			datos+="funcionalidad="+$("#funcionalidad").val();
		if($("#formaPago").val()!='0'&&datos!="")
			datos+="&formaPago="+$("#formaPago").val();
		else if($("#formaPago").val()!='0')
			datos+="formaPago="+$("#formaPago").val();
		if($("#NumEquipos").val()!='0'&&datos!="")
			datos+="&NumEquipos="+$("#NumEquipos").val();
		else if($("#NumEquipos").val()!='0')
			datos+="NumEquipos="+$("#NumEquipos").val();
		if($("#paquete").val()!='0'&&datos!="")
			datos+="&paquete="+$("#paquete").val();
		else if($("#paquete").val()!='0')
			datos+="paquete="+$("#paquete").val();
		if(datos==""){
			alert('Debe de elegir al menos un filtro de b\u00FAsqueda');
			return false;
		}
		var url="especiales/buscarCajas.php";
		datos+="&caso=1";
		$("#waitingplease").css('display','block');
		var result=ajaxN(url,datos);
		var respuesta=result.split('|');
		if(respuesta[0]=='error'){
			var res=window.confirm('El resultado de la b\u00FAsqueda es mayor a 20 se sugiere descargar el archivo para modificarlo\n\u00bfDesea Continuar?');
			if(res==true){
				window.open('../../code/ajax/exportarComisionesExcel.php?datos='+respuesta[1]);
				$("#waitingplease").css('display','none');
				return false;
			}else if(res==false){
				$("#waitingplease").css('display','none');
				return false;
			}
		}
		else{
			$("#cajasBusqueda").html(result);
			datos=datos.replace("&caso=1","&caso=2");
			var result=ajaxN(url,datos);
			$("#cajasBusqueda1").html(result);
			$("#waitingplease").css('display','none');
		}
		
	}
	function EditarCaja(id,caso){
		var url="especiales/CajaComisionEditar.php";
		var datos="id_control="+id+"&caso="+caso;
		var result=ajaxN(url,datos);
		$("#fila_x_"+id).html(result);
	}
	function GuardarCaja(id_control){
		var PrecioPublico=$("#PrecioPublico_"+id_control).val(); var Instalacion=$("#Instalacion_"+id_control).val(); var Promocion=$("#Promocion_"+id_control).val(); var ServInstalacion=$("#ServInstalacion_"+id_control).val(); var Complemento=$("#Complemento_"+id_control).val(); var DerechoActivacion=$("#DerechoActivacion_"+id_control).val(); var TotalGanar=$("#TotalGanar_"+id_control).val(); var Accesorio=$("#Accesorio_"+id_control).val(); var DIST=$("#DIST_"+id_control).val(); var Audicel=$("#Audicel_"+id_control).val(); var Total=$("#Total_"+id_control).val(); var TotalDistribuidor=$("#TotalDistribuidor_"+id_control).val(); var TotalDISCOM=$("#TotalDISCOM_"+id_control).val(); var TotalTECEXT=$("#TotalTECEXT_"+id_control).val(); var TotalTECFP=$("#TotalTECFP_"+id_control).val(); var TotalVendedorEXT=$("#TotalVendedorEXT_"+id_control).val(); var TotalVendedorFP=$("#TotalVendedorFP_"+id_control).val(); var DescuentoCliente=$("#DescuentoCliente_"+id_control).val(); var DescuentoFP=$("#DescuentoFP_"+id_control).val();
		
		var url="especiales/CajaComisionGuardar.php";
		var datos="PrecioPublico="+PrecioPublico+"&Instalacion="+Instalacion+"&Promocion="+Promocion+"&ServInstalacion="+ServInstalacion+"&Complemento="+Complemento+"&DerechoActivacion="+DerechoActivacion+"&TotalGanar="+TotalGanar+"&Accesorio="+Accesorio+"&DIST="+DIST+"&Audicel="+Audicel+"&Total="+Total+"&TotalDistribuidor="+TotalDistribuidor+"&TotalDISCOM="+TotalDISCOM+"&TotalTECEXT="+TotalTECEXT+"&TotalTECFP="+TotalTECFP+"&TotalVendedorEXT="+TotalVendedorEXT+"&TotalVendedorFP="+TotalVendedorFP+"&DescuentoCliente="+DescuentoCliente+"&DescuentoFP="+DescuentoFP+"&id_control="+id_control;
		$("#waitingplease").css('display','block');
		var result=ajaxN(url,datos);
		if(result=='exito'){
			$("#waitingplease").css('display','none');
			alert('La informaci\u00F3n se actualiz\u00F3 correctamente');
			EditarCaja(id_control,'2');
		}
	}
	function MuestraImportacion(){
		$.fancybox({
				type: 'iframe',
				href: '../especiales/importaciones.php?idLayout=20',
				maxWidth	: 900,
				maxHeight	: 800,
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){
					// En esta sección se coloca el código que se requiera ejecutar cuando se cierra el fancybox
				}
		});
	}
	</script>
{/literal}
<!--
<div style="z-index:5000; display:none;position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
		<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
		<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
	</div>
-->

<div>
	<div style="width:50%;float:left;border-right:1px solid #E0E0E0;">
	B&uacute;squeda por:
	<table>
		<tr>
			<td style="padding-right:5px">
				<label class="nom_campo_comision">Caja de Comisi&oacute;n:</label>
				<br/><select id="caja"><option value="0">--Caja--</option>
				{section name='Comision' loop=$a_comisiones}
					<option value="{$a_comisiones[Comision].0}">{$a_comisiones[Comision].1}</option>
				{/section}
				</select>
			</td>
			<td><label class="nom_campo_comision">Clave:</label>
				<br/><select id="clave">
					<option value="0">--Clave--</option>
					{section name='Clave' loop=$a_claves}
						<option value="{$a_claves[Clave].0}">{$a_claves[Clave].0}</option>
					{/section}
				</select>
			</td>
			<td style="padding-right:5px"><label class="nom_campo_comision">Tipo Producto:</label>
				<br/><select id="tipoProducto">
					<option value="0">--Tipo  de Producto--</option>
					{section name='Tipo' loop=$a_tipoProductos}
						<option value="{$a_tipoProductos[Tipo].0}">{$a_tipoProductos[Tipo].1}</option>
					{/section}
				</select>
			</td>
			</tr>
		<tr>
			<td><label class="nom_campo_comision">Promoci&oacute;n:</label>
			<br/><select id="promocion">
				<option value="0">--Promoci&oacute;n--</option>
				{section name='Promocion' loop=$a_promociones}
					<option value="{$a_promociones[Promocion].0}">{$a_promociones[Promocion].1}</option>
				{/section}
			</select>
			</td>
			<td><label class="nom_campo_comision">Funcionalidad:</label>
			<br/><select id="funcionalidad">
					<option value="0">--Funcionalidad--</option>
					{section name='Funcionalidad' loop=$a_funcionalidades}
						<option value="{$a_funcionalidades[Funcionalidad].0}">{$a_funcionalidades[Funcionalidad].1}</option>
					{/section}
				</select>
			</td>
			<td><label class="nom_campo_comision">Forma de Pago:</label>
			<br/><select id="formaPago">
					<option value="0">--Forma de Pago--</option>
					{section name='FormaPago' loop=$a_formasPago}
						<option value="{$a_formasPago[FormaPago].0}">{$a_formasPago[FormaPago].1}</option>
					{/section}
				</select>
			</td>
		</tr>
		<tr>
			<td><label class="nom_campo_comision">N&uacute;mero de Equipos:</label>
			<br/><select id="NumEquipos">
					<option value="0">--N&uacute;mero de Equipos--</option>
					{section name='Equipo' loop=$a_numeroEquipos}
						<option value="{$a_numeroEquipos[Equipo].0}">{$a_numeroEquipos[Equipo].1}</option>
					{/section}
				</select>
			</td>
			<td style="padding-right:5px"><label class="nom_campo_comision">Paquete:</label>
			<br/><select id="paquete">
					<option value="0">--Paquete--</option>
					{section name='Paquete' loop=$a_paquetes}
						<option value="{$a_paquetes[Paquete].0}">{$a_paquetes[Paquete].1}</option>
					{/section}
				</select>
			</td>
			<td>
				<input type="button" value="Buscar" class="button_search" onclick="BuscarCajasComisiones()"/>
			</td>
		</tr>
	</table>
</div>
	<div style="width:49%;float:left">
		<input type="button" value="Actualizar por Archivo Modificado" class="button_import_csv" onclick="MuestraImportacion()" style="margin-left:26%;margin-right:26%;margin-top:12%;margin-button:12%;"/>
	</div>
</div>
<div style="width:100%;float:left;padding-top:3px">
<div style="height:665px;width:50%;float:left;">
	<table class="encabezados_cajas" width="100%">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Tipo Prod.</th>
				<th>Promoci&oacute;n</th>
				<th>Funcionalidad</th>
				<th>Forma Pago</th>
				<th>No. Equipos</th>
				<th>Paquete</th>
				<th>Tipo Activaci&oacute;n</th>
			</tr>
		</thead>
	</table>
	<table width="100%" class="detalle_cajas">
		<tbody id="cajasBusqueda" >
		</tbody>
	</table>
</div>
<div style="height:665px;overflow:auto;width:50%;float:left">
	<table class="encabezados_cajas2" width="2310px">
		<tr>
			<th><div style="float:left;width:105px;"><div style="float:left;width:85px;">Precio Publico o Suscripci&oacute;n</div><div style="float:left;width:15px;"><input type="checkbox" disabled title="Editar Columna"/></div></div></th>
			<th>Instalaci&oacute;n</th>
			<th>Promoci&oacute;n</th>
			<th>Serv. Instalaci&oacute;n</th>
			<th>Complemento</th>
			<th>Suma</th>
			<th>Derecho de Activaci&oacute;n</th>
			<th>Total a Ganar</th>
			<th>Accesorio</th>
			<th>DIST</th>
			<th>Audicel</th>
			<th>Total</th>
			<th>Total Distribuidor</th>
			<th>Total DISCOM</th>
			<th>Total TEC EXT</th>
			<th>Total TEC FP</th>
			<th>Total Vendedor EXT</th>
			<th>Total Vendedor FP</th>
			<th>Descuento Cliente</th>
			<th>Descuento FP</th>
			<th>Clave Producto</th>
			<th style="width:15px !important"></th>
		</tr>
	</table>
	<table width="2310px" class="detalle_cajas2">
		<tbody id="cajasBusqueda1">
		</tbody>
	</table>
</div>
</div>
{include file="_footer.tpl"}