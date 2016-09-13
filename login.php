<?php

session_start();
include ("libreria/ConexionPDO.php");

Conexion::init(true);

$msg="";

if(isset($_POST["usuario"])){
    $usuario=$_POST["usuario"];
    $password=$_POST["password"];

    $cons=Conexion::ejecutarConsulta("select password,id_perfil from cat_usuario where login=? and estatus=1",array($usuario));

    foreach($cons as $row){
        if($row["password"]==md5($password)){
            $_SESSION["user_constancia"]=$usuario;
            $_SESSION["user_profile"]=$row["id_perfil"];
            header("location: index2.php");
            exit;
        }
    }

    $msg="Usuario o contraseña incorrectos";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema de Control de Constancias :.</title>

<link href="css/main.css" rel="stylesheet" type="text/css" />
<script src="libreria/jquery-1.7.js"></script>
<script>	
function notificacion(txt, img){
	$("#imgNotif").attr("src",img);
	$("#notifTxt").html(txt);
	$("#notif").slideDown();
	
	setTimeout("ocultarNotif()",5000);
}
function ocultarNotif(){
	$("#notif").slideUp();
}
function error(txt){
	notificacion(txt,"images/remove.png");
}
function aviso(txt){
	notificacion(txt,"images/aviso.png");
}
</script>
</head>

<body style="background:url(images/fondo.jpg);">

<div id="notif">
	<div id="notifImg"><img id="imgNotif" src="images/aviso.png" width="30" height="30" /></div>
    <div id="notifTxt">asdfasdfasd</div>
</div>

<div class="mainWrap" style="background-color:#FFF">
	<div id="lefts"></div>
    <div id="rights"></div>

	<div id="head"><img src="images/banner.png" width="990" border="0" /></div>
    
    <div id="menu">
    	
    </div>
    
    <div id="submenu">
        
    </div>
    
    <div style="margin:auto; position:relative; margin-top:70px; margin-bottom:70px; background:url(images/gestion.png); background-position: center; background-repeat:no-repeat; width:403px; height:249px;">
		<form method='POST' action=''>
			<div style="position:relative; top:95px; font-weight:bold">
			<table style="text-align:center" border='0' width='250px' align="center">
				<tbody>
					<tr height="35">
						<td>Usuario</td>
						<td><input type="text" name="usuario" id="usuario" /></td>
					</tr>
					<tr height="35">
						<td>Contraseña</td>
						<td><input type="password" name="password" id="password" /></td>
					</tr>
					<tr height="35">
						<td colspan='2' style="text-align:center"><input type="submit" value="Aceptar" class='btnAceptar' style="float:none" /></td>
					</tr>
				</tbody>
			</table>
			</div>
		</form>
    </div>
    
    <div id="bottom"></div>
</div>

<script>
<?php
if($msg!=""){
	echo "error('".$msg."');";
}
?>
</script>
</body>
</html>
