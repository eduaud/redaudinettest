<table border="0" class="detalle"> 
	<tbody>
	{assign var="contador" value="1"}
		{section name="pedido" loop=$a_pedidos}
			<tr>
				<td style="width:20px; text-align:center">{$contador}</td>
				<td style="width:150px; text-align:center">{$a_pedidos[pedido].Plaza}</td>
				<td style="width:80px; text-align:center">{$a_pedidos[pedido].Pedido}</td>
				<td style="width:100px; text-align:center">{$a_pedidos[pedido].Cliente}</td>
				<td style="width:100px; text-align:center">{$a_pedidos[pedido].Fecha}</td>
				<td style="width:100px; text-align:center">{$a_pedidos[pedido].Monto}</td>
				<td style="width:100px; text-align:center">&nbsp;&nbsp;</td>
				<td style="width:200px; text-align:center">
				<div style="width : auto">
						<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaPedido({$a_pedidos[pedido].id_control_pedido})"/>
						<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaPedido({$a_pedidos[pedido].id_control_pedido})"/>
						<br><br>
						<textarea style="width:150px" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
				</div>		
				
				</td>
				<td style="display:none"><input id="idOrden{$contador}" type="hidden" value="{$filas[filasOrdenes].1}"/></td>
				<td style="display:none">{$contador++}</td>
			</tr>
		{sectionelse}
			<tr>
				<td>No existen pedidos con estos datos de busqueda</td>
			</tr>
		{/section}
	</tbody>
</table>