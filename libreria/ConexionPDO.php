<?php
include_once("libreria/config.php");
//include_once("../libreria/config.php");

class Conexion{
	public static $conexion;

	private function __construct(){}

	public static function init($utf8=true){
		global $Servidor,$UsrMysql,$ClaveMysql,$DB;

		if(Conexion::$conexion==null){
			Conexion::$conexion=new PDO('mysql:host='.$Servidor.';dbname='.$DB, $UsrMysql, $ClaveMysql);
			if($utf8)
				Conexion::ejecutar("set names 'utf8'");
		}
	}

	public static function cerrar(){
		Conexion::$conexion=null;
	}

	public static function ejecutar($sql, $params=null){
		$statement=Conexion::$conexion->prepare($sql);
		return self::consulta($statement,$params);
	}

	private static function consulta($statement, $params=null){
		try{
			if($params!=NULL){
				for($i=0;$i<count($params);$i++){
					$statement->bindParam($i+1,$params[$i]);
				}
			}
			return $statement->execute();
		}
		catch(Exception $ex){
			return false;
		}
	}

	public static function ejecutarObjeto($sql, $clase, $params=null){
		$statement=Conexion::$conexion->prepare($sql);
		self::consulta($statement,$params);
		$statement->setFetchMode(PDO::FETCH_INTO, $clase);
		return $statement;
	}

	public static function ejecutarConsulta($sql, $params=null, $fetch = PDO::FETCH_ASSOC){
		$statement=Conexion::$conexion->prepare($sql);
		self::consulta($statement,$params);
		return $statement->fetchAll($fetch);
	}

	public static function ejecutarEscalar($sql, $params=null, $fetch = PDO::FETCH_ASSOC){
		$statement=Conexion::$conexion->prepare($sql);
		self::consulta($statement,$params);
		return $statement->fetchColumn(0);
	}

	public static function ejecutarFila($sql, $params=null, $fetch = PDO::FETCH_ASSOC){
		$statement=Conexion::$conexion->prepare($sql);
		self::consulta($statement,$params);
		return $statement->fetch($fetch);
	}

	public static function obtenerPorCampo($tabla,$campo,$valor,$fetch=PDO::FETCH_ASSOC){
		return Conexion::ejecutarConsulta("select * from $tabla where $campo=?",array($valor));
	}

	public static function lastInsertId(){
		return Conexion::ejecutarEscalar("select LAST_INSERT_ID()");
	}
}
?>
