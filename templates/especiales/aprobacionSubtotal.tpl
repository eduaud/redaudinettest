{include file="_header.tpl" pagetitle="$contentheader"}

<br /><br />
<a href="" class="thickbox" id="thickbox_href"></a>  
<h1>{$nombre_menu}</h1>

<br>

<table>
	<tbody>
		<tr>
			{if $v eq 1}
			<td>
				<input type="button" name="modificar" value=" Modificar &raquo;" onclick="location.href='aprobacionSubtotal.php?t={$t}&k={$k}&op=2&v=0'" class="botonSecundario">&nbsp;&nbsp;&nbsp;
			</td>
			{/if}
			{if $v eq 0}
		    <td>
				<input type="button" name="modificar" id="btn_rechazar" value=" Rechazar Solicitud &raquo;" class="botonSecundario"> &nbsp;&nbsp;&nbsp;
			</td>
		    <td>
				<input type="button" name="modificar" id="btn_aprobar" value=" Aprobar Solicitud &raquo;" class="botonSecundario"> &nbsp;&nbsp;&nbsp;
			</td>
			{/if}
		    <td>
				<input type="button" name="listado" value=" Listado &raquo;" direccion="../indices/listados.php?t={$t}" onclick="Redirecciona(this)" class="botonSecundario">&nbsp;&nbsp;&nbsp;
        	</td>    
			<td>
				<input type="button" name="listado" value=" Abrir Pedido &raquo;" onclick="abrir_pedido()" class="botonSecundario">&nbsp;&nbsp;&nbsp;
        	</td>    
		</tr>
	</tbody>
</table>

<br>

<table width="100%">
	<tr>
		<td class="nom_campo" width="100px">
			Pedido
		</td>
		<td height="30px" colspan="3">
			<span class="campos_sin_borde" >{$datosPedido[1]}</span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Cliente
		</td>
		<td height="30px" colspan="3">
			<span class="campos_sin_borde" >{$datosPedido[2]}</span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Vendedora
		</td>
		<td width="600px" height="30px" colspan="3">
			<span class="campos_sin_borde" >{$datosPedido[6]}</span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Fecha Creaci&oacute;n
		</td>
		<td height="30px" colspan="3">
			<span class="campos_sin_borde" >{$datosPedido[4]}</span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Fecha Evento
		</td>
		<td height="30px" colspan="3">
			<span class="campos_sin_borde" >{$datosPedido[5]}</span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Estatus
		</td>
		<td height="30px" colspan="3">
			<span class="campos_sin_borde" >{$datosPedido[3]}</span>
		</td>
	</tr>
	
	<tr>
		<td colspan=4>
			<br>
			<div id="div_fila_catalogo_13" style="width:920px; height:15px; background-color:#000; color:#FFF; font-size:10pt; padding-top:2px; padding-bottom:2px ">
				<b> 
					<div style=""> &nbsp;&nbsp; Montos </div>
				</b>
			</div>
			<br>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<h4>Propuesto</h4>
		</td>
		<td colspan="2">
			<h4>Solicitado</h4>
		</td>
	</tr>
	
	<tr>
		<td class="nom_campo" width="100px">
			Subtotal Art&iacute;culos
		</td>
		<td height="30px">
			<span class="campos_sin_borde" id="subTotalArt" valor="{$datosPedido[9]}"> {$datosPedido[9]|number_format:2:'.':','}  </span>
		</td>
		<td class="nom_campo" width="100px">
			Subtotal Art&iacute;culos
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[8]|number_format:2:'.':','} </span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px" title="Flete, vi&aacute;ticos, montaje extraordinario y desmontaje">
			Subtotal Otros Cobros
		</td>
		<td height="30px">
			<span class="campos_sin_borde" id="subTotalOtros" valor="{$datosPedido[10]}" >  {$datosPedido[10]|number_format:2:'.':','} </span>
		</td>
		<td class="nom_campo" width="100px" title="Flete, vi&aacute;ticos, montaje extraordinario y desmontaje">
			Subtotal Otros Cobros
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[10]|number_format:2:'.':','} </span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Total
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[12]|number_format:2:'.':','} </span>
		</td>
		<td class="nom_campo" width="100px">
			Total
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[11]|number_format:2:'.':','} </span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			IVA
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[14]|number_format:2:'.':','} </span>
		</td>
		<td class="nom_campo" width="100px">
			IVA
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[13]|number_format:2:'.':','} </span>
		</td>
	</tr>
	<tr>
		<td class="nom_campo" width="100px">
			Gran Total
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[16]|number_format:2:'.':','} </span>
		</td>
		<td class="nom_campo" width="100px">
			Gran Total
		</td>
		<td height="30px">
			<span class="campos_sin_borde" > {$datosPedido[15]|number_format:2:'.':','} </span>
		</td>
	</tr>
	{if $v eq 0}
	<tr>
		<td class="nom_campo" width="100px">
			Observaciones
		</td>
		<td height="30px" colspan="3">
			<textarea class="campos_req" rows="4" cols="50" id="observaciones"></textarea>
		</td>		
	</tr>
	{/if}
