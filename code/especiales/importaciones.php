<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$idLayout=0;
if(isset($_GET["idLayout"])){
	$idLayout=$_GET["idLayout"];
} elseif(isset($_POST["idLayout"])){
	$idLayout=$_POST["idLayout"];
}
$idLayout_principal=$idLayout;
$arrErr=array();
$arrErr1=array();
$arrErr2=array();
$contar=array();
$informe='';
/***   Incluye las funciones basicas de importacion   ***/
require('funcionesBaseImportacion.php');
/***   Termina Incluye las funciones basicas de importacion   ***/

/***   Incluye las funciones especiales para cada layout   ***/
require('funcionesEspecialesPorImportacion.php');
/***   Termina Incluye las funciones especiales para cada layout   ***/

$nombreLayout=nombreImportacion($idLayout);
if($nombreLayout == ''){
	header("Location: ". ROOTURL ."code/indices/menu.php");
}

if ($_POST["action"] == "upload") {
	if($idLayout == 5){
		$idAlmacen=$_GET["idAlmacen"];
		$idOrdenCompra=$_GET["idOrdenCompra"];
		$validacionTipoEquipo=$_GET["validacionTipoEquipo"];
		$numeroRenglon=$_GET["numeroRenglon"];
		$cantidadAIngresar=$_GET["cantidadAIngresar"];
		$actualizaNumerosDeSerie=$_GET['actualizaNumerosDeSerie'];
		$idDetalleOrdenCompra=$_GET['idDetalleOrdenCompra'];
	} elseif($idLayout == 6){
		$idCajaComisiones=$_POST['cajaComisiones'];
		$actualizaImporCajaComisiones=$_POST['actualizaImporCajaComisiones'];
	}
	
	if(isset($_FILES['archivo'])){
		$totArchivos = count($_FILES['archivo']['name']);
		if($totArchivos > 1){
			$nombreA1 = $_FILES["archivo"]['name'][0];
			$nombreA2 = $_FILES["archivo"]['name'][1];
			
			$smarty->assign("nombreA1",$nombreA1);
			$smarty->assign("nombreA2",$nombreA2);
		}
	}
	
	for($a=0; $a < $totArchivos; $a++){
		if($totArchivos > 1){
			$tamano = $_FILES["archivo"]['size'][$a];
			$tipo = $_FILES["archivo"]['type'][$a];
			$archivo = $_FILES["archivo"]['name'][$a];
			$tmp_name = $_FILES['archivo']['tmp_name'][$a];
			if($a == 1 && $idLayout_principal == 4){ $idLayout=19; }
		} else {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$tmp_name = $_FILES['archivo']['tmp_name'];
		}

		$encabezadoCorrecto='si';
		$arrEncabezados=encabezadosLayout($idLayout);
		
		if (($gestor = fopen($tmp_name, "r")) !== FALSE) {
			$datos = fgetcsv($gestor, 1000, ",");
			$totalCamposCSV=count($datos);
			
			for($i=0; $i < $totalCamposCSV; $i++){
				if($datos[$i] != $arrEncabezados[$i][1]){
					$encabezadoCorrecto='no';
				}
			}
			
			/***   Valida que no se repita la informacion en el archivo a importar   ***/
			$repetidos=validaInformacionRepetidaEnElArchivoAImportar($idLayout,$tmp_name);
			/***   Termina Valida que no se repita la informacion en el archivo a importar   ***/
			if($encabezadoCorrecto == 'si' && count($repetidos) == 0){
				$fila = 2;
				while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
					$h=0;
					for($j=0; $j < $totalCamposCSV; $j++){
						$resultValida=array();
						$resultValidacionEspecial=array();
						
						$resultValida=validaCampo($arrEncabezados[$h], $datos[$j], $fila);
						$resultValidacionEspecial=validacionEspecial($arrEncabezados[$h], $datos[$j], $fila, $idLayout, $datos);
						
						if(count($resultValida) > 0){
							for($rv=0; $rv < count($resultValida); $rv++){
								array_push($arrErr,$resultValida[$rv]);
							}
						}
						if(count($resultValidacionEspecial) > 0){
							for($rve=0; $rve < count($resultValidacionEspecial); $rve++){
								array_push($arrErr,$resultValidacionEspecial[$rve]);
							}
						}
						$h++;
					}
					$fila++;
				}
			} elseif(count($repetidos) > 0){
				for($rve=0; $rve < count($repetidos); $rve++){
					array_push($arrErr,$repetidos[$rve]);
				}
			} else {
				array_push($arrErr,array("1"," N/A ","Formato Incorrecto"));
			}
			fclose($gestor);
			

			if($idLayout == 5){
				if($cantidadAIngresar != -1 ){
					//Esto es una validacion especial, que verifica que el numero de registros cuadre con el numero que se ingreso en el formulario
					$errorCorrespondencia = comparaCantidadIngresarVSRegistros($idLayout, $tmp_name, $cantidadAIngresar);
					if($errorCorrespondencia!="") array_push( $arrErr, array("N/A", "N/A", $errorCorrespondencia));
					//Valida que el numero de serie no exista en la tabla cl_control_series --->
				}
			}
			
		}
		
		if($a == 1){ $arrErr2 = $arrErr; $arrErr=array(); }
		else{ $arrErr1=$arrErr; $arrErr=array(); }
	}
	
	
	
	$idLayout = $idLayout_principal;
	
	if(count($arrErr1) > 0 || count($arrErr2) > 0){
		$informe='error';
	} else {
		for($a=0; $a < $totArchivos; $a++){
			if($totArchivos > 1){
				$tamano = $_FILES["archivo"]['size'][$a];
				$tipo = $_FILES["archivo"]['type'][$a];
				$archivo = $_FILES["archivo"]['name'][$a];
				$tmp_name = $_FILES['archivo']['tmp_name'][$a];
				if($a == 1 && $idLayout_principal == 4){ $idLayout=19; }
			} else {
				$tamano = $_FILES["archivo"]['size'];
				$tipo = $_FILES["archivo"]['type'];
				$archivo = $_FILES["archivo"]['name'];
				$tmp_name = $_FILES['archivo']['tmp_name'];
			}
			
			$arrEncabezados=encabezadosLayout($idLayout);
			
			if (($gestor = fopen($tmp_name, "r")) !== FALSE) {
				/***   Se ejecunta funciones necesarias (por layout) antes de insertar  los registros   ***/
				if($idLayout == 6){
					if($actualizaImporCajaComisiones == 'si'){
						actualizaCampos('cl_importacion_caja_comisiones','activo','0','id_caja_comisiones,activo',$idCajaComisiones.',1');
						$sqlCargas="insert into cl_cargas (id_layout,fecha_hora,usuario_realizo,accion,nombre_archivo) values 
								(".$idLayout.",now(),'".$_SESSION["USR"]->fullusername."','Remplazo de caja de comisiones ". extraeNombreCajaDeComision($idCajaComisiones)." con id: ".$idCajaComisiones." en la Importacion ".$nombreLayout."','".$archivo."')";
						$queryCargas=mysql_query($sqlCargas);
					}
				}elseif ($idLayout == 5) {
					if($actualizaNumerosDeSerie == 'si'){
						actualizaCampos('cl_importacion_numeros_series','activo','0','id_orden_compra,activo',$idOrdenCompra.',1');
						$sqlCargas="insert into cl_cargas (id_layout,fecha_hora,usuario_realizo,accion,nombre_archivo) values 
								(".$idLayout.",now(),'".$_SESSION["USR"]->fullusername."','Remplazo de numeros de serie con id: ".$idOrdenCompra." en la Importacion ".$nombreLayout."','".$archivo."')";
						$queryCargas=mysql_query($sqlCargas);
					}
				}
				if($idLayout == 17){
					/*para las remesas*/
					$datosR=$_POST['IDRemesasRepetidas'];
					ActualizaDatosAntesDeInsertar($idLayout,$datosR);
				}
				/***   Termina Se ejecunta funciones necesarias (por layout) antes de insertar  los registros   ***/
				
				$queryTablaInsert='SELECT * FROM cl_layouts WHERE id_layout='.$idLayout;
				$resultTablaInsert=mysql_query($queryTablaInsert);
				$datosTablaInsert=mysql_fetch_array($resultTablaInsert);
				$tablaInsertar=$datosTablaInsert['tabla_insertar'];
				$InsertarActualizar=$datosTablaInsert['insertar_actualizar'];
				
				$sqlImportacion="insert into cl_cargas (id_layout,fecha_hora,usuario_realizo,accion,nombre_archivo) values 
								(".$idLayout.",now(),'".$_SESSION["USR"]->fullusername."','Importacion ".$nombreLayout."','".$archivo."')";
				$queryImportacion=mysql_query($sqlImportacion);
				$idImportacion=mysql_insert_id();
				
				$filaDatos=3; $contador=0;
				$qryReg=""; $qry="";
				$encabezados = fgetcsv($gestor, 1000, ",");
				if($InsertarActualizar=="insertar"){
					$contadorAux=0;
					while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
						$totalCamposCSV=count($arrEncabezados);
						$camposInsert='';
						for($i=0; $i < $totalCamposCSV; $i++){
							$camposInsert.=','.$arrEncabezados[$i][13];
						}
						
						$mes=1;
						$meses='';
						$e=0;
						$dtInser="";
						for($d=0;$d<$totalCamposCSV;$d++){
							if($arrEncabezados[$e][2] == 'fecha'){
								$fechaAMD=formatoFechaYMD($datos[$d]);
								$dtInser.=",'".$fechaAMD."'";
							} else {
								if($idLayout == 6 && $arrEncabezados[$e][1] == "Clave Producto"){
									$dtInser.=",'".$datos[$d]."'";
								} else {
									$dtInser.=",'".$datos[$d]."'";
								}
							}
							$e++;
						}
						$l=0;
						/***   Se agregan los valores a insertar para los campos unicos por layout   ***/
						if($idLayout == 6){
							$arr_id_producto_servicio_cxc=devuelveCampoApartirOtroCampo("cl_productos_servicios","id_producto_servicio","clave",$datos[34],"","");
							$arr_id_producto_servicio_cxp=devuelveCampoApartirOtroCampo("cl_productos_servicios","id_producto_servicio","clave",$datos[35],"","");
							
							$id_producto_servicio_cxc=$arr_id_producto_servicio_cxc[0][0];
							$id_producto_servicio_cxp=$arr_id_producto_servicio_cxp[0][0];
							
							$dtInser .= ",'".$id_producto_servicio_cxc."','".$id_producto_servicio_cxp."','".$idCajaComisiones."','1'";
						} elseif($idLayout == 8){
							$idEstatusContrato='';
							$f=0;
							for($g=0;$g<$totalCamposCSV;$g++){
								if($arrEncabezados[$f][1] == 'Status Contrato'){
									if($datos[$g] == 'Reagendado por Nova'){
										$idEstatusContrato .= "2";
									}
								} elseif($arrEncabezados[$f][1] == 'Subestatus Contrato'){
									if($datos[$g] == 'VN Liberado 0-45' || $datos[$g] == 'VN Liberado 46-60'){
										$idEstatusContrato .= "1";
									} elseif($datos[$g] == 'VN Rechazo Definitivo' || $datos[$g] == 'VN Liberado Parcial'){
										$idEstatusContrato .= "3";
									}
								}
								$f++;
							}
							$dtInser .= ",'".$idEstatusContrato."'";
						} elseif($idLayout == 5){
							$dtInser .= ",'".$idAlmacen."','".$idOrdenCompra."','1'".",'".$idDetalleOrdenCompra."'";
							//die(",'".$idAlmacen."','".$idOrdenCompra."','1'".",'".$idDetalleOrdenCompra."'");
						} elseif($idLayout == 12){
							$idCXP='';
							$f=0;
							for($g=0;$g<$totalCamposCSV;$g++){
								if($arrEncabezados[$f][1] == 'Folio'){
									$arrIdCXP=devuelveCampoApartirOtroCampo("ad_cuentas_por_pagar_operadora","id_cuenta_por_pagar","numero_documento",$datos[$g],"","");
									$idCXP=$arrIdCXP[0][0];
								}
								$f++;
							}
							$dtInser .= ",'".$idCXP."'";
						} elseif($idLayout == 17){
							$dtInser .= ",1";
						}elseif($idLayout == 18){
							$idSuc='';
							$idClie='';
							$idConcepto='';
							$f=0;
							for($g=0;$g<$totalCamposCSV;$g++){
								if($arrEncabezados[$f][1] == 'Clave Plaza'){
									$arrIdCXP=devuelveCampoApartirOtroCampo("ad_sucursales","id_sucursal","clave",$datos[$g],"","");
									if(count($arrIdCXP)==0)
										$idSuc=0;
									else 
										$idSuc=$arrIdCXP[0][0];
									
								}
								if($arrEncabezados[$f][1] == 'Clave Distribuidor'){
									$arrIdCXP=devuelveCampoApartirOtroCampo("ad_clientes","id_cliente","clave",$datos[$g],"","");
									if(count($arrIdCXP)==0)
										$idClie=0;
									else 
										$idClie=$arrIdCXP[0][0];
								}
								if($arrEncabezados[$f][1] == 'Total'){
										$meses=$meses.",".$mes;
										$mes+=1;
								}
								if($arrEncabezados[$f][1] == ''){
									$arrIdCXP=devuelveCampoApartirOtroCampo("cl_conceptos_cuotas","id_concepto","nombre",$datos[$g],"","");
									if(count($arrIdCXP)==0)
										$idConcepto=0;
									else 
										$idConcepto=$arrIdCXP[0][0];
								}
								
								$f++;
							}
							$dtInser .= ",'".$idSuc."','".$idClie."','".$_POST['AniosCuotas']."'".$meses.",".$idConcepto.',1';
						} elseif($idLayout == 19){
							$dtInser .= ",1";
						}
						/***   Termina Se agregan los valores a insertar para los campos unicos por layout   ***/

						if($contador == 0){
							$qryReg .= "(".$idLayout.",".$idImportacion.",now()".$dtInser.")";
						} else {
							$qryReg .= ",(".$idLayout.",".$idImportacion.",now()".$dtInser.")";
						}
						
						$contador++;
						if($contador == 480 || feof($gestor) || $fila == $filaDatos){
							/***   Se agregan campos unicos por layout   ***/
							$camposInsert .= agregarCamposUnicosPorLayout($idLayout);
							/***   Termina Se agregan campos unicos por layout   ***/
							
							$qry="INSERT INTO ".$tablaInsertar." (id_layout,id_carga,fecha_carga".$camposInsert.")
									VALUES ".$qryReg;
									//die($qry);
							$ins = mysql_query($qry);
							$contadorAux += $contador;
							$contador=0; $qryReg=""; $qry="";
							$camposInsert="";
							
						}
						$filaDatos++;
					}
				}
				/***   Ejecuta los updates correspondientes al layout   ***/
				/*echo '<pre>';
				print_r($arrEncabezados);
				echo '</pre>';
				die();*/
				actualizaTabla($arrEncabezados);
				/***   Termina Ejecuta los updates correspondientes al layout   ***/
				
				/***   Realiza inserts en una segunda tabla por layout   ***/
				$arrErrorinsertPorLayout=insertPorLayout($idLayout,$tmp_name,$idImportacion);
				$numErrorinsertPorLayout=count($arrErrorinsertPorLayout);
				/***   Termina Realiza inserts en una segunda tabla por layout   ***/
				
				if($idLayout == 5){
					$id_carga = $idImportacion;
				}
				
				$informe='Correcto';
				
				fclose($gestor);
			}
		}
	}
}

