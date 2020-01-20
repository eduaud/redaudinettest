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
	<table width="946" class="tabla_Grid_RC" border="1"  cellspacing="0" align="left">
		<tr class="interfazHeader" >
				<td width="50"></td>
				<td width="72"></td>
				<td width="84"></td>
				<td width="82"></td>
				<td width="84"></td>
				<td width="84"></td>
				<td width="70"></td>
				<td width="70"></td>
				<td width="68"></td>
				<td width="68"></td>
				<td width="72"></td>
				<td width="72"></td>
				<td width="70"></td>
			
		</tr>
		{section loop=$registros name=y start=0}
			<tr class="NormalCell">
			{section loop=$registros[y] name=x start=0}
				    {* si estamos recorriendo el primer pagare por saldar guardamos sus valores*}
				    {if $smarty.section.y.index eq '0' }
						<input name="id_pagare_nosal_{$smarty.section.x.index}" id="id_pagare_nosal_{$smarty.section.x.index}"  type="hidden" value="{$registros[y][x]}">
					{/if}
					{* no mostramos los dos primeros campos de cada registro*}
					{if $smarty.section.x.index > '1' }			
						<td>
							{$registros[y][x]}
						</td>
					{/if}
			{/section}
			
			</tr>
				
		{/section}
		<input name="nada" id="nada"  type="hidden" value="valor nada">
	</table>
</form>
</body>
</html>