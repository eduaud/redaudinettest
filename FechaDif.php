<?php
 putenv ('TZ=America/Mexico_City'); 
 mktime(0,0,0,1,1,1970);
echo "pruebas";

 echo "No mas no </br>";
 
  horasX();
 

function horasX(){
 echo "Hora de mexico ".date("Y-m-d H:i:s")."</br>"; 
}

?>