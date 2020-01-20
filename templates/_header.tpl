<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
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
	
</head>

{literal}
<!-- Estilo para el autocompletado  -->
<style>.ui-autocomplete {font:70% "Verdana", Geneva, sans-serif; position: absolute; cursor: default; width: 200px;}</style>
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
	
	function valida_nombre_archivo_cer(archivo){
		var nombre_archivo = archivo.value;
		var pos = nombre_archivo.lastIndexOf('.');
		var nombre_sin_extencion = nombre_archivo.substring(0, pos);
		
		if(isNaN(nombre_sin_extencion)){
			alert("El nombre del archivo debe contener solo numeros.");
		}
	}
	
	function soloNumeros(e){ 
		var key = window.Event ? e.which : e.keyCode
		return ((key >= 48 && key <= 57) || (key==8))
	}
	
	mostroMensaje = 0;
	function checkSesion()
	{
		{/literal}
		var aux = ajaxR("{$rooturl}/checkSesion.php");
		{literal}
		
		auxSplit = aux.split("|");
		
		if(auxSplit[0] != "ACTIVA")
		{
			alert("Por inactividad tu sesi\u00f3n ha expirado.");
			{/literal}
				location.href="{$rooturl}/index.php";	
			{literal}
		}
		else if(auxSplit[1] <= ((5*60)+5) && mostroMensaje == 0)
		{
			alert("Le quedan menos de 5 minutos antes de que se cierre el sistema por inactividad");
			mostroMensaje = 1;
		}
		
	}
	var chksess=setInterval("checkSesion()", "30000");		
	
	
	function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
	
</script>
{/literal}





