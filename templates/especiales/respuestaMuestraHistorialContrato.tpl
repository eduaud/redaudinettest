{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
{*$sql*}
<table>
	<tbody>
	{section name="fila" loop=$filas}
		<tr height="18">
			<td class="letra_detalle" align="right" width="30">{$contador++}&nbsp;</td>
			<td class="letra_detalle" align="left" width="150">&nbsp;{$filas[fila].0}</td>
			<td class="letra_detalle" align="left" width="60">&nbsp;{$filas[fila].1}</td>
			<td class="letra_detalle" align="left" width="200">&nbsp;{$filas[fila].2}</td>
			<td class="letra_detalle" align="left" width="210">&nbsp;{$filas[fila].4}</td>
			<td class="letra_detalle" align="right" width="100">
			<!-- Si id_accion_contrato es 1 o 7 y esta activo -->
			{if ($filas[fila].10 eq "1" || $filas[fila].10 eq "11") && $filas[fila].8 eq "1"}
				<a href="#" onclick="editarHistorialContrato('{$filas[fila].7}','{$filas[fila].5}','6','{$filas[fila].11}');" title="Modificar Promocion"><img src="{$rooturl}imagenes/promocion.png" height="12"></a>
			{else}
				{*<img src="{$rooturl}imagenes/promocion_2.png" height="12"> *}
			{/if}
			<!-- Si id_accion_contrato es 1 o 7 y esta activo -->
			{if ($filas[fila].10 eq "1" || $filas[fila].10 eq "11") && $filas[fila].8 eq "1"}
				<a href="#" onclick="editarHistorialContrato('{$filas[fila].7}','{$filas[fila].5}','5','{$filas[fila].11}');" title="Modificar NIT"><img src="{$rooturl}imagenes/usuarios.png" height="12"></a>
			{else}
				{*<img src="{$rooturl}imagenes/usuarios_2.png" height="12"> *}
			{/if}
			<!-- Si id_accion_contrato es 1 y esta activo -->
			{if $filas[fila].10 eq "1" && $filas[fila].8 eq "1"}
				<a href="#" onclick="editarHistorialContrato('{$filas[fila].7}','{$filas[fila].5}','4','{$filas[fila].11}');" title="Modificar Precio de Suscripci&oacute;n"><img src="{$rooturl}imagenes/pesos.png" height="12"></a>
			{else}
				{*<img src="{$rooturl}imagenes/pesos_2.png" height="12"> *}
			{/if}
			<!-- Si id_contra_recibo es 11 y esta activo -->
			{* if $filas[fila].6 eq "11" && $filas[fila].8 eq "1" *}
			{if $filas[fila].10 eq "11" && $filas[fila].8 eq "1"}
				<a href="#" onclick="editarHistorialContrato('{$filas[fila].7}','{$filas[fila].5}','3','{$filas[fila].11}');" title="Modificar Fecha de Movimiento"><img src="{$rooturl}imagenes/calendario-de-texto-icono-6814-128.png" width="10"></a>
			{else}
				{*<img src="{$rooturl}imagenes/calendario-de-texto-icono-6814-128_2.png" width="10"> *}
			{/if}
			<!-- Si id_contra_recibo es 11 y esta activo -->
			{* if $filas[fila].6 eq "11" && $filas[fila].8 eq "1" *}
			{if $filas[fila].10 eq "11" && $filas[fila].8 eq "1"}
				<a href="#" onclick="actualizarInformacionDeContratos('{$filas[fila].7}','{$filas[fila].5}','2','{$filas[fila].11}');" title="Elimira Registro"><img src="{$rooturl}imagenes/ic18x18_tache.png" width="10"></a>
			{else}
				{*<img src="{$rooturl}imagenes/ic18x18_tache_2.png" width="10"> *}
			{/if}
			</td>
		</tr>
	{sectionelse}
		<tr><td colspan="6" align="center" class="letra_detalle" width="750" height="20">No existen Contratos a mostrar</td></tr>
	{/section}
	</tbody>
</table>
