<?PHP 
define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
require("../libreria/Utilidades.php");
require("../libreria/ConexionPDO.php");

class PDF extends FPDF{
	public $slogan,$noConstancia,$idSol,$anioSol,$fecha_ingreso,$tipoProyecto,$superficieTotal, $direccion, $municipio, $localidad, $texto2, $munID,$fechaConst,$numero,$texto1,$nooficio,$tipoPago, $noPago, $legalSancion;

	function Header(){
		$ancho=$this->w-$this->lMargin-$this->rMargin;
		
		$this->Image('../images/LogoQRoo.jpg',25,20,100,40);
		$this->Image('../images/SloganQroo.jpg',485,20,100,40);
		
		$this->Rect(335,65,250,40);
		
		$this->SetFont('Arial','B',8);
		$this->SetXY(340,68);
		$this->Cell(70,15,"REFERENCIA:");
		$this->SetFont('Arial','',8);
		$this->Cell(180,15,"SECRETARIA DE DESARROLLO URBANO Y VIVIENDA");
		
		$this->Ln(20);
		
		$this->SetX(340);
		$this->SetFont('Arial','B',8);
		$this->Cell(70,15,"OFICIO No.:");
		$this->SetFont('Arial','',8);
		$this->Cell(180,15,"SEDU/DS/SDU/DAU/".$this->nooficio."/".$this->anioSol);
		
		$this->Ln(18);
		
		$this->SetFont('Arial','',7);
		
		$this->Cell($ancho,11,$this->slogan,0,1,"R");
		
		$this->SetFont('Arial','B',8);
		$this->Cell($ancho,11,"ASUNTO: ORDEN DE PAGO No. ".$this->noPago,0,1,"R");
		$this->Cell($ancho,11,"CLAVE: 010216",0,1,"R");
	}
	
	function Footer(){
		$ancho=$this->w-$this->lMargin-$this->rMargin;
		
		$this->SetY(-110);
			
		$this->SetFont('Arial','',6.5);
		$this->Cell($ancho,10,"Secretaría de Desarrollo Urbano y Vivienda",0,1,"R");
		$this->Cell($ancho,10,"Efraín Aguilar #418 Col. Campestre",0,1,"R");
		$this->Cell($ancho,10,"C.P. 77030. Chetumal, Quintana Roo, México",0,1,"R");
		$this->Cell($ancho,10,"Tel. (983) 832 41 08, 285 35 88, 129 3316-18 Ext. 128",0,1,"R");
		$this->Cell($ancho,10,"http://seduvi.qroo.gob.mx",0,1,"R");
		
		$this->Image('../images/imgreporte.jpg',0,$this->y,$this->w,50);
	}
	
	function generarCCPS(){
		$res=Conexion::ejecutarConsulta("select nombre from ccps where id_municipio=? and constancia=0 order by orden asc",array($this->munID),PDO::FETCH_NUM);
		$copias="";
		foreach($res as $row){
			$copias.=($copias!=""?"#":"").$row[0];
		}
		
		Conexion::ejecutar("update constancias_pagos set ccps=? where anio=? and id_solicitud=? and id_tipo_pago=?",array($copias,$this->anioSol,$this->idSol,$this->tipoPago));
		
		return $copias;
	}
	
