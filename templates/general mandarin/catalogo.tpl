{if $hf neq 10}
	{include file="_header.tpl" pagetitle="$contentheader"}
{else}    
	<html >
<title>:</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/pro_dropdown_2.css" />
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos.css"/>
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
    
    <div class="tabla" id="tabla">
    <br />
    <br />    
	
    <!-- Librerias para el menu pro_dropdown-->
    <script src="{$rooturl}js/pro_dropdown_2/stuHover.js" type="text/javascript"></script>	
    
    <!-- Librerias para el grid-->
    <script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/yahoo.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/event.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/dom.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/fix.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-es.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-setup.js"></script>
    <script language="javascript" src="{$rooturl}js/funciones.js"></script>
    
    {literal}
    <style>
		body{background-color:#FFF; background-image:none}
	</style>
    {/literal}
{/if}
{include file="general/funciones_catalogos_cabecera.tpl"}

{if $idRecibo neq ""}
<script>
	window.open('../contratos/imprimibles/recibo.php?tipo={$tipoRecibo}&id_nota={$idRecibo}', "Doc", "width=800, height=600");
</script>
{/if}

{literal}
<script>

/*$(document).ready(function() {
	$("#accordion").accordion();	
});
*/
document.onkeypress = keyhandler;

function keyhandler(e) {
   if(window.event)
	  keyCode=window.event.keyCode; //IE	
   else
      if(e)
	     keyCode=e.which;
	      
      if(keyCode == 39){
	     return false;
	  }
}

function validaultimafila(grid)
{
	var nfil=NumFilas(grid);
	if(nfil<1)
		return true;	
	var objt=document.getElementById("Body_"+grid);
	if(objt)
	{
		for(var i=0;i<objt.rows.length;i++)
		{
			var fila=objt.rows[i];
			var valvac=0;
			for(var j=0;j<fila.cells.length;j++)
			{
				var celda=fila.cells[j];
				if(j>0)
				{
					var head=document.getElementById("H_"+grid+(j-1));
					if(head)
					{ 
						var mod=head.getAttribute("modificable").toUpperCase();
						var tipo=(head.getAttribute("tipo"))?(head.getAttribute("tipo").toUpperCase()):"";
						if(mod=="S"&&tipo!="ELIMINADOR")
						{
							if(tipo!="TEXTO")
								var valor=celda.getAttribute("valor");
							else
								var valor=celda.innerHTML;							
							if(valor==null||valor==""||valor=="&nbsp;")
							{
								alert("En la ultima fila aun existen valores requeridos vacios.");
								return false;
							}
						}
					}					
				}
			}		
		}
	}
	return true;;
}

function redondear(num, long)
{
	 var nuevo = Math.round(num*Math.pow(10,long))/Math.pow(10,long);
	 return nuevo;
}
{/literal}
{* crea la validacion del grid*}
{$funcNew}
{$funcFin}

</script>

<div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">{$nombre_menu}</div>
</div>
<p class='titulo_accion'>
{if $t eq 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA=='}
	{if $atributos[10][15]  eq '2'}
    	El regitro ya ha sido validado
	{elseif $atributos[10][15]  eq '3'}        
    	El regitro ya ha sido rechazado
    {else}    
    	Registro en espera de validaci&oacute;n
    {/if}	
{else}
	{$mensaje_accion}
{/if}    
</p>

<!--div de espera -->
<!--
<div style="z-index:5000; display:none; position:absolute; left:0; top:0;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
 -->
<div class="tabla" id="tabla">
<form action="encabezados.php" name="forma_datos" id="forma_datos" method="post" enctype="multipart/form-data">
{* make indicara la accion que realizaremos dar click al boton de guardar *}

{if $popup eq 'SI'}
	<input type="hidden" name="popup" value="{$popup}" />
    <input type="hidden" name="funcpop" value="{$funcpop}" />
    <input type="hidden" name="valpopup" value="{$valpopup}" />
    <input type="hidden" name="popup2" value="SI" />
    <input type="hidden" name="closewindow" value="10" />
{/if}
<input type="hidden"  name="porcentaje_iva_general" id="porcentaje_iva_general"  value="{$porcentaje_iva}"  />
<input type="hidden"  name="make" id="make"  value="{$make}"  />
<input type="hidden"  name="t"  value="{$t}"  />
<input type="hidden"  name="v"  value="{$v}"  />
<input type="hidden"  name="op"  value="{$op}"  />
<input type="hidden" name="closewindow" value="{$hf}" />
<input type="hidden" name="nreg" value="{$nreg}" />
<input name="strSelected" type="hidden" value="" />
<input name="tipo_mensaje" type="hidden" value="{$tipo_mensaje}" />
<input name="keycodif" id="keyCodif" type="hidden" value="{$keyCodif}" />

<input name="generaSubmit" type="hidden" value="0" />
<input name="iva_100" type="hidden"  id="iva_100" value="{$iva_100}" />

{if $t eq 'YW5kZXJwX3Byb2R1Y3Rvcw=='}
<input type="hidden" id="tiposPresentacionGrid" name="tiposPresentacionGrid" value="" />
<input type="hidden" id="prodGrid" name="prodGrid" value="" />
<input type="hidden" id="accionElim" name="accionElim" value="" />
<input type="hidden" id="accion_pant" name="accion_pant" value="" />

{/if}
{if $t eq 'YW5kZXJwX25vdGFzX3ZlbnRh'}
<input type="hidden" id="v_cli" name="v_cli" value="" />
<input type="hidden" id="v_dircli" name="v_cli" value="" />
<input type="hidden" id="v_suc" name="v_cli" value="" />
{/if}

{if $t eq 'YW5kZXJwX2N1ZW50YXNfcG9yX2NvYnJhcg=='}
<input type="hidden" id="CuantaSaldar" name="CuantaSaldar" value="0" />

{/if}

<!-- Campo para cambiar a inactivos a registros del grid cuentas por pagar regActivoCxC -->
{if $t eq 'YW5kZXJwX2N1ZW50YXNfcG9yX2NvYnJhcg=='}
<input type="hidden" id="regActivoCxC" name="regActivoCxC" value="" />
{/if}

<!--------------------------- SECCION AQUI DEBEN IR LO BOTONES ---------------------------->
<br />
<table>
	<tr>
        {if $t eq 'ZmVsZWNfZmFjdHVyYXM=' and ($make eq 'actualizar') && $atributos[5][15] eq ''}				
			<td>            	
            	<input type="button" name="sellatimbra"  id = "sellatimbra" value=" Sellar y Timbrar Factura " onclick="SellaYTimbra(this, this.form)" class="boton"/>
            </td>
		{/if}
        
        {if $t eq 'ZmVsZWNfbm90YXNfY3JlZGl0bw==' and ($make eq 'actualizar') && $atributos[5][15] eq ''}				
			<td>            	
            	<input type="button" name="sellatimbranc"  id = "sellatimbranc" value=" Sellar y Timbrar NC " onclick="SellaYTimbraNC(this, this.form)" class="boton"/>
            </td>
		{/if}
		
		{if $v neq '0'   and $v neq '3' and $v neq ''  and $npe neq 0 and $t neq 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA==' and $t neq 'YW5kZXJwX2ZhY3R1cmFz' and $t neq 'YW5kZXJwX2N1ZW50YXNfcG9yX2NvYnJhcg=='}
			<td >
				<input type="button" name="nuevo" value=" Nuevo »" onclick="irNuevo('{$rooturl}','{$t}','{$tcr}')" class="botonSecundario"/>&nbsp;&nbsp;&nbsp;
			</td>
		{/if}		
		
				
		
        
        {if $v eq '3'  and $epe neq 0 and $t neq 'YW5kZXJwX2ZhY3R1cmFz'}
			<td align="right" width="750">
				<input type="button" name="nuevo" value=" Eliminar »" onclick="irEliminarRegistro(this.form,'{$rooturl}','{$t}','campo_0')" class="boton"/> &nbsp;&nbsp;&nbsp;
			</td>
        {else}
	        {if $v eq '3' and $epe neq 0 and $t eq 'YW5kZXJwX2ZhY3R1cmFz'}
	        	<td align="right" width="750">
	        		<input type="button" name="cancelar" value=" Cancelar " onclick="CancelarFactura(this.form,'{$rooturl}','{$t}','campo_0')" class="boton"/>&nbsp;&nbsp;&nbsp;
	    		</td>
	        {/if}	
		{/if}
        
        {if $v neq '0' and $v neq '3' and  $v neq ''  and $mpe neq 0 and $t neq 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA==' and $t neq 'YW5kZXJwX25vdGFzX3ZlbnRh'  and $t neq 'YW5kZXJwX2ZhY3R1cmFz'}
  	    	{if $modifica_doc neq '0'}
  	    		<td >
  	    			<input type="button" name="mod" value=" Modificar »" onclick="irModificarRegistro(this.form,'{$rooturl}','{$t}','campo_0','{$tcr}')" class="boton"/> &nbsp;&nbsp;&nbsp;
    			</td>
  	    	{/if}  	    
		{/if}
        
        {if $v neq '1'  and $v neq '3' and $t neq 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA=='}			
			<td >
				<input type="button" name="modificar"  id = "guardarb" value=" Guardar »" onclick="valida(this.form,'actualizar')" class="boton"/> &nbsp;&nbsp;&nbsp;
			</td>
		{/if}
        
        {if $hf neq 10 and $t neq 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA=='}
			{if ($make eq 'insertar' or ($make eq 'actualizar' and $v eq 0)) and $mensaje_salida eq '1'}
            	<td >
            		<input type="button" name="listado" value=" Listado »" direccion="{$rooturl}code/indices/listados.php?t={$t}" onclick="Redirecciona(this)" class="botonSecundario"/>&nbsp;&nbsp;&nbsp;
        		</td>                			
			{else}            	
				<td >
					<input type="button" name="listado" value=" Listado »" onclick="irListado('{$rooturl}','{$t}')" class="botonSecundario"/>
				</td>                
			{/if}
        {/if}  
        
        	<!--TABLA: {$t} -->         
		{if $t eq '0' and $ipe neq 0}
			<td>
				<input type="button" value="Imprimir »" class="boton" onClick="imprime('{$t}');">&nbsp;&nbsp;&nbsp;
			</td>
		{/if}        
		<td></td>
	</tr>
</table>

{if $mensaje_err neq ''}
	<table>
    	<tr>
        	<td>
            	<br />
            	<font face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000" size="2">
                	<b>{$mensaje_err}</b>
                </font>
            </td>
        </tr>
    </table>
{/if}




{* TABLA DE CAMPOS *}  

{assign var="contColumnas" value=0}	
{assign var="divAnterior" value=0}	
{assign var="divActual" value = 0}
{assign var="generaDiv" value = 0}
{assign var="contadorCampos" value = 0}
{assign var="contadorCiclos" value = 0}

{section loop=$atributos name=indice start=0}
	{if $atributos[indice][6]}
		{assign var="contadorCampos" value=$contadorCampos+1}
	{/if}
{/section}
<table id="tabla_campos">
	<tr>	
    	<td>
			{section loop=$atributos name=indice start=0}		            	
        		{if $atributos[indice][3] eq "LEYENDA" }
          			<tr class="subtitulocenter" >
                		<td colspan="4" >
	       					<br>
	       					$atributos[indice][2]}       
	       				</td>
	   				</tr>
				{else} 
					{assign var="divActual" value = $atributos[indice][24]}
		
                    {if $divAnterior neq $divActual }		
                        </table>
                        </div>		
                        {assign var="divAnterior" value = $atributos[indice][24]}
                        {assign var="generaDiv" value = 0}		
                    {/if}
                
                    {if $divAnterior eq $divActual }
                        {if $generaDiv eq 0}
                            {if $contadorCiclos < $contadorCampos}	
                            		
                                {if $atributos[indice][25] neq '-1'}
                                    <br />	
                                    <div style="width:920px; background-color:#FDEBDF; color:#F47B28; font-size:11pt;  font-weight: normal; margin-bottom: 14px " id="fila_catalogo_{$smarty.section.indice.index}">
                                        <h3>{$atributos[indice][25]}</h3>
                                    </div>
                                    <br />
                                {/if}                               
                                
                                <div>
                                <table style="width:920px;" id="tabla_fila_catalogo_{$smarty.section.indice.index}">
                                {assign var="generaDiv" value = 1}
                                {if $atributos[indice][25] eq 'Características Particulares'}
                                	<tr>
                                    	<td colspan="6" style="font-size:14px; color:#06C; height:70px;">
                                        	Designa con el número 1 a la característica que creas que mejor te describe, 
                                        	con el número 2 a la siguiente, y así sucesivamente hasta asignar el número 11 a la
                                            característica que creas que menos te define:<br />
                                    	</td>
                                 	</tr>
                                {/if}
                            {/if}
                        {/if}
                    {/if}
                    
                    {if $atributos[indice][40] eq "1"}
                    	</tr>
                        <tr><td class="{$atributos[indice][18]}" style="text-align:left" colspan="4">{$atributos[indice][2]}</td></tr>
                        <tr id="fila_catalogo_{$smarty.section.indice.index}"> 
					{/if}
                    
					{if $atributos[indice][38] eq "1"}
						<tr id="fila_catalogo_{$smarty.section.indice.index}"> 
					{/if}
					             
                                                
					<!-------------- SE PONE EL NOMBRE DE LA ETIQUETA PARA EL CAMPO -------------------->
                    {* 1/ Para los campos visibles (visible->6)*}
         			                   	
                    {if $atributos[indice][6] eq "1"}
                        {if $atributos[indice][3] neq 'ARCHIVO'}
                        	{if $atributos[indice][40] eq "1"}
                                <td class="{$atributos[indice][18]}" colspan="1" style="width:110"></td>
                            {else if $atributos[indice][3] eq 'ARCHIVO'}
                            	<td class="{$atributos[indice][18]}" colspan="1" style="width:110">{$atributos[indice][2]}</td>
                         	{/if}
                    {else if $atributos[indice][3] eq 'ARCHIVO'}
                        {if $v neq 1 or $op eq 3}
                            <td class="{$atributos[indice][18]}" colspan="1" style="width:110">{$atributos[indice][2]}</td>
                        {/if}
                    {/if}                  	
                  	              

                	<!----------------------------------------------------------------------------------->
                    
                    <input type="hidden"  name="propiedades_[{$smarty.section.indice.iteration-1}]"  
                    value="{$atributos[indice][2]}|{$atributos[indice][3]}|{$atributos[indice][5]}"  />	
                    				
                    <td colspan="{$atributos[indice][39]}">
                    
                    {* 1.1 el control que Utilizaremos*}
                    {* 1.1.1 para char y enteros*}
                
                    {if $atributos[indice][3] eq "CHAR" or $atributos[indice][3] eq "MAIL" }
                        {*****{if $atributos[indice][4] > 100 }	***********}
                            <!--<textarea name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" cols="20" rows="3" 
                            class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
                            onkeypress="{$atributos[indice][21]}" {if $atributos[indice][7]  eq '0'}readonly{/if}>{$atributos[indice][15]}
                            </textarea>-->
                        {*****{else}***********}
                            {*para llaves del tipo cadena*}
                            {if $atributos[indice][7]  eq '0'}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" readonly onblur="{$atributos[indice][28]}" 
                                title="Tipo texto, {$atributos[indice][4]} caracteres">
                            {else}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
                                onkeypress="{$atributos[indice][21]}" title="Tipo texto, {$atributos[indice][4]} caracteres">
                            {/if}
                        
                        {*****{/if}***********}		
                    {elseif $atributos[indice][3] eq "TEXTAREA"}	
						<textarea name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        	cols="{$atributos[indice][37]}" rows="4" class="{$atributos[indice][19]}" {$readonly} 
                            onblur="{$atributos[indice][28]}" 
                            onkeypress="{$atributos[indice][21]}" {if $atributos[indice][7]  eq '0'}readonly{/if}>{$atributos[indice][15]}</textarea>        
                  	{elseif $atributos[indice][3] eq "LABEL"}	
						<label name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}">{$atributos[indice][15]}
                       	</label>                
                    {elseif $atributos[indice][3] eq "INT"}	                    				
                        {if $atributos[indice][11]  eq '1'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" readonly 
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                        {else}
                            {if $atributos[indice][7]  eq '0'}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" 
                                onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}" readonly 
                                title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                            {else}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" {$readonly} 
                                onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}"
                                title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres" >
                            {/if}
                        
                        {/if}
                        
                        {if $make == 'insertar' AND $atributos[indice][0] eq 'monto_inicial' && $t eq 'bm92YWxhc2VyX2FwZXJ0dXJhX2RpYQ=='}
                            <font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000">
                                <i>Monto del cierre anterior:</i>
                            </font>
                            <font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF0000">
                                <b>{$monto_ultimo_cierre}</b>
                            </font>
                        {/if}
                    
                     {elseif $atributos[indice][3] eq "DECIMAL"}	                    				
                     
                        {if $atributos[indice][7]  eq '0'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" 
                            onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}" readonly 
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                        {else}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" {$readonly} 
                            onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}"
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres" >
                        {/if}
                        
                    
                    {elseif $atributos[indice][3] eq 'PASS'}
                        <input type="password" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}" 
                        class="{$atributos[indice][19]}" {$readonly} title="Tipo contrase&ntilde;a, {$atribs[indice][2]} caracteres">				
                    {elseif $atributos[indice][3] eq 'TYNYINT'}					
                        {if $atributos[indice][7] eq '0'}					
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                    
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true" >					
                        {else}                    	
                            <input type="checkbox" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="1"  {if $atributos[indice][15] eq 'checked' or $atributos[indice][15] eq '1'} 
                            checked="checked"{/if} onclick="{$atributos[indice][30]}">						 						 
                        {/if} 					
                    {elseif $atributos[indice][3] eq 'DATE'}        			
                        {if $atributos[indice][7] eq '0'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="14"
                             size="18" class="{$atributos[indice][19]}" onfocus="{$atributos[indice][22]}" onblur="{$atributos[indice][28]}"
                             value="{$atributos[indice][15]}" onchange="{$atributos[indice][29]}" readonly title="Tipo fecha (aaaa-mm-dd)"/>
                        {else}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="14"
                             size="18" class="{$atributos[indice][19]}" onfocus="{$atributos[indice][22]}" onblur="{$atributos[indice][28]}"
                             value="{$atributos[indice][15]}" onchange="{$atributos[indice][29]}" {$readonly} 
                             title="Tipo fecha (aaaa-mm-dd)"/>
                        {/if}
                        <span class="tiposDatos">&nbsp;&nbsp;Formato dd/mm/aaaa</span>	
                    {elseif $atributos[indice][3] eq 'TIME'}
                        {if $atributos[indice][7] eq '0'}		
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="10"
                            size="12" class="{$atributos[indice][19]}"    value="{$atributos[indice][15]}" {$readonly} 
                            title="Tipo hora (hh:mm:ss)" readonly="readonly"/>
                        {else}    
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="10"
                            size="12" class="{$atributos[indice][19]}"    value="{$atributos[indice][15]}" {$readonly} 
                            title="Tipo hora (hh:mm:ss)"/>
                        {/if}            
                        <span class="tiposDatos">&nbsp;&nbsp;Formato HH:mm:ss</span>            
                    {elseif $atributos[indice][3] eq 'COMBO'}					
                        {* 1/ Para los campos no modificables (modificable->7)*}	
                        {if $atributos[indice][7] eq '0' }
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                            
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true"  >				        
                        {else}  
                            <!-- Excepcion para Pantalla Series y Vendedores con combo de sucursal-->
                            {if $t eq 'YW5kZXJwX3Nlcmllcw==' or $t eq 'YW5kZXJwX3ZlbmRlZG9yZXM='}
                                {if $atributos[indice][0] eq 'id_sucursal'}	
                                    <select name="campo_{$smarty.section.indice.iteration-1}_combo" class="{$atributos[indice][19]}" 
                                    onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}_combo" disabled="disabled">
                                        {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15] }
                                    </select>    
                                    <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                    value="{$atributos[indice][15]}" />
                                {else}
                                    <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" 
                                    onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}">
                                    {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15]}
                                    </select>
                                {/if}	                  
                            {else}         
                                <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}">
                                {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15]}
                                </select>                                            
                            {/if} 
                        {/if}
                    {elseif $atributos[indice][3] eq 'BUSCADOR'}           
                        {if $atributos[indice][7] eq '0'}
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                            
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true">
                        {else}    
                            <input type="text" name="texto_buscador_{$smarty.section.indice.iteration-1}" 
                            id="texto_buscador_{$smarty.section.indice.iteration-1}" size="23" class="{$atributos[indice][19]}" 
                            onkeyup="ejecutaBuscador(this.value, document.forma_datos.radio_buscador_{$smarty.section.indice.iteration-1}.value, {$atributos[indice][32]}, campo_{$smarty.section.indice.iteration-1});"/>
                            <input type="hidden" name="radio_buscador_{$smarty.section.indice.iteration-1}" value="0" />
                            {section loop=$atributos[indice][31] name=xbus start=0 step=2}                	
                                {if $smarty.section.xbus.loop eq 2}	
                                
                                {else}	                        
                                    {if $smarty.section.xbus.iteration eq 1}
                                        <input type="radio" name="rb_{$smarty.section.indice.iteration}" checked="checked" 
                                        onclick="radio_buscador_{$smarty.section.indice.iteration-1}.value={$smarty.section.xbus.index}"/>{$atributos[indice][31][xbus]}&nbsp;
                                    {else}    
                                        <input type="radio" name="rb_{$smarty.section.indice.iteration}" 
                                        onclick="radio_buscador_{$smarty.section.indice.iteration-1}.value={$smarty.section.xbus.index}"/>{$atributos[indice][31][xbus]}&nbsp;
                                    {/if}
                                {/if}
                            {/section}
                            <br />
                            <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" 
                            onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}" size="5" style="width:100">
                            {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15] }
                            </select>
                        {/if}    	
                    {elseif $atributos[indice][3] eq 'COMBOBUSCADOR'}
                        {if $atributos[indice][7] eq '0'}
                            <input type="text" name="v_campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
                            class="{$atributos[indice][19]}"  onchange="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                            datosdb="{$atributos[indice][16]}" onclick="ocultaCombobusc('campo_{$smarty.section.indice.iteration-1}')"  
                            on_change="{$atributos[indice][29]}" depende="{$atributos[indice][33]}" onchange="compruebaRef()" readonly>
                            
                            <input type="hidden" name="campo_{$smarty.section.indice.iteration-1}" 
                            id="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15][0]}" />
                        {else}
                            <div style="float:left">
                                <div id="divcampo_{$smarty.section.indice.iteration-1}" style="visibility:hidden; display:none; 
                                position:absolute; z-index:3;">
                                <select id="selcampo_{$smarty.section.indice.iteration-1}" size="4" 
                                onclick="asignavalorbusc('campo_{$smarty.section.indice.iteration-1}')" 
                                onkeydown="teclaCombo('campo_{$smarty.section.indice.iteration-1}',event)">
                                    <option></option>
                                </select>
                                </div>				
                                <input type="text" name="v_campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
                                class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                                datosdb="{$atributos[indice][16]}" onclick="ocultaCombobusc('campo_{$smarty.section.indice.iteration-1}')"  
                                on_change="{$atributos[indice][29]}" depende="{$atributos[indice][33]}"  
                                title="Digite una palabra, y se desplegara un menu emergente, seleccione una opci&oacute;n del menu." 
                                autocomplete="off"/>
                                <img src="{$rooturl}imagenes/general/flecha_abajo.gif" style="height:12px;" 
                                onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" 
                                onclick="botonBuscador('campo_{$smarty.section.indice.iteration-1}')"/>										
                                <input type="hidden" name="campo_{$smarty.section.indice.iteration-1}" 
                                id="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15][0]}" />                
                            </div>
                        {/if}
                    {elseif $atributos[indice][3] eq 'BUSCADORCP'}
                        <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
                        class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                        title="Tipo Buscador de CP, digite y de click en buscar para llenar los campos de Ciudad,Estado y Municipio.">
                        <input name="button" type="button"  
                        style="background-image:url(../../imagenes/general/e-boton-bg3.jpg); color:#FFFFFF; font-size:8pt; border-color:#999999 #999999 #999999 #FAFAFA; border-width:0 2px 2px 1px; height:18px;" onclick="buscadorcp('campo_{$smarty.section.indice.iteration-1}');"
                         value="Buscar"/>
                        <input type="hidden" id="hcampo_{$smarty.section.indice.iteration-1}" value="cp|id_estado|id_ciudad|id_delmpo|colonia" />
                    {elseif $atributos[indice][3] eq 'ARCHIVO' and $v neq 1}
                        <input type="file" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" class="{$atributos[indice][19]}" 
                        title="Tipo Archivo, seleccione y asigne un archivo" >
                    {/if}        	
        			</td>			
                    {else}
                        <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        value="{$atributos[indice][15]}"  />
                        <input type="hidden"  name="propiedades_[{$smarty.section.indice.iteration-1}]"  
                        value="{$atributos[indice][2]}|{$atributos[indice][3]}|{$atributos[indice][5]}"  />		
                        {* 1/*} 
                    {/if}      

                    
                    {assign var="contColumnas" value=$contColumnas+1}
                    
					{if $atributos[$contColumnas][38] eq "1"}
						</tr>
					{/if}
                {/if}	
				{assign var="contadorCiclos" value=$contadorCiclos+1}
			{/section}
			<input type="hidden" name='countReg' size="10" value = '{$smarty.section.indice.index}'/>
            </table>
			</div>
		</td>
    </tr>
</table>

{* TERMINA TABLA DE CAMPOS *}

<!-- Para mover los grid a las izquierda -->
{if $t eq ''  && $make neq 'insertar'}
	<script language="javascript">
	if(navigator.appName == 'Microsoft Internet Explorer')
		document.getElementById("tabla_campos").style.styleFloat="left";
	else
		document.getElementById("tabla_campos").style.cssFloat="left";
	</script>
{/if}


{* en esta parte va el grid y sus excepciones*}

<input type="hidden" name="ngrids" value="{$ngrids}" />

{section loop=$grids name=ng start=0}
	<br />
    <input type="hidden" name="file_{$smarty.section.ng.index}" value="" />
    <input type="hidden" name="grid_{$smarty.section.ng.index}" value="{$grids[ng][1]}" />       
    <input type="hidden" name="guardaen_{$smarty.section.ng.index}" value="{$grids[ng][13]}" />
{if $grids[ng][18] eq '1'}
<table>
	<tr>
		<td>
{else if $grids[ng][18] gt '1'}
<td>
{/if}
    {if $grids[ng][25] eq '1' && !($grids[ng][1] eq 'sucursalesusuarios' && $VER_SUCURSAL neq 1)}
  	    <div id="divgrid_{$grids[ng][1]}" style="display:block; float:left;">        
    {else}
		
    	<div id="divgrid_{$grids[ng][1]}" style="display:none">
    {/if}
    <table>
    	<tr>
        	<td>&nbsp;</td>
        	<td  width="0%">
            	<table>
                	<tr>
                    	<td class="encabezado_grid">
                    		{$grids[ng][2]}&nbsp;&nbsp;
                        </td>
                        <td>
                        	{if $grids[ng][10] neq 'false' && $grids[ng][14] neq 'S' && $grids[ng][14] neq 's'}                	
				            	<img src="{$rooturl}imagenes/general/nuevo-it.jpg" alt="" width="20" height="25" border="0" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" onclick="nuevoGridFila('{$grids[ng][1]}')">
            			    {/if}                
                        </td>
                    </tr>
                </table>
            	
            </td>            
            	
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>
            	<div style="overflow-x:auto; width:{$grids[ng][24]}px; padding:0; height:
                {if $grids[ng][12] eq 'S' or $grids[ng][12] eq 's'}
	                {$grids[ng][5]+25}px">
                {else}
                	{$grids[ng][5]+23}px">    
                {/if}
                {if $t neq 'YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=='}
                <table id="{$grids[ng][1]}" cellpadding="0" cellspacing="0" border="1" Alto="{$grids[ng][5]}"
                   conScroll="{$grids[ng][7]}" validaNuevo="{$grids[ng][10]}" despuesInsertar="{$grids[ng][28]}" AltoCelda="{$grids[ng][6]}" auxiliar="0" ruta="{$grids[ng][8]}"
                   validaElimina="{$grids[ng][9]}" Datos="{$grids[ng][11]}{$llave}&campoid={$grids[ng][17]}&id_grid={$grids[ng][0]}&id_PF={$id_PF}" verFooter="{$grids[ng][12]}" guardaEn="{$grids[ng][13]}{$llave}&campoid={$grids[ng][17]}&id_grid={$grids[ng][0]}&make={$make}"
                   listado="{$grids[ng][14]}" class="tabla_Grid_RC" scrollH="{$grids[ng][23]}" despuesEliminar="{$grids[ng][29]}" estilos_header="{$grids[ng][30]}" alto_head="{$grids[ng][31]}" >
                   <!--{if $grids[ng][23] eq 'S'}
	                   width="700px"
                   {/if}  -->  
                	<tr class="HeaderCell">                      
                    	{section loop=$grid_detalle[ng] name=ngd}
                    	{if $grid_detalle[ng][ngd][4] neq 'libre'}
                        	<td tipo="{$grid_detalle[ng][ngd][4]}" modificable="{$grid_detalle[ng][ngd][5]}" mascara="{$grid_detalle[ng][ngd][6]}" align="{$grid_detalle[ng][ngd][7]}" formula="{$grid_detalle[ng][ngd][8]}" datosdb="../grid/getCombo.php?id={$grid_detalle[ng][ngd][0]}" depende="{$grid_detalle[ng][ngd][10]}" onChange="{$grid_detalle[ng][ngd][11]}" largo_combo="{$grid_detalle[ng][ngd][12]}" verSumatoria="{$grid_detalle[ng][ngd][13]}" valida="{$grid_detalle[ng][ngd][14]}" onkey="{$grid_detalle[ng][ngd][15]}" inicial="{$grid_detalle[ng][ngd][16]}" width="{$grid_detalle[ng][ngd][19]}" offsetwidth="{$grid_detalle[ng][ngd][19]}" on_Click="{$grid_detalle[ng][ngd][21]}" multidependencia="{$grid_detalle[ng][ngd][22]}" multiseleccion="{$grid_detalle[ng][ngd][23]}">{$grid_detalle[ng][ngd][2]}</td>
                        {else}
                        	<td tipo="{$grid_detalle[ng][ngd][4]}" modificable="NO" align="{$grid_detalle[ng][ngd][7]}" width="{$grid_detalle[ng][ngd][19]}" offsetwidth="{$grid_detalle[ng][ngd][19]}" valor="{$grid_detalle[ng][ngd][2]}" on_Click="{$grid_detalle[ng][ngd][21]}">{$grid_detalle[ng][ngd][20]}</td>
                        {/if}
	                    {/section}
    	            </tr>       
        	 	</table>
             </div>
             <script>	  	
                CargaGrid('{$grids[ng][1]}');
				{if $grids[ng][27] neq '0'}
					for(ci=NumFilas('{$grids[ng][1]}');ci<{$grids[ng][27]};ci++)
						InsertaFila('{$grids[ng][1]}');
						
					{if $grids[ng][1] eq 'sucursalesusuarios' && $VER_SUCURSAL neq 1}					
						document.getElementById('csucursalesusuarios_4_0').checked = true;
						valorXY('sucursalesusuarios', 4, 0, '1');
					{/if}
				{/if}
              </script> 
        	</td>      
        </tr>
    </table> 
        {/if}  
        {if $t eq 'YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=='}
    <table id="listadodePresentaciones" cellpadding="0" cellspacing="0" border="1" Alto="250"
               conScroll="S" validaNuevo="false" AltoCelda="25" auxiliar="0" ruta="../../imagenes/general/" validaElimina="false" Datos="../ajax/especiales/datosPresentaciones.php?t={$t}&k={$llave}&vpe={$vpe}&npe={$npe}&mpe={$mpe}&epe={$epe}&ipe={$ipe}&gpe={$gpe}" verFooter="N" guardaEn="False" listado="S" class="tabla_Grid_RC" paginador="S" datosxPag="30" pagMetodo='php' ordenaPHP="S" title="Listado de Registros">
          <tr class="HeaderCell">
              
            <td offsetwidth="0" width="0" tipo="oculto"  campoBD="id_presentacion" valor="" >id_presentacion</td>
            <td width="130" offsetWidth="130" tipo="texto" campoBD="nombre" valor="">Descripci&oacute;n</td>
            <td width="50" offsetWidth="50" tipo="binario" modificable="S" >Activo</td>
   
          </tr>            
    </table> 
    <input type="hidden" id="gridPresentaciones" name="gridPresentaciones" />

    
    <script>	  	
	  	CargaGrid('listadodePresentaciones');	
    </script>
{/if}
   {if $t eq 'YW5kZXJwX25vdGFzX3ZlbnRh'}
  <input type="hidden" id="claveNV" name="claveNV" />
   {/if}
    </div>
{if $grids[ng][18] ge '1'}
	  </td>
{/if}
{if $grids[ng][18] ge '3'}
	</tr>     
</table>
{/if}


{/section}

{if $id_PF}
	<input name="borrFaT" type="hidden" value="{$id_PF}" />
{/if}

<!--------------------------- TERMINA AQUI DEBEN IR LO BOTONES ---------------------------->

</form>

{if $t eq 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA==' && $atributos[10][15] == 1}

<br />
<table width="40%">
	<tr>
    	<td>
			<input type="button" value=" Aceptar " name="guardarb" id="guardarb" class="boton" onclick="autoriza(2);"/>
        </td>    
		<td>
			<input type="button" value=" Rechazar " class="boton" onclick="autoriza(3);"/>
		</td>
	</tr>
</table>	                        


<script>
	{literal}
	
		function autoriza(pos)
		{
			obj=document.getElementById('estatus');
			obj.value=pos;
			validaR(document.forma_datos, 'actualizar');
		}
	
	{/literal}
</script>


<br />&nbsp;<br />
{/if}



</div>
{* toda esta parte de scrips se integrara a un archivo js generar propio de los catalagos
y alli se manejaran las excepciones
*} 

{literal}


<script type="text/javascript" language="javascript">
//objform forma
//make accion a realizar
//filtro de la pantalla a considerar

if(document.forma_datos.t.value=="c3lzX3VzdWFyaW9z"){
  if(document.forma_datos.make.value == 'insertar'){

     document.forma_datos.email.value = '';
	 document.forma_datos.pass.value = '';
	  
  }
}

//validacion para catalogo clientes
if(document.forma_datos.t.value == "YW5kZXJwX2NsaWVudGVz"){
   var f = document.forma_datos;
  //alert('AA '+document.forma_datos.make.value);
  if(document.forma_datos.make.value == 'insertar'){  
    var v_pagoSat  =  f.forma_pago_sat.options[f.forma_pago_sat.selectedIndex].value; //prefijo ruta
	//alert(v_pagoSat);
	if(v_pagoSat == 1 || v_pagoSat == 2){
	      f.no_cuenta.value ='NO IDENTIFICADO';
		  f.no_cuenta.readOnly = true;
	   }
	   else{
		   f.no_cuenta.value ='';
		   f.no_cuenta.readOnly = false;
	   }
 	   	   
  }
  else{
        if(document.forma_datos.make.value == 'actualizar'){
		   var v_pagoSat  =  f.forma_pago_sat.options[f.forma_pago_sat.selectedIndex].value; //prefijo ruta
		  if(v_pagoSat == 1 || v_pagoSat == 2){
		     f.no_cuenta.readOnly = true;
	      } 
	   }
  }
} 

function imprime(t)
{
	var f=document.forma_datos;
	if(t == '')
	{
		if(f.campo_0.value=="")
		{
			alert("Debe Guardar el documento para poder imprimirlo.")
			return false;
		}
		window.open('../pdf/imprimeDoc.php?Factura=SI&idfactura='+f.campo_0.value,"imp1","width=900,height=650,top=200,left=200,resizable=NO");
	}
	
}

function irListado(rooturl,tabla)
{
	location.href=	rooturl+"code/indices/listados.php?t="+tabla;
}	
//da de alta un nuevo registro de la misma tabla seleccionada
function irNuevo(rooturl,tabla,tcr)
{
	location.href=rooturl+"code/general/encabezados.php?t="+tabla+"&k=&op=1&tcr="+tcr;
}	

//modifica el registro de la tabla seleccionada EL ID SIEMPRE ES EL CAMPO UNO
function irModificarRegistro(objform,rooturl,tabla,campo,tcr)
{
	
	var id = objform.elements[campo].value;
	
	//alert('??');
	
	var res=ajaxR(rooturl+'code/ajax/verificaPermisos.php?tabla='+tabla+'&id='+id+'&accion=1');
		
	//alert(res);
	if(res != 'si')
	{
		alert('No es posible realizar el registro, ya esta relacionado a otros módulos.');
		return false;
	}
		
	location.href=	rooturl+"code/general/encabezados.php?t="+tabla+"&k="+id+"&tcr="+tcr+"&op=2&v=0";
	
}	

/************************/
function CancelarFactura(){
   
   if (confirm(" Esta seguro de Cancelar esta Factura?")){
      //alert("Cancelada");
   }
   else{
      return false;
   }

}
/*************************/

function irEliminarRegistro(objform,rooturl,tabla,campo)
{	
    
	
	var obj=document.getElementById('waitingplease');
	
	if(document.forma_datos.modificar)
	{
		//document.forma_datos.modificar.disabled=true;		
		obj.style.display="block";		
	}
	
	//tabla productos
	if(objform.t.value=="YW5kZXJwX3Byb2R1Y3Rvcw=="){
	 //  alert("Entro a eliminar grid productos");
	   var f = document.forma_datos;
	   var id_prod = f.id_producto.value;

	   var aux=ajaxR("../ajax/validaEliminarProducto.php?accion=1&id="+id_prod);
	  
	   var ax=aux.split('|');
	   if(ax[0] == 'exito'){
	           
			   var cade = "";
			   var id_sucursal = f.id_sucursal.value;
			   var obj=document.getElementById('detalleproducto');  //objeto grid
			   var num=NumFilas('detalleproducto');
			   
			   var pagAct=obj.pagAct?obj.pagAct:obj.getAttribute("pagAct");  //pagina en cual esta posicionado
			   
			   //recorremos grid de listado de presentaciones 		
		      for(var i=0; i <num; i++){
		   
			      var id_prodDeta = celdaValorXY('detalleproducto', 0, i); //obtiene id_producto_detalle Grid
				
				   if(cade == ""){
				      cade = id_prodDeta;
				   }
				   else{
				      cade = cade + "@@" + id_prodDeta; 
				   }
				 
		      }//fin for i
			  		  
			    if(cade != ""){
				 document.getElementById("accion_pant").value = 4;
				  document.getElementById("accionElim").value = 1;
				  document.getElementById("prodGrid").value = cade;
				}
			 
	
			   
	   }
	   else{
	      alert(ax[1]);
		
		  obj.style.display="none";
		    return false;
	   }
	
	}
	
	//pantalla Notas de Venta VALIDACION 
	/*valida si se puede Eliminar una  nota de venta,Condicines:
	 Si la nota de venta tiene asociada una Factura No se puede Eliminar
	 Si la Cuenta por Cobrar de La nota de Venta tiene pagos o cobros registrados No se Puede Eliminar
	 Cada que se Elimine una Nota de Venta se Elimina su Cuenta por Cobrar
	****/
	if(objform.t.value=="YW5kZXJwX25vdGFzX3ZlbnRh"){
	   
	   var f = document.forma_datos;
	   var id_cnv = f.id_control_nota_venta.value;
	   var id_nv = f.id_nota_venta.value;

       //alert(id_cnv+'-'+id_nv);
	   
	   var aux=ajaxR("../ajax/validaEliminarNotaVenta.php?accion=1&id_nv="+id_nv+"&id_cnv="+id_cnv);
	  // alert(aux);
	   
	   var ax=aux.split('|');
	   //var ax=aux.split('|');
	   if(ax[0] == 'exito'){
	       if(ax[1] != 1){
		       alert(ax[1]); 
			   obj.style.display="none";
		       return false;
		   }
	   }
	  
	}
    
	
	if(objform.t.value=="YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=="){
	   document.getElementById('gridPresentaciones').value = 'borra';
	}
    
	 if(objform.t.value == "cGV1Z19wdW50b3NfdmVudGE=")
	{
		var id_punto_venta=document.getElementById('id_punto_venta').value;
		var aux=ajaxR("../ajax/validaElimPV.php?tipo=1&id_punto_venta="+id_punto_venta);
		var ax=aux.split('|');
		if(ax[0] == 'exito')
		{
			if(confirm(ax[1]))
			{
				aux=ajaxR("../ajax/validaElimPV.php?tipo=2&id_punto_venta="+id_punto_venta);
				if(aux == 'exito')
					objform.submit();							
				else
					alert(aux);	
			}	
		}
		else
		{
			alert(aux);	
			obj.style.display="none";
			return false;
		}	
	}
	else if (!confirm(" Desea eliminar el registro?"))
				return false;	
	else
		objform.submit();	
   				
	
}

function valida(objform,make)
{		
	var obj=document.getElementById('waitingplease');
	
	if(document.forma_datos.modificar)
	{
		document.forma_datos.modificar.disabled=true;		
		obj.style.display="block";		
	}	
	
	if(validaR(objform,make) == false)	
	{
		if(document.forma_datos.modificar)
		{
			document.forma_datos.modificar.disabled=false;		
			obj.style.display="none";
		}	
	}
}
//  *************   abc   ******************
// Obtenemos el objeto al cual hacemos referencia 
// asi podemos manipular cualquier propiedad del mismo
function activarRequeridos(tabla,objform,obj){
	var sinoVer = $("#require_contrato").val();
	if (sinoVer == 1)
	{
		$("#fila_catalogo_20").show();
		$("#fila_catalogo_23").show();
		$("#tabla_fila_catalogo_20").show();
		$("#tabla_fila_catalogo_23").show();
	}
	else
	{
		$("#fila_catalogo_20").hide();
		$("#fila_catalogo_23").hide();
		$("#tabla_fila_catalogo_20").hide();
		$("#tabla_fila_catalogo_23").hide();
		$("#apoderado_legal").val('');
		$("#no_escritura").val('');
		$("#fecha_escritura").val('');
		$("#no_contrato").val('');
		$("#titular_contrato").val('');
		$("#ciudad_contrato").val('');
		$("#pena_convencional").val('');		
	}
	
	switch(tabla){
		case 'of_proveedores': //si chekean requiere contrato, cambiamos datos de requeridos
		                        if(obj.checked){
			                         for(i=20;i<27;i++){
										 elemento=objform.elements["propiedades_["+i+"]"];
			                             //alert("checado elemento "+i+" - "+elemento.value);
										 //cambiamos el ultimo valor por 1 que es el permiso de requerido
										 elemento.value=elemento.value.substring(0,elemento.value.length-1)+"1";
										 //alert("nuevo valor "+i+" - "+elemento.value);
									 }
								}else{
									 for(i=20;i<27;i++){
										 elemento=objform.elements["propiedades_["+i+"]"];
			                             //cambiamos el ultimo valor por 1 que es el permiso de requerido
										 elemento.value=elemento.value.substring(0,elemento.value.length-1)+"0";
									 }
								}
							break;	
	}//fin del switch

}
//creamos la carga de datos en el grid
if(document.forma_datos.t.value=="b2ZfcHJveWVjdG9z" && document.forma_datos.op.value==1){
   //alert("si entre ");
   //referenciamos el id del combo book
   id_book=document.getElementById('id_book');
   //obtenemos el valor del combo para realizar la consulta
   id_valor_book=id_book.value;
     
   ruta_proyectos="../ajax/getDatosAjax.php?opc=2&id_book="+id_valor_book;
   var datosPro=ajaxR(ruta_proyectos);
   JsonProyecto = jQuery.parseJSON(datosPro);
   grid="detalleproyectos";
   //alert(ruta_proyectos+" - "+JsonProyecto);
   
   for(i=0;i<JsonProyecto.length;i++){
	   valorXY(grid,0,i,JsonProyecto[i].id_control_detalle_proyecto);
	   valorXY(grid,1,i,id_valor_book);//insertamos en id_producto_proyecto el valor que obtenemos arriba
	   //valorXY(grid,2,i,JsonProyecto[i].id_proyecto);
	   valorXY(grid,3,i,JsonProyecto[i].id_pto_fran);
	   valorXY(grid,4,i,JsonProyecto[i].nombre);
	   valorXY(grid,5,i,JsonProyecto[i].nombre_del_espacio);
	   valorXY(grid,6,i,JsonProyecto[i].titulo_para_impresion);
	   valorXY(grid,7,i,JsonProyecto[i].insertar_en_pagina_numero);
	   //insertamos filas nuevas dependiendo a tamaño del arreglo
	   //kitando la fila ya insertada ...total de datos-1
	   if(i<(JsonProyecto.length-1)){
	      InsertaFila('detalleproyectos');
	   }
   }
}//fin del if de carga de datos para proyectos
//  ***************************************

function validaR(objform,make)
{
    //   **************  abc  ****************
	// Exepciones para the books
	//veridfcamos si en la tabla proveedores esta activado el check
	//requiere contrato para cambiar los campos de contrato a requeridos
	if(objform.t.value=="b2ZfcHJvdmVlZG9yZXM="){
		check=document.getElementById('require_contrato');
		 if(check.checked){
			 for(i=20;i<27;i++){
				 elemento=objform.elements["propiedades_["+i+"]"];
				 //cambiamos el ultimo valor por 1 que es el permiso de requerido
				 elemento.value=elemento.value.substring(0,elemento.value.length-1)+"1";
			 }
		 }//if checed
	}
	//   *************************************
	
	var strSelected="";
	var strSelectedSuc="";
	var varinicio=1;
	var validaIDEdit='no';
	
	objform.elements['generaSubmit'].value='0';

	//si el la llave es del tipo chard y es requerida comenzamos a validar desde 0
	var des_arrayID=(objform.elements["propiedades_[0]"].value).split("|");
		
	if(des_arrayID[1]=="CHAR" && des_arrayID[2]=="1")
	{
		varinicio=0;
		
		//validamos si estamos en el insert
		//si la opcion es 1
		if(objform.elements["make"].value=='insertar')
		{
			validaIDEdit='si';
		}
		
		//si estamos realizando el insert 
			
	}
	
	for(var i=varinicio; i<parseInt(objform.countReg.value);i++)
	{
		var des_array=(objform.elements["propiedades_["+i+"]"].value).split("|");
		
		
		//si estamos en la fecha de alta mejor lo mandamos desde el codigo
		if (des_array[1]=="DATE" &&  objform.elements["campo_"+i+""].value=="" && des_array[0]=="FECHA ALTA")
		{
			//colocamos la fecha de alta de hoyDate() 
			 objform.elements["campo_"+i+""].value= "now()";
		}
		
		//validamos el formato de la fecha
		if (des_array[1]=="DATE" &&  objform.elements["campo_"+i+""].value!="" &&  objform.elements["campo_"+i+""].value!="now()" &&  objform.elements["campo_"+i+""].value!="0000-00-00 00:00:00" && objform.elements["campo_"+i+""].value!="0000-00-00" && objform.elements["campo_"+i+""].value!="NULL")
		{
		
			//colocamos la fecha de alta de hoyDate() 
			//alert(esFechaValida(objform.elements["campo_"+i+""].value));
			if (esFechaValida(objform.elements["campo_"+i+""].value)==false && objform.elements["campo_"+i+""].value!= "00/00/0000")
			{
				alert("El formato de  "+ des_array[0]+ " es inválido");
				return false;
			}
		}
	
		if (des_array[1]=="TIME" &&  objform.elements["campo_"+i+""].value!="")
		{
			
			if (esHora(objform.elements["campo_"+i+""].value)==false)
			{
				alert("El campo  "+ des_array[0]+ " es inválido.");
				return false;
			}
		}
		
		
		if(des_array[1]=="COMBOBUSCADOR")
		{
			objform.elements["v_campo_"+i+""].value=trim(objform.elements["v_campo_"+i+""].value);
		}
		
		var regexp = /^[0-9]$/;
		if(des_array[1]=="COMBOBUSCADOR" && des_array[2]=="3" && isNaN(parseInt(objform.elements["campo_"+i+""].value)))
		{
			
			alert("Debe seleccionar un registro válido del listado en el campo "+des_array[0]+".");
			objform.elements["v_campo_"+i+""].focus();
			return false;
		}
		if(des_array[1]=="COMBOBUSCADOR" && des_array[2]=="2" && isNaN(parseInt(objform.elements["campo_"+i+""].value)) && objform.elements["campo_"+i+""].value != "0")
		{
			
			alert("Debe seleccionar un registro válido del listado en el campo "+des_array[0]+".");
			objform.elements["v_campo_"+i+""].focus();
			return false;
		}

		if (des_array[2]=="1" && objform.elements["campo_"+i+""].value=="")
		{
			alert("El campo "+des_array[0]+" es requerido.");
			objform.elements["campo_"+i+""].focus();
			return false;
           
		}
		
		
		if (des_array[1]=="EMAIL" &&  objform.elements["campo_"+i+""].value!="")
		{
			if (validaMail(objform.elements["campo_"+i+""].value)==false)
			{
				alert("El campo  "+ des_array[0]+ " es inválido.");
				return false;
			}
		}
		
	}
	
	//EXCEPCIONES POR CATALOGO
	if(objform.t.value=="c3BhX25vdGFzX3NlcnZpY2lvcw=="){
		/*VALIDAR NOTAS DE CAMBIO*/
		var error = 0;
		Array.prototype.unique=function(a){
			return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
		});
		var arregloId = new Array();
		var arregloIdSinDuplicados = new Array();
		
		var montoFila = "";
		var idNotaCambio = "";
		var valorIdNotaCambio = "";
		var idTipoPago = 0;
		
		$("#Body_detalleNotasServiciosPagos tbody tr").each(function (index) {
			$(this).children("td").each(function (index2) {
				switch (index2) {
					case 5:
						idTipoPago = $(this).attr("valor").replace(/,/g, '').replace('$', '');
						break;
					case 10:
						montoFila = $(this).text().replace(/,/g, '').replace('$', '');
						break;
					case 11:
						idNotaCambio = $(this).attr("valor").replace(/,/g, '').replace('$', '');
						valorIdNotaCambio = $(this).html().replace(/,/g, '').replace('$', '');
						if(idNotaCambio != ""){
							arregloId.push(idNotaCambio);
						}
						break;
				}
			});
			
			if(idTipoPago == 4){
				var resp = ajaxR("../ajax/getDatosAjax.php?opc=5&idNota=" + idNotaCambio);
				disponible = jQuery.parseJSON(resp);
				//console.log(disponible[0].disponible + "<" + montoFila);
				if(parseFloat(disponible[0].disponible) < parseFloat(montoFila)){
					alert("Ingreso una cantidad superior a lo disponible para la nota de cambio " + valorIdNotaCambio);
					error = 1;
				}
			}
		});
		
		arregloIdSinDuplicados = arregloId.unique();
		
		if(arregloId.length != arregloIdSinDuplicados.length){
			alert("Ingreso m\u00E1s de una vez una misma nota de cambio");
			error = 1;
		}
		
		if(error == 0){
			//monto minimo
			importe_minimo = 100;
			
			total = $("#total").val();
			total = total.replace(/,/g, '').replace('$', '');
			
			sumaColumnaGrid('detalleNotasServiciosPagos', 10, 'saldo_pendiente');
			saldo_pendiente = $("#saldo_pendiente").val();
					
			saldo_pendiente = saldo_pendiente.replace(/,/g, '').replace('$', '');
			$("#saldo_pendiente").val(formatear_pesos(total - saldo_pendiente));	
	
			if(importe_minimo > saldo_pendiente){
				alert("El importe pagado es inferior a lo m\u00EDnimo solicitado");
				return false;
			}else if((total - saldo_pendiente) < 0){
				alert("El importe pagado es superior a los servicios contratados");
				return false;
			}
		}else{
			return false;	
		}
	}
	
	if(objform.t.value=="c3BhX25vdGFzX3Byb2R1Y3Rvcw=="){
		total = $("#total").val();
		total = total.replace(/,/g, '').replace('$', '');
		
		sumaColumnaGrid('detalleNotasProductosPagos', 10, 'saldo_pendiente');
		saldo_pendiente = $("#saldo_pendiente").val();
				
		saldo_pendiente = saldo_pendiente.replace(/,/g, '').replace('$', '');
		$("#saldo_pendiente").val(formatear_pesos(total - saldo_pendiente));	

		if((total - saldo_pendiente) < 0){
			alert("El importe pagado es superior a los productos comprados");
			return false;
		}else if((total - saldo_pendiente) > 0){
			alert("El importe pagado es inferior a los productos comprados");
			return false;
		}
	}
	
	//validamos la excepcion de ususarios si es que 
	if(objform.t.value=="c3lzX3VzdWFyaW9z")
	{
	    //quitamos espacios en blanco a la derecha e izquierda
        document.getElementById('login').value = trim(document.getElementById('login').value);
		document.getElementById('pass').value = trim(document.getElementById('pass').value);
		document.getElementById('password2').value = trim(document.getElementById('password2').value);
	
		//validamos que el password y confirmacion del password sean iguales y mayores a cero
		if(document.getElementById('pass').value.length<6)
		{
			alert("El PASSWORD debe constar de almenos 6 caracteres");
			return false;			
		}
		
		if(document.getElementById('pass').value != document.getElementById('password2').value )
		{
			alert("El campo PASSWORD es distinto al CONFIRMAR PASSWORD.");
			return false;
		
		}
	}
	
	if(objform.t.value=="cGV1Z19jbGllbnRlc191c3Vhcmlvc193ZWI=")
	{
		//validamos que el password y confirmacion del password sean iguales y mayores a cero
		if(document.getElementById('contrasena').value.length < 6 && document.getElementById('contrasena').value.length > 0)
		{
			alert("El PASSWORD debe constar de almenos 6 caracteres");
			return false;			
		}
		
		if(document.getElementById('contrasena').value != document.getElementById('validar_contrasena').value && document.getElementById('contrasena').value.length > 0)
		{
			alert("El campo PASSWORD es distinto al CONFIRMAR PASSWORD.");
			return false;
		
		}
	}
	
	/*Excepcion Validacion de Pantalla Rutas
	****/
	if(objform.t.value == "YW5kZXJwX3J1dGFz"){
           
	  if(objform.id_ruta.value != '')	   
	      var v_id_ruta = objform.id_ruta.value;
      else 		
	      var v_id_ruta = 0;
	  
	  var v_prefijo = objform.prefijo.value;
	  var v_nombre = objform.nombre.value;
	  
	  //validamos si el Prefijo de la Ruta ya existe en otra sucursal
	  var aux = ajaxR("../ajax/validaRutas.php?accion=1&prefijo="+v_prefijo+"&name_ruta="+v_nombre+"&ruta_sel="+v_id_ruta);
	   
		 var ax = aux.split("|");
		 if(ax[0] == 'exito'){
		   // alert(ax[1]);
			return false;
		 } 
		 
	  //validamos el vendedor seleccionado
	  
	  	var v_vendedor = objform.id_vendedor.value;
		
	    var aux = ajaxR("../ajax/validaRutas.php?accion=2&id_vende="+v_vendedor+"&ruta_sel="+v_id_ruta);
	   
		 var ax = aux.split("|");
		 if(ax[0] == 'exito'){
		    //alert(ax[1]);
			return false;
		 } 
		
	}//fin de if ruta
                  




	
	/*Excepcion Validacion de encabezado clientes
	 Si el Cliente Requiere Factura debe llenar los campos basicos de RFC,CURP,Domicilio,etc y la Forma de Pago SAT debe seleccionarla y el No de Cuenta debe ser minimo 4 caracteres y maximo 18 o la palabra No identificado
	 
	 Si el Cliente No requiere Facturas, los campos Formas de pago SAT son por Default NO IDENTIFICADO y el campo No de Cuenta es NO IDENTIFICADO.
	*****/
	if(objform.t.value == "YW5kZXJwX2NsaWVudGVz"){

	   var f = document.forma_datos;
	   var v_require_factura = document.getElementById('require_factura');
	   //checkbox Requiere Factura
	   if(v_require_factura.checked == true){
	     //requiere de datos Fiscales 
		 if(f.rfc.value == ''){
		    alert("El campo RFC es requerido.");
			f.rfc.focus();
			return false;
		 }
		  if(f.curp.value == ''){
		    alert("El campo CURP es requerido.");
			f.curp.focus();
			return false;
		 }
		 
		 //Caso Formas de pago SAT es por DEFAULT No Identificado
		 //Caso Campo numero de cuenta se requiere min 4 caracteres y max 18 
		  if(f.no_cuenta.value == ''){
		    alert("El campo N\u00famero de Cuenta es requerido.");
			f.no_cuenta.focus();
			return false;
		 }
		 
		  if(f.no_cuenta.value.length < 4){
		     alert("El N\u00famero de Cuenta no puede ser menor a 4 caracteres.");
			f.no_cuenta.focus();
			return false;			 
		  }
		  if(f.no_cuenta.value.length >18 ){
		     alert("El N\u00famero de Cuenta no puede ser mayor a 18 caracteres.");
			f.no_cuenta.focus();
			return false;			 
		  }
		 
		 
	   }
	   
	   
	    
	   
          //caso para dias de revicion
	   /*if(f.id_forma_de_pago.value == 2){
	       //si la forma de pago es credito 
		   //Se requiere de un dia de revision o la opcion no aplica y agregar dias de revision
		   
		   if(f.dias_credito.value == '' || f.dias_credito.value == '0'){
		     alert("La forma de pago es Crédito,debe seleccionar un Dia de Revisión y Asignar Días de Crédito.");
			  f.dias_credito.value='';
			  f.dias_credito.focus();
			   return false;
		   }
	   
	   }*/
	
	 
	}	

	//Excepcion para Cuentas por Cobrar
	if(objform.t.value == "YW5kZXJwX2N1ZW50YXNfcG9yX2NvYnJhcg=="){ 
	 
	   var f = document.forma_datos;
	   var v_importe = f.total.value;
	  // alert('Importe Cabecera'+v_importe);
	   var subtotal = 0;
	   var nfil=NumFilas('detallecobros');
	   
	   
	   //validacion de Tipo de Cobro
	   //Si es Cheque debe ingresar el campos Docuimento en Grid Detalle de Cobros
	   for(var k=0;k<nfil;k++){
			var v_tipo_cobro = celdaValorXY('detallecobros',3,k); 
			var v_documento = celdaValorXY('detallecobros',4,k); 
			if(v_tipo_cobro == 2 && v_documento == ''){//si es tipo Cheque
			   alert("Debe llenar el campo Documento porque es un Tipo de Cobro Cheque.");
			   return false;
			}
		}//fin for

        //valida si la suma de los montos campturados arrebasa el saldo de la cuenta por pagar 
		for(var i=0;i<nfil;i++){
			var v_monto = celdaValorXY('detallecobros',6,i); 
		   subtotal += parseFloat((v_monto=="")?0:v_monto);
		}
	///alert('Subtotal'+subtotal);

		if(subtotal > v_importe){
		   alert("La Sumatoria de los Montos a Cobrar debe ser exacta al Importe de la Cuenta por Cobrar.");  
		   return false;
		}
		
		if(subtotal == v_importe){
		   //Si la sumatoria de los montos es igual al importe de nota de venta la cuenta cambia a saldada
		  //document.getElementById('saldada').checked = true;
		   document.getElementById('CuantaSaldar').value=1;
		}
	
		
	}//fin if tabla cuentas x cobrar
	
	//VALIDACION DEL GRID
if(objform.t.value!="YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=="){ //si es diferente a la tabla anderp_productos_tipos valida grid	
	var ng=parseInt(objform.ngrids.value);

	for(var i=0;i<ng;i++)
	{
		if(validaGridTotalCampos(objform.elements["grid_"+i].value) == false)
			return false;
		if(objform.elements["guardaen_"+i].value.length > 0 && objform.elements["guardaen_"+i].value != "NO")
		{
			
			var aux=GuardaGrid(objform.elements["grid_"+i].value, 10);		
			//alert(aux);
			var ax=aux.split('|')
			if(ax[0] == 'exito')
			{
				objform.elements["file_"+i].value=ax[1];
				//alert("file_"+i+" = "+ax[1]);
			}	
			else		
				alert(aux);	
		}		
		//return false;	
	}
	//return false;
   }//fin de tabla excepcion
	//TERMINA VALIDACION DEL GRID
	
	//alert('Zehahaha');return false;
	

	{/literal}
	
	{literal}
	
	

	
	if(validaIDEdit=='si')
	{
		 
		var campo=objform.elements['campo_0'].id;
		var valor=objform.elements['campo_0'].value;
		var tabla=objform.elements['t'].value;
		
		objform.elements['generaSubmit'].value='1';
				
		makeRequest('../ajax/ajaxresponse.php?campo='+campo+'&id='+valor+'&tabla='+tabla+'&code=si','validaId');
		
		return false;
	}
	//return false;
	objform.strSelected.value=strSelected;
	document.getElementById('guardarb').disabled="true";
	
	
	//Validacion en cambio de usuarios
	if(objform.t.value=="c3lzX3VzdWFyaW9z")
	{
		var f=document.forma_datos;
	    var v_idSucursal = objform.id_sucursal.value;
		var banderaGpr =0;
		document.getElementById('login').value = trim(document.getElementById('login').value);
		document.getElementById('pass').value = trim(document.getElementById('pass').value);
        document.getElementById('password2').value = trim(document.getElementById('password2').value); 
		//confirmar contraseña
		//validamos que el password y confirmacion del password sean iguales y mayores a cero
		if(document.getElementById('pass').value.length<6)
		{
			alert("El PASSWORD debe constar de almenos 6 caracteres");
			document.getElementById('pass').value='';
			document.getElementById('password2').value='';
			document.getElementById('pass').focus();
			return false;			
		}
		
		if(document.getElementById('pass').value != document.getElementById('password2').value)
		{
			alert("El campo PASSWORD es distinto al CONFIRMAR PASSWORD.");
			document.getElementById('password2').value='';
			document.getElementById('password2').focus();
			return false;
		
		}
		
		
		//seleccionar afuerza un GRUPO del Grid
		/* var cabs=document.getElementById('Body_gruposusuarios');	
	Trs=cabs.getElementsByTagName('tr');
	Tds=Trs[0].getElementsByTagName('td');
	//alert(Trs.length+' -'+Tds.length);

	for(var i=0; i<Trs.length; i++){
	      var cabs=document.getElementById('cgruposusuarios_4_'+i);
		  //alert(cabs.checked);
		  if(cabs.checked == true){
		      banderaGpr =1;
		  }
	}
	if(banderaGpr==0){
	   alert("Debe Seleccionar un Grupo para el usuario.");
	   return false;
	}
	*/
		
		var nombre=document.getElementById('nombres').value;
		var apaterno=document.getElementById('apellido_paterno').value;
		var amaterno=document.getElementById('apellido_materno').value;
		var pass=trim(document.getElementById('pass').value);
		var login=trim(document.getElementById('login').value);
		//var email=trim(document.getElementById('email').value);
		var id_usuario=trim(document.getElementById('id_usuario').value);

		//Validamos que el usuario no tenga el mismo LOGIN que un usuario existente
				
      var aux = ajaxR("../ajax/catalogos/validaNewUser.php?accion=1&nlogin="+document.getElementById('login').value+"&sucursal="+v_idSucursal+"&actual="+document.getElementById('id_usuario').value);
	 // alert(aux);
		 var ax = aux.split("|");
	
		 if(ax[0] != 'exito'){
		    alert(ax[0]);
			return false;
		 } 
		 
		 
		 
		 
	}
	
	
//EXCEPCIONES POR CATALOGO 
//Catalogo Series 

if(objform.t.value=="YW5kZXJwX3Nlcmllcw=="){
	//alert("entro a catalogo series");
	//validamos que no ingrese un mismo nombre de serie por sucursal
	      var v_idSerie = objform.id_serie.value; //document.getElementById('id_serie').value;
		  var v_idSucursal = objform.id_sucursal.value;
		   var v_descrip = objform.nombre.value;
		   
		  
		  //alert(v_idSerie+'-'+v_idSucursal+'-'+v_descrip); 
	      var aux = ajaxR("../ajax/especiales/validacionesSeries.php?accion=1&id_serie="+v_idSerie+'&idSucursal='+v_idSucursal+'&descrip='+v_descrip);
	    //alert(aux);
		 var ax = aux.split("|");
	
		 if(ax[0] != 'exito'){
		    alert(ax[0]);
			return false;
		 } 
		 
	  	  if(ax[1] > 0)//numero de registros
		  {
			alert("Ya existe el nombre de la Serie "+v_descrip+" para otra Sucursal,Verifique.");
			return false;
		  }	
		  
		  //valida no agrege dos series a una misma sucursal
		 var aux = ajaxR("../ajax/especiales/validacionesSeries.php?accion=2&id_serie="+v_idSerie+'&idSucursal='+v_idSucursal+'&descrip='+v_descrip);
	   // alert(aux);
		 var ax = aux.split("|");
	
		 if(ax[0] != 'exito'){
		    alert(ax[0]);
			return false;
		 } 
		 
	  	  if(ax[1] > 0)//numero de registros
		  {
			alert("Ya existe la Serie para esta Sucursal,Verifique.");
			return false;
		  }	
		
		  
	
}

//Si es el catalogo Tipos de productos (productos_tipos) se toman los valores del check seleccionado psra guardar la presentacion de cada tipo de producto 
	if(objform.t.value=="YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=="){
		   
	   //variables
	   var cade = '';
	   var obj=document.getElementById('listadodePresentaciones');  //objeto grid
	   var num=NumFilas('listadodePresentaciones');
	   var pagAct=obj.pagAct?obj.pagAct:obj.getAttribute("pagAct");  //pagina en cual esta posicionado
	   //recorremos grid de listado de presentaciones 		
		for(var i=0; i <num; i++){
		    var o=document.getElementById('clistadodePresentaciones_2_'+(i+((pagAct-1)*30)));
		     if(o.checked == true) //escojemos las opciones seleccionadas
			   {
			      var id = celdaValorXY('listadodePresentaciones', 0, i);
				  //alert(id)
				  if(cade == ''){
				     cade = id;
				  }
				  else{
				    cade = cade + '@@'+id;
				  }
			  }	  
	    }	
		
		//alert(cade);		  
	  //colocamos en un campo oculto los id's de las presentaciones seleccionadas para ese tipo de producto			  
	  document.getElementById('gridPresentaciones').value = cade; 
	   
	}

	/****************************/
	//Pantalla  Tipos de Productos
	 if(objform.t.value=="YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=="){
	
	  //valida si el tipo de producto ya existe 
	  if(objform.nombre.value != null){
	   
	   if(objform.id_producto_tipo.value != '')
	     var v_id_tipo = objform.id_producto_tipo.value; //Cuando es edicion
	   else
	     var v_id_tipo = 0; //cuando es nuevo

	     var v_nombre = objform.nombre.value; //nombre
		 var v_nom = v_nombre.toUpperCase(); 

		//valida que el tipo de producto no exista 
        var aux = ajaxR("../ajax/validaProducto.php?accion=3&id_tipo_prod="+v_id_tipo+"&name_prod="+v_nom);
	    // alert(aux);
		 var ax = aux.split("|");
		 if(ax[0] == 'exito'){
		    alert(ax[1]);
			return false;
		 } 
	  }
	
	}//fin if tipos productos
	
  /****************************/
	//Pantalla  Tipos de Presentacion
	 if(objform.t.value=="YW5kZXJwX3ByZXNlbnRhY2lvbmVz"){
	
	  //valida si el tipo de presentacion ya existe 
	  if(objform.nombre.value != null){
	   
	   if(objform.id_presentacion.value != '')
	     var v_id_presentacion = objform.id_presentacion.value; //Cuando es edicion
	   else
	     var v_id_presentacion = 0; //cuando es nuevo

	     var v_nombre = objform.nombre.value; //nombre
		 var v_nom = v_nombre.toUpperCase(); 

		//valida que el tipo de presentacion no exista 
        var aux = ajaxR("../ajax/validaProducto.php?accion=4&id_presentacion="+v_id_presentacion+"&name_present="+v_nom);
	   //  alert(aux);
		 var ax = aux.split("|");
		 if(ax[0] == 'exito'){
		    alert(ax[1]);
			return false;
		 } 
	  }
	
	}//fin if tipos productos
	
	/****************************/
	//pantalla Ciudades
	if(objform.t.value=="YW5kZXJwX2NpdWRhZGVz"){
	  //valida si ciudad ya existe 
	  if(objform.nombre.value != null){
	  
	   if(objform.id_ciudad.value != '')
	     var v_id_ciu = objform.id_ciudad.value; //Cuando es edicion
	   else
	     var v_id_ciu = 0; //cuando es nuevo
		  	
		 var v_estado = objform.id_estado.value; //estado	  
	     var v_nombre = objform.nombre.value; //nombre
		 var v_nom = v_nombre.toUpperCase(); 
		//valida que la ciudad no exista 
        var aux = ajaxR("../ajax/validaCiudades.php?accion=1&id_ciu="+v_id_ciu+"&name_ciu="+v_nom+"&id_estado="+v_estado);
		// alert(aux);
		 var ax = aux.split("|");
		 if(ax[0] == 'exito')
		 {
		    alert(ax[1]);
			return false;
		 } 
	  }
	
	}//fin if ciudades
	
	/***************************** VALIDACIONES PARA NO INSERTAR REPETIDOS EN CATALOGOS ***********************/
	if (document.forma_datos.make.value == "insertar")
	{
		/************** VALIDA ESTADOS ***************************/
		if(objform.t.value=="b2ZfZXN0YWRvcw==")
		{
			if(objform.nombre.value != null)
			{		
				var v_pais = objform.id_pais.value;
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=1" + "&nombre="+v_nom+"&id_pais="+v_pais);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA ESTADOS ***************************/
		
		/************** VALIDA PROVEEDOR ***************************/
		if(objform.t.value == "b2ZfcHJvdmVlZG9yZXM=")
		{
			alert(objform.razon_social.value);
			if(objform.razon_social.value != null)
			{		
				var v_razon_social = objform.razon_social.value;
				var v_rfc = objform.rfc.value;
				var v_nom = v_razon_social.toUpperCase(); 
				var v_rfc = v_rfc.toUpperCase(); 
				
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=3" + "&razon_social=" + v_nom + "&rfc=" + v_rfc);
				
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PROVEEDOR ***************************/
		
		/************** VALIDA PAIS ***************************/
		if(objform.t.value == "b2ZfcGFpc2Vz")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=4" + "&nombre=" + v_nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PAIS ***************************/
		
		/************** VALIDA FRANQUICIA ***************************/
		if(objform.t.value == "b2ZfZnJhbnF1aWNpYXM=")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_book = objform.id_book.value;
				var v_id_plaza = objform.id_plaza.value;
				var v_nom = v_nombre.toUpperCase(); 
							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=5" + "&nombre=" + v_nom + "&id_book=" + v_id_book + "&id_plaza=" + v_id_plaza);
				
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA FRANQUICIA ***************************/
		
		/************** VALIDA PROSPECTO POR USUARIO ***************************/
		if(objform.t.value == "ZnJfcHJvc3BlY3Rvcw==")
		{
			if(objform.razon_social.value != null && objform.rfc.value != null)
			{		
				var razon = objform.razon_social.value;
				var rfc = objform.rfc.value;

				var v_razon = razon.toUpperCase();
				var v_rfc = rfc.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=12" + "&razon_social=" + v_razon + "&rfc=" + v_rfc);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PROSPECTO POR USUARIO ***************************/
		
		/************** VALIDA OPERADORA ***************************/
		if(objform.t.value == "b2Zfb3BlcmFkb3Jh")
		{
			if(objform.razon_social.value != null && objform.rfc.value != null)
			{		
				var razon = objform.razon_social.value;
				var rfc = objform.rfc.value;

				var v_razon = razon.toUpperCase();
				var v_rfc = rfc.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=13" + "&razon_social=" + v_razon + "&rfc=" + v_rfc);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA OPERADORA ***************************/
		
		/************** VALIDA CIUDAD ***************************/
		if(objform.t.value == "b2ZfY2l1ZGFkZXM=")
		{
			if(objform.nombre.value != null)
			{		
				var v_id_pais = objform.id_pais.value;
				var v_id_estado = objform.id_estado.value;
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=2" + "&nombre=" + v_nom + "&id_pais=" + v_id_pais + "&id_estado=" + v_id_estado);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA CIUDAD ***************************/
		
		/************** VALIDA BOOK ***************************/
		if(objform.t.value == "b2ZfY2l1ZGFkZXM=")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=14" + "&nombre=" + v_nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA BOOK ***************************/
		
		/************** VALIDA CUENTAS BANCARIAS OPERADORA ***************************/
		if(objform.t.value == "b2ZfY3VlbnRhc19iYW5jYXJpYXM=")
		{
			if(objform.numero_de_cuenta.value != null)
			{		
				var v_numero = objform.numero_de_cuenta.value;
				var v_banco = objform.banco.value;
				var v_sucursal = objform.sucursal.value;
				var v_clabe = objform.clabe.value;
				
				var banco = v_banco.toUpperCase();
				var sucursal = v_sucursal.toUpperCase();	
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=9" + "&numero_de_cuenta=" + v_numero + "&banco=" + banco + "&sucursal=" + sucursal + "&clabe=" + v_clabe);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA CUENTAS BANCARIAS OPERADORA ***************************/
		
		/************** VALIDA NEGOCIO ***************************/
		if(objform.t.value == "b2ZfbmVnb2Npb3M=")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_book = objform.id_book.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=8" + "&nombre=" + nom + "&id_book=" + v_id_book);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA NEGOCIO ***************************/
		
		/************** VALIDA PLAZA ***************************/
		if(objform.t.value == "b2ZfcGxhemE=")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_estado = objform.id_estado.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=10" + "&nombre=" + nom + "&id_estado=" + v_id_estado);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PLAZA ***************************/
		
		/************** VALIDA PRODUCTOS DE INVENTARIO POR PROYECTO ***************************/
		if(objform.t.value == "b2ZfcHJvZHVjdG9zX2ludmVudGFyaW9fcHJveWVjdG8=")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_book = objform.id_book.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=15" + "&nombre=" + nom + "&id_book=" + v_id_book);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PRODUCTOS DE INVENTARIO POR PROYECTO ***************************/
		
		/************** VALIDA PRODUCTOS SERVICIOS ***************************/
		if(objform.t.value == "b2ZfcHJvZHVjdG9zX3NlcnZpY2lvcw==")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_book = objform.id_book.value;
				var v_id_negocio = objform.id_negocio.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=16" + "&nombre=" + nom + "&id_book=" + v_id_book + "&id_negocio=" + v_id_negocio);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PRODUCTOS SERVICIOS ***************************/
		
		/************** VALIDA ESTADO CIVIL ***************************/
		if(objform.t.value == "b2ZfZXN0YWRvX2Npdmls")
		{
			if(objform.descripcion.value != null)
			{						
				var v_descripcion = objform.descripcion.value;
				var nom = v_descripcion.toUpperCase();							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=17" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA ESTADO CIVIL ***************************/
		
		/************** VALIDA ACTIVIDADES DE PROYECTO ***************************/
		if(objform.t.value == "b2ZfYWN0aXZpZGFkZXNfcHJveWVjdG8=")
		{
			if(objform.descripcion.value != null)
			{						
				var v_descripcion = objform.descripcion.value;
				var nom = v_descripcion.toUpperCase();							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=18" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA ACTIVIDADES DE PROYECTOA ***************************/
		
		/************** VALIDA ESTATUS ORDEN DE COMPRA ***************************/
		if(objform.t.value == "b2ZfZXN0YXR1c19vcmRlbl9jb21wcmE=")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				var nom = v_nombre.toUpperCase()						
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=7" + "&nombre=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA ESTATUS ORDEN DE COMPRA ***************************/
		
		/************** VALIDA GASTO MENSUAL ***************************/
		if(objform.t.value == "b2ZfZ2FzdG9zX21lbnN1YWxlcw==")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				var nom = v_nombre.toUpperCase()						
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=19" + "&nombre=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA GASTO MENSUAL ***************************/
	
		/************** VALIDA INGRESO MENSUAL ***************************/
		if(objform.t.value == "b2ZfZ2FzdG9zX21lbnN1YWxlcw==")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				var nom = v_nombre.toUpperCase()						
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=20" + "&nombre=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA INGRESO MENSUAL ***************************/
		
		
		/************** VALIDA TASAS IVA ***************************/
		if(objform.t.value == "b2ZfdGFzYXNfaXZh")
		{
			if(objform.porcentaje_iva.value != null)
			{						
				var v_porcentaje_iva = objform.porcentaje_iva.value;
							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=6" + "&porcentaje_iva=" + v_porcentaje_iva);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TASAS IVA ***************************/
		
		/************** VALIDA NIVEL DE ESTUDIOS ***************************/
		if(objform.t.value == "b2Zfbml2ZWxfZXN0dWRpb3M=")
		{
			if(objform.descripcion.value != null)
			{						
				var v_desc = objform.descripcion.value;
				var descripcion = v_desc.toUpperCase();
							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=21" + "&descripcion=" + descripcion);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA NIVEL DE ESTUDIOS ***************************/
		
		/************** VALIDA NUMERO DE HIJOS ***************************/
		if(objform.t.value == "b2ZfbnVtZXJvX2hpam9z")
		{
			if(objform.descripcion.value != null)
			{						
				var v_desc = objform.descripcion.value;
				var descripcion = v_desc.toUpperCase();
							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=22" + "&descripcion=" + descripcion);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA NUMERO DE HIJOS ***************************/
		
		/************** VALIDA SUBSUBTIPO GASTO ***************************/
		if(objform.t.value == "b2Zfc3Vic3VidGlwb19nYXN0bw==")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				var v_id_subtipo_gasto = objform.id_subtipo_gasto.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=23" + "&nombre_descripcion=" + nom + "&id_subtipo_gasto=" + v_id_subtipo_gasto);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA SUBSUBTIPO GASTO ***************************/
		
		/************** VALIDA SUBTIPO GASTO ***************************/
		if(objform.t.value == "b2Zfc3VidGlwb19nYXN0bw==")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				var v_id_tipo_gasto = objform.id_tipo_gasto.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=24" + "&nombre_descripcion=" + nom + "&id_tipo_gasto=" + v_id_tipo_gasto);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA SUBTIPO GASTO ***************************/
		
		/************** VALIDA TIPO CASA ***************************/
		if(objform.t.value == "b2ZfdGlwb19jYXNh")
		{
			if(objform.descripcion.value != null)
			{		
				var v_nombre = objform.descripcion.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=25" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TIPO CASA ***************************/
				
		/************** VALIDA TIPO CUENTA POR COBRAR ***************************/
		if(objform.t.value == "b2ZfdGlwb19jdWVudGFzX3Bvcl9jb2JyYXI=")
		{
			if(objform.descripcion.value != null)
			{		
				var v_nombre = objform.descripcion.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=26" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TIPO CUENTA POR COBRAR ***************************/
		
		/************** VALIDA TIPO GASTO ***************************/
		if(objform.t.value == "b2ZfdGlwb19nYXN0bw==")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				var v_id_negocio = objform.id_negocio.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=27" + "&nombre_descripcion=" + nom + "&id_negocio=" + v_id_negocio);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TIPO GASTO ***************************/
		
		/************** VALIDA TIPO PAGO ***************************/
		if(objform.t.value == "b2ZfdGlwb19wYWdv")
		{
			if(objform.descripcion.value != null)
			{		
				var v_nombre = objform.descripcion.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=28" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TIPO PAGO ***************************/
		
		/************** VALIDA TIPO COBRO ***************************/
		if(objform.t.value == "b2ZfdGlwb3NfZGVfY29icm8=")
		{
			if(objform.descripcion.value != null)
			{		
				var v_nombre = objform.descripcion.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=29" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TIPO COBRO ***************************/
		
		/************** VALIDA TIPO PROVEEDOR ***************************/
		if(objform.t.value == "b2ZfdGlwb3NfZGVfcHJvdmVlZG9y")
		{
			if(objform.nombre_descripcion.value != null)
			{		
				var v_nombre = objform.nombre_descripcion.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=11" + "&nombre=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA TIPO PROVEEDOR ***************************/
		
		/************** VALIDA VALORACION PROVEEDOR ***************************/
		if(objform.t.value == "b2ZfdmFsb3JhY2lvbl9wcm92ZWVkb3I=")
		{
			if(objform.descripcion.value != null)
			{		
				var v_nombre = objform.descripcion.value;
				
				var nom = v_nombre.toUpperCase();
											
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=30" + "&descripcion=" + nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA VALORACION PROVEEDOR ***************************/
		
		/************** VALIDA GIRO ***************************/
		if(objform.t.value == "b2ZfZ2lybw==")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_categoria_giro = objform.id_categoria_giro.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=31" + "&nombre=" + nom + "&id_categoria_giro=" + v_id_categoria_giro);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA GIRO ***************************/
		
		/************** VALIDA CONOCISTE TEH BOOKS ***************************/
		if(objform.t.value == "b2ZfY29tb19jb25vY2lzdGVfdGhlX2Jvb2tz")
		{
			if(objform.descripcion.value != null)
			{		
				var v_nombre = objform.descripcion.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=32" + "&descripcion=" + nom );
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA CONOCISTE TEH BOOKS ***************************/
		
		/************** VALIDA CLASIFICACION DE GIROS ***************************/
		if(objform.t.value == "b2ZfY2F0ZWdvcmlhc19naXJv")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var nom = v_nombre.toUpperCase();		
								
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=33" + "&nombre=" + nom );
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA CLASIFICACION DE GIROS ***************************/
	}
	/***************************** TERMINA VALIDACIONES PARA NO INSERTAR REPETIDOS EN CATALOGOS ***********************/
	
	/***************/
	//tabla productos
	if(objform.t.value=="YW5kZXJwX3Byb2R1Y3Rvcw=="){
	// alert("Entro a guardar");
	  var arrIdTipoProdGrid = new Array();
	  var grid=document.getElementById('Body_detalleproducto');
	  var nfil=NumFilas(grid);
		
	  Trs=grid.getElementsByTagName('tr');
	  var numdatos=Trs.length;
	  var iteracion=0, numdatAct=0;
	
	 //valida si el producto ya existe 
	  if(objform.nombre.value != null){
	  
	   if(objform.id_producto.value != '')
	     var v_id_prod = objform.id_producto.value; //Cuando es edicion
	   else
	     var v_id_prod = 0; //cuando es nuevo
		  	  
	     var v_nombre = objform.nombre.value;
		 var v_nom = v_nombre.toUpperCase(); 
		 var v_tipo_prod = objform.id_producto_tipo_default.value;


   var aux = ajaxR("../ajax/validaProducto.php?accion=2&id_tipo_prod="+v_tipo_prod+"&name_prod="+v_nom+"&id_prod="+v_id_prod);
		 //alert(aux);
		 var ax = aux.split("|");
		 if(ax[0] == 'exito'){
		    alert(ax[1]);
           //falta limpiar grid 
			return false;
		 } 
	  }
	  
	
	  for(var i=0;i<numdatos;i++)
	{
		Tds=Trs[i].getElementsByTagName('td');
		for(var j=1;j<Tds.length;j++)
		{
			
			ax=Tds[j].valor?Tds[j].valor:Tds[j].getAttribute('valor');
			//alert(ax);
			if(j==5){
			   arrIdTipoProdGrid[arrIdTipoProdGrid.length] =ax; 
			}
			/*if(tipos[j-1] == 'eliminador' || tipos[j-1] == 'libre')
				break;
			else
			{
				ax=Tds[j].valor?Tds[j].valor:Tds[j].getAttribute('valor');
				aux="&dato"+j+"["+numdatAct+"]="+ax;
				ruta+=aux;
			}*/
		}
		
	  }
	  document.getElementById('tiposPresentacionGrid').value = arrIdTipoProdGrid.join(','); 
	// alert(document.getElementById('tiposPresentacionGrid').value);
	}//fin de tabla productos
	/************/
	
	/**VALIDACION de Pantalla Notas de Venta  
	El Folio no debe de repetirse en la misma sucursal
	*****/
	if(objform.t.value=="YW5kZXJwX25vdGFzX3ZlbnRh"){
	 //crear el id de notas de venta
      var accion_pant =  document.getElementById('make').value; 
	 
	if(accion_pant == 'insertar'){
	  var f = document.forma_datos;
	  var v_sucursal = document.getElementById('prefijo').value; //prefijo sucursal
	  var v_ruta  =  f.id_ruta.options[f.id_ruta.selectedIndex].text; //prefijo ruta
      var v_folio = document.getElementById('consecutivo').value; //Folio
	  var v_clave = v_sucursal+'-'+v_ruta+'-'+v_folio;  //se forma la clave
	  
	  if(v_sucursal == ''){
	    alert('Ocurrió un error en la sesión, Ingrese de nuevo al sistema.');
		return false;
	  }
	  if(v_ruta == ''){
	     alert("Debe Seleccionar una ruta valida.");
		 return false;
	  }
	  
	  
	  
	  /**
	   Valida si el folio de
	  *************/
	  var aux = ajaxR("../ajax/validaProducto.php?accion=1&id_nota_venta="+v_clave);
	 
	  var ax = aux.split("|");
	  if(ax[0] == 'exito'){
	    alert(ax[1]);
		return false;
	  }	

	//alert('clave '+v_sucursal+'-'+v_ruta+'-'+v_folio);
	  document.getElementById('id_nota_venta').value = v_clave;	  
	  
	  document.getElementById('claveNV').value = v_clave;	
	  //alert(document.getElementById('claveNV').value);
	  }//fin if accion pant
	}
	
	
	/*Excepcion para Notas  de Venta 
	****/
	if(objform.t.value=="YW5kZXJwX25vdGFzX3ZlbnRh"){
	   /*Obtenemos los valores para insertar en Cuentas por Cobrar*/
	
	   var f = document.forma_datos;
	   var v_cliente =  f.selcampo_7.value; 
	   var v_dirCliente =document.getElementById('id_direccion_entrega').value;
	   var v_idSucursal = f.campo_6.value;

       document.getElementById('v_cli').value =v_cliente; 
	   document.getElementById('v_dircli').value =v_dirCliente;
	   document.getElementById('v_suc').value =v_idSucursal;
	   
       //alert(v_cliente+'-'+v_dirCliente+'-'+v_idSucursal);
	 	
	  
	   /*
 id_control_cxc,
 id_control_nota_venta,
 id_nota_venta,
 id_compania,
 fecha_y_hora,
 id_moneda,
 id_sucursal,
 id_cliente,
 id_direccion_entrega,
 id_ruta,
 id_vendedor,
 id_tipo_pago,
 fecha_revision,
 fecha_vencimiento,
 id_control_factura,
 id_factura,
 total,
 observaciones,
 cancelada,
 fecha_cancelacion,
 hora_cancelacion,
 id_usuario_cancelacion,
 id_tipo_cuenta,
 saldada
	   */
	   quitaComasForma(f); 
	   
	}
	
	//execiones CATALGO
	var valRegresoCat=validaCatalogo(objform.t.value);
	
	if(valRegresoCat==0)
	{
		
		return false;
	}
	
	
	objform.submit();	
	//verifcamos el nombre de la tabla
	// si es una solcitud no puede ser modificada si tiene asignado un contrato
				
}
/*******************************************************/
function quitaComasForma(f)
{
	//alert(f.subtotal.value);
	f.subtotal.value=removeCommas(f.subtotal.value);
	f.total.value=removeCommas(f.total.value);
	return true;
}
/****************************************************/
function removeCommas(strValue)
{
	//objRegExp = /,/g;
	objRegExp = /\$|\(|[,]/g;
	//alert('pio'+strValue.replace(objRegExp,''));
	return strValue.replace(objRegExp,'');
}
/********************************************************/
//funcion 
function makeRequest(url,busca){

	http_request = false;
	if(window.XMLHttpRequest){ // mozilla, netscape, opera...
		http_request = new XMLHttpRequest();
		if(http_request.overrideMimeType){
			http_request.overrideMimeType('text/xml');
		}
	}else if(window.ActiveXObject){ // IE
		try{
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
			}
		}
	}
	if(!http_request){
		alert('Falla :( No es posible crear una instancia XMLHTTP');
		return false;
	}
	
	if(busca=='validaId')
		http_request.onreadystatechange = validaId;
	else if(busca=='cargaOpciones') 
		http_request.onreadystatechange = cargaOpciones;
	
	http_request.open('GET', url, true);
	http_request.send(null);
	
	
}

function trim(cadena)
{
	for(i=0; i<cadena.length; )
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}

	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	return cadena;
}

/*
//nuevas funcion
function validaLlave(campo,valor,tabla)
{
	//validamos que no este de alta ya el id {if $v neq '0' and $v neq '3' and  $v neq ''}
	var valv=document.forma_datos.elements["v"].value;
	//colocamos la varable para que no genere el submit
	document.forma_datos.elements['generaSubmit'].value='0';
	
	//alert(valv)
	if(valor!="" && valv != '0' && valv != '3' && valv != '1')
		makeRequest('../ajax/ajaxresponse.php?campo='+campo+'&id='+valor+'&tabla='+tabla+'&code=no','validaId');
}
*/

//datos de dependecia , relativo a cada tabla
function llenaDependencia(valor,aquienLlena,opcion)
{
	//realizamos el make 
	makeRequest('../ajax/obtenOpciones.php?valor='+valor+'&aql='+aquienLlena+'&opcion='+opcion,'cargaOpciones');
}

function llenaDependenciaIni()
{
	var valor=document.getElementById('id_familia_de_producto').value;
	llenaDependencia(valor,'subfamilia1','2');
	
}

function muestraPassword(){
//copia el password en la copia
    var f=document.forma_datos;
	if (f.t.value=='c3lzX3VzdWFyaW9z')
	{
		f.campo_13.value=f.campo_12.value;
		
	}
}

function ingresaFil(grid)
{
	var nfil=NumFilas(grid);
	if(nfil==0)
		return true;
}

function validaMatriz(obj)
{
	var f=document.forma_datos;
	if(obj.checked == true)
	{
		aux=ajaxR('../ajax/validaMatrizUnica.php?id_suc='+f.campo_0.value);
		if(aux != 'exito')
		{
			alert(aux);
			obj.checked=false;
			return false;
		}
	}
}

function rutaAnterior()
{

	var f=document.forma_datos;
	aux=ajaxR('../ajax/obtenUltimaRuta.php?id_suc='+f.campo_0.value);
	
	var arrResp=aux.split("|");
	if(arrResp[0] != 'exito')
	{
		//alert(arrResp[0]);
		return false;
		
	}
	else
	{
		//alert(arrResp[1]);
		objRuta = document.getElementById('id_ruta');
		for(var i=0;i<objRuta.length;i++){
			 if(objRuta.options[i].value == arrResp[1]){
			   objRuta.options[i].selected = true;
			 }
		 }
		
	}
}
	
function muestrafilasEntrada(opcion)
{
	if(opcion==1)
	{
		//Orden de compra
		$("#fila_catalogo_6").show();
		$("#fila_catalogo_7").hide();
		$("#fila_catalogo_8").hide();
			
	}
	else if(opcion==2)
	{
		$("#fila_catalogo_6").hide();
		$("#fila_catalogo_7").show();
		$("#fila_catalogo_8").hide();
	
	}
	else if(opcion==3)
	{
		$("#fila_catalogo_6").hide();
		$("#fila_catalogo_7").hide();
		$("#fila_catalogo_8").show();
	
	}
	else if(opcion==4)
	{
		$("#fila_catalogo_6").hide();
		$("#fila_catalogo_7").hide();
		$("#fila_catalogo_8").hide();
	}
}

function muestrafilasSalida(opcion)
{
	if(opcion==1)
	{
		//Orden de compra
		$("#fila_catalogo_6").show();
		$("#fila_catalogo_7").hide();
		$("#fila_catalogo_8").hide();
			
	}
	else if(opcion==2)
	{
		$("#fila_catalogo_6").hide();
		$("#fila_catalogo_7").show();
		$("#fila_catalogo_8").hide();
	
	}
	else if(opcion==3)
	{
		$("#fila_catalogo_6").hide();
		$("#fila_catalogo_7").hide();
		$("#fila_catalogo_8").show();
	
	}
	else if(opcion==4)
	{
		$("#fila_catalogo_6").hide();
		$("#fila_catalogo_7").hide();
		$("#fila_catalogo_8").hide();
	}
}

function muestraFilasVisiblesEntrada()
{
	//obtenemos el valor del tipo de movim	
	
	 muestrafilasEntrada(document.getElementById('id_tipo_entrada').value);
}
function muestraFilasVisiblesSalida()
{
	//obtenemos el valor del tipo de movim	
	
	muestrafilasSalida(document.getElementById('id_tipo_salida').value);
}


function muestraAlmacenes(valor,tipo)
{
	var id_suc=document.getElementById('id_sucursal').value;
	if(tipo=='entrada')
	{
		//de la sucursal seleccionada buscamos los almacenes relacionadados para realizar la entrada
		objCombo = document.getElementById('id_almacen_entrada');
		
	}
	else
	{
		//de la sucursal seleccionada buscamos los almacenes relacionadados para realizar la salida
		objCombo = document.getElementById('id_almacen_salida');
	}
	
	limpiaCombo(objCombo);
  // alert("doc "+objTipoDoc.value);
	var respuesta=ajaxR("../ajax/obtenAlmacenes.php?tipo="+tipo+"&id_suc="+id_suc+"&$idAlamacen=0");
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]>0)
	{
		var numDatos=parseInt(arrResp[1]);		
		for(var i=0;i<numDatos;i++)
		{
			var arrDatos=arrResp[i+2].split("~");			
			objCombo.options[i]=new Option(arrDatos[1], arrDatos[0]);
		}
	}
		
		
}

function muestraAlmacenesNoSele()
{
	var id_suc=document.getElementById('id_sucursal').value;
    var id_almacen=document.getElementById('id_almacen_salida').value;
	
	//de la sucursal seleccionada buscamos los almacenes relacionadados para realizar la salida
	objCombo = document.getElementById('id_almacen_entrada');
	
	limpiaCombo(objCombo);
  // alert("doc "+objTipoDoc.value);
	var respuesta=ajaxR("../ajax/obtenAlmacenes.php?tipo=0&id_suc="+id_suc+"&idAlamacen="+id_almacen);
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]>0)
	{
		var numDatos=parseInt(arrResp[1]);		
		for(var i=0;i<numDatos;i++)
		{
			var arrDatos=arrResp[i+2].split("~");			
			objCombo.options[i]=new Option(arrDatos[1], arrDatos[0]);
		}
	}
		
		
}

function colocaSucursalAuxiliar(valor)
{
	if(valor==9999)
	{
		//obtenemso el valopr del sucursal solicitante
		valor=document.getElementById('id_sucursal_solicita').value;
	}
	
	//por ajax modificamos los valores de la sesion
	var respuesta=ajaxR("../ajax/cambiaValorSesion.php?tipo=1&valor="+valor);
	
}


function cargaWait()
{
	var w=screen.width;
	var h=screen.height;
	var obj=document.getElementById('waitingplease');
	var im1=document.getElementById('imgW1');
	var im2=document.getElementById('imgW2');
	im2.width=screen.width;
	im2.height=screen.height;
	im1.style.left=(w-128)/2;
	im1.style.top=(h-128)/2-(h/8);
}	




{/literal}

{if $EvitaRefresh eq 'SI'}

alert('Se ha insertado el dato correctamente');
	location.href="encabezados.php?t={$t}&k={$atributos[0][15]}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";
	
{/if}	

{if $EvitaRefreshEdicion eq 'SI'} 
	alert('Se ha guardado el dato correctamente');
		
	if("{$t}" != "c3BhX2NsaWVudGVz"){literal}{
		location.href="encabezados.php?t={/literal}{$t}&k={$atributos[0][15]}{literal}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";
	}else{
		location.href = "../especiales/valoracionMedica.php?accion=0&soloVer=0&idCliente={/literal}{$atributos[0][15]}{literal}";
	}{/literal}
	
	/*alert('Se ha guardado el dato correctamente');
	location.href="encabezados.php?t={$t}&k={$atributos[0][15]}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";*/
	
{/if}	

{if $resTimbrado eq 'exito'}
	alert("El documento fue sellado y timbrado exitosamente.");	
{/if}

{if $t eq 'YW5kZXJwX25vdGFzX3ZlbnRh' && $op eq '1'}
	rutaAnterior();
{/if}





{if $t eq 'c3BhX25vdGFzX3NlcnZpY2lvcw=='}
{literal}
	window.onload = mostrarFacturacion(0);
	
	

	$("#id_cliente").change(function(){
		var idCliente = 0;
		setTimeout("idCliente = $('#hcampo_5').val();$('#H_detalleNotasServiciosCitas3').attr('datosdb', '../grid/getCombo.php?id=35&aux=' + idCliente);", 1000);
		//console.log(idCliente);		
	});
	
	$("#requiere_factura").change(function(){
		mostrarFacturacion($(this).val());
	});
	
	function mostrarFacturacion($valor){
		if($valor == 1){
			$("#fila_catalogo_13").show();
			$("#tabla_fila_catalogo_13").show();
		}else{
			$("#fila_catalogo_13").hide();
			$("#tabla_fila_catalogo_13").hide();
		}
	}
	
	$("#selcampo_5").attr("onkeydown", "teclaCombo('campo_5',event); cargarDatosFiscales(event)");
	$("#selcampo_5").live("change", function(){
		cargarDatosFiscales(""); 
	});
	
	function cargarDatosFiscales(evento){
		if((evento.keyCode==13) || evento == ""){
			//alert($('#selcampo_5').val());
			
			var resp = ajaxR("../ajax/getDatosAjax.php?opc=8&id_cliente=" + $('#selcampo_5').val());	
			datos = jQuery.parseJSON(resp);
			
			$("#rfc").val(datos[0].rfc);
			$("#calle_f").val(datos[0].calle_f);
			$("#numero_ext_f").val(datos[0].numero_ext_f);
			$("#numero_int_f").val(datos[0].numero_int_f);
			$("#colonia_f").val(datos[0].colonia_f);
			$("#id_ciudad_f").val(datos[0].id_ciudad_f);
			$("#del_mpo_f").val(datos[0].del_mpo_f);
			$("#cp_f").val(datos[0].cp_f);
		}
	}
{/literal}
{/if}




{if $t eq 'c3BhX25vdGFzX3Byb2R1Y3Rvcw=='}
{literal}
	window.onload = mostrarFacturacion(0);
	window.onload = mostrarVentaPublico(2);
	
	$("#requiere_factura").change(function(){
		mostrarFacturacion($(this).val());
	});
	
	function mostrarFacturacion($valor){
		if($valor == 1){
			$("#fila_catalogo_17").show();
			$("#tabla_fila_catalogo_17").show();
		}else{
			$("#fila_catalogo_17").hide();
			$("#tabla_fila_catalogo_17").hide();
		}
	}
	
	$("#selcampo_6").attr("onkeydown", "teclaCombo('campo_6',event); cargarDatosFiscales(event)");
	$("#selcampo_6").live("change", function(){
		cargarDatosFiscales(""); 
	});
	
	function cargarDatosFiscales(evento){
		if((evento.keyCode==13) || evento == ""){
			var resp = ajaxR("../ajax/getDatosAjax.php?opc=8&id_cliente=" + $('#selcampo_6').val());	
			datos = jQuery.parseJSON(resp);
			
			$("#rfc").val(datos[0].rfc);
			$("#calle_f").val(datos[0].calle_f);
			$("#numero_ext_f").val(datos[0].numero_ext_f);
			$("#numero_int_f").val(datos[0].numero_int_f);
			$("#colonia_f").val(datos[0].colonia_f);
			$("#id_ciudad_f").val(datos[0].id_ciudad_f);
			$("#del_mpo_f").val(datos[0].del_mpo_f);
			$("#cp_f").val(datos[0].cp_f);
		}
	}
	
	$("#venta_publico").change(function(){
		$("#rfc").val("");
		$("#calle_f").val("");
		$("#numero_ext_f").val("");
		$("#numero_int_f").val("");
		$("#colonia_f").val("");
		$("#id_ciudad_f").val("");
		$("#del_mpo_f").val("");
		$("#cp_f").val("");
		$("#id_cliente").val("");
		mostrarVentaPublico($(this).val());
	});
	
	function mostrarVentaPublico($valor){
		if($valor == 2){
			$("#fila_catalogo_6").show();
			$("#fila_catalogo_7").hide();
		}else{
			$("#fila_catalogo_6").hide();
			$("#fila_catalogo_7").show();
		}
	}
	
{/literal}
{/if}





{if $t eq 'c3BhX2FsbWFjZW5lc19lbnRyYWRhcw=='}
	muestraFilasVisiblesEntrada();
{/if}

{if $t eq 'c3BhX2FsbWFjZW5lc19lbnRyYWRhcw==' && $op eq '1'}
	muestraAlmacenes(0,'entrada');
{/if}

{if $t eq 'c3BhX2FsbWFjZW5lc19zYWxpZGFz'}
	muestraFilasVisiblesSalida();
{/if}

{if $t eq 'c3BhX2FsbWFjZW5lc19zYWxpZGFz' && $op eq '1'}
	muestraAlmacenes(0,'salida');
{/if}

{if $t eq 'c3BhX3BlZGlkb3Nfc3VjdXJzYWw='}
	colocaSucursalAuxiliar(9999);
{/if}

</script>


{include file="general/funciones_catalogos_pie.tpl"}
{if $hf neq 10}
	{include file="_footer.tpl" aktUser=$username}
{/if}    