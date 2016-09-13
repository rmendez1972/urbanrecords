<?

		$requiredUserLevel = array(1,2);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/encabezado.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

?>




<?

// Si no ha sido enviado algo

if (isset($_GET['anio']) && $_GET['id_solicitud']){

		$consulta= "SELECT * 
					FROM`solicitud` s
						WHERE anio=".$_GET['anio']." AND id_solicitud='".$_GET['id_solicitud']."'";

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos=mysql_fetch_array($sql_datos);


?>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

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


	case -1:

		correcto2=true;

		for(var i=1;i<8;i++){
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

 <form name="formulario" method="post" action="<? echo $_SERVER['PHP_SELF'] ?>">

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Modificar datos Solicitud de Constancia</td>

  </tr>

  <tr>	<td align=center >

  		<br><br>

		<table width="95%" class="tabla2">

          <tr > 

            <td class="segundalinea">A&ntilde;o</td>

            <td class="segundalinea" ><? echo $datos['anio'] ?></td>
          </tr>

          <tr > 

            <td class="segundalinea" > Fecha de Ingreso </td>

            <td class="segundalinea" ><input name="fecha" type="text" style="vertical-align:middle"  size="15" maxlength="10" value="<? echo $datos['fecha_ingreso'] ?>" readonly />

            <img src='../images/calendario.png' style="vertical-align:middle"  name='calendario' alt='calendario' onclick='showCalendar(this,formulario.fecha, "yyyy-mm-dd",null,1,-1,-1)'></td>
          </tr>

          <tr > 

            <td class="segundalinea" > Tipo Proyecto</td>

            <td class="segundalinea" ><select name="tipo_proy1" size="1">
              <?  $consulta= "select * from cat_tipo_proy";


					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="">Seleccione...</option>';

					while($tipos= mysql_fetch_array($resultado)){
						if ($tipos['id_tipo']== $datos['id_proyecto1'])	
							echo '<option value="'.$tipos['id_tipo'].'" selected>'.$tipos['descripcion'].'</option>';
						else
							echo '<option value="'.$tipos['id_tipo'].'">'.$tipos['descripcion'].'</option>';

					} //fin while

				?>
            </select></td>
          </tr>
          <tr > 

            <td  class="segundalinea" > Fracciones</td>

            <td class="segundalinea" ><input name="fracciones" style="text-align:right" type="text" size="15" maxlength="10" value="<? echo $datos['fracciones'] ?>"/>
              fracciones
              <input name="viviendas" type="text" style="text-align:right" size="15" maxlength="10" value="<? echo $datos['num_viviendas'] ?>"/>
              viviendas</td>
          </tr>
          <tr > 
            <td width="20%"  class="segundalinea">  Propietario</td>
            <td width="80%" class="segundalinea" >  <textarea name="propietario" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><? echo $datos['propietario'] ?></textarea>            </td>
          </tr>
          <tr > 
            <td width="20%"  class="segundalinea">  Proyecto</td>
            <td width="80%" class="segundalinea" >  <textarea name="proyecto" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><? echo $datos['nombre_proyecto'] ?></textarea>            </td>
          </tr>
          <tr > 
            <td width="20%"  class="segundalinea">Direcci&oacute;n</td>
            <td width="80%" class="segundalinea" >  <textarea name="direccion" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><? echo $datos['direccion'] ?></textarea>            </td>
          </tr>
          <tr >
            <td class="segundalinea">Municipio</td>
            <td class="segundalinea" ><select name="id_municipio" size="1">
                <?  $consulta= "select * from cat_municipios";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="">Seleccione...</option>';

					while($municipios= mysql_fetch_array($resultado)){
						if ($municipios['id_municipio']== $datos['id_municipio'])	
							echo '<option value="'.$municipios['id_municipio'].'" selected>'.$municipios['descripcion'].'</option>';
						else
						echo '<option value="'.$municipios['id_municipio'].'">'.$municipios['descripcion'].'</option>';

					} //fin while

				?>
            </select></td>
          </tr>
          <tr >
            <td class="segundalinea">Localidad</td>
            <td class="segundalinea" ><input name="localidad" type="text" style="vertical-align:middle" size="70" maxlength="120" onblur="javascript:this.value=this.value.toUpperCase()" value="<?  if ($datos['id_localidad']!=0){
					$consulta= "select * from cat_localidades WHERE id_municipio=".$datos['id_municipio']." AND id_localidad=".$datos['id_localidad'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$localidad= mysql_fetch_array($resultado);
					echo $localidad['descripcion'];
				}
?>" readonly=""> <input name="id_localidad" type="hidden" value="<? echo $datos['id_localidad'] ?>" />
            <img src="../images/elegir1.png" style="vertical-align:middle" onmouseover="" alt="Elegir localidad y asignar" onclick="abreVentana()" /></td>
          </tr>
          <tr >
            <td  class="segundalinea" > Superficie</td>
            <td class="segundalinea" ><input name="superficie" type="text" style="text-align:right" size="15" maxlength="10" value="<? echo $datos['superficie'] ?>"/>m<sup>2</sup></td>
          </tr>
        </table>

<br />

		  <input name="ingresar" type="hidden">

		  <input name="id_solicitud" type="hidden"  value="<? echo $datos['id_solicitud'] ?>"/>
		  <input name="anio" type="hidden"  value="<? echo $datos['anio'] ?>"/>

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center"><img src="../images/continuar1.png" onmouseover="" alt="Editar solicitantes" onclick="ejecuta(-1)" /></td>

      		<td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" <? echo $central; ?> /></td>

		</tr>

		</table><br>

</td></tr>

</table>

</form>

<?



}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "UPDATE solicitud SET fecha_ingreso='".$_POST['fecha']."', id_proyecto1='".$_POST['tipo_proy1']."', fracciones=".$_POST['fracciones'].", num_viviendas=".$_POST['viviendas'].", propietario='".$_POST['propietario']."', nombre_proyecto='".$_POST['proyecto']."', direccion='".$_POST['direccion']."', id_localidad='".$_POST['id_localidad']."', id_municipio='".$_POST['id_municipio']."', superficie='".$_POST['superficie']."' WHERE id_solicitud=".$_POST['id_solicitud']." AND anio=".$_POST['anio'];

		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	//    $id_solicitud = mysql_insert_id($conexion);


		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	
?>
<script language="javascript">

	window.location='documentacion_mod.php?id_solicitud=<?PHP  echo $id_solicitud; ?>&anio=<?PHP  echo $_POST['anio']; ?>';

</script>

<?

}	mysql_close($conexion);



?>

</body>

</html>

