{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />

<h1>Bienvenido</h1>
	
{if $num_mensajes > 0}
	<div class="tableContainer" id="tableContainer">
		<table width="auto" cellspacing="0" cellpadding="0" border="0" class="scrollTable" style="margin: auto;">
			<thead class="fixedHeader">
				<tr class="alternateRow">
					<th style="width: 800px;"><img height="20" width="30" alt="" src="../../imagenes/mensaje.png">Mensaje</th>
					<th><img height="20" width="30" alt="" src="../../imagenes/fecha.png">Fecha</th>
				</tr>
			</thead>
			<tbody class="scrollContent" style="width: 1036px; height: 400px;">
				{section name=mensaje loop=$arr_mensajes}
					{assign var="residuo" value=$smarty.section.mensaje.index%2}
					{if $residuo == 0}
						<tr class="normalRow">
					{else}
						<tr class="normalRow_2">
					{/if}
						<td style="width: 800px;"><p>{$arr_mensajes[mensaje].0}</p></td>
						<td style="text-align: center;"><p>{$arr_mensajes[mensaje].1}</p></td>
					</tr>
				{/section}
			</tbody>
		</table>
	</div>
{else}
	<div class="iconos-home">
		{section loop=$regmenu name=q}
			<div class="holder">
				<a href="{$rooturl}{$regmenu[q][10]}{$regmenu[q][0]}">
					<img class="fade-out" src="{$rooturl}imagenes/{$regmenu[q][18]}.png" alt="Cat&aacute;logos" name="Image{$smarty.section.q.iteration-1}" title="{$regmenu[q][1]}"/>
					<div class="caption"><br>{$regmenu[q][1]}</div>
				</a>
			</div>
		{/section}
	</div>
{/if}

	
{include file="_footer.tpl" aktUser=$username}