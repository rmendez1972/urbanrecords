<?PHP
// Verificar el acceso
$requiredUserLevel = array(1);
$cfgProgDir =  '../';

include("../seguridad/secure.php");
include ("../libreria/ConexionPDO.php");
Conexion::init(true);

$mun=$_GET["mun"];
$constancia=$_GET["const"];
$nombreMun=Conexion::ejecutarEscalar("select descripcion from cat_municipios where id_municipio=?",array($mun));

header('Content-Type: text/html; charset=utf-8');
?>
<table class="tablaDatos" width="450">
<thead>
  <tr class="tituTab">
    <th colspan="2">C.C.P del municipio <?PHP echo $nombreMun; ?></th>
  </tr>
  </thead>
  <tbody>
  <?
$cons=Conexion::ejecutarConsulta("select * from ccps where id_municipio=? and constancia=? order by orden asc",array($mun,$constancia),PDO::FETCH_NUM);
$cont=0;
foreach($cons as $row){
	$cont++;
	echo "<tr class='regTab'><td>".$row[2]."</td><td width='65'><img src='../images/up_arrow.png' class='imgBtnNo' border='0' width='20' title='Subir un nivel' onclick='subirCCP(".$row[0].")'><img src='../images/down_arrow.png' width='20' class='imgBtnNo' border='0' title='Bajar un nivel' onclick='bajarCCP(".$row[0].")'><img src='../images/b_drop.png' class='imgBtnNo' border='0' title='Eliminar' onclick='eliminarCCP(".$row[0].")'></td></tr>";	
}
if($cont==0){
	echo "<tr class='regTab'><td colspan='2'>No se encontraron registros</td></tr>";	
}
  ?>
  </tbody>
</table>