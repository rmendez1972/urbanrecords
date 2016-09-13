<?PHP
include ("config.php");
$conexion=mysql_connect($Servidor,$UsrMysql,$ClaveMysql);
mysql_select_db($DB,$conexion);
?>