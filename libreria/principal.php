<?PHP
		$requiredUserLevel = array(1,2,3,4,5,6,7);
		$cfgProgDir =  '../';
		include('../seguridad/' . "secure.php");

include ("../libreria/config.php");
include ("../libreria/libreria.php");

$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = "Chetumal, Quintana Roo a ".date("d");
$mes = $meses[date("n")];
		$fecha .= " de ".$mes." ".date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style>
#contenido{
	text-align:center;
	width:400px;
	padding:20px;
	color:#FFF;
	background-color:#002e49;
	margin:auto;
	margin-top:100px;
	font-weight:bold;
	font-size:14px;
	border-width:2px;
	border-style:solid;
	border-color:#E9EBEB;
	border-radius:7px;
}
</style>
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
</head>

<body>

<div id="contenido">
Gobierno del Estado de Quintana Roo<br />
Secretaria de Desarrollo Urbano<br />
Departamento de Sistemas y Tecnologías de Información<br />
<br />
<?PHP echo $fecha; ?>
</div>

</body>
</html>