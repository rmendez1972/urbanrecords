<?PHP 

		$requiredUserLevel = array(1,4);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/config.php");
include ("../libreria/libreria.php");



// Conexion con la BD

$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);

if(isset($_GET["anio"])){
	$anio=$_GET["anio"];
	$idsol=$_GET["idsol"];
	$sql="select * from solicitud where anio='$anio' and id_solicitud='$idsol'";
	$cons=mysql_query($sql,$conexion);
	$row=mysql_fetch_array($cons);
}
else
	exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resumen de solicitud</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<style>
body{
	background-color:#FFF;
}
</style>
</head>

<body>

<table class="tablaDatos" width="90%" align="center" style="margin:15px">
	<thead>
		<tr class="tituTab">
			<td colspan="2">Resumen de solicitud</td>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td>Proyecto</td>
			<td><?php echo $row["nombre_proyecto"]; ?></td>
		</tr>
		<tr>
			<td>Solicitante</td>
			<td><?php echo $row["propietario"]; ?></td>
		</tr>
		<tr>
			<td>Descripción</td>
			<td><?php echo $row["descripcion"]; ?></td>
		</tr>
	</tbody>
</table>

</body>

</html>
<?php
mysql_close($conexion);
?>

