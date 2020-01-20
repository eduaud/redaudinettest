{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript">
{literal}
		function generarPedido(posX){
				var idPrepedido = $("#idPrepedido" + posX).val();
				$(location).attr('href', '../general/encabezados.php?t=YWRfcGVkaWRvcw==&k=&op=1&idPedido=' + idPrepedido); 
				}
{/literal}
</script>

<style>
{literal}

		.genera-prepedido th{
				padding : 5px;
				font-weight : bold;
				font-size : 10px;
				text-align : center;
				height : 20px;
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

		.cuerpo-prepedidos td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-prepedidos td, .resultado-productos td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 300px;
				overflow : auto;
				}
				
		.genera {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;
			color: #ffffff;
			padding: 3px 6px;
			background: -moz-linear-gradient(
				top,
				#6b6b6b 0%,
				#000000);
			background: -webkit-gradient(
				linear, left top, left bottom,
				from(#6b6b6b),
				to(#000000));
			-moz-border-radius: 6px;
			-webkit-border-radius: 6px;
			border-radius: 6px;
			border: 1px solid #000000;
			-moz-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,0.7);
			-webkit-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,0.7);
			box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,0.7);
			text-shadow:
				0px -1px 0px rgba(000,000,000,0.4),
				0px 1px 0px rgba(255,255,255,0.3);
			cursor : pointer;
		}

{/literal}
</style>

<h1 class="encabezado">Generaci&oacuten de Pedidos a Partir de Prepedidos</h1>
<br>
<div id="productos-agregados" style="margin:20px 0; width:966px">
		<table class="genera-prepedido">
		<caption>Prepedidos</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:250px;">Vendedor</th>
								<th style="width:250px;">Cliente</th>
								<th style="width:150px;">Fecha y Hora</th>
								<th style="width:120px;">Total</th>
								<th style="width:150px;">Generar Pedido</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-prepedidos"> 
						<tbody>
								{assign var="contador" value="1"}
								{section name="filasPrepedidos" loop=$prepedido}
										<tr>
												<td style="width:20px; text-align:center">{$contador}</td>
												<td style="width:250px; text-align:center">{$prepedido[filasPrepedidos].0}</td>
												<td style="width:250px; text-align:center">{$prepedido[filasPrepedidos].1}</td>
												<td style="width:150px; text-align:center">{$prepedido[filasPrepedidos].2}</td>
												<td style="width:120px; text-align:center">{$prepedido[filasPrepedidos].4}</td>
												<td style="width:144px; text-align:center"><input type="button" id="generar" class="genera" value="Generar Pedido" onClick="generarPedido({$contador})"/></td>
												<td style="display:none"><input id="idPrepedido{$contador}" type="hidden" value="{$prepedido[filasPrepedidos].3}"</td>
										</tr>
								<div style="display:none">{$contador++}</div>
								{sectionelse}
										<tr>
												<td>No existen prepedidos registrados</td>
										</tr>
								{/section}
						</tbody>
				</table>
		
		</div>
		
		
		
</div>



{include file="_footer.tpl"}