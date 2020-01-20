<?php	
class ArchivoZip{
    var $zip;
    var $nombreArchivo;
    function ArchivoZip(){
        //Se crea un archivo temporal en la carpeta temporal default del sistema servidor
        $this->nombreArchivo = "biblioteca_archivos.zip";
        $this->zip = new ZipArchive();
        $this->zip->open($this->nombreArchivo, ZipArchive::OVERWRITE);
		$this->zip->addEmptyDir("Biblioteca_Audinet");
    }
	
	function agregarArchivo($ruta_archivo, $nombre_archivo){
        $nombre = str_replace(" ", "_", $nombre);
        $this->zip->addFile($ruta_archivo, "Biblioteca_Audinet/".$nombre_archivo);
    }
 
    function cerrarZip(){
        $this->zip->close();
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=Biblioteca_Audinet.zip");
        header("Content-Transfer-Encoding: binary");
        readfile($this->nombreArchivo);
        unlink($this->nombreArchivo);//Destruye el archivo temporal
    }
}

$archivo_descarga = new ArchivoZip();
$archivo_descarga->ArchivoZip();

$directorio = "../../../Biblioteca_de_Archivos_Audinet/";
$archivos = array_diff(scandir($directorio), array('..', '.'));
$num_archivos = count($archivos);

if($num_archivos > 0){
	for($i = 2; $i < ($num_archivos + 2); $i++){
		$archivo_descarga->agregarArchivo($directorio.$archivos[$i],$archivos[$i]);
	}
}

$archivo_descarga->cerrarZip();
?>