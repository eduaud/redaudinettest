<?PHP
//Inicializamos las variables de sesión, grid, smarty y MySQL
	php_track_vars;
    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
    include("../../../code/especiales/funcionesRent.php");
	

	$strTrans="AUTOCOMMIT=0";
	mysql_query($strTrans);
	mysql_query("BEGIN");
	
	//monto_flete_gerente_ventas +'|'+ 
	$arrMonto = explode('|',$montos);
	
	for($i=0; $i<count($arrMonto); $i++)
	{
		if($arrMonto[$i]=='')
		{
			$arrMonto[$i]=0;
		}
	}

	$sql="UPDATE rac_pedidos SET monto_flete_gerente_ventas =".$arrMonto[0].",
							  monto_montaje_gerente_ventas=".$arrMonto[1].",
							  monto_montaje_extraorinario_gerente_ventas=".$arrMonto[2].",
							  monto_desmontaje_gerente_ventas=".$arrMonto[3].",
							  monto_viaticos_gerente_ventas=".$arrMonto[4].",
							  subtotal_otros=".$arrMonto[5].",
							  iva=".$arrMonto[6].",
							  total=".$arrMonto[7]."							 
		WHERE id_control_pedido='".$id_control."' ";

	mysql_query($sql) or rollback('-',mysql_error(),mysql_errno(),$sql);

	mysql_query("COMMIT");
	
	//mandamos llamar el estatus
	
	echo "exito";
	die();

	
	
?>