{include file="_header.tpl" pagetitle="$contentheader"}

<style>
		{literal}
		label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
		.caja-texto{
				padding : 5px !important;
				font-size : 12px;
				width : 150px;
				height : 15px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FEFDFD;
				color : #6E6E6E;
				}
		{/literal}
</style>


<h1 class="encabezado">Pedidos</h1>
<table>
		<tr>
				<td><input type="button" class="boton" value="Guardar &raquo;" onclick="nuevaLista()"/></td>
				<td style="padding-left:15px;"><input type="button" class="boton" value="Solicitar Descuento &raquo;" onclick="registraLista()"/></td>
				<td style="padding-left:15px;" valign="bottom" id="respuesta"></td>
		</tr>
</table>
<div id="wrapper" style="margin-top:30px;">
		<div id="col-izquierda">
				<label for="pedidoNo">Pedido No.</label><input type="text" id="noPedido" name="noPedido" class="caja-texto"/>
				<br>
				<label for="sucursal">Sucursal</label><input type="text" id="sucursal" name="sucursal" class="caja-texto"/>
		</div>
</div>


{include file="_footer.tpl"}