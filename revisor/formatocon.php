<?php
$requiredUserLevel = array(1,3);
$cfgProgDir =  '../';
include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
Conexion::init(true);

if(isset($_GET["id_solicitud"]) && isset($_GET["anio"])){
	$id_solicitud=$_GET["id_solicitud"];
	$anio=$_GET["anio"];
	
	$datos=Conexion::ejecutarFila("select * from solicitud where id_solicitud=? and anio=?",array($id_solicitud, $anio));
}
else
	exit;

$lotes=1;
if(isset($_GET["mod"])){
	$lotes=Conexion::ejecutarEscalar("select lotes from detalles_condominio where id_solicitud=? and anio=?",array($id_solicitud,$anio));	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script>
function guardar(){
	if($.isNumeric($("#lotes").val()) && $("#condesc_1").val().length>0 && $("#condesc_2").val().length>0){
		$.post("Controlador.php",$("#formCon").serialize(),function(datos){
			if(datos=="ok"){
				parent.aviso("Datos guardados");
				location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>';
			}
			else
				parent.error(datos);
		},"html");
	}
	else
		parent.error("Error. Llene correctamente los campos marcados con *");
}
</script>
</head>

<body>
<form id="formCon" name="formCon" method="post" action="">
  <input name="anio" type="hidden" id="anio" value="<?php echo $_GET["anio"]; ?>" />
  <input name="id_solicitud" type="hidden" id="id_solicitud" value="<?php echo $_GET["id_solicitud"]; ?>" />
  <input name="modulo" type="hidden" id="modulo" value="CONDOMINIO" />
  <input name="accion" type="hidden" id="accion" value="<?PHP echo isset($_GET["mod"])?"MODIFICAR_DESCRIPCION":"AGREGAR_DESCRIPCION"; ?>" />
  <br />
  <table width="80%" class="tablaDatos" align="center">
  <thead>
    <tr class="tituTab">
      <th colspan="4" align="center">Proyecto</th>
    </tr>
    </thead>
    
    <tbody>
    <tr >
      <td align="center"> Nombre del proyecto</td>
      <td colspan="3"><?PHP  echo $datos['nombre_proyecto']; ?>
        <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" /></td>
    </tr>
    <tr >
      <td align="center">Ubicaci&oacute;n</td>
      <td colspan="3"><?PHP  echo $datos['direccion']; ?></td>
    </tr>
    <tr >
      <td align="center"  class="segundalinea">Superficie</td>
      <td colspan="3"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>
        m<sup>2</sup></td>
    </tr>
    <tr >
      <td align="center"  class="segundalinea">Propietario</td>
      <td colspan="3"  class="segundalinea"><?PHP  echo $datos['propietario']; ?></td>
    </tr>
    <tr >
      <td align="center"  class="segundalinea">Nº de Lotes *</td>
      <td colspan="3"  class="segundalinea"><input name="lotes" type="text" id="lotes" size="4" maxlength="3" value="<?PHP echo $lotes; ?>" /></td>
    </tr>
    </tbody>
  </table>
  <br />
  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
    <thead>
    <tr class="tituTab">
      <th scope="col">Descripción</th>
    </tr>
    </thead>
    <tbody>
    <?php
$cons=Conexion::ejecutarConsulta("select * from cat_condominios order by id_condominio asc",null,PDO::FETCH_NUM);
foreach($cons as $row){
	$valor=Conexion::ejecutarEscalar("select descripcion from descripcion_condominio where id_solicitud=? and anio=? and id_condominio=?",array($id_solicitud, $anio, $row[0]));
	echo "<tr><td>".$row[1].($row[0]<3?" *":"")."</td></tr>";
	echo "<tr><td><textarea id='condesc_".$row[0]."' name='condesc_".$row[0]."' cols='110' rows='3'>$valor</textarea></td></tr>";
}
	?>
    </tbody>
  </table>
  <br />
  <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td align="center"><img src="../images/continuar1.png" alt="Capturar documentación" class="btnOld" onclick="guardar()" /></td>
      <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>'" /></td>
    </tr>
  </table>
</form>
</body>
</html>