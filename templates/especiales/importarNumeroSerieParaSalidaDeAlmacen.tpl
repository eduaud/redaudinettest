{if $idLayout eq '0'}
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
{else}
	{include file="_header.tpl" pagetitle="$contentheader"}
	<script src='{$rooturl}js/multicarga/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
{/if}
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->
<div>
<h1 class="encabezado">&nbsp;&nbsp;Importaci&oacute;n {$nombreLayout}</h1>
<h3><p>&Uacute;nicamente archivos tipo CSV.</p></h3>
</div>
<form id="formulario1" name="formulario1" action="" method="post" enctype="multipart/form-data" onsubmit="return validarFile(this)" style="width: 80%; margin: auto;">
	<div align="center">
		<table>
			<tr>
				<td align="center">
					<input type="file" name="archivo" id="archivo" style="padding-bottom: 30px;"></input>
					<input type="hidden" name="action" value="upload">
				</td>
				<td style="vertical-align: top;"><input type="submit" value="Subir archivo" class="boton"></input></td>
			</tr>
		</table>
	</div>
</form>

{if $informe eq 'Exito'}
	<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 100%;">
		<tr><th><b>LIA INFORMACION FUE VALIDADA CORRECTAMENTE</b></th></tr>
	</table>
{/if}
{if $informe eq 'Error'}
	<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 100%;">
		<caption><b>Ocurrieron los siguientes errores durante la importaci&oacute;n:</b><br><br></caption>
		<tr>
			<th>No.<!-- {$filaDatos}{$sql} --></th>
			<th>LINEA</th>
			<th>CAMPO</th>
			<th>ERROR</th>
		</tr>
		{section loop=$arrErr name=x}
			<tr>
				<td align="center">{$smarty.section.x.iteration}</td>
				<td align="center">{$arrErr[x][0]}</td>
				<td>{$arrErr[x][1]}</td>
				<td>{$arrErr[x][2]}</td>
			</tr>
		{/section}
		<tr>
			<th colspan="4"><b>El archivo no se ha importado.</b></th>
		</tr>
	</table>
{/if}