	function iniciar($solicitud, $anio){
		$this->idSol=$solicitud;
		$this->anioSol=$anio;
		
		$sql="select S.id_solicitud, S.anio, M.abreviatura, S.fecha_ingreso, S.id_proyecto1, S.superficie, S.direccion, M.descripcion, L.descripcion,M.id_municipio,CP.id_orden,date_format(CP.transaccion,'%d/%c/%Y') from solicitud S inner join cat_municipios M on S.id_municipio = M.id_municipio inner join cat_localidades L on S.id_localidad=L.id_localidad and S.id_municipio=L.id_municipio inner join constancias_pagos CP on S.anio=CP.anio and S.id_solicitud=CP.id_solicitud where S.id_solicitud=? and S.anio=?";
		
		$cons=Conexion::ejecutarConsulta($sql,array($this->idSol,$this->anioSol),PDO::FETCH_NUM);

		foreach($cons as $row){
			$num=$row[0];
			$num=($num<100?"0":"").($num<10?"0":"").$num;
			$this->noConstancia=$num."-".$row[2]."-".$row[1];
			
			$num=$row[10];
			$num=($num<100?"0":"").($num<10?"0":"").$num;
			$this->noPago=$num."/".$row[1];
			
			$this->fecha_ingreso=$row[3];
			$this->tipoProyecto=$row[4];
			$this->superficieTotal=$row[5];
			$this->direccion=$row[6];
			$this->municipio=$row[7];
			$this->localidad=$row[8];
			$this->munID=$row[9];
			$this->fechaConst=$row[11];
			
			break;
		}
			
		$this->nooficio=Conexion::ejecutarEscalar("select numero from constancias_pagos where anio=? and id_solicitud=? and id_tipo_pago=?",array($this->anioSol,$this->idSol,$this->tipoPago));
		
		$this->slogan=Utilidades::obtenerConfiguracion("SLOGAN",$this->fecha_ingreso);
		$this->legalSancion=Utilidades::obtenerConfiguracion("SANCION",$this->fecha_ingreso);
	}
	