if($idLayout == 5){
	
	//$idOrdenCompra =$_GET["idOrdenCompra"];

	$smarty->assign("id_carga",$id_carga);
	$smarty->assign("cantidadAIngresar",$contadorAux);
	$smarty->assign("idAlmacen",$idAlmacen);
	$smarty->assign("idOrdenCompra",$idOrdenCompra);
	$smarty->assign("numeroRenglon",$numeroRenglon);
} elseif($idLayout == 6){
	$arrCajaComisiones=extraeCajasDeComision();
	$smarty->assign("arrCajaComisiones",$arrCajaComisiones);
}elseif($idLayout == 18){
	$arrAnios=extraeAnios();
	$smarty->assign("arrAnios",$arrAnios);
}

$smarty->assign("arrErr1",$arrErr1);
$smarty->assign("arrErr2",$arrErr2);
$smarty->assign("numarrErr2",count($arrErr2));
$smarty->assign("idLayout",$idLayout_principal);
$smarty->assign("informe",$informe);
$smarty->assign("nombreLayout",$nombreLayout);
$smarty->assign("arrErrorinsertPorLayout",$arrErrorinsertPorLayout);
$smarty->assign("numErrorinsertPorLayout",$numErrorinsertPorLayout);

$smarty -> display("especiales/importaciones.tpl");
?>