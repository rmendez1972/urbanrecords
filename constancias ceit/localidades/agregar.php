<?PHP 

	$requiredUserLevel = array(1,2);

	$cfgProgDir = '../';

	include("../seguridad/secure.php");

?>

<?PHP 

include ("../libreria/config.php");

include ("../libreria/libreria.php");




?>

<html>

<head>

<title>Alta Localidades</title>

<link href="../libreria/estilos.css" rel="stylesheet" type="text/css">

</head>

<?PHP 

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

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

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Agregar Localidad </td>

  </tr>

  <tr>	<td align=center ><br>

		<table width="95%" class="tabla2">
          <tr >
            <td  class="segundalinea">Municipio</td>
            <td  class="segundalinea">
<?PHP             	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
				$consulta= "SELECT * FROM cat_municipios WHERE id_municipio=".$_GET['id_municipio'];
				$sql_municipio=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$datos=mysql_fetch_array($sql_municipio);
				echo $datos['descripcion'];

  ?>          <input name="id_municipio" type="hidden" value="<?PHP  echo $_GET['id_municipio'] ?>" /></td>
          </tr>
          <tr > 
            <td class="segundalinea">Nombre  </td>
            <td class="segundalinea">  <input name="nombre" type="text" size="70" onBlur="javascript:this.value=this.value.toUpperCase()"></td>
          </tr>
        </table>

		<input name="ingresar" type="hidden" />
		<br />

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center">
      		  <img src="../images/aceptar1.png" alt="Guardar Localidad y asignar"  onClick="ejecuta(-1);" />		</td>

      		<td align="center">
      		  <img src="../images/cancelar1.png" alt="Cerrar Ventana"  onClick="window.close();" />			</td>

		</tr>

		</table><br>

</td></tr>

</table>

</form>

<?PHP 



}

?>

</body>

</html>

