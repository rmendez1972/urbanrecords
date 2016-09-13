<?PHP 

// Verificar el acceso
$requiredUserLevel = array(1);
$cfgProgDir =  '../';

header('Content-Type: text/html; charset=utf-8');

include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");

$fecha=date("Y-m-d H:i:s");

Conexion::init(true);

if(isset($_GET["switch"])){
	$activo=Conexion::ejecutarEscalar("select activo from cat_tipo_proy where id_tipo=?", array($_GET["switch"]));
	Conexion::ejecutar("update cat_tipo_proy set activo=? where id_tipo=?", array($activo==0?1:0, $_GET["switch"]));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script>
function eliminarTipo(id){
	if(confirm("¿Está seguro que desea eliminar?"))
		location.href="tipoproyectos.php?del="+id;
}
</script>
</head>

<body>

<div class="contFrame">
	<div class="tituSec">Tipos de proyecto</div>
	<div class="menuSec"></div>
	<table class="tablaDatos" width="70%" align="center">
		<thead>
			<tr class="tituTab">
				<th>Descripción</th>
				<th width="100">Abreviatura</th>
				<th width="100">Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?
			$tipos=Conexion::ejecutarConsulta("select * from cat_tipo_proy order by descripcion asc",null);
			foreach($tipos as $tipo){
				$activo=$tipo["activo"];
				$arr=array("Activar tipo de proyecto","Desactivar tipo de proyecto");
				echo "<tr><td>".$tipo['descripcion']."</td><td>".$tipo['abreviatura']."</td><td><a href='requisitos.php?tipo=".$tipo['id_tipo']."'><img src='../images/resumen.png' height='20' title='Ver requisitos' /></a> <a href='tipoproyectos.php?switch=".$tipo['id_tipo']."'><img src='../images/switch".$activo.".png' height='20' title='".$arr[$activo]."' /></a> <img src='../images/trash.png' height='20' title='Eliminar tipo de proyecto' style='cursor:pointer' onclick='eliminarTipo(".$tipo['id_tipo'].")' /></td></tr>";
			}
			?>
		</tbody>
	</table>
</div>

</body>
</html>
