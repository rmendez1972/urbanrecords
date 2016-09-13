<?PHP 
define('FPDF_FONTPATH','../font/');

require('../libreria/fpdf.php');
require("../libreria/Utilidades.php");
require("../libreria/ConexionPDO.php");

class PDF extends FPDF{
	public $slogan,$noConstancia,$idSol,$anioSol,$fecha_ingreso,$tipoProyecto,$superficieTotal, $direccion, $municipio, $localidad, $texto2, $munID,$fechaConst,$numero,$texto1;

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
		$this->Cell(180,15,"SEDU/DS/SDU/DAU/".$this->numero."/".$this->anioSol);
		
		$this->Ln(18);
		
		$this->SetFont('Arial','',7);
		
		$this->Cell($ancho,11,$this->slogan,0,1,"R");
		
		$this->SetFont('Arial','B',8);
		$this->Cell($ancho,11,"ASUNTO: CONSTANCIA DE COMPATIBILIDAD",0,1,"R");
		
		$this->Cell($ancho,11,"URBANÍSTICA ESTATAL No. ".$this->noConstancia,0,1,"R");
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
	
	function iniciar($solicitud, $anio){
		$this->idSol=$solicitud;
		$this->anioSol=$anio;
		
		$sql="select S.id_solicitud, S.anio, M.abreviatura, S.fecha_ingreso, S.id_proyecto1, S.superficie, S.direccion, M.descripcion, L.descripcion,M.id_municipio,date_format(C.fecha,'%d/%c/%Y') from solicitud S inner join cat_municipios M on S.id_municipio = M.id_municipio inner join cat_localidades L on S.id_localidad=L.id_localidad and S.id_municipio=L.id_municipio inner join constancias C on S.id_solicitud=C.id_solicitud and S.anio=C.anio where S.id_solicitud=? and S.anio=?";
		
		$cons=Conexion::ejecutarConsulta($sql,array($this->idSol,$this->anioSol),PDO::FETCH_NUM);

		foreach($cons as $row){
			$num=$row[0];
			$num=($num<100?"0":"").($num<10?"0":"").$num;
			$this->noConstancia=$num."-".$row[2]."-".$row[1];
			
			$this->fecha_ingreso=$row[3];
			$this->tipoProyecto=$row[4];
			$this->superficieTotal=$row[5];
			$this->direccion=$row[6];
			$this->municipio=$row[7];
			$this->localidad=$row[8];
			$this->munID=$row[9];
			$this->fechaConst=$row[10];
			
			break;
		}
			
		
		$this->slogan=Utilidades::obtenerConfiguracion("SLOGAN",$this->fecha_ingreso);
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
		
		$this->SetFont('Arial','',8);
		
		$constancia1=Utilidades::obtenerConfiguracion("LEGALES",$this->fecha_ingreso);
		
		$contenido=$constancia1." ".$nombreProy;
			
		$this->texto1=Conexion::ejecutarEscalar("select texto2 from constancias where id_solicitud=? and anio=? and numero=?",array($this->idSol,$this->anioSol,$this->numero));
		$this->proyecto($contenido);
		
		$this->Ln(10);
		
		$this->texto2=Conexion::ejecutarEscalar("select texto from constancias where id_solicitud=? and anio=? and numero=?",array($this->idSol,$this->anioSol,$this->numero));
		$this->MultiCell($ancho,11,$this->texto2);
		
		$this->Ln(10);
		
		$this->MultiCell($ancho,11,"El presente documento tiene una vigencia de 3 años contados a partir de la fecha de su expedición y no autoriza ningún concepto de construcción de obra, por lo que ésta Secretaría se reserva el derecho de revocarlo si se contraviene a los términos en él especificados.");
		
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
		
		$copias=Conexion::ejecutarEscalar("select ccps from constancias where id_solicitud=? and anio=? and numero=?",array($this->idSol,$this->anioSol,$this->numero));
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
	
	function proyecto($txt){
		$ancho=$this->w-$this->lMargin-$this->rMargin;
		$tp=$this->tipoProyecto;
		
		/*
		1 Fraccionamiento o Conjunto habitacional / FRACC
		2 Subdivisión de predios / SUB
		3 Condominio / CON
		4 Fusion / FUS
		*/
		
		///////////////// DATOS DEL PROYECTO //////////////////////////
		if($tp==1 || $tp>=5 && $tp<=10){
			//FRACC
			$sup="".$this->superficieTotal;
			$has=Utilidades::aHectareas($sup);
			$loc=!($this->localidad=="SIN LOCALIDAD");
			$txt.=" el cual contempla una superficie total de ".$this->superficieTotal." m² equivalentes a ".$has." Ha; ubicado en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo; presentado por usted ante ésta Secretaría a mi cargo; ";
		}
		else if($tp==2){
			//SUB
			$sup="".$this->superficieTotal;
			$has=Utilidades::aHectareas($sup);
			$loc=!($this->localidad=="SIN LOCALIDAD");
			$txt.=" al predio de su propiedad, el cual contempla una superficie total de ".$sup." m², equivalente a ".$has." has, ubicado en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo; ";
		}
		else if($tp==3 || $tp==11 || $tp==12 || $tp==23){
			//CON
			$sup="".$this->superficieTotal;
			$has=Utilidades::aHectareas($sup);
			$loc=!($this->localidad=="SIN LOCALIDAD");
			$txt.=" al predio de su propiedad, el cual contempla una superficie total de ".$sup." m², equivalente a ".$has." has, ubicado en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo; ";
		}
		else if($tp==4){
			//FUS
			$loc=!($this->localidad=="SIN LOCALIDAD");
			$txt.=" a los predios de su propiedad, los cuales contempla las siguientes superficies: ";
			$cons=Conexion::ejecutarConsulta("select lote,area from lotes where id_solicitud=? and anio=?",array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
			
			$lotes=array();
			$i=0;
			foreach($cons as $row)
				$lotes[$i++]=$row;
				
			for($i=0;$i<count($lotes);$i++){
				$linea="";
				if($i==count($lotes)-1)
					$linea.=" y ";
				else if($i>0)
					$linea.=", ";
				$linea.=$lotes[$i][0]." con una superficie de ".$lotes[$i][1]." m²";
				
				$txt.=$linea;
			}
			
			$txt.="; ubicados en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo; ";
		}
		else if($tp>=13 && $tp<=16){
			//Centros comerciales, hospitales
			$sup="".$this->superficieTotal;
			$has=Utilidades::aHectareas($sup);
			$loc=!($this->localidad=="SIN LOCALIDAD");
			$txt.=" el cual contempla una superficie total de ".$this->superficieTotal." m² equivalentes a ".$has." Ha; ubicado en ".$this->direccion.($loc?" en la ciudad de ".$this->localidad.",":" del")." Municipio de ".$this->municipio.", Quintana Roo; ";
		}
		
		$this->MultiCell($ancho,11,$txt.$this->texto1);
		$this->Ln(10);
		
		///////////////// TABLAS ////////////////////
		if($tp==1 || $tp>=5 && $tp<=10){
			//FRACC
			$titulos=array("Uso de Suelo","Descripción","Superficies (m²)");
			$datos=array();
			$i=0;
			$sql="select A.descripcion, F.observaciones, F.superficie from formato2_desglose F inner join cat_desglose_areas A on F.id_area=A.id_area where F.id_solicitud=? and F.anio=?";
			
			$cons=Conexion::ejecutarConsulta($sql,array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
			foreach($cons as $row)
				$datos[$i++]=$row;
			
			$tams=$this->obtenerTams($titulos,$datos);
			
			$totalw=0;
			for($i=0;$i<count($tams);$i++){
				$tams[$i]+=15;
				$totalw+=$tams[$i];	
			}
			
			$inix=$ancho/2-$totalw/2;
			
			$this->SetFont('Arial','B',8);
			$this->SetX($inix);
			for($i=0;$i<count($titulos);$i++){
				$this->Cell($tams[$i],15,$titulos[$i],1,0,"C");
			}
			$this->Ln(15);
			
			$this->SetFont('Arial','',8);
			for($i=0;$i<count($datos);$i++){
				$this->SetX($inix);
				for($j=0;$j<count($datos[$i]);$j++){
					$this->Cell($tams[$j],15,$datos[$i][$j],1,0);
				}
				$this->Ln(15);
			}
		}
		else if($tp==2){
			//SUB
			$titulos=array("Lotes Y/O Fracciones","Área en m²","Superficie total");
			$datos=array();
			$i=0;
			$cons=Conexion::ejecutarConsulta("select lote,area from lotes where id_solicitud=? and anio=?",array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
			foreach($cons as $row)
				$datos[$i++]=$row;
				
			$tams=$this->obtenerTams($titulos,$datos);
			
			$totalw=0;
			for($i=0;$i<count($tams);$i++){
				$tams[$i]+=15;
				$totalw+=$tams[$i];	
			}
			
			$inix=$ancho/2-$totalw/2;
			
			$this->SetFont('Arial','B',8);
			$this->SetX($inix);
			
			$this->Cell($tams[0]+$tams[1],15,"Subdivisión",1,0,"C");
			$this->Cell($tams[2],30,"Superficie Total",1,1,"C");
			
			$this->SetXY($inix,$this->y-15);
			for($i=0;$i<count($titulos)-1;$i++){
				$this->Cell($tams[$i],15,$titulos[$i],1,0,"C");
			}
			$this->Ln(15);
			
			$this->SetFont('Arial','',8);
			$suma=0;
			for($i=0;$i<count($datos);$i++){
				$this->SetX($inix);
				for($j=0;$j<count($datos[$i]);$j++){
					$this->Cell($tams[$j],15,$datos[$i][$j],1,0);
				}
				$suma+=$datos[$i][1];
				if($i==count($datos)-1){
					$tx=$this->x;
					$this->SetXY($tx,$this->y-15*(count($datos)-1));
					$this->Cell($tams[2],count($datos)*15,$suma." m²",1,1,"C");
				}
				else
					$this->Ln(15);
			}
		}
		else if($tp==3 || $tp==11 || $tp==12){
			//CON
			$titulos=array("Nº de Lotes","Descripción","Superficie Total (m²)");
			$tams=array(60,350,100);
			
			$this->SetX(50);
			for($i=0;$i<3;$i++)
				$this->Cell($tams[$i],15,$titulos[$i],1,0,"C");
			
			$this->Ln(15);
			
			$tempy=$this->GetY();
			
			$cons=Conexion::ejecutarConsulta("select CAT.descripcion,DES.descripcion from descripcion_condominio DES inner join cat_condominios CAT on DES.id_condominio=CAT.id_condominio where DES.id_solicitud=? and DES.anio=? order by DES.id_condominio asc",array($this->idSol, $this->anioSol),PDO::FETCH_NUM);
			
			foreach($cons as $row){
				if($row[1]!=""){
					$this->SetX(110);
					$this->SetFont('Arial','B',8);
					$this->Cell(350,15,$row[0],0,1);
					$this->SetX(110);	
					$this->SetFont('Arial','',8);
					$this->MultiCell(350,11,$row[1]);
				}
			}
			
			$finaly=$this->GetY();
			$alto=$finaly-$tempy;
			
			$nlotes=Conexion::ejecutarEscalar("select lotes from detalles_condominio where id_solicitud=? and anio=?",array($this->idSol, $this->anioSol));
			
			$this->SetXY(50,$tempy);
			$this->Cell(60,$alto,$nlotes,1,0,"C");
			$this->Cell(350,$alto,"",1,0,"C");
			$this->Cell(100,$alto,Utilidades::formatoDecimal($sup)." m²",1,1,"C");
		}
		else if($tp==4){
			//FUS
			$titulos=array("Lotes","Área en m²","Resultante","Superficie total");
			$datos=array();
			$i=0;
			$cons=Conexion::ejecutarConsulta("select L.lote,L.area,LR.lote,'1 100 000 m2' from lotes L inner join lotes_resulta2 LR on L.anio=LR.anio and L.id_solicitud=LR.id_solicitud where L.id_solicitud=? and L.anio=?",array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
			foreach($cons as $row)
				$datos[$i++]=$row;
				
			$tams=$this->obtenerTams($titulos,$datos);
			
			$totalw=0;
			for($i=0;$i<count($tams);$i++){
				$tams[$i]+=15;
				$totalw+=$tams[$i];	
			}
			
			$inix=$ancho/2-$totalw/2;
			
			$this->SetFont('Arial','B',8);
			$this->SetX($inix);
			
			$this->Cell($tams[0]+$tams[1]+$tams[2],15,"Relotificación (Fusión)",1,0,"C");
			$this->Cell($tams[3],30,"Superficie total",1,1,"C");
			$this->SetXY($inix,$this->y-15);
			$this->Cell($tams[0],15,"Lotes",1,0,"C");
			$this->Cell($tams[1],15,"Área en m²",1,0,"C");
			$this->Cell($tams[2],15,"Resultante",1,1,"C");
			
			$this->SetFont('Arial','',8);
			
			for($i=0;$i<count($datos);$i++){
				$this->SetX($inix);
				for($j=0;$j<count($datos[$i])-2;$j++){
					$this->Cell($tams[$j],15,$datos[$i][$j],1,0);
				}
				$this->Ln(15);
			}
			
			$sizey=count($datos)*15;
			$this->SetXY($inix+$tams[0]+$tams[1],$this->y-$sizey);
			
			if(count($datos)>0){
				$this->Cell($tams[2],$sizey,$datos[0][2],1,0,"C");
				$this->Cell($tams[3],$sizey,$this->superficieTotal." m²",1,1,"C");
			}
		}
		else if($tp>=13 && $tp<=16){
			//Centros comerciales y hospitales
			$titulos=array("No. Lotes","Descripción","Superficie total");
			$i=0;
			$cons=Conexion::ejecutarConsulta("select descripcion,superficie from solicitud where id_solicitud=? and anio=?",array($this->idSol,$this->anioSol),PDO::FETCH_NUM);
			foreach($cons as $row)
				$datos=$row;
			
			$inix=80;
			
			$this->SetFont('Arial','B',8);
			$this->SetX($inix);
			
			$this->Cell(70,15,$titulos[0],1,0,"C");
			$this->Cell(300,15,$titulos[1],1,0,"C");
			$this->Cell(100,15,$titulos[2],1,1,"C");
			
			$this->SetFont('Arial','',8);
			$posy=$this->y;
			$this->SetX($inix+70);
			$this->MultiCell(300,11,$datos[0],1);
			
			$hei=$this->y-$posy;
			$this->SetXY($inix,$posy);
			$this->Cell(70,$hei,"01",1,0,"C");
			$this->Cell(300,$hei,"");
			$this->Cell(100,$hei,$datos[1]." m²",1,1,"C");
		}
		else if($tp==23){
			//CONDOMINIO MAESTRO
			$i=0;
			$titulos=array();
			$res=Conexion::ejecutarConsulta("select * from condo_maestro_cols where anio=? and id_solicitud=? order by id_columna asc",array($this->anioSol,$this->idSol));
			foreach($res as $row)
				$titulos[$i++]=$row["nombre"];
				
			$datos=array();
			$res=Conexion::ejecutarConsulta("select distinct fila from condo_maestro_datos where anio=? and id_solicitud=? order by fila asc",array($this->anioSol,$this->idSol));
			$fc=0;
			foreach($res as $fila){
				$datos[$fc]=array();
				$cons=Conexion::ejecutarConsulta("select dato from condo_maestro_datos where anio=? and id_solicitud=? and fila=? order by id_columna asc",array($this->anioSol,$this->idSol,$fila["fila"]));
				$cc=0;
				foreach($cons as $reg){
					$datos[$fc][$cc++]=$reg["dato"];	
				}
				$fc++;
			}
				
			$tams=$this->obtenerTams($titulos,$datos);
			
			$totalw=0;
			for($i=0;$i<count($tams);$i++){
				$tams[$i]+=15;
				$totalw+=$tams[$i];	
			}
			
			$this->Ln(15);
			
			$inix=$ancho/2-$totalw/2;
			
			$this->SetFont('Arial','B',8);
			$this->SetX($inix);
			
			$this->SetXY($inix,$this->y-15);
			for($i=0;$i<count($titulos);$i++){
				$this->Cell($tams[$i],15,$titulos[$i],1,0,"C");
			}
			$this->Ln(15);
			
			$this->SetFont('Arial','',8);
			$suma=0;
			for($i=0;$i<count($datos);$i++){
				$this->SetX($inix);
				for($j=0;$j<count($datos[$i]);$j++){
					$this->Cell($tams[$j],15,$datos[$i][$j],1,0);
				}
				$this->Ln(15);
				
				
			}
		}
	}
	
	function obtenerTams($titulos, $datos){
		$tams=array();
		
		$i=0;
		$this->SetFont('Arial','B',8);
		for($i=0;$i<count($titulos);$i++){
			$size=$this->GetStringWidth($titulos[$i]);
			if($size>$tams[$i])
				$tams[$i]=$size;
		}
		
		$this->SetFont('Arial','',8);
		for($i=0;$i<count($datos);$i++){
			for($j=0;$j<count($datos[$i]);$j++){
				$size=$this->GetStringWidth($datos[$i][$j]);
				if($size>$tams[$j])
					$tams[$j]=$size;
			}
		}
		
		return $tams;
	}
}

//Page header
//Instanciation of inherited class
Conexion::init(false);

$solicitud=$_GET["idsol"];
$anio=$_GET["anio"];
$numero=Conexion::ejecutarEscalar("select numero from constancias where id_solicitud=? and anio=?",array($solicitud,$anio));

$pdf=new PDF("P","pt","Letter");
$pdf->SetAutoPageBreak(1,115);
$pdf->bMargin=115;
$pdf->numero=$numero;
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
