<?PHP 

		$requiredUserLevel = array(1,2);

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
<script src="../calendar/calendario.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/calendario.css">
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<?PHP 

// Si no ha sido enviado algo

if (!isset($_POST['ingresar']) ){

?>

<SCRIPT language="javascript">

<!--

var miPopup

function abreVentana(){

	if (document.forms.formulario.id_municipio.value!=""){
		miPopup = window.open("../localidades/listado.php?id_municipio="+document.formulario.id_municipio.options[document.formulario.id_municipio.selectedIndex].value,"miwin","width=600,height=400,scrollbars=yes")

		miPopup.focus()
	}else
		alert("Seleccione un municipio");
}




correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){
	case 1:
		nombrecampo="Año\nSeleccione un año";
		cad=formulario.anio.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

		break;

	case 2:
		nombrecampo="Proyecto 1\nSeleccione un proyecto";
		cad=formulario.tipo_proy1.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

		break;

	case 3:

		nombrecampo="Fracciones\nAsigne sólo números";
		cad=formulario.fracciones.value;
		correcto=esnatural(cad);

		break;

	case 4:

		nombrecampo="Viviendas\nAsigne sólo números";
		cad=formulario.viviendas.value;
			correcto=esnatural(cad);

		break;

	case 5:
		nombrecampo="Proyecto\nAsigne un nombre de proyecto";
		cad=formulario.proyecto.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

		break;

	case 6:
		nombrecampo="Municipio\nAsigne un municipio";
		cad=formulario.id_municipio.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

		break;

	case 7:

		nombrecampo="Superficie\nAsigne sólo números";
		cad=formulario.superficie.value;
		correcto=esnumerocondecimal(cad);

		break;
    case 8:
        nombrecampo="La localidad es requerida";
        cad=formulario.id_localidad.value;
        if(cad=="")
            correcto=false;
        else
            correcto=true;
        break;

	case -1:

		correcto2=true;

		for(var i=1;i<9;i++){
			correcto=ejecuta(i);

			correcto2=correcto2&&correcto;

		}// for

		break;

}// Switch





if (valor!=-1){	

	if (!correcto){

		//alert("Error en "+ nombrecampo);

	}// if	

}// if

else{

	if (correcto2){
		document.forms.formulario.ingresar.value="OK";
		document.forms.formulario.submit( );
	}	
	else{
		parent.error("Llene todos los campos marcados con *");	
	}


}//else



return correcto;

}//function
</script>
</head>

