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
if (isset($_GET['accion']) && ($_GET['accion']=="eliminar") && isset($_GET['id_archivo'])){
		$consulta= "DELETE from archivos WHERE id_archivo=".$_GET['id_archivo']." AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
$ruta ="../doctos/revisiones/";
$log="";
	if (is_uploaded_file($_FILES['archivoupload']['tmp_name'])) { 
		if($_FILES['archivoupload']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $_FILES['archivoupload']['type']=='application/msword' || $_FILES['archivoupload']['type']=='application/pdf' ) {
		  	//Ingresar archivo y obtener id
			mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$consulta="INSERT INTO archivos values(".$_GET['anio'].",".$_GET['id_solicitud'].",0,'".$_POST['descripcion']."','',NOW())";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");	
	
		    $id_archivo = mysql_insert_id($conexion);
			
			$consulta="UPDATE archivos SET archivo='".$_GET['anio']."-".$_GET['abreviatura']."-".$_GET['id_solicitud']."-".$id_archivo.$_POST['extension']."' WHERE anio=".$_GET['anio']." AND id_solicitud= ".$_GET['id_solicitud']." AND id_archivo =".$id_archivo;
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");	
				
			mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			 
			if (!copy($_FILES['archivoupload']['tmp_name'], $ruta.$_GET['anio']."-".$_GET['abreviatura']."-".$_GET['id_solicitud']."-".$id_archivo.$_POST['extension'])){
			  	$log="Ocurrió un error al intentar subir el archivo.\\nVuelva a Intentarlo
          \\nSi el problema persiste, comuníquelo al Administrador del Sistema";
				//Eliminar archivo
				mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				
				$consulta="DELETE FROM archivos WHERE anio=".$_GET['anio']." AND id_solicitud= ".$_GET['id_solicitud']." AND id_archivo =".$id_archivo;
				mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");	
					
				mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				
			}else 
		  	$log="El Archivo subió con éxito";
		}else{
		  $log="El Archivo no es de un tipo permitido\\nSólo archivos de Word y PDF";
		}
    } 

	if($log!="") { 
		echo '
<script language="javascript">
	alert("'.$log.'");
</script>
		
		';
	} 
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

function comprueba_extension(formulario, archivo) { 
   extensiones_permitidas = new Array(".doc",".docx", ".pdf"); 
   mierror = ""; 
   if (!archivo) { 
      //Si no tengo archivo, es que no se ha seleccionado un archivo en el formulario
        mierror = "No has seleccionado ningún archivo"; 
   }else{ 
      //recupero la extensión de este nombre de archivo 
      extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
      //alert (extension); 
      //compruebo si la extensión está entre las permitidas 
      permitida = false; 
      for (var i = 0; i < extensiones_permitidas.length; i++) { 
         if (extensiones_permitidas[i] == extension) { 
         permitida = true; 
         break; 
         } 
      } 
      if (!permitida) { 
         mierror = "Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
      }else{ 
		  if (formulario.descripcion.value=="") { 
			 mierror = "Asigne la descripción del archivo";
		  }else{ 
			  formulario.extension.value=extension;
			
			  //Enviar! 
			 formulario.ingresar.value="OK";
			 formulario.submit(); 
			 return 1;
		  }
      } 
   } 
   //si estoy aqui es que no se ha podido ENVIAR 
   alert (mierror); 
   return 0; 


}//function



//-->

</script>

 <form name="formulario" method="post" enctype="multipart/form-data" action="<?PHP  echo $_SERVER['PHP_SELF']."?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura'] ?>">

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Adjuntar Formatos</td>

  </tr>

  <tr>	<td align=center >

         <table width="80%" class="tabla1" align="center">
          <tr > 
              <td align="center"  class="segundalinea"> Nombre Proyecto</td>
              <td align="center" colspan="3"  class="segundalinea"><?PHP  echo $datos['nombre_proyecto']; ?></td>
          </tr>
          <tr > 
              <td height="28" align="center"  class="primeralinea">Ficha</td>
              <td align="center"  class="primeralinea">Ubicaci&oacute;n</td>
              <td align="center"  class="primeralinea">Superficie</td>
              <td align="center"  class="primeralinea"># Viviendas</td>
          </tr>
          <tr > 
              <td align="center"  class="segundalinea"><?PHP  echo $datos['id_solicitud']."/".$_GET['abreviatura']."/".$datos['anio']; ?></td>
              <td align="center"  class="segundalinea"><?PHP  echo $datos['direccion']; ?></td>
              <td align="center"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>m<sup>2</sup></td>
            <td align="center"  class="segundalinea"><?PHP  echo $datos['num_viviendas']; ?></td>
          </tr>
			</table><br />
            
		  </legend>
         <table width="80%" class="tabla1" align="center">
          <tr > 
              <td align="center" colspan="3" class="primeralinea">Archivos Adjuntos</td>
          </tr>
          <tr > 
              <td align="center" class="primeralinea">Nombre de Archivo</td>
              <td align="center" class="primeralinea">Descripci&oacute;n</td>
              <td align="center" class="primeralinea">Operaci&oacute;n</td>
          </tr>
          <?php 
			$consulta="SELECT * FROM archivos WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
			$sql_archivo=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


			while($data= mysql_fetch_array($sql_archivo))
				{?>
          <tr > 
              <td align="center" class="segundalinea"><?php echo $data['archivo']; ?></td>
              <td align="center" class="segundalinea"><?php echo $data['descripcion']; ?></td>
              <td align="center" class="segundalinea">
				<?php echo "              
              		<img src=\"../images/b_drop.png\" alt=\"Eliminar archivo\" width=\"16\" height=\"16\" border=\"0\" onclick=\"location.replace('".$_SERVER['PHP_SELF']."?id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']."&anio=".$_GET['anio']."&accion=eliminar&id_archivo=".$data['id_archivo']."')\" />
				";
				?>
			</td>
          </tr>
          <?php }
  			mysql_free_result($sql_archivo);
?>
			</table>          
  <p align="center">
  Descripción<input name="descripcion" type="text" size="42" maxlength="100" /><br />
  Archivo 
    <input name="archivoupload" type="file" id="archivo"> 
  </p> 
  <p align="center">
  <input name="boton" type="button" id="boton" value="Adjuntar Archivo" onclick="comprueba_extension(this.form, this.form.archivoupload.value)"></p> 
		  <input name="ingresar" type="hidden">

		<input name="extension" type="hidden"/>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center">		
   		    <input type="image" src="../images/terminar1.png"  onClick="location.replace('../solicitud/<?php echo "seleccionarS.php?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']; ?>')"/>			</td>

		</tr>

		</table><br>

</td></tr>

</table>
</form>


<?PHP 



}


	
?>
<?PHP 
	mysql_close($conexion);

?>
</body>

</html>

