<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$nombre_cuenta_contable = utf8_decode($nombre_cuenta_contable);
	
	switch ($nivel_cuenta){
	
		case "1":
				$sql = "UPDATE scfdi_cuentas_contables SET 
				cuenta_contable='$cuenta_contable',
				nombre='$nombre_cuenta_contable',
				nivel=$nivel_cuenta,
				es_cuenta_mayor=$es_cuenta_mayor,
				id_genero_cuenta_contable=$id_genero_cuenta_contable_aux,
				facturable=$es_facturable,
				id_cuenta_superior='$id_cuenta_contable',
				id_cuenta_mayor='$id_cuenta_contable',
				visible_arbol=$visible_arbol,
				en_poliza=$visible_poliza,
				activo=$activo,
				niveles=$nivelesDeLaCuenta
				WHERE id_cuenta_contable='$llave'";
				
				$result = mysql_query($sql);
				
				if($result != 1){
					
					$error =mysql_errno();
					if($error == 1062){
						$result = "ERROR: ID CUENTA CONTABLE YA EXISTE\n(Nota: Si no se encuentra en la lista, es posible que esté desactivada)";
					}
					else{
						$result = "ERROR AL ACTUALIZAR: Intentar más tarde. " . mysql_error();
					}
					
				}
				
				echo $result;
				
			break;
			
		case "2":
				$cuenta_contable_nivel2 = $CuentaSup . "-" . $cuenta_contable;
				$sql = "UPDATE scfdi_cuentas_contables SET 
				cuenta_contable='$cuenta_contable_nivel2',
				nombre='$nombre_cuenta_contable',
				nivel=$nivel_cuenta,
				es_cuenta_mayor=$es_cuenta_mayor,
				id_genero_cuenta_contable=$id_genero_cuenta_contable_aux,
				facturable=$es_facturable,
				id_cuenta_superior='$id_cuenta_mayor',
				id_cuenta_mayor='$id_cuenta_mayor',
				visible_arbol=$visible_arbol,
				en_poliza=$visible_poliza,
				activo=$activo WHERE id_cuenta_contable='$llave'";
							
				$result = mysql_query($sql);
				
				if($result != 1){
					
					$error =mysql_errno();
					if($error == 1062){
						$result = "ERROR: ID CUENTA CONTABLE YA EXISTE";
					}
					else{
						$result = "ERROR AL ACTUALIZAR: Intentar más tarde. \n" . mysql_error();
					}
					
				}
				
				echo $result;
				
			break;
			
		case "3":
				$cuenta_contable_nivel3 = $CuentaSup . "-" . $cuenta_contable;
				$sql = "UPDATE scfdi_cuentas_contables SET 
				cuenta_contable='$cuenta_contable_nivel3',
				nombre='$nombre_cuenta_contable',
				nivel=$nivel_cuenta,
				es_cuenta_mayor=$es_cuenta_mayor,
				id_genero_cuenta_contable=$id_genero_cuenta_contable_aux,
				facturable=$es_facturable,
				id_cuenta_superior='$id_cuenta_superior',
				id_cuenta_mayor='$id_cuenta_mayor',
				visible_arbol=$visible_arbol,
				en_poliza=$visible_poliza,
				activo=$activo WHERE id_cuenta_contable='$llave'";
							
				$result = mysql_query($sql);
				
				if($result != 1){
					
					$error =mysql_errno();
					if($error == 1062){
						$result = "ERROR: ID CUENTA CONTABLE YA EXISTE";
					}
					else{
						$result = "ERROR AL ACTUALIZAR: Intentar más tarde";
					}
					
				}
				
				echo $result;
				
			break;
			
	}
	
	
	//$smarty->display("especiales/agregar_cuentas_contables.tpl");
?>