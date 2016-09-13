<html>
<head>
<title>Buscador de Localidades</title>
<script>
function ponPrefijo(nombre,id) {
	window.opener.document.formulario.id_localidad.value = id;
	window.opener.document.formulario.localidad.value = nombre;
	window.close()
}
</script>
<link href="../libreria/estilos.css" rel="stylesheet" type="text/css">
</head>
<?PHP  
include ("../libreria/config.php");
include ("../libreria/libreria.php");
	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

	if (!isset($_POST['nombre']) && isset($_GET['nombre'])) $_POST['nombre']=$_GET['nombre'];
	if (isset($_POST['id_municipio'])) $_GET['id_municipio']=$_POST['id_municipio'];

?>
<body onLoad="Javascript: document.buscar.nombre.focus();">
<form action="<?PHP  echo $_SERVER['PHP_SELF']; ?>" name="buscar" method="post">
   <table border="0" cellpadding="2" align="center" class="tabla2">
      <tr>
	    <td colspan="2" class="primeralinea" align="center">
		  Buscador de localidades</td>
	  </tr>
      <tr class="fuente2">
	    <td width="30%" class="segundalinea" >
		  Descripci&oacute;n:</td>
		<td width="70%" class="segundalinea" >
		   <input type="text" name="nombre" maxlength="100" size="40" value="<?PHP  if (isset($_POST['nombre'])) echo $_POST['nombre'] ?>">		</td>
	  </tr >
	  <tr>
	    <td colspan="2" align="center" class="segundalinea" ><INPUT TYPE="image" style=" vertical-align:middle;  border:0 hidden "  src= "../images/buscar1.png"  border=0 hspace=7 vspace=4 alt="Buscar localidad" >
	      <img src="../images/agregar1.png" style=" vertical-align:middle;  border:0 hidden " alt="Agregar Localidad" onClick="location.href='agregar.php?id_municipio=<?PHP  echo $_GET['id_municipio'] ?>'"/>
	    
		  <input name="id_municipio" type="hidden" value="<?PHP  echo $_GET['id_municipio'] ?>"></td>
	  </tr>
  </table>  
   
   
</form>

<?PHP    
$consulta0 = "select * from cat_localidades WHERE 1";
if (isset($_POST['nombre'])){
//	if (!isset($_POST['nombre'])) $_POST['nombre']=$_GET['nombre'];
	$consulta0 .= " AND descripcion like '%".$_POST['nombre']."%'";
}
if (isset($_GET['id_municipio'])){
//	if (isset($_POST['id_municipio'])) $_GET['id_municipio']=$_POST['id_municipio'];
	$consulta0 .= " AND id_municipio = ".$_GET['id_municipio'];
}
	//echo $consulta0;
$resultado0 = mysql_query($consulta0, $conexion);
$filas=mysql_num_rows($resultado0);
$enlaces=$filas;
if (isset($_GET['numi']))
	$numi=$_GET['numi'];
if (empty($numi)) { $numi=0; }
	 $consulta0.=" ORDER BY descripcion";
	 $consulta0.=" limit $numi,10";
	 //echo $consulta0;
	 $resultado0 = mysql_query($consulta0, $conexion);
	$filas=mysql_num_rows($resultado0);
	if (empty($filas)) { print "<center>No hay localidades con esas caracter&iacute;sticas.</center><br>"; } else { ?>
 
<table align="center">
  <tr> 
    <td class="primeralinea">Descripci&oacute;n Localidad</td>
  </tr>
  <?PHP  while ($lafila=mysql_fetch_array($resultado0)) { ?>
  <tr bgcolor="#7CCEED"> 
    <td class="segundalinea"><a href="#" onClick="ponPrefijo('<?PHP  echo $lafila["descripcion"]; ?>','<?PHP  echo $lafila["id_localidad"]; ?>')"><?PHP  echo $lafila["descripcion"]; ?></a></td>
  </tr>
  <?PHP  } ?>
</table>
<?PHP  } 
  if ($enlaces>10) {
  $i=0;
  $j=1; 
  print "<center><font size=2 face='Verdana, Arial, Helvetica, sans-serif'>Páginas: ";
  while ($i<$enlaces) { ?>
      <a href="listado.php?numi=<?PHP  echo $i; if (isset($_POST['nombre'])) echo "&nombre=".$_POST['nombre']; if (isset($_GET['id_municipio'])) echo "&id_municipio=".$_GET['id_municipio']; ?>"><?PHP  echo $j; ?></a>
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