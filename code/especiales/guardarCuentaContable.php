<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$nombre_cuenta_contable = utf8_decode($nombre_cuenta_contable);
	switch ($nivel_cuenta){
	
		case "1":
				$sql = "INSERT INTO scfdi_cuentas_contables(cuenta_contable,nombre,nivel,es_cuenta_mayor,id_genero_cuenta_contable,facturable,id_cuenta_superior,id_cuenta_mayor,
							visible_arbol,en_poliza,activo,niveles,id_cuenta_sat) VALUES ('$cuenta_contable','$nombre_cuenta_contable',$nivel_cuenta,$es_cuenta_mayor,$id_genero_cuenta_contable,$es_facturable,
							'$id_cuenta_contable','$id_cuenta_contable',$visible_arbol,$visible_poliza,$activo,$nivelesDeLaCuenta,$cuentaSAT);";
				
				$result = mysql_query($sql);
				$sqlUltimoReg="SELECT * FROM `scfdi_cuentas_contables` ORDER BY `id_cuenta_contable` DESC LIMIT 1";
				$result_1 = mysql_query($sqlUltimoReg);
				$updateCC="UPDATE scfdi_cuentas_contables SET id_cuenta_superior=".mysql_result($result_1,0).", id_cuenta_mayor=".mysql_result($result_1,0)." WHERE id_cuenta_contable=".mysql_result($result_1,0);
				mysql_query($updateCC);
				if($result != 1){
					
					$error =mysql_errno();
					if($error == 1062){
						$result = "ERROR: ID CUENTA CONTABLE YA EXISTE\n(Nota: Si no se encuentra en la lista, es posible que esté desactivada)";
					}
					else{
						$result = "ERROR AL REGISTRAR: Intentar más tarde";
					}
					
				}
				
				echo $result;
				
			break;
			
		case "2":
				$id_cuenta_contable_nivel2 = $cuenta_mayor . "-" . $cuenta_contable;
				$sql = "INSERT INTO scfdi_cuentas_contables(cuenta_contable,nombre,nivel,es_cuenta_mayor,id_genero_cuenta_contable,facturable,id_cuenta_superior,id_cuenta_mayor,
							visible_arbol,en_poliza,activo,id_cuenta_sat) VALUES ('$id_cuenta_contable_nivel2','$nombre_cuenta_contable',$nivel_cuenta,$es_cuenta_mayor,$id_genero_cuenta_contable,$es_facturable,
							'$id_cuenta_mayor','$id_cuenta_mayor',$visible_arbol,$visible_poliza,$activo,$cuentaSAT);";
							
				$result = mysql_query($sql);
				
				if($result != 1){
					
					$error =mysql_errno();
					if($error == 1062){
						$result = "ERROR: ID CUENTA CONTABLE YA EXISTE";
					}
					else{
						$result = "ERROR AL REGISTRAR: Intentar más tarde. \n" . mysql_error();
					}
					
				}
				
				echo $result;
				
			break;
			
		case "3":
				$sqlCuentaSuperior="SELECT cuenta_contable FROM scfdi_cuentas_contables WHERE id_cuenta_contable=".$id_cuenta_superior;
				$resultado=mysql_query($sqlCuentaSuperior);
				
				$id_cuenta_contable_nivel3 = mysql_result($resultado,0) . "-" . $cuenta_contable;
				$sql = "INSERT INTO scfdi_cuentas_contables(cuenta_contable,nombre,nivel,es_cuenta_mayor,id_genero_cuenta_contable,facturable,id_cuenta_superior,id_cuenta_mayor,
							visible_arbol,en_poliza,activo) VALUES ('$id_cuenta_contable_nivel3','$nombre_cuenta_contable',$nivel_cuenta,$es_cuenta_mayor,$id_genero_cuenta_contable,$es_facturable,
							'$id_cuenta_superior','$id_cuenta_mayor',$visible_arbol,$visible_poliza,$activo);";
							
				$result = mysql_query($sql);
				
				if($result != 1){
					
					$error =mysql_errno();
					if($error == 1062){
						$result = "ERROR: ID CUENTA CONTABLE YA EXISTE";
					}
					else{
						$result = "ERROR AL REGISTRAR: Intentar más tarde";
					}
					
				}
				
				echo $result;
				
			break;
			
	}
	
	
	//$smarty->display("especiales/agregar_cuentas_contables.tpl");
?>