{include file="_header.tpl" pagetitle="$contentheader"}   
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">
 <br>
<h1>Generaci&oacute;n de  Facturar</h1> </div>

  
 
<table border="0" width="90%" >
 <form name="forma1" method="post" action="preSurtido.php">
<tr>
<input type="hidden" name="accion" id="accion" value="{$accion}" />
<td colspan="5" class="campo_small" ><br> 	 Seleccione los criterios que desee especificar y de clic al bot&oacute;n 'Buscar '.<br><br>
</td>
</tr>

</tr>   
   <tr class='nom_campo'>
   
    <td >Tipos de Facturas </td>
    <td><select name="id_sucursal" class="campos_req" id="id_sucursal">
      <option value="0" selected="selected"> - Seleccione tipo de factura - </option>
       <option value="1" selected="selected"> Comisiones</option>
        <option value="2" > Bonos  y Complementos</option>

    
    </select></td>
</tr>






<tr class='nom_campo'>
  <td>Fecha Activaci&oacute;n</td>
  <td align="center"><input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);"/>
    al
    <input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);"/></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  
</tr>
<tr class='nom_campo'>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input name="btnBuscar" type="button" class="boton" value="Buscar      &raquo;" onClick="buscar();" /></td>

</tr>
</form>

</table> 


<form name="forma2" method="post" action="preSurtido.php">
<input type="hidden" name="accion_forma2" id="accion_forma2" value="{$accion}" />

<div >
  <table >
    <tr>
      <td ><input type="hidden" name="accion" id="accion" value="actualizar" />
          <input type="hidden" name="id_orden_servicio" id="id_orden_servicio" value="" />

          <input type="hidden" name="realiza" id="realiza" value="realiza" />
                          </th>

      <td class="">Folio</td>
      <td class="">Fecha de Activaci&oacute;n</td>
      <td class="">Promoci&oacute;n</td>
     <td class="">Paquete</th>
      <td class="">Contrarecibo</td>
      <td class="">Fecha de ContraRecibo</td>

      <td class="">$ Comisi&oacute;n</td>
      <td class="">$ IVA</td>
      <td class="">$ Total</td>
    </tr>
    
  </table>
</div>

</form>


<br /><br /><br /><br />

{include file="_footer.tpl" aktUser=$username}
