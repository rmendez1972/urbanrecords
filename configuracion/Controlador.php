<?PHP
// Verificar el acceso
$requiredUserLevel = array(1);
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

if($modulo=="LEGALES"){
	if($accion=="AGREGAR"){
		$legales=$_POST["legales"];
		$res=Conexion::ejecutar("insert into configuracion values ('LEGALES',?,NOW())",array($legales));
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="ELIMINAR"){
		$fecha=Conexion::ejecutarEscalar("select fecha from configuracion where campo='LEGALES' order by fecha desc",NULL);
		$res=Conexion::ejecutar("delete from configuracion where campo='LEGALES' and fecha=?",array($fecha));
		echo $res?"ok":"Error al eliminar";
	}
}
if($modulo=="SLOGAN"){
	if($accion=="AGREGAR"){
		$slogan=$_POST["slogan"];
		$res=Conexion::ejecutar("insert into configuracion values ('SLOGAN',?,NOW())",array($slogan));
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="ELIMINAR"){
		$fecha=Conexion::ejecutarEscalar("select fecha from configuracion where campo='SLOGAN' order by fecha desc",NULL);
		$res=Conexion::ejecutar("delete from configuracion where campo='SLOGAN' and fecha=?",array($fecha));
		echo $res?"ok":"Error al eliminar";
	}
}
if($modulo=="CCP"){
	if($accion=="AGREGAR"){
		$municipio=$_POST["municipio"];
		$nombre=$_POST["nombre"];
		$constancias=$_POST["constancias"];
		
		$max=Conexion::ejecutarEscalar("select max(orden) from ccps where id_municipio=? and constancia=?",array($municipio,$constancias));
		if($max==NULL)
			$max=0;
		$max++;
		
		$res=Conexion::ejecutar("insert into ccps (id_municipio,nombre,orden,constancia) values (?,?,?,?)",array($municipio,$nombre,$max,$constancias));
		echo $res?"ok":"Error al agregar";
	}
	else if($accion=="SUBIR"){
		$id=$_POST["id"];
		$ccp=Conexion::ejecutarFila("select * from ccps where id_ccp=?",array($id));
		
		$max=Conexion::ejecutarEscalar("select max(orden) from ccps where id_municipio=? and constancia=?",array($ccp["id_municipio"],$ccp["constancia"]));
		
		if($ccp["orden"]!=$max){
			$r1=Conexion::ejecutar("update ccps set orden=? where orden=? and id_municipio=? and constancia=?",array($ccp["orden"],$ccp["orden"]+1,$ccp["id_municipio"],$ccp["constancia"]));
			$r2=Conexion::ejecutar("update ccps set orden=? where id_ccp=?",array($ccp["orden"]+1,$id));
			$res=$r1&&$r2;
		}
		else
			$res=true;
		
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="BAJAR"){
		$id=$_POST["id"];
		$ccp=Conexion::ejecutarFila("select * from ccps where id_ccp=?",array($id));
		
		if($ccp["orden"]!=1){
			$r1=Conexion::ejecutar("update ccps set orden=? where orden=? and id_municipio=? and constancia=?",array($ccp["orden"],$ccp["orden"]-1,$ccp["id_municipio"],$ccp["constancia"]));
			$r2=Conexion::ejecutar("update ccps set orden=? where id_ccp=?",array($ccp["orden"]-1,$id));
			$res=$r1&&$r2;
		}
		else
			$res=true;
		
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="ELIMINAR"){
		$id=$_POST["id"];
		$res=Conexion::ejecutar("delete from ccps where id_ccp=?",array($id));
		echo $res?"ok":"Error al eliminar";
	}
}
?>