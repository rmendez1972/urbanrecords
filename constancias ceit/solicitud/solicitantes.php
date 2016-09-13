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

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Capturar Solicitud de Constancia</td>

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
            <td  class="segundalinea" > Observaciones</td>
            <td class="segundalinea" ><?PHP  echo $datos['observaciones'] ?></td>
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
            <td class="primeralinea" >Operaci&oacute;n</td>
          </tr>
          			<?PHP   
					$consulta= "select * from solicitantes s, cat_tipo_solicitantes t WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					while($solicitantes= mysql_fetch_array($resultado)){
						$data_solicitante="";
						if ($solicitantes['telefono']!="")
							$data_solicitante.=", TEL. ".$solicitantes['telefono'];
						if ($solicitantes['celular']!="")
							$data_solicitante.=", CEL. ".$solicitantes['celular'];
						if ($solicitantes['correo']!="")
							$data_solicitante.=", CORREO: ".$solicitantes['correo'];
						echo "
						  <tr >
							<td class=\"segundalinea\">".$solicitantes['descripcion']."</td>
							<td class=\"segundalinea\">".$solicitantes['nombre']."</td>
							<td class=\"segundalinea\">".$data_solicitante."</td>
							<td class=\"segundalinea\"><img src=\"../images/b_edit.png\" alt=\"Modificar datos solicitante\" width=\"16\" height=\"16\" border=\"0\" onclick=\"window.open('solicitante_mod.php?id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']."&id_solicitante=".$solicitantes['id_solicitante']."', 'miwin', 'width=600, height=400, scrollbars=yes')\" />
							<img src=\"../images/b_drop.png\" alt=\"Eliminar solicitante\" width=\"16\" height=\"16\" border=\"0\" onclick=\"location.replace('".$_SERVER['PHP_SELF']."?id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']."&accion=eliminar&id_solicitante=".$solicitantes['id_solicitante']."')\" />
							</td>							
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

		  <input name="id_cliente" type="hidden" />

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center">
  		<img src="../images/agregarsolicitante1.png" style=" vertical-align:middle;  border:0 hidden " alt="Agregar solicitante" onclick="window.open('solicitante_add.php?id_solicitud=<?PHP  echo $_GET['id_solicitud']; ?>&anio=<?PHP  echo $_GET['anio']; ?>', 'miwin', 'width=600, height=400, scrollbars=yes')"/></td>

      		<td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

		</tr>

		</table><br>

</td></tr>

</table>

</form>

<?PHP 



}
	mysql_close($conexion);



?>

</body>

</html>

