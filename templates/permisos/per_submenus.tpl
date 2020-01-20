
<html >
<head>
<link rel="stylesheet" type="text/css" href="{$rooturl}pro_dropdown_2/pro_dropdown_2.css" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos.css"/>
<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
<script language="javascript" src="{$rooturl}js/datepicker/jquery-1.9.1.js"></script>
<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />

</head>

<body>

<form action="#" name="forma_datos_frame" id="forma_datos_frame" method="post" >
	<table  class="tabla_Grid_RC" border="1"  cellspacing="0" align="left">
		{section loop=$reg name=y start=0}
        	<tr class="NormalCell">
				<td width="187" > </td>
				<td width="26" class="header_permisos">Ver</td>
				<td width="26" class="header_permisos">Nvo </td>
				<td width="26" class="header_permisos">Mod</td>
				<td width="26" class="header_permisos">Elim</td>
				<td width="26" class="header_permisos">Imp</td>
				<td width="26" class="header_permisos">Gen</td>
			</tr>
				
			<tr class="NormalCell">
				{section loop=$reg[y] name=x start=1}
					{if $smarty.section.x.index eq '1' }
						<td align="left">
							<a href="#" onClick="despliegaDiv('clase_{$smarty.section.y.index}')"><b> {$reg[y][1]} >> </b></a>
					{elseif $smarty.section.x.index eq '8' }
						</tr>
						<!--<tr  class="NormalCell">-->
							<!--<td  colspan="8">-->
							{* vamos colocando los hijos del menu *}
								<!--<table id="{$smarty.section.y.index}" style="display:none;"  >-->
									{section loop=$reg[y][x] name=w start=0}  
										<tr class="NormalCell clase_{$smarty.section.y.index}" style="display:none;">
											{section loop=$reg[y][x][w] name=z start=1}
												{if $smarty.section.z.index eq '1' }
													<td align="left">
														&nbsp;&nbsp;&nbsp;&nbsp;{$reg[y][x][w][1]}
                                                {else}
													<td class="check_permisos">
														<input name="sub_{$smarty.section.y.index}_{$smarty.section.w.index}_{$smarty.section.z.index}" id="sub_{$smarty.section.y.index}_{$smarty.section.w.index}_{$smarty.section.z.index}"  value="{$reg[y][x][w][0]}|{$smarty.section.z.index}"   type="checkbox"
														{ if $reg[y][x][w][z] eq '1'}
															checked
														{/if}
														>                                  								
												{/if}
													</td>
											{/section}	
										</tr>
									{/section}
								<!--</table>-->
							<tr>
					{else}								
						<td width="25" class="check_permisos" >
							{*seccion de MENUS*}
							<input name="m_{$smarty.section.y.index}_{$smarty.section.x.index}" id="m_{$smarty.section.y.index}_{$smarty.section.x.index}"  value="{$reg[y][0]}|{$smarty.section.x.index}" type="checkbox" onClick="SeleccionaDeselecciona(this.form,this,{$smarty.section.y.index},{$smarty.section.x.index})">
                    {/if}
						</td>
				{/section}
			</tr>
			<input type="hidden" name='countReg_{$smarty.section.y.index}' size="10" value = '{$smarty.section.w.index}'/>
		{/section}
		
		<input type="hidden" name='countRegTodos' size="10" value = "{$smarty.section.y.index}" />
		
	</table>
</form>
{literal}
<script type="text/javascript" language="javascript">

function asignaVal(objform,opcionVal)
{
	objform.id_submenu.value=opcionVal;
}

function SeleccionaDeselecciona(objform,valor,menu,columna)
{
	var k=0;
	
	if (valor.checked == true)
		k=1;
	
	if (objform.elements["countReg_"+menu].value==1)
	{
			objform.elements["sub_"+menu+"_0_"+columna].checked = k;
	}
	else
	{
		for(var i=0; i<objform.elements["countReg_"+menu].value;i++)
		{
			objform.elements["sub_"+menu+"_"+i+"_"+columna].checked = k;
		}		
	}
}

function despliegaDiv(div){
	/*
	var divDesp = document.getElementById(div);
	if(divDesp.style.display == 'none')
		divDesp.style.display = 'block';
	else
		divDesp.style.display = 'none';
	return true;
	*/
	
	if($("." + div).css('display') == 'none')
	{
		$("." + div).show();
	}
	else
	{
		$("." + div).hide();
	}
	return true;
}

</script>
{/literal}

</body>
</html>