<body onload="ajustar()" style="margin:20px">
<div id="calendario" style="position:absolute"></div>
 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

   <input name="ingresar" type="hidden" />
   <input name="id_cliente" type="hidden" />
   <table width="100%" align="center" class="tablaDatos">
   <thead>
   <tr >
     <th colspan="2" class="tituTab">Solicitud de constancia</th>
   </tr>
   </thead>
   
   <tbody>
   <tr >
     <td class="segundalinea">A&ntilde;o</td>
     <td class="segundalinea" ><select name="anio" size="1">
       <?PHP   $consulta= "select * from anios";


					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					

						echo '<option value="">Seleccione...</option>';

					while($anios= mysql_fetch_array($resultado)){
						if ($anios['anio']== date('Y'))	
							echo '<option value="'.$anios['anio'].'" selected>'.$anios['anio'].'</option>';
						else
							echo '<option value="'.$anios['anio'].'">'.$anios['anio'].'</option>';

					} //fin while

				?>
     </select></td>
   </tr>
   <tr >
     <td class="segundalinea" > Fecha de Ingreso </td>
     <td class="segundalinea" >
     <input name="fecha" id="fecha" type="text" style="vertical-align:middle" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />
       <img src='../images/calendario.png' class="btnOld" style="vertical-align:middle" onclick="calendarioF('calendario','fecha',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" /></td>
   </tr>
   <tr >
     <td class="segundalinea" > Tipo Proyecto *</td>
     <td class="segundalinea" ><select name="tipo_proy1" size="1">
       <?PHP   $consulta= "select * from cat_tipo_proy where activo=1";


					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="">Seleccione...</option>';

					while($tipos= mysql_fetch_array($resultado)){
							echo '<option value="'.$tipos['id_tipo'].'">'.$tipos['descripcion'].'</option>';

					} //fin while

				?>
     </select></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Fracciones</td>
     <td class="segundalinea" ><input name="fracciones" style="text-align:right" type="text" size="15" maxlength="10" value="0"/></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Viviendas</td>
     <td class="segundalinea" ><input name="viviendas" type="text" style="text-align:right" size="15" maxlength="10" value="0"/> <input name="contabilizar" type="checkbox" id="contabilizar" value="1" checked="checked" />
       Contabilizar viviendas</td>
   </tr>
   <tr >
     <td width="20%"  class="segundalinea"> Propietario</td>
     <td width="80%" class="segundalinea" ><textarea name="propietario" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"></textarea></td>
   </tr>
   <tr >
     <td width="20%"  class="segundalinea"> Proyecto *</td>
     <td width="80%" class="segundalinea" ><textarea name="proyecto" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"></textarea></td>
   </tr>
   <tr >
     <td width="20%"  class="segundalinea">Direcci&oacute;n</td>
     <td width="80%" class="segundalinea" ><textarea name="direccion" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"></textarea></td>
   </tr>
   <tr >
     <td class="segundalinea">Municipio *</td>
     <td class="segundalinea" ><select name="id_municipio" size="1">
       <?PHP   $consulta= "select * from cat_municipios";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="">Seleccione...</option>';

					while($municipios= mysql_fetch_array($resultado)){
						echo '<option value="'.$municipios['id_municipio'].'">'.$municipios['descripcion'].'</option>';

					} //fin while

				?>
     </select></td>
   </tr>
   <tr >
     <td class="segundalinea">Localidad *</td>
     <td class="segundalinea" ><input name="localidad"  type="text" style="vertical-align:middle" size="70" maxlength="120" onblur="javascript:this.value=this.value.toUpperCase()" readonly="readonly" />
       <input name="id_localidad" type="hidden" />
       <img src="../images/elegir1.png" style="vertical-align:middle" onmouseover="" alt="Elegir localidad y asignar" onclick="abreVentana()" class="btnOld" /></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Superficie</td>
     <td class="segundalinea" ><input name="superficie" type="text" style="text-align:right" size="15" maxlength="10" value="0"/>
       m<sup>2</sup></td>
   </tr>
   </tbody>
 </table>
   <br />
   <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
     <tr>
       <td align="center"><img src="../images/continuar1.png" onmouseover="" alt="Capturar documentaci&oacute;n" onclick="ejecuta(-1)" class="btnOld" /></td>
       <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> class="btnOld" /></td>
     </tr>
   </table>

</form>

<?PHP 



}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion);
		$contab=0;
		if($_POST["contabilizar"]==1)
			$contab=1;
		$consulta= "insert into solicitud values (".$_POST['anio'].",0,'".$_POST['fecha']."','".$_POST['tipo_proy1']."',".$_POST['fracciones'].",".$_POST['viviendas'].",'".$_POST['propietario']."','".$_POST['proyecto']."','".$_POST['direccion']."','".$_POST['id_localidad']."','".$_POST['id_municipio']."','".$_POST['superficie']."','',0,0,'".$login."',NOW(),1,0,NULL,".$contab.",0)";
		
		

		mysql_query($consulta,$conexion);

	    $id_solicitud = mysql_insert_id($conexion);


		mysql_query("COMMIT",$conexion);
?>
<script language="javascript">

	window.location='documentacion.php?id_solicitud=<?PHP   echo $id_solicitud; ?>&anio=<?PHP echo $_POST['anio']; ?>';

</script>
<?PHP 

}	mysql_close($conexion);



?>
</body>

</html>

