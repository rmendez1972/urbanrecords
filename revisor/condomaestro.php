<?php
$requiredUserLevel = array(1,3);
$cfgProgDir =  '../';
include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
Conexion::init(true);

if(isset($_GET["id_solicitud"]) && isset($_GET["anio"])){
	$id_solicitud=$_GET["id_solicitud"];
	$anio=$_GET["anio"];
	
	$datos=Conexion::ejecutarFila("select * from solicitud where id_solicitud=? and anio=?",array($id_solicitud, $anio));
}
else
	exit;

$lotes=1;
if(isset($_GET["mod"])){
	$lotes=Conexion::ejecutarEscalar("select lotes from detalles_condominio where id_solicitud=? and anio=?",array($id_solicitud,$anio));	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<style>
.regTab input{
	width:96%;
}
.regTab td{
	text-align:center;	
}
</style>
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script>

function guardar(){
	$.post("CondominioMaestro.php",$("#formCon").serialize(),function(datos){
		var splt=datos.split("#");
		
		if(splt[0]=="ok")
			location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>';
		else
			parent.error(splt[1]);
	},"html");

}

var ncol=0;
var nfil=0;

function agregarFila(){
	nfil++;
	var nuevo=document.createElement("tr");
	nuevo.className="regTab";
	nuevo.id="gFila_"+nfil;
	var i;
	var inhtml="";
	for(i=1;i<=ncol;i++){
		inhtml+="<td id='gCell_"+i+"_"+nfil+"'><input type='text' id='val_"+i+"_"+nfil+"' name='val_"+i+"_"+nfil+"' /></td>";	
	}
	nuevo.innerHTML=inhtml;
	document.getElementById("gTabla").getElementsByTagName("tbody")[0].appendChild(nuevo);
	$("#filas").val(nfil);
	ajustador();
}

function agregarColumna(){
	ncol++;
	var i;
	
	var ntit=document.createElement("th");
	ntit.id="gTit_"+ncol;
	ntit.innerHTML="<input type='text' id='col_"+ncol+"' name='col_"+ncol+"' />";
	document.getElementById("gTitulos").appendChild(ntit);
	
	for(i=1;i<=nfil;i++){
		var celda=document.createElement("td");
		celda.id="gCell_"+ncol+"_"+i;
		celda.innerHTML="<input type='text' name='val_"+ncol+"_"+i+"' id='val_"+ncol+"_"+i+"' />";
		document.getElementById("gFila_"+i).appendChild(celda);
	}
	
	$("#columnas").val(ncol);
	ajustador();
}

</script>
</head>

<body>
<form id="formCon" name="formCon" method="post" action="">
  <input name="anio" type="hidden" id="anio" value="<?php echo $_GET["anio"]; ?>" />
  <input name="id_solicitud" type="hidden" id="id_solicitud" value="<?php echo $_GET["id_solicitud"]; ?>" />
  <input name="modulo" type="hidden" id="modulo" value="CONDOMINIO" />
  <input name="accion" type="hidden" id="accion" value="GUARDAR" />
  <br />
  <table width="80%" class="tablaDatos" align="center">
  <thead>
    <tr class="tituTab">
      <th colspan="4" align="center">Proyecto</th>
    </tr>
    </thead>
    
    <tbody>
    <tr >
      <td align="center"> Nombre del proyecto</td>
      <td colspan="3"><?PHP  echo $datos['nombre_proyecto']; ?>
        <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" /></td>
    </tr>
    <tr >
      <td align="center">Ubicaci&oacute;n</td>
      <td colspan="3"><?PHP  echo $datos['direccion']; ?></td>
    </tr>
    <tr >
      <td align="center"  class="segundalinea">Superficie</td>
      <td colspan="3"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>
        m<sup>2</sup></td>
    </tr>
    <tr >
      <td align="center"  class="segundalinea">Propietario</td>
      <td colspan="3"  class="segundalinea"><?PHP  echo $datos['propietario']; ?></td>
    </tr>
    </tbody>
  </table>
  <br />
  <div class="tituSec">Detalles del condominio maestro</div>
  <div>
  	<input type="button" class="btnAceptar" name="button" id="button" onclick="agregarFila()" style=" margin-left:25px; margin-bottom:10px;" value="Agregar fila" />
  	<input type="button" class="btnAceptar" name="button2" id="button2" onclick="agregarColumna()" style="margin-bottom:10px;" value="Agregar columna" />
  </div>
  <?
  $tot=Conexion::ejecutarEscalar("select count(*) from condo_maestro_cols where anio=? and id_solicitud=?",array($_GET["anio"],$_GET["id_solicitud"]));
  if($tot==0){
	  $ncol=4;
	  $nfil=4;
	?>
    <input name="columnas" type="hidden" id="columnas" value="4" />
    <input name="filas" type="hidden" id="filas" value="4" />
<table width="95%" border="0" align="center" class="tablaDatos" id='gTabla'>
<thead>
    <tr class="tituTab" id="gTitulos">
        <th id="gTit_1"><input name="col_1" type="text" id="col_1" value="No. Lotes" /></th>
        <th id="gTit_2"><input name="col_2" type="text" id="col_2" value="Descripción" /></th>
        <th id="gTit_3"><input name="col_3" type="text" id="col_3" value="Viviendas" /></th>
        <th id="gTit_4"><input name="col_4" type="text" id="col_4" value="Superficie (m2)" /></th>
    </tr>
    </thead>
    <tbody>
      <tr id="gFila_1" class="regTab">
        <td id="gCell_1_1"><input name="val_1_1" type="text" id="val_1_1" value="01" /></td>
        <td id="gCell_2_1"><input name="val_2_1" type="text" id="val_2_1" value="Unidades Privativas" /></td>
        <td id="gCell_3_1"><input type="text" name="val_3_1" id="val_3_1" /></td>
        <td id="gCell_4_1"><input type="text" name="val_4_1" id="val_4_1" /></td>
      </tr>
      <tr id="gFila_2" class="regTab">
        <td id="gCell_1_2"><input type="text" name="val_1_2" id="val_1_2" /></td>
        <td id="gCell_2_2"><input name="val_2_2" type="text" id="val_2_2" value="Habitacional Unifamiliar" /></td>
        <td id="gCell_3_2"><input type="text" name="val_3_2" id="val_3_2" /></td>
        <td id="gCell_4_2"><input type="text" name="val_4_2" id="val_4_2" /></td>
      </tr>
      <tr id="gFila_3" class="regTab">
        <td id="gCell_1_3"><input type="text" name="val_1_3" id="val_1_3" /></td>
        <td id="gCell_2_3"><input name="val_2_3" type="text" id="val_2_3" value="Habitacional Multifamiliar" /></td>
        <td id="gCell_3_3"><input type="text" name="val_3_3" id="val_3_3" /></td>
        <td id="gCell_4_3"><input type="text" name="val_4_3" id="val_4_3" /></td>
      </tr>
      <tr id="gFila_4" class="regTab">
        <td id="gCell_1_4"><input type="text" name="val_1_4" id="val_1_4" /></td>
        <td id="gCell_2_4"><input name="val_2_4" type="text" id="val_2_4" value="Mixto" /></td>
        <td id="gCell_3_4"><input type="text" name="val_3_4" id="val_3_4" /></td>
        <td id="gCell_4_4"><input type="text" name="val_4_4" id="val_4_4" /></td>
      </tr>
      </tbody>
  </table>
<?  
  }
  else{
	  echo "<table class='tablaDatos' id='gTabla'  width='95%' border='0' align='center'><thead><tr id='gTitulos' class='tituTab'>";
	  
	  $columnas=Conexion::ejecutarConsulta("select * from condo_maestro_cols where anio=? and id_solicitud=? order by id_columna asc",array($_GET["anio"],$_GET["id_solicitud"]));
	  foreach($columnas as $columna){
		  $idval="col_".$columna["id_columna"];
		  echo "<th id='gTit_".$columna["id_columna"]."'><input type='text' name='$idval' id='$idval' value='".$columna["nombre"]."' /></th>";
	  }
	  echo "</tr></thead><tbody>";
	  
	  $datos=Conexion::ejecutarConsulta("select * from condo_maestro_datos where anio=? and id_solicitud=? order by fila asc, id_columna asc",array($_GET["anio"],$_GET["id_solicitud"]));
	  $fila=0;
	  foreach($datos as $dat){
		if($fila!=$dat["fila"]){
			if($fila!=0)
				echo "</tr>";
			echo "<tr id='gFila_".$dat["fila"]."' class='regTab'>";
			$fila=$dat["fila"];  
		}
		
		echo "<td id='gCell_".$dat["id_columna"]."_".$dat["fila"]."'><input type='text' name='val_".$dat["id_columna"]."_".$dat["fila"]."' id='val_".$dat["id_columna"]."_".$dat["fila"]."' value='".$dat["dato"]."' /></td>";
	  }
	  
	  echo "</tbody></table>";
	  
	  $ncol=$dat["id_columna"];
	  $nfil=$dat["fila"];
	  
	  ?>
      <input name="columnas" type="hidden" id="columnas" value="<? echo $ncol; ?>" />
    <input name="filas" type="hidden" id="filas" value="<? echo $nfil; ?>" />
      <?
  }
  ?>
  <script>
  ncol=<? echo $ncol; ?>;
  nfil=<? echo $nfil; ?>;
  </script>
  <br />
  <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td align="center"><img src="../images/continuar1.png" alt="Capturar documentación" class="btnOld" onclick="guardar()" /></td>
      <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>'" /></td>
    </tr>
  </table>
</form>
</body>
</html>