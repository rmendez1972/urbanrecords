<?PHP 

	$requiredUserLevel = array(1,2,3,6);

	$cfgProgDir = '../';


		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
	mysql_query("set names 'utf8'",$conexion);
	
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
</head>

<body onload="ajustar()">    

    <?php
	
if (isset($_POST['eliminar']) && $_POST['eliminar']=='OK'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "DELETE FROM seguimiento WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']." AND id_seguimiento=".$_POST['etapa'];
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		if ( $_POST['etapa']==1 ){

			$consulta= "DELETE FROM solicitud_revisor WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']."";
	
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		}
		if ( $_POST['etapa']==2 ){

			$consulta= "DELETE FROM formato WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']."";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "DELETE FROM formato_observaciones WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']."";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "DELETE FROM formato2 WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']."";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			
			$consulta= "DELETE FROM formato2_desglose WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']."";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "DELETE FROM datos_vivienda WHERE  anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']."";
	
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			
		}
		if ($_POST['etapa']==3){
		
			$consulta= "UPDATE solicitud SET id_validacion=0 WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "DELETE FROM solicitud_validacion WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}
		if ($_POST['etapa']==4){
			// Eliminar informaci&oacute;n
			$consulta= "DELETE FROM constancias_pagos WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "UPDATE solicitud_revisor SET  estado=0  WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		}
		if ($_POST['etapa']==7){
		
			$consulta= "UPDATE solicitud SET estado='0' WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}

		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


		$destino="seleccionarS.php?backing=1&anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud'];
?>

<script language="javascript">
	window.location='<?PHP  echo $destino ?>';
</script>
<?PHP 

}
	


// Si no ha sido enviado algo

