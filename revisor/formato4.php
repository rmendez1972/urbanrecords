<?PHP 

		$requiredUserLevel = array(1,3);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/libreria.php");


	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
</head>

<body>

<?php

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion);

		$consulta= "insert into lotes values (".$_GET['anio'].",".$_GET['id_solicitud'].",0,'".$_POST['lote']."','".$_POST['area']."',NOW())";
	//			echo $consulta."<br>";
		mysql_query($consulta,$conexion);

		mysql_query("COMMIT",$conexion);
}


if (isset($_POST['accion']) && $_POST['accion']=='eliminar'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "DELETE FROM lotes WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud']." AND id_lote=".$_POST['id_lote'];
	//			echo $consulta."<br>";
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	
?>
<?PHP 
}
// Si no ha sido enviado algo

if (isset($_GET['anio']) && isset($_GET['id_solicitud']) ){

			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);


?>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function confirmar(valor,id_lote){
	var answer = confirm("¿Está seguro que desea eliminar el lote "+valor+"?");
	if (answer){
		document.forms.formulario.accion.value="eliminar";
		document.forms.formulario.id_lote.value=id_lote;
		document.forms.formulario.submit( );
	}
}
function finalizar(){
	window.open('../reportes/formato_unico.php?id_solicitud=<?PHP   echo $_GET['id_solicitud']; ?>&anio=<?PHP echo $_GET['anio']; ?>');
	document.forms.formulario.action="<?PHP  echo "../solicitud/seleccionarS.php?backing=1&anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura'] ?>";
	document.forms.formulario.accion.value="fin";
	document.forms.formulario.submit( );
}


correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){

	case 1:
		cad=document.formulario.lote.value;
		nombrecampo="Lote. Asignar nombre del lote";
		if (cad=="")
			correcto=false;
		else	
			correcto=true;
		break;
	case 2:
		cad=document.formulario.area.value;
		nombrecampo="Área. Asignar la superficie del lote";
		correcto=esnumerocondecimal(cad);
		break;
		
	case -1:

		correcto2=true;

		for(var i=1;i<3;i++){
			correcto=ejecuta(i);

			correcto2=correcto2&&correcto;

		}// for

		break;

}// Switch





if (valor!=-1){	

	if (!correcto){

		alert("Error en "+ nombrecampo);

	}// if	

}// if

else{

	if (correcto2){
		document.forms.formulario.ingresar.value="OK";
		document.forms.formulario.submit( );
	}	



}//else



return correcto;

}//function



//-->

</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF']."?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura'] ?>">
 
 <div class="tituSec">Anexo de Formato Único</div>

 <table width="80%" class="tablaDatos" align="center">
   <tr >
     <td align="center"  class="segundalinea"> Nombre Proyecto</td>
     <td align="center" colspan="3"  class="segundalinea"><?PHP  echo $datos['nombre_proyecto']; ?>
       <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" /></td>
   </tr>
   <tr class="tituTab">
     <td height="28" align="center"  class="primeralinea">Ficha</td>
     <td align="center"  class="primeralinea">Ubicaci&oacute;n</td>
     <td align="center"  class="primeralinea">Superficie</td>
     <td align="center"  class="primeralinea"># Viviendas</td>
   </tr>
   <tr >
     <td align="center"  class="segundalinea"><?PHP  echo $datos['id_solicitud']."/".$_GET['abreviatura']."/".$datos['anio']; ?></td>
     <td align="center"  class="segundalinea"><?PHP  echo $datos['direccion']; ?></td>
     <td align="center"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>
       m<sup>2</sup></td>
     <td align="center"  class="segundalinea"><?PHP  echo $datos['num_viviendas']; ?></td>
   </tr>
 </table>
 
 <br />
 
 <table width="80%" class="tablaDatos" align="center">
   <tr >
     <td height="28" colspan="3" align="center"  class="primeralinea">Subdivisi&oacute;n</td>
     <td rowspan="2" align="center"  class="primeralinea">Superficie Total</td>
   </tr>
   <tr class="tituTab">
     <td height="28" colspan="2" align="center"  class="primeralinea">Lote</td>
     <td align="center"  class="primeralinea">Área en m<sup>2</sup></td>
   </tr>
   <?php
		   
				$consulta= "select * from lotes WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$sql_lotes=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$registros=mysql_num_rows($sql_lotes);
				$cont=0;
					while($lotes= mysql_fetch_array($sql_lotes)){
						if ($cont==0){
		    ?>
   <tr >
     <td align="center"  class="segundalinea"><img src="../images/b_drop.png" alt="Eliminar solicitante" width="16" height="16" border="0" class="btnOld" onclick="return confirmar(<?PHP  echo "'".$lotes['lote']."','".$lotes['id_lote']."'"; ?>)" /></td>
     <td align="center"  class="segundalinea"><?PHP  echo $lotes['lote']; ?></td>
     <td align="center"  class="segundalinea"><?PHP  echo number_format($lotes['area'],2,".",","); ?></td>
     <td rowspan="<?php echo $registros; ?>" align="center"  class="segundalinea"><?PHP  $consulta= "select area from lotes WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$sql_suma=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$suma=0;
				while($lotes_sum= mysql_fetch_array($sql_suma))
					$suma+=$lotes_sum['area'];
				
				echo number_format($suma,2,".",","); ?></td>
   </tr>
   <?php }else{
		   ?>
   <tr >
     <td align="center"  class="segundalinea"><img src="../images/b_drop.png" alt="Eliminar solicitante" width="16" height="16" border="0" class="btnOld" onclick="return confirmar(<?PHP  echo "'".$lotes['lote']."','".$lotes['id_lote']."'"; ?>)" /></td>
     <td align="center"  class="segundalinea"><?php
			echo $lotes['lote'];
		    ?></td>
     <td align="center"  class="segundalinea"><?php
			echo number_format($lotes['area'],2,".",",");
		    ?></td>
   </tr>
   <?php
			   }
			   $cont++;
		   } ?>
   <tr >
     <td colspan="2" align="center"  class="segundalinea"><input type="text" name="lote" id="lote" maxlength="25" /></td>
     <td align="center"  class="segundalinea"><input type="text" name="area" id="area" maxlength="25" /></td>
     <td align="center"  class="segundalinea"><input name="agregar" type="button" class="btnOld" onclick="ejecuta(-1)" value="Agregar lote" /></td>
   </tr>
 </table>
 <br />
 <input name="ingresar" type="hidden" />
 <input name="accion" type="hidden" />
 <input name="id_lote" type="hidden"/>
 <input name="id_solicitud" type="hidden" value="<?PHP  echo $_GET['id_solicitud'] ?>" />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><img src="../images/terminar1.png" alt="Guardar e imprimir" class="btnOld" onclick="finalizar();" onmouseover="" /></td>
     <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>'" /></td>
   </tr>
 </table>

</form>

<?PHP 



}

	mysql_close($conexion);

?>
</body>

</html>

