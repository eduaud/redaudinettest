{if $tipoCarga eq "1"}
{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}/css/pop_up_form.css" rel="stylesheet" type="text/css" />

{/if}    
<!--<script src='{$rooturl}/js/fullcalendar-1.6.1/jquery/jquery-1.9.1.min.js'></script>
<script src='{$rooturl}/js/fullcalendar-1.6.1/jquery/jquery-ui-1.10.2.custom.min.js'></script>-->
<script language="javascript" src="{$rooturl}/js/jquery-1.8.3.js"></script>

<link rel="stylesheet" type="text/css" href="{$rooturl}/css/thickbox.css" media="screen"/>

 <link href="{$rooturl}css/style-t.css" rel="stylesheet" type="text/css" />

   


    <br />
	<br />
	



















    
      <h1>Dirección:   </h1>
	 <div  class="tablaContenedor"  >  
	  	{$tabla}
     </div>
	 <input type="button" value="Guardar" onclick="guardarDireccion();" class="botonSecundario"/>