<?php
	/**Excepcion para pantalla productos opcion Eliminar
	 ***********************************/

	if($tabla == 'anderp_productos'){
			
		$condicion2 = " AND id_sucursal=".$_SESSION["USR"]->sucursalid." AND id_compania=".$_SESSION["USR"]->id_compania;
		$strElim = $strUpdate." where " .$campoKey ." = '".$$valorllave."'";
		$accion_pant=4; //Accion insert(Nuevo producto)
		$smarty->assign("accionElim",$accionElim);
			
		$id_sucursal_actual = $_SESSION["USR"]->sucursalid;
		$sql = "SELECT id_sucursal FROM sys_sucursales WHERE id_sucursal NOT IN ($id_sucursal_actual)";
		//echo $sql;
		$res = mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
		$numRows = mysql_num_rows($res);
			
		if($numRows >0){
			while($row = mysql_fetch_array($res)){
				$arrIdSuc[count($arrIdSuc)] = $row[0];
			}
			mysql_free_result($res);
		}
			
		if($accion_pant == 4){

			for($k=0; $k<count($arrIdSuc); $k++){
				//valida si no esta relacionada con una nota de venta ese producto en X sucursal
				$sql = "SELECT a.id_control_nota_venta,b.id_producto
				FROM anderp_notas_venta a
				LEFT JOIN anderp_notas_venta_detalles b ON b.id_control_nota_venta= a.id_control_nota_venta
				WHERE a.id_sucursal=".$arrIdSuc[$k]." AND b.id_producto = '".$$valorllave."'";
				//echo $sql;
				$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
				$num=mysql_num_rows($res);
				if($num == 0){ //no hay relacion con nota de venta puede eliminar
					$id_prodDeta = Array();

					//elimina cabecera del producto en X sucursal
					$sql="DELETE FROM anderp_productos WHERE id_sucursal = ".$arrIdSuc[$k]." AND id_producto = ".$$valorllave;
					//echo $sql."</br>";
					$result = mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());

					//elimina detalle de producto en X sucursal
					$sql = "SELECT id_producto
					FROM anderp_productos_detalle
					WHERE id_sucursal = ".$arrIdSuc[$k]." AND id_producto = ".$$valorllave;
					// echo $sql."</br>";
					$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
					$num=mysql_num_rows($res);
					if($num>0){
						while($row = mysql_fetch_array($res)){
							$id_prodDeta[count($id_prodDeta)] = $row[0];
						}
					}


					for($i=0; $i<count($id_prodDeta); $i++){
						$sql2="DELETE FROM anderp_productos_detalle
						WHERE id_sucursal=".$arrIdSuc[$k]." AND id_producto =".$id_prodDeta[$i];
						//echo $sql2."</br>";
						$result = mysql_query($sql2) or die("Error en:\n$sql2\n\nDescripcion:\n".mysql_error());
					} //fin for i
				}
				else{
					//no puede eliminar
				}
			}//fin for k
		}
		/**************************/
	}
	else{
		$condicion2="";
	}
?>