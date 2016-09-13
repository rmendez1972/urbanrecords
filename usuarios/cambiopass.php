<?PHP 

// Verificar el acceso
$requiredUserLevel = array(1,2,3,4,5,6,7);
$cfgProgDir =  '../';

header('Content-Type: text/html; charset=utf-8');

include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");

$fecha=date("Y-m-d H:i:s");

Conexion::init(true);

$pass1=$pass2=$pass3=$msg="";

if(isset($_POST["anterior"])){
	$pass1=$_POST["anterior"];
	$pass2=$_POST["nueva"];
	$pass3=$_POST["nueva2"];
	
	$password=Conexion::ejecutarEscalar("select password from cat_usuario where login=?",array($login));
	
	$success=false;
	
	if($password==md5($pass1)){
		$res=Conexion::ejecutar("update cat_usuario set password=? where login=?",array(md5($pass2),$login));
		if($res){
			$msg="Su contraseña ha sido cambiada";
			$success=true;
		}
		else
			$msg="Error al guardar los datos";
	}
	else
		$msg="La contraseña es incorrecta. Intente de nuevo";
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
<?PHP
if($msg!=""){
	if($success)
		echo "parent.aviso('".$msg."');";
	else
		echo "parent.error('".$msg."');";
}
?>
	
function verificarPass(){
	if($("#nueva").val()==$("#nueva2").val()){
		document.getElementById("formpass").submit();
	}
	else{
		parent.error("Las contraseñas no coinciden");
	}
}
</script>
<body>

<form method="POST" id="formpass" action="">
<table class="tablaDatos" width="350px" style="margin-top:30px" align="center">
	<thead>
		<tr class="tituTab">
			<th colspan="2">Cambiar contraseña</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Contraseña anterior</td>
			<td><input type="password" id="anterior" name="anterior" value="<? echo $pass1; ?>" /></td>
		</tr>
		<tr>
			<td>Contraseña nueva</td>
			<td><input type="password" id="nueva" name="nueva" value="<? echo $pass2; ?>" /></td>
		</tr>
		<tr>
			<td>Repetir contraseña</td>
			<td><input type="password" id="nueva2" name="nueva2" value="<? echo $pass3; ?>" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center"><input class="btnAceptar" type="button" value="Aceptar" onclick="verificarPass()" style="float:none" /></td>
		</tr>
	</tbody>
</table>
</form>

</body>
</html>
