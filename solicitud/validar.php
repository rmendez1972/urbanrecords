<?PHP 

		$requiredUserLevel = array(1,4);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/config.php");
include ("../libreria/libreria.php");



// Conexion con la BD

$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

mysql_query("set names 'utf8'",$conexion);

if (isset($_POST['accion']) && $_POST['accion']=="invalidar"){
	$anio=$_POST["anio"];
	$idsol=$_POST["id_solicitud"];
	mysql_query("update solicitud set improcedente=1 where id_solicitud='$idsol' and anio='$anio'",$conexion);
}
if (isset($_POST['accion']) && $_POST['accion']=="validar"){

		$repetidos="";
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$consulta= "SELECT count(id_solicitud) FROM solicitud_autoriza2 WHERE anio=".$_POST['anio']." AND id_solicitud='".$_POST['id_solicitud']."'"; 
			$sql_existe=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			if (mysql_result($sql_existe,0)==0){
				$consulta= "INSERT INTO solicitud_autoriza2 values (".$_POST['anio'].", ".$_POST['id_solicitud'].",'".$login."',NOW())"; 
				//echo $consulta."<br>";
				mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				
				mysql_query("update solicitud set editable=0 where anio='".$_POST['anio']."' and id_solicitud='".$_POST['id_solicitud']."'",$conexion);
			}else{
				$repetidos= $_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'];
			}

		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


?>
<script language="javascript">
	<? if (isset($repetidos) && $repetidos!=""){ 
		echo 'alert("La ficha con número '.$repetidos.' ha sido autorizada previamente.");';
	 } ?>
</script>


<?PHP 
}
// Despliega  

		$consulta= "SELECT * 
					FROM `solicitud` s, cat_tipo_proy tp 
					WHERE id_tipo=id_proyecto1 and s.improcedente <> 1 ";

			$consulta.= '
				AND estado=0 AND id_validacion=1
				AND (concat( anio,"/", id_solicitud ) 
				NOT IN (
					SELECT concat( anio,"/", id_solicitud ) 
					FROM `solicitud_autoriza2` 
				))
				AND concat( anio,"/", id_solicitud ) 
				IN ( SELECT concat( anio,"/", id_solicitud ) FROM `seguimiento` WHERE id_seguimiento=4)			
			';
			
		$consulta.= " ORDER BY s.anio,s.id_solicitud";
//echo $consulta;				
		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	$existe= mysql_num_rows($resultado);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<SCRIPT language="javascript">

<!--
function validar(valor){
	var answer = confirm("¿Está seguro que desea autorizar la ficha "+valor+"?");
	if (answer){
		var elem = valor.split('/');
		document.forms.formulario.anio.value=elem[2];
		document.forms.formulario.abreviatura.value=elem[1];
		document.forms.formulario.id_solicitud.value=elem[0];
		document.forms.formulario.accion.value="validar";
		document.forms.formulario.submit();
	}
}
function invalidar(idsol, anio){
	if(confirm("¿Está seguro que desea marcar la solicitud como improcedente?")){
		document.forms.formulario.anio.value=anio;
		document.forms.formulario.id_solicitud.value=idsol;
		document.forms.formulario.accion.value="invalidar";
		document.forms.formulario.submit();
	}
}
function mostrarMapa(anio, idsol){
	parent.abrirShadowbox("solicitud/mapav2.php?anio="+anio+"&idsol="+idsol,"iframe","",640,480);	
}

function autmod(anio, idsol){
	if(confirm("¿Está seguro que desea autorizar?")){
		var params=new Object();
		params.modulo="SOLICITUD";
		params.accion="AMOD";
		params.anio=anio;
		params.idsol=idsol;
		
		$.post("ControladorSol.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Modificación autorizada");
				location.reload();
			}
			else
				parent.error(datos);
		},"html");
	}
}

function verResumen(anio, idsol){
	parent.abrirShadowbox("solicitud/resumen.php?anio="+anio+"&idsol="+idsol,"iframe","",400,300);
}

function negmod(anio, idsol){
	if(confirm("¿Está seguro que desea denegar?")){
		var params=new Object();
		params.modulo="SOLICITUD";
		params.accion="NMOD";
		params.anio=anio;
		params.idsol=idsol;
		
		$.post("ControladorSol.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Modificación denegada");
				location.reload();
			}
			else
				parent.error(datos);
		},"html");
	}
}
//-->

