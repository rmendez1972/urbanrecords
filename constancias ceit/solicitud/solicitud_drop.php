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
if (isset($_POST['cancelar']) && $_POST['cancelar']=='OK'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "UPDATE solicitud SET estado=1 WHERE id_solicitud=".$_POST['id_solicitud']." AND anio=".$_POST['anio'];

		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	
?>
<script language="javascript">

	window.location='seleccionarS.php';

</script>

<?PHP 

}// Si no ha sido enviado algo

if (isset($_GET['id_solicitud']) ){

		$consulta= "SELECT * 
					FROM`solicitud` s, cat_tipo_proy tp 
					WHERE id_tipo=id_proyecto1 
						AND anio=".$_GET['anio']." AND id_solicitud='".$_GET['id_solicitud']."'";

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$datos=mysql_fetch_array($sql_datos);

?>
<script LANGUAGE="JavaScript">

<!--

function confirmar()

{

var aceptar=confirm("¿Está seguro que desea dar de baja?");

if (aceptar){
	document.forms.formulario.cancelar.value="OK";
	return true ;

}else

	return false ;

}

// -->

</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Dar de Baja Solicitud de Constancia</td>

  </tr>

  <tr>	<td align=center >

  		<br>

		<table width="95%" class="tabla2">
          <tr > 
            <td colspan="2" class="primeralinea" align="center">Datos solicitud</td>
		</tr>
          <tr > 

            <td class="segundalinea">Ficha No. </td>

            <td class="segundalinea" ><?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?><img src="../images/b_edit.png" alt="Modificar datos solicitud" width="16" height="16" border="0" onclick="location.replace('editar.php<?PHP  echo "?id_solicitud=".$datos['id_solicitud']."&anio=".$datos['anio']; ?>')" /></td>
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
					echo $localidad['descripcion'];
				}
				?></td>
          </tr>
          <tr >
            <td  class="segundalinea" > Superficie</td>
            <td class="segundalinea" ><?PHP  echo $datos['superficie'] ?></td>
          </tr>
        </table>
    </td>
    
      </tr>
    
      <tr>	<td align=center ><br />
      
		<table width="95%" class="tabla2">
          <tr > 
            <td colspan="4" class="primeralinea" align="center">Datos solicitantes</td>
		</tr>
          <tr > 
            <td height="25" class="primeralinea">Tipo</td>
            <td class="primeralinea" >Nombre</td>
            <td class="primeralinea" >Contacto</td>
          </tr>
          			<?PHP   
					$consulta= "select * from solicitantes s, cat_tipo_solicitantes t WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					while($solicitantes= mysql_fetch_array($resultado)){
						echo "
						  <tr >
							<td class=\"segundalinea\">".$solicitantes['descripcion']."</td>
							<td class=\"segundalinea\">".$solicitantes['nombre']."</td>
							<td class=\"segundalinea\">".$solicitantes['telefono'].", ".$solicitantes['celular'].", ".$solicitantes['correo']."</td>
						  </tr>
						";
					}
				?>
		</table>
</td>

  </tr>
      <tr>	<td align=center >


<br />

	    <input name="cancelar" type="hidden">
	    <input name="anio" type="hidden" value="<?PHP  echo $datos['anio'] ?>">
	    <input name="id_solicitud" type="hidden" value="<?PHP  echo $datos['id_solicitud'] ?>">

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center"><input type="image" src="../images/baja1.png" style="border:0 hidden " alt="Dar de baja solicitud" onclick='return confirmar()'/></td>

      		<td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>
		</tr>
		</table>
		<br>

</td></tr>

</table>

</form>

<?PHP 



}
	mysql_close($conexion);



?>

</body>

</html>