if (isset($_POST['id_solicitud']) && isset($_POST['anio']) && isset($_POST['etapa'])){

		$consulta= "SELECT * 
					FROM `seguimiento` 
					WHERE anio=".$_POST['anio']." AND id_solicitud='".$_POST['id_solicitud']."' AND id_seguimiento=".$_POST['etapa'];

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		if (mysql_num_rows($sql_datos)>0){
			$seguimiento=mysql_fetch_array($sql_datos);

?>


 <br />
 <table width="95%" align="center" class="tablaDatos">
   <tr >
     <td colspan="2"  class="tituTab">Eliminar seguimiento de solicitud</td>
   </tr>
   <tr >
     <td  class="segundalinea">Etapa</td>
     <td  class="segundalinea"><?PHP   
			  	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

			  $consulta= "select * from cat_seguimiento WHERE id_seguimiento=".$_POST['etapa'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$tipos= mysql_fetch_array($resultado);
						echo $tipos['descripcion'];

					mysql_free_result($resultado);
				?>
       <?PHP  echo $_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']; ?>
       <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura']; ?>')" /></td>
   </tr>
   <tr >
     <td class="segundalinea" > Fecha </td>
     <td class="segundalinea" ><?PHP  echo date('Y-m-d') ?></td>
   </tr>
   <?PHP  
		  if ($seguimiento['id_seguimiento']==2){ 
		  

			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);
		  
		  
		  ?>
   <tr >
     <td class="segundalinea">Generales</td>
     <td class="segundalinea" ><?PHP  echo $datos['nombre_proyecto'] ?>
       <br />
       <?PHP  echo $datos['direccion'] ?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Superficie</td>
     <td class="segundalinea" ><?PHP  echo $datos['superficie'] ?>
       m<sup>2</sup></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Fracciones</td>
     <td class="segundalinea" ><?PHP  echo $datos['fracciones'] ?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > Viviendas</td>
     <td class="segundalinea" ><?PHP  echo $datos['num_viviendas'] ?></td>
   </tr>
   <?php 		  	$consulta= "select * from datos_vivienda WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				$data_viv= mysql_fetch_array($resultado);
				mysql_free_result($resultado);

         if (strpos ($_POST['abreviatura'],"CON")!== false  ){ 
?>
   <tr >
     <td  class="segundalinea" > Tipo de Condominio</td>
     <td class="segundalinea" ><?PHP   
			  if (!is_null($data_viv['tipo_con'])){
			  	$consulta= "select * from cat_tipo_con WHERE id_tipo=".$data_viv['tipo_con'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$tipo_v= mysql_fetch_array($resultado);
					echo $tipo_v['descripcion'];
				mysql_free_result($resultado);
			  }
			?></td>
   </tr>
   <tr style="display:<?  if (!is_null($data_viv['tipo_con']) && $data_viv['tipo_con']==2) echo "block"; else echo "none"; ?>" id="rniveles" >
     <td  class="segundalinea" > N&uacute;mero de niveles</td>
     <td class="segundalinea" ><?PHP  echo $data_viv['niveles'] ?></td>
   </tr>
   <tr style="display:<?  if (!is_null($data_viv['tipo_con']) && $data_viv['tipo_con']==2) echo "block"; else echo "none"; ?>" id="rnumxnivel" >
     <td  class="segundalinea" > N&uacute;mero de viviendas por niveles</td>
     <td class="segundalinea" ><?PHP  echo $data_viv['numxnivel'] ?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > N&uacute;mero de viviendas por condominio</td>
     <td class="segundalinea" ><?PHP  echo $data_viv['vivxcon'] ?></td>
   </tr>
   <?php } ?>
   <?php          if (strpos ($_POST['abreviatura'],"FRACC") !== false  || strpos ($_POST['abreviatura'],"CON") !== false  ){
?>
   <tr >
     <td  class="segundalinea" > Tipo de Vivienda</td>
     <td class="segundalinea" ><?PHP   
			  if (!is_null($data_viv['tipo_con'])){
			  	$consulta= "select * from cat_tipo_viv WHERE id_tipo=".$data_viv['tipo_viv'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$tipo_v= mysql_fetch_array($resultado);
					echo $tipo_v['descripcion'];
				mysql_free_result($resultado);
			  }
			?></td>
   </tr>
   <tr >
     <td  class="segundalinea" > No. de cajones de estacionamiento</td>
     <td class="segundalinea" ><?PHP  echo $data_viv['no_cajones'] ?></td>
   </tr>
   <?php } ?>
   <?PHP  
			 	$consulta="SELECT id_solicitud FROM formato WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$sql_form=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				if (mysql_num_rows($sql_form)>0){
			  ?>
   <tr >
     <td class="segundalinea" colspan="2" align="center" >Formato único <img src="../images/b_search.png" alt="Visualizar datos formato único" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/formato_unico.php<?PHP  echo "?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']; ?>')" />
       <?PHP  
			 	$consulta="SELECT id_solicitud FROM formato2 WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$sql_form2=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				if (mysql_num_rows($sql_form2)>0){
			  ?>
       Formato cuadro <img src="../images/b_search.png" alt="Visualizar datos formato cuadro" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/formato_cuadro.php<?PHP  echo "?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']; ?>')" />
       <?PHP  
				
				}
					mysql_free_result($sql_form2);
			  ?></td>
   </tr>
   <?PHP  
			}
		mysql_free_result($sql_form);

		  } ?>
   <?PHP  if ($seguimiento['id_seguimiento']==3){ ?>
   <tr >
     <td class="segundalinea">Estado</td>
     <td class="segundalinea" ><?PHP   
			  	$consulta= "select * from cat_validacion WHERE id_validacion IN (SELECT id_validacion FROM solicitud WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'].")";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				$tipo_v= mysql_fetch_array($resultado);
					echo $tipo_v['descripcion'];
				mysql_free_result($resultado);
			?></td>
   </tr>
   <?PHP  } ?>
   <?PHP  if ($seguimiento['id_seguimiento']==4  || $seguimiento['id_seguimiento']==5 || $seguimiento['id_seguimiento']==6 || $seguimiento['id_seguimiento']==7){ ?>
   <tr >
     <td class="segundalinea">Constancias</td>
     <td class="segundalinea" ><?PHP   
			  	$consulta= "select * from constancias_pagos WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$salto=0;
				while($constancias= mysql_fetch_array($resultado)){
					if ($salto <> 0) echo "<br>";
					
					$cons=mysql_query("select descripcion from cat_tipo_pago where id_tipo_pago=".$constancias["id_tipo_pago"],$conexion);
					$row=mysql_fetch_array($cons);
					
					echo "Orden de pago de ".$row[0]."<br/>";
					echo "Total: $ ".number_format($constancias['derechos'],2,'.',',');
					
					$salto++;
				}
				mysql_free_result($resultado);

			?></td>
   </tr>
   <?PHP  } ?>
   <?PHP  if ($seguimiento['id_seguimiento']==4  || $seguimiento['id_seguimiento']==5 || $seguimiento['id_seguimiento']==6 || $seguimiento['id_seguimiento']==7){ ?>
   <tr >
     <td class="segundalinea">Solicitantes</td>
     <td class="segundalinea" ><?PHP  
	   			$consulta= "select concat( anio ,'-', id_solicitud ,'-', id_solicitante ) AS email,`nombre`, `telefono`, `celular`, `correo`, t.descripcion from solicitantes s, cat_tipo_solicitantes t 
				WHERE s.id_tipo=t.id_tipo AND id_solicitud=".$_POST['id_solicitud']." AND anio=".$_POST['anio'];

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

			 ?></td>
   </tr>
   <?PHP  } ?>
   <tr >
     <td width="20%"  class="segundalinea"><?PHP  if ($seguimiento['id_seguimiento']==2) 
						echo "Pendientes";
					else
						echo "Observaciones";
				?></td>
     <td width="80%" class="segundalinea" ><?PHP  echo $seguimiento['observaciones'] ?></td>
   </tr>
   <?PHP  if ($seguimiento['id_seguimiento']==1){ 
						  
			$consulta= "SELECT nombre FROM cat_usuario u, solicitud_revisor s WHERE s.id_revisor=u.login AND anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		  ?>
   <tr >
     <td class="segundalinea">Revisor</td>
     <td class="segundalinea" ><?PHP  echo mysql_result($sql_user,0); ?></td>
   </tr>
   <?PHP  } ?>
 </table>
 <br />
  <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>" >
<script language="javascript">
	function confirmar (  ) {
		return confirm( "¿Está seguro que desea eliminar el registro?" );
	}
</script>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>
      		<td align="center">
                <INPUT TYPE=image class="btnOld" style=" vertical-align:middle;  border:0 hidden " onclick="return confirmar('')" src="../images/baja1.png" alt="Eliminar Seguimiento" hspace=7 vspace=4  border=0 >

            
          </td>
      		<td align="center"><img src="../images/terminar1.png" alt="Ir a movimientos" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_POST['id_solicitud']."&anio=".$_POST['anio']; ?>'" /></td>
		</tr>
		</table>
        		<input name="eliminar" type="hidden"  value="OK"/>
		<input name="anio" type="hidden" value="<?PHP  echo $_POST['anio'] ?>" />
		<input name="id_solicitud" type="hidden" value="<?PHP  echo $_POST['id_solicitud'] ?>" />
		<input name="abreviatura" type="hidden" value="<?PHP  echo $_POST['abreviatura'] ?>" />
		<input name="etapa" type="hidden" value="<?PHP  echo $_POST['etapa']; ?>" />

</form>


<?PHP 



}
}

?>

</body>

</html>

