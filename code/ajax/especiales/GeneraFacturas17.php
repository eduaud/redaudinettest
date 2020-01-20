<?php
/*Genera Facturas de las Notas de Ventas Seleccionadas
28 de Diciembre 2011
***/
	php_track_vars;

	/*Conseguimos los datos del post
	
		tabla -> Nombre de la tabla que se esta accediendo
		id -> id del campo a utilizar
		accion -> accion a realizar, los valores pueden ser: [1 => Modificar, 2 => Ver, 3 => Eliminar o Cancelar]
	*/
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../../conect.php");	
//variable de entorno para tomar horario de mexico	
putenv ('TZ=America/Mexico_City'); 
mktime(0,0,0,1,1,1970);	
	
if($opcion == 1){
/*Genera una Factura por Una nota de Venta Seleccionada
****/

  if($id_nv != "") $notasVenta = explode("@@",$id_nv);
  if($id_cli != "") $clientes = explode("@@",$id_cli); 

  $IDSucursal = $_SESSION["USR"]->sucursalid;
  $NVAsociadas = implode(",",$notasVenta);
  $cadenaIdFacturas =""; 
  $strCli_ReqFact="";
  $strCli_No_ReqFact="";
  $strId_nv ="";
  $strId_nv_no="";
  //echo $notasVenta[0]."-".$notasVenta[1]."-".count($notasVenta)."\n";
  
  
  //validamos a cliente
  for($k=0; $k<count($notasVenta); $k++){
      
	  $sqlCli = "SELECT a.id_cliente,b.require_factura 
FROM anderp_notas_venta a 
LEFT JOIN anderp_clientes b ON b.id_cliente = a.id_cliente 
WHERE a.id_control_nota_venta=".$notasVenta[$k];

	  $resultado = mysql_query(utf8_decode($sqlCli)) or die("Error en:\n$sqlCli\n\nDescripcion:\n".mysql_error());
      $num = mysql_num_rows($resultado);
	
	if($num >0){
	  
	   $row=mysql_fetch_row($resultado);
	  
	    //echo "Numero Rows ".$num."- requiere_fact:".$row[1];
	  if($row[1] == 1){ //Cliente Requiere Factura
	      if($strCli_ReqFact == ''){
			 $strCli_ReqFact .= $row[0];
		  }
		  else{
			 $strCli_ReqFact .= "@@".$row[0];
		  }
		  if($strId_nv ==''){
		     $strId_nv .= $notasVenta[$k];    
		  }
		  else{
		    $strId_nv .= "@@".$notasVenta[$k];  
		  }
		
		  
	  }
	  else{
		  if($row[1] == 0){//NO requiere factura
			 if($strCli_No_ReqFact ==''){
				 $strCli_No_ReqFact .= $row[0];
			  }
			  else{
				 $strCli_No_ReqFact .= "@@".$row[0];
			  }
			  
			 if($strId_nv_no ==''){
		        $strId_nv_no .= $notasVenta[$k];    
		     }
		     else{
		        $strId_nv_no .= "@@".$notasVenta[$k];  
		     }
		  
		  }//fin if no requiere fact
	 }//fin else
	 
	}//fin num mayor a cero
	
  }//fin for k
    
	
	//variables para regreso
	$cadena1='';
	$cadena2='';
  

  //llamamos proceso a generar factura una por una de Cliente que  Requiere Factura
  if(strlen($strCli_ReqFact)>0){
    
	  $v_cad = GeneraFacturas_ClienteRFC($strId_nv,$strCli_ReqFact);
	  
	  if($v_cad != "" && strlen($v_cad)>0){
	      $getDatos = explode("|",$v_cad);
		  if($getDatos[0] == 'exito'){
		     $cadena1 = $getDatos[2];
		  }
	  }
	  
  }
  
  //llamamos a generar Facturas conCliente Generico ya que no requiere factura este cli
    if(strlen($strCli_No_ReqFact)>0){

	  $v_cad2 = GeneraFactura_ClienteGeneral($strId_nv_no,$strCli_No_ReqFact);
	  
	  if($v_cad2 != "" && strlen($v_cad2)>0){
	      $getDatos = explode("|",$v_cad2);
		  if($getDatos[0] == 'exito'){
		     $cadena2 = $getDatos[2];
		  }
	  }
    }
	
	//armamos cadena de Regreso 
	if($cadena2 != "" && strlen($cadena2)>0){
	       $cara = "@@";
    }
	
	
	echo "exito|".$cadena1.$cadena2."|".$cadena1.$cara.$cadena2;
  
  
  	 
  
}//fin de opcion 1

/*********************************************************************************************************************/