<body>
<div class="wrapper">
	<div class="header_bg" id="header_bg">
  		<div class="header" id="header">
		
			<ul id="nav">               
                {section loop=$registrosmenu name=q}
					{*si es igual a cero solo creamos la tabla de cierre *}
					{if $registrosmenu[q][0] eq '0'}
						{if $registrosmenu[q][17] eq '17'}
							</ul>
							</li>
						{else}
							{*significa que abrira un ul *}
						 	<ul class="{$registrosmenu[q][16]}">
						{/if}	
					{else}
						{*creara un li normal*}
						{if ($make eq 'insertar' or ($make eq 'actualizar' and $v eq 0)) and $mensaje_salida eq '1'}
							{if $registrosmenu[q][3] eq '.'}
								<li class="{$registrosmenu[q][9]}"><a direccion="{$rooturl}{$registrosmenu[q][10]}{$registrosmenu[q][0]}" id="{$registrosmenu[q][12]}" class="{$registrosmenu[q][11]}" title="Pulse para Abrir Listado de Submenus." onClick="Redirecciona(this)"><span class="{$registrosmenu[q][13]}"> <!--<img src="{$rooturl}imagenes/general/{$registrosmenu[q][18]}18.png" width="18" height="24" border="0">--> {$registrosmenu[q][1]}</span></a>
							{else}
							    <li class="{$registrosmenu[q][9]}" onMouseOver="this.style.cursor='hand';this.style.cursor='pointer';"><a direccion="{$rooturl}{$registrosmenu[q][10]}{$registrosmenu[q][3]}" id="{$registrosmenu[q][12]}" class="{$registrosmenu[q][11]}" onClick="Redirecciona(this)"><span class="{$registrosmenu[q][13]}">{$registrosmenu[q][1]}</span></a>
							{/if}
						{elseif $opcionMenu eq 'proceso'}
							{if $registrosmenu[q][3] eq '.'}
								<li class="{$registrosmenu[q][9]}"><a direccion="{$rooturl}{$registrosmenu[q][10]}{$registrosmenu[q][0]}" id="{$registrosmenu[q][12]}" class="{$registrosmenu[q][11]}" title="Pulse para Abrir Listado de Submenus." onClick="SalirProceso(this)"><span class="{$registrosmenu[q][13]}"> <!--<img src="{$rooturl}imagenes/general/{$registrosmenu[q][18]}18.png" width="18" height="24" border="0">--> {$registrosmenu[q][1]}</span></a>
							{else}
							    {if $registrosmenu[q][10] eq 'especial'}
	                            	<li class="{$registrosmenu[q][9]}">	                                
	                                <span onClick="Abrir_Ventana('{$rooturl}/code/cuentascontables/catalogoCuentasContables.php|800|380');" >
                                    <a href="#">{$registrosmenu[q][1]}</a></span>
	                            {else}
		                            <li class="{$registrosmenu[q][9]}" onMouseOver="this.style.cursor='hand';this.style.cursor='pointer';">
                                    <a direccion="{$rooturl}{$registrosmenu[q][10]}{$registrosmenu[q][3]}" id="{$registrosmenu[q][12]}" class="{$registrosmenu[q][11]}" onClick="SalirProceso(this)"><span class="{$registrosmenu[q][13]}">{$registrosmenu[q][1]}</span></a>
								{/if}
							{/if}
						{else}
							{if $registrosmenu[q][3] eq '.'}
								<li class="{$registrosmenu[q][9]}"><a href="{$rooturl}{$registrosmenu[q][10]}{$registrosmenu[q][0]}" id="{$registrosmenu[q][12]}" class="{$registrosmenu[q][11]}" title="Pulse para Abrir Listado de Submenus."><span class="{$registrosmenu[q][13]}">{$registrosmenu[q][1]}</span></a>
							{else}								
	                            {if $registrosmenu[q][10] eq 'especial'}
	                            	<li class="{$registrosmenu[q][9]}">
	                                <span onClick="Abrir_Ventana('{$rooturl}/code/cuentascontables/catalogoCuentasContables.php|800|380');" ><a href="#">{$registrosmenu[q][1]}</a></span>
	                            {else}
	                            	<li class="{$registrosmenu[q][9]}"><a href="{$rooturl}{$registrosmenu[q][10]}{$registrosmenu[q][3]}" id="{$registrosmenu[q][12]}" class="{$registrosmenu[q][11]}"><span class="{$registrosmenu[q][13]}">{$registrosmenu[q][1]}</span></a>
								{/if}
							{/if}	
						{/if}
						
					
						{if $registrosmenu[q][14] eq 'f_li'}
							</li>
						{/if}	
					{/if}
				{/section}
			</ul>
		
		<div class="logo_admin" id="logo_admin"><a href="{$rooturl}code/indices/menu.php"><img src="{$rooturl}imagenes/header_logo.png" alt="Audicel" width="147" height="71" border="0" /></a></div>
    	<div class="systemname_admin" id="systemname_admin">Sistema para Audicel <strong>v1.0</strong></div>
       
    	<div class="logout" id="logout">
            <form action="{$rooturl}index.php" method="get">
				<input type="hidden" name="view" value="logout">
				<input name="button3" type="submit" class="button_salir" id="button3" value="Salir" />
			</form>
            </a></p><!-- InstanceBeginEditable name="headcol3" -->    Bienvenido(a): <span class="header_name">{$userfullname}</span>   <!-- InstanceEndEditable --></div>
        </div>
	</div>

    
    <!-- ***  DIV para bloquear la pantalla con una imagen de cargando  *** -->
    <div style="z-index:5000; display: none; position:fixed; left:0px; top: 0px; bottom: 0px; width:100%; height:100%; background-image: url('{$rooturl}/imagenes/general/back_wait.gif');" id="waitingplease">
		<img border="0" src="{$rooturl}/imagenes/general/wait.gif" style="z-index:2000; position:absolute; padding: 15% 0 0 45%;" id="imgW1">
	</div>
    
    
    
    <div class="content_shadow" id="content_shadow">
    	<div class="content" id="content">
        	<div class="content-sistema" id="content-sistema">
