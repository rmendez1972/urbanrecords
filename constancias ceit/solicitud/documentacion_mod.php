<?PHP 

		$requiredUserLevel = array(1,2);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/encabezado.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

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
<script language="javascript" >
function envia(){
		document.forms.formulario.ingresar.value="OK";
		document.forms.formulario.submit( );
}
</script>
<form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Modificar datos Solicitud de Constancia</td>

  </tr>

  <tr>	<td align=center >

  		<br>

		<table width="95%" class="tabla2">
          <tr > 
            <td colspan="2" class="primeralinea" align="center">Datos solicitud</td>
		</tr>
          <tr > 

            <td class="segundalinea">Ficha No. </td>

            <td class="segundalinea" ><?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?><img src="../images/b_edit.png" alt="Modificar datos solicitud" width="16" height="16" border="0" onclick="location.replace('editar.php<?PHP  echo "?id_solicitud=".$datos['id_solicitud']."&anio=".$datos['anio']; ?>')" /><img src="../images/b_search.png" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']."&abreviatura=".$datos['abreviatura']; ?>')" alt="Visualizar datos solicitud" width="16" height="16" border="0" /></td>
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
            <td width="20%"  class="segundalinea">  Propietario</td>
            <td width="80%" class="segundalinea" >  <?PHP  echo $datos['propietario'] ?>            </td>
          </tr>
          <tr > 
            <td width="20%"  class="segundalinea">  Proyecto</td>
            <td width="80%" class="segundalinea" >  <?PHP  echo $datos['nombre_proyecto'] ?></td>
          </tr>
          <tr > 
            <td width="20%"  class="segundalinea">Direcci&oacute;n</td>
            <td width="80%" class="segundalinea" ><?PHP  echo $datos['direccion'] ?></td>
          </tr>
          <tr >
            <td class="segundalinea">Municipio</td>
            <td class="segundalinea" >
                <?PHP   $consulta= "select * from cat_municipios WHERE id_municipio=".$datos['id_municipio'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$municipios= mysql_fetch_array($resultado);
					echo $municipios['descripcion'];

				?>            </td>
          </tr>
          <tr >
            <td class="segundalinea">Localidad</td>
            <td class="segundalinea" >
			<?PHP   if ($datos['id_localidad']!=0){
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
            <td  class="segundalinea" >Observaciones</td>
            <td class="segundalinea" ><textarea name="observaciones" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $datos['observaciones'] ?></textarea></td>
          </tr>
        </table>
    </td>
    
      </tr>
    
      <tr>	<td align=center ><br />
      
		<table width="95%" class="tabla2">
          <tr > 
            <td colspan="4" class="primeralinea" align="center">Documentaci&oacute;n Entregada</td>
		</tr>
          <tr > 
            <td height="25" width="40" class="primeralinea">Entreg&oacute;
</td>
            <td class="primeralinea" >Requisito</td>
          </tr>
          			<?PHP   
					$consulta= "select * from cat_requisitos WHERE id_requisito IN (SELECT id_requisito FROM cat_requisitos_opc WHERE id_tipo=".$datos['id_proyecto1'].")";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					while($solicitantes= mysql_fetch_array($resultado)){


						$consulta= "select entrego from doctos WHERE anio=".$datos['anio']." AND id_solicitud='".$datos['id_solicitud']."' AND id_requisito=".$solicitantes['id_requisito'];
	
						$sql_entrego=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
						$opcion1=""; $opcion2="";
						if (mysql_num_rows($sql_entrego)==0){
							$opcion1=""; $opcion2="selected=\"selected\"";
						}else{
							if (mysql_result($sql_entrego,0)=="SI")
								$opcion1="selected=\"selected\"";
							if (mysql_result($sql_entrego,0)=="NO")
								$opcion2="selected=\"selected\"";
						}
						
						echo "
						  <tr >
							<td class=\"segundalinea\">
							  <select name=\"".$solicitantes['id_requisito']."\">
							  <option value=\"SI\" ".$opcion1.">SI</option>
							  <option value=\"NO\" ".$opcion2.">NO</option>
							  </select>
							</td>
							<td class=\"segundalinea\">".$solicitantes['descripcion']."</td>
						  </tr>
						";
					}
				?>
		</table>
</td>

  </tr>
      <tr>	<td align=center >


<br />

		  <input name="ingresar" type="hidden">

		  <input name="anio" type="hidden" value="<?PHP  echo $datos['anio'] ?>" />
		  <input name="id_solicitud" type="hidden" value="<?PHP  echo $datos['id_solicitud'] ?>" />
		  <input name="id_proyecto" type="hidden" value="<?PHP  echo $datos['id_proyecto1'] ?>" />

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center">
<img src="../images/continuar1.png" onmouseover="" alt="Capturar solicitantes" onClick="envia()" />			</td>

      		<td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

		</tr>

		</table><br>

</td></tr>

</table>

</form>


<?PHP 



}
if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "DELETE FROM doctos WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "SELECT id_requisito FROM cat_requisitos_opc WHERE id_tipo=".$_POST['id_proyecto']."";

		$sql_req=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		while($requisito=mysql_fetch_array($sql_req)){
			$consulta= "insert into doctos values (".$_POST['anio'].",".$_POST['id_solicitud'].",'".$requisito['id_requisito']."','".$_POST[$requisito['id_requisito']]."')";
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}
		mysql_free_result($sql_req);
		
		$consulta= "UPDATE solicitud SET observaciones='".$_POST['observaciones']."' WHERE anio=".$_POST['anio']." AND ".$_POST['id_solicitud'];
		//echo $consulta."<br>";
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		
		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	
?>
<script language="javascript">

	window.location='solicitantes.php?id_solicitud=<?PHP   echo $id_solicitud; ?>&anio=<?PHP   echo $_POST['anio']; ?>';

</script>

<?PHP 

}	mysql_close($conexion);



?>

</body>

</html>

