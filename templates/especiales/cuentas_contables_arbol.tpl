
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Audicel : : Sistema</title>
    
    <!--CSS GENERAL DE ..........-->
    <link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
	<!--CSS PLUGIN DE SELECT MULTIPLE ..........-->
	<link href="{$rooturl}include/multipleSelect/multiple-select.css" rel="stylesheet" type="text/css" />
	<!--------------------------->
    
    {if ($make neq '' && $t eq 'bm92YWxhc2VyX2NsaWVudGVz') or $grid2 eq '1'}
        <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW_l.css"/>
    {else}
        <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
    {/if}    
     
    <!-- Librerias para el grid-->
    <script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
   <script type="text/javascript" src="{$rooturl}js/calendar.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-es.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-setup.js"></script>
    <script language="javascript" src="{$rooturl}js/pro_dropdown_3/stuHover.js" type="text/javascript"></script>
     <script language="javascript" src="{$rooturl}js/base64.js"></script>
    
    <!--css date picker-->
    <script language="javascript" src="{$rooturl}js/datepicker/jquery-1.9.1.js"></script>
	<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>

    <link rel="stylesheet" href="{$rooturl}js/datepicker/jquery-ui-themes-1.10.2/themes/smoothness/jquery-ui.css" />
    
    
    <!--  abc  ThickBox   -->
    
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/thickbox.css" media="screen"/>
	<script language="javascript" src="{$rooturl}js/jquery-1.8.3.js"></script>
    <script language="javascript" src="{$rooturl}js/thickbox.js"></script>
    <script language="javascript" src="{$rooturl}js/funcionesGenerales.js"></script>
    <script language="javascript" src="{$rooturl}/js/funcionesCliente.js"></script>
	<script language="javascript" src="{$rooturl}/js/functionsClient.js"></script>	<!-- JA 20/10/2015 !>
    <script language="javascript" src="{$rooturl}js/funcionesProyecto.js"></script>
    <!--<script language="javascript" src="{$rooturl}/js/detalle_articulos.js"></script>-->
	<script language="javascript" src="{$rooturl}/js/buscador_Direcciones.js"></script>
	<script language="javascript" src="{$rooturl}/js/verAticulosSustitutos.js"></script>
	<script language="javascript" src="{$rooturl}/js/agregarDirecciones.js"></script>
	<script language="javascript" src="{$rooturl}/js/existencias.js"></script>
	<script language="javascript" src="{$rooturl}/js/direccionExistente.js"></script>
	<script language="javascript" src="{$rooturl}/js/utf8Toolkit.js"></script>
	<script language="javascript" src="{$rooturl}/js/pedidos.js"></script>
	<script language="javascript" src="{$rooturl}/js/clientes.js"></script>
	<script language="javascript" src="{$rooturl}/js/facturas.js"></script>
	<script language="javascript" src="{$rooturl}/js/caja_chica.js"></script>
    <script language="javascript" src="{$rooturl}js/datepicker/jquery-ui.js"></script>
    
	<!--  @Saulo Cristales: Las siguientes 3 lineas, son para activar el fancybox  -->
	<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
 
	 <!-- termina gc -->
	 
	 <!--  Plugin de select multiple  -->
	 <script language="javascript" src="{$rooturl}include/multipleSelect/jquery.multiple.select.js"></script>
	 <!--------------------------------->
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
<br />
<h1> Cuentas Contables </h1>

<div id="cuentas">
	<table border="0" align="center" class="listaCuentasNivel1">
		<tbody>
			{section name="ccn1" loop=$cuentasNivel1}
				<tr>
					<td align="left">
						{section name="mostrarCheck" loop=$agregarCheck}
							{if $agregarCheck[mostrarCheck].0 == $cuentasNivel1[ccn1].0 AND $agregarCheck[mostrarCheck].1 == "SI"}
							<input type="checkbox" onclick="guardaCuentaContable(this.value,'{$id}','{$cuenta}')" value="{$cuentasNivel1[ccn1].0}"/>
						<a href="#" id="linkNivel1{$cuentasNivel1[ccn1].0}" onClick="mostrarCuentasNivel2('{$cuentasNivel1[ccn1].0}','{$id}','{$cuenta}');"><b>+ {$cuentasNivel1[ccn1].2}</b></a>
						{/if}
						{if $agregarCheck[mostrarCheck].0 == $cuentasNivel1[ccn1].0 AND $agregarCheck[mostrarCheck].1 == "NO"}
						<a href="#" id="linkNivel1{$cuentasNivel1[ccn1].0}" onClick="mostrarCuentasNivel2('{$cuentasNivel1[ccn1].0}','{$id}','{$cuenta}');"><b>+ {$cuentasNivel1[ccn1].2}</b></a>
						{/if}
						{/section}
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
<div>
	<input type="hidden" id="IDcuentaContable"/>
</div>
<!--<div id="frmVerCuentacontable">
</div>
->