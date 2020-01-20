<?php
	require('../../conect.php');
	include_once("../general/funciones.php");
	include_once("../especiales/funcionesRent.php");
	include_once("../clases/disponibilidad.class.php");
	
	extract($_GET);
	$accion = 0;
	if (isset($_REQUEST['accion']))
	{
		$accion = $_REQUEST['accion'];
	}

	switch($accion)
	{
		case 0:
			echo  sacarPrecio();
			break;
		case 1: 
			echo  sacarDescuento();
			break;
		case 2:
			echo importarContactos();
			break;	
		case 3:
			echo saca_existencia();
			break;
		case 4:
			echo importarDirecciones();
			break;			
	}

	function saca_existencia()
	{ 
		$id_articulo_principal=$_GET['id_articulo'];	
		$fecha_entrega_articulos=$_GET['fecha_entrega_articulos'];
		$hora_entrega=$_GET['hora_entrega'];
		$fecha_recoleccion=$_GET['fecha_recoleccion'];
		$hora_recoleccion=$_GET['hora_recoleccion'];
		$tipo_evento=$_GET['tipo_evento'];
		$valExistencia = "";
		
		//validamos si es compuesto
		$strSQL="
			SELECT id_articulo, es_compuesto 
			FROM rac_articulos 
			WHERE id_articulo = '".$id_articulo_principal."' ";
		$arrCompuesto=valBuscador($strSQL);
		
		if($arrCompuesto[1] == 1)
		{
			$arregloMin = array();
			
			//OBTTENER CANTIDAD MINIMA DE LOS PRODUCTOS QUE COMPONEN EL PRODUCTO
			$strSQL="
				SELECT id_articulo_basico, cantidad 
				FROM rac_articulos_detalle_basicos 
				WHERE id_articulo = '".$id_articulo_principal."'
			";
			if(!($resource0 = mysql_query($strSQL))) die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
			while($row0 = mysql_fetch_assoc($resource0))
			{
				$disponibleArticulo = new ItemDisponibilidad($tipo_evento, $row0['id_articulo_basico'], convertDate($fecha_entrega_articulos), $hora_entrega, convertDate($fecha_recoleccion), $hora_recoleccion);
				array_push($arregloMin, floor($disponibleArticulo->validarArticuloDisponibilidad() / $row0['cantidad']));
				unset($disponibleArticulo);
			}
						
			$valExistencia = min($arregloMin);
		}
		else
		{
			//OBTTENER DISPONIBILIDAD DEL PRODUCTO
			$disponibleArticulo = new ItemDisponibilidad($tipo_evento, $id_articulo_principal, convertDate($fecha_entrega_articulos), $hora_entrega, convertDate($fecha_recoleccion), $hora_recoleccion);
			$valExistencia = $disponibleArticulo->validarArticuloDisponibilidad();
			unset($disponibleArticulo);
		}
		
		return $valExistencia;
	}
		
	
	function sacarPrecio(){
	  switch($_GET['id_tipo_servicio'])
	  {
		  case 1:
			  $srtSQL="SELECT precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  case 33:
			  $srtSQL="SELECT precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  case 2:
			  $srtSQL="SELECT precio_venta precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  case 3:
			  //TRANSFORMACION
			  $srtSQL="SELECT precio_transformacion precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  case 4:
			  //TRANSFORMACION ESPECIALES
			  $srtSQL="SELECT precio_transformacion_especial precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  case 5:
			  //PRODUCCION
			  $srtSQL="SELECT precio_produccion precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  case 6:
			  //COMPRA
			  $srtSQL="SELECT precio_compra precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."'";
			  break;
		  default:
			  $srtSQL="SELECT 0 precio_renta FROM rac_articulos WHERE id_articulo='".$_GET['id_articulo']."' WHERE 1=0";
		  
	  }
	  
	  $sql=mysql_query($srtSQL) or die("Error");
	  $row= mysql_fetch_assoc($sql);
	  
	  return $row['precio_renta'];
	}
	
	function sacarDescuento(){
	  $srtSQL="SELECT b.porcentaje_descuento  FROM rac_clientes a
			   LEFT JOIN rac_clientes_tipos b ON b.id_tipo_cliente=a.id_tipo_cliente WHERE a.id_cliente='".$_GET['id_cliente']."'";
	  $sql=mysql_query($srtSQL) or die("Error");
	  $row= mysql_fetch_assoc($sql);
	  
	  return $row['porcentaje_descuento'];
	}
	
	function importarContactos()
	{
	  $contactos="";
	  $srtSQL="     SELECT    
	  				a.nombre,
					a.apellidos,
					a.cargo,
					a.id_tipo_contacto,
					a.es_contacto_principal,
					a.telefono1,
					a.telefono2,
					a.celular,
					a.email,
					a.facebook,
					a.twitter,
					a.printers,
					a.dia_nacimiento,
					a.mes_nacimiento,
					a.anio_nacimiento,
					a.id_detalle
					FROM rac_eventos_direcciones_detalle_contactos a
          WHERE a.id_direccion='".$_GET['id_direccion']."' AND a.id_detalle
          NOT IN(SELECT (b.id_contacto) FROM rac_clientes_detalle_contactos b WHERE (b.id_contacto=a.id_detalle AND b.id_direccion=a.id_direccion AND b.id_cliente='".$_GET['id_cliente']."') ) ";
	    $sql=mysql_query($srtSQL) or die("Error");
		while($row=mysql_fetch_row($sql))
		{
		   $contactos.='|'.$row[0].'~'.$row[1].'~'.$row[2].'~'.$row[3].'~'.$row[4].'~'.$row[5].'~'.$row[6].'~'.$row[7].'~'.$row[8].'~'.$row[9].'~'.$row[10].'~'.$row[11].'~'.$row[12].'~'.$row[13].'~'.$row[14].'~'.$row[15];
		}
		
		return $contactos;
	}
	
 //Sacar las direcciones de el lugar de evento  asociado	
  function importarDirecciones()
  {
   $direcciones="";
   $strSQL="SELECT a.id_direccion FROM rac_eventos_direcciones a WHERE a.id_direccion='".$_GET['id_direccion']."' AND a.id_direccion
          NOT IN(SELECT b.id_direccion FROM rac_clientes_detalle_direcciones b WHERE (b.id_cliente='".$_GET['id_cliente']."' AND b.id_direccion='".$_GET['id_direccion']."'))";
   $sql=mysql_query($strSQL) or die("Error");
   while($row=mysql_fetch_row($sql))
		{
		 $direcciones.='|'.$row[0];  
		}		  
   return $direcciones;
  }
	

?>