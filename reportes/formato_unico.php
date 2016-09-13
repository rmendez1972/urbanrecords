<?PHP 
header("Content-Type: text/html; charset=iso-8859-1 ");
define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
include ("../libreria/config.php"); 
class PDF extends FPDF
{

	//Encabezado
	function Header()
	{
	
		//Logo
		$this->Image('../images/LogoSEDU_g.jpg',10,10,105,15);
	
		$this->Ln(5);
		$this->SetFont('Arial','B',10);
	
		// titulo
		$this->MultiCell(197,5,"Subsecretar�a de Desarrollo Urbano",0,'R',0);
		$this->MultiCell(197,5,"Direcci�n de Administraci�n Urbana",0,'R',0);
		$this->Ln(5);
		$this->SetFont('Arial','B',9);
//		$this->MultiCell(197,5,"FORMATO �NICO",0,'C',0);
	//	$this->Ln(5);
		

//		$this->Ln(10);
	
		$this->SetFillColor(213,217,214);
		$this->SetTextColor(0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','',8);

		
	}	//Fin del header
	
	
	
	//Page footer

	//Pie de p�gina	
	function Footer()
	{
	
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'P�gina '.$this->PageNo().'/{nb}',0,0,'C');
		
		$this->SetX(10);
		$this->SetFont('Arial','',6);
		$this->Cell(0,10,'Sistema de Control de Constancias de Compatibilidad Urban�stica',0,0,'L');
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

		$consulta= "select id_proyecto1, nombre_proyecto, fracciones, num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
		$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos= mysql_fetch_array($sql_solicitud);
		mysql_free_result($sql_solicitud);

		$this->Cell(60,5,"NOMBRE DEL PROYECTO:",0,0,'L',0);
		$this->MultiCell(137,6,utf8_decode($datos["nombre_proyecto"]),'B','J',0);			
		$this->Cell(60,5,"SUPERFICIE Y N�MERO DE VIVIENDAS:",0,0,'L',0);
		$this->MultiCell(137,6,number_format($datos["superficie"],2,".",",")." m�; ".$datos["num_viviendas"]." Viviendas",'B','J',0);			

		$consulta= "select * from seguimiento WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND id_seguimiento=2";
		$sql_seguimiento=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos_seg= mysql_fetch_array($sql_seguimiento);
		mysql_free_result($sql_seguimiento);


		$meses= array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$mes=(int) strftime("%m",strtotime($datos_seg["fecha"]));
		$fecha=strftime("%d",strtotime($datos_seg["fecha"]))." DE ".$meses[$mes]." DEL ".strftime("%Y",strtotime($datos_seg["fecha"]));
		$this->Cell(60,5,"FECHA DE REVISI�N:",0,0,'L',0);
		$this->MultiCell(137,6,$fecha,'B','J',0);			
		$this->Cell(60,5,"PENDIENTES:",0,0,'L',0);
		$this->MultiCell(137,6,utf8_decode($datos_seg["observaciones"]),'B','J',0);			
		$this->Ln(4);

		$nota= "Expedici�n de Constancias de Compatibilidad Urban�stica Estatal para ".$datos["id_proyecto1"];

		if ($datos["id_proyecto1"]==1 || ($datos["id_proyecto1"]>=5 && $datos["id_proyecto1"]<= 10))
			$nota.="Fraccionamientos o Conjunto Habitacional, Modificaci�n de Fraccionamiento, Renovaci�n de Fraccionamiento o Conjunto Habitacional, Fraccionamiento Tur�stico Hotelero, Fraccionamiento Mixto Hotelero-Habitacional, Fraccionamiento Habitacional Suburbano o rural, Fraccionamiento de Servicios funerarios";
		if ($datos["id_proyecto1"]==2)
			$nota.="Subdivisi�n de predios";
		if ($datos["id_proyecto1"]==3 || ($datos["id_proyecto1"]>=11 && $datos["id_proyecto1"]<= 22))
			$nota.="R�gimen Condominal, Modificaci�n de R�gimen de propiedad en condominio, Renovaci�n de R�gimen de propiedad en condominio, Centros comerciales, Renovaci�n de Centros comerciales, Hospitales, Centros M�dicos, Central de abastos, Oficinas, Industrias medianas y grandes, Renovaci�n para Industrias medianas y grandes, Centrales camionera y de carga y Renovaci�n de Centrales camionera y de carga";
		if ($datos["id_proyecto1"]==4)
			$nota.="Relotificaci�n de predios";
		
	$this->MultiCell(197,4,$nota,0,'J',0);			
	

		$consulta= "select * from cat_apartado_formato";
		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		while($apartado= mysql_fetch_array($resultado)){
			$this->Ln(4);
			$this->SetFont('Arial','B',8);
			$this->MultiCell(197,4,$apartado['numero'].". ".utf8_decode($apartado['descripcion']),0,'J',0);			
			$this->SetFont('Arial','',8);
			
			$consulta= "select * from cat_preguntas_formato WHERE id_apartado=".$apartado['id_apartado'];
			$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			while($pregunta= mysql_fetch_array($sql_preg)){
				$this->MultiCell(197,4,$apartado['numero'].".".$pregunta['id_pregunta'].".- ".utf8_decode($pregunta['descripcion']),0,'J',0);			

				$consulta= "select respuesta from formato WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND id_apartado=".$apartado['id_apartado']." AND id_pregunta=".$pregunta['id_pregunta'];
				$sql_formato=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$form_preg= mysql_fetch_array($sql_formato);
				mysql_free_result($sql_formato);
				$this->MultiCell(197,4,utf8_decode($form_preg['respuesta']),'B','J',0);			
				
				if ($apartado['numero']=="II" && $pregunta['id_pregunta']==5){
					$this->Ln(4);
					if ($datos["id_proyecto1"]==2){ //Subdivisi�n
						$posY=$this->GetY();
						$posX=$this->GetX();
						$w=array(40,40,40,40);
						$this->Cell(($w[0]*2),5,"Subdivisi�n",1,1,'C');
						$this->Cell($w[0],5,"Lotes",1,0,'C');
						$this->Cell($w[1],5,"�rea en m�",1,0,'C');
						$this->SetY($posY);
						$this->SetX($posX+($w[0]*2));
						$this->Cell($w[2],10,"Superficie Total",1,1,'C');

						$this->SetWidths($w,array('C','C','C','C'),5,1);
						$this->SetFont('Arial','',8);
						$consulta= "select * from lotes WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
						$sql_lotes=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
						$suma=0;
						$lotes_des="";
						$areas_des="";
						while($lotes= mysql_fetch_array($sql_lotes)){
							$suma+=$lotes['area'];
							$lotes_des.=$lotes['lote']."\n";
							$areas_des.=number_format($lotes['area'],2,".",",")."\n";
						}
						
						$this->Row(array(utf8_decode($lotes_des),$areas_des,number_format($suma,2,".",",")." m�"),array(0));
						
					}
					if ($datos["id_proyecto1"]==4){ //Relotificaci�n
						$posY=$this->GetY();
						$posX=$this->GetX();
						$w=array(40,40,40,40);
						$this->Cell(($w[0]*3),5,"Relotificaci�n ( Fusi�n )",1,1,'C');
						$this->Cell($w[0],5,"Lotes",1,0,'C');
						$this->Cell($w[1],5,"�rea en m�",1,0,'C');
						$this->Cell($w[2],5,"Resultante",1,1,'C');
						$this->SetY($posY);
						$this->SetX($posX+($w[0]*3));
						$this->Cell($w[3],10,"Superficie Total",1,1,'C');

						$this->SetWidths($w,array('C','C','C','C'),5,1);
						$this->SetFont('Arial','',8);
						$consulta= "select * from lotes WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
						$sql_lotes=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
						$suma=0;
						$lotes_des="";
						$areas_des="";
						while($lotes= mysql_fetch_array($sql_lotes)){
							$suma+=$lotes['area'];
							$lotes_des.=$lotes['lote']."\n";
							$areas_des.=number_format($lotes['area'],2,".",",")."\n";
						}
						
						$consulta= "select lote from lotes_resulta2 WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
						$sql_suma=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
						$valor="";
						if (mysql_num_rows($sql_suma)>0){
							$valor= mysql_result($sql_suma,0);
						}				
						$this->Row(array($lotes_des,$areas_des,$valor,number_format($suma,2,".",",")." m�"),array(0));
						
					}
					
				}

			}
		}

		$this->Ln(4);

		$consulta= "select observaciones from formato_observaciones WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND formato=1";
		$sql_obs=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos_obs= mysql_fetch_array($sql_obs);
		mysql_free_result($sql_obs);

		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->MultiCell(197,4,"OBSERVACIONES",0,'J',0);			
		$this->SetFont('Arial','',8);
		$this->MultiCell(197,4,utf8_decode($datos_obs['observaciones']),'B','J',0);			
		
		$this->Ln(30);
			$w=array(98,99);
			$this->SetWidths($w,array('C','C'),4,0);
			$consulta= "SELECT * FROM cat_usuario WHERE login='".$datos_seg['login']."'";
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz� correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$usuario= mysql_fetch_array($sql_user);

			$consulta= "SELECT * FROM cat_firmas WHERE id_firma=1";
			$sql_firma=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz� correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$firmas= mysql_fetch_array($sql_firma);


			$this->SetFont('Arial','B',8);
			$this->Row(array(utf8_decode($usuario['nombre']),utf8_decode($firmas['nombre'])),array(0));
			$this->SetFont('Arial','',8);
			$this->Row(array("Departamento de Evaluaci�n y Dictamen Urbano",utf8_decode($firmas['cargo'])),array(0));
		
		
	
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
$pdf->Output("ficha_constancia.pdf", "D");

?>
