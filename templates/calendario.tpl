<link href="../../inc/css/gridSW.css" rel="stylesheet" type="text/css">
{include file="_header.tpl" pagetitle="$contentheader"}

<link rel="stylesheet" type="text/css" href="{$rooturl}css/pro_dropdown_2.css" />
<link rel="stylesheet" type="text/css" href="{$rooturl}inc/css/estilos_1.css"   />
<link rel="stylesheet" type="text/css" href="{$rooturl}inc/css/gridSW.css"   />
<div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">Calendario</div>
 </div>

<div class="buscador" id="buscador">


<form action="#" name="forma_datos_frame" id="forma_datos_frame" method="post" >
<!-- <table class="NormalCell" width="70%" align="center">
  <tr>	
  	<td >
	<input type="hidden" name="op" value="">
	  <input type="hidden" name="doc" value="">
	<div align="center">
  	  <input name="r1" type="radio"  value="mes" {$checkedOp1}  >
  	  MES  	 
  	  
  	</div></td>
	<td><div align="center">
	  <input type="radio" name="r1" value="semana" {$checkedOp2} >
	  SEMANA 	</div></td>
	<td><div align="left">
	  <input type="radio" name="r1" value="dia" {$checkedOp3}>
	  DIA&nbsp;&nbsp;&nbsp;&nbsp;		
	  <input type="text" name="fecha" id="fecha" maxlength="11" size="11" value="{$fecha}"  class="" onFocus="calendario(this)"  >
	</div></td>
	  <td><div align="left"></div></td>
  </tr>	
  <tr>	
  	<td>LUGAR
  	  <select name="id_ubicacion" class="combo" id="id_ubicacion">
	    <option value="0">TODAS</option>
	    
					 {html_options values=$ubicaciones[0] output=$ubicaciones[1] selected=$id_ubicacion }
		
	  </select></td>
	<td>EVENTO
		<select name="id_evento" class="combo" id="id_evento">
	    <option value="0">TODAS</option>
	    
					 {html_options values=$eventos[0] output=$eventos[1] selected=$id_evento }
		
	  </select>
	</td>
	<td>&nbsp;</td>
	 <td> 	
	  
	     <div align="left">
	       <input type="button" name="buscar" value=" C O N S U L T A R " class="boton" onClick="buscaDatos(this.form)">
	      </div></td>
  </tr>	
  </table>
  
  <br> -->
<p>&nbsp;</p>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="NormalCell">
  <tr>

    <td width="14%" height="67" valign="middle" background="{$rooturl}imagenes/general/calendar-consultar-bg.jpg" bgcolor="#F6F9F9" >
        <input type="hidden" name="op" value="">
        <input type="hidden" name="doc" value="">
      <div align="center">
              <font color="#333333">
              <input name="r1" type="radio"  value="mes" {$checkedOp1}  >
            MES</font> </div></td>

    <td width="11%" valign="middle" background="{$rooturl}imagenes/general/calendar-consultar-bg.jpg" bgcolor="#F6F9F9">
      <div align="left">
        <font color="#333333">
        <input type="radio" name="r1" value="semana" {$checkedOp2} >
        SEMANA</font> </div></td>
    <td width="18%" valign="middle" background="{$rooturl}imagenes/general/calendar-consultar-bg.jpg" bgcolor="#F6F9F9"><div align="left">
      <font color="#333333">
      <input type="radio" name="r1" value="dia" {$checkedOp3}>
      DIA&nbsp;&nbsp;&nbsp;
      <input type="text" name="fecha" id="fecha" maxlength="11" size="11" value="{$fecha}"  class="" onFocus="calendario(this)"  >
      </font></div></td>
    <td width="17%" valign="middle" background="{$rooturl}imagenes/general/calendar-consultar-bg.jpg" bgcolor="#F6F9F9"><font color="#333333">LUGAR   
       <select name="id_ubicacion" class="combo" id="id_ubicacion">
	    <option value="0">TODOS</option>
	    
					 {html_options values=$ubicaciones[0] output=$ubicaciones[1] selected=$id_ubicacion }
		
	  </select></td>
    </font></td>
    <td width="24%" valign="middle" background="{$rooturl}imagenes/general/calendar-consultar-bg.jpg" bgcolor="#F6F9F9"><font color="#333333">EVENTO
        <select name="id_evento" class="combo" id="id_evento">
	    <option value="0">TODOS</option>
	    
					 {html_options values=$eventos[0] output=$eventos[1] selected=$id_evento }
		
	  </select>
    </font></td>
    <td width="16%" background="{$rooturl}imagenes/general/calendar-consultar-bg.jpg" bgcolor="#F6F9F9"><div align="left">
      <input type="button" name="buscar" value=" C O N S U L T A R " class="boton" onClick="buscaDatos(this.form)">
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>

<table width="95%" align="center">
  <tr>
    <td width="92%" align="left" valign="middle" class="calendar-mes">{$titcalendario}</td>
    <td width="4%" align="left" valign="middle"><a href="calendario.php?op={$op}&doc={$doc}&fecha={$fechainicial}&id_evento={$id_evento}&id_ubicacion={$id_ubicacion}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image15','','{$rooturl}imagenes/general/cal-flecha-reg-r.gif',1)"><img src="{$rooturl}imagenes/general/cal-flecha-reg.gif" alt="regresar" name="Image15" width="29" height="25" border="0"></a></td>
    <td width="4%" align="right" valign="middle"><a href="calendario.php?op={$op}&doc={$doc}&fecha={$fechafinal}&id_evento={$id_evento}&id_ubicacion={$id_ubicacion}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image16','','{$rooturl}imagenes/general/cal-flecha-sig-r.gif',1)"><img src="{$rooturl}imagenes/general/cal-flecha-sig.gif" alt="siguiente" name="Image16" width="29" height="25" border="0"></a></td>
  </tr>
