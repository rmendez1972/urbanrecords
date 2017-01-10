<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

header("Content-Type: text/html; charset=iso-8859-1 ");
define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
include ("../libreria/config.php");

class PDF extends FPDF
{

	//Encabezado
	function Header()
	{


		//Logo comentado en espera de nueva imagen de gobierno
		$this->Image('../images/LogoQRoo.png',10,10,45,20);
		$this->Image('../images/SloganQroo.png',162,10,45,20);

		$this->SetFont('Arial','B',10);

		// titulo

		$this->MultiCell(197,5,"Gobierno del Estado de Quintana Roo",0,'C',0);
		$this->MultiCell(197,5,utf8_decode("Secretaría de Desarrollo Urbano y Vivienda"),0,'C',0);
		$this->MultiCell(197,5,utf8_decode("Subsecretaría de Desarrollo Urbano"),0,'C',0);
		$this->MultiCell(197,5,utf8_decode("DIRECCIÓN DE ADMINISTRACIÓN URBANA"),0,'C',0);
		$this->SetFont('Arial','',8);
		$this->MultiCell(197,4,utf8_decode("Av. Álvaro Obregón No. 474 Col. Centro C.P. 77000, Chetumal, Quintana Roo"),0,'C',0);
		$this->MultiCell(197,4,"Tel. (01-983 8351700, 8324108, 2853588 Ext. 228)  constancias_sedu@qroo.gob.mx",0,'C',0);
		//$this->MultiCell(197,4,"",0,'C',0);
		$this->SetFont('Arial','B',9);
		$this->Ln(5);
		$this->MultiCell(197,5,utf8_decode("CONSTANCIA DE COMPATIBILIDAD URBANÍSTICA ESTATAL"),0,'C',0);


//		$this->Ln(10);

		$this->SetFillColor(213,217,214);
		$this->SetTextColor(0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','',9);


	}	//Fin del header



	//Page footer

	//Pie de p�gina
	function Footer()
	{

		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');

		$this->SetX(10);
		$this->SetFont('Arial','',6);
		$this->Cell(0,10,utf8_decode('Sistema de Control de Constancias de Compatibilidad Urbanística'),0,0,'L');
		$this->SetFont('Arial','',6);
		$this->Cell(0,10,utf8_decode('Fecha de impresión: '.date('Y-m-d  g:i a')),0,0,'R');

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
    mysql_query("set names 'utf8'",$conexion);


		$w=array(30,30,80,80,40);
		$this->SetWidths($w,array('C','C','J','J','J'),5,1);

		//mb_internal_encoding("UTF-8");

		$consulta= "SELECT s.*, tp.descripcion AS tipo_proyecto, tp.abreviatura, cm.descripcion AS municipio
					FROM`solicitud` s, cat_tipo_proy tp, cat_municipios cm
					WHERE id_tipo=id_proyecto1 AND s.id_municipio=cm.id_municipio";


		if (isset($_GET['anio']))
			$consulta.= " AND anio=".$_GET['anio']."";

		if (isset($_GET['id_solicitud']))
			$consulta.= " AND s.id_solicitud=".$_GET['id_solicitud'];

		if (isset($_POST['anio']))
			$consulta.= " AND anio=".$_POST['anio']."";

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

		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz� correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$cont=0;
		while($datos= mysql_fetch_array($resultado)){
			if ($cont<>0) $this->AddPage(); else $cont++;
			$this->SetFont('Arial','B',9);
			$this->MultiCell(197,5,utf8_decode($datos['tipo_proyecto']),0,'C',0);
			$this->SetFont('Arial','',8);

			$meses= array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
			$mes=(int) strftime("%m",strtotime($datos["fecha_ingreso"]));
			$fecha="CHETUMAL, QUINTANA ROO A ".strftime("%d",strtotime($datos["fecha_ingreso"]))." DE ".$meses[$mes]." DEL ".strftime("%Y",strtotime($datos["fecha_ingreso"]));
			$this->Ln(5);
			$this->MultiCell(197,6,$fecha,0,'R',0);
//			$this->Ln(10);
			$this->SetFont('Arial','B',9);
		    $this->MultiCell(197,6,"Ficha No. ".$datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio'],0,'C',1);
			$this->SetFont('Arial','',8);
			$this->Ln(5);

			$this->Cell(30,6,"PROPIETARIO:",0,0,'L',0);
			$this->MultiCell(167,6,utf8_decode($datos['propietario']),'B','J',0);
			$this->Cell(30,6,utf8_decode("TRÁMITE:"),0,0,'L',0);
			$this->MultiCell(167,6,utf8_decode($datos["nombre_proyecto"]),'B','J',0);
			$this->Cell(30,6,utf8_decode("DIRECCIÓN:"),0,0,'L',0);
			$this->MultiCell(167,6,utf8_decode($datos["direccion"]),'B','J',0);
			$this->Cell(30,6,"MUNICIPIO:",0,0,'L',0);
			$this->MultiCell(167,6,utf8_decode($datos["municipio"]),'B','J',0);

			if ($datos['id_localidad']!=0){
					$consulta= "select * from cat_localidades WHERE id_municipio=".$datos['id_municipio']." AND id_localidad=".$datos['id_localidad'];

					$sql_loc=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$localidad= mysql_fetch_array($sql_loc);
					//echo strtoupper($localidad['descripcion']);

					$this->Cell(30,6,"LOCALIDAD:",0,0,'L',0);
					$this->MultiCell(167,6,strtoupper(utf8_decode($localidad['descripcion'])),'B','J',0);
			}

			$consulta= "select * from solicitantes s, cat_tipo_solicitantes t WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$datos['id_solicitud']." AND anio=".$datos['anio'];

			$sql_solicitante=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$data_solicitante="";
			while($solicitantes= mysql_fetch_array($sql_solicitante)){
				if ($data_solicitante!="") $data_solicitante.="\n";
				$data_solicitante.=utf8_decode($solicitantes['descripcion']).": ".utf8_decode($solicitantes['nombre']);
				if ($solicitantes['telefono']!="")
					$data_solicitante.=", TEL. ".$solicitantes['telefono'];
				if ($solicitantes['celular']!="")
					$data_solicitante.=", CEL. ".$solicitantes['celular'];
				if ($solicitantes['correo']!="")
					$data_solicitante.=", CORREO: ".$solicitantes['correo'];

			}
			mysql_free_result($sql_solicitante);
			$this->Cell(30,5,"SOLICITANTE",0,0,'L',0);
			$this->MultiCell(167,5,$data_solicitante,'B','J',0);

			if ($datos["observaciones"]!=""){
				$this->Cell(30,5,"OBSERVACIONES:",0,0,'L',0);
				$this->MultiCell(167,5,utf8_decode($datos["observaciones"]),'B','J',0);
			}

	$this->Ln(4);

		$consulta= "select * from cat_requisitos r, doctos d WHERE r.id_requisito=d.id_requisito AND id_solicitud=".$datos['id_solicitud']." AND anio=".$datos['anio'];

		$sql_req=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$w=array(30,167);
			$this->SetFont('Arial','B',8);
			$this->SetWidths($w,array('C','C'),5,1);
			$this->Row(array(utf8_decode("ENTREGÓ"),"DOCUMENTO"),array(1,1,1,1,1));
			$this->SetWidths($w,array('C','J'),4,1);
			$this->SetFont('Arial','',7);
			while($requisitos= mysql_fetch_array($sql_req)){
				$this->Row(array(utf8_decode($requisitos['entrego']),utf8_decode($requisitos['descripcion'])),array(0));
			}

			$this->Ln(4);
			$w=array(130,67);
			$this->SetWidths($w,array('C','C'),4,0);
			$this->Row(array("",utf8_decode("Recibió")),array(0));
			$this->Ln(4);
			$consulta= "SELECT * FROM cat_usuario WHERE login='".$datos['login']."'";
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$usuario= mysql_fetch_array($sql_user);
			$this->Row(array("",utf8_decode($usuario['nombre'])." ".utf8_decode($usuario['apellido_paterno'])." ".utf8_decode($usuario['apellido_materno'])),array(0));
			$this->Row(array("",utf8_decode("Departamento de Control Técnico")),array(0));


			$this->Ln(4);

			$this->SetY(240);
			$pie="*En cumplimiento a lo previsto por el artículo 33, fracción III de la ley de Transparencia y Acceso a la Información Pública del Estado de Quintana Roo, se le informa que el propósito de recabar sus datos personales se circunscribe única y exclusivamente a contar con la información que señala el artículo 51 de la citada ley, para atender y dar seguimiento a la solicitud.";
			$this->MultiCell(197,4,utf8_decode($pie),0,'J',0);

			$this->Ln(2);

			$this->SetFont('Arial','B',7.5);
			$this->SetTextColor(200,0,0);
			$this->MultiCell(197,4,utf8_decode("PARA GENERAR SU ORDEN DE PAGO ANTE TRIBUTANET DEBERÁ OBTENER PREVIAMENTE LA ORDEN DE PAGO QUE OTORGA ESTA DEPENDENCIA"),0,"C",0);
			$this->SetTextColor(0,0,0);
		}
		mysql_free_result($resultado);




	mysql_close($conexion);

} // fin del function
}// fin del class

//Page header
//Instanciation of inherited class
$pdf=new PDF("P","mm","Letter");
$pdf->Open("ficha_constancia.pdf", "D");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->FancyTable($Servidor,$UsrMysql,$ClaveMysql,$DB);
$pdf->Output();

?>


