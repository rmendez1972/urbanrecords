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

if($modulo=="USUARIOS"){
	if($accion=="AGREGAR"){
		$nombre=$_POST["nombre"];
		$iniciales=$_POST["iniciales"];
		$email=$_POST["email"];
		$usuario=$_POST["usuario"];
		$contrasenia=md5($_POST["password"]);
		$tipo=$_POST["tipo"];
		$estatus=$_POST["estatus"];
		
		$res=Conexion::ejecutar("insert into cat_usuario values (UCASE(?),?,?,?,?,0,?,?)",array($nombre,$usuario,$contrasenia,$email,$tipo,$estatus,$iniciales));
		echo $res?"ok":"Error al agregar usuario. Verifique que el nombre de usuario no exista";
	}
	else if($accion=="EDITAR"){
		$nombre=$_POST["nombre"];
		$iniciales=$_POST["iniciales"];
		$email=$_POST["email"];
		$usuario=$_POST["usuario"];
		$tipo=$_POST["tipo"];
		$estatus=$_POST["estatus"];
		
		$flg1=true;
		
		if(isset($_POST["password"])){
			$contrasenia=md5($_POST["password"]);
			
			/*
			$oldpass=md5($_POST["oldpass"]);
			
			$total=Conexion::ejecutarEscalar("select count(*) from cat_usuario where login=? and password=?",array($usuario,$oldpass));
			if($total==0){
				echo "La contraseña anterior no es correcta";
				exit;
			}
			else
			*/
				$flg1=Conexion::ejecutar("update cat_usuario set password=? where login=?",array($contrasenia,$usuario));
		}
		
		$flg2=Conexion::ejecutar("update cat_usuario set nombre=UCASE(?),iniciales=?,email=?,id_perfil=?,estatus=? where login=?",array($nombre,$iniciales,$email,$tipo,$estatus,$usuario));
		
		echo $flg1 && $flg2?"ok":"Error al guardar los datos";
	}
	else if($accion=="ELIMINAR"){
		$usuario=$_POST["usuario"];
		
		$res=Conexion::ejecutar("delete from cat_usuario where login=?",array($usuario));
		echo $res?"ok":"Error al eliminar";
	}
}
?>