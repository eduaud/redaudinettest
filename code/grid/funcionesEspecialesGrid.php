<?php

	function cambiaPropiedadesDetalleGrid($ArrRo,$subtipo_movimiento,$stm,$op,$tabla)
	{
		if($tabla == 'ad_pedidos'){
			if($ArrRo[3]=='precio'){
				$ArrRo[4]='oculto';
			}elseif($ArrRo[3]=='importe'){
				$ArrRo[4]='oculto';
			}
		}else{
			//vamos cambiando renglon por renglon
			$ArrRo[19]=0;
			$ArrRo[4]='oculto';
			
			if($ArrRo[24]=='id_producto')
			{
				$ArrRo[19]=300;
				$ArrRo[4]='buscador';
				
				//solo se puede modificar en nuevo
				if($op==2)
				{
					$ArrRo[5]='N';
				}
				
			}
			elseif($ArrRo[24]=='id_lote')
			{
				
				$ArrRo[19]=180;
				$ArrRo[4]='combo';
				
			}
			elseif($ArrRo[24]=='cantidad')
			{
				$ArrRo[19]=100;
				$ArrRo[4]='decimal';
			}
			elseif($ArrRo[24]=='cantidad_traspaso')
			{
				
				if($subtipo_movimiento=='70005' || $subtipo_movimiento=='70006' )
				{
					$ArrRo[19]=100;
					$ArrRo[4]='decimal';
				}
				if($op==2)
				{
					$ArrRo[5]='N';
				}
			}
			elseif($ArrRo[24]=='cantidad_existencia')
			{
				if($op==1)
				{
					$ArrRo[19]=100;
					$ArrRo[4]='decimal';
				}
				
			}
			elseif($ArrRo[24]=='cantidad_solicitada')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='cantidad')
			{
				
				
				if($subtipo_movimiento=='3')
				{
				
					$ArrRo[19]=100;
					$ArrRo[4]='decimal';
					$ArrRo[5]='N';
				}
			}
			elseif($ArrRo[24]=='id_lote')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=200;
					$ArrRo[4]='combo';
					$ArrRo[5]='S';
				}
				if($op==2)
				{
					$ArrRo[5]='N';
				}
				
			}
			elseif($ArrRo[24]=='id_articulo_origen_final')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='cantidad_origen_final')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='id_lote_origen_final')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='id_tipo_mantenimiento')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='id_tipo_reparacion')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='id_tipo_produccion')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='observaciones_danio')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='monto_danio')
			{
				if($subtipo_movimiento=='')
				{
			
					$ArrRo[19]=0;
					$ArrRo[4]='oculto';
				}
			}
			elseif($ArrRo[24]=='observaciones')
			{
				$ArrRo[19]=350;
				$ArrRo[4]='texto';
				$ArrRo[5]='S';
			}	
			//JA 31122015 -->
			elseif($ArrRo[24]=='IRDS')
			{
				$ArrRo[19]=350;
				$ArrRo[4]='libre';
				$ArrRo[5]='S';
			}		
			//JA 31122015 <--
		}
		return $ArrRo;
	}
	
	

?>