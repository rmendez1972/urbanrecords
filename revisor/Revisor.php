<?PHP
// Verificar el acceso
$requiredUserLevel = array(1,3);
$cfgProgDir =  '../';

include("../seguridad/secure.php");
include ("../libreria/ConexionPDO.php");

$modulo=$_POST["modulo"];
if(!isset($modulo))
	exit;
$accion=$_POST["accion"];
if(!isset($accion))
	exit;

Conexion::init(true);

if($modulo=="FRACCIONAMIENTO"){
	if($accion=="AGREGAR_AREA"){
		$area=$_POST["area"];
		
		$max=Conexion::ejecutarEscalar("select max(id_area) from cat_desglose_areas",null);
		if($max==null)
			$max=0;
		$max++;
		
		$res=Conexion::ejecutar("insert into cat_desglose_areas values (?,?,1)",array($max,$area));
		echo $res?"ok#".$max:"no#Error al guardar";
	}
}
?>