</script>
</head>

<body onLoad="ajustar()">
<div class="tituSec" style="margin-bottom:20px">Listado de solicitudes sin autorizar</div>
<form name="formulario" method="post" action="<? echo $_SERVER['PHP_SELF'] ?>">

<div style="text-align:center">Presione sobre el icono para visualizar.
                    <br />
                    Claves de Seguimiento: 
                    <?PHP   $consulta= "select * from cat_seguimiento";

					$sql_seg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$cont=mysql_num_rows($sql_seg);
					while($dato_seg= mysql_fetch_array($sql_seg)){
						$cont--;
						echo $dato_seg['id_seguimiento'].": ".$dato_seg['descripcion'];
						if ($cont<>0)
							echo ", ";
							
					}

				?>                 </div>
<table width="95%" align="center" class="tablaDatos">

			  <tr class="tituTab">
		          <td width="15%" rowspan="2" align="center" class="primeralinea" >Operación</td>
	            <td width="8%" rowspan="2" align="center" class="primeralinea" >Ficha</td>
                <td width="9%" rowspan="2" align="center" class="primeralinea" >Fecha</td>
	            <td width="33%"  rowspan="2" align="center" class="primeralinea" >Descripción</td>
	            <td height="40" colspan="11" align="center" class="primeralinea" >Seguimiento</td>
			  </tr>
			  <tr class="tituTab">
       			<?PHP   
					$consulta= "select * from cat_seguimiento";
	
					$sql_seg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					while($dato_seg= mysql_fetch_array($sql_seg)){
						echo '<td width="3%" height="26" align="center" class="primeralinea" >'.$dato_seg['id_seguimiento'].'</td>';			
					}
					mysql_free_result($sql_seg);
				?>

	            <td height="26" align="center" class="primeralinea" >Acciones</td>
			  </tr>

<?PHP 
	while ($datos= mysql_fetch_array($resultado)){
?>

			  <tr class="regTab">

		      

          <td align="center"  class="segundalinea"> 

          <img src="../images/b_search.png" alt="Visualizar datos solicitud" title="Ver solicitud" width="18" height="18" border="0" class="btnOld" onClick="window.open('../reportes/formato_unico.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']."&abreviatura=".$datos['abreviatura']; ?>')" /> <img src="../images/mapa.png" width="22" height="22" class="btnOld" onClick="mostrarMapa(<? echo $datos["anio"]; ?>,<? echo $datos["id_solicitud"]; ?>)" title="Ver en mapa" />
          <img src="../images/resumen.png" title="Ver resumen" width="18" height="18" border="0" class="btnOld" onClick="verResumen(<?php echo $datos['anio'].",".$datos['id_solicitud']; ?>)" />
          </td>

          <td align="center"  class="segundalinea"> 
			<?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?>		  </td>
          <td align="center"  class="segundalinea"> 
			<?PHP  echo date("d/m/Y",strtotime($datos["fecha_ingreso"])); ?>          </td>
          <td align="left"  class="segundalinea"> 
			<?PHP  echo $datos["propietario"]; ?><br />
			<?PHP  echo $datos["nombre_proyecto"]; ?>		  </td>
			<?PHP   
				$terminado=0; $etapa=0;
				$consulta= "select * from cat_seguimiento";

				$sql_seg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$etapas=mysql_num_rows($sql_seg);

				$consulta= "select count(id_seguimiento) from seguimiento WHERE anio=".$datos["anio"]." AND id_solicitud=".$datos["id_solicitud"];
				$sql_nseg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$numero_seg=mysql_result($sql_nseg,0);


				while($dato_seg= mysql_fetch_array($sql_seg)){
							
			
					$consulta= "select * from seguimiento WHERE anio=".$datos["anio"]." AND id_solicitud=".$datos["id_solicitud"]." AND id_seguimiento=".$dato_seg['id_seguimiento'];
	
					$sql_seguimiento=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					if (mysql_num_rows($sql_seguimiento)>0){
						while($data_seg= mysql_fetch_array($sql_seguimiento)){
							 echo '
							 <td align="center" class="segundalinea">
							 <img src="../images/check.png" style="cursor:pointer" alt="Visualizar '.$data_seg['fecha'].'" width="16" height="16" border="0"  onclick="window.open(\'seguimiento_ver.php?anio='.$datos['anio'].'&id_solicitud='.$datos["id_solicitud"].'&id_seguimiento='.$dato_seg['id_seguimiento'].'&abreviatura='.$datos['abreviatura'].'\',\'miwin\',\'width=600,height=400,scrollbars=yes\')" />';
							 
							 
							echo '</td>
							';						

							if ($dato_seg['id_seguimiento']==$etapas) $terminado++;
							$etapa=$dato_seg['id_seguimiento'];
						}
					}else
						echo '<td align="center" class="segundalinea">&nbsp;</td>';
				}
				?>            
 			<td align="center" valign="middle"  class="segundalinea">
			  <?PHP  $etapa++;
					$consulta= "SELECT count(id_perfil)
								FROM `cat_perfil_seguimiento` 
								WHERE id_perfil=".$userLevel." AND id_seguimiento=".$etapa;
			
					$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$autorizado=0;
					if (mysql_num_rows($sql_datos)>0)
						$autorizado=mysql_result($sql_datos,0);
			  		
			  
			  		if ($datos['estado']==0 && $datos['id_validacion']<=1 && ($terminado==0) && ($autorizado>0)){ ?>
			  <input name="valida" type="button" class="btnAceptar" value="Autorizar" onClick="return validar('<?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?>')" />
			  <input name="invalida" type="button" class="btnCancelar" value="No procedente" onclick="invalidar(<?PHP echo $datos["id_solicitud"].",".$datos["anio"]; ?>)" />
              <?PHP  }else
                  		echo '<img src="../images/b_no.png" alt="NO tiene permido agregar seguimiento" />';
			  
			   ?>
              
              </td>
            
            

			  </tr>

 <?PHP 

	}	
