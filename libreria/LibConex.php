<?
class LibConex{
	public static function obtenerEscalar($sql, $conexion){
		$cons=mysql_query($sql,$conexion);
		if($row=mysql_fetch_row($cons))
			return $row[0];
		
		return -1;
	}
	
	public static function obtenerFila($sql, $conexion){
		$cons=mysql_query($sql,$conexion);
		if($row=mysql_fetch_array($cons))
			return $row;
		
		return -1;
	}
}
?>