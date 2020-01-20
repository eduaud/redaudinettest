<?php
	
	if($tabla == 'ad_movimientos_almacen')
	{
		
		if($op!=1) 
		{
			
			
			$resValAlm=array();
			$strSQL=" SELECT id_subtipo_movimiento FROM ad_movimientos_almacen  where id_control_movimiento = '".$llave."'";
			$resValAlm=valBuscador($strSQL);
			$valor_subtipo_movimiento=$resValAlm[0];
			
			
			
		}		
	} 

	//********************************************************************************************************
	
?>