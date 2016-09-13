<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buscador de supervisores</title>
<script>
function ponPrefijo(nombre,id) {
	window.opener.document.formulario.id_localidad.value = id;
	window.opener.document.formulario.localidad.value = nombre;
	window.close();
}
</script>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
</head>
<?PHP  
include ("../libreria/config.php");
include ("../libreria/libreria.php");
	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);
	if (!isset($_POST['nombre']) && isset($_GET['nombre'])) $_POST['nombre']=$_GET['nombre'];
	if (isset($_POST['anio'])) $_GET['anio']=$_POST['anio'];

?>
<body onLoad="Javascript: document.buscar.nombre.focus();">
<form action="<?PHP  echo $_SERVER['PHP_SELF']; ?>" name="buscar" method="post">
   <table border="0" cellpadding="2" align="center" class="tablaDatos">
      <tr class="tituTab">
	    <td colspan="2" class="primeralinea" align="center">
		  Buscador de supervisores</td>
	  </tr>
      <tr class="fuente2">
	    <td width="30%" class="segundalinea" >
		  Nombre:</td>
		<td width="70%" class="segundalinea" >
		   <input type="text" name="nombre" maxlength="100" size="40" value="<?PHP  if (isset($_POST['nombre'])) echo $_POST['nombre'] ?>">		</td>
	  </tr >
	  <tr>
	    <td height="52" colspan="2" align="center" class="segundalinea" ><INPUT TYPE="image" style=" vertical-align:middle;  border:0 hidden "  src= "../images/buscar1.png"  border=0 hspace=7 vspace=4 alt="Buscar supervisor" >
	      <img src="../images/agregar1.png" style=" vertical-align:middle;  border:0 hidden " alt="Agregar supervisor" onClick="location.href='agregar.php?anio=<?PHP  echo $_GET['anio'] ?>'"/>
	    
		  <input name="anio" type="hidden" value="<?PHP  echo $_GET['anio'] ?>"></td>
     </tr>
  </table>  
   
   
</form>

<?PHP    
$consulta0 = "select * from cat_supervisores WHERE estado=0";
if (isset($_POST['nombre'])){
//	if (!isset($_POST['nombre'])) $_POST['nombre']=$_GET['nombre'];
	$consulta0 .= " AND nombre like '%".$_POST['nombre']."%'";
}
	echo $consulta0;
$resultado0 = mysql_query($consulta0, $conexion);
$filas=mysql_num_rows($resultado0);
$enlaces=$filas;
if (isset($_GET['numi']))
	$numi=$_GET['numi'];
if (empty($numi)) { $numi=0; }
	 $consulta0.=" ORDER BY nombre";
	 $consulta0.=" limit $numi,10";
	 //echo $consulta0;
	 $resultado0 = mysql_query($consulta0, $conexion);
	$filas=mysql_num_rows($resultado0);
	if (empty($filas)) { print "<center>No hay supervisores con esas caracter&iacute;sticas.</center><br>"; } else { ?>
 
<table align="center" class="tablaDatos">
  <tr class="tituTab"> 
    <td class="primeralinea">Supervisores</td>
    <td class="primeralinea">Doctos en revisi&oacute;n</td>
  </tr>
  <?PHP  while ($lafila=mysql_fetch_array($resultado0)) { ?>
  <tr> 
    <td class="segundalinea"><a href="#" onClick="ponPrefijo('<?PHP  echo $lafila["nombre"]; ?>','<?PHP  echo $lafila["id_supervisor"]; ?>')"><?PHP  echo $lafila["nombre"]; ?></a></td>
    <td class="segundalinea"><?PHP  
		$consulta= "SELECT COUNT(id_seguimiento) 
					FROM `solicitud_supervisor` 
					WHERE anio=".$_POST['anio']." AND id_supervisor=".$lafila["id_supervisor"]."
					AND estado=0";

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		echo mysql_num_rows($sql_datos);
	
	
	?></td>
  </tr>
  <?PHP  } ?>
</table>
<?PHP  } 
  if ($enlaces>10) {
  $i=0;
  $j=1; 
  print "<center><font size=2 face='Verdana, Arial, Helvetica, sans-serif'>Páginas: ";
  while ($i<$enlaces) { ?>
      <a href="listado.php?numi=<?PHP  echo $i; if (isset($_POST['nombre'])) echo "&nombre=".$_POST['nombre']; if (isset($_GET['anio'])) echo "&anio=".$_GET['anio']; ?>"><?PHP  echo $j; ?></a>
 <?PHP  $j++; 
 $i=$i+10; }
 }
 @mysql_free_result($resultado0);
  ?>
  <div align="center">
    <label>
    <img src="../images/cerrarventana1.png" style=" vertical-align:middle;" alt="Cerrar ventana" onClick="window.close();"/>    </label>
  </div>
<p align="center">&nbsp;</p>
</body>
</html>