<?PHP 

// Verificar el acceso
		$requiredUserLevel = array(1);
		$cfgProgDir =  '../';

		include("../seguridad/secure.php");



include ("../libreria/config.php");

include ("../libreria/encabezado.php");

include ("../libreria/libreria.php");

?>

<script LANGUAGE="JavaScript">

<!--

function confirmar()

{

var aceptar=confirm("¿Está seguro que desea eliminar?");

if (aceptar)

	return true ;

else

	return false ;

}

// -->

</script>





<?PHP 

if (isset($_GET['tipo'])){

// Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

	$elimina= "delete from cat_usuario where login='".$_GET['tipo']."'";

	$resultado=mysql_query($elimina,$conexion);

	

		mysql_close($conexion);

?>

		 <table width="70%" class="tabla1" align="center">

		  <tr > 

		    

    <td align="center" class="primeralinea"> Eliminado</font> 

    </td>

		  </tr>

		  <tr>

		    <td align="center"> <br>

			 <table width="70%" class="tabla2" >

			  <tr class="fuente2">

		      <td colspan=2 align="center" > 

				<p>  El usuario ha sido eliminado con &eacute;xito. 

              Si desea continuar eliminando, presione el bot&oacute;n Eliminar. Si desea 

              terminar la operaci&oacute;n, presione el bot&oacute;n Terminar. </font> </p>  

	    	  </td>

			  </tr>

			  </table><br /><table width="50%">

			  <tr>

		      <td align="center"><img src="../menu/usereliminar1.png" alt="Eliminar otro usuario" onclick="location.replace('<?PHP  echo $_SERVER['PHP_SELF'] ?>')" /></td>

			    <td align="center" ><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

			  </tr>

			  </table>

		    

      <br>

    </td>

		  </tr>

	  </table>



<?PHP  

exit();

}



// Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

	$consulta= "select login,nombre from cat_usuario";

 	if ($userLevel!=1) 

		$consulta.= " where id_sucursal='$district'";

	$consulta.= " order by login";



	$resultado=mysql_query($consulta,$conexion);

	$existe= mysql_num_rows($resultado);

?>

		 <table width="70%" class="tabla1" align="center">

		  <tr > 

		    

    <td align="center" class="primeralinea">Usuarios del Sistema</td>

		  </tr>

		  <tr>

		    <td align="center"><br>

		      <table width="70%" class="tabla2">

			  <tr class="fuente2">

		        <td align="center"> Presione sobre el usuario que desee eliminar.

			 </tr>

<?PHP 

	while ($usuarios= mysql_fetch_array($resultado)){

?>

			  <tr>

		      

          <td align="center" class="segundalinea"> 

		  <?PHP  echo "<a href='".$_SERVER['PHP_SELF']."?tipo=".$usuarios['login']."' "."onclick='return confirmar()' >".$usuarios['login']."-->".$usuarios['nombre']."</a>" ?>

          </td>

			  </tr>

 <?PHP 

	}	

	if (!$existe){

?>

			  <tr class="aviso">

		      

          <td align="center"  > No hay usuarios 

            para visualizar</font></td>

			  </tr>

<?PHP 

	}

	

	mysql_free_result($resultado);

	mysql_close($conexion);



?>



			  </table><br>

			 <table width="70%" >

			  <tr>

		      <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

			  </tr>

			  </table>

			

      <br>

    </td>

		  </tr>

	  </table>

</body>

</html>

