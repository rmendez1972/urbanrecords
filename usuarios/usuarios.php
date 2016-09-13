<?php
// Verificar el acceso
$requiredUserLevel = array(1);
$cfgProgDir =  '../';

include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");

Conexion::init(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script>
function agregar(){
	parent.abrirShadowbox("usuarios/addUsuario.php","iframe","Agregar usuario",600,445);
}
function editar(usr){
	parent.abrirShadowbox("usuarios/addUsuario.php?usr="+usr,"iframe","Agregar usuario",600,505);
}
function eliminar(usr){
	var params=new Object();
	params.modulo="USUARIOS";
	params.accion="ELIMINAR";
	params.usuario=usr;
	
	if(confirm("¿Está seguro que desea eliminar?")){
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("El usuario ha sido eliminado");
				location.href="usuarios.php?rd="+Math.random();
			}
			else
				parent.error(datos);
		},"html");
	}
}
</script>
</head>

<body>

<div class="contFrame">

<div class="tituSec">Usuarios del sistema</div>

<div class="menuFrame">
	<div style="float:left; font-weight:bold; padding:10px;">Acciones: </div>
	<div class="btnFrame" title="Agregar usuario" onclick="agregar()"><img src="../images/agregar.png" width="16" height="16" /> Agregar</div>
</div>

<div style="text-align:center">
<table class="tablaDatos" align="center" width="100%">
	<thead>
    	<tr class="tituTab">
        	<th>Usuario</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
<?php
$usuarios=Conexion::ejecutarConsulta("select U.nombre,U.login,P.descripcion from cat_usuario U inner join cat_perfiles P on U.id_perfil=P.id_perfil order by U.login asc",NULL,PDO::FETCH_NUM);

foreach($usuarios as $usuario){
	echo "<tr class='regTab'>
	<td>".$usuario[1]."</td>
	<td>".$usuario[0]."</td>
	<td>".$usuario[2]."</td>
	<td><img src='../images/edit.png' width='22' height='22' class='imgBtnNo' title='Editar usuario' onclick=\"editar('".$usuario[1]."')\" /><img src='../images/remove.png' width='22' height='22' class='imgBtnNo' title='Eliminar usuario' onclick=\"eliminar('".$usuario[1]."')\" /></td>
	</tr>";	
}
?>
    </tbody>
</table>
</div>

</div>

</body>
</html>