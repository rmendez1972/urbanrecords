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
	location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>';
}

function agregarPriv(){
	var params=new Object();
	params.modulo="PRIVATIVAS";
	params.accion="AGREGAR";
	
	params.unifamiliar=$("#unifamiliar").val();
	params.multifamiliar=$("#multifamiliar").val();
	params.mixto=$("#mixto").val();
	params.superficie=$("#superficie1").val();
	params.viviendas=$("#viviendas").val();
	params.anio=$("#anio").val();
	params.idsol=$("#id_solicitud").val();
	
	$.post("CondominioMaestro.php",params,function(datos){
		if(datos=="ok")
			location.reload();
		else
			parent.error(datos);
	},"html");
}

function eliminarPriv(anio, idsol, nolote){
	var params=new Object();
	params.modulo="PRIVATIVAS";
	params.accion="ELIMINAR";
	
	params.anio=anio;
	params.idsol=idsol;
	params.nolote=nolote;
	
	if(confirm("¿Está seguro que desea eliminar el lote?")){
		$.post("CondominioMaestro.php",params,function(datos){
			if(datos=="ok")
				location.reload();
			else
				parent.error(datos);
		},"html");
	}
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
    </tbody>
  </table>
  <br />
  <div>
  <div class="tituSec">UNIDADES PRIVATIVAS</div>

    <table width="98%" border="0" align="center">
  <tr>
    <td>Hab. Unifamiliar
      <input name="unifamiliar" type="text" id="unifamiliar" size="6" />
      (m<sup>2</sup>)</td>
    <td>Hab. Multifamiliar
      <input name="multifamiliar" type="text" id="multifamiliar" size="6" />
(m<sup>2</sup>)</td>
    <td>Mixto
      <input name="mixto" type="text" id="mixto" size="6" />
      (m<sup>2</sup>)</td>
    <td>Superficie
      <input name="superficie1" type="text" id="superficie1" size="6" />
(m<sup>2</sup>)</td>
    <td>Viviendas 
      <input name="viviendas" type="text" id="viviendas" size="3" /></td>
    <td><input type="button" name="button" id="button" value="Agregar" class="btnVerde" onclick="agregarPriv()" /></td>
  </tr>
</table>

  <table width="95%" border="0" class="tablaDatos" align="center">
    <tr class="tituTab">
      <td rowspan="2">Lote</td>
      <td colspan="3">Uso de suelo</td>
      <td rowspan="2">Superficie (m<sup>2</sup>)</td>
      <td rowspan="2">Viviendas</td>
      <td rowspan="2">Acciones</td>
    </tr>
    <tr style="text-align:center">
      <td>Habitacional Unifamiliar (m<sup>2</sup>)</td>
      <td>Habitacional Multifamiliar (m<sup>2</sup>)</td>
      <td>Mixto (m<sup>2</sup>)</td>
      </tr>
      <?php
	  $nlote=0;
	  $cons=Conexion::ejecutarConsulta("select * from condo_maestro_pvt where anio=? and id_solicitud=? order by no_lote asc",array($_GET["anio"],$_GET["id_solicitud"]));
	  foreach($cons as $row){
		  $nlote++;
		  echo "<tr class='regTab' style='text-align:center'><td>".$nlote."</td><td>".$row["unifamiliar"]."</td><td>".$row["multifamiliar"]."</td><td>".$row["mixto"]."</td><td>".$row["superficie"]."</td><td>".$row["viviendas"]."</td><td><img src='../images/remove.png' width='18' height='18' title='Eliminar lote' class='btnOld' onclick=\"eliminarPriv(".$_GET["anio"].",".$_GET["id_solicitud"].",".$row["no_lote"].")\" /></td></tr>";
	  }
	  ?>
  </table>
  </div>
  
  <br />

  <div>
  <div class="tituSec">ÁREAS COMUNES</div>
  <table width="700" border="0" align="center">
    <tr>
      <td>Uso de suelo 
        <input name="usoSuelo" type="text" id="usoSuelo" size="30" /></td>
      <td>Superficie
        <input name="superficie2" type="text" id="superficie2" size="6" />
(m<sup>2</sup>)</td>
      <td>No. Lotes 
        <input name="noLotes" type="text" id="noLotes" size="3" /></td>
      <td><input type="button" name="button2" id="button2" value="Agregar" class="btnVerde" onclick="agregarCom()" /></td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" class="tablaDatos">
    <tr class="tituTab">
      <td>Uso de suelo</td>
      <td>Superficie (m<sup>2</sup>)</td>
      <td>No. Lotes</td>
      <td>Acciones</td>
    </tr>
    <?
	$cons=Conexion::ejecutarConsulta("select * from condo_maestro_com where anio=? and id_solicitud=?",array($_GET["anio"],$_GET["id_solicitud"]));
	foreach($cons as $row){
		echo "<tr><td>".$row["uso_suelo"]."</td><td>".$row["superficie"]."</td><td>".$row["no_lotes"]."</td><td></td></tr>";	
	}
	?>
  </table>
  </div>
  
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