</table>


<br><br>
<div class="encabezado_grid">
	Detalle de art&iacute;culos solicitados (Solo Informativo)
</div>

<table  border="1" CELLPADDING="10">
	<thead>
		<tr>
			<th class="buttonHeader_12" width="150px">Tipo</th>
			<th class="buttonHeader_12" width="350px">Art&iacute;culo</th>
			<th class="buttonHeader_12" width="80px">Cantidad</th>
			<th class="buttonHeader_12" width="80px">Factor costo</th>
			<th class="buttonHeader_12" width="100px">Precio propuesto</th>
			<th class="buttonHeader_12" width="100px">Precio solicitado</th>
			<th class="buttonHeader_12" width="100px">Importe propuesto</th>
			<th class="buttonHeader_12" width="100px">Importe solicitado</th>
			<th class="buttonHeader_12" width="100px">Diferencia</th>
		</tr>
	</thead>
	<tbody>
		{section loop=$arregloDetalle name=x}
			{if $smarty.section.x.index is not odd}
				<tr class="NormalCell_gris">
			{else}
				<tr class="NormalCell">
			{/if}
				<td>{$arregloDetalle[x][0]}</td>	
				<td style="padding:7px !important;">{$arregloDetalle[x][1]}</td>	
				<td>{$arregloDetalle[x][2]}</td>	
				<td>{$arregloDetalle[x][3]}</td>	
				<td>$ {$arregloDetalle[x][4]|number_format:2:'.':','}</td>	
				<td>$ {$arregloDetalle[x][5]|number_format:2:'.':','}</td>	
				<td>$ {$arregloDetalle[x][6]|number_format:2:'.':','}</td>	
				<td>$ {$arregloDetalle[x][7]|number_format:2:'.':','}</td>	
				<td>$ {$arregloDetalle[x][8]|number_format:2:'.':','}</td>	
			</tr>
		{/section}
	</tbody>
</table>

            
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->

<script>
	{literal}
		function abrir_pedido(){
			var ruta = "../general/encabezados.php?t=cmFjX3BlZGlkb3M=&k={$k}&k=1&op=2&v=1&hf=10&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";
			window.open(ruta,"", "width=1000, height=600, scrollbars=YES");				 
		}
	{/literal}
	
	{if $v eq 0}
		{literal}
			$( document ).ready(function() {
				$("#btn_aprobar").click(function(){
					if($("#observaciones").val() != "")
					{
						if(confirm(String.fromCharCode(191) + "Confirma que desea aprobar el descuento indicado?"))
						{	
							$.getJSON("aprobacionSubtotal.php?action=1&t={/literal}{$t}{literal}&id_control={/literal}{$k}{literal}&resp=1&obs=" + $("#observaciones").val(),
								function(data){
									if(data.mensaje != "")
									{
										alert(data.mensaje);
										location.href='../indices/listados.php?t={/literal}{$t}{literal}';
									}
									else
										alert("Error desconocido");
								}
							);
						}	
					}
					else
					{
						alert("Para aceptar la solicitud debe agregar una observaci\u00F3n");
					}
				});
				
				$("#btn_rechazar").click(function(){
					if($("#observaciones").val() != "")
					{
						if(confirm(String.fromCharCode(191) + "Confirma que desea rechazar el descuento?"))
						{	
							$.getJSON("aprobacionSubtotal.php?action=1&t={/literal}{$t}{literal}&id_control={/literal}{$k}{literal}&resp=2&obs=" + $("#observaciones").val(),
								function(data){
									if(data.mensaje != "")
									{
										alert(data.mensaje);
										location.href='../indices/listados.php?t={/literal}{$t}{literal}';
									}
									else
										alert("Error desconocido");
								}
							);
						}					
					}
					else
					{
						alert("Para rechazar la solicitud debe agregar una observaci\u00F3n");
					}
				});

				/*
				$("#porcentajeAprobado").on("blur", function(){
					valorDescuento = parseFloat($(this).val());
					valorSubtotArt = parseFloat($("#subTotalArt").attr("valor"));
					valorSubtotOtros = parseFloat($("#subTotalOtros").attr("valor"));
					
					valorSubTotArtAprobado = valorSubtotArt - (valorSubtotArt * valorDescuento / 100);
					valorTotalAprobado = valorSubTotArtAprobado + valorSubtotOtros;
					
					$("#porcentajeAprobado").val(valorDescuento);
					$("#subTotArtAprobado").html(valorSubTotArtAprobado);
					$("#totalAprobado").html(valorTotalAprobado);
				});
				*/
			});
		{/literal}
	{/if}
</script>

{include file="_footer.tpl" aktUser=$username}