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

if($modulo=="CONDOMINIO"){
	if($accion=="AGREGAR_DESCRIPCION"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$lotes=$_POST["lotes"];
			
		$cuenta=Conexion::ejecutarEscalar("select count(*) from detalles_condominio where anio=? and id_solicitud=?",array($anio,$id_solicitud));
		if($cuenta==0){
			$res1=Conexion::ejecutar("insert into detalles_condominio values (?,?,?)",array($anio,$id_solicitud,$lotes));
			$res2=true;
			$cons=Conexion::ejecutarConsulta("select * from cat_condominios order by id_condominio asc",null);
			foreach($cons as $row){
				if($res1)
					$res2=$res2 && Conexion::ejecutar("insert into descripcion_condominio values (?,?,?,?)",array($id_solicitud,$anio,$row["id_condominio"],$_POST["condesc_".$row["id_condominio"]]));	
			}
			
			echo $res1&&$res2?"ok":"Error. Verifique que los datos sean correctos";
		}
		else
			$accion="MODIFICAR_DESCRIPCION";
	}
	
	if($accion=="MODIFICAR_DESCRIPCION"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$lotes=$_POST["lotes"];
		
		$res1=Conexion::ejecutar("update detalles_condominio set lotes=? where anio=? and id_solicitud=?",array($lotes,$anio,$id_solicitud));
		$res2=true;
		$cons=Conexion::ejecutarConsulta("select * from cat_condominios order by id_condominio asc",null);
		foreach($cons as $row){
			if($res1){
				$res2=$res2 && Conexion::ejecutar("update descripcion_condominio set descripcion=? where id_solicitud=? and anio=? and id_condominio=?",array($_POST["condesc_".$row["id_condominio"]],$id_solicitud,$anio,$row["id_condominio"]));	
			}
		}
		
		echo $res1&&$res2?"ok":"Error. Verifique que los datos sean correctos";
	}
}
?>
