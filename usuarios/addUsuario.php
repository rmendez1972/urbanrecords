<?php
// Verificar el acceso
$requiredUserLevel = array(1);
$cfgProgDir =  '../';

include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");

Conexion::init(true);

if(isset($_GET["usr"])){
	$usuario=Conexion::ejecutarFila("select * from cat_usuario where login=?",array($_GET["usr"]));
	$edit=true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<link href="../css/popup.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script>
function validarNuevo(){
	if($("#addPassword").val() != $("#addPassword2").val()){
		alert("Las contraseñas no coinciden");
		return;	
	}
	if($("#addUsuario").val().length<6 || $("#addPassword").val().length<6){
		alert("El usuario y contraseña deben tener 6 caracteres como mínimo");
		return;
	}
	if($("#addNombre").val().length>0 && $("#addIniciales").val().length>0 && $("#addTipo").val()!=-1){
		var params=new Object();
		params.modulo="USUARIOS";
		params.accion="AGREGAR";
		
		params.nombre=$("#addNombre").val();
		params.iniciales=$("#addIniciales").val();
		params.email=$("#addEmail").val();
		params.usuario=$("#addUsuario").val();
		params.password=$("#addPassword").val();
		params.tipo=$("#addTipo").val();
		params.estatus=0;
		if($("#estatus_0").is(":checked"))
			params.estatus=1;
		
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				parent.cargarUltima();
				parent.aviso("El usuario ha sido registrado");
				parent.cerrarShadowbox();
			}
			else
				parent.error(datos);
		},"html");
	}
	else{
		alert("Llene todos los campos marcados con *");	
	}
}

function cambioPW(){
	if($("#cambioPass").is(":checked"))
		$("#divPass").fadeIn();
	else
		$("#divPass").fadeOut();	
}

function validarEdit(){
	var cpass=$("#cambioPass").is(":checked");
	
	if($("#modPassword").val() != $("#modPassword2").val() && cpass){
		alert("Las contraseñas no coinciden");
		return;	
	}
	if($("#modPassword").val().length<6 && cpass){
		alert("El usuario y contraseña deben tener 6 caracteres como mínimo");
		return;
	}
	if($("#modNombre").val().length>0 && $("#modIniciales").val().length>0 && $("#modTipo").val()!=-1){
		var params=new Object();
		params.modulo="USUARIOS";
		params.accion="EDITAR";
		
		params.nombre=$("#modNombre").val();
		params.iniciales=$("#modIniciales").val();
		params.email=$("#modEmail").val();
		params.usuario=$("#modUsuario").val();
		
		if(cpass){
			params.password=$("#modPassword").val();
			params.oldpass=$("#modPassOld").val();
		}
		
		params.tipo=$("#modTipo").val();
		params.estatus=0;
		if($("#modestatus_0").is(":checked"))
			params.estatus=1;
		
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				parent.cargarUltima();
				parent.aviso("Los datos han sido actualizados");
				parent.cerrarShadowbox();
			}
			else
				parent.error(datos);
		},"html");
	}
	else{
		alert("Llene todos los campos marcados con *");	
	}
}
</script>
</head>

<body style="background-color:#FFF">
<?php
if(!$edit){
?>
<form id="formAdd" name="formAdd" method="post" action="">
  <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
    <thead>
    <tr class="tituTab">
      <th colspan="2" scope="col">Ingrese los siguientes datos</th>
      </tr>
    </thead>
    
    <tbody>
    <tr class="regTab">
      <td width="150">Nombre completo *</td>
      <td><input class="regMax" type="text" name="addNombre" id="addNombre" /></td>
    </tr>
    <tr class="regTab">
      <td>Iniciales *</td>
      <td><input name="addIniciales" type="text" id="addIniciales" size="5" maxlength="5" /></td>
    </tr>
    <tr class="regTab">
      <td>Correo electrónico</td>
      <td><input class="regMax" name="addEmail" type="text" id="addEmail" /></td>
    </tr>
    <tr class="regTab">
      <td>Usuario (login) *</td>
      <td><input type="text" name="addUsuario" id="addUsuario" /> <label>(6 caracteres mínimo)</label></td>
    </tr>
    <tr class="regTab">
      <td>Contraseña *</td>
      <td><input type="password" name="addPassword" id="addPassword" /> <label>(6 caracteres mínimo)</label></td>
    </tr>
    <tr class="regTab">
      <td>Reescriba contraseña *</td>
      <td><input type="password" name="addPassword2" id="addPassword2" /> <label>(6 caracteres mínimo)</label></td>
    </tr>
    <tr class="regTab">
      <td>Tipo de usuario *</td>
      <td><select name="addTipo" id="addTipo">
      <option value="-1">-- Seleccione uno --</option>
      <?php
$cons=Conexion::ejecutarConsulta("select * from cat_perfiles order by descripcion asc");
foreach($cons as $row){
	echo "<option value='".$row["id_perfil"]."'>".$row["descripcion"]."</option>";
}
	  ?>
      </select></td>
    </tr>
    <tr class="regTab">
      <td valign="top">Estatus *</td>
      <td>
        <label>
          <input name="estatus" type="radio" id="estatus_0" value="1" checked="checked" />
          Activo</label>
        <br />
        <label>
          <input type="radio" name="estatus" value="0" id="estatus_1" />
          Inactivo</label>
	</td>
    </tr>
    </tbody>
  </table>
  
  <div class="botones" style="display:table; margin:auto; margin-top:15px;"><div class="btnAceptar" onclick="validarNuevo()">Aceptar</div><div class="btnCancelar" onclick="parent.cerrarShadowbox()">Cancelar</div></div>
</form>
<?php
}
else{
?>

<form id="formEdit" name="formEdit" method="post" action="">
<table width="480" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
    <thead>
    <tr class="tituTab">
      <th colspan="2" scope="col"><input name="modUsuario" type="hidden" id="modUsuario" value="<?php echo $usuario["login"]; ?>" />
        Ingrese los siguientes datos</th>
      </tr>
    </thead>
    
    <tbody>
    <tr class="regTab">
      <td width="150">Nombre completo *</td>
      <td><input name="modNombre" type="text" class="regMax" id="modNombre" value="<?php echo $usuario["nombre"]; ?>" /></td>
    </tr>
    <tr class="regTab">
      <td>Iniciales *</td>
      <td><input name="modIniciales" type="text" id="modIniciales" value="<?php echo $usuario["iniciales"]; ?>" size="5" maxlength="5" /></td>
    </tr>
    <tr class="regTab">
      <td>Correo electrónico</td>
      <td><input class="regMax" name="modEmail" type="text" id="modEmail" value="<?php echo $usuario["email"]; ?>" /></td>
    </tr>
    <tr class="regTab">
      <td>Usuario (login)</td>
      <td><?php
echo $usuario["login"];
	  ?></td>
    </tr>
    <tr class="regTab">
      <td colspan="2" align="center">Cambiar contraseña 
        <input name="cambioPass" type="checkbox" id="cambioPass" value="1" onchange="cambioPW()" />
        <div id="divPass" style="display:none">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr class="regTab">
              <td>Contraseña nueva*</td>
              <td><input type="password" name="modPassword" id="modPassword" />
                <label>(6 caracteres mínimo)</label></td>
            </tr>
            <tr class="regTab">
              <td>Reescriba contraseña *</td>
              <td><input type="password" name="modPassword2" id="modPassword2" />
                <label>(6 caracteres mínimo)</label></td>
            </tr>
            <tr style="display:none">
              <td scope="col">Contraseña anterior</td>
              <td scope="col"><input type="password" name="modPassOld" id="modPassOld" /></td>
            </tr>
            </tbody>
          </table>
        </div></td>
      </tr>
    <tr class="regTab">
      <td>Tipo de usuario *</td>
      <td><select name="modTipo" id="modTipo">
        <option value="-1">-- Seleccione uno --</option>
        <?php
$cons=Conexion::ejecutarConsulta("select * from cat_perfiles order by descripcion asc");
foreach($cons as $row){
	echo "<option value='".$row["id_perfil"]."' ".($row["id_perfil"]==$usuario["id_perfil"]?"selected":"").">".$row["descripcion"]."</option>";
}
	  ?>
        </select></td>
    </tr>
    <tr class="regTab">
      <td valign="top">Estatus *</td>
      <td>
        <label>
          <input name="modestatus" type="radio" id="modestatus_0" value="1" checked="checked" />
          Activo</label>
        <br />
        <label>
          <input type="radio" name="modestatus" value="0" id="modestatus_1" />
          Inactivo</label>
	</td>
    </tr>
    </tbody>
  </table>
  
  <div class="botones" style="display:table; margin:auto; margin-top:15px;"><div class="btnAceptar" onclick="validarEdit()">Aceptar</div><div class="btnCancelar" onclick="parent.cerrarShadowbox()">Cancelar</div></div>
</form>
<?php
}
?>
</body>
</html>