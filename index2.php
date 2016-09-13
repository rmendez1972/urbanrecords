<?php
$requiredUserLevel = array(1,2,3,4,5,6,7);
$cfgProgDir =  '';
include("seguridad/secure.php");
include ("libreria/ConexionPDO.php");

Conexion::init(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: Sistema de Control de Constancias :.</title>

<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="shadowbox/shadowbox.css" rel="stylesheet" type="text/css" />
<script src="libreria/jquery-1.7.js"></script>
<script src="shadowbox/shadowbox.js"></script>
<script>
var lasturl="";

function menu(num){
	var i;
	for(i=0;i<=3;i++){
		try{
			$("#submnu"+i).hide();
		}catch(ex){}
	}
	
	$("#submnu"+num).slideDown();
	
	var pos=$("#btn"+num).position();
	var wid=$("#btn"+num).width();
	$("#mnupick").show();
	var pick=document.getElementById("mnupick");
	pick.style.left=(pos.left+(wid+15)/2)+"px";
}
function cargarUltima(){
	cargar(lasturl);
}
function cargar(url){
	lasturl=url;
	document.getElementById("ifcont").height=400;
	document.getElementById("ifcont").src=url;
}
function autorizaciones(){
	cargar('solicitud/validar.php');	
}
function setFH(){
	document.getElementById("ifcont").height=400;
}
function ajustar(altura){
	document.getElementById("ifcont").height=altura;
}
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

Shadowbox.init({
    modal: true,
	overlayOpacity: '0.8',
	displayNav: true
});

function abrirShadowbox(content, player, title, wid, hei){
    Shadowbox.open({
        content:    content,
        player:     player,
        title:      title,
		width:      wid,
		height:     hei,
    });
}

function cerrarShadowbox(){
	Shadowbox.close();	
}

timerup=0;
function inicializarind(){
	setInterval("timerind()",1000);
}
function timerind(){
	timerup++;
	if(timerup==60){
		timerup=0;
		
		$.post("refresh.php",null,function(datos){
			
		},"html");
	}
}
</script>
</head>

<body style="background:url(images/fondo.jpg);" onload="<?php echo ($userLevel==4?"autorizaciones();":"")."inicializarind();"; ?>">

<div id="notif">
	<div id="notifImg"><img id="imgNotif" src="images/aviso.png" width="30" height="30" /></div>
    <div id="notifTxt">asdfasdfasd</div>
</div>

<div class="mainWrap" style="background-color:#FFF">
	<div id="lefts"></div>
    <div id="rights"></div>

	<div id="head"><img src="images/banner.png" width="990" border="0" /></div>
    
    <div id="menu">
    	<div id="mnupick"><img src="images/mnupick.png" height="12" /></div>
        
    	<div class="btnMenu" onclick="location.href='index2.php'">INICIO</div>
        <div id="btn0" class="btnMenu" onclick="menu(0)">CONSTANCIAS</div>
        <div id="btn1" class="btnMenu" onclick="menu(1)">REPORTES</div>
        <?php if($userLevel==1){ ?>
        <div id="btn2" class="btnMenu" onclick="menu(2)">ADMINISTRACIÓN</div>
        <?php } ?>
        <div id="btn3" class="btnMenu" onclick="menu(3)">MI CUENTA</div>
        <div class="btnMenu" onclick="location.href='logout.php'">CERRAR SESIÓN</div>
    </div>
    
    <div id="submenu">
        <div id="submnu0" class="submenuC">
        	<?php if ($userLevel==1 || $userLevel==2) { ?>
        	<div class="btnSMenu" onclick="cargar('solicitud/nuevo.php')">NUEVA SOLICITUD</div>
            <?php } ?>
            <div class="btnSMenu" onclick="cargar('solicitud/seleccionarS.php')">SEGUIMIENTOS</div>
            <?php if ($userLevel==1 || $userLevel==4) { ?>
            <div class="btnSMenu" onclick="cargar('solicitud/validar.php')">AUTORIZAR</div>
            <?php } ?>
        </div>
        
        <div id="submnu1" class="submenuC">
        	<div class="btnSMenu" onclick="cargar('reportes/seleccionarn.php')">CONSTANCIAS</div>
            <div class="btnSMenu" onclick="cargar('reportes/repgraficos.php')">GRÁFICOS</div>
        </div>
        
        <?php if ($userLevel==1) { ?>
        <div id="submnu2" class="submenuC">
        	<div class="btnSMenu" onclick="cargar('usuarios/usuarios.php')">USUARIOS</div>
            <div class="btnSMenu" onclick="cargar('configuracion/configuracion.php')">CONFIGURACIÓN</div>
            <div class="btnSMenu" onclick="cargar('configuracion/tipoproyectos.php')">TIPOS DE PROYECTO</div>
            <div class="btnSMenu" onclick="cargar('configuracion/list_requisitos.php')">REQUISITOS</div>
        </div>
        <?php } ?>
        
        <div id="submnu3" class="submenuC">
        <div class="btnSMenu" onclick="cargar('usuarios/cambiopass.php')">CAMBIAR CONTRASEÑA</div>
        </div>
        
        <div id="datosusr" class="datusr">
        <?php
		$consulta= "SELECT * FROM cat_usuario u, cat_perfiles p where u.id_perfil=p.id_perfil AND login=?";
		$data=Conexion::ejecutarFila($consulta,array($login));
		echo "USUARIO: ".$data['nombre']."<br/>PERFIL: ".$data['descripcion'];
		?>
        </div>
    </div>
    
    <div>
    <iframe id="ifcont" src="libreria/principal.php" width="100%" height="400px" frameborder="0" marginheight="0" marginwidth="0"></iframe>
    </div>
    
    <div id="bottom" style="text-align:right">
		<div style="padding:4px"><a href="ManualDeUsuario.pdf" target="_blank" style="color:#FFF">Manual de usuario</a></div>
    </div>
</div>

</body>
</html>
