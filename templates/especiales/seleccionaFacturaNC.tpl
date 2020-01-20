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
	<script type="text/javascript">
	{literal}
			function buscaFacturas(){
					var cliente = $("#select-cliente").find("option:selected").val();
					if(cliente == 0){
							alert("Seleccione un Cliente");
							}
					else{
							$(".cuerpo-facturas tbody tr").remove();
							var ruta = "llenaTablaFacturas.php";
							var envio = "id=" + cliente + "&caso=1";
							var respuesta = ajaxN(ruta, envio);
							$(".cuerpo-facturas tbody").append(respuesta);
							}
						}
			function seleccionaFactura(factura, e){
					e.preventDefault();
					$(".cuerpo-facturas-detalles tbody tr").remove();
					var ruta = "llenaTablaFacturas.php";
					var envio = "id=" + factura + "&caso=2";
					var respuesta = ajaxN(ruta, envio);
					$(".cuerpo-facturas-detalles tbody").append(respuesta);
					}
			function anadirFacturaDetalle(){
					var valorProductos = new Array();
					$('table.cuerpo-facturas-detalles input[name="idFacturaCheck[]"]:checked').each(function() {
							valorProductos.push($(this).val());
							});
					if(valorProductos.length == 0){
							alert("Seleccione algun producto");
							}
					else{
							parent.$("#campo_productos").val(valorProductos);
							parent.$.fancybox.close();
							}
							
					}
	{/literal}
	</script>
	<style>
		{literal}
		
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
		label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
		.busca-facturas th, .busca-facturas-detalles th{
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
				font-size : 11px;
				font-weight : bold;
				text-align : left;
				padding : 3px 0;
				color : #404651;
				}
		.cuerpo-facturas td:first-child, .cuerpo-facturas-detalles td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-facturas td, .cuerpo-facturas-detalles td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 100px;
				overflow : auto;
				}
		{/literal}	
	</style>
</head>
<body>
		<h1> B&uacute;squeda de Facturas </h1>
		<div id="busquedas">
				<p>Buscar por:</p><br>
				<table>
						<tr>
								<td><label for="select-cliente">Cliente</label></td>
								<td>
										<select name="select-cliente" id="select-cliente" onchange="">
												<option value="0">Seleccione Cliente</option>
												{section name="clientesDatos" loop=$cliente}
														<option value="{$cliente[clientesDatos].0}">{$cliente[clientesDatos].1}</option>
												{/section}
										</select>
								</td>
								<td>&nbsp;&nbsp;&nbsp;<input type="button" class="boton" value="Buscar &raquo;" onclick="buscaFacturas();"/></td>
						</tr>
				</table>
		</div>
		<br><br>
		<div id="facturas">
				<div style="width:100%">
						<table class="busca-facturas">
						<caption>Facturas</caption>
						<thead>
								<tr>
										<th style="width:20px;">No</th>
										<th style="width:100px;">Factura</th>
										<th style="width:150px;">Monto</th>
										<th style="width:150px;">Fecha</th>
										<th style="width:150px;">Seleccionar</th>
								</tr>
						</thead>
				</table>
				</div>
				<div id="scroll-tabla">
						<table border="0" class="cuerpo-facturas"> 
								<tbody>
								</tbody>
						</table>
				</div>
		</div>
		<div id="factura-detalles">
				<div style="width:100%">
						<table class="busca-facturas-detalles">
						<caption>Productos</caption>
						<thead>
								<tr>
										<th style="width:20px;">No</th>
										<th style="width:29px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-facturas-detalles');"/></th>
										<th style="width:200px;">Producto</th>
										<th style="width:200px;">Descripci&oacute;n</th>
										<th style="width:150px;">Cantidad</th>
										<th style="width:150px;">Importe</th>
								</tr>
						</thead>
				</table>
				</div>
				<div id="scroll-tabla">
						<table border="0" class="cuerpo-facturas-detalles"> 
								<tbody>
								</tbody>
						</table>
				</div>
		</div>
		<p style="display:block; float:right; margin-right:70px"><input type="button" class="boton" value="A&ntilde;adir Productos &raquo;" onclick="anadirFacturaDetalle();"/></p>
</body>
</html>