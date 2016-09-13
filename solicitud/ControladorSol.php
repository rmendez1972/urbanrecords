<?PHP
$requiredUserLevel = array(1,2,3,4,5,6);
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

if($modulo=="SOLICITUD"){
	if($accion=="SMOD"){
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
		$res=Conexion::ejecutar("update solicitud set soledit=1 where anio=? and id_solicitud=?",array($anio,$idsol));
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="AMOD"){
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
		$res=Conexion::ejecutar("update solicitud set editable=1, soledit=0 where anio=? and id_solicitud=?",array($anio,$idsol));
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="NMOD"){
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
		$res=Conexion::ejecutar("update solicitud set soledit=0 where anio=? and id_solicitud=?",array($anio,$idsol));
		echo $res?"ok":"Error al guardar";
	}
}
?>