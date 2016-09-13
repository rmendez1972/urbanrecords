<?PHP 

// Verificar el acceso
		$requiredUserLevel = array(1);
		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

	include ("../libreria/config.php");

	include ("../libreria/encabezado.php");

	include ("../libreria/libreria.php");



   // Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

		$consulta= "select id_sucursal,login,nombre,descripcion from cat_usuario u, cat_perfiles p WHERE u.id_perfil=p.id_perfil ";

// 	if ($userLevel!=1) 

	//	$consulta.= " where id_sucursal='$district'";

	$consulta.= " order by login";



	$resultado=mysql_query($consulta,$conexion);

	$existe= mysql_num_rows($resultado);

?>

		 <table width="70%" class="tabla1" align="center">

		  <tr class="fuente2"> 

		    <td align="center" class="td1">Usuarios del Sistema</font> </td>

		  </tr>

		  <tr>

		    <td align="center"> <br>

			 <table width="95%" class="tabla2">

        <tr class="aviso"> 

          <td  align="center" class="primeralinea">  

              Usuario </td>

          <td  align="center"  class="primeralinea">  

              Nombre </td>

          <td  align="center"  class="primeralinea"> Tipo 

              </td>


        </tr>

        <?PHP 

	 

	while ($usuarios= mysql_fetch_array($resultado)){

?>

        <tr > 

          <td align="center" class="segundalinea" >  <?PHP  echo $usuarios['login'] ?> 

          </td>

          <td align="center"  class="segundalinea">  <?PHP  echo $usuarios['nombre']; ?> 

          </td>

          <td align="center"  class="segundalinea">  

            <?PHP  echo $usuarios['descripcion'] ?>
          </td>


        </tr>

        <?PHP 

	}	

	if (!$existe){

?>

        <tr class="aviso"> 

          <td align="center"  colspan="4"> No hay usuarios 

            del sistema para visualizar</td>

        </tr>

        <?PHP 

	}

?>

      </table>

      <br>

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

