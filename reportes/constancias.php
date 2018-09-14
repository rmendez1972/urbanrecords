<?PHP 
header("Content-Type: text/html; charset=iso-8859-1 ");
define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
include ("../libreria/config.php"); 
class PDF extends FPDF
{
var $titulo;
private $status=1;
	//Encabezado
	function Header()
	{
	
		//Logo
		$this->Image('../images/LogoQRoo.jpg',10,10,45,20);
		$this->Image('../images/SloganQroo.jpg',225,10,45,20);
	
		$this->SetFont('Arial','B',14);
	
		// titulo
		$this->MultiCell(260,6,"SECRETAR�A DE DESARROLLO TERRITORIAL URBANO SUSTENTABLE",0,'C',0);
		$this->SetFont('Arial','B',12);
		$this->MultiCell(260,6,"Sistema de Control de Constancias de Compatibilidad Urban�stica",0,'C',0);
		$this->SetX(55);
		$this->SetFont('Arial','B',12);
	    $this->MultiCell(170,6,utf8_decode($this->titulo),0,'C',2);

		$this->SetY(35);
		$this->SetFillColor(213,217,214);
		$this->SetTextColor(0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(.3);
		$w=array(35,30,60,60,35,40);
		$this->SetFont('Arial','B',8);
		$this->SetWidths($w,array('C','C','C','C','C'),5,1);
		
		if($this->status==1)
			$this->Row(array("FICHA","FECHA DE TR�MITE","DESARROLLO","SOLICITANTES","SUPERFICIES (m�)","MUNICIPIO"),array(1,1,1,1,1,1));
		else{
			$this->SetFont('Arial','B',8);
			$this->Cell(45,5,"MUNICIPIO",1,0,"C",1);
			$this->Cell(80,5,"TIPO DE TR�MITE",1,0,"C",1);
			$this->Cell(30,5,"CANTIDAD",1,0,"C",1);
			$this->Cell(30,5,"SUPERFICIE (m�)",1,1,"C",1);	
		}
		$this->SetWidths($w,array('C','C','J','J','R','J'),6,1);
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
	$this->SetFont('Arial','',9);

	$conexion=mysql_connect($Servidor,$UsrMysql,$ClaveMysql);
	$base=mysql_select_db($DB,$conexion);


		$w=array(35,30,60,60,35,40);
		$this->SetWidths($w,array('C','C','J','J','R','J'),5,1);
		$this->SetFont('Arial','',8);


		$consulta= "SELECT s.*, tp.descripcion AS tipo_proyecto, tp.abreviatura, cm.descripcion AS municipio 
					FROM`solicitud` s, cat_tipo_proy tp, cat_municipios cm 
					WHERE id_tipo=id_proyecto1 AND s.id_municipio=cm.id_municipio
						AND anio=".$_POST['anio']."";

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

		
		while($datos= mysql_fetch_array($resultado)){
			$consulta= "select * from solicitantes s, cat_tipo_solicitantes t WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$datos['id_solicitud']." AND anio=".$datos['anio'];

			$sql_solicitante=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$data_solicitante="";
			while($solicitantes= mysql_fetch_array($sql_solicitante)){
				$data_solicitante.=$solicitantes['descripcion']." ".$solicitantes['nombre'];
				if ($solicitantes['telefono']!="")
					$data_solicitante.=", ".$solicitantes['telefono'];
				if ($solicitantes['celular']!="")
					$data_solicitante.=", ".$solicitantes['celular'];
				if ($solicitantes['correo']!="")
					$data_solicitante.=", ".$solicitantes['correo'];
			}
			mysql_free_result($sql_solicitante);	
			$this->Row(array($datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio'],date("d/m/Y",strtotime($datos["fecha_ingreso"])),utf8_decode($datos["propietario"])."\n".utf8_decode($datos["nombre_proyecto"]), utf8_decode($data_solicitante),number_format($datos["superficie"],2,".",","),utf8_decode($datos["municipio"])),array(0));
		}
		mysql_free_result($resultado);	
	$this->Ln(6);

	$this->status=2;
	
	$consulta="select M.descripcion,TP.descripcion,count(*),sum(s.superficie) from solicitud s inner join cat_municipios M on s.id_municipio=M.id_municipio inner join cat_tipo_proy TP on s.id_proyecto1=TP.id_tipo where s.anio=".$_POST["anio"];
	
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
	
	$sql=$consulta;
	$sql.=" group by M.descripcion,TP.descripcion order by M.descripcion,TP.descripcion";
	
	$consulta=mysql_query($sql,$conexion);
	$this->SetFont('Arial','B',8);
	$this->Cell(45,5,"MUNICIPIO",1,0,"C",1);
	$this->Cell(80,5,"TIPO DE TR�MITE",1,0,"C",1);
	$this->Cell(30,5,"CANTIDAD",1,0,"C",1);
	$this->Cell(30,5,"SUPERFICIE (m�)",1,1,"C",1);
	
	$this->SetFont('Arial','',8);
	$cantot=0;
	$suptot=0;
	while($res=mysql_fetch_array($consulta)){
		$this->Cell(45,5,utf8_decode($res[0]),1,0,"C",0);
		$this->Cell(80,5,utf8_decode($res[1]),1,0,"C",0);
		$this->Cell(30,5,number_format($res[2],0,".",","),1,0,"C",0);
		$this->Cell(30,5,number_format($res[3],2,".",","),1,1,"R",0);
		
		$cantot+=$res[2];
		$suptot+=$res[3];
	}
	
	$this->Cell(100,5,"");
	$this->Cell(25,5,"Total: ",0,0,"R");
	$this->Cell(30,5,number_format($cantot,0,".",","),1,0,"C",0);
	$this->Cell(30,5,number_format($suptot,2,".",","),1,1,"R",0);
	
	mysql_close($conexion);
	
} // fin del function
}// fin del class

	$conexion=mysql_connect($Servidor,$UsrMysql,$ClaveMysql);
	$base=mysql_select_db($DB,$conexion);

		$titulo="CONSTANCIAS";
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
$pdf->Output("ficha_constancia.pdf", "D");

?> 


