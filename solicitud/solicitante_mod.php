<?PHP 

	$requiredUserLevel = array(1,2);

	$cfgProgDir = '../';

	include("../seguridad/secure.php");

?>

<?PHP 

include ("../libreria/config.php");

include ("../libreria/libreria.php");




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Modificar datos solicitante</title>

<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>

</head>

<?PHP 

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
	mysql_query("set names 'utf8'",$conexion);

	$consulta= "UPDATE solicitantes SET
	 	nombre='".$_POST['nombre']."', telefono='".$_POST['telefono']."', celular='".$_POST['celular']."', correo='".$_POST['correo']."', id_tipo='".$_POST['id_tipo']."'
		WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']." AND id_solicitante=".$_POST['id_solicitante'];

		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		

?>

<script>
	window.opener.location.reload();
	window.close()
</script>
<?PHP 

}// si ya existe

//Cerrar la conexion y liberar memoria

//	mysql_close($conexion);







?>


<?PHP 

// Si no ha sido enviado algo

if (isset($_GET['id_solicitante']) && isset($_GET['anio']) && isset($_GET['id_solicitud'])){
	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
	mysql_query("set names 'utf8'",$conexion);

	$consulta= "select * from solicitantes s WHERE id_solicitante=".$_GET['id_solicitante']." AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

	$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
	$datos= mysql_fetch_array($resultado);

?>
<body>

<SCRIPT language="javascript">

<!--



correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){



	case 1:

		nombrecampo="Nombre\nAsigne un nombre";
		cad=formulario.nombre.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

		break;

	case 2:

		nombrecampo="Tipo\nAsigne un tipo de solicitante";
		cad=formulario.id_tipo.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

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

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>" >

 <br />
 <table width="95%" align="center" class="tablaDatos">
   <tr class="tituTab">
     <td colspan="2" class="segundalinea"><span class="primeralinea">Modificar datos Solicitante </span></td>
   </tr>
   <tr >
     <td class="segundalinea">Nombre </td>
     <td class="segundalinea"><input name="nombre" type="text" size="70" value="<?PHP  echo $datos['nombre'] ?>" onblur="javascript:this.value=this.value.toUpperCase()" /></td>
   </tr>
   <tr >
     <td  class="segundalinea">Telefono</td>
     <td  class="segundalinea"><input name="telefono" type="text" size="20" value="<?PHP  echo $datos['telefono'] ?>" maxlength="20" onblur="javascript:this.value=this.value.toUpperCase()" /></td>
   </tr>
   <tr >
     <td  class="segundalinea">Celular</td>
     <td  class="segundalinea"><input name="celular" type="text" size="20" maxlength="20" value="<?PHP  echo $datos['celular'] ?>" onblur="javascript:this.value=this.value.toUpperCase()" /></td>
   </tr>
   <tr >
     <td  class="segundalinea">Correo</td>
     <td  class="segundalinea"><input name="correo" type="text" size="70" maxlength="100" value="<?PHP  echo $datos['correo'] ?>"  /></td>
   </tr>
   <tr >
     <td  class="segundalinea">Tipo solicitante</td>
     <td  class="segundalinea"><select name="id_tipo" size="1">
       <?PHP   
			  	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);
			  $consulta= "select * from cat_tipo_solicitantes";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="">Seleccione...</option>';

					while($tipos= mysql_fetch_array($resultado)){
						if ($datos['id_tipo']==$tipos['id_tipo'])
							echo '<option value="'.$tipos['id_tipo'].'" selected>'.$tipos['descripcion'].'</option>';
						else
							echo '<option value="'.$tipos['id_tipo'].'">'.$tipos['descripcion'].'</option>';

					} //fin while

				?>
     </select></td>
   </tr>
 </table>
 <input name="ingresar" type="hidden" />
 <input name="anio" type="hidden" value="<?PHP  echo $_GET['anio'] ?>" />
 <input name="id_solicitud" type="hidden" value="<?PHP  echo $_GET['id_solicitud'] ?>" />
 <input name="id_solicitante" type="hidden" value="<?PHP  echo $_GET['id_solicitante'] ?>" />
 <br />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><img src="../images/aceptar1.png" alt="Guardar solicitante y asignar" class="btnOld"  onclick="ejecuta(-1);" /></td>
     <td align="center"><img src="../images/cancelar1.png" alt="Cerrar Ventana" class="btnOld"  onclick="window.close();" /></td>
   </tr>
 </table>
 
</form>

<?PHP 



}

?>

</body>

</html>

