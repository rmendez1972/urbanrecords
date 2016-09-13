<html>

<head>

<meta http-equiv="Content-Language" content="es">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title><?php echo "$titulo";?></title>

<LINK href="../libreria/estilos.css" rel="stylesheet" type="text/css">

</head>

<body >
<div id="Layer1" style=" width:100%; height:244px; z-index:1; left: 0; top: 0;"><img src="../images/banner.png" width="100%" height="100%"></div>
<?   include ('../menu/menu.php'); 

$conectado=mysql_connect($Servidor,$UsrMysql,$ClaveMysql) OR die("<div align='center'>No se puede conectar con $servidor</div>");
$conexion=mysql_select_db($DB,$conectado) OR die("<div align='center'>No se puede conectar con $base en $base</div>");
$consulta= "SELECT * FROM cat_usuario u, cat_perfiles p where u.id_perfil=p.id_perfil AND login='".$login."'";
$sql_user=mysql_query($consulta,$conectado);
$data=mysql_fetch_array($sql_user);
echo "<span class=\"fuente2\">USUARIO: ".$data['nombre']." |  PERFIL: ".$data['descripcion']."</span>";
mysql_free_result($sql_user);
mysql_close($conectado);

?>

<br>