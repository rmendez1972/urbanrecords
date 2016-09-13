<?PHP 

	$requiredUserLevel = array(1,2,3,4,5,6,7);

	$cfgProgDir = '../';


		include("../seguridad/secure.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Informaci&oacute;n de Seguimiento</title>
<script>
function ponPrefijo(nombre,id) {
	window.opener.document.formulario.id_revisor.value = id;
	window.opener.document.formulario.revisor.value = nombre;
	window.close();
}
function cargar(url){
	window.opener.parent.cargar(url);
	window.close();
}
</script>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
</head>
<?PHP  
include ("../libreria/config.php");
include ("../libreria/libreria.php");
	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);

// Si no ha sido enviado algo

if (isset($_GET['id_solicitud']) && isset($_GET['anio'])){

		$consulta= "SELECT * 
					FROM `seguimiento` 
					WHERE anio=".$_GET['anio']." AND id_solicitud='".$_GET['id_solicitud']."' AND id_seguimiento=".$_GET['id_seguimiento'];

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		if (mysql_num_rows($sql_datos)>0){
			$seguimiento=mysql_fetch_array($sql_datos);

?>


 <br />
 <table width="95%" align="center" class="tablaDatos">
   <tr >
     <td colspan="2"  class="tituTab">Detalles del seguimiento</td>
   </tr>
   <tr >
     <td  class="segundalinea">Etapa</td>
     <td  class="segundalinea"><?PHP   
			  $consulta= "select * from cat_seguimiento WHERE id_seguimiento=".$_GET['id_seguimiento'];

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$tipos= mysql_fetch_array($resultado);
						echo $tipos['descripcion'];

					mysql_free_result($resultado);
				?>
       <?PHP  echo $_GET['id_solicitud']."/".$_GET['abreviatura']."/".$_GET['anio']; ?>
       <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" /></td>
   </tr>
   <tr >
     <td class="segundalinea" > Fecha </td>
     <td class="segundalinea" ><?PHP  echo date('d/m/Y',strtotime($seguimiento["fecha"])) ?></td>
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
   <?php 		  	$consulta= "select * from datos_vivienda WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				$data_viv= mysql_fetch_array($resultado);
				mysql_free_result($resultado);

         if (strpos ($_GET['abreviatura'],"CON")!== false  ){ 
?>
   <tr >
     <td class="segundalinea">Tipo de Condominio</td>
     <td class="segundalinea"><?PHP   
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
   <?php          if (strpos ($_GET['abreviatura'],"FRACC") !== false  || strpos ($_GET['abreviatura'],"CON") !== false  ){
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
			 	$consulta="SELECT id_solicitud FROM formato WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$sql_form=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				if (mysql_num_rows($sql_form)>0){
			  ?>
   <tr >
     <td class="segundalinea" colspan="2" align="center" >Formato &uacute;nico <img src="../images/b_search.png" alt="Visualizar datos formato único" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/formato_unico.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']; ?>')" />
       <?PHP  
			 	$consulta="SELECT id_proyecto1 FROM solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$sql_form2=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$restot=mysql_fetch_row($sql_form2);
				$tipop=$restot[0];
				if ($tipop==1 || ($tipop>=5 && $tipop<= 10)){
			  ?>
       Formato cuadro <img src="../images/b_search.png" alt="Visualizar datos formato cuadro" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/formato_cuadro.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']; ?>')" />
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
			  	$consulta= "select * from cat_validacion WHERE id_validacion IN (SELECT id_validacion FROM solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'].")";
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
			  	$consulta= "select * from constancias_pagos WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$salto=0;
				
				$cons=mysql_query("select login from solicitud_autoriza2 where id_solicitud=".$_GET["id_solicitud"]." and anio=".$_GET["anio"],$conexion);
				if(mysql_fetch_row($cons))
					$generar=true;
				else
					$generar=false;
				$generar=true;
				
				echo "<div><strong>Órdenes de pago:</strong>".($userLevel==1||$userLevel==6?" - <a href='#x' onclick=\"cargar('solicitud/constancias.php?anio=".$_GET["anio"]."&id_solicitud=".$_GET["id_solicitud"]."')\">Editar órdenes</a>":"")."</div>";
				while($constancias= mysql_fetch_array($resultado)){
					if ($salto <> 0) echo "<br>";
					
					$cons=mysql_query("select descripcion from cat_tipo_pago where id_tipo_pago=".$constancias["id_tipo_pago"],$conexion);
					$row=mysql_fetch_array($cons);
					
					echo "Orden de pago de ".$row[0]."<br/>";
					echo " <a href='../constanciasceu/ordenpago.php?idsol=".$_GET["id_solicitud"]."&anio=".$_GET["anio"]."&numero=".$constancias["id_tipo_pago"]."'>Generar orden de pago<img src='../images/b_search.png' border='0' /></a>";
					
					echo "<br />Total: $ ".number_format($constancias['derechos'],2,'.',',');
					
					$salto++;
				}
				
				if($generar){
						echo "<br /><br /><a href='../constanciasceu/genconst.php?idsol=".$_GET["id_solicitud"]."&anio=".$_GET["anio"]."'>Generar Constancia <img src='../images/b_search.png' border='0' /></a>";
						echo "<br /><a href='../constanciasceu/genconst.php?edit=1&idsol=".$_GET["id_solicitud"]."&anio=".$_GET["anio"]."'>Editar Constancia <img src='../images/edit.png' width='18' border='0' /></a>";
						
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
     <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" class="btnOld" onclick="window.close();"  /></td>
   </tr>
 </table>
 
<?PHP 



}
}

?>

</body>

</html>

