<html>
<head>
	<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
	<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
		
	<script language="javascript" src="{$rooturl}js/datepicker/jquery-1.9.1.js"></script>
	<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
    <link rel="stylesheet" href="{$rooturl}js/datepicker/jquery-ui-themes-1.10.2/themes/cupertino/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
	
	<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript" src="{$rooturl}js/calendar.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-es.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-setup.js"></script>
	
	<script language="javascript" src="{$rooturl}js/funciones.js"></script>
    <script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
	<script language="javascript" src="{$rooturl}js/pedidos.js"></script>
	<script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
	<style>
		{literal}
		.busca-producto{
				padding : 7px !important;
				font-size : 14px;
				width : 250px;
				height : 30px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
		#busquedas p{
				font-size:13px; 
				font-weight:bold;
				}
		#busquedas select{
				border: 1px solid rgb(219, 225, 235);
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				width: 300px;
				font-size : 12px;
				color : #999999;
				}
		.cuerpo-facturas select{
				border: 1px solid rgb(219, 225, 235);
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				width: 150px;
				font-size : 11px;
				color : #999999;
				}
		.busca-facturas th{
				padding : 5px;
				font-weight : bold;
				font-size : 10px;
				text-align : center;
				background-color: #FAFBFF;
				background-image: -o-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -moz-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -webkit-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -ms-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: linear-gradient(to bottom, #FAFBFF 0%, #B3B4B5 100%);
				}
		caption{
				font-size : 13px;
				font-weight : bold;
				text-align : left;
				padding : 10px 0;
				color : #404651;
				}

		.cuerpo-facturas td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-facturas td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : 1200px;
				height : 117px;
				overflow : auto;
				}
		
		.rechazo{
				font-size : 11px;
				color : #999999;
				}
		
		.fechas_ordenes{
				padding : 5px !important;
				font-size : 12px;
				width : 125px;
				height : 25px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		.tabla-facturas td{
				padding : 5px;
				}
		
		{/literal}
</style>
<script>
{literal}
	function buscaFactura(){
				var factura = $("#busca_factura").val();
				var total = $("#busca_total").val();
				if(factura == "" && total == ""){
						alert("Anota un numero de factura\no el monto total para comenzar la busqueda");
						}
				else{
						if($("#sel-todos").is(':checked'))
								$("#sel-todos").prop("checked", "");
						$(".cuerpo-facturas tbody tr").remove();
						var ruta = "llenaTablaFacturasDB.php";
						var envio = "idFactura=" + factura + "&total=" + total;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-facturas tbody").append(respuesta);
						}
				}
	function buscaCliente(){
					var idCliente = $("#select-cliente").find("option:selected").val();
					var fecha_inicio = $("#fechadel").val();
					var fecha_fin = $("#fechaal").val();
					
					var FechaInicioConv = convierteFechaJava(fecha_inicio);
					var FechaFinConv = convierteFechaJava(fecha_fin);
					if(FechaInicioConv > FechaFinConv){
							alert("La fecha final no puede ser mayor a la fecha inicial");
							}
					else{
							if($("#sel-todos").is(':checked'))
									$("#sel-todos").prop("checked", "");
							$(".cuerpo-facturas tbody tr").remove();
							var ruta = "llenaTablaFacturasDB.php";
							var envio = "idCliente=" + idCliente + "&fecini=" + fecha_inicio + "&fecfin=" + fecha_fin;
							var respuesta = ajaxN(ruta, envio);
							$(".cuerpo-facturas tbody").append(respuesta);
							}
					
					}
function anadirFactura(){
		var valorFacturas = new Array();
		var cantidades = new Array();
		var formas = new Array();
		var idFacturasR = $("#id_facturasR").val();
		var facturasR = idFacturasR.split(",");
		var contadorF = facturasR.length;
		var duplicado = 0;
		var cadena = "";
		$('table.cuerpo-facturas input[name="idFacturaCheck[]"]:checked').each(function() {
				cantidades.push($("#montoFactura" + $(this).val()).val().replace(",", ""));
				valorFacturas.push($(this).val());
				formas.push($("#slct_cobros" + $(this).val()).find("option:selected").val());
				if(contadorF > 0){
						for(var i=0; i<contadorF;i++){
								if(facturasR[i] == $(this).val()){
										cadena += $("#nomFactura" + $(this).val()).text() + ", ";
										duplicado += 1;
										}
								}
						}
				});
		if(valorFacturas.length == 0){
				alert("Seleccione alguna factura");
				}
		else if(duplicado > 0){
				alert("La factura " + cadena + " ya esta seleccionada");
				}
		else{
				parent.$("#campo_facturas").val(valorFacturas);
				parent.$("#campo_cantidades").val(cantidades);
				parent.$("#campo_formas").val(formas);
				parent.$.fancybox.close();
				}
				
		}
{/literal}
</script>
		<title>Buscar Facturas</title>
</head>

<body>
		<h1> B&uacute;squeda de Facturas </h1>
<div id="busquedas">
<input type="hidden" id="id_facturasR" name="id_facturasR" class="busca-producto" value="{$facturasR}"/>
<p>Buscar por:</p><br>
		<div id="provedores" style="float:left; padding-left:25px;">
			<table class="tabla-busquedas">
				<tr>
				<td>
						<label for="select-cliente">Cliente</label>
				</td>
				<td colspan="3">
						<select name="select-cliente" id="select-cliente" onchange="">
								<option value="0">Seleccione Cliente</option>
								{section name="clientesDatos" loop=$cliente}
								<option value="{$cliente[clientesDatos].0}">{$cliente[clientesDatos].1}</option>
								{/section}
						</select>
				</td>
				</tr>
						<tr>
								<td><label for="fechadel">Fecha del&nbsp;&nbsp;&nbsp;</label></td>
								<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);" readonly/></td>
								<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
								<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);" readonly/></td>
						</tr>
				</table>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaCliente()"/></p>
						
		</div>
		<div id="busqueda-factura" style="float:left; padding-left:25px;">
		<table>		
				<tr>
						<td><label for="busca_factura">Factura</label></td>
						<td><input type="text" id="busca_factura" name="busca_factura" class="busca-producto"/></td>
				</tr>
				<tr>
						<td><label for="busca_total">Total</label></td>
						<td><input type="text" id="busca_total" name="busca_total" class="busca-producto" style="width:100px;" onkeydown="return noletras(event);"/></td>
						
				</tr>
		</table>	
		<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaFactura()"/></p>
		</div>
</div>
<div style="clear:both;"></div>
<div style="margin:20px 0; width:1200px; overflow : auto;" >
		<table class="busca-facturas">
		<caption>Facturas</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-facturas');"/></th>
								<th style="width:80px;">Factura</th>
								<th style="width:200px;">Cliente</th>
								<th style="width:120px;">Fecha</th>
								<th style="width:100px;">Total</th>
								<th style="width:150px;">Pagos</th>
								<th style="width:150px;">Saldo</th>
								<th style="width:250px;">Forma de Cobro</th>
								<th style="width:200px;">Monto</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-facturas"> 
						<tbody>
						</tbody>
				</table>
		</div>
</div>
<p style="display:block; float:right;"><input type="button" class="boton" value="A&ntilde;adir Facturas &raquo;" onclick="anadirFactura();"/></p>
</body>
</html>