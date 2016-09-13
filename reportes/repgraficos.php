<?php
$requiredUserLevel = array(1,2,3,4,5);
$cfgProgDir =  '../';
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
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script src="../calendar/calendario.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/calendario.css">
<script>
function cambiarTipo(tipo){
	var valor=tipo.split("_");
	if(valor[0]==2 || valor[0]==4)
		document.getElementById("periodo").disabled=false;	
	else{
		document.getElementById("periodo").selectedIndex=0;
		document.getElementById("periodo").disabled=true;
		document.getElementById("anio").disabled=false;
	}
	
	if(valor[0]==4 || valor[0]==5){
		document.getElementById("tipog").selectedIndex=1;
	}
}
function tipoGraf(){
	cambiarTipo(document.getElementById("tipoReporte").value);	
}
function generar(){
	parent.abrirShadowbox("graficos/municipio.php?"+$("#formrep").serialize(),"iframe","",900,500);
}
function verAnual(val){
	if(val==3)
		document.getElementById("anio").disabled=true;	
	else
		document.getElementById("anio").disabled=false;
}
</script>
</head>

<body onload="cambiarTipo('1_1')">
<div id="calendario" style="position:absolute"></div>
<div class="tituSec">Reportes gráficos</div>
<br />
<form id="formrep" name="formrep" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
    <thead>
    <tr class="tituTab">
      <th colspan="2" scope="col">Filtro de solicitudes</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="120">Tipo de reporte</td>
      <td><select name="tipoReporte" id="tipoReporte" onchange="cambiarTipo(this.value)">
      <optgroup label="Ingresos">
        <option value="1_1">Ingresos por municipio</option>
        <option value="2_1">Ingresos por periodos de tiempo</option>
        <option value="3_1">Ingresos por tipos de constancia</option>
        <option value="4_1">Ingresos por municipio y periodos de tiempo</option>
      </optgroup>
       <optgroup label="Cantidad de constancias">
        <option value="1_2">Cantidad de constancias por municipio</option>
        <option value="2_2">Cantidad de constancias por periodos de tiempo</option>
        <option value="3_2">Cantidad de constancias por tipos de constancia</option>
        <option value="4_2">Cantidad de constancias por municipio y periodos de tiempo</option>
      </optgroup>
      <optgroup label="Superficies abarcadas">
        <option value="1_3">Superficies por municipio</option>
        <option value="3_3">Superficies por tipos de constancia</option>
        <option value="5_3">Superficies por municipio y tipos de constancia</option>
      </optgroup>
      </select></td>
    </tr>
    <tr>
      <td>Tipo de gráfica</td>
      <td><select name="tipog" id="tipog" onchange="tipoGraf()">
        <option value="pastel">Pastel</option>
        <option value="barras">Barras</option>
      </select></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td><select name="periodo" id="periodo" onchange="verAnual(this.value)">
        <option value="1">Mensual</option>
        <option value="2">Trimestral</option>
        <option value="3">Anual</option>
      </select></td>
    </tr>
    <tr>
      <td>Año</td>
      <td><select name="anio" id="anio">
      <?php
	  for($i=2008;$i<=$anioC;$i++)
	  	echo "<option value='$i' ".($i==$anioC?"selected":"").">$i</option>";
	  ?>
      </select></td>
    </tr>
    </tbody>
  </table>
</form>
<div class="botones" style="display:table; margin:auto; margin-top:15px;"><div class="btnAceptar" onclick="generar()">Aceptar</div><div class="btnCancelar" onclick="parent.cargar('libreria/principal.php')">Cancelar</div></div>
</body>
</html>