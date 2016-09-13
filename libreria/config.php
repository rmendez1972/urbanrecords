<?php

// Este archivo se utiliza en las p�ginas que se abren en el frame principal

// Aqu&iacute; se establecen los valores de configuraci&oacute;n que se utilizan en las p�ginas.



$titulo=".: Sistema de Control de Constancias :.";

date_default_timezone_set('America/Mexico_City');
$diaC=date("d");
$mesC=date("m");
$anioC=date("Y");

// Direcci&oacute;n de la pagina central

$central="onClick=\"location.replace('../libreria/principal.php')\"";

$centro="onClick=\"location.replace('../libreria/principal.php')\"";



$Servidor='localhost';

$UsrMysql='root'; 

//$ClaveMysql='shalala';
$ClaveMysql='admin';

$DB='constancias';

//setlocale(LC_TIME,"sp");


//para el env&iacute;o en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//direcci&oacute;n del remitente 
$headers .= "From: Sistema de Control de Constancias de Compatibilidad <sistemas_sedu@qroo.gob.mx>\r\n"; 

//direcci&oacute;n de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: ad_urbana@qroo.gob.mx\r\n"; 

//ruta del mensaje desde origen a destino 
//$headers .= "Return-path: witzil@gmail.com\r\n"; 

//direcciones que recibi�n copia 
//$headers .= "Cc: witzil@hotmail.com\r\n"; 

//direcciones que recibir�n copia oculta 
//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 


?>