function GeneraFactura_ClienteGeneral($id_nv,$id_cli){
/*Genera una Sola Factura con las Notas  de Venta donde los clientes No Requieren Factura y seleccionaron una factura por nota de venta*/

  if($id_nv != "") $notasVenta = explode("@@",$id_nv);
  if($id_cli != "") $cliente = explode("@@",$id_cli); 

  $IDSucursal = $_SESSION["USR"]->sucursalid;
  $NVAsociadas = implode(",",$notasVenta);
  $cadenaIdFacturas =""; 
  
  //valores de año y numero de aprobacion
  $sqlExt = "SELECT 
b.id_control_folio,
b.anio_aprobacion,
b.numero_aprobacion
FROM anderp_folios a
JOIN anderp_folios_historico b ON a.id_control_folio = b.id_control_folio AND b.activo=1
WHERE a.activo=1 AND a.id_sucursal =".$IDSucursal;
   	$resExt=mysql_query($sqlExt) or die("Error en:\n$sqlExt\n\nDescripcion:".mysql_error());
	$numExt=mysql_num_rows($resExt);//numero de filas
    if($numExt > 0){
	   $rowExt=mysql_fetch_array($resExt);
	   $anio_aprobacion =  $rowExt[1];
	   $no_aprobacion = $rowExt[2];	
	}
	
   //insertamos el registro a facturas con el ID_Cliente 1 que es el cliente mostrador
	//Encabezado de Factura
	 $sql = "
SELECT 
a.id_compania,
a.id_sucursal,
a.id_moneda,
a.id_direccion_entrega,
a.id_ruta,
a.id_vendedor,
a.id_tipo_pago,
a.fecha_revision,
a.fecha_vencimiento,
a.porcentaje_descuento,
a.cancelada,
a.fecha_cancelacion,
a.hora_cancelacion,
a.id_usuario_cancelacion,
SUM(a.subtotal) as 'subtotal',
SUM(a.descuento) as 'descuento',
SUM(a.total) as 'total'
FROM anderp_notas_venta a 
WHERE a.id_sucursal=".$IDSucursal." AND a.id_cliente=".$cliente[0]." AND a.id_control_nota_venta IN(".$NVAsociadas.")
GROUP BY a.id_cliente";    


	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$num=mysql_num_rows($res);//numero de filas
	$col = mysql_num_fields($res);
	
	$strValores = '';
	$strCamposNombre = '';
	$strInsert ='';
	if($num >0){
	
         //obtenemos cadena de campos para insertar encabezado de facturas
         $strCamposNombre = MakeCadenaCampos($col,$res);  
        
         //obtenemos valores de la consulta 
	     while($row = mysql_fetch_row($res)){
		   	
           $strValores = MakeCadenaValores($col,$res,$row);
		 
		 }//fin while
		 mysql_free_result($res);
	   
	
	
	   
	   //generamos insert para Encabezado de Facturas
 
	   $horaActMexico = date("Y-m-d H:i:s"); //enves de NOW() colocamos la hora de mexico y no del servidor.
	   
	   $strInsert = "INSERT INTO anderp_facturas (id_control_factura,".$strCamposNombre.",fecha_y_hora,activo,notas_venta_asociadas,id_cliente,anio_aprobacion,numero_aprobacion,forma_pago_sat,no_cuenta) VALUES (NULL,".$strValores.",'".$horaActMexico."',1,'".$NVAsociadas."',1,'".$anio_aprobacion ."','".$no_aprobacion."',1,'NO IDENTIFICADO')";
	   //echo $strInsert."\n";
	   
	   mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );
	   
	   	//recibo el último id
   	$id_control_factura = mysql_insert_id(); 
   	//echo $id_control_factura."\n"; 
	
	/****************************************************************************************************/
	//Detalle Factura parcial
for($i=0; $i<count($notasVenta); $i++){	
	 $sqlDeta = "
SELECT 
a.id_control_nota_venta,
a.id_control_nota_venta_detalle,
a.id_producto,
a.id_producto_tipo,
a.id_producto_presentacion,
a.cantidad,
a.kilos_brutos,
a.tara_x_caja,
a.kilos_netos,
a.kilos_promedio,
a.valor_unitario,
a.valor,
a.valor_minimo,
a.descuento,
a.iva_monto,
a.iva_tasa,
a.ieps_monto,
a.ieps_tasa,
a.retencion_monto,
a.retencion_tasa,
a.importe,
a.id_unidad_venta
FROM anderp_notas_venta_detalles a
WHERE a.id_control_nota_venta=".$notasVenta[$i];    

//echo $sqlDeta;

	$result=mysql_query($sqlDeta) or die("Error en:\n$sqlDeta\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$numd = mysql_num_rows($result);//numero de filas
	$cold = mysql_num_fields($result);
	
	//echo "NumDeta ".$numd."\n ColDeta ".$cold."\n"; 
	if($numd >0){
	
	     $strCamposNombreDeta = '';
		 //Obtenemos cadena de campos a insertar en Facturas detalle parcial
         $strCamposNombreDeta = MakeCadenaCampos($cold,$result); 
		    
		 //obtenemos valores de la consulta
	     while($row = mysql_fetch_array($result)){
		 	$strValoresDeta = '';
	        $strInsertDeta ='';
		     
			$strValoresDeta = MakeCadenaValores($cold,$result,$row);
		  
			
			  $strInsertDeta = "INSERT INTO anderp_facturas_detalles_parcial (id_control_factura_detalle_parcial,id_control_factura,".$strCamposNombreDeta.",activo) VALUES (NULL,".$id_control_factura.",".$strValoresDeta.",1)";
	         //echo $strInsertDeta."\n";
		
		 mysql_query($strInsertDeta) or rollback('strInsertDeta',mysql_error(),mysql_errno(),$strInsertDeta );
			 
		 }//fin while
		 mysql_free_result($result);
     }//fin if numd
	 
 }//fin for i	 		
		
   /**************************************************************************************************/		 
	$new_precio_unitario = array();
	//generamos los datos para facturas detalle Agrupando los productos de cada nota de Venta por:
	 //id_producto,tipo_producto y presentacion de producto
//calculamos el precio unitario ponderado
	 $sqlDetaFact = "SELECT 
id_control_factura,
id_producto,
id_producto_tipo,
id_producto_presentacion,
SUM(IFNULL(cantidad,0)) as 'cantidad',
SUM(kilos_brutos) as kilos_brutos,
SUM(tara_x_caja) as tara_x_caja,
SUM(kilos_netos) as kilos_netos,
SUM(kilos_promedio) as kilos_promedio,
AVG(valor_unitario) as valor_unitario,
SUM(valor) as valor,
AVG(valor_minimo) as valor_minimo,
AVG(valor_maximo) as valor_maximo,
SUM(descuento) as descuento,
SUM(iva_monto) as iva_monto,
SUM(iva_tasa) as iva_tasa,
SUM(ieps_monto) as ieps_monto,
SUM(ieps_tasa) as ieps_tasa,
retencion_monto,
retencion_tasa,
SUM(importe) as importe,
activo,
id_unidad_venta
FROM anderp_facturas_detalles_parcial
WHERE id_control_factura=".$id_control_factura."
GROUP BY id_control_factura,id_producto,id_producto_tipo,id_producto_presentacion"; 

 //echo sqlDetaFact."\n";
 
$resultado1=mysql_query($sqlDetaFact) or die("Error en:\n$sqlDetaFact\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$filas1 = mysql_num_rows($resultado1);//numero de filas
	$cols1 = mysql_num_fields($resultado1);
    //$rowt = mysql_fetch_assoc($resultado1);
    //extract($rowt);
   
   //calculo de precio ponderado
    if($filas1>0){
   //calculo de precio ponderado
     while($rowt = mysql_fetch_assoc($resultado1)){
	 extract($rowt);
	if($valor > 0){
	  if($id_unidad_venta == 1){  //KG
	      	   $new_precio_unitario[count($new_precio_unitario)] = number_format(($importe/$kilos_netos),4);  
			   
			   
	  }

	  if($id_unidad_venta == 2){  //PIEZAS
	       if($cantidad > 0){
	      	   $new_precio_unitario[count($new_precio_unitario)]  = number_format(($importe/$cantidad),4); 
		   }
		   else{
		       $new_precio_unitario[count($new_precio_unitario)]   = number_format($valor,4);
		   }   
	  }
	   //echo "Precio Ponderado (".$importe."/".$kilos_netos.") = ".$new_precio_unitario;
	}
	}//fin while
   }//fin if filas1
   ///////////////////////////
//////Guarda Factura detalle 

	 $sqlDetaFactGbl = "SELECT 
id_control_factura,
id_producto,
id_producto_tipo,
id_producto_presentacion,
SUM(IFNULL(cantidad,0)) as 'cantidad',
SUM(kilos_brutos) as kilos_brutos,
SUM(tara_x_caja) as tara_x_caja,
SUM(kilos_netos) as kilos_netos,
SUM(kilos_promedio) as kilos_promedio,
AVG(valor_unitario) as valor_unitario,
AVG(valor_minimo) as valor_minimo,
AVG(valor_maximo) as valor_maximo,
SUM(descuento) as descuento,
SUM(iva_monto) as iva_monto,
SUM(iva_tasa) as iva_tasa,
SUM(ieps_monto) as ieps_monto,
SUM(ieps_tasa) as ieps_tasa,
retencion_monto,
retencion_tasa,
SUM(importe) as importe,
activo,
id_unidad_venta
FROM anderp_facturas_detalles_parcial
WHERE id_control_factura=".$id_control_factura."
GROUP BY id_control_factura,id_producto,id_producto_tipo,id_producto_presentacion"; 
 
 //echo $sqlDetaFact."\n";
 
	$resultado=mysql_query($sqlDetaFactGbl) or die("Error en:\n$sqlDetaFactGbl\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$filas = mysql_num_rows($resultado);//numero de filas
	$cols = mysql_num_fields($resultado);

	 if($filas >0){
	     $strCamposNombreDeta = '';
	     //Obtiene cadena de campos a insertar en facturas detalle 
		 $strCamposNombreDeta = MakeCadenaCampos($cols,$resultado); 
		 $cont=0;
		 //obtenemos valores de la consulta 
		 while($row = mysql_fetch_array($resultado)){
		 	$strValoresDeta = '';
	        $strInsertDeta ='';
		  
			$strValoresDeta = MakeCadenaValores($cols,$resultado,$row);
			
   $strInsertDetaGbl = "INSERT INTO anderp_facturas_detalles (id_control_factura_detalle,".$strCamposNombreDeta.",valor) VALUES (NULL,".$strValoresDeta.",".$new_precio_unitario[$cont].")";
	        //echo $strInsertDetaGbl."\n";
			
			mysql_query($strInsertDetaGbl) or rollback('strInsertDetaGbl',mysql_error(),mysql_errno(),$strInsertDetaGbl );
			 $cont = $cont + 1;
		 }//fin while
		 mysql_free_result($resultado);
	 }//fin if filas
	 
	 
  }//fin if num
  

  $sqlUpdateNV = "UPDATE anderp_notas_venta SET id_control_factura=".$id_control_factura." WHERE id_control_nota_venta IN(".$NVAsociadas.")";
  
   // echo $sqlUpdateNV."\n";
    mysql_query($sqlUpdateNV) or rollback('sqlUpdateNV',mysql_error(),mysql_errno(),$sqlUpdateNV );
	
		 //actualizamos CxC
  $sqlUpdateCxC = "UPDATE anderp_cuentas_por_cobrar SET id_control_factura=".$id_control_factura." WHERE id_control_nota_venta IN(".$NVAsociadas.")";
     //echo $sqlUpdateCxC."\n";
     mysql_query($sqlUpdateCxC) or rollback('sqlUpdateCxC',mysql_error(),mysql_errno(),$sqlUpdateCxC );
	 
	 
	
	 mysql_query("COMMIT");
	 
	
	   if($cadenaIdFacturas == ""){
	     $cadenaIdFacturas = $id_control_factura;
	  }
	  else{
	    $cadenaIdFacturas = $cadenaIdFacturas."@@".$id_control_factura;
	  }
	  
	  
	 //Llamo al sellado de la factura
	// require("generaDocs.php");
	 
	 
	 
	 return "exito|$id_control_factura|$cadenaIdFacturas";
	 
	

}//fin funcion

/********************************************************************************************************************/
function GeneraFacturas_ClienteRFC($id_nv,$id_cli){

  if($id_nv != "") $notasVenta = explode("@@",$id_nv);
  if($id_cli != "") $clientes = explode("@@",$id_cli); 

  $IDSucursal = $_SESSION["USR"]->sucursalid;
  //$NVAsociadas = implode(",",$notasVenta);
  $cadenaIdFacturas =""; 
  $forma_pago_sat = "";
  $no_cuenta = ""; 	
	   
    //valores de año y numero de aprobacion
  $sqlExt = "SELECT 
b.id_control_folio,
b.anio_aprobacion,
b.numero_aprobacion
FROM anderp_folios a
JOIN anderp_folios_historico b ON a.id_control_folio = b.id_control_folio AND b.activo=1
WHERE a.activo=1 AND a.id_sucursal =".$IDSucursal;
   	$resExt=mysql_query($sqlExt) or die("Error en:\n$sqlExt\n\nDescripcion:".mysql_error());
	$numExt=mysql_num_rows($resExt);//numero de filas
    if($numExt > 0){
	   $rowExt=mysql_fetch_array($resExt);
	   $anio_aprobacion =  $rowExt[1];
	   $no_aprobacion = $rowExt[2];	
	}
	
	
  
for($i=0; $i < count($notasVenta); $i++){

	//datos de fiscales de cliente
	$sqlFiscal = "SELECT forma_pago_sat,no_cuenta  FROM anderp_clientes WHERE id_cliente = ".$clientes[$i];
	$resFiscal=mysql_query($sqlFiscal) or die("Error en:\n$sqlFiscal\n\nDescripcion:".mysql_error());
	$numFiscal=mysql_num_rows($resFiscal);//numero de filas
    if($numFiscal > 0){
	   $rowFiscal=mysql_fetch_array($resFiscal);
	   $forma_pago_sat = $rowFiscal[0];
	   $no_cuenta = $rowFiscal[1]; 	   
	}
	
   
	//Encabezado de Factura
	 $sql = "
SELECT 
a.prefijo,
a.consecutivo,
a.id_compania,
a.id_sucursal,
a.id_moneda,
a.id_cliente,
a.id_direccion_entrega,
a.id_ruta,
a.id_vendedor,
a.id_tipo_pago,
a.fecha_revision,
a.fecha_vencimiento,
a.porcentaje_descuento,
a.subtotal,
a.descuento,
a.iva,
a.ieps,
a.total,
a.observaciones,
a.cancelada,
a.fecha_cancelacion,
a.hora_cancelacion,
a.id_usuario_cancelacion
FROM anderp_notas_venta a 
WHERE a.id_sucursal=".$IDSucursal." AND a.id_control_nota_venta=".$notasVenta[$i];    


	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$num=mysql_num_rows($res);//numero de filas
	$col = mysql_num_fields($res);
	
	$strValores = '';
	$strCamposNombre = '';
	$strInsert ='';
	if($num >0){
	
         //obtenemos cadena de campos para insertar encabezado de facturas
         $strCamposNombre = MakeCadenaCampos($col,$res);  
        
         //obtenemos valores de la consulta 
	     while($row = mysql_fetch_row($res)){
		   	
           $strValores = MakeCadenaValores($col,$res,$row);
		 
		 }//fin while
		 mysql_free_result($res);
	   
	
	
	   //generamos insert para Encabezado de Facturas
	     $horaActMexico = date("Y-m-d H:i:s"); //enves de NOW() colocamos la hora de mexico y no del servidor.
	   $strInsert = "INSERT INTO anderp_facturas (id_control_factura,".$strCamposNombre.",fecha_y_hora,activo,notas_venta_asociadas,anio_aprobacion,numero_aprobacion,forma_pago_sat,no_cuenta) VALUES (NULL,".$strValores.",'".$horaActMexico."',1,".$notasVenta[$i].",'".$anio_aprobacion."','".$no_aprobacion."',".$forma_pago_sat.",'".$no_cuenta."')";
	   //echo $strInsert."\n";
	   
	  mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );
	   
	   	//recibo el último id
   	$id_control_factura = mysql_insert_id(); 
   	//echo $id_control_factura."\n"; 
	   
/**************************************************************************************************************/
	//Detalle Factura Parcial
	 $sqlDeta = "
SELECT 
a.id_control_nota_venta,
a.id_control_nota_venta_detalle,
a.id_producto,
a.id_producto_tipo,
a.id_producto_presentacion,
a.descripcion2,
a.cantidad,
a.kilos_brutos,
a.tara_x_caja,
a.kilos_netos,
a.kilos_promedio,
a.valor_unitario,
a.valor,
a.valor_minimo,
a.descuento,
a.iva_monto,
a.iva_tasa,
a.ieps_monto,
a.ieps_tasa,
a.retencion_monto,
a.retencion_tasa,
a.importe,
a.id_unidad_venta
FROM anderp_notas_venta_detalles a
WHERE a.id_control_nota_venta=".$notasVenta[$i];    

//echo $sqlDeta;

	$result=mysql_query($sqlDeta) or die("Error en:\n$sqlDeta\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$numd = mysql_num_rows($result);//numero de filas
	$cold = mysql_num_fields($result);
	
	//echo "NumDeta ".$numd."\n ColDeta ".$cold."\n"; 
	if($numd >0){
	
	     $strCamposNombreDeta = '';
		 //Obtenemos cadena de campos a insertar en Facturas detalle parcial
         $strCamposNombreDeta = MakeCadenaCampos($cold,$result); 
		    
		 //obtenemos valores de la consulta
	     while($row = mysql_fetch_array($result)){
		 	$strValoresDeta = '';
	        $strInsertDeta ='';
		     
			$strValoresDeta = MakeCadenaValores($cold,$result,$row);
		  
			
			  $strInsertDeta = "INSERT INTO anderp_facturas_detalles_parcial (id_control_factura_detalle_parcial,id_control_factura,".$strCamposNombreDeta.",activo) VALUES (NULL,".$id_control_factura.",".$strValoresDeta.",1)";
	         //echo $strInsertDeta."\n";
		
		 mysql_query($strInsertDeta) or rollback('strInsertDeta',mysql_error(),mysql_errno(),$strInsertDeta );
			 
		 }//fin while
		 mysql_free_result($result);
	   
	  
	   
/*********************************************************************************************************************/
	 //generamos los datos para facturas detalle Agrupando los productos por:
	 //id_producto,tipo_producto y presentacion de producto
$new_precio_unitario=array();
	 $sqlDetaFact = "SELECT 
id_control_factura,
id_producto,
id_producto_tipo,
id_producto_presentacion,
SUM(IFNULL(cantidad,0)) as 'cantidad',
SUM(kilos_brutos) as kilos_brutos,
SUM(tara_x_caja) as tara_x_caja,
SUM(kilos_netos) as kilos_netos,
SUM(kilos_promedio) as kilos_promedio,
AVG(valor_unitario) as valor_unitario,
SUM(valor) as valor,
SUM(importe) as importe,
id_unidad_venta
FROM anderp_facturas_detalles_parcial
WHERE id_control_factura=".$id_control_factura."
GROUP BY id_control_factura,id_producto,id_producto_tipo,id_producto_presentacion"; 
 
 //echo sqlDetaFact."\n";
 
	$resultado1=mysql_query($sqlDetaFact) or die("Error en:\n$sqlDetaFact\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$filas1 = mysql_num_rows($resultado1);//numero de filas
	$cols1 = mysql_num_fields($resultado1);
    
    
   
   if($filas1>0){
   //calculo de precio ponderado
     while($rowt = mysql_fetch_assoc($resultado1)){
	 extract($rowt);
	if($valor > 0){
	  if($id_unidad_venta == 1){  //KG
	      	   $new_precio_unitario[count($new_precio_unitario)] = number_format(($importe/$kilos_netos),4);  
			   
			   
	  }

	  if($id_unidad_venta == 2){  //PIEZAS
	       if($cantidad > 0){
	      	   $new_precio_unitario[count($new_precio_unitario)]  = number_format(($importe/$cantidad),4); 
		   }
		   else{
		       $new_precio_unitario[count($new_precio_unitario)]   = number_format($valor,4);
		   }   
	  }
	   //echo "Precio Ponderado (".$importe."/".$kilos_netos.") = ".$new_precio_unitario;
	}
	}//fin while
   }//fin if filas1
   ///////////////////////////
    $sqlDetaFactGbl = "SELECT 
id_control_factura,
id_producto,
id_producto_tipo,
id_producto_presentacion,
SUM(IFNULL(cantidad,0)) as 'cantidad',
SUM(kilos_brutos) as kilos_brutos,
SUM(tara_x_caja) as tara_x_caja,
SUM(kilos_netos) as kilos_netos,
SUM(kilos_promedio) as kilos_promedio,
AVG(valor_unitario) as valor_unitario,
AVG(valor_minimo) as valor_minimo,
AVG(valor_maximo) as valor_maximo,
SUM(descuento) as descuento,
SUM(iva_monto) as iva_monto,
SUM(iva_tasa) as iva_tasa,
SUM(ieps_monto) as ieps_monto,
SUM(ieps_tasa) as ieps_tasa,
retencion_monto,
retencion_tasa,
SUM(importe) as importe,
activo,
id_control_nota_venta,
id_unidad_venta
FROM anderp_facturas_detalles_parcial
WHERE id_control_factura=".$id_control_factura."
GROUP BY id_control_factura,id_producto,id_producto_tipo,id_producto_presentacion"; 
   
$resultado = mysql_query($sqlDetaFactGbl) or die("Error en:\n$sqlDetaFactGbl\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$filas = mysql_num_rows($resultado);//numero de filas
	$cols = mysql_num_fields($resultado);
	
	 if($filas >0){
	     $strCamposNombreDeta = '';
	     //Obtiene cadena de campos a insertar en facturas detalle 
		 $strCamposNombreDeta = MakeCadenaCampos($cols,$resultado); 
		 $cont=0;
		 //obtenemos valores de la consulta 
		 while($row = mysql_fetch_array($resultado)){
		    
		 	$strValoresDeta = '';
	        $strInsertDeta ='';
		  
			$strValoresDeta = MakeCadenaValores($cols,$resultado,$row);
			$new_precio_unitario[$cont]=str_replace(',','',  $new_precio_unitario[$cont]);
   $strInsertDetaGbl = "INSERT INTO anderp_facturas_detalles (id_control_factura_detalle,".$strCamposNombreDeta.",valor) VALUES (NULL,".$strValoresDeta.",".$new_precio_unitario[$cont].")";
	  // echo $strInsertDetaGbl."\n\n";
			
		mysql_query($strInsertDetaGbl) or rollback('strInsertDetaGbl',mysql_error(),mysql_errno(),$strInsertDetaGbl );
			$cont=$cont + 1; 
		 }//fin while
		 mysql_free_result($resultado);
	   
		
	  if($cadenaIdFacturas == ""){
	     $cadenaIdFacturas = $id_control_factura;
	  }
	  else{
	    $cadenaIdFacturas = $cadenaIdFacturas."@@".$id_control_factura;
	  }
	  
	  
	    $sqlUpdateNV = "UPDATE anderp_notas_venta SET id_control_factura=".$id_control_factura." WHERE id_control_nota_venta =".$notasVenta[$i];
  
     //echo $sqlUpdateNV."\n";
     mysql_query($sqlUpdateNV) or rollback('sqlUpdateNV',mysql_error(),mysql_errno(),$sqlUpdateNV );
	 
	 //actualizamos CxC
	$sqlUpdateCxC = "UPDATE anderp_cuentas_por_cobrar SET id_control_factura=".$id_control_factura." WHERE id_control_nota_venta =".$notasVenta[$i];
     //echo $sqlUpdateCxC."\n";
     mysql_query($sqlUpdateCxC) or rollback('sqlUpdateCxC',mysql_error(),mysql_errno(),$sqlUpdateCxC );
  
  	 mysql_query("COMMIT");
	 
	 
	  }//fin if num detalle facturas
	
	
	}//fin if numd detalle parcial

	}//fin if num encabezado
	  
  }//fin for i
  
  
 	 
	 
	 	 //Llamo al sellado de la factura
	 //include("generaDocs.php?factura=SI");
	 
	 return "exito|$id_control_factura|$cadenaIdFacturas";
	 
	 //incluir cofigp facturacion
	 


}//fin funcion Genera Facturas_ClienteRFC

/********************************************************************************************************************/

if($opcion == 2){
/*Opcion Genera Una factura Global por Cliente de N Notas de Venta Seleccionadas
*******/
  
  if($id_nv != "") $notasVenta = explode("@@",$id_nv);
  if($id_cli != "") $cliente = explode("@@",$id_cli); 

  $IDSucursal = $_SESSION["USR"]->sucursalid;
  $NVAsociadas = implode(",",$notasVenta);
  $cadenaIdFacturas =""; 
  //echo "asoc: ".$NVAsociadas."\n";
  //echo $notasVenta[0]."-".$notasVenta[1]."-".count($notasVenta)."\n";
  
  //validamos si el Cliente de las notas de venta seleccionadas Requiere Factura
   	$sqlCli = "SELECT require_factura FROM anderp_clientes WHERE id_cliente=".$id_cli[0];

	$resultadoCli = mysql_query(utf8_decode($sqlCli)) or die("Error en:\n$sqlCli\n\nDescripcion:\n".mysql_error());
    $numCli = mysql_num_rows($resultadoCli);
	
	if($numCli>0){
	   $rowC=mysql_fetch_row($resultadoCli);
	   if($rowC[0] == 1){//requiere Factura
	      $opcionCli = 1;
	   }
	   else{
	      if($rowC[1] == 0){ //No requiere Factura
	         $opcionCli = 2;
	      }
	   }
	   
	}
	
 //datos fiscales
 //valores de año y numero de aprobacion
  $anio_aprobacion='';
  $no_aprobacion ='';
  
  $sqlExt = "SELECT 
b.id_control_folio,
b.anio_aprobacion,
b.numero_aprobacion
FROM anderp_folios a
JOIN anderp_folios_historico b ON a.id_control_folio = b.id_control_folio AND b.activo=1
WHERE a.activo=1 AND a.id_sucursal =".$IDSucursal;
   	$resExt=mysql_query($sqlExt) or die("Error en:\n$sqlExt\n\nDescripcion:".mysql_error());
	$numExt=mysql_num_rows($resExt);//numero de filas
    if($numExt > 0){
	   $rowExt=mysql_fetch_array($resExt);
	   $anio_aprobacion =  $rowExt[1];
	   $no_aprobacion = $rowExt[2];	
	}
	
	$forma_pago_sat='';
	$no_cuenta='';
	
	//datos de fiscales de cliente
	$sqlFiscal = "SELECT forma_pago_sat,no_cuenta  FROM anderp_clientes WHERE id_cliente = ".$cliente[0];
	$resFiscal=mysql_query($sqlFiscal) or die("Error en:\n$sqlFiscal\n\nDescripcion:".mysql_error());
	$numFiscal=mysql_num_rows($resFiscal);//numero de filas
    if($numFiscal > 0){
	   $rowFiscal=mysql_fetch_array($resFiscal);
	   $forma_pago_sat = $rowFiscal[0];
	   $no_cuenta = $rowFiscal[1]; 	   
	}
	
	
   
	//Encabezado de Factura
 if($opcionCli == 1){ //Requiere Factura Dejamos el mismo cliente de la nota de venta
	$sql = "SELECT 
	a.id_compania,
	a.id_sucursal,
	a.id_moneda,
	a.id_cliente,
	a.id_direccion_entrega,
	a.id_ruta,
	a.id_vendedor,
	a.id_tipo_pago,
	a.fecha_revision,
	a.fecha_vencimiento,
	a.porcentaje_descuento,
	a.cancelada,
	a.fecha_cancelacion,
	a.hora_cancelacion,
	a.id_usuario_cancelacion,
	SUM(a.subtotal) as 'subtotal',
	SUM(a.descuento) as 'descuento',
	SUM(a.total) as 'total'
	FROM anderp_notas_venta a 
	WHERE a.id_sucursal=".$IDSucursal." AND a.id_cliente=".$cliente[0]." AND a.id_control_nota_venta IN(".$NVAsociadas.")
	GROUP BY a.id_cliente";  

 }
 else{
    if($opcionCli == 2){ //No requiere Factura 
	   $sql = "SELECT 
		a.id_compania,
		a.id_sucursal,
		a.id_moneda,
		a.id_direccion_entrega,
		a.id_ruta,
		a.id_vendedor,
		a.id_tipo_pago,
		a.fecha_revision,
		a.fecha_vencimiento,
		a.porcentaje_descuento,
		a.cancelada,
		a.fecha_cancelacion,
		a.hora_cancelacion,
		a.id_usuario_cancelacion,
		SUM(a.subtotal) as 'subtotal',
		SUM(a.descuento) as 'descuento',
		SUM(a.total) as 'total'
		FROM anderp_notas_venta a 
	WHERE a.id_sucursal=".$IDSucursal." AND a.id_cliente=".$cliente[0]." AND a.id_control_nota_venta IN(".$NVAsociadas.")
GROUP BY a.id_cliente";  
	   
     } 
 }
 
  


	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$num=mysql_num_rows($res);//numero de filas
	$col = mysql_num_fields($res);
	
	$strValores = '';
	$strCamposNombre = '';
	$strInsert ='';
	if($num >0){
	
         //obtenemos cadena de campos para insertar encabezado de facturas
         $strCamposNombre = MakeCadenaCampos($col,$res);  
        
         //obtenemos valores de la consulta 
	     while($row = mysql_fetch_row($res)){
		   	
           $strValores = MakeCadenaValores($col,$res,$row);
		 
		 }//fin while
		 mysql_free_result($res);
	   
	
$horaActMexico = date("Y-m-d H:i:s"); //enves de NOW() colocamos la hora de mexico y no del servidor.
	  
	   
	   //generamos insert para Encabezado de Facturas
	  if($opcionCli == 1){ //cliente requiere Factura
	      $strInsert = "INSERT INTO anderp_facturas (id_control_factura,".$strCamposNombre.",fecha_y_hora,activo,notas_venta_asociadas,anio_aprobacion,numero_aprobacion,forma_pago_sat,no_cuenta) VALUES (NULL,".$strValores.",'".$horaActMexico."',1,'".$NVAsociadas."','".$anio_aprobacion."','".$no_aprobacion."',".$forma_pago_sat.",'".$no_cuenta."')";
	  }
	  else{
	    if($opcionCli == 2){//cliente no requiere Factura
	        $strInsert = "INSERT INTO anderp_facturas (id_control_factura,".$strCamposNombre.",fecha_y_hora,activo,notas_venta_asociadas,id_cliente,anio_aprobacion,numero_aprobacion,forma_pago_sat,no_cuenta) VALUES (NULL,".$strValores.",'".$horaActMexico."',1,'".$NVAsociadas."',1,'".$anio_aprobacion."','".$no_aprobacion."',1,'NO IDENTIFICADO')";
		}
	  }
	     
	   
	   
	   
	   //echo $strInsert."\n";
	  
	   mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );
	   
	   	//recibo el último id
   	$id_control_factura = mysql_insert_id(); 
   	//echo $id_control_factura."\n"; 
	
	/****************************************************************************************************/
	//Detalle Factura parcial
for($i=0; $i<count($notasVenta); $i++){	
	 $sqlDeta = "
SELECT 
a.id_control_nota_venta,
a.id_control_nota_venta_detalle,
a.id_producto,
a.id_producto_tipo,
a.id_producto_presentacion,
a.cantidad,
a.kilos_brutos,
a.tara_x_caja,
a.kilos_netos,
a.kilos_promedio,
a.valor_unitario,
a.valor,
a.valor_minimo,
a.descuento,
a.iva_monto,
a.iva_tasa,
a.ieps_monto,
a.ieps_tasa,
a.retencion_monto,
a.retencion_tasa,
a.importe,
a.id_unidad_venta
FROM anderp_notas_venta_detalles a
WHERE a.id_control_nota_venta=".$notasVenta[$i];    

//echo $sqlDeta;

	$result=mysql_query($sqlDeta) or die("Error en:\n$sqlDeta\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$numd = mysql_num_rows($result);//numero de filas
	$cold = mysql_num_fields($result);
	
	//echo "NumDeta ".$numd."\n ColDeta ".$cold."\n"; 
	if($numd >0){
	
	     $strCamposNombreDeta = '';
		 //Obtenemos cadena de campos a insertar en Facturas detalle parcial
         $strCamposNombreDeta = MakeCadenaCampos($cold,$result); 
		    
		 //obtenemos valores de la consulta
	     while($row = mysql_fetch_array($result)){
		 	$strValoresDeta = '';
	        $strInsertDeta ='';
		     
			$strValoresDeta = MakeCadenaValores($cold,$result,$row);
		  
			
			  $strInsertDeta = "INSERT INTO anderp_facturas_detalles_parcial (id_control_factura_detalle_parcial,id_control_factura,".$strCamposNombreDeta.",activo) VALUES (NULL,".$id_control_factura.",".$strValoresDeta.",1)";
	         //echo $strInsertDeta."\n";
		
		 mysql_query($strInsertDeta) or rollback('strInsertDeta',mysql_error(),mysql_errno(),$strInsertDeta );
			 
		 }//fin while
		 mysql_free_result($result);
     }//fin if numd
	 
 }//fin for i	 		
		
   /**************************************************************************************************/		 
	$new_precio_unitario = array();
	//generamos los datos para facturas detalle Agrupando los productos de cada nota de Venta por:
	 //id_producto,tipo_producto y presentacion de producto
//calculamos el precio unitario ponderado
	 $sqlDetaFact = "SELECT 
id_control_factura,
id_producto,
id_producto_tipo,
id_producto_presentacion,
SUM(IFNULL(cantidad,0)) as 'cantidad',
SUM(kilos_brutos) as kilos_brutos,
SUM(tara_x_caja) as tara_x_caja,
SUM(kilos_netos) as kilos_netos,
SUM(kilos_promedio) as kilos_promedio,
AVG(valor_unitario) as valor_unitario,
SUM(valor) as valor,
AVG(valor_minimo) as valor_minimo,
AVG(valor_maximo) as valor_maximo,
SUM(descuento) as descuento,
SUM(iva_monto) as iva_monto,
SUM(iva_tasa) as iva_tasa,
SUM(ieps_monto) as ieps_monto,
SUM(ieps_tasa) as ieps_tasa,
retencion_monto,
retencion_tasa,
SUM(importe) as importe,
activo,
id_unidad_venta
FROM anderp_facturas_detalles_parcial
WHERE id_control_factura=".$id_control_factura."
GROUP BY id_control_factura,id_producto,id_producto_tipo,id_producto_presentacion"; 

 //echo sqlDetaFact."\n";
 
	$resultado1=mysql_query($sqlDetaFact) or die("Error en:\n$sqlDetaFact\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$filas1 = mysql_num_rows($resultado1);//numero de filas
	$cols1 = mysql_num_fields($resultado1);
    //$rowt = mysql_fetch_assoc($resultado1);
    //extract($rowt);
   
   //calculo de precio ponderado
    if($filas1>0){
   //calculo de precio ponderado
     while($rowt = mysql_fetch_assoc($resultado1)){
	 extract($rowt);
	if($valor > 0){
	  if($id_unidad_venta == 1){  //KG
	      	   $new_precio_unitario[count($new_precio_unitario)] = number_format(($importe/$kilos_netos),4);  
			   
			   
	  }

	  if($id_unidad_venta == 2){  //PIEZAS
	       if($cantidad > 0){
	      	   $new_precio_unitario[count($new_precio_unitario)]  = number_format(($importe/$cantidad),4); 
		   }
		   else{
		       $new_precio_unitario[count($new_precio_unitario)]   = number_format($valor,4);
		   }   
	  }
	   //echo "Precio Ponderado (".$importe."/".$kilos_netos.") = ".$new_precio_unitario;
	}
	}//fin while
   }//fin if filas1
   ///////////////////////////
//////Guarda Factura detalle 

	 $sqlDetaFactGbl = "SELECT 
id_control_factura,
id_producto,
id_producto_tipo,
id_producto_presentacion,
SUM(IFNULL(cantidad,0)) as 'cantidad',
SUM(kilos_brutos) as kilos_brutos,
SUM(tara_x_caja) as tara_x_caja,
SUM(kilos_netos) as kilos_netos,
SUM(kilos_promedio) as kilos_promedio,
AVG(valor_unitario) as valor_unitario,
AVG(valor_minimo) as valor_minimo,
AVG(valor_maximo) as valor_maximo,
SUM(descuento) as descuento,
SUM(iva_monto) as iva_monto,
SUM(iva_tasa) as iva_tasa,
SUM(ieps_monto) as ieps_monto,
SUM(ieps_tasa) as ieps_tasa,
retencion_monto,
retencion_tasa,
SUM(importe) as importe,
activo,
id_unidad_venta
FROM anderp_facturas_detalles_parcial
WHERE id_control_factura=".$id_control_factura."
GROUP BY id_control_factura,id_producto,id_producto_tipo,id_producto_presentacion"; 
 
 //echo $sqlDetaFact."\n";
 
	$resultado=mysql_query($sqlDetaFactGbl) or die("Error en:\n$sqlDetaFactGbl\n\nDescripcion:".mysql_error()."\n".mysql_errno());
	$filas = mysql_num_rows($resultado);//numero de filas
	$cols = mysql_num_fields($resultado);

	 if($filas >0){
	     $strCamposNombreDeta = '';
	     //Obtiene cadena de campos a insertar en facturas detalle 
		 $strCamposNombreDeta = MakeCadenaCampos($cols,$resultado); 
		 $cont=0;
		 //obtenemos valores de la consulta 
		 while($row = mysql_fetch_array($resultado)){
		 	$strValoresDeta = '';
	        $strInsertDeta ='';
		  
			$strValoresDeta = MakeCadenaValores($cols,$resultado,$row);
			$new_precio_unitario[$cont]=str_replace(',','',$new_precio_unitario[$cont]);
   $strInsertDetaGbl = "INSERT INTO anderp_facturas_detalles (id_control_factura_detalle,".$strCamposNombreDeta.",valor) VALUES (NULL,".$strValoresDeta.",".$new_precio_unitario[$cont].")";
	        //echo $strInsertDetaGbl."\n";
			
			mysql_query($strInsertDetaGbl) or rollback('strInsertDetaGbl',mysql_error(),mysql_errno(),$strInsertDetaGbl );
			 $cont = $cont + 1;
		 }//fin while
		 mysql_free_result($resultado);
	 }//fin if filas
	 
	 
  }//fin if num
  

  $sqlUpdateNV = "UPDATE anderp_notas_venta SET id_control_factura=".$id_control_factura." WHERE id_control_nota_venta IN(".$NVAsociadas.")";
  
   // echo $sqlUpdateNV."\n";
    mysql_query($sqlUpdateNV) or rollback('sqlUpdateNV',mysql_error(),mysql_errno(),$sqlUpdateNV );
	
		 //actualizamos CxC
  $sqlUpdateCxC = "UPDATE anderp_cuentas_por_cobrar SET id_control_factura=".$id_control_factura." WHERE id_control_nota_venta IN(".$NVAsociadas.")";
     //echo $sqlUpdateCxC."\n";
     mysql_query($sqlUpdateCxC) or rollback('sqlUpdateCxC',mysql_error(),mysql_errno(),$sqlUpdateCxC );
	 
	 
	
	 mysql_query("COMMIT");
	 
	
	   if($cadenaIdFacturas == ""){
	     $cadenaIdFacturas = $id_control_factura;
	  }
	  else{
	    $cadenaIdFacturas = $cadenaIdFacturas."@@".$id_control_factura;
	  }
	  
	  
	 //Llamo al sellado de la factura
	// require("generaDocs.php");
	 
	 
	 
	 echo "exito|$id_control_factura|$cadenaIdFacturas";
	 
	 
}//fin if opcion 2




/*Funcion para construir cadena de campos de X consulta
****/
function MakeCadenaCampos($col,$res){

   $strCamposNombre = '';

	    for ($j = 0; $j < $col; $j++) {
          $campo = mysql_field_name($res, $j);
		  
		  if($strCamposNombre ==''){
		     $strCamposNombre = $campo;
		  }
		  else{
		       $strCamposNombre = $strCamposNombre.", ".$campo;
		  }
		  
        }//fin for i
		
		return $strCamposNombre;
		
}//fin funcion crear cadena de campos

/*Funcion para construir cadena de valores de X consulta
****/
function MakeCadenaValores($col,$res,$row){
      
	  
	  for($k=0; $k<$col; $k++){
			    
		   $type  = mysql_field_type($res, $k);
		   // echo $type."-".$row[$k]."\n"; 
				  
		    if($strValores == ''){
			   if($type == 'string' || $type == 'date' || $type == 'blob' || $type == 'time'){
				  $strValores = "'".$row[$k]."'";
			   }
				else{
					 if($row[$k] == '')
					      $strValores = "'".$row[$k]."'";
				     else	  
				         $strValores = $row[$k];
					}   
				 }
				 else{
				    if($type == 'string' || $type == 'date' || $type == 'blob' || $type == 'time'){
					   $strValores = $strValores.", '".$row[$k]."'";
					}
					else{
					   if($row[$k] == '')
					      $strValores = $strValores.", '".$row[$k]."'";
					   else
					       $strValores = $strValores.", ".$row[$k];
					}    
				 }
			} //fin for k
	    
		 
		 
		 return $strValores;

}//fin


?>