<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idDocumento = $_POST['idDocumento'];
$caso = $_POST['caso'];

$sql = "SELECT aplica_calculo_iva, aplica_retencion_iva, aplica_retencion_isr FROM ad_tipos_documentos WHERE id_tipo_documento = " . $idDocumento;
		$result = new consultarTabla($sql);
		$datos = $result -> obtenerLineaRegistro();

		$where = "";
		$where .= " AND aplica_calculo_iva = " . $datos['aplica_calculo_iva'];
		$where .= " AND aplica_retencion_iva = " . $datos['aplica_retencion_iva'];
		$where .= " AND aplica_retencion_isr = " . $datos['aplica_retencion_isr'];

if($caso == 1){
		$sql = "SELECT id_gasto, nombre FROM na_conceptos_gastos WHERE id_gasto IN(SELECT DISTINCT id_gasto FROM na_conceptos_subgastos WHERE utilizable_cxp = 1 AND activo = 1" . $where . ") AND utilizable_cxp = 1 AND activo = 1 ORDER BY nombre";
		$result = new consultarTabla($sql);
		$datos = $result -> obtenerRegistros();
				foreach($datos as $dato){
						echo '<option value="' . $dato -> id_gasto . '">' . utf8_encode($dato -> nombre) . '</option>';
						}	
		}
else if($caso == 2){
		$idGasto = $_POST['idGasto'];
		$sql = "SELECT id_subgasto, nombre FROM na_conceptos_subgastos WHERE id_gasto = " . $idGasto . " 
		AND (utilizable_cxp = 1 OR utilizable_caja_chica = 1) AND activo = 1 ORDER BY nombre";
		$result = new consultarTabla($sql);
		$datos = $result -> obtenerRegistros();
				foreach($datos as $dato){
						echo '<option value="' . $dato -> id_subgasto . '">' . utf8_encode($dato -> nombre) . '</option>';
						}	
		}
		
else if($caso == 3){
		echo $datos['aplica_calculo_iva'] . "|" . $datos['aplica_retencion_iva'] . "|" . $datos['aplica_retencion_isr'];
		}

?>