<?PHP
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

if($modulo=="CONSTANCIAS"){
	if($accion=="AGREGAR_ORDEN"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$id_tipo_pago=$_POST["id_tipo_pago"];
		$numero=$_POST["oficio"];
		$derechos=$_POST["derechos"];
		$cantidadLetras=$_POST["cantidadLetras"];
		
		$max=Conexion::ejecutarEscalar("select max(id_orden) from constancias_pagos where anio=? and id_tipo_pago=?",array($anio,$id_tipo_pago));
		if($max==NULL || $max=="")
			$max=0;
		$max++;
		
		$res=Conexion::ejecutar("insert into constancias_pagos (anio, id_solicitud, id_tipo_pago, id_orden, numero, municipio, derechos, cantidadLetras) values (?,?,?,?,?,?,?,?)",array($anio,$id_solicitud,$id_tipo_pago,$max,$numero,"I",$derechos,$cantidadLetras));
		
		echo $res?"ok":"Error al agregar. Verifique que los datos sean correctos y/o que el tipo de orden no se encuentre repetido.";
	}
	
	else if($accion=="ACTUALIZAR_ORDEN"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$id_tipo_pago=$_POST["id_tipo_pago"];
		$id_orden=$_POST["id_orden"];
		$numero=$_POST["oficio"];
		$derechos=$_POST["derechos"];
		$cantidadLetras=$_POST["cantidadLetras"];
		
		$res=Conexion::ejecutar("update constancias_pagos set numero=?, derechos=?, cantidadLetras=? where anio=? and id_solicitud=? and id_tipo_pago=? and id_orden=?",array($numero,$derechos,$cantidadLetras,$anio,$id_solicitud,$id_tipo_pago,$id_orden));
		
		echo $res?"ok":"Error al guardar. Verifique que los datos sean correctos.";
	}
	
	else if($accion=="ELIMINAR_ORDEN"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$id_tipo_pago=$_POST["id_tipo_pago"];
		$id_orden=$_POST["id_orden"];
		
		$res=Conexion::ejecutar("delete from constancias_pagos where anio=? and id_solicitud=? and id_tipo_pago=? and id_orden=?",array($anio,$id_solicitud,$id_tipo_pago,$id_orden));
		
		echo $res?"ok":"Error al eliminar. Intente de nuevo";
	}
	
	else if($accion=="ACTUALIZAR_SEGUIMIENTO"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$fecha=$_POST["fecha"];
		$observaciones=$_POST["observaciones"];
		
		$res=Conexion::ejecutar("UPDATE seguimiento SET fecha=?, observaciones=? WHERE anio=? AND id_solicitud=? AND id_seguimiento=4",array($fecha,$observaciones,$anio,$id_solicitud));
		
		echo $res?"ok":"Error al actualizar. Intente de nuevo";
	}
}

?>
