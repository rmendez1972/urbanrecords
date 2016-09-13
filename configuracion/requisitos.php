<?PHP 

// Verificar el acceso
$requiredUserLevel = array(1);
$cfgProgDir =  '../';

header('Content-Type: text/html; charset=utf-8');

include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");

$fecha=date("Y-m-d H:i:s");

Conexion::init(true);
$tp=$_GET["tipo"];

if(isset($_POST["requisito"])){
	$requisito=$_POST["requisito"];
	
	Conexion::ejecutar("insert into cat_requisitos (descripcion) values (?)", array($requisito));
	$idr=Conexion::lastInsertId();

	$max=Conexion::ejecutarEscalar("select max(orden) from cat_requisitos_opc where id_tipo=?",array($tp));
	$max++;
	
	Conexion::ejecutar("insert into cat_requisitos_opc values (?,?,?)",array($idr,$tp,$max));
}
if(isset($_GET["del"])){
	$del=$_GET["del"];
	Conexion::ejecutar("delete from cat_requisitos_opc where id_tipo=? and id_requisito=?",array($tp,$del));
}
if(isset($_GET["up"])){
	$requp=$_GET["up"];
	Conexion::ejecutar("update cat_requisitos_opc set orden=? where id_tipo=? and id_requisito=?", array($_GET["orden"], $tp, $requp));
}

$tipo=Conexion::ejecutarFila("select * from cat_tipo_proy where id_tipo=?",array($tp));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script>
function eliminarRequisito(tipo, req){
	if(confirm("¿Está seguro que desea eliminar?"))
		location.href="requisitos.php?tipo="+tipo+"&del="+req;
}

function orden(e, tp, id_req, orden){
	if(e.which == 13){
		location.href="requisitos.php?tipo="+tp+"&up="+id_req+"&orden="+orden;
	}
}
</script>
</head>

<body>

<div class="contFrame">
	<div class="tituSec">Requisitos del trámite: <? echo $tipo['descripcion']; ?></div>
	
	<div id="lrequisitos" style='padding:10px; background-color:#FFF; border:#CCC 1px solid; border-radius: 5px; display:table; margin:auto; margin-bottom:20px'>
		<form method="post">
			Requisito 
			<br />
			<textarea id="requisito" name="requisito" style="width: 600px; height: 40px"></textarea>
			<br />
			<input type="submit" value="Agregar"  />
		</form>
	</div>
	
	<table class="tablaDatos" width="90%" align="center">
		<thead>
			<tr class="tituTab">
				<th>Descripción</th>
				<th width="100">Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?
			$requisitos=Conexion::ejecutarConsulta("select R.descripcion,TR.id_requisito, TR.id_tipo, TR.orden from cat_requisitos_opc TR inner join cat_requisitos R on TR.id_requisito=R.id_requisito where TR.id_tipo=? order by TR.orden asc",array($tp));

			foreach($requisitos as $req){
				echo "<tr><td>".$req['descripcion']."</td><td><input type='text' value='".$req["orden"]."' style='width:20px' onkeypress='orden(event, ".$tp.",".$req["id_requisito"].", this.value)' /> <img src='../images/trash.png' style='cursor:pointer' onclick='eliminarRequisito(".$tp.",".$req['id_requisito'].")' height='20' title='Eliminar requisito' /></a></td></tr>";
			}
			?>
		</tbody>
	</table>
</div>

</body>
</html>
