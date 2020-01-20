<?php
/**
*** La siguiente Excepción consulta solamente el GRID de Detalle de Conceptos de Gastos para el módulo de 'Cuentas Por Pagar' Cuando es llamado
*** por el FancyBox de Costeo de Productos
**/

if($t == 'bmFfY3VlbnRhc19wb3JfcGFnYXI=' AND $verGridFancy == 2){
	$sql="SELECT
	      id_grid/*1*/,
		  nombre/*2*/,
		  display/*3*/,
		  tabla_relacionada/*4*/,
		  color_borde/*5*/,
		  alto/*6*/,
		  alto_celda/*7*/,
		  scroll/*8*/,
		  ruta_img/*9*/,
		  funcion_eliminar/*10*/,
		  funcion_nuevo/*11*/,
		  datos_file/*12*/,
		  footer/*13*/,
		  guarda_file/*14*/,
		  listado/*15*/,
		  tabla_padre/*16*/,
		  orden/*17*/,
		  campo_llave/*18*/,
		  alinear_derecha/*19*/,
		  afecta_tabla/*20*/,
		  query/*21*/,
		  funcion_final/*22*/,		  
		  (SELECT SUM(ancho) FROM sys_grid_detalle WHERE id_grid=g.id_grid)/*23*/,
		  IF(
		  	(SELECT SUM(ancho) FROM sys_grid_detalle WHERE id_grid=g.id_grid) > ancho_maximo,
			'S',
			'N'
		  )/*24*/,
		  ancho_maximo/*25*/,
		  QueryVisible/*26*/,
		  filasMin/*27*/,
		  filasIni/*28*/,
		  despues_insertar/*29*/,
		  despues_eliminar/*30*/,
		  estilo_css_head/*31*/,
		  alto_header/*32*/
		  FROM `sys_grid` g
		  WHERE tabla_padre='$tabla' AND nombre='detalleConceptosGastosCuentasPorPagar' OR nombre='detalleProductosCuentasPorPagar'
		  ORDER BY orden";
}
else{
//print_r($t);
//echo $verGridFancy;
//VALIDACIONES DEL GRID
//******************************************************Validacion grid*******************
	$sql="SELECT
	      id_grid/*1*/,
		  nombre/*2*/,
		  display/*3*/,
		  tabla_relacionada/*4*/,
		  color_borde/*5*/,
		  alto/*6*/,
		  alto_celda/*7*/,
		  scroll/*8*/,
		  ruta_img/*9*/,
		  funcion_eliminar/*10*/,
		  funcion_nuevo/*11*/,
		  datos_file/*12*/,
		  footer/*13*/,
		  guarda_file/*14*/,
		  listado/*15*/,
		  tabla_padre/*16*/,
		  orden/*17*/,
		  campo_llave/*18*/,
		  alinear_derecha/*19*/,
		  afecta_tabla/*20*/,
		  query/*21*/,
		  funcion_final/*22*/,		  
		  (SELECT SUM(ancho) FROM sys_grid_detalle WHERE id_grid=g.id_grid)/*23*/,
		  IF(
		  	(SELECT SUM(ancho) FROM sys_grid_detalle WHERE id_grid=g.id_grid) > ancho_maximo,
			'S',
			'N'
		  )/*24*/,
		  ancho_maximo/*25*/,
		  QueryVisible/*26*/,
		  filasMin/*27*/,
		  filasIni/*28*/,
		  despues_insertar/*29*/,
		  despues_eliminar/*30*/,
		  estilo_css_head/*31*/,
		  alto_header/*32*/
		  FROM `sys_grid` g
		  WHERE tabla_padre='$tabla'
		  ORDER BY orden";	
	//echo $sql;	  
	}
?>