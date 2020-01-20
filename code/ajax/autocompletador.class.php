<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
 * Clase Autocompletador
 *
 * Sirve para buscar y devolver los resultados de diferentes tablas
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Autocompletador
 * @package    Autocompletador
 * @author     autor original <isc_jluis@live.com.mx>
 * @author     autor  <>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      archivo disponible desde la vercion 1.0
 * @deprecated 
 */
class Autocompletador
{
	//  propiedades de conexion
    /*
     * Funcion publica
     *
     * Incluye conect.php y realiza la conexion
     *
     * @var string
     */
    public function  __construct() {	
		include_once("../../config.inc.php");
		/*echo "Host ".$dbhost." Base ".$dbname." usuario ". $dbuser." password ".$dbpassword."<br />";*/		
		mysql_connect($dbhost, $dbuser, $dbpassword);
		mysql_query("SET NAMES 'utf8'");
        mysql_select_db($dbname) or die('no se logro la coneccion');	
    }
	/*
     * funcion para buscar al cliente
     */
    public function buscarCliente($nombre){
//die('en funcion');
       $datosp = array();
         $sqlp = "SELECT id_cliente, 
					 	 CONCAT(nombre, ' ',  apellido_paterno,' ', apellido_materno) AS nombre
				    FROM ad_clientes
			   	   WHERE activo=1
					 AND (concat_ws(' ',nombre, apellido_paterno, apellido_materno) LIKE '%$nombre%'
					  OR nombre LIKE '%$nombre%'
					  OR apellido_paterno LIKE '%$nombre%'
					  OR apellido_materno LIKE '%$nombre%')
				   LIMIT 10 ";	
/*				   
$sqlp = "SELECT id_cliente, 
					 	 CONCAT(nombre, ' ',  apellido_paterno,' ', apellido_materno) AS nombre
				    FROM ad_clientes
			   	   WHERE activo=1
					 AND (nombre LIKE '%$nombre%'
					  OR apellido_paterno LIKE '%$nombre%'
					  OR apellido_materno LIKE '%$nombre%')
				   LIMIT 10 ";	*/			
        $resultadop = mysql_query($sqlp);

		while ($rowp= mysql_fetch_array($resultadop, MYSQL_ASSOC)){
			$datosp[] = array( "value" => $rowp['nombre'],																	  
								  "id_cliente"=>$rowp['id_cliente']
								 );
			}
//			var_dump($datosp);
//			die();
			return $datosp;					
		}
		
		public function buscarUsuario($nombreUsuario){
        $datos = array();

        $sql = "SELECT folio_inventario,					   
					   equipo,
					   procesador,
					   ram,
					   hd,
					   modelo,
					   ip,
					   area,
					   responsable					   				   
				 FROM  inventario_maestro					   
			     WHERE responsable   LIKE '%$nombreUsuario%'   
				    OR ip   LIKE '%$nombreUsuario%'   
				    OR folio_inventario  LIKE '%$nombreUsuario%'	LIMIT 10";
        $resultado = mysql_query($sql);
        while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)){
        $datos[] = array( "value" => $row['responsable'].", Equipo: ".$row['equipoTipo'].", Modelo: ".$row['modelo'].", IP:  ".$row['ip'],
							  "responsable"=>$row['responsable'],
							  "equipoTipo"  =>  $row['equipo'],
                              "procesador"  =>  $row['procesador'],
                              "ram" => $row['ram'],
							  "dd" => $row['hd'],
							  "s1" => $row['modelo'],
							  "ip" => $row['ip'],
							  "fi" => $row['folio_inventario'],
							  "varea" => $row['area']
                             );
        }
        return $datos;					
    }
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
}