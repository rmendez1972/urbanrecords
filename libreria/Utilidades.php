<?PHP
class Utilidades{
	public static function obtenerConfiguracion($campo, $fecha){
		$sql="select valor from configuracion where campo=? and fecha <= ? order by fecha desc";
		//$sql="select valor from configuracion where campo='$campo' order by fecha asc";
		$consulta=Conexion::ejecutarEscalar($sql,array($campo,$fecha));
		return $consulta;
	}
	public static function aHectareas($sup){
		$has="";
		$cont=0;
		$div=explode(".",$sup);
		if(strlen($div[0])%2!=0)
			$div[0]="0".$div[0];
			
		for($i=0;$i<strlen($div[0]);$i++){
			if($cont==2){
				$cont=0;
				$has.="-";	
			}
			$has.=substr($div[0],$i,1);
			$cont++;
		}
		
		return $has.(count($div)>1?".".$div[1]:"");
	}
	
	public static function catalogo($tabla, $orden, $default=-1){
		$res=Conexion::ejecutarConsulta("select * from $tabla order by $orden asc",NULL,PDO::FETCH_NUM);
		foreach($res as $row){
			echo "<option value='".$row[0]."' ".($res[0]==$default?"selected":"").">".$row[1]."</option>";	
		}
	}
	
	public static function formatoDecimal($num){
		$div=explode(".",$num);
		
		$enteros="";
		$cuenta=0;
		
		for($i=strlen($div[0])-1;$i>=0;$i--){
			if($cuenta==3){
				$cuenta=0;
				$enteros=",".$enteros;
			}
			
			$enteros=substr($div[0],$i,1).$enteros;
			
			$cuenta++;
		}
		
		if(count($div)==1)
			return $enteros.".00";
		else
			return $enteros.".".$div[1];
	}
}
?>