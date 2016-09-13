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
<head>

<title>Alta Localidades</title>

<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
</head>

<?PHP 

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
	mysql_query("set names 'utf8'",$conexion);

	$consulta= "insert into cat_localidades values (".$_POST['id_municipio'].",0,'".$_POST['nombre']."')";

	mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

    $id_localidad = mysql_insert_id($conexion);
		

?>

<script>
	window.opener.document.formulario.id_localidad.value = '<?PHP  echo $id_localidad; ?>';
	window.opener.document.formulario.localidad.value = '<?PHP  echo $_POST['nombre']; ?>';
	window.close()
</script>
<?PHP 

}// si ya existe la variable nombre. Envio una medida para guardar

//Cerrar la conexion y liberar memoria

//	mysql_close($conexion);







?>


<?PHP 

// Si no ha sido enviado algo

if (!isset($_POST['ingresar'])){

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

	case -1:

		correcto2=true;

		for(var i=1;i<2;i++){

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
     <td colspan="2"  class="segundalinea">Agregar localidad</td>
   </tr>
   <tr >
     <td  class="segundalinea">Municipio</td>
     <td  class="segundalinea"><?PHP             	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);
				$consulta= "SELECT * FROM cat_municipios WHERE id_municipio=".$_GET['id_municipio'];
				$sql_municipio=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$datos=mysql_fetch_array($sql_municipio);
				echo $datos['descripcion'];

  ?>
       <input name="id_municipio" type="hidden" value="<?PHP  echo $_GET['id_municipio'] ?>" /></td>
   </tr>
   <tr >
     <td class="segundalinea">Nombre </td>
     <td class="segundalinea"><input name="nombre" type="text" size="70" onblur="javascript:this.value=this.value.toUpperCase()" /></td>
   </tr>
 </table>
 <br />
<input name="ingresar" type="hidden" />
 <br />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><img src="../images/aceptar1.png" alt="Guardar Localidad y asignar"  onclick="ejecuta(-1);" class="btnOld" /></td>
     <td align="center"><img src="../images/cancelar1.png" alt="Cerrar Ventana"  onclick="window.close();" class="btnOld" /></td>
   </tr>
 </table>

</form>

<?PHP 



}

?>

</body>

</html>

