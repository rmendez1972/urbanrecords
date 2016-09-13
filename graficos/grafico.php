<?php
$requiredUserLevel = array(1,2,3,4,5);
$cfgProgDir =  '../';
include("../seguridad/secure.php");
include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");
//include("../libreria/Graficador.php");
$fecha=date("Y-m-d H:i:s");
Conexion::init(true);

$tr=$_GET["tipoReporte"];
$anio=$_GET["anio"];
$periodo=$_GET["periodo"];
$tipog=$_GET["tipog"];

$graf=$tipog=="pastel"?"3d pie":"column";

$prefix="";
$sufix="";

if($anio!="")
	$consanio=" and S.anio=".$anio;

if($tr=="1_1"){
	$prefix="$";
	$sql="select M.descripcion,sum(CP.derechos) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio inner join constancias_pagos CP on S.id_solicitud=CP.id_solicitud and S.anio=CP.anio where S.anio=".$anio." group by M.descripcion order by M.descripcion asc";
	$titulo="Ingresos generados de las emisiones de constancias por municipio";
}
if($tr=="2_1"){
	$prefix="$";
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
if($tr=="3_1"){
	$prefix="$";
	$sql="select TP.descripcion,sum(CP.derechos) from solicitud S inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 inner join constancias_pagos CP on S.id_solicitud=CP.id_solicitud and S.anio=CP.anio where S.anio=".$anio." group by TP.descripcion order by TP.descripcion asc";
	$titulo="Ingresos generados de las emisiones de constancias por tipo de proyectos";
}
if($tr=="4_1"){
	$prefix="$";
	if($periodo==1)
		$campo="(select descripcion from cat_meses where id_mes=month(S.fecha_ingreso))";
	else if($periodo==2)
		$campo="concat('Trimestre ',truncate(month(S.fecha_ingreso)/3,0)+1)";
	else
		$campo="year(S.fecha_ingreso)";
	$pers=array("","mes","trimestre","año");
	
	$sql="select $campo as grupo,M.descripcion,sum(CP.derechos) from solicitud S inner join constancias_pagos CP on S.id_solicitud=CP.id_solicitud and S.anio=CP.anio inner join cat_municipios M on S.id_municipio=M.id_municipio where 1=1 $consanio group by grupo,M.descripcion order by S.fecha_ingreso asc,M.descripcion asc";
	
	$titulo="Ingresos generados de las emisiones de constancias por municipio y ".$pers[$periodo];
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
if($tr=="3_2"){
	$sql="select TP.descripcion,count(*) from solicitud S inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 where S.anio=".$anio." group by TP.descripcion order by TP.descripcion asc";
	$titulo="Cantidad de constancias por tipo de proyectos";
}
if($tr=="4_2"){
	if($periodo==1)
		$campo="(select descripcion from cat_meses where id_mes=month(S.fecha_ingreso))";
	else if($periodo==2)
		$campo="concat('Trimestre ',truncate(month(S.fecha_ingreso)/3,0)+1)";
	else
		$campo="year(S.fecha_ingreso)";
	$pers=array("","mes","trimestre","año");
	
	$sql="select $campo as grupo,M.descripcion,count(*) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio where 1=1 $consanio group by grupo,M.descripcion order by S.fecha_ingreso asc,M.descripcion asc";
	
	$titulo="Cantidad de constancias por municipio y ".$pers[$periodo];
}

if($tr=="1_3"){
	$sufix="m²";
	$sql="select M.descripcion,sum(S.superficie) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio where S.anio=".$anio." group by M.descripcion order by M.descripcion asc";
	$titulo="Superficie abarcada por municipio";
}
if($tr=="3_3"){
	$sufix="m²";
	$sql="select TP.descripcion,sum(S.superficie) from solicitud S inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 where S.anio=".$anio." group by TP.descripcion order by TP.descripcion asc";
	$titulo="Superficie abarcada por tipo de constancia";
}
if($tr=="5_3"){
	$sufix="m²";
	$sql="select TP.descripcion,M.descripcion,sum(S.superficie) from solicitud S inner join cat_municipios M on S.id_municipio=M.id_municipio inner join cat_tipo_proy TP on TP.id_tipo=S.id_proyecto1 where S.anio=".$anio." group by TP.descripcion,M.descripcion order by TP.descripcion asc,M.descripcion asc";
	$titulo="Superficie abarcada por municipio y tipo de constancia";
}

class Municipio{
	public $nombre,$periodos;
	
	function __construct($nombre, $num){
		$this->nombre=$nombre;
		$this->periodos=array();
		for($i=0;$i<$num;$i++)
			$this->periodos[$i]=0;
	}
}
?>
<chart>
	<license>LTQNN-HE4JUOASZ0B6SVMYWHM5SXBL</license>
    <chart_data>
<?

$cons=Conexion::ejecutarConsulta($sql,null,PDO::FETCH_NUM);
$etiquetas=array();
$fila=array();
$valores=array();
$i=0;

function agregar($val, $arreglo){
	for($i=0;$i<count($arreglo);$i++)
		if($val==$arreglo[$i])
			return $arreglo;

	$arreglo[$i]=$val;
	return $arreglo;
}
function obtenerIndice($val, $arreglo){
	for($i=0;$i<count($arreglo);$i++)
		if($val==$arreglo[$i])
			return $i;
	
	return -1;	
}

if($tr=="4_1" || $tr=="4_2" || $tr=="5_3"){
	$graf="";
	
	$periodos=array();
	$municipios=array();
	
	foreach($cons as $row){
		$periodos=agregar($row[0],$periodos);
		$municipios=agregar($row[1],$municipios);
		$fila[$i++]=$row;
	}
	
	$muniobj=array();
	for($i=0;$i<count($municipios);$i++)
		$muniobj[$i]=new Municipio($municipios[$i],count($periodos));
	
	foreach($fila as $row){
		$perid=obtenerIndice($row[0],$periodos);
		$munid=obtenerIndice($row[1],$municipios);
		
		$muniobj[$munid]->periodos[$perid]=$row[2];	
	}
	
	echo "<row><null/>";
	for($i=0;$i<count($periodos);$i++){
		echo "<string>".$periodos[$i]."</string>";
	}
	echo "</row>";
	
	foreach($muniobj as $muni){
		echo "<row><string>".$muni->nombre."</string>";	
		foreach($muni->periodos as $per){
			echo $per==0?"<null/>":"<number tooltip='".$muni->nombre." (".$prefix.$per.$sufix.")'>".$per."</number>";	
		}
		echo "</row>";
		$graf.="<string>column</string>";
	}
}
else{
	foreach($cons as $row){
		$etiquetas[$i]=$row[0];
		$valores[$i]=$row[1];
		$i++;
	}
	
	echo " <row>
			<null/>";
	foreach($etiquetas as $lab)
		echo"<string>".$lab."</string>";
	echo "</row>";
	
	$i=0;
	echo "<row><string></string>";
	foreach($valores as $val)
		echo $val==0?"<null/>":"<number tooltip='".$etiquetas[$i++]." (".$prefix.$val.$sufix.")'>".$val."</number>";
	
	echo "</row>";
}
?>
	</chart_data>
	
    <chart_label shadow='low' color='0' alpha='65' size='12' position='inside' as_percentage='false' />				
	<chart_rect x='260' y='70' width='500' height='<? echo $tipog=="pastel"?"250":"200"; ?>' positive_alpha='0' />
	<chart_type><? echo $graf; ?></chart_type>
	<tooltip color='FFFFFF' alpha='90' background_color_1='8888FF' background_alpha='90' shadow='medium' />
	<axis_category size='11' orientation='diagonal_down' />
    <axis_value size='14' prefix='<? echo $prefix; ?>' suffix='<? echo $sufix; ?>' />
	<draw>
		<rect bevel='bg' layer='background' x='0' y='0' width='400' height='320' fill_color='ffffff' line_thickness='0' />
		<text shadow='low' color='9E0D05' alpha='60' size='22' x='0' y='15' width='430' height='50' v_align='middle'><? echo $titulo; ?></text>
		<rect shadow='low' layer='background' x='-50' y='70' width='600' height='212' rotation='-5' fill_alpha='0' line_thickness='80' line_alpha='5' line_color='D6D6D6' />
	</draw>
	
    <filter>
		<shadow id='low' distance='2' angle='45' color='0' alpha='50' blurX='4' blurY='4' />
		<bevel id='bg' angle='180' blurX='100' blurY='100' distance='50' highlightAlpha='0' shadowAlpha='15' type='inner' />
		<bevel id='bevel1' angle='45' blurX='5' blurY='5' distance='1' highlightAlpha='25' highlightColor='ffffff' shadowAlpha='50' type='inner' />
	</filter>
	
    <context_menu save_as_bmp='true' save_as_jpeg='true' save_as_png='true' /> 
    
<legend shadow='low' bevel='bevel1' transition='dissolve' delay='2' duration='0.5' x='5' y='80' width='50' height='5' layout='horizontal' margin='5' bullet='line' size='11' color='9E0D05' alpha='75' fill_color='ffffff' fill_alpha='10' line_color='ffffff' line_alpha='0' line_thickness='0' />

    <chart_pref select='false' drag='true' rotation_x='60' min_x='20' max_x='90' />
    <chart_transition type='spin' delay='0' duration='1' order='category' />
        <series_color>
            <color>00ff88</color>
            <color>ffaa00</color>
            <color>66ddff</color>
            <color>bb00ff</color>
        </series_color>
        <series_explode>
            <number>25</number>
            <number>45</number>
        </series_explode>

</chart>