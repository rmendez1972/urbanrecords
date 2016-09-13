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
	if($accion=="GUARDAR"){
		$anio=$_POST["anio"];
		$id_solicitud=$_POST["id_solicitud"];
		$columnas=$_POST["columnas"];
		$filas=$_POST["filas"];
		
		$matriz=array();
		$matriz[0]=array();
		for($i=1;$i<=$columnas;$i++){
			$matriz[0][$i-1]=$_POST["col_".$i];
		}
		
		for($i=1;$i<=$filas;$i++){
			$matriz[$i]=array();
			for($j=1;$j<=$columnas;$j++){
				$matriz[$i][$j-1]=$_POST["val_".$j."_".$i];
			}
		}
		
		$error="";
		
		//Verificar columnas
		$icol=array();
		for($i=0;$i<$columnas;$i++){
			$icol[$i]=false;
			
			for($j=1;$j<=$filas;$j++){
				if($matriz[$j][$i]!=""){
					$icol[$i]=true;
					break;	
				}
			}
			
			if($icol[$i] && $matriz[0][$i]==""){
				$error="Escriba el título de la columna ".($i+1);
				break;	
			}
		}
		
		//Verificar filas
		$ifil=array();
		for($i=1;$i<=$filas;$i++){
			$ifil[$i-1]=false;
			
			for($j=0;$j<$columnas;$j++){
				if($matriz[$i][$j]!=""){
					$ifil[$i-1]=true;
					break;
				}
			}
		}
		
		if($error==""){
			Conexion::ejecutar("delete from condo_maestro_cols where anio=? and id_solicitud=?",array($anio,$id_solicitud));
			for($i=0;$i<$columnas;$i++){
				Conexion::ejecutar("insert into condo_maestro_cols values (?,?,?,?)",array($anio,$id_solicitud,$i+1,$matriz[0][$i]));	
			}
			
			Conexion::ejecutar("delete from condo_maestro_datos where anio=? and id_solicitud=?",array($anio,$id_solicitud));
			for($i=1;$i<=$filas;$i++){
				for($j=0;$j<$columnas;$j++){
					Conexion::ejecutar("insert into condo_maestro_datos values (?,?,?,?,?)",array($anio,$id_solicitud,$j+1,$i,$matriz[$i][$j]));	
				}
			}
			
			echo "ok#";
		}
		else
			echo "no#".$error;
	}
}

if($modulo=="PRIVATIVAS"){
	if($accion=="AGREGAR"){
		$unifamiliar=$_POST["unifamiliar"];
		$multifamiliar=$_POST["multifamiliar"];
		$mixto=$_POST["mixto"];
		$superficie=$_POST["superficie"];
		$viviendas=$_POST["viviendas"];
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
		
		$max=Conexion::ejecutarEscalar("select max(no_lote) from condo_maestro_pvt where anio=? and id_solicitud=?",array($anio,$idsol));
		if($max==null)
			$max=0;
		$max++;
		
		$res=Conexion::ejecutar("insert into condo_maestro_pvt values (?,?,?,?,?,?,?,?)",array($anio,$idsol,$max,$unifamiliar,$multifamiliar,$mixto,$superficie,$viviendas));
		echo $res?"ok":"Error al guardar";
	}
	else if($accion=="ELIMINAR"){
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
		$nolote=$_POST["nolote"];
		
		$res=Conexion::ejecutar("delete from condo_maestro_pvt where anio=? and id_solicitud=? and no_lote=?",array($anio,$idsol,$nolote));
		echo $res?"ok":"Error al eliminar";
	}
}

if($modulo=="COMUNES"){
	if($accion=="AGREGAR"){
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
	}
	else if($accion=="ELIMINAR"){
		$anio=$_POST["anio"];
		$idsol=$_POST["idsol"];
	}
}
?>