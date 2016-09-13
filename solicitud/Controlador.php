<?php
// Verificar el acceso
$requiredUserLevel = array(1,2);
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

if($modulo=="SOLICITANTES"){
	if($accion=="ELIMINAR"){
		$solid=$_POST["solicitud"];
		$anio=$_POST["anio"];
		$solicitante=$_POST["solicitante"];
		
		$res=Conexion::ejecutar("delete from solicitantes where id_solicitud=? and anio=? and id_solicitante=?",array($solid,$anio,$solicitante));
		echo $res?"ok":"Error al eliminar";
	}
}
?>