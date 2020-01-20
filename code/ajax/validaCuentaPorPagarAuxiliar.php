<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

extract($_POST);

$log = "";
$status = false ;
$bool_uuid = false;
$bool_rfc = false;
$bool_subtotal = false;
$bool_total = false;

$selec_valida_xml = "SELECT id_cuenta_por_pagar FROM ad_cuentas_por_pagar_operadora WHERE folio_fiscal = '$uuid' AND id_estatus_cuentas_por_pagar != 3"; 
//$selec_valida_xml = "SELECT id_cuenta_por_pagar FROM ad_cuentas_por_pagar_operadora WHERE folio_fiscal = 'bfa45763-76dc-48df-8c26-9728e6db2834' AND id_estatus_cuentas_por_pagar != 3"; 
$datos = new consultarTabla($selec_valida_xml);
$result = $datos -> obtenerLineaRegistro();

if(empty($result)){
    // validamos el rfc del emisor con el de la sesion 
    $bool_uuid = true;
    $select_rfc_sesion = "SELECT ad_proveedores.rfc AS rfc FROM ad_proveedores LEFT JOIN sys_usuarios ON sys_usuarios.id_cliente_distribuidor = ad_proveedores.id_cliente WHERE sys_usuarios.id_usuario = ".$_SESSION["USR"]->userid;
    $datos_rfc = new consultarTabla($select_rfc_sesion);
    $result_rfc = $datos_rfc -> obtenerLineaRegistro();
    $real_rfc = $result_rfc["rfc"];
    $real_rfc = str_replace(" ","",$real_rfc);
    $log .= "RFC Sesion:  $real_rfc ";
    //$real_rfc = "TTC031027GT6";
    if($real_rfc == $rfc_emisor){
        $bool_rfc = true;
        // Validamos el total y subtotal +- 1.00
        $select_total_subtotal = "SELECT subtotal,total FROM ad_facturas WHERE id_control_factura = $id_control_factura";
        $datos_total_subtotal = new consultarTabla($select_total_subtotal);
        $result_total_subtotal = $datos_total_subtotal -> obtenerLineaRegistro();
        
        $margen = 5.0;
        
        //$result_total_subtotal["subtotal"] = 237931.04;
        //$result_total_subtotal["total"] = 276000.01;
        if($result_total_subtotal["subtotal"] <= ($subtotal+$margen) && $result_total_subtotal["subtotal"] >= ($subtotal-$margen) ){
            $bool_subtotal = true;
            
        }
        if($result_total_subtotal["total"] <= ($total+$margen) && $result_total_subtotal["total"] >= ($total-$margen) ){
            $bool_total = true;               
        } 
    }
}

if($bool_total == true && $bool_subtotal == true){
    $status = true;
}


$array_respnse = array("status" => $status, "uuid" => $bool_uuid, "rfc" => $bool_rfc , "subtotal" => $bool_subtotal , "total" => $bool_total, "Log" => $log );
echo json_encode($array_respnse);

?>