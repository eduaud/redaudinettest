<?php
  //print_r($_REQUEST);
?>
<table border="0" height="170" width="190">
   <tr>
      <td colspan="3">
           Ingrese su error :
      </td>
   </tr>
   <tr>
      <td colspan="3" align="center">
           <textarea id="mensaje_error"></textarea>
      </td>
   </tr>
   <tr>
      <td>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      <td>
         &nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      <td>
         <input type="button" value="Agregar error" onclick="agregarError(<?php echo $_REQUEST['fila'];?>);">
      </td>
   </tr>
</table>   