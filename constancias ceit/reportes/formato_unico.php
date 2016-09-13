<?PHP 

define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
include ("../libreria/config.php"); 
class PDF extends FPDF
{

	//Encabezado
	function Header()
	{
	
		//Logo
		$this->Image('../images/LogoQRoo.jpg',10,10,45,20);
		$this->Image('../images/SloganQroo.jpg',162,10,45,20);
	
		$this->SetFont('Arial','B',10);
	
		// titulo
/*		$this->MultiCell(197,6,"GOBIERNO DEL ESTADO DE QUINTANA ROO",0,'C',0);
		$this->MultiCell(197,6,"SECRETARÍA DE DESARROLLO URBANO",0,'C',0);
		$this->SetFont('Arial','',9);
		$this->MultiCell(197,6,"SUBSECRETARÍA DE DESARROLLO URBANO Y VIVIENDA",0,'C',0);
		$this->MultiCell(197,6,"DIRECCIÓN DE ADMINISTRACIÓN URBANA",0,'C',0);
	*/	
		$this->MultiCell(197,5,"Gobierno del Estado de Quintana Roo",0,'C',0);
		$this->MultiCell(197,5,"Secretaría de Desarrollo Urbano",0,'C',0);
		$this->MultiCell(197,5,"Subsecretaría de Desarrollo Urbano y Vivienda",0,'C',0);
		$this->MultiCell(197,5,"DIRECCIÓN DE ADMINISTRACIÓN URBANA",0,'C',0);
		$this->SetFont('Arial','',8);
		$this->MultiCell(197,4,"Av. Efraín Aguilar No. 418 entre Dimas Sansores y Retorno 3, Chetumal, Quintana Roo",0,'C',0);
		$this->MultiCell(197,4,"Tel. (01-983 83 24108, 1293317, 1293318 Ext. 137)  sduyv@qroo.gob.mx",0,'C',0);
		//$this->MultiCell(197,4,"",0,'C',0);
		$this->SetFont('Arial','B',9);
		$this->Ln(5);
		$this->MultiCell(197,5,"FORMATO ÚNICO",0,'C',0);
		$this->Ln(5);
		

//		$this->Ln(10);
	
		$this->SetFillColor(213,217,214);
		$this->SetTextColor(0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','',8);

		
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
	$this->SetFont('Arial','',8);

	$conexion=mysql_connect($Servidor,$UsrMysql,$ClaveMysql);
	$base=mysql_select_db($DB,$conexion);


		$w=array(30,30,80,80,40);
		$this->SetWidths($w,array('C','C','J','J','J'),5,1);

		$consulta= "select nombre_proyecto,fracciones,num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
		$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos= mysql_fetch_array($sql_solicitud);
		mysql_free_result($sql_solicitud);

		$this->Cell(60,5,"NOMBRE DEL PROYECTO:",0,0,'L',0);
		$this->MultiCell(137,6,$datos["nombre_proyecto"],'B','J',0);			
		$this->Cell(60,5,"SUPERFICIE Y NÚMERO DE VIVIENDAS:",0,0,'L',0);
		$this->MultiCell(137,6,$datos["superficie"]." metros cuadrados; ".$datos["num_viviendas"]." Viviendas",'B','J',0);			

		$consulta= "select * from seguimiento WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND id_seguimiento=2";
		$sql_seguimiento=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos= mysql_fetch_array($sql_seguimiento);
		mysql_free_result($sql_seguimiento);


		$meses= array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$mes=(int) strftime("%m",strtotime($datos["fecha"]));
		$fecha=strftime("%d",strtotime($datos["fecha"]))." DE ".$meses[$mes]." DEL ".strftime("%Y",strtotime($datos["fecha"]));
		$this->Cell(60,5,"FECHA DE REVISIÓN:",0,0,'L',0);
		$this->MultiCell(137,6,$fecha,'B','J',0);			
		$this->Cell(60,5,"PENDIENTES:",0,0,'L',0);
		$this->MultiCell(137,6,$datos["observaciones"],'B','J',0);			
		$this->Ln(4);
	$nota="Expedición de Constancias de Compatibilidad Urbanística Estatal para Fraccionamientos, Régimen Condominal, Subdivisión y Relotificación de predios";
	$this->MultiCell(197,4,$nota,0,'J',0);			
	

		$consulta= "select * from cat_apartado_formato";
		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		while($apartado= mysql_fetch_array($resultado)){
			$this->Ln(4);
			$this->SetFont('Arial','B',8);
			$this->MultiCell(197,4,$apartado['numero'].". ".$apartado['descripcion'],0,'J',0);			
			$this->SetFont('Arial','',8);
			
			$consulta= "select * from cat_preguntas_formato WHERE id_apartado=".$apartado['id_apartado'];
			$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			while($pregunta= mysql_fetch_array($sql_preg)){
				$this->MultiCell(197,4,$apartado['numero'].".".$pregunta['id_pregunta'].".- ".$pregunta['descripcion'],0,'J',0);			

				$consulta= "select respuesta from formato WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND id_apartado=".$apartado['id_apartado']." AND id_pregunta=".$pregunta['id_pregunta'];
				$sql_formato=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$form_preg= mysql_fetch_array($sql_formato);
				mysql_free_result($sql_formato);
				$this->MultiCell(197,4,$form_preg['respuesta'],'B','J',0);			
			
			}
		}

		$consulta= "select observaciones from formato_observaciones WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND formato=1";
		$sql_obs=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos_obs= mysql_fetch_array($sql_obs);
		mysql_free_result($sql_obs);

		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->MultiCell(197,4,"OBSERVACIONES",0,'J',0);			
		$this->SetFont('Arial','',8);
		$this->MultiCell(197,4,$datos_obs['observaciones'],'B','J',0);			
		
		$this->Ln(10);
			$w=array(98,99);
			$this->SetWidths($w,array('C','C'),4,0);
			$consulta= "SELECT * FROM cat_usuario WHERE login='".$datos['login']."'";
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$usuario= mysql_fetch_array($sql_user);

			$consulta= "SELECT * FROM cat_firmas WHERE id_firma=1";
			$sql_firma=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$firmas= mysql_fetch_array($sql_firma);


			$this->SetFont('Arial','B',8);
			$this->Row(array($usuario['nombre'],$firmas['nombre']),array(0));
			$this->SetFont('Arial','',8);
			$this->Row(array("Departamento de Control Técnico",$firmas['cargo']),array(0));
		
		
	
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


