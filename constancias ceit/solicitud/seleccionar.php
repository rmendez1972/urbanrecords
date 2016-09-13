<?PHP 

		$requiredUserLevel = array(1,2);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/config.php");

include ("../libreria/encabezado.php");

include ("../libreria/libreria.php");



// Conexion con la BD

$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

?>
 <table width="80%" class="tabla1" align="center">

  <tr class="fuente2"> 

       			<?PHP   
					$consulta= "select * from cat_seguimiento";
	
					$sql_seg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					while($dato_seg= mysql_fetch_array($sql_seg)){
						echo '<td width="3%" height="26" align="center" class="segundalinea" >'.$dato_seg['id_seguimiento']." --> ".$dato_seg['descripcion'].'</td>';			
					}
					mysql_free_result($sql_seg);
				?>
                </tr></table>
                VALID&oacute; REVISI&oacute;N
</body>

</html>

