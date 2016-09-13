<?PHP 

define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
include ("../libreria/config.php"); 
class PDF extends FPDF
{

	//Encabezado
	function Header()
	{
	
		$this->Image('../images/LogoSEDU_g.jpg',10,10,105,15);
	
		$this->Ln(5);
		$this->SetFont('Arial','B',10);
	
		// titulo
		$this->MultiCell(197,5,"Subsecretaría de Desarrollo Urbano y Vivienda",0,'R',0);
		$this->MultiCell(197,5,"Dirección de Administración Urbana",0,'R',0);
		$this->Ln(5);
		$this->SetFont('Arial','B',9);
//		$this->Ln(10);
	
		$this->SetFillColor(213,217,214);
		$this->SetTextColor(0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','',9);

		
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


		$w=array(30,30,80,80,40);
		$this->SetWidths($w,array('C','C','J','J','J'),5,1);

		//mb_internal_encoding("UTF-8");

		$consulta= "SELECT tp.descripcion AS tipo_proyecto, tp.abreviatura,id_tipo
					FROM `cat_tipo_proy` tp
					WHERE 1";
		if (isset($_GET['id_tipo']) && $_GET['id_tipo']!="Todos")			
					$consulta.=" AND id_tipo=".$_GET['id_tipo']."";
		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		
		while($datos= mysql_fetch_array($resultado)){
			$this->SetFont('Arial','B',9);
			$this->MultiCell(197,5,$datos['tipo_proyecto'],0,'C',0);
			$this->SetFont('Arial','',8);

		
			$consulta= "select * from cat_requisitos r, cat_requisitos_opc ro WHERE r.id_requisito=ro.id_requisito AND id_tipo=".$datos['id_tipo']." ORDER BY orden";
	
			$sql_req=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			
			$w=array(90);
			$this->SetFont('Arial','B',8);
			$this->SetWidths($w,array('C','C'),5,1);
			$this->Row(array($datos['tipo_proyecto']),array(1,1,1,1,1));
			$this->SetWidths($w,array('J'),4,1);
			$this->SetFont('Arial','',7);
			while($requisitos= mysql_fetch_array($sql_req)){
				$this->Row(array($requisitos['descripcion']),array(0));
			}
		$this->AddPage();

		}
		mysql_free_result($resultado);	
		
		


	$pie="*En cumplimiento a lo previsto por el artículo 33, fracción III de la ley de Transparencia y Acceso a la Información Pública del Estado de Quintana Roo, se le informa que el propósito de recabar sus datos personales se circunscribe única y exclusivamente a contar con la información que señala el artículo 51 de la citada ley, para atender y dar seguimiento a la solicitud.";
	//$this->MultiCell(197,4,$pie,0,'J',0);			

	
	mysql_close($conexion);
	
} // fin del function
}// fin del class

//Page header
//Instanciation of inherited class
$pdf=new PDF("P","mm","Letter");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->FancyTable($Servidor,$UsrMysql,$ClaveMysql,$DB);
$pdf->Output();

?> 


