<?PHP 

		$requiredUserLevel = array(1,2);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);
?>




<?PHP 
if (isset($_GET['accion']) && ($_GET['accion']=="eliminar") && isset($_GET['id_solicitante'])){
		$consulta= "DELETE from solicitantes WHERE id_solicitante=".$_GET['id_solicitante']." AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
}
// Si no ha sido enviado algo

if (isset($_GET['id_solicitud']) ){

		$consulta= "SELECT * 
					FROM`solicitud` s, cat_tipo_proy tp 
					WHERE id_tipo=id_proyecto1 
						AND anio=".$_GET['anio']." AND id_solicitud='".$_GET['id_solicitud']."'";

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos=mysql_fetch_array($sql_datos);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<style>
body{
	margin:20px;
	margin-top:15px;	
}
.tablaDatos tr td{
	font-size:11px;	
}
</style>
<script>
function eliminarSolicitante(solicitud, anio, solicitante){
	var params=new Object();
	params.modulo="SOLICITANTES";
	params.accion="ELIMINAR";
	
	params.solicitud=solicitud;
	params.anio=anio;
	params.solicitante=solicitante;

	if(confirm("¿Está seguro que desea eliminar?")){
		$.post("Controlador.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Datos eliminados");
				location.reload(true);
			}
			else
				parent.error(datos);
		},"html");
	}
}
</script>
</head>

<body>
 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

   <input name="ingresar" type="hidden" />
   <input name="id_cliente" type="hidden" />
   <div class="tituSec">Capturar Solicitud de Constancia</div>
   <table width="95%" align="center" class="tablaDatos">
   <tr >
     <td colspan="2" class="tituTab" align="center">Datos solicitud</td>
   </tr>
   <tr >
     <td class="segundalinea">Ficha No. </td>
     <td class="segundalinea" ><?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?>
       <img src="../images/b_edit.png" alt="Modificar datos solicitud" width="16" height="16" border="0" onclick="location.replace('editar.php<?PHP  echo "?id_solicitud=".$datos['id_solicitud']."&anio=".$datos['anio']; ?>')" class="btnOld" /><img src="../images/b_search.png" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']."&abreviatura=".$datos['abreviatura']; ?>')" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" /></td>
   </tr>
   <tr >
     <td class="segundalinea" > Fecha de Ingreso </td>
     <td class="segundalinea" ><?PHP  echo $datos['fecha_ingreso'] ?></td>
   </tr>
   <tr >
     <td class="segundalinea" > Tipo Proyecto</td>
     <td class="segundalinea" ><?PHP  echo $datos['descripcion'] ?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Fracciones</td>
     <td class="segundalinea" ><?PHP  echo $datos['fracciones'] ?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Viviendas</td>
     <td class="segundalinea" ><?PHP  echo $datos['num_viviendas'] ?></td>
   </tr>
   <tr >
     <td width="20%"  class="segundalinea"> Propietario</td>
     <td width="80%" class="segundalinea" ><?PHP  echo $datos['propietario'] ?></td>
   </tr>
   <tr >
     <td width="20%"  class="segundalinea"> Proyecto</td>
     <td width="80%" class="segundalinea" ><?PHP  echo $datos['nombre_proyecto'] ?></td>
   </tr>
   <tr >
     <td width="20%"  class="segundalinea">Direcci&oacute;n</td>
     <td width="80%" class="segundalinea" ><?PHP  echo $datos['direccion'] ?></td>
   </tr>
   <tr >
     <td class="segundalinea">Municipio</td>
     <td class="segundalinea" ><?PHP   $consulta= "select * from cat_municipios WHERE id_municipio=".$datos['id_municipio'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$municipios= mysql_fetch_array($resultado);
					echo $municipios['descripcion'];

				?></td>
   </tr>
   <tr >
     <td class="segundalinea">Localidad</td>
     <td class="segundalinea" ><?PHP   if ($datos['id_localidad']!=0){
					$consulta= "select * from cat_localidades WHERE id_municipio=".$datos['id_municipio']." AND id_localidad=".$datos['id_localidad'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$localidad= mysql_fetch_array($resultado);
					echo strtoupper($localidad['descripcion']);
				}
				?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Superficie</td>
     <td class="segundalinea" ><?PHP  echo $datos['superficie'] ?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Observaciones</td>
     <td class="segundalinea" ><?PHP  echo $datos['observaciones'] ?></td>
   </tr>
 </table>
   <br />
 <table width="95%" align="center" class="tablaDatos">
   <tr >
     <td colspan="4" class="tituTab" align="center">Datos solicitantes</td>
   </tr>
   <tr style="background-color:#E9E9E9">
     <td height="25"  class="primeralinea" >Tipo</td>
     <td class="primeralinea" >Nombre</td>
     <td class="primeralinea" >Contacto</td>
     <td class="primeralinea" >Operaci&oacute;n</td>
   </tr>
   <?PHP   
					$consulta= "select * from solicitantes s, cat_tipo_solicitantes t WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					while($solicitantes= mysql_fetch_array($resultado)){
						$data_solicitante="";
						if ($solicitantes['telefono']!="")
							$data_solicitante.=", TEL. ".$solicitantes['telefono'];
						if ($solicitantes['celular']!="")
							$data_solicitante.=", CEL. ".$solicitantes['celular'];
						if ($solicitantes['correo']!="")
							$data_solicitante.=", CORREO: ".$solicitantes['correo'];
						echo "
						  <tr >
							<td class=\"segundalinea\">".$solicitantes['descripcion']."</td>
							<td class=\"segundalinea\">".$solicitantes['nombre']."</td>
							<td class=\"segundalinea\">".$data_solicitante."</td>
							<td class=\"segundalinea\"><img src=\"../images/b_edit.png\" style='cursor:pointer' alt=\"Modificar datos solicitante\" width=\"16\" height=\"16\" border=\"0\" onclick=\"window.open('solicitante_mod.php?id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']."&id_solicitante=".$solicitantes['id_solicitante']."', 'miwin', 'width=600, height=400, scrollbars=yes')\" />
							<img src=\"../images/b_drop.png\" style='cursor:pointer' alt=\"Eliminar solicitante\" width=\"16\" height=\"16\" border=\"0\" onclick=\"eliminarSolicitante(".$_GET['id_solicitud'].",".$_GET['anio'].",".$solicitantes['id_solicitante'].")\" />
							</td>							
						  </tr>
						";
					}
				?>
 </table>
 <br />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><img src="../images/agregarsolicitante1.png" style=" vertical-align:middle;  border:0 hidden " alt="Agregar solicitante" onclick="window.open('solicitante_add.php?id_solicitud=<?PHP  echo $_GET['id_solicitud']; ?>&amp;anio=<?PHP  echo $_GET['anio']; ?>', 'miwin', 'width=600, height=400, scrollbars=yes')" class="btnOld" /></td>
     <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" class="btnOld" onclick="location.replace('../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>')" /></td>
   </tr>
 </table>

</form>

<?PHP 



}
	mysql_close($conexion);



?>

</body>

</html>

