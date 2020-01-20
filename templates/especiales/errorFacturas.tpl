{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/envioPedido.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

<div id="titulo-icono" class="titulo-icono">
	<div id="titulo" class="titulo">Error al facturar</div>
</div>

<table width="950">
<br>
<br>
<br>
<br>

	{$respuesta}
	
	
	
	<br><br><br><br><br><br><br>
	
</table>


{include file="_footer.tpl" aktUser=$username}