	function generar(){
		$ancho=$this->w-$this->lMargin-$this->rMargin;
		
		$this->SetFont('Arial','B',8.5);
		
		
		
		$cons=Conexion::ejecutarConsulta("select propietario,nombre_proyecto from solicitud where id_solicitud=? and anio=?",array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
		foreach($cons as $row){
			$propietario=$row[0];
			$nombreProy=$row[1];
			break;
		}
		
		$solicitantes=array();
		$cons=Conexion::ejecutarConsulta("select S.nombre,T.descripcion from solicitantes S inner join cat_tipo_solicitantes T on S.id_tipo=T.id_tipo where S.id_solicitud=? and S.anio=? and S.id_tipo <> 1",array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
		$i=0;
		foreach($cons as $row){
			$solicitantes[$i++]=$row;
		}
		
		for($i=0;$i<count($solicitantes);$i++){
			$this->Cell(300,12,$solicitantes[$i][0],0,1);
			
			if($i==count($solicitantes[$i])-1)
				$add=" DE";
			else
				$add="";
				
			$this->Cell(300,12,$solicitantes[$i][1].$add,0,1);
		}
			
		
		$this->Cell(300,12,$propietario,0,1);
		
		$this->Cell(300,12,"PRESENTE:",0,1);
		
		$this->Ln(10);
		
		$this->SetFont('Arial','',9.5);
		
		$fila=Conexion::ejecutarFila("select derechos,cantidadLetras from constancias_pagos where anio=? and id_solicitud=? and id_tipo_pago=?",array($this->anioSol,$this->idSol,$this->tipoPago),PDO::FETCH_NUM);
		$derechos=Utilidades::formatoDecimal($fila[0]);
		
		if($this->tipoPago==1 || $this->tipoPago==2){
			$txtcontenido="Deberá pagar a la Recaudadora de Rentas del Gobierno del Estado la cantidad de $ $derechos ";
			while($this->GetStringWidth($txtcontenido)<$ancho-14)
				$txtcontenido.="-";
			$this->MultiCell($ancho,11,$txtcontenido);
			
			$cantletras=" (Son: $fila[1] M.N.) ";
			while($this->GetStringWidth($cantletras)<$ancho-16)
				$cantletras="-".$cantletras."-";
			$this->MultiCell($ancho,11,$cantletras);
			
			$loc=!($this->localidad=="SIN LOCALIDAD");
			
			$txtcontenido="";
			if($this->tipoPago==2){
				$txtcontenido="Equivalente al 0.5% del presupuesto de urbanización que asciende a la cantidad: $ ".Utilidades::formatoDecimal($fila[0]*1000/5)." ";	
			}
			
			$txtcontenido.="por el proyecto de $nombreProy, ubicado en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo.";
			$this->MultiCell($ancho,11,$txtcontenido);
			
			$this->Ln(10);
			
			$this->MultiCell($ancho,11,"El presente documento tiene una vigencia de 30 días calendario contados a la fecha de recepción del documento, posteriormente ésta Secretaría se reserva el derecho de revocarlo.");
		}
		else if($this->tipoPago==3){
			
			$txtcontenido="A efecto de REGULARIZAR la situación en el proyecto de $nombreProy, ubicado en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo, esta Secretaría de Desarrollo Urbano, ".$this->legalSancion." impone una sanción administrativa por la cantidad de:";
			
			$this->MultiCell($ancho,11,$txtcontenido);
			
			$txtcontenido="$ $derechos";
			while($this->GetStringWidth($txtcontenido)<$ancho-16)
				$txtcontenido="-".$txtcontenido."-";
			$this->MultiCell($ancho,11,$txtcontenido);
			
			$cantletras=" (Son: $fila[1] pesos 00/100 M.N.) ";
			while($this->GetStringWidth($cantletras)<$ancho-16)
				$cantletras="-".$cantletras."-";
			$this->MultiCell($ancho,11,$cantletras);
			
			$this->Ln(11);
			
			$this->MultiCell($ancho,11,"De igual manera le informo que en caso de reincidencia, la multa podrá ser duplicada.");
		}
		
		
		
		$bm=$this->bMargin;
		$this->SetAutoPageBreak(1,60);
		
		$divf=explode("/",$this->fechaConst);
		$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		
		$this->Ln(15);
		$this->SetFont('Arial','B',8.5);
		$this->MultiCell($ancho,15,"A T E N T A M E N T E:");
		//$this->MultiCell($ancho,15,"\"SUFRAGIO EFECTIVO. NO REELECCIÓN\"");
		$this->MultiCell($ancho,15,"Chetumal, Quintana Roo, a ".$divf[0]." de ".$meses[$divf[1]]." de ".$divf[2]);
		$this->MultiCell($ancho,15,"EL SECRETARIO DE DESARROLLO URBANO.");
		
		$secretario=Conexion::ejecutarEscalar("select nombre from cat_usuario where id_perfil=4 order by estatus desc",NULL);
			
		$this->Ln(35);
		$this->MultiCell($ancho,15,$secretario);
		
		$copias=Conexion::ejecutarEscalar("select ccps from constancias_pagos where id_solicitud=? and anio=? and id_tipo_pago=?",array($this->idSol,$this->anioSol,$this->tipoPago));
		if($copias==""){
			$copias=$this->generarCCPS();
		}
		$ccps=explode("#",$copias);
		
		$tmpy=$this->y;
		$this->SetY(-130-(count($ccps)*11));
		
		if($tmpy>$this->y)
			$this->SetY($tmpy);
			
		$this->SetFont('Arial','',7);
		
		foreach($ccps as $ccp){
			$this->MultiCell($ancho,11,"C.c.p.- ".$ccp);	
		}
		
		$iniSec=Conexion::ejecutarEscalar("select iniciales from cat_usuario where id_perfil=4 order by estatus desc",NULL);
		$iniSub=Conexion::ejecutarEscalar("select iniciales from cat_usuario where id_perfil=5 order by estatus desc",NULL);
		$iniDir=Conexion::ejecutarEscalar("select iniciales from cat_usuario where id_perfil=6 order by estatus desc",NULL);
		$iniRev=Conexion::ejecutarEscalar("select iniciales from cat_usuario where id_perfil=3 order by estatus desc",NULL);
		
		$this->SetFont('Arial','B',7);
		$this->MultiCell($ancho,11,"$iniSec/$iniSub/$iniDir/$iniRev");
		
		$this->SetAutoPageBreak(1,$bm);
	}
}

//Page header
//Instanciation of inherited class
Conexion::init(false);

$solicitud=$_GET["idsol"];
$anio=$_GET["anio"];
$numero=$_GET["numero"];

$pdf=new PDF("P","pt","Letter");
$pdf->SetAutoPageBreak(1,115);
$pdf->bMargin=115;
$pdf->tipoPago=$numero;
$pdf->iniciar($solicitud,$anio);
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->generar();
$pdf->Output();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
</body>
</html>
