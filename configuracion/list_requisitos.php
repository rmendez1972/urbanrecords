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

if(isset($_GET["new"])){
	Conexion::ejecutar("insert into cat_requisitos (descripcion) values ('')");
}

if(isset($_GET["del"])){
	$del=$_GET["del"];
	
	Conexion::ejecutar("delete from cat_requisitos where id_requisito=?",array($del));
}

if(isset($_POST["update"])){
	$result=Conexion::ejecutarConsulta("select * from cat_requisitos");
	foreach($result as $res){
		$valor=$_POST["req".$res['id_requisito']];
		$valor=trim($valor);
		$tot_reqs=Conexion::ejecutarEscalar("select count(*) from cat_requisitos where descripcion=?",array($valor));
		if($tot_reqs==0)
			Conexion::ejecutar("update cat_requisitos set descripcion=? where id_requisito=?",array($valor,$res['id_requisito']));
	}
}

$requisitos=Conexion::ejecutarConsulta("select * from cat_requisitos order by descripcion asc",null);
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
function eliminarRequisito(id){
	if(confirm("¿Está seguro que desea eliminar? (El requisito podría estar siendo utilizado)")){
		location.href="list_requisitos.php?del="+id;
	}
}
function guardarRequisitos(){
	document.getElementById('frmRequisitos').submit();
}
</script>

</head>

<body>

<div class="contFrame">
	<div class="tituSec">Listado de requisitos</div>
	
	<div class="menuSec" style="display:table; margin-bottom:5px">
		<a href="list_requisitos.php?new=1"><div class='btnFrame' style='width:50px'><img src='../images/agregar.png' width='25' height='25' /><br />Nuevo</div></a>
		<div class='btnFrame' style='width:50px' onclick='guardarRequisitos()'><img src='../images/save.png' width='25' height='25' /><br />Guardar</div>
	</div>
	
	<form id="frmRequisitos" action="list_requisitos.php" method="post">
		<input type="hidden" name="update" id="update" value="1" />
	<table class="tablaDatos" width="90%" align="center">
		<thead>
			<tr class="tituTab">
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?
			foreach($requisitos as $req){
				$idname="req".$req['id_requisito'];
				echo "<tr>
						<td><textarea rows='4' cols='80' name='$idname' id='$idname'>".$req['descripcion']."</textarea></td>
						<td valign='top'><img src='../images/remove.png' width='22' height='22' onclick='eliminarRequisito(".$req['id_requisito'].")' title='Eliminar requisito' style='cursor:pointer' /></td>
					</tr>";
			}
			?>
		</tbody>
	</table>
	</form>
</div>

</body>
</html>
