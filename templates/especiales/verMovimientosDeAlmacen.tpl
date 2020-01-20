{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<br /><br /><br />
<table align="center" border="0" class="encabezados">
	<thead>
		<tr BGCOLOR="#F2F2F2">
			<th style="width:24px;"  class="letra_encabezado">NO.{*$sQuery*}</th>
			<th style="width:60px; text-align:center;" class="letra_encabezado">ID MOV</th>
			<th style="width:240px; text-align:center" class="letra_encabezado">ALMACEN</th>
			<th style="width:250px; text-align:center" class="letra_encabezado">F. MOVIMIENTO</th>
			<th style="width:200px; text-align:center" class="letra_encabezado">H. MOVIMIENTO</th>
			<th style="width:450px; text-align:center" class="letra_encabezado">PROVEEDOR</th>
			<th style="width:250px; text-align:center" class="letra_encabezado">PRODUCTO</th>
			<th style="width:50px; text-align:center" class="letra_encabezado">CANTIDAD</th>
		</tr>
	</thead>
	<tbody>
{section name="detalle" loop=$detalles}
		<tr>
			<td align="center" class="letra_detalle">{$contador++}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].0}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].2}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].3}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].4}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].6}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].8}</td>
			<td align="center" class="letra_detalle">{$detalles[detalle].9}</td>
		</tr>
{/section}
	</tbody>
</table>