{if $tipoCarga eq "1"}
	{include file="_header.tpl" pagetitle="$contentheader"}
	<link href="{$rooturl}/css/pop_up_form.css" rel="stylesheet" type="text/css" />
{/if}    

<input type="hidden" id="colsel" value={$col}>

{if $accion eq "localizacion" }
	<script language="javascript" src="{$rooturl}/js/jquery-1.8.3.js"></script>
	<link rel="stylesheet" type="text/css" href="{$rooturl}/css/thickbox.css" media="screen"/>
	<script language="javascript" src="{$rooturl}/js/detalle_articulos.js"></script>

	<div align="center" style="width:100%" >
  
	<br />
	
	<input type="hidden" value="{$id_articulo}" id="id_articulo_original">
	<input type="hidden" value="{$articulo}" id="articulo_original">
	
	<h1>ARTICULO:{$articulo}   CANTIDAD  EN EXISTENCIA:{$cantidad}</h1>
	<br /><br />
	
	<div align="left"><strong>ESTATUS: RESERVADOS EN PEDIDOS DE RENTA NO CONFIRMADOS EN COTIZACION</strong></div>
	{$articulosReservados} 
	<br />
	
	<div align="left"><strong>ESTATUS:RESERVADOS EN PEDIDO DE PRUEBA DE MONTAJE NO CONFIRMADOS EN COTIZACIÓN</strong></div>
	{$articulosReservadosMontaje}
	<div align="right" style="width:100%;">
	<br />
	</div>
	
	<br /><br /><br /><br />
	<div align="left">
		<strong>ESTATUS:TRANSFORMABLES EN SU SUBCATEGORIA</strong>
		<input type="button" class="button12"  value="Agregar lineas seleccionadas" onclick="guardarTransformables();"/>
	</div>
	<br>
	{$transformables}
	
		
	
   	</div>	
{elseif $accion eq "sustitutos"}
	
	<h1>Sustitutos para el articulo: {$nombre}</h1>
	
	{literal}
		
		<link rel="stylesheet" type="text/css" href="{/literal}{$rooturl}{literal}/js/slide/slideshow.css" media="screen" />
		<style type="text/css">
		a { color: #404040; }
		a:hover { text-decoration: none; }
		code { color: #404040; font: normal 10px Monaco, monospace; }
		em { color: #808080; font-style: normal; }
		
		/*h1:before { content: '.'; }*/
		p { color: #404040; font: normal 12px/16px Arial, sans-serif; padding: 0 20px 16px; }
		
		/* Overriding the default Slideshow thumbnails for the vertical presentation */

		.slideshow-thumbnails {
			height: 300px;
			left: auto;
			right: -80px;
			top: 0;
			width: 70px;
		}
		.slideshow-thumbnails ul {
			height: 500px;
			width: 70px;
		}    
		</style>
		<script type="text/javascript" src="{/literal}{$rooturl}{literal}/js/slide/mootools.js"></script>
		<script type="text/javascript" src="{/literal}{$rooturl}{literal}/js/slide/slideshow.js"></script>
		<script type="text/javascript">		
		//<![CDATA[
			
		  window.addEvent('domready', function(){
			var data = {
			{/literal}{$cadenaImagenPrincipal}{literal}
			};
			
			var myShow = new Slideshow('show', data, { captions: true,  controller: true, height: 300, hu: '', thumbnails: false, width: 400 });
			});
		//]]>
		</script>
	{/literal}

		<div id="show" class="slideshow">
		</div>
	
	

	
{/if}