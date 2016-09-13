<?php
$requiredUserLevel = array(1,2,3,4,5);
$cfgProgDir =  '../';
include("../seguridad/secure.php");
include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");
//include("../libreria/Graficador.php");
$fecha=date("Y-m-d H:i:s");
Conexion::init(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body style="margin:0; background-color:#FFF">
<?php
$tr=$_GET["tipoReporte"];
$anio=$_GET["anio"];
$periodo=$_GET["periodo"];

if($anio!="")
	$consanio=" and S.anio=".$anio;

if($tr=="1_1"){
	$sql="select M.descripcion,sum(CP.derechos) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio inner join constancias_pagos CP on S.id_solicitud=CP.id_solicitud and S.anio=CP.anio where S.anio=".$anio." group by M.descripcion order by M.descripcion asc";
	$titulo="Ingresos generados de las emisiones de constancias por municipio";
}
if($tr=="2_1"){
	if($periodo==1)
		$campo="(select descripcion from cat_meses where id_mes=month(S.fecha_ingreso))";
	else if($periodo==2)
		$campo="concat('Trimestre ',truncate(month(S.fecha_ingreso)/3,0)+1)";
	else
		$campo="year(S.fecha_ingreso)";
	$pers=array("","mes","trimestre","año");
	
	$sql="select $campo as grupo,sum(CP.derechos) from solicitud S inner join constancias_pagos CP on S.id_solicitud=CP.id_solicitud and S.anio=CP.anio where 1=1 $consanio group by grupo order by S.fecha_ingreso asc";
	$titulo="Ingresos generados de las emisiones de constancias por ".$pers[$periodo];
}
if($tr=="3_2"){
	$sql="select TP.descripcion,sum(CP.derechos) from solicitud S inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 inner join constancias_pagos CP on S.id_solicitud=CP.id_solicitud and S.anio=CP.anio where S.anio=".$anio." group by TP.descripcion order by TP.descripcion asc";
	$titulo="Ingresos generados de las emisiones de constancias por tipo de proyectos";
}

if($tr=="1_2"){
	$sql="select M.descripcion,count(*) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio where S.anio=".$anio." group by M.descripcion order by M.descripcion asc";
	$titulo="Cantidad de constancias por municipio";
}
if($tr=="2_2"){
	if($periodo==1)
		$campo="(select descripcion from cat_meses where id_mes=month(S.fecha_ingreso))";
	else if($periodo==2)
		$campo="concat('Trimestre ',truncate(month(S.fecha_ingreso)/3,0)+1)";
	else
		$campo="year(S.fecha_ingreso)";
	$pers=array("","mes","trimestre","año");
	
	$sql="select $campo as grupo,count(*) from solicitud S where 1=1 $consanio group by grupo order by S.fecha_ingreso asc";
	$titulo="Cantidad de constancias por ".$pers[$periodo];
}
if($tr=="3_1"){
	$sql="select TP.descripcion,count(*) from solicitud S inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 where S.anio=".$anio." group by TP.descripcion order by TP.descripcion asc";
	$titulo="Cantidad de constancias por tipo de proyectos";
}

if($tr=="1_3"){
	$sql="select M.descripcion,sum(S.superficie) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio where S.anio=".$anio." group by M.descripcion order by M.descripcion asc";
	$titulo="Superficie abarcada por municipio";
}
if($tr=="3_3"){
	$sql="select TP.descripcion,sum(S.superficie) from solicitud S inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 where S.anio=".$anio." group by TP.descripcion order by TP.descripcion asc";
	$titulo="Superficie abarcada por tipo de proyectos";
}

$cons=Conexion::ejecutarConsulta($sql,null,PDO::FETCH_NUM);
$etiquetas=array();
$valores=array();
$i=0;
foreach($cons as $row){
	$etiquetas[$i]=$row[0];
	$valores[$i]=$row[1];
	$i++;
}

$reporte=new Graficador($titulo,$etiquetas,$valores,Graficador::$PASTEL,Graficador::$NUMEROS);
$reporte->setSize(900,400);
$reporte->setFont("../libreria/arialnb.ttf");
$img=$reporte->generate();
$result=imagepng($img, "grafica.png");
imagedestroy($img);

if($result)
	echo "<img src='grafica.png' />";
?>
</body>
</html>