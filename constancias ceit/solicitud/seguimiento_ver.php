<?PHP 

	$requiredUserLevel = array(1,2,3);

	$cfgProgDir = '../';


		include("../seguridad/secure.php");

?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>
<title>Información de Seguimiento</title>
<script>
function ponPrefijo(nombre,id) {
	window.opener.document.formulario.id_revisor.value = id;
	window.opener.document.formulario.revisor.value = nombre;
	window.close()
}
</script>
<link href="../libreria/estilos.css" rel="stylesheet" type="text/css">
</head>
<?PHP  
include ("../libreria/config.php");
include ("../libreria/libreria.php");
	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);


// Si no ha sido enviado algo

if (isset($_GET['id_solicitud']) && isset($_GET['anio'])){

		$consulta= "SELECT * 
					FROM `seguimiento` 
					WHERE anio=".$_GET['anio']." AND id_solicitud='".$_GET['id_solicitud']."' AND id_seguimiento=".$_GET['id_seguimiento'];

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		if (mysql_num_rows($sql_datos)>0){
			$seguimiento=mysql_fetch_array($sql_datos);

?>


 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Datos Seguimiento Solicitud</td>

  </tr>

  <tr>	<td align=center ><br>

		<table width="95%" class="tabla2">
          <tr >
            <td  class="segundalinea">Etapa</td>
            <td  class="segundalinea">
              <?PHP   
			  	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

			  $consulta= "select * from cat_seguimiento WHERE id_seguimiento=".$_GET['id_seguimiento'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$tipos= mysql_fetch_array($resultado);
						echo $tipos['descripcion'];

					mysql_free_result($resultado);
				?>
           
            <?PHP  echo $_GET['id_solicitud']."/".$_GET['abreviatura']."/".$_GET['anio']; ?><img src="../images/b_search.png" onClick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" alt="Visualizar datos solicitud" width="16" height="16" border="0" />            </td>
          </tr>
          <tr > 
            <td class="segundalinea" > Fecha </td>
            <td class="segundalinea" ><?PHP  echo date('Y-m-d') ?></td>
          </tr>
          <?PHP  
		  if ($seguimiento['id_seguimiento']==2){ 
		  

			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);
		  
		  
		  ?>
          <tr >
            <td class="segundalinea">Generales</td>
            <td class="segundalinea" ><?PHP  echo $datos['nombre_proyecto'] ?><br /><?PHP  echo $datos['direccion'] ?></td>
          </tr>          
          <tr > 

            <td  class="segundalinea" > Superficie</td>

            <td class="segundalinea" ><?PHP  echo $datos['superficie'] ?>  m<sup>2</sup></td>
          </tr>
          <tr > 
            <td  class="segundalinea" > Fracciones</td>
            <td class="segundalinea" ><?PHP  echo $datos['fracciones'] ?></td>
          </tr>
          <tr > 
            <td  class="segundalinea" > Viviendas</td>
            <td class="segundalinea" >
<?PHP  echo $datos['num_viviendas'] ?></td>
          </tr>
             <?PHP  
			 	$consulta="SELECT id_solicitud FROM formato WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$sql_form=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				if (mysql_num_rows($sql_form)>0){
			  ?>
          <tr > 
            <td class="segundalinea" colspan="2" align="center" >Formato único <img src="../images/b_search.png" onClick="window.open('../reportes/formato_unico.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']; ?>')" alt="Visualizar datos formato único" width="16" height="16" border="0" />
             <?PHP  
			 	$consulta="SELECT id_solicitud FROM formato2 WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$sql_form2=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				if (mysql_num_rows($sql_form2)>0){
			  ?>
Formato cuadro <img src="../images/b_search.png" onClick="window.open('../reportes/formato_cuadro.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']; ?>')" alt="Visualizar datos formato cuadro" width="16" height="16" border="0" />             <?PHP  
				
				}
					mysql_free_result($sql_form2);
			  ?>

            </td>
          </tr>
          <?PHP  
			}
		mysql_free_result($sql_form);

		  } ?>
          <?PHP  if ($seguimiento['id_seguimiento']==3){ ?>
          <tr >
            <td class="segundalinea">Estado</td>
            <td class="segundalinea" >
              <?PHP   
			  	$consulta= "select * from cat_validacion WHERE id_validacion IN (SELECT id_validacion FROM solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'].")";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				$tipo_v= mysql_fetch_array($resultado);
					echo $tipo_v['descripcion'];
				mysql_free_result($resultado);
			?>
            </td>
          </tr>
          <?PHP  } ?>
          <?PHP  if ($seguimiento['id_seguimiento']==4  || $seguimiento['id_seguimiento']==5 || $seguimiento['id_seguimiento']==6 || $seguimiento['id_seguimiento']==7){ ?>
          <tr >
            <td class="segundalinea">Constancias</td>
            <td class="segundalinea" >
              <?PHP   
			  	$consulta= "select * from constancias WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$salto=0;
				echo "Constancias No. : ";
				while($constancias= mysql_fetch_array($resultado)){
					if ($salto <> 0) echo ", ";
					echo $constancias['numero']."/".$constancias['municipio']."/".$constancias['anio'];
					$salto++;
				}
				mysql_free_result($resultado);

			  	$consulta= "select * from constancias_pagos WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				while($pagos= mysql_fetch_array($resultado)){
					echo "<br>";
					echo "Sanci&oacute;n: $ ".number_format($pagos['sancion'],2,'.',',')." + derechos: $ ".number_format($pagos['derechos'],2,'.',',')." = $ ".number_format(($pagos['sancion']+$pagos['derechos']),2,'.',',');
				}
				mysql_free_result($resultado);
			?>
            </td>
          </tr>
          <?PHP  } ?>
          <?PHP  if ($seguimiento['id_seguimiento']==4  || $seguimiento['id_seguimiento']==5 || $seguimiento['id_seguimiento']==6 || $seguimiento['id_seguimiento']==7){ ?>
          <tr >
            <td class="segundalinea">Solicitantes</td>
            <td class="segundalinea" >
			<?PHP  
	   			$consulta= "select concat( anio ,'-', id_solicitud ,'-', id_solicitante ) AS email,`nombre`, `telefono`, `celular`, `correo`, t.descripcion from solicitantes s, cat_tipo_solicitantes t 
				WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$_GET['id_solicitud']." AND anio=".$_GET['anio'];

				$sql_solicitante=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$data_solicitante="";
				while($solicitantes= mysql_fetch_array($sql_solicitante)){
					if ($data_solicitante!=""){ echo "<br>"; $data_solicitante=""; }
					$data_solicitante.=$solicitantes['descripcion'].": ".$solicitantes['nombre'];
					if ($solicitantes['telefono']!="")
						$data_solicitante.=", TEL. ".$solicitantes['telefono'];
					if ($solicitantes['celular']!="")
						$data_solicitante.=", CEL. ".$solicitantes['celular'];
					if ($solicitantes['correo']!="")
						$data_solicitante.=", CORREO: ".$solicitantes['correo'];
						
					echo $data_solicitante;
				}
				mysql_free_result($sql_solicitante);	

			 ?>
            </td>
          </tr>
          <?PHP  } ?>
          <tr > 
            <td width="20%"  class="segundalinea">
            	<?PHP  if ($seguimiento['id_seguimiento']==2) 
						echo "Pendientes";
					else
						echo "Observaciones";
				?></td>
            <td width="80%" class="segundalinea" ><?PHP  echo $seguimiento['observaciones'] ?></td>
          </tr>
          <?PHP  if ($seguimiento['id_seguimiento']==1){ 
						  
			$consulta= "SELECT nombre FROM cat_usuario u, solicitud_revisor s WHERE s.id_revisor=u.login AND anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		  ?>
          <tr >
            <td class="segundalinea">Revisor</td>
            <td class="segundalinea" ><?PHP  echo mysql_result($sql_user,0); ?></td>
          </tr>
          <?PHP  } ?>
        </table>

		<br />

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>
      		<td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" onClick="window.close();"  /></td>
		</tr>
		</table>
		<br>

</td></tr>

</table>


<?PHP 



}
}

?>

</body>

</html>

