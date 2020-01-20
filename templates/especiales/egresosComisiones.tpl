<html>
		<head>
				<script language="javascript" src="{$rooturl}js/datepicker/jquery-1.9.1.js"></script>
				<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
				<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
				<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
				<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
				<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
				<style>
				{literal}
				.busca-com th{
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
				.cuerpo-com td:first-child, .busca-com td:first-child{
						border-left : 1px #C4C5C7 solid;
						}
				.cuerpo-com td, .busca-com td{
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
				.cuerpo-com select, .busca-com select{
						border: 1px solid #ABADB3;
						border-radius: 4px;
						color: rgb(90, 90, 90);
						padding: 4px;
						font-size : 12px;
						color : #999999;
						}
				#datos table td{
						font-weight : bold;
						font-size : 12px;
						padding : 5px;
						}
				#datos table td:first-child{
						padding-right : 10px;
						}
				{/literal}
				</style>
				<script type="text/javascript">
				{literal}
				$(document).ready(function() {
						colocaSubgasto();
						});
						function generaEgreso(){
								var gasto = $("#slct_gasto").find("option:selected").val();
								var subgasto = $("#slct_subgasto").find("option:selected").val();
								var descripcion = $("#descripcion").val();
								var monto = $("#monto_total").val();
								var pedido = $("#control_pedido").val();
								var recibio = $("#recibio").val();
								var ruta = "generaEgreso.php";
								var envio = "gasto=" + gasto + "&subgasto=" + subgasto + "&descripcion=" + descripcion + "&monto=" + monto + "&pedido=" + pedido + "&recibio=" + recibio
								var respuesta = ajaxN(ruta, envio);
								alert(respuesta);
								parent.$.fancybox.close();
								}
						function colocaSubgasto(){
								var gasto = $("#slct_gasto").find("option:selected").val();
								var selectHijo = "slct_subgasto";
								var urlAjax = "colocaSubgasto.php";
								var envio_datos = 'id=' + gasto;  // Se arma la variable de datos que procesara el php
								ajaxCombos(urlAjax, envio_datos, selectHijo);
								}
				{/literal}
				</script>
		</head>
<body>
		<input type="hidden" id="monto_total" value="{$total}"/>
		<input type="hidden" id="recibio" value="{$vendedor}"/>
		<h1 class="encabezado">GENERAR EGRESO DE CAJA CHICA {$sucursal}</h1>
		<br>
		<div id="datos">
				<table>
						<tr>
								<td>Vendedor:</td><td>{$vendedor}</td>
						</tr>
						<tr>
								<td>Fecha de Comisi&oacute;n:</td><td>{$fecha}</td>
						</tr>
				</table>
		</div><br><br>
		<div id="content" style="width:960px">
		<div style="width:400px: float:left">
		<table class="busca-com">
		<caption>Pedidos</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:82px;">Pedido</th>
								<th style="width:82px;">Fecha</th>
								<th style="width:100px;">Monto Pedido</th>
								<th style="width:102px;">Monto Comisi&oacute;n</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-com"> 
						<tbody>
						{assign var="contador" value="1"}
						{section name="filasComisiones" loop=$filasCom}
								<tr>
										<td style="width:20px; text-align:center">{$contador}</td>
										<td style="width:80px; text-align:center">{$filasCom[filasComisiones].0}</td>
										<td style="width:80px; text-align:center">{$filasCom[filasComisiones].1}</td>
										<td style="width:100px; text-align:center">{$filasCom[filasComisiones].2}</td>
										<td style="width:100px; text-align:center">{$filasCom[filasComisiones].3}</td>
										<td style="display:none"><input type="text" id="control_pedido" name="control_pedido" value="{$filasCom[filasComisiones].4}"/></td>
										<td style="display:none">{$contador++}</td>
								</tr>
						{sectionelse}
								<tr>
										<td>No existen pedidos relacionados a este vendedor</td>
								</tr>
						{/section}
						
						</tbody>
				</table>
		</div>
		</div>
		<div style="width:500px: float:right">
				<table class="busca-com">
		<caption>Egresos</caption>
				<thead>
						<tr>
								<th style="width:250px;">Gasto</th>
								<th style="width:250px;">Subgasto</th>
								<th style="width:120px;">Descripci&oacute;n</th>
								<th style="width:80px;">Monto</th>
						</tr>
						<tr>
								<td style="width:150px; text-align:center">
										<select id="slct_gasto" name="slct_gasto" style="width:180px" onchange="colocaSubgasto();">
												{html_options values=$gasto_id output=$gasto_nombre}
										</select>
								</td>
								<td style="width:150px; text-align:center">
										<select id="slct_subgasto" name="slct_subgasto" style="width:180px">
												<option value="0">Seleccione un gasto</option>
										</select>
								</td>
								<td style="width:350px; text-align:center">
										<input type="text" id="descripcion" style="width:340px;"/>
								</td>
								<td style="width:150px; text-align:center">{$monto_total}</td>
						</tr>
					
				</thead>
		</table>
		<p style="display:block; float:right;">
				<input type="button" class="boton" value="Generar Egreso &raquo;" onclick="generaEgreso()"/>
		</p>
		</div>
		</div>
</body>
</html>