</table>
<p>&nbsp;</p>
  
 
  
  {*para la seccion de mes*}
 {if $ver=='0'}
  <table  width="95%" class="tabla_Grid_RC"  border="1"  cellspacing="0" align="center">
		<tr>
			<td class="calendar-tabletit">SAB</td>
    		<td class="calendar-tabletit">DOM</td>
    		<td class="calendar-tabletit">LUN</td>
    		<td class="calendar-tabletit">MAR</td>
    		<td class="calendar-tabletit">MIER</td>
    		<td class="calendar-tabletit">JUE</td>
    		<td class="calendar-tabletit">VIER</td>
		</tr>
		
		{section loop=$arrTotal name=y start=0 max=$maxreg+1}
    		<tr class="NormalCell" height="10">
			
				{section loop=$arrTotal[y] name=x start=0}
				<td width="14%" class="textocalendario" valign="top">
					
					{$arrTotal[y][x][0][1]}
					 <br>
					 <br>
					{section loop=$arrTotal[y][x] name=w start=0}
						<a href='encabezados.php?t=c2lhc19ldmVudG9zX2FnZW5kYQ==&k={$arrTotal[y][x][w][0]}&op=2&v=0'>{$arrTotal[y][x][w][2]} <br>
						{$arrTotal[y][x][w][3]}<br>{$arrTotal[y][x][w][4]}<br>{$arrTotal[y][x][w][5]}</a>	
						
					    <br>
									  <br>
					{/section}			  </td>	
				{/section}			</tr>
		{/section}
  </table>
{/if}

 {if $ver=='1'}
  
<table  width="90%" class="tabla_Grid_RC"  border="1"  cellspacing="0" align="center">
		<tr>
			<td class="buttonHeader">SABADO</td>
			<td class="buttonHeader"><div align="center">DOMINGO</div></td>
			<td class="buttonHeader"><div align="center">LUNES</div></td>
			<td class="buttonHeader"><div align="center">MARTES</div></td>
			<td class="buttonHeader"><div align="center">MIERCOLES</div></td>
			<td class="buttonHeader"><div align="center">JUEVES</div></td>
			<td class="buttonHeader"><div align="center">VIERNES</div></td>
		</tr>
		
		{section loop=$arrTotal name=y start=0 }
    		<tr class="NormalCell" height="50" valign="top">
			
				{section loop=$arrTotal[y] name=x start=0}
				<td width="15%" class="textocalendario" >
					
					{$arrTotal[y][x][0][1]}
					 <br>
					 <br>
					 

					{section loop=$arrTotal[y][x] name=w start=0}
						<a href='inc/general/encabezados.php?t=c2lhc19ldmVudG9zX2FnZW5kYQ==&k={$arrTotal[y][x][w][0]}&op=2&v=0'>{$arrTotal[y][x][w][2]} <br>
						{$arrTotal[y][x][w][3]}<br>{$arrTotal[y][x][w][4]}<br>{$arrTotal[y][x][w][5]}</a>	
						
					    <br>
									  <br>
					{/section}			  </td>	
				{/section}			</tr>
		{/section}
  </table>
{/if}


{if $ver=='2'}
  <table  width="30%" class="tabla_Grid_RC"  border="1"  cellspacing="0" align="center">
		<tr>
			<td class="buttonHeader"><div align="center">{$nombreDia}</div></td>
			
		</tr>
		<tr class="NormalCell" height="50" valign="top">
		<td width="15%" class="textocalendario">
		
		{$arrTotal[0][1]}
					 <br>
					 <br>
		{section loop=$arrTotal name=y start=0 }
    					<a href='inc/general/encabezados.php?t=bmV3YV9zYWxhc19wcm9ncmFtYWNpb24&k={$arrTotal[y][x][w][0]}&op=2&v=0'>{$arrTotal[y][x][w][2]} <br>
						{$arrTotal[y][x][w][3]}<br>{$arrTotal[y][x][w][4]}<br>{$arrTotal[y][x][w][5]}</a>	
						
			    <br>
			    <br>
			  
		
		{/section}
		
		</td>	
		</tr>		
  </table>
{/if}
<p>&nbsp;</p>
<p>&nbsp;</p>

<table width="92%" align="center">
  <tr>
    <td width="48%" align="center" valign="middle"><a href="calendario.php?op={$op}&doc={$doc}&fecha={$fechainicial}&id_evento={$id_evento}&id_ubicacion={$id_ubicacion}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image15','','{$rooturl}imagenes/general/cal-flecha-reg-r.gif',1)"><img src="{$rooturl}imagenes/general/cal-flecha-reg.gif" alt="regresar" name="Image15" width="29" height="25" border="0"></a></td>
    <td width="52%" align="center" valign="middle"><a href="calendario.php?op={$op}&doc={$doc}&fecha={$fechafinal}&id_evento={$id_evento}&id_ubicacion={$id_ubicacion}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image16','','{$rooturl}imagenes/general/cal-flecha-sig-r.gif',1)"><img src="{$rooturl}imagenes/general/cal-flecha-sig.gif" alt="siguiente" name="Image16" width="29" height="25" border="0"></a></td>
  </tr>
</table>



</form>
</div>

{literal}
<script type="text/javascript" language="javascript">

function buscaDatos(objform)
{
	//<input type="hidden" name="op" value="">
	//<input type="hidden" name="doc" value="">
	
	//fecha
	if(objform.r1[0].checked)
		objform.op.value='0';
	else if(objform.r1[1].checked)
		objform.op.value='1';
	else if(objform.r1[2].checked)
		objform.op.value='2';

	
	objform.submit();
}




</script>
{/literal}

{include file="_footer.tpl" aktUser=$username}