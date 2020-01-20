<?PHP
//Inicializamos las variables de sesión, grid, smarty y MySQL
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	

	$strTrans="AUTOCOMMIT=0";
	mysql_query($strTrans);
	mysql_query("BEGIN");
	
	//--------------
	//--------------	
	
	$arrIDControl = explode('|',$strIDControl);
	$arrIDServicio = explode('|',$strIDServicio);
	$arrCantidadMinima = explode('|',$strCantidadMinima);
	$arrCantidadMaxima = explode('|',$strCantidadMaxima);
	$arrPorcentaje = explode('|',$strPorcentaje);
	
	//echo "tamaño de Areglo: ".count($arr1);
	for($i=0; $i<count($arrIDControl); $i++){
		
		if($arrIDControl[$i]!='-1')
		{
			//realizamos el insert
			if($arrIDControl[$i]=='0' || $arrIDControl[$i]=='NO')
			{
				$sql="INSERT INTO spa_lista_precios_servicios_detalle (id_lista_de_precios_servicios, id_lista_de_precios, id_servicio, cantidad_minima_venta, cantidad_maxima_venta, porcentaje_descuento) VALUES (NULL, ".$id_lista.", ".$arrIDServicio[$i].", ".$arrCantidadMinima[$i].",  ".$arrCantidadMaxima[$i].",  ".$arrPorcentaje[$i].")";
			}
			else
			{//realiza UPDATE
				$sql="UPDATE spa_lista_precios_servicios_detalle SET id_servicio=".$arrIDServicio[$i].", cantidad_minima_venta=".$arrCantidadMinima[$i].", cantidad_maxima_venta=".$arrCantidadMaxima[$i].", porcentaje_descuento=".$arrPorcentaje[$i]." WHERE id_lista_de_precios_servicios='".$arrIDControl[$i]."' ";
			}
			
			mysql_query($sql) or rollback('-',mysql_error(),mysql_errno(),$sql);
		}
		
	}//fin de for i
	mysql_query("COMMIT");
	echo "exito";
	die();

	
	
?>