?>
<?PHP 
	if (!$existe){

?>

			  <tr class="aviso">

		      

          <td align="center" colspan="15"> No 

            hay solicitudes para autorizar</font>            </td>
			  </tr>

<?PHP 

	}

	

	mysql_free_result($resultado);

	



?>
			  </table>

<br />

<div>

<div class="tituSec">Solicitudes de modificaciones</div>

<div>
  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
  	<thead>
        <tr class="tituTab">
          <th scope="col">Ficha</th>
          <th scope="col">Fecha</th>
          <th scope="col">Descripción</th>
          <th scope="col">Acciones</th>
        </tr>
  	</thead>
    <tbody>
    <?
	$cons=mysql_query("select * from solicitud where soledit=1 order by anio asc,id_solicitud asc",$conexion);
	$cnt=0;
	while($datos=mysql_fetch_array($cons)){
		$fechaI=date("d/m/Y",strtotime($datos["fecha_ingreso"]));
		echo "<tr class='regTab'><td>".$datos["id_solicitud"]."/".$datos["anio"]."</td><td>".$fechaI."</td><td>".$datos["propietario"]."<br />".$datos["nombre_proyecto"]."</td><td><img src=\"../images/b_search.png\" alt=\"Visualizar datos solicitud\" title=\"Ver solicitud\" width=\"18\" height=\"18\" border=\"0\" class=\"btnOld\" onClick=\"window.open('../reportes/formato_unico.php?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']."')\" /> <img src='../images/check.png' width='18' height='18' title='Autorizar modificación' class='btnOld' onclick='autmod(".$datos["anio"].",".$datos["id_solicitud"].")' /> <img src='../images/remove.png' width='18' height='18' title='Denegar modificación' class='btnOld' onclick='negmod(".$datos["anio"].",".$datos["id_solicitud"].")' /></td></tr>";
		$cnt++;
	}
	if($cnt==0)
		echo "<tr class='regTab'><td colspan='4'>No hay solicitudes por autorizar</td></tr>";
	?>
    </tbody>
  </table>
</div>

</div>

<table width="70%" align="center" >
  <tr>
    <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" class="btnOld" <?PHP  echo $central; ?> /></td>
  </tr>
</table>
 			
<input name="anio" type="hidden" />
	        <input name="id_solicitud" type="hidden" />
    		<input name="accion" type="hidden" />
            <input name="abreviatura" type="hidden" />
     </form>
</body>

</html>
<?php
mysql_close($conexion);
?>

