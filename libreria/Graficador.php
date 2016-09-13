<?php
class Graficador{
	private $titulo,$etiquetas,$valores,$colores,$colores2,$tipo,$ancho,$alto,$font,$display,$im,$tmpcolors;
	public static $BARRAS=1,$PASTEL=2,$PORCENTAJES=2,$NUMEROS=1;
	
	function __construct($title, $labels, $values, $type, $display){
		$this->titulo=$title;
		$this->etiquetas=$labels;
		$this->valores=$values;
		$this->tipo=$type;
		$this->display=$display;
		
		$this->ancho=750;
		$this->alto=360;
		
		$colors=array(new Color(50, 80, 180),new Color(8, 255, 140),new Color(255, 172, 8), new Color(106, 222, 255), new Color(189, 8, 255), new Color(222, 222, 8), new Color(47, 111, 51), new Color(186, 17, 17), new Color(140, 140, 140), new Color(120, 106, 249));
		$this->setColors($colors);
	}
	
	function setFont($font){
		$this->font=$font;	
	}
	
	function setSize($width, $height){
		$this->ancho=$width;
		$this->alto=$height;
	}
	
	function setColors($colors){
		$this->tmpcolors=$colors;
	}
	
	private function establecerColores(){
		$this->colores=array();
		$this->colores2=array();
		
		for($i=0;$i<count($this->tmpcolors);$i++){
			$r=$this->tmpcolors[$i]->red;
			$g=$this->tmpcolors[$i]->green;
			$b=$this->tmpcolors[$i]->blue;
			$this->colores[$i]=imagecolorallocate($this->im, $r, $g, $b);
			$r-=30;
			$g-=30;
			$b-=30;
			$r=$r<0?0:$r;
			$g=$g<0?0:$g;
			$b=$b<0?0:$b;
			$this->colores2[$i]=imagecolorallocate($this->im, $r, $g, $b);
		}
	}
	
	function generate(){
		$this->im = imagecreatetruecolor($this->ancho, $this->alto);
		
		$this->establecerColores();
		
		$fondo = imagecolorallocate($this->im, 245, 245, 245);
		imagefill($this->im,0,0,$fondo);
		
		$letras=imagecolorallocate($this->im,160,30,30);
		imagettftext($this->im,16,0,8,25,$letras,$this->font,$this->titulo);
		
		$padding=22;
		$colorlab=imagecolorallocate($this->im, 215, 215, 215);
		$bordes=imagecolorallocate($this->im, 170, 170, 170);
		imagefilledrectangle($this->im,6,38,232,42+count($this->valores)*$padding,$bordes);
		imagefilledrectangle($this->im,8,40,230,40+count($this->valores)*$padding,$colorlab);
		$i=0;
		foreach($this->etiquetas as $val){
			imagefilledrectangle($this->im,13,43+($i*$padding),27,50+($i*$padding)+7,$this->colores[$i]);
			imagettftext($this->im,10,0,34,56+($i*$padding),$letras,$this->font,$val);
			$i++;
		}
		
		if($this->tipo==self::$BARRAS)
			$this->barras();
		else
			$this->pastel();
		
		return $this->im;
	}
	
	private function pastel(){
		$wid=$this->ancho-250-30;
		$hei=$this->alto-40-42;
		
		$cenx=270+$wid/2;
		$ceny=40+$hei/2;
		
		$suma=0;
		foreach($this->valores as $val)
			$suma+=$val;
		
		$sumagrados=0;
		
		for($j=$ceny+30;$j>=$ceny-1;$j--){
			for($i=0;$i<count($this->valores);$i++){
				$grados=$this->valores[$i]*360/$suma;
				
				$grads=$sumagrados;
				
				imagefilledarc($this->im,$cenx,$j,($wid+130)/2,($hei+130)/2,$grads,$grads+$grados,$this->colores2[$i],IMG_ARC_PIE);
				$sumagrados+=$grados;
			}
		}
		
		$sumagrados=0;
		for($i=0;$i<count($this->valores);$i++){
			$grados=$this->valores[$i]*360/$suma;
			$grads=$sumagrados;
			
			imagefilledarc($this->im,$cenx,$ceny,($wid+130)/2,($hei+130)/2,$grads,$grads+$grados,$this->colores[$i],IMG_ARC_PIE);
			$sumagrados+=$grados;
		}
		
		$sumagrados=0;
		for($i=0;$i<count($this->valores);$i++){
			$grados=$this->valores[$i]*360/$suma;
			
			$radx=($sumagrados+$grados/2)*3.1416/180;
			$distx=($wid)/4;
			$disty=($hei)/4;
			$tmpx=$cenx+$distx*cos($radx)-5;
			$tmpy=$ceny+$disty*sin($radx)+4;
			imagettftext($this->im,10,0,$tmpx,$tmpy,$letras,$this->font,$this->valores[$i]);
			
			$sumagrados+=$grados;
		}
	}
	
	private function barras(){
		$bordes=imagecolorallocate($this->im, 110, 110, 110);
		$colnums=imagecolorallocate($this->im, 0, 0, 0);
		$wid=$this->ancho-250-30;
		$hei=$this->alto-40-42;
		imagefilledrectangle($this->im,250,$this->alto-40,251,42,$bordes);
		imagefilledrectangle($this->im,250,$this->alto-40,250+$wid,$this->alto-39,$bordes);
		
		$anchoef=$wid-40;
		if(count($this->valores)!=0)
			$anchocol=$anchoef/count($this->valores);
		else
			$anchocol=100;
		
		if($anchocol>100)
			$anchocol=100;
		
		$max=0;
		
		foreach($this->valores as $val)
			if($val>$max)
				$max=$val;
				
		$numeros=true;
		$valoresF=$this->valores;
		if($this->display==self::$PORCENTAJES){
			$numeros=false;
			$max=100;
			$valoresF=$this->aPorcentaje($this->valores);
		}
		
		for($i=0;$i<count($valoresF);$i++){
			$inix=270+$i*$anchocol;
			$iniy=$this->alto-41;
			$resta=$valoresF[$i]*($hei-20)/$max;
			$num=$numeros?$valoresF[$i]:$valoresF[$i]."%";
			imagettftext($this->im,8,0,$inix,$iniy-$resta-5,$colnums,$this->font,$num);
			imagefilledrectangle($this->im,$inix,$iniy,$inix+$anchocol-10,$iniy-$resta,$this->colores[$i]);
		}
	}
	
	private function aPorcentaje($valores){
		$suma=0;
		foreach($valores as $val)
			$suma+=$val;
		
		$resultado=array();
		$porcentajes=0;
		for($i=0;$i<count($valores);$i++){
			$resultado[$i]=number_format($valores[$i]*100/$suma,0);
			$porcentajes+=$resultado[$i];
		}
		
		$i=0;
		while($porcentajes<100){
			if($i==count($resultado))
				$i=0;
			
			$resultado[$i]++;
			$porcentajes++;	
		}
		
		return $resultado;
	}
}
class Color{
	public $red,$green,$blue;
	function __construct($r,$g,$b){
		$this->red=$r;
		$this->green=$g;
		$this->blue=$b;	
	}
}
?>