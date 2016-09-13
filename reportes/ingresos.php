<?PHP 

define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
include ("../libreria/config.php"); 
class PDF extends FPDF
{
var $titulo;

	//Encabezado
	function Header()
	{
	
		//Logo
		$this->Image('../images/LogoQRoo.jpg',10,10,45,20);
		$this->Image('../images/SloganQroo.jpg',225,10,45,20);
	
		$this->SetFont('Arial','B',14);
	
		// titulo
		$this->MultiCell(260,6,"SECRETARÍA DE DESARROLLO URBANO",0,'C',0);
		$this->SetFont('Arial','B',12);
		$this->MultiCell(260,6,"Sistema de Control de Constancias de Compatibilidad Urbanística",0,'C',0);
		$this->SetX(55);
		$this->SetFont('Arial','B',12);
	    $this->MultiCell(170,6,$this->titulo,0,'C',2);

		$this->SetY(35);
		$this->SetFillColor(213,217,214);
		$this->SetTextColor(0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(.3);

		
	}	//Fin del header
	
	
	
	//Page footer

	//Pie de página	
	function Footer()
	{
	
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
		
		$this->SetX(10);
		$this->SetFont('Arial','',6);
		$this->Cell(0,10,'Sistema de Control de Constancias de Compatibilidad Urbanística',0,0,'L');
		$this->SetFont('Arial','',6);
		$this->Cell(0,10,'Fecha de impresion: '.date('Y-m-d  g:i a'),0,0,'R');
	
	}	//Fin del Footer


function FancyTable($Servidor,$UsrMysql,$ClaveMysql,$DB)
{

    $this->SetFillColor(213,217,214);
  	$this->SetTextColor(0);
	$this->SetDrawColor(128,128,128);
    $this->SetLineWidth(.3);
	$this->SetFont('Arial','',9);

	$conexion=mysql_connect($Servidor,$UsrMysql,$ClaveMysql);
	$base=mysql_select_db($DB,$conexion);


		$w=array(25,20,45,80,35,25,30);
		$this->SetFont('Arial','B',8);
		$this->SetWidths($w,array('C','C','C','C','C','C'),5,1);
		$this->Row(array("FICHA","FECHA DE TRÁMITE","TIPO DE TRÁMITE","DESARROLLO","MUNICIPIO","No. DE OFICIO","INGRESOS"),array(1,1,1,1,1,1,1));
		$this->SetWidths($w,array('C','C','J','J','J','R','R'),5,1);
		$this->SetFont('Arial','',8);


		$consulta= "SELECT s.*, tp.descripcion AS tipo_proyecto, tp.abreviatura, cm.descripcion AS municipio 
					FROM`solicitud` s, cat_tipo_proy tp, cat_municipios cm 
					WHERE id_tipo=id_proyecto1 AND s.id_municipio=cm.id_municipio
						AND s.anio=".$_POST['anio']."";
			$consulta.= '
				AND concat( anio,"/", id_solicitud ) 
				IN (
					SELECT concat( anio,"/", id_solicitud ) 
					FROM `constancias_pagos` cp 
					WHERE cp.anio='.$_POST['anio'].'
				)
			';

		if (isset($_POST['id_municipio']) && $_POST['id_municipio']!="Todos")
			$consulta.= " AND s.id_municipio=".$_POST['id_municipio'];
		if (isset($_POST['id_proyecto']) && $_POST['id_proyecto']!="Todos")
			$consulta.= " AND s.id_proyecto1=".$_POST['id_proyecto'];

		if (isset($_POST['fecha1']) && isset($_POST['fecha2'])){
			$consulta.= " AND (s.fecha_ingreso BETWEEN '".$_POST['fecha1']."' AND '".$_POST['fecha2']."')";
		}
		if (isset($_POST['tipo']) && $_POST['tipo']=="1")
			$consulta.= " AND s.id_solicitud=".$_POST['numero'];
		if (isset($_POST['tipo']) && $_POST['tipo']=="2")
			$consulta.= " AND (s.propietario LIKE '%".$_POST['descripcion']."%' OR s.nombre_proyecto LIKE '%".$_POST['descripcion']."%')";
		if (isset($_POST['tipo']) && $_POST['tipo']=="3")
			$consulta.= " AND s.nombre_proyecto LIKE '%".$_POST['descripcion']."%'";
		if (isset($_POST['tipo']) && $_POST['tipo']=="4")
			$consulta.= " AND s.id_solicitud BETWEEN ".$_POST['numero']." AND ".$_POST['numero2'];

		//$this->MultiCell(197,5,$consulta,0,'C',0);

		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$Tderechos=0;
		$Tder_urb=0;
		$Total=0;
		while($datos= mysql_fetch_array($resultado)){
			$consulta= "select * from constancias_pagos WHERE id_solicitud=".$datos['id_solicitud']." AND anio=".$datos['anio'];

			$sql_constancia=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$data_cons="";
			$data_pago="";
			while($constancias= mysql_fetch_array($sql_constancia)){
//				$data_cons.=$constancias['numero']."/".$constancias['municipio']."/".$constancias['anio'];
				$Tderechos+=$constancias['derechos'];
				if ($constancias['derechos']<>0){
					if  ($data_cons!=""){ $data_cons.="\n";  $data_pago.="\n";}
					$data_cons.=$constancias['numero'];//."/".$constancias['municipio']."/".$constancias['anio']." D: ";
					$data_pago.=number_format($constancias['derechos'],2,'.',',');
				}
			}
			mysql_free_result($sql_constancia);	
			$this->Row(array($datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio'],date("d/m/Y",strtotime($datos["fecha_ingreso"])),$datos["tipo_proyecto"],$datos["propietario"]."\n".$datos["nombre_proyecto"],$datos["municipio"],$data_cons,$data_pago),array(0));
		}
		mysql_free_result($resultado);	
		$this->SetX(215);
		$this->Cell(25,5,"TOTAL",1,0,'R',0);
		$this->Cell(30,5,number_format($Tderechos,2,'.',','),1,1,'R',0);

	$this->Ln(4);

/**************************** N U M E R A L I A ****************************************/
/*
SELECT count(s.id_solicitud), tp.descripcion AS tipo_proyecto, tp.abreviatura, cm.descripcion AS municipio
FROM`solicitud` s, cat_tipo_proy tp, cat_municipios cm , constancias_pagos cp
WHERE id_tipo=id_proyecto1 AND s.id_municipio=cm.id_municipio
AND cp.anio=s.anio AND s.id_solicitud=cp.id_solicitud AND s.anio=2011 GROUP BY s.id_municipio
*/

		$w=array(40,50,17,40,40);
		$this->SetFont('Arial','B',8);
		$this->SetWidths($w,array('C','C','C','C','C','C'),5,1);
		$this->Row(array("MUNICIPIO","TIPO DE TRÁMITE","CANTIDAD","DERECHOS"),array(1,1,1,1,1,1,1));
		$this->SetWidths($w,array('J','J','C','R','R'),5,1);
		$this->SetFont('Arial','',8);

		$consulta= "SELECT count(s.id_solicitud) AS cantidad, tp.descripcion AS tipo_proyecto, tp.abreviatura, cm.descripcion AS municipio, sum(derechos) AS tderechos
FROM `solicitud` s, cat_tipo_proy tp, cat_municipios cm, constancias_pagos cp
WHERE id_tipo=id_proyecto1 AND s.id_municipio=cm.id_municipio
AND cp.anio=s.anio AND s.id_solicitud=cp.id_solicitud
						AND s.anio=".$_POST['anio']."";

		if (isset($_POST['id_municipio']) && $_POST['id_municipio']!="Todos")
			$consulta.= " AND s.id_municipio=".$_POST['id_municipio'];
		if (isset($_POST['id_proyecto']) && $_POST['id_proyecto']!="Todos")
			$consulta.= " AND s.id_proyecto1=".$_POST['id_proyecto'];

		if (isset($_POST['fecha1']) && isset($_POST['fecha2'])){
			$consulta.= " AND (s.fecha_ingreso BETWEEN '".$_POST['fecha1']."' AND '".$_POST['fecha2']."')";
		}
		if (isset($_POST['tipo']) && $_POST['tipo']=="1")
			$consulta.= " AND s.id_solicitud=".$_POST['numero'];
		if (isset($_POST['tipo']) && $_POST['tipo']=="2")
			$consulta.= " AND (s.propietario LIKE '%".$_POST['descripcion']."%' OR s.nombre_proyecto LIKE '%".$_POST['descripcion']."%')";
		if (isset($_POST['tipo']) && $_POST['tipo']=="3")
			$consulta.= " AND s.nombre_proyecto LIKE '%".$_POST['descripcion']."%'";
		if (isset($_POST['tipo']) && $_POST['tipo']=="4")
			$consulta.= " AND s.id_solicitud BETWEEN ".$_POST['numero']." AND ".$_POST['numero2'];
			
			$consulta.= " GROUP BY s.id_municipio,s.id_proyecto1";
//		$this->MultiCell(197,5,$consulta,0,'C',0);

		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		
		while($datos= mysql_fetch_array($resultado)){
			$this->Row(array($datos["municipio"],$datos["tipo_proyecto"],$datos["cantidad"],number_format($datos["tderechos"],2,'.',',')),array(0));
		}
		mysql_free_result($resultado);	
		$this->SetX(100);
		$this->Cell(17,5,"TOTAL",1,0,'R',0);
		$this->Cell(40,5,number_format($Tderechos,2,'.',','),1,0,'R',0);
	$this->Ln(4);




	
	mysql_close($conexion);
	
} // fin del function
}// fin del class

	$conexion=mysql_connect($Servidor,$UsrMysql,$ClaveMysql);
	$base=mysql_select_db($DB,$conexion);

		$titulo="INGRESOS";
		if (isset($_POST['id_municipio']) && $_POST['id_municipio']!="Todos"){
			$consulta= "select * from cat_municipios WHERE id_municipio=".$_POST['id_municipio'];
			
			$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$municipios= mysql_fetch_array($resultado);
			$titulo.=" DE ".$municipios['descripcion'];
			mysql_free_result($resultado);
		}
		if (isset($_POST['id_proyecto']) && $_POST['id_proyecto']!="Todos"){
			$consulta= "select * from cat_tipo_proy WHERE id_tipo=".$_POST['id_proyecto'];
			
			$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$proyectos= mysql_fetch_array($resultado);
			$titulo.=" POR ".$proyectos['descripcion'];
			mysql_free_result($resultado);
		}
		
		if (isset($_POST['fecha1']) && isset($_POST['fecha2'])){
			$titulo.="\nDEL ".$_POST['fecha1']." AL ".$_POST['fecha2']."";
		}
		if (isset($_POST['tipo']) && $_POST['tipo']=="4")
			$titulo.=" NUMERADAS DEL ".$_POST['numero']." AL ".$_POST['numero2']."";
		
		mysql_close($conexion);

//Page header
//Instanciation of inherited class
$pdf=new PDF("L","mm","Letter");
$pdf->titulo=$titulo;
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->FancyTable($Servidor,$UsrMysql,$ClaveMysql,$DB);
$pdf->Output();

?> 


