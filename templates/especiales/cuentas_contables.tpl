{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
<br />
<h1> Cuentas Contables </h1>
<p>
&nbsp;
<p />
<a id="frmAgregarCuentaContable" data-fancybox-type="iframe" href="agregarCuentaContable.php" onClick="agregarCuentaContable();">
	<input name="btnAgregarCuentaContable" type="button" class="boton" value="Nueva Cuenta Contable	&raquo;" />
</a>
<p>
&nbsp;
<p />
<h4>Lista de Cuentas Contables</h4>
<p>
&nbsp;
<p />

<div id="cuentasNivel1">
	<table border="0" align="center" class="listaCuentasNivel1">
		<tbody>
			
			{section name="ccn1" loop=$cuentasNivel1}
				<tr>
					<td align="left">
						<a href="#" id="linkNivel1{$cuentasNivel1[ccn1].3}" onClick="mostrarCuentasNivel2('{$cuentasNivel1[ccn1].3}');"><b>+ {$cuentasNivel1[ccn1].2}</b></a>
						<div id="cuentasNivel2{$cuentasNivel1[ccn1].0}" style="display:none">
						</div>
					</td>
				</tr>
				{if $smarty.section.ccn1.iteration != $smarty.section.ccn1.max}
				<tr>
					<td>&nbsp;</td>
				</tr>
				{/if}
			{/section}
		</tbody>
	</table>
</div>

<div id="frmVerCuentacontable">
</div>

{include file="_footer.tpl" aktUser=$username}