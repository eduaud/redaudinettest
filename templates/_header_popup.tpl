<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    
    <title>M&aacute;ndarin Medical Spa :: Sistema v1.0</title>
    

<link rel="stylesheet" type="text/css" href="{$rooturl}css/pro_dropdown_3.css" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos.css"/>
{if ($make neq '' && $t eq 'bm92YWxhc2VyX2NsaWVudGVz') or $grid2 eq '1'}
	<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW_l.css"/>
{else}
	<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
{/if}    
    
<!-- Librerias para el menu pro_dropdown-->
<script src="{$rooturl}js/pro_dropdown_2/stuHover.js" type="text/javascript"></script>	

<!-- Librerias para el grid-->
<script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
<!--<script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/event.js"></script>
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/dom.js"></script>
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/fix.js"></script>
<script type="text/javascript" src="{$rooturl}js/calendar.js"></script>
<script type="text/javascript" src="{$rooturl}js/calendar-es.js"></script>
<script type="text/javascript" src="{$rooturl}js/calendar-setup.js"></script>
<script language="javascript" src="{$rooturl}js/funciones.js"></script>
<script language="javascript" src="{$rooturl}js/pro_dropdown_3/stuHover.js" type="text/javascript"></script>
<script language="javascript" src="{$rooturl}js/base64.js"></script>
<script language="javascript" src="{$rooturl}js/jquery.min.js"></script>
<script language="javascript" src="{$rooturl}js/jquery-ui.js"></script>-->

<link rel="stylesheet" href="{$rooturl}js/datepicker/jquery-ui-themes-1.10.2/themes/cupertino/jquery-ui.css" />
<!--  abc  ThickBox   -->
<!--   fin del thickbox   -->

</head>

{literal}

<script>	

	document.onkeydown = function(event)
	{	
		if(window.event)
		{		
			if(window.event.keyCode == 116)
				window.event.keyCode=505;								
			if(window.event && window.event.keyCode == 505)
				return false;	
		}
		else
		{
			if(event.which == 116)
				return false;		
		}	
			
	}
	
	function checkSesion()
	{
		{/literal}
		var aux=ajaxR("{$rooturl}/checkSesion.php");
		{literal}
		
		if(aux != "ACTIVA")
		{
			clearInterval(chksess);
			if(confirm("Su sesión ha expirado, ¿desea logearse nuevamente?"))
			{
				{/literal}
				window.open("{$rooturl}/iniciaSesion.php?sucursal={$SUCURSAL_USR}&username={$NAME_USR}","Inicio de Sesi&oacute;n", "width=300, height=150,top="+(screen.height/2-75)+",left="+(screen.width/2-150));
				{literal}	
				chksess=setInterval("checkSesion()", "30000");
			}	
			else
			{/literal}
				location.href="{$rooturl}/index.php";	
			{literal}	
		}
		
	}
	
	var chksess=setInterval("checkSesion()", "30000");
	
	/*$(document).ready(function() {		
		$(function() {
			$( ".datepicker" ).datepicker({
				changeMonth: true,
				changeYear: true
			});
		});
	});*/
	
	
	
</script>

{/literal}

<div class="contenido" id="contenido">

<div class="content-sistema" id="content-sistema">

<div style="position:fixed; width:950px" align="right">
<input type="button" value="Cerrar" class="cerrarPopUp" />
</div>