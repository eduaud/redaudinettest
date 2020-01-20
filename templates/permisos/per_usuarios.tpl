<html >
<head>
 
<link rel="stylesheet" type="text/css" href="{$rooturl}css/pro_dropdown_2.css" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos.css"/>
<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />
<link href="../../css/gridSW.css" rel="stylesheet" type="text/css" />

</head>

<body>
<form action="#" name="forma_datos_i" id="forma_datos_i" method="post" >
	<table  class="tabla_Grid_RC" border="1"  cellspacing="0" align="left">
		<tr class="interfazHeader" >
				
				<td >Usuario</td>
				<td >Nvo</td>
				<td >Ver</td>
				<td >Mod</td>
				<td >Elim</td>
				<td >Imp</td>
			
			
		</tr>
		{section loop=$registrosusr name=y start=0}
			<tr class="NormalCell">
				{section loop=$registrosusr[y] name=x start=1}
				<td class="listado_permiso">
				 {if $smarty.section.x.index eq '1'  }
					
					<input name="id_{$smarty.section.y.index}" id="id_submenu{$smarty.section.y.index}"  type="hidden" value="{$registrossubr[y][0]}">
					{$registrosusr[y][x]}		
					
				{else}
						<input name="id_hidden_{$smarty.section.y.index}_{$smarty.section.x.index}" id="id_hidden_{$smarty.section.y.index}_{$smarty.section.x.index}"  type="hidden" >
						
						{if $registrosusr[y][x] neq 0}
						<input name="id_checked_{$smarty.section.y.index}_{$smarty.section.x.index}" id="id_checked_{$smarty.section.y.index}_{$smarty.section.x.index}"  type="checkbox" checked="checked">				
						{else}
						<input name="id_checked_{$smarty.section.y.index}_{$smarty.section.x.index}" id="id_checked_{$smarty.section.y.index}_{$smarty.section.x.index}"  type="checkbox">				
						{/if}
				{/if}
				</td>		
				{/section}
			</tr>
		{/section}
		
		<input type="hidden" name='countReg' size="10" value = '{$smarty.section.y.index}'/>
		
	</table>
</form>
</body>
</html>