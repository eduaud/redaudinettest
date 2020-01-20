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
				<input type="button" name="modificar" value=" Modificar &raquo;" onclick="location.href='aprobacionArticulosOtrosPedidos.php?t={$t}&k={$k}&op=2&v=0'" class="botonSecundario">&nbsp;&nbsp;&nbsp;
			</td>
			{/if}
			{if $v eq 0}
		    <td>
				<input type="button" name="modificar" id="btn_guardar" value=" Guardar &raquo;" class="botonSecundario"> &nbsp;&nbsp;&nbsp;
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
			Ver pedido
		</td>
		<td height="30px" colspan="3">
			<input class="botonSecundario" value=" Ver &raquo; " onclick='window.open("../general/encabezados.php?t=cmFjX3BlZGlkb3M=&k={$k}&op=2&v=1&hf=10&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==" ,"", "width=1000, height=600, scrollbars=YES");'>
		</td>
	</tr>
</table>


<br><br>
<div class="encabezado_grid">
	Art&iacute;culos que requieren una respuesta
</div>

<form method="post" id="formdata">
	<table  border="1" CELLPADDING="10">
		<thead>
			<tr>
				<th class="buttonHeader_12" width="40px">Sol. Cot.</th>
				<th class="buttonHeader_12" width="160px">Cliente</th>
				<th class="buttonHeader_12" width="150px">Vendedor</th>
				<th class="buttonHeader_12" width="200px">Art&iacute;culo</th>
				<!--<th class="buttonHeader_12" width="50px">Cantidad Reservada</th>-->
				<th class="buttonHeader_12" width="50px">Cantidad Solicitada</th>			
				<th class="buttonHeader_12" width="50px">Ver Pedido</th>
				<th class="buttonHeader_12" width="70px">Estatus</th>
				<th class="buttonHeader_12" width="150px">Observación</th>
			</tr>
		</thead>
		<tbody>
			{section loop=$arregloDetalle name=x}
				{if $smarty.section.x.index is not odd}
					<tr class="NormalCell_gris">
				{else}
					<tr class="NormalCell">
				{/if}
					<td>{$arregloDetalle[x][1]}</td>	
					<td style="padding:7px !important;">{$arregloDetalle[x][2]}</td>	
					<td>{$arregloDetalle[x][3]}</td>	
					<td>{$arregloDetalle[x][5]}</td>	
					<!--<td>{$arregloDetalle[x][7]|number_format:0:'.':','} / {$arregloDetalle[x][6]|number_format:0:'.':','}</td>-->
					<td>{$arregloDetalle[x][9]|number_format:0:'.':','}</td>	
					<td>
						<img src="../../imagenes/general/ver22.png" border="0" onclick='window.open("../general/encabezados.php?t=cmFjX3BlZGlkb3M=&k={$arregloDetalle[x][0]}&op=2&v=1&hf=10&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==" ,"", "width=1000, height=600, scrollbars=YES");' alt="Ver" title="Abrir Archivo" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" style="cursor: pointer;">
					</td>
					<td>
						{if $op eq 2 and $v eq 0}
							<select class="estDetPedido" name="ctrlLib_{$arregloDetalle[x][12]}" id="ctrlLib_{$arregloDetalle[x][12]}" style="width:140px">
								{section loop=$arregloEstatus name=y}
									<option value="{$arregloEstatus[y][0]}">{$arregloEstatus[y][1]}</option>
								{/section}	
							</select>
						{else}
							N/A
						{/if}
					</td>
					<td>
						{if $op eq 2 and $v eq 0}
							<input name="ctrlLib_{$arregloDetalle[x][12]}_obs" id="ctrlLib_{$arregloDetalle[x][12]}_obs" type="text" >
						{else}
							N/A
						{/if}
					</td>
				</tr>
			{/section}
		</tbody>
	</table>
 </form>
            
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
				$("#btn_guardar").click(function(){
					var esValido = 1;
					$(".estDetPedido").each(function() {
						var estatus = $(this).val();
						if(estatus == 7)
						{
							alert("Debe aprobar o no aprobar todos los art\u00EDculos.");
							esValido = 0;
						}
					});
					
					if(esValido == 1)
					{
						$.post("aprobacionArticulosOtrosPedidos.php?action=1&k={/literal}{$k}{literal}&t={/literal}{$t}{literal}", $("#formdata").serialize(), function(res){
							if(res == 1){
								alert("ok");
							} else {
								alert("muere");
							}
						});
					}
				});
				
			});
		{/literal}
	{/if}
</script>

{include file="_footer.tpl" aktUser=$username}