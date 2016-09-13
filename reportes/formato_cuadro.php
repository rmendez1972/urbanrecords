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
		$this->MultiCell(197,5,"Subsecretar�a de Desarrollo Urbano y Vivienda",0,'R',0);
		$this->MultiCell(197,5,"Direcci�n de Administraci�n Urbana",0,'R',0);
		$this->Ln(5);
		
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

		$consulta= "select s.id_proyecto1,nombre_proyecto, direccion, propietario, superficie, id_solicitud,anio, p.descripcion
					FROM solicitud s, cat_tipo_proy p
					WHERE p.id_tipo= s.id_proyecto1 AND anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
		$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos= mysql_fetch_array($sql_solicitud);
		mysql_free_result($sql_solicitud);

		$this->Cell(40,5,"Tipo:",0,0,'L',0);
		$this->MultiCell(157,6,utf8_decode($datos["descripcion"]),'B','J',0);			
		$this->Cell(40,5,"Nombre del Proyecto:",0,0,'L',0);
		$this->MultiCell(157,6,utf8_decode($datos["nombre_proyecto"]),'B','J',0);			
		$this->Cell(40,5,"Ubicaci�n:",0,0,'L',0);
		$this->MultiCell(157,6,utf8_decode($datos["direccion"]),'B','J',0);			
		$this->Cell(40,5,"Superficie total del terreno:",0,0,'L',0);
		$this->MultiCell(157,6,number_format($datos["superficie"],2,".",",")." metros cuadrados; ",'B','J',0);			
		$this->Cell(40,5,"Propietario:",0,0,'L',0);
		$this->MultiCell(157,6,utf8_decode($datos["propietario"]),'B','J',0);			
		$this->Ln(4);

		$cons=mysql_query("select count(*) from formato2 where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."'",$conexion);
		$res=mysql_fetch_row($cons);
		if($res[0]>0){
		
			$cons=mysql_query("select distinct C.id_columna,C.descripcion from formato2 F inner join cat_columnas_formato2 C on F.id_columna=C.id_columna where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."' order by id_columna asc",$conexion);
			$columnas=array();
			$w=array();
			$w[0]=40;
			$titulos=array();
			$titulos[0]="CONCEPTO";
			$align=array();
			$align[0]="C";
			$i=0;
			$numeros=array();
			$numeros[0]=1;
			while($res=mysql_fetch_row($cons)){
				$columnas[$i]=array();
				$columnas[$i]["id"]=$res[0];
				$columnas[$i]["desc"]=utf8_decode($res[1]);
				$i++;
				$titulos[$i]=utf8_decode($res[1]);
				$w[$i]=25;
				$align[$i]="C";
				$numeros[$i]=1;
			}

			
			$this->SetWidths($w,$align,4,1);
			$this->Row($titulos,$numeros);
			
			$align[0]="J";
			$this->SetWidths($w,$align,4,1);
		

			$consulta= "select * from cat_preguntas_formato2";
			$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			while($pregunta= mysql_fetch_array($sql_preg)){
					$data=array();
					$data[0]=utf8_decode($pregunta["descripcion"]);
					for($i=0;$i<count($columnas);$i++){
						$cons=mysql_query("select valor from formato2 where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."' and id_pregunta='".$pregunta["id_pregunta"]."' and id_columna='".$columnas[$i]["id"]."'",$conexion);
						$res=mysql_fetch_row($cons);
						$data[$i+1]=utf8_decode($res[0]);
					}

					$this->Row($data,array(0));
			}
		}

		if ($datos['id_proyecto1']==1 || ($datos['id_proyecto1']>=5 && $datos['id_proyecto1']<= 10)){
		$this->Ln(10);

			$consulta= "SELECT descripcion, superficie, observaciones FROM `cat_desglose_areas` a, `formato2_desglose` f WHERE a.id_area=f.id_area AND anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
			$sql_desglose=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			if (mysql_num_rows($sql_desglose)>0){
				$w=array(50,60,87);
				$this->SetWidths($w,array('C','C','C'),4,1);
				$this->Row(array("DESGLOSE DE �REAS (USO DE SUELO)","SUPERFICIE MTS. CUAD.","OBSERVACIONES SEG�N NORMA"),array(1,1,1,1));
				$this->SetWidths($w,array('J','R','J'),4,1);
			
				while($areas= mysql_fetch_array($sql_desglose)){
		
						$this->Row(array(utf8_decode($areas['descripcion']),number_format($areas['superficie'],2,".",","),utf8_decode($areas['observaciones'])),array(0));
				}
			}
			mysql_free_result($sql_desglose);
		}


		$consulta= "select observaciones from formato_observaciones WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND formato=2";
		$sql_obs=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos_obs= mysql_fetch_array($sql_obs);
		mysql_free_result($sql_obs);

		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->MultiCell(197,4,"OBSERVACIONES",0,'J',0);			
		$this->SetFont('Arial','',8);
		$this->MultiCell(197,4,utf8_decode($datos_obs['observaciones']),0,'J',0);			
		
		$this->Ln(30);

		$consulta= "select * from seguimiento WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND id_seguimiento=2";
		$sql_seguimiento=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos= mysql_fetch_array($sql_seguimiento);
		mysql_free_result($sql_seguimiento);

		
			$w=array(98,99);
			$this->SetWidths($w,array('C','C'),4,0);
			$consulta= "SELECT * FROM cat_usuario WHERE login='".$datos['login']."'";
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


