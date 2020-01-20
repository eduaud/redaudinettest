{if $idLayout eq '0'}
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
{else}
	{include file="_header.tpl" pagetitle="$contentheader"}
	<script src='{$rooturl}js/multicarga/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
{/if}

{if $informe eq 'Exito'}
	<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 100%;">
		<tr><th><b>LA INFORMACION FUE VALIDADA CORRECTAMENTE</b></th></tr>
	</table>
{/if}
{if $informe eq 'Error'}
	<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 100%;">
		<tr><th colspan="3"><b>Ocurrieron los siguientes errores durante la valicaci&oacute;n</b></th></tr>
		<tr>
			<th>No.<!-- {$filaDatos}{$sql} --></th>
			<th>IRD</th>
			<th>ERROR</th>
		</tr>
		{section loop=$arrErr name=x}
			<tr>
				<td align="center">{$smarty.section.x.iteration}</td>
				<td>{$arrErr[x][1]}</td>
				<td>{$arrErr[x][2]}</td>
			</tr>
		{/section}
		<tr>
			<th colspan="3"><b>La informaci&oacute;n no se ha importado.</b></th>
		</tr>
	</table>
{/if}