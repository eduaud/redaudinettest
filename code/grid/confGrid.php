<?php

	include("funcionesEspecialesGrid.php");
	
	$grids=array();
	$grid_detalle=array();
	$grids2=array();
	$grid_detalle2=array();
	$ngrids=0;
	$ngrids2=0;
	
	$funcFin="function validaGridTotalCampos(tabla)\n{";
	$funcNew="function nuevoFila(tabla)\n{";
	$funcNew.="\n\tvar pos=NumFilas(tabla)-1;";		
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		//echo "llave".$llave."-".$row[25]."</br>";
		//Conseguismos el valor que dice si el grid se despliega o no
		
		
	
		
		
		if(!isset($llave) || $llave == '')
		{			
			$sq=str_replace('$ID',"-45",$row[25]);
		}	
		else	
			$sq=str_replace('$ID',$llave,$row[25]);			
		//echo "$sq<br>";
		$re=mysql_query($sq) or die("Error en:<br><i>$sq</i><br><br>Descripcion:<b>".mysql_error());
		$ro=mysql_fetch_row($re);
		$row[25]=$ro[0];
		
	
		
		if(strlen($row[20]))
		{
				
			//Conseguimos el numero de filas actuales
			if(!isset($llave) || $llave == '')
			{			
				$sq=str_replace('$ID',"-45",$row[20]);
		
			}	
			else	
				$sq=str_replace('$ID',$llave,$row[20]);
				
			$sq = str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid,$sq);
			
			//echo "$sq<br>";
			$re=mysql_query($sq) or die("Error en:<br><i>$sq</i><br><br>Descripcion:<b>".mysql_error());
			$numFilasExistentes=mysql_num_rows($re);
			mysql_free_result($re);
		}
		else
			$numFilasExistentes=-1;
		
		
		//si estamos en la opcion de colocamos el valor de listado ugual a S
		if($ver=='1' || $especialValorProyectoPedidos=="1")
		{
			$row[14]="S";
			$row[9]="false";
		}
		
		
		
		
		
		
		array_push($grids, $row);
		$ngrids++;
		//echo "<br>Dato:".$row[18];
		/*if(!$row[18])
		{
			array_push($grids, $row);
			$ngrids++;
		}
		else
		{
			array_push($grids2, $row);	
			$ngrids2++;
		}*/	
		$det=array();
		$sq="SELECT * FROM sys_grid_detalle WHERE id_grid=".$row[0]." ORDER BY orden";
		$var_id_grid=$row[0];
		
		//echo "$sq<br>";		
		$funcNew.="\n\tif(tabla == '".$row[1]."')\n\t{";		
		$funcFin.="\n\tif(tabla == '".$row[1]."')\n\t{";
		$funcFin.="\n\t\tborrafilvac('".$row[1]."');";
		$funcFin.="\n\t\tvar num=NumFilas('".$row[1]."');";
		if($numFilasExistentes==0&&$row[26]==0)
		{
			$funcFin.="\n\t\tborrafilvac(tabla)";
			$funcFin.="\n\t\tnum=NumFilas(tabla);";
		}
		$funcFin.="\n\t\tif(num < ".$row[26].")\n\t\t{";
		$funcFin.="\n\t\t\talert('Es necesario insertar al menos ".$row[26]." dato al grid de ".$row[2]."');\n\t\t\treturn false;\n\t\t}";		
		if(strlen($row[21]) > 0)		
			$funcFin.="\n\t\tif(".$row[21]." == false)\n\t\t\treturn false;";
		$funcFin.="\n\t\tfor(i=0;i<num;i++)\n\t\t{";
		$re=mysql_query($sq);
		$nu=mysql_num_rows($re);		
		for($j=0;$j<$nu;$j++)
		{
			$ro=mysql_fetch_row($re);
			if($ro[16] == 'now()' or $ro[16] == 'NOW()')
			{			
				$ro[16]=date("Y-m-d")." ".date("H:i:s");
				
			}
			if($ro[16] == 'sucursal')
			{			
				$ro[16]=$_SESSION["USR"]->sucursalid;
				
			}			
			if($ro[16] == 'USUARIO')
			{			
				$ro[16]=$_SESSION["USR"]->userid;
				
			}			
			if(strlen($ro[20]) > 0)
			{
				$ro[20]=str_replace('$ROOT', ROOTURL, $ro[20]);
			}	
			
			//grid de na_movimientos_almacen
			if($var_id_grid=='54')
			{
				// lo obtenemos de encabezadosExcepcionesAntesGrid $valor_subtipo_movimiento
				//echo '<pre>';print_r($ro);echo '</pre>';die();
				$ro = cambiaPropiedadesDetalleGrid($ro,$valor_subtipo_movimiento,$stm,$op,$tabla);	//JA 31/12/2015
			}
			 
			
		$grupo = $_SESSION["USR"] -> idGrupo;
			
			if($grupo == 2 && ($var_id_grid==3 || $var_id_grid == 100))
			{
				// Obtenemos el campo modificable de grid_detalles y lo modificamos por No
				$ro[5] ='N';
			}
			
			array_push($det,$ro);
			if($ro[17] == 'S')
			{
				$funcNew.="\n\t\tif(pos == -1)\n\t\t\treturn true;";
				$funcNew.="\n\t\tif(celdaValorXY('".$row[1]."', $j, pos) == \"\")\n\t\t{";
				$funcNew.="\n\t\t\talert('Es necesario ingresar el dato \"".$ro[2]."\" en el grid \"".$row[2]."\"');";
				$funcNew.="\n\t\t\treturn false;";
				$funcNew.="\n\t\t}";
				$funcFin.="\n\t\t\tif(celdaValorXY('".$row[1]."', $j, i) == \"\")\n\t\t\t{";
				$funcFin.="\n\t\t\t\talert('Es necesario ingresar el dato \"".$ro[2]."\" en el grid \"".$row[2]."\" en la fila '+(i+1)+' ');";
				$funcFin.="\n\t\t\t\treturn false;";
				$funcFin.="\n\t\t\t}";
			}
		}
		$funcNew.="\n\t\treturn true;\n\t}";
		$funcFin.="\n\t\t}\n\t\treturn true;\n\t}";		
		array_push($grid_detalle, $det);		
	}	
	
	$funcNew.="\n}";
	$funcFin.="\n}";
	//echo "<br>$funcNew";
	//echo "Good[$num]";
	//echo sizeof($grid_detalle2[0])."<br>";
	//print_r($grids);
	
	//print_r($grid_detalle);
	
	require("../grid/gridExcepciones.php");
	$smarty->assign("ngrids",$ngrids);
	$smarty->assign("grids",$grids);
	$smarty->assign("grid_detalle",$grid_detalle);
	$smarty->assign("ngrids2",$ngrids2);
	$smarty->assign("grids2",$grids2);
	$smarty->assign("grid_detalle2",$grid_detalle2);
	$smarty->assign("funcNew",$funcNew);	
	$smarty->assign("funcFin",$funcFin);	//print_r($grids);
	

?>