<?PHP 

//Conectarse a el motor de la base de datos y con la base de datos.

function conectarDB($Servidor, $usuario, $clave, $base)

{

	error_reporting(0);
   	$link=mysql_connect($Servidor,$usuario,$clave) OR die("<div align='center'>No se puede conectar con $servidor</div>");
   	$conexion=mysql_select_db($base,$link) OR die("<div align='center'>No se puede conectar con $base en $base</div>");
	error_reporting(15);

	return $link;



}



//FUNCIO QUE SE UTILIZA PARA DETERMINAR LA IP DE UN CLIENTE CONECTADO, SE UTILIZA PASRA LAS TRANSACCIONES.

function GetIP() 

	{ 

		   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown")) 

				   $ip = getenv("HTTP_CLIENT_IP"); 

		   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 

				   $ip = getenv("HTTP_X_FORWARDED_FOR"); 

		   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 

				   $ip = getenv("REMOTE_ADDR"); 

		   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 

				   $ip = $_SERVER['REMOTE_ADDR']; 

		   else 

				   $ip = "unknown"; 

			

   			return($ip); 

} 


//funcion para acompletar una cadena con un patron requerido

function FAcompleta ($cadena,$patron,$lado,$tamanio)

{

	$cant=$tamanio - strlen($cadena);

	$ceros="";

	for ($i=0; $i<$cant; $i++) 

		$ceros=$ceros.$patron;

	if ($lado=="izquierda")

		$cadena=$ceros.$cadena;

	else

		$cadena=$cadena.$ceros;

	

return $cadena;

}	

	



?>

