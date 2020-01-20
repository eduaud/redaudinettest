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
		.busca-pedidos th{
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

		.cuerpo-pedidos td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-pedidos td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 250px;
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
		.tabla-busquedas td{
				padding : 5px;
				}
		
		{/literal}
</style>
<script>
{literal}
function buscaFactura(){
	var pedido = $("#busca_pedido").val();
	if(pedido == ""){
		alert("Anote un numero de factura");
	}
	else{
		$(".cuerpo-pedidos tbody tr").remove();
		var ruta = "llenaTablaFacturasParaDepositosBancarios.php";
		var envio = "idPedido=" + pedido;
		var respuesta = ajaxN(ruta, envio);
		$(".cuerpo-pedidos tbody").append(respuesta);
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
		$(".cuerpo-pedidos tbody tr").remove();
		var ruta = "llenaTablaFacturasParaDepositosBancarios.php";
		var envio = "idCliente=" + idCliente + "&fecini=" + fecha_inicio + "&fecfin=" + fecha_fin;
		var respuesta = ajaxN(ruta, envio);
		$(".cuerpo-pedidos tbody").append(respuesta);
	}
}
function anadirFactura(){
	var valorPedidos = new Array();
	$('table.cuerpo-pedidos input[name="idPedidoCheck[]"]:checked').each(function() {
		valorPedidos.push($(this).val());
	});
	if(valorPedidos.length == 0){
		alert("Seleccione alguna factura");
	}
	else{
		parent.$("#campo_pedidos").val(valorPedidos);
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
<p>Buscar por:</p>
<div id="provedores" style="float:left; padding-left:25px;">
	<table class="tabla-busquedas">
		<tr>
			<td><label for="select-cliente">Cliente</label></td>
			<td colspan="3">
			<select name="select-cliente" id="select-cliente" onChange="">
			<option value="0">Seleccione Cliente</option>
			{section name="clientesDatos" loop=$cliente}
			<option value="{$cliente[clientesDatos].0}">{$cliente[clientesDatos].1}</option>
			{/section}
			</select>
			</td>
		</tr>
		<tr>
			<td><label for="fechadel">Fecha del&nbsp;&nbsp;&nbsp;</label></td>
			<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);"/></td>
			<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
			<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);"/></td>
		</tr>
	</table>
	<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onClick="buscaCliente()"/></p>
</div>


<div id="pedido" style="float:left; padding-left:25px;">
	<label for="busca_pedido">Factura</label>
	<input type="text" id="busca_pedido" name="busca_pedido" class="busca-producto"/>
	<br>
	<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onClick="buscaFactura();"/></p>
	</div>
	</div>
	<div style="clear:both;"></div>
	<div style="margin:20px 0; width:100%">
	<table class="busca-pedidos">
	<caption>Facturas</caption>
	<thead>
	<tr>
	<th style="width:20px;">No</th>
	<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-pedidos');"/></th>
	<th style="width:100px;">Factura</th>
	<th style="width:250px;">Cliente</th>
	<th style="width:150px;">Monto</th>
	<th style="width:150px;">Fecha de Pago</th>
	<th style="width:150px;">Forma de Pago</th>
	<th style="width:150px;">Documento</th>
	</tr>
	</thead>
	</table>
	<div id="scroll-tabla">
	<table border="0" class="cuerpo-pedidos" width="100%"> 
	<tbody>
	</tbody>
	</table>
	</div>
</div>
<p style="display:block; float:right;"><input type="button" class="boton" value="A&ntilde;adir Facturas &raquo;" onClick="anadirFactura();"/></p>
<br /><br />
</body>
</html>