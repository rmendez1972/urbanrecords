<?php
// Verificar el acceso
$requiredUserLevel = array(1,4,5,6);
$cfgProgDir =  '../';

include("../seguridad/secure.php");
include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");

Conexion::init(true);

if(isset($_GET["idsol"]) && isset($_GET["anio"])){
	$idsol=$_GET["idsol"];
	$anio=$_GET["anio"];
	
	if(isset($_POST["texto"])){
		Conexion::ejecutar("delete from constancias where anio=? and id_solicitud=?",array($anio,$idsol));
		
		$numconst=$_POST["numconst"];
		$texto=$_POST["texto"];
		$texto2=$_POST["texto2"];
		
		$mun=Conexion::ejecutarEscalar("select id_municipio from solicitud where id_solicitud=? and anio=?",array($idsol,$anio));
		$cons=Conexion::ejecutarConsulta("select nombre from ccps where id_municipio=? and constancia=1 order by orden asc",array($mun),PDO::FETCH_NUM);
		$copias="";
		$flg=false;
		foreach($cons as $row){
			$copias.=($flg?"#":"").$row[0];
			$flg=true;
		}
		
		Conexion::ejecutar("insert into constancias values (?,?,?,?,?,NOW(),?)",array($anio,$idsol,$numconst,$texto,$copias,$texto2));
	}
	
	$tot=Conexion::ejecutarEscalar("select count(*) from constancias where id_solicitud=? and anio=?",array($idsol,$anio));

	if($tot>0 && !isset($_GET["edit"])){
		header("location: constancia.php?idsol=".$idsol."&anio=".$anio."&numero=".$numero);	
	}
	
	$fecha=date("Y-m-d H:i:s");
}
else
	exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Constancias</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script>
function vertext(obj){
	if(obj.value=="Escriba los señalamientos correspondientes")
		obj.value="";
}
function validar(){
	if($("#numconst").val().length==0 || $("#texto").val().length==0 || $("#texto").val()=="Escriba los señalamientos correspondientes")
		alert("Escriba los señalamientos correspondientes");
	else
		document.getElementById('form1').submit();
}
</script>
<style>
textarea{
	font-size:11px;	
}
</style>
</head>

<body style="margin:25px; font-size:11px">
<div class="tituSec">Vista previa</div>
<form id="form1" name="form1" method="post" action="genconst.php?anio=<?php echo $_GET["anio"]."&idsol=".$_GET["idsol"]; ?>">
<p>Num. de oficio: 
  <input name="numconst" type="text" id="numconst" value="<? echo $numero; ?>" size="6" maxlength="5" />
</p>
<p><?php
echo Utilidades::obtenerConfiguracion("LEGALES",$fecha)."... [datos del proyecto]";
?></p>
<p>
  <textarea name="texto2" id="texto2" cols="45" rows="3">consideró procedente el trámite, por lo que tiene a bien OTORGAR la presente CONSTANCIA DE COMPATIBILIDAD URBANÍSTICA ESTATAL para quedar como sigue:</textarea>
</p>
<table width="450" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
  <textarea name="texto" id="texto" cols="45" rows="4" onfocus="vertext(this)">Escriba los señalamientos correspondientes</textarea>
</p>
<p>El presente documento tiene una vigencia de 3 años contados a partir de la fecha de su expedición y no autoriza ningún concepto de construcción de obra, por lo que ésta Secretaría se reserva el derecho de revocarlo si se contraviene a los términos en él especificados.</p>
<p><div class="btnVerde" onclick="validar()">Generar constancia</div></p>
</form>
</body>
</html>
