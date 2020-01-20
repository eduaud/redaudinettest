<?php
	$strConsulta="SELECT porcentaje_iva FROM sys_parametros_configuracion WHERE activo='1'";
	$res=mysql_query($strConsulta) or die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<b>".mysql_error());
	$row=mysql_fetch_row($res);
	$smarty->assign("porcentaje_iva",$row[0]);
	
	//obtenemos el valor de la orden de entrada y colocamos el update
	if($tabla == "ad_proveedores" ){
	//cuando se muestran los datos , vemos el tipo 
	
		if(( $make=="actualizar") && $llave<>''){
			
			//consultamos el tipo de cliente proveedor que es
			//modificamos los datos que vera por tipo de proveedor
			
			//no podra modificar el tipo de cliente proveedor
			//no podra modificar plaza
			//np podra modificar la clave
			$resValCl=array();
			$strSQL=" SELECT id_tipo_cliente_proveedor FROM ad_proveedores  where id_proveedor = '".$llave."'";
			$resValCl=valBuscador($strSQL);
			$tipo_cliente_proveedor=$resValCl[0];
			if($tipo_cliente_proveedor=='1' || $tipo_cliente_proveedor=='2'  || $tipo_cliente_proveedor=='3'  || $tipo_cliente_proveedor=='6')
			{
				$campo=retornaIDCatalogoOrden("id_tipo_cliente_proveedor ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("id_sucursal ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("clave ","ad_proveedores");
				$atributos[$campo][7]=0;
				
				$campo=retornaIDCatalogoOrden("razon_social ","ad_proveedores");
				$atributos[$campo][7]=0;
				
				$campo=retornaIDCatalogoOrden("rfc ","ad_proveedores");
				$atributos[$campo][7]=0;
				
				$campo=retornaIDCatalogoOrden("calle ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("numero_exterior ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("numero_interior ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("colonia ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("codigo_postal ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("id_pais ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("id_estado ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("id_ciudad ","ad_proveedores");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("delegacion_municipio ","ad_proveedores");
				$atributos[$campo][7]=0;
				
				
			}
		
		}
	
	}
	
	
	
	
	//convierte la fecha a formato aaaa-mm-dd
	function FormatoFecha($fecha){
	  $getFech = explode("/",$fecha);
	  
	  $newFecha =  $getFech[2]."-".$getFech[1]."-".$getFech[0];
	  
	  return $newFecha;
	}
	//convierte fecha a formato aaaa-mm-dd H:MM:SS
	function FormatoFechaHora($fecha){
	  $getTime = explode(" ",$fecha);
	  $getFech = explode("/",$getTime[0]);
	  $newFecha =  $getFech[2]."-".$getFech[1]."-".$getFech[0];
	  
	  return $newFecha." ".$getTime[1];
	}
	

	
	
?>