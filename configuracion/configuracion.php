<?php
// Verificar el acceso
$requiredUserLevel = array(1);
$cfgProgDir =  '../';

header('Content-Type: text/html; charset=utf-8');


include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");

$fecha=date("Y-m-d H:i:s");

Conexion::init(true);

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
function guardarLegales(){
	var params=new Object();
	params.modulo="LEGALES";
	params.accion="AGREGAR";
	params.legales=$("#legales").val();
	
	$.post("Controlador.php",params,function(datos){
		if(datos=="ok")
			parent.aviso("Datos guardados");
		else
			parent.error(datos);
	},"html");
}
function eliminarLegales(){
	var params=new Object();
	params.modulo="LEGALES";
	params.accion="ELIMINAR";
	
	if(confirm("¿Está seguro que desea eliminar?")){
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Datos eliminados");
				location.href="configuracion.php?rd="+Math.random();
			}
			else
				parent.error(datos);
		},"html");
	}
}
function guardarSlogan(){
	var params=new Object();
	params.modulo="SLOGAN";
	params.accion="AGREGAR";
	params.slogan=$("#slogan").val();
	
	$.post("Controlador.php",params,function(datos){
		if(datos=="ok")
			parent.aviso("Datos guardados");
		else
			parent.error(datos);
	},"html");
}
function eliminarSlogan(){
	var params=new Object();
	params.modulo="SLOGAN";
	params.accion="ELIMINAR";
	
	if(confirm("¿Está seguro que desea eliminar?")){
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Datos eliminados");
				location.href="configuracion.php?rd="+Math.random();
			}
			else
				parent.error(datos);
		},"html");
	}
}

var munActual=-1;
function guardarCCP(){
	var params=new Object();
	params.modulo="CCP";
	params.accion="AGREGAR";
	params.municipio=munActual;
	params.nombre=$("#nombrePersona").val();
	params.constancias=$("#constancias").val();
	
	if(params.nombre=="")
		parent.error("Escriba el nombre de la persona");
	else{
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				cancelarCCP();
				cambiarMunicipio(munActual);
			}
			else
				parent.error(datos);
		},"html");
	}
}
function agregarCCP(){
	$("#nombrePersona").val("");
	$("#formCCP").fadeIn();
	document.getElementById("nombrePersona").focus();
}
function cancelarCCP(){
	$("#formCCP").fadeOut();
}
function cambiarMunicipio(mun){
	munActual=mun;
	
	if(munActual==-1)
		return;
		
	$("#addCCP").fadeIn();
	
	$.get("ccps.php?mun="+mun+"&const="+$("#constancias").val(),null,function(datos){
		$("#divMunicipios").html(datos);
		$("#divMunicipios").fadeIn();
		ajustar();
	},"html");
}
function subirCCP(id){
	var params=new Object();
	params.modulo="CCP";
	params.accion="BAJAR";
	params.id=id;
	
	$.post("Controlador.php",params,function(datos){
		if(datos=="ok")
			cambiarMunicipio(munActual);
		else
			parent.error(datos);
	},"html");
}
function bajarCCP(id){
	var params=new Object();
	params.modulo="CCP";
	params.accion="SUBIR";
	params.id=id;
	
	$.post("Controlador.php",params,function(datos){
		if(datos=="ok")
			cambiarMunicipio(munActual);
		else
			parent.error(datos);
	},"html");
}
function eliminarCCP(id){
	var params=new Object();
	params.modulo="CCP";
	params.accion="ELIMINAR";
	params.id=id;
	
	if(confirm("¿Está seguro que desea eliminar?")){
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok")
				cambiarMunicipio(munActual);
			else
				parent.error(datos);
		},"html");
	}
}
</script>
</head>

<body>
<div class="contFrame">
<form id="frmconfig" name="frmconfig" method="post" action="">
<div class="tituSec">Configuración del sistema</div>
<table class="tablaDatos" width="100%">
	<thead>
  <tr class="tituTab">
    <th width="250">Variable</th>
    <th>Valor</th>
    <th width="200">Acciones</th>
  </tr>
  	</thead>
    <tbody>
  <tr class="regTab">
    <td valign="top">Aspectos legales</td>
    <td><textarea class="regMax" name="legales" id="legales" cols="45" rows="5"><?php echo Utilidades::obtenerConfiguracion("LEGALES",$fecha);  ?></textarea></td>
    <td valign="top">
    <div class="imgBtn" onclick="guardarLegales()"><img src="../images/save.png" width="25" height="25" border="0" title="Guardar" /></div>
    <div class="imgBtn" onclick="eliminarLegales()"><img src="../images/trash.png" width="25" height="25" border="0" title="Eliminar" /></div>
    </td>
  </tr>
  <tr class="regTab">
    <td>Slogan</td>
    <td><input class="regMax" name="slogan" type="text" id="slogan" value="<?php echo Utilidades::obtenerConfiguracion("SLOGAN",$fecha);  ?>" /></td>
    <td>
    <div class="imgBtn" onclick="guardarSlogan()"><img src="../images/save.png" width="25" height="25" border="0" title="Guardar" /></div>
    <div class="imgBtn" onclick="eliminarSlogan()"><img src="../images/trash.png" width="25" height="25" border="0" title="Eliminar" /></div>
    </td>
  </tr>
  <tr>
    <td valign="top">C.C.P. por municipio</td>
    <td valign="top" colspan="2">
    <div style="width:450px; position:relative">
    <div style="float:left">
    Municipio 
      <select name="municipio" id="municipio" onchange="cambiarMunicipio(this.value)">
        <option value="-1">-- Seleccione uno --</option>
        <?php
		$muns=Conexion::ejecutarConsulta("select id_municipio,descripcion from cat_municipios order by descripcion asc",NULL,PDO::FETCH_NUM);

		foreach($muns as $row){
			echo "<option value='".$row[0]."'>".$row[1]."</option>";
		}
		?>
      </select>
      <select name="constancias" id="constancias" onchange="cambiarMunicipio(municipio.value)">
        <option value="1">Constancias</option>
        <option value="0">Órdenes de pago</option>
      </select>
    </div>
      <div id="addCCP" class="imgBtn" style="float:right; display:none" title="Agregar CCP" onclick="agregarCCP()"><img src="../images/add.png" width="20" height="20" /></div>
      
      <div id="formCCP">Nombre:<br />
        <input type="text" name="nombrePersona" id="nombrePersona" style="width:200px" />
        <div><div class="btnAceptar" onclick="guardarCCP()">Aceptar</div><div class="btnCancelar" onclick="cancelarCCP()">Cancelar</div></div>
      </div>
    </div>
    <div id="divMunicipios"></div>
    </td>
  </tr>
  </tbody>
  </table>
</form>
</div>
</body>
</html>