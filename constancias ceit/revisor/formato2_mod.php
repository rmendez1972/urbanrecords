<?PHP 

		$requiredUserLevel = array(1,3);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/encabezado.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

?>




<?PHP 

// Si no ha sido enviado algo

if (isset($_GET['anio']) && isset($_GET['id_solicitud']) ){

			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio, propietario from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);


?>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function activa(){
	if (document.getElementById('visualizar').style.display=='block'){
		document.getElementById('ocultar').style.display='block';
		document.getElementById('visualizar').style.display='none';
		document.getElementById('desglose').style.display='block';
		document.forms.formulario.desglose.value="OK";
	}else{
		document.getElementById('ocultar').style.display='none';
		document.getElementById('visualizar').style.display='block';
		document.getElementById('desglose').style.display='none';
		document.forms.formulario.desglose.value="NO";
	}
}



correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){

<?PHP  
		$consulta='SELECT id_pregunta, CONCAT( LEFT( p.descripcion, 15) ,"...")AS pregunta
FROM`cat_preguntas_formato2` p';
		$sql_pregv=mysql_query($consulta,$conexion);
		$cont=1;
		while ($data=mysql_fetch_array($sql_pregv)){
			echo '			
			case '.$cont++.':
				nombrecampo="'."Proyecto. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.proyecto'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			case '.$cont++.':
				nombrecampo="'."PDU. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.pdu'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			case '.$cont++.':
				nombrecampo="'."Cumple. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.cumple'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			';
				}
?>

	case -1:

		correcto2=true;

		for(var i=1;i<<?PHP  echo $cont; ?>;i++){
		
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

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Formato Tabla</td>

  </tr>

  <tr>	<td align=center >

         <table width="80%" class="tabla1" align="center">
          <tr > 
              <td align="center"  class="segundalinea"> Nombre Proyecto</td>
              <td colspan="3"  class="segundalinea"><?PHP  echo $datos['nombre_proyecto']; ?></td>
          </tr>
          <tr > 
              <td align="center"  class="segundalinea">Ubicaci&oacute;n</td>
            <td colspan="3"  class="segundalinea"><?PHP  echo $datos['direccion']; ?></td>
          </tr>
          <tr > 
              <td align="center"  class="segundalinea">Superficie</td>
            <td colspan="3"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>m<sup>2</sup></td>
          </tr>
          <tr > 
              <td align="center"  class="segundalinea">Propietario</td>
              <td colspan="3"  class="segundalinea"><?PHP  echo $datos['propietario']; ?></td>
         </tr>
			</table>
            *Nota: Si no es necesario capturar el formato de tabla presione el bot&oacute;n CANCELAR
        <br />

  <legend ><fieldset>Generales</fieldset>
		<table width="95%" > 
                  <tr > 
              <td height="28" align="center"  class="primeralinea">CONCEPTO</td>
              <td align="center"  class="primeralinea">PROYECTO</td>
              <td align="center"  class="primeralinea">PDU</td>
              <td align="center"  class="primeralinea">CUMPLE</td>
          </tr>

<?PHP  
				$consulta= "select * from cat_preguntas_formato2";
				$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($pregunta= mysql_fetch_array($sql_preg)){
					$consulta= "select proyecto,pdu,cumple from formato2 WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud']." AND id_pregunta=".$pregunta['id_pregunta'];
					$sql_resp=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$dato_resp=mysql_fetch_array($sql_resp);
					mysql_free_result($sql_resp);
?>
        <tr >
            <td class="segundalinea"><?PHP  echo $pregunta['descripcion'] ?></td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "proyecto".$pregunta['id_pregunta']; ?>" cols="50" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['proyecto'] ?></textarea>            </td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "pdu".$pregunta['id_pregunta']; ?>" cols="50" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['pdu'] ?></textarea>            </td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "cumple".$pregunta['id_pregunta']; ?>" cols="20" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['cumple'] ?></textarea>            </td>
          </tr>
<?PHP 					} //fin while
					mysql_free_result($sql_preg);

?>
            
          </table>
          </legend>
<br />
<?PHP 
		$consulta= "select count(anio) from formato2_desglose  WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud'];
		$sql_resp=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$display="none";
		if (mysql_result($sql_resp,0)>0)
			$display="block";
		mysql_free_result($sql_resp);

?>

  <legend  ><fieldset id="tit_desglose" >
  DESGLOSE DE &Aacute;REAS (USO DE SUELO)
  <input name="Visualizar" type="button" value="Visualizar" id="visualizar" style="display:<?PHP  echo $display; ?>" onclick="activa();" />
  <input name="Ocultar" type="button" value="Ocultar" id="ocultar" style="display:none" onclick="activa();" />
  </fieldset>
  S&oacute;lo se guarda el desglose si esta visible
		<table width="95%" id="desglose" style="display:none" > 
          <tr > 
              <td height="28" align="center"  class="primeralinea">&Aacute;REAS</td>
              <td align="center"  class="primeralinea">SUPERFICIE M<sup>2</sup></td>
              <td align="center"  class="primeralinea">OBSERVACIONES SEG&Uacute;N NORMA</td>
          </tr>
<?PHP  
				$consulta= "select * from cat_desglose_areas";
				$sql_area=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($areas= mysql_fetch_array($sql_area)){
						
?>
        <tr >
            <td class="segundalinea"><?PHP  echo $areas['descripcion'] ?></td>
            <td class="segundalinea" >
              <input name="<?PHP  echo "superficie".$areas['id_area']; ?>" style="text-align:right" type="text" size="25" maxlength="20" value="0"/></td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "observaciones".$areas['id_area']; ?>" cols="60" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"></textarea>            </td>
          </tr>
<?PHP 				} //fin while
					mysql_free_result($sql_area);

					$consulta= "select observaciones from formato_observaciones WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud']." AND formato=2";
					$sql_resp=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

?>
        </table>
          </legend>
<br />

		<table width="95%" > 
          <tr > 
              <td height="28" align="center"  class="primeralinea">OBSERVACIONES</td>
          </tr>
          <tr > 
              <td height="28" align="center"  class="segundalinea"><textarea name="observaciones" cols="150" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo mysql_result($sql_resp,0); ?></textarea></td>
          </tr>
		</table>
<br />

		  <input name="ingresar" type="hidden">
		  <input name="desglose" type="hidden">

		<input name="anio" type="hidden" value="<?PHP  echo $_GET['anio'] ?>" />
		<input name="id_solicitud" type="hidden" value="<?PHP  echo $_GET['id_solicitud'] ?>" />
<script language="javascript">
	activa();
</script>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center">
<img src="../images/continuar1.png" onmouseover="" alt="Capturar documentación" onClick="ejecuta(-1)" />
			</td>

      		<td align="center">
   		    <img src="../images/cancelar1.png" alt="Ir a movimientos" onClick="location.replace('../solicitud/seleccionarS.php?id_solicitud=<?PHP  echo $datos['id_solicitud']; ?>&anio=<?PHP  echo $datos['anio']; ?>')" />			</td>

		</tr>

		</table><br>

</td></tr>

</table>

</form>

<?PHP 



}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "DELETE FROM formato2 WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
		$consulta= "DELETE FROM formato2_desglose WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
		$consulta= "DELETE FROM formato_observaciones WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']." AND formato=2";

		$consulta= "select * from cat_preguntas_formato2";
		$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		while($pregunta= mysql_fetch_array($sql_preg)){

			$consulta= "insert into formato2 values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$pregunta['id_pregunta'].",'".$_POST['proyecto'.$pregunta['id_pregunta']]."','".$_POST['pdu'.$pregunta['id_pregunta']]."','".$_POST['cumple'.$pregunta['id_pregunta']]."')";
//			echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}
		mysql_free_result($sql_preg);
		
		if (isset($_POST['desglose']) && $_POST['desglose']=='OK'){
				$consulta= "select * from cat_desglose_areas";
				$sql_area=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($areas= mysql_fetch_array($sql_area)){
					$consulta= "insert into formato2_desglose values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$areas['id_area'].",'".$_POST['superficie'.$areas['id_area']]."','".$_POST['observaciones'.$areas['id_area']]."')";
							echo $consulta."<br>";
					mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				
				}
		}
		$consulta= "insert into formato_observaciones values (".$_POST['anio'].",".$_POST['id_solicitud'].",2,'".$_POST['observaciones']."')";
	//			echo $consulta."<br>";
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	
?>
<script language="javascript">

	window.open('../reportes/formato_cuadro.php?id_solicitud=<?PHP   echo $id_solicitud; ?>&anio=<?PHP   echo $_POST['anio']; ?>');
	window.location='../solicitud/seleccionarS.php?id_solicitud=<?PHP   echo $id_solicitud; ?>&anio=<?PHP   echo $_POST['anio']; ?>';

</script>
<?PHP 

}	mysql_close($conexion);

?>
</body>

</html>

