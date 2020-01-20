{include file="_header.tpl" pagetitle="$contentheader"}

<br /><br />
<a href="" class="thickbox" id="thickbox_href"></a>  
<h1>{$nombre_menu}</h1>

<br>
<br>
<br><br>
<div class="encabezado_grid">
	Detalle de art&iacute;culos  (Solo Informativo)
</div>

<table  border="1" CELLPADDING="10">
	<thead>
		<tr>
			<th class="buttonHeader_12" width="150px">Tipo</th>
			<th class="buttonHeader_12" width="350px">Art&iacute;culo</th>
			<th class="buttonHeader_12" width="100px">Cantidad</th>
			<th class="buttonHeader_12" width="100px">Precio unitario</th>
			<th class="buttonHeader_12" width="100px">Importe final</th>
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
				<td>$ {$arregloDetalle[x][3]|number_format:2:'.':','}</td>	
				<td>$ {$arregloDetalle[x][4]|number_format:2:'.':','}</td>	
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
					if(parseFloat($("#porcentajeAprobado").val()) <= 100 && parseFloat($("#porcentajeAprobado").val()) > 0)
					{
						if(confirm(String.fromCharCode(191) + "Confirma que desea aprobar el descuento indicado?"))
						{	
							$.getJSON("aprobacionSubtotal.php?action=1&t={/literal}{$t}{literal}&id_control={/literal}{$k}{literal}&descuento=" + $("#porcentajeAprobado").val() + "&obs=" + $("#observaciones").val(),
								function(data){
									if(data[0].mensaje != "")
									{
										alert(data[0].mensaje);
										location.href='../indices/listados.php?t={$t}';
									}
									else
										alert("Error desconocido");
								}
							);
						}	
					}
					else
					{
						alert("El porcentaje ingresado no es valido");
					}
				});
				
				$("#btn_rechazar").click(function(){
					if($("#observaciones").val() != "")
					{
						if(confirm(String.fromCharCode(191) + "Confirma que desea rechazar el descuento?"))
						{	
							$.getJSON("aprobacionSubtotal.php?action=1&t={/literal}{$t}{literal}&id_control={/literal}{$k}{literal}&descuento=0&obs=" + $("#observaciones").val(),
								function(data){
									if(data[0].mensaje != "")
									{
										alert(data[0].mensaje);
										location.href='../indices/listados.php?t={$t}';
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
			});
		{/literal}
	{/if}
</script>

{include file="_footer.tpl" aktUser=$username}