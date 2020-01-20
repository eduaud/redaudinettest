<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
	<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="{$rooturl}js/jquery-1.8.3.js"></script>
	<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
	<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	
	<title>Listado de Cuentas Contables Para Seleccionar</title>
</head>

<body>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
<br />
<h1> Selección de Cuentas Contables </h1>
<p>
&nbsp;
<p />
<h4>Lista de Cuentas Contables</h4>

<div id="cuentasNivel1">
	<table border="0" class="listaCuentasNivel1">
		<tbody>
			{section name="ccn1" loop=$cuentasNivel1}
				<tr height="10px">
					<td align="left">
						{section name="mostrarRadio" loop=$agregarRadioButton}
							{if $agregarRadioButton[mostrarRadio].0 == $cuentasNivel1[ccn1].0 AND $agregarRadioButton[mostrarRadio].1 == "SI"}
								<input type="radio" name="id_cuenta_contable_radio" id="id_cuenta_contable_radio" value="{$cuentasNivel1[ccn1].0}" onClick="mostrarCuentasNivel2Seleccionar('{$cuentasNivel1[ccn1].3}','{$cuentasNivel1[ccn1].0}','s');" />
								<a href="#" id="linkNivel1{$cuentasNivel1[ccn1].3}" onClick="mostrarCuentasNivel2Seleccionar('{$cuentasNivel1[ccn1].3}','{$cuentasNivel1[ccn1].0}','s');">
							{/if}
							{if $agregarRadioButton[mostrarRadio].0 == $cuentasNivel1[ccn1].0 AND $agregarRadioButton[mostrarRadio].1 == "NO"}
								+ <a href="#" id="linkNivel1{$cuentasNivel1[ccn1].3}" onClick="mostrarCuentasNivel2Seleccionar('{$cuentasNivel1[ccn1].3}','{$cuentasNivel1[ccn1].0}','n');">
							{/if}
						{/section}
							<b>{$cuentasNivel1[ccn1].2}</b>
						</a>
						<div id="cuentasNivel2{$cuentasNivel1[ccn1].0}" style="display:none"></div>
					</td>
				</tr>
				{if $smarty.section.ccn1.iteration != $smarty.section.ccn1.max}
				<tr height="10px">
					<td></td>
				</tr>
				{/if}
			{/section}
		</tbody>
	</table>
</div>
<input type="hidden" name="numeroFilaGrid" id="numeroFilaGrid" value="{$numeroFilaGrid}" />
<input type="hidden" name="nombreGridDetalle" id="nombreGridDetalle" value="{$nombreGridDetalle}" />
<div id="frmVerCuentaContableSeleccion">
</div>

</body>
</html>