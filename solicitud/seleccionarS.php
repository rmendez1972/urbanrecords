<?PHP 

		$requiredUserLevel = array(1,2,3,4,5,6,7);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/config.php");

include ("../libreria/libreria.php");



// Conexion con la BD

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
<script src="../calendar/calendario.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/calendario.css">
<script>
function mostrarMapa(anio, idsol){
	parent.abrirShadowbox("solicitud/mapav2.php?anio="+anio+"&idsol="+idsol,"iframe","",640,480);	
}

function solmodif(anio, idsol){
	if(confirm("¿Desea enviar una solicitud de modificación al Secretario?")){
		var params=new Object();
		params.modulo="SOLICITUD";
		params.accion="SMOD";
		params.anio=anio;
		params.idsol=idsol;
		
		$.post("ControladorSol.php",params,function(datos){
			if(datos=="ok")
				parent.aviso("Solicitud enviada");
			else
				parent.error(datos);
		},"html");
	}
}
</script>
</head>

<body>
<div id="calendario" style="position:absolute; z-index:4"></div>

<?php

if (!isset($_POST['filtrar']) && (!isset($_GET['anio'])) && (!isset($_GET['id_solicitud']))){
?>
<SCRIPT language="javascript" src="../libreria/validacion_entero.js"></SCRIPT>
<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--
function activa(){
	if (document.formulario.tipo[0].checked){
		document.getElementById('restado').style.display='block';
		document.getElementById('restatus').style.display='block';
		//document.getElementById('ranio').style.display='block';
		document.getElementById('rmunicipio').style.display='block';
		document.getElementById('rtipo').style.display='block';
		document.getElementById('rnumero').style.display='none';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='none';
		document.formulario.estado.disabled=false; 
		document.formulario.estatus.disabled=false; 
		document.formulario.id_municipio.disabled=false; 
		document.formulario.id_proyecto.disabled=false; 
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	}
	if (document.formulario.tipo[1].checked || document.formulario.tipo[4].checked){
		document.getElementById('restado').style.display='none';
		document.getElementById('restatus').style.display='none';
		//document.getElementById('ranio').style.display='block';
		document.getElementById('rmunicipio').style.display='none';
		document.getElementById('rtipo').style.display='none';
		document.getElementById('rnumero').style.display='block';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='none';
		document.formulario.estado.disabled=true; 
		document.formulario.estatus.disabled=true; 
		document.formulario.id_municipio.disabled=true; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.act_fecha[1].checked=true;
		document.formulario.numero.disabled=false;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	
	}
	if (document.formulario.tipo[2].checked || document.formulario.tipo[3].checked){
		document.getElementById('restado').style.display='none';
		document.getElementById('restatus').style.display='none';
		//document.getElementById('ranio').style.display='block';
		document.getElementById('rmunicipio').style.display='none';
		document.getElementById('rtipo').style.display='none';
		document.getElementById('rnumero').style.display='none';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='block';
		document.formulario.estado.disabled=true; 
		document.formulario.estatus.disabled=true; 
		document.formulario.id_municipio.disabled=true; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.act_fecha[1].checked=true;
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=false;
	}
	if (document.formulario.tipo[4].checked){
		document.getElementById('rango').style.display='block';
		document.formulario.numero2.disabled=false;
	}
	if (document.formulario.act_fecha[0].checked){
		document.getElementById('fechas1').style.display='block';
		document.getElementById('fechas2').style.display='block';
		document.formulario.fecha1.disabled=false; 
		document.formulario.fecha2.disabled=false;
	}else{
		document.getElementById('fechas1').style.display='none';
		document.getElementById('fechas2').style.display='none';
		document.formulario.fecha1.disabled=true; 
		document.formulario.fecha2.disabled=true;
	}

}


correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;

/*

switch(valor){



	case 1:
		nombrecampo="Año\nSeleccione un año";
		cad=formulario.anio.value;
		if (cad=="")
			correcto=false;
		else
			correcto=true;

		break;

	case 2:
		if (document.formulario.numero.disabled)
			correcto=true;
		else{
			nombrecampo="Número\nAsigne sólo números";
			cad=formulario.numero.value;
			correcto=esnatural(cad);
		}
		break;

	case 3:
		if (document.formulario.numero2.disabled)
			correcto=true;
		else{
			nombrecampo="Número limite\nAsigne sólo números";
			cad=formulario.numero2.value;
			correcto=esnatural(cad);
		}
		break;

	case 4:
		if (document.formulario.descripcion.disabled)
			correcto=true;
		else{
			nombrecampo="Descripción\nAsigne información para búsqueda";
			cad=formulario.descripcion.value;
			if (cad=="")
				correcto=false;
			else
				correcto=true;
		}
		break;

	case -1:

		correcto2=true;

		for(var i=1;i<5;i++){

			correcto=ejecuta(i);

			correcto2=correcto2&&correcto;

		}// for

		break;

}// Switch

*/

document.forms.formulario.filtrar.value="OK";
		document.forms.formulario.submit( );

/*
if (valor!=-1){	

	if (!correcto){

		alert("Error en "+ nombrecampo);

	}// if	

}// if

else{

	if (correcto2){
		document.forms.formulario.filtrar.value="OK";
		document.forms.formulario.submit( );

	}	



}//else
*/

return correcto;

}//function

//-->

</script>
 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">
<br />
 <table width="95%" align="center" class="tablaDatos">
   <tr class="tituTab">
     <th colspan="2" align="left">Filtro de solicitudes
      <input name="filtrar" type="hidden" /></th>
    </tr>
   <tr style="display:none">
     <td align="left" class="segundalinea" >Reporte</td>
     <td align="left" class="segundalinea" ><select name="reporte" size="1">
       <option value="constancia.php" >Datos de las Fichas</option>
       <option value="ingresos.php" >Ingresos</option>
       <option value="constancias.php" >Constancias</option>
     </select></td>
   </tr>
   <tr  >
     <td rowspan="2" align="left" class="segundalinea" >Tipo reporte</td>
     <td align="left" class="segundalinea" ><label>
       <input type="radio" name="tipo" value="0" checked="checked" onclick="activa()" />
       General</label>
       <label>
         <input type="radio" name="tipo" value="1" onclick="activa()" />
         N&uacute;mero </label>
       <label>
         <input type="radio" name="tipo" value="2" onclick="activa()" />
         Propietario </label>
       <label>
         <input type="radio" name="tipo" value="3" onclick="activa()" />
         Proyecto </label>
       <label>
         <input type="radio" name="tipo" value="4" onclick="activa()" />
         Rango</label></td>
   </tr>
   <tr  >
     <td align="left" class="segundalinea" style="position:relative"><label>Periodo
       <input type="radio" name="act_fecha" value="0" checked="checked" onclick="activa()" />
       SI</label>
       <label>
         <input type="radio" name="act_fecha" value="0" onclick="activa()" />
         NO</label>
         
         <div id="fechast" style="position:absolute; display:table; right:50px; top:0px;">
         <div id="fechas1" style="float:left">
         Inicio
           <input name="fecha1" id="fecha1" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />
           <img src='../images/calendario.png' width="20" alt='calendario' name='calendario' class="btnOld" id="calendario3" onclick="calendarioF('calendario','fecha1',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" />
           </div>
           
           <div id="fechas2" style="float:left">
           Final
           <input name="fecha2" id="fecha2" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />
           <img src='../images/calendario.png' width="18" alt='calendario' name='calendario' class="btnOld" id="calendario" onclick="calendarioF('calendario','fecha2',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" />
           </div>
           
         </div>
         </td>
   </tr>
   <tr >
     <td align="left" class="segundalinea" >Año</td>
     <td align="left" class="segundalinea" ><select name="anio" size="1">
       <?PHP   $consulta= "select * from anios";


					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					

						echo '<option value="">Seleccione...</option>';

					while($anios= mysql_fetch_array($resultado)){
						if ($anios['anio']== date('Y'))	
							echo '<option value="'.$anios['anio'].'" selected>'.$anios['anio'].'</option>';
						else
							echo '<option value="'.$anios['anio'].'">'.$anios['anio'].'</option>';

					} //fin while

				?>
     </select></td>
    </tr>
   <tr >
     <td colspan="2" align="left" class="segundalinea" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr class="tablaDatos"  id="restado" >
         <td width="150" align="left" class="segundalinea" >Estado Solicitud</td>
         <td align="left" class="segundalinea" ><select name="estado" size="1">
           <option value="Todos">Todos...</option>
           <option value="0" selected="selected">En proceso</option>
           <option value="1">Bajas</option>
           <option value="2">Finalizados</option>
         </select></td>
       </tr>
       <tr class="tablaDatos"  id="restatus" >
         <td width="150" align="left" class="segundalinea" >Estado validaci&oacute;n</td>
         <td align="left" class="segundalinea" ><select name="estatus" size="1">
           <?PHP   	$consulta= "select * from cat_validacion";
					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="Todos">Todos...</option>';

					while($data_edos= mysql_fetch_array($resultado)){
							echo '<option value="'.$data_edos['id_validacion'].'">'.$data_edos['descripcion'].'</option>';

					} //fin while

				?>
         </select></td>
       </tr>
       <tr class="tablaDatos"  id="rmunicipio" >
         <td width="150" align="left" class="segundalinea" >Municipio</td>
         <td align="left" class="segundalinea" ><select name="id_municipio" size="1">
           <?PHP   $consulta= "select * from cat_municipios";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="Todos">Todos...</option>';

					while($municipios= mysql_fetch_array($resultado)){
						echo '<option value="'.$municipios['id_municipio'].'">'.$municipios['descripcion'].'</option>';

					} //fin while

				?>
         </select></td>
       </tr>
       <tr class="tablaDatos" id="rtipo">
         <td width="150" class="segundalinea">Tipo de proyecto</td>
         <td class="segundalinea"><select name="id_proyecto" size="1">
           <?PHP   $consulta= "select * from cat_tipo_proy";


					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="Todos">Todos...</option>';

					while($tipos= mysql_fetch_array($resultado)){
							echo '<option value="'.$tipos['id_tipo'].'">'.$tipos['descripcion'].'</option>';

					} //fin while

				?>
         </select></td>
       </tr>
       <tr class="tablaDatos"  id="rnumero" style="display:none">
         <td width="150" class="segundalinea">Número</td>
         <td  class="segundalinea"><input name="numero" type="text" size="20" maxlength="20" disabled="disabled" />
       <div id="rango" style="display:none"> al
         <input name="numero2" type="text" size="20" maxlength="20" disabled="disabled"  />
       </div></td>
       </tr>
       <tr class="tablaDatos" id="rdescripcion" style="display:none">
         <td width="150" class="segundalinea">Descripci&oacute;n</td>
         <td  class="segundalinea"><input name="descripcion" type="text" size="60" disabled="disabled" /></td>
       </tr>
     </table></td>
    </tr>
   <tr >
     <td colspan="2" align="left" class="segundalinea" >&nbsp;</td>
   </tr>
 </table>
 <br />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><img src="../images/aceptar1.png" alt="Generar Información" class="btnOld"  onclick="ejecuta(-1);" /></td>
     <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" <?PHP  echo $central; ?> /></td>
   </tr>
 </table>
 <br />
</form>


<?PHP 
}
if ((isset($_POST['filtrar']) && $_POST['filtrar']=="OK") || isset($_GET["backing"]) || (isset($_GET['anio']) && isset($_GET['id_solicitud']))){
// Despliega  

		$consulta= "SELECT * 
					FROM`solicitud` s, cat_tipo_proy tp 
					WHERE id_tipo=id_proyecto1 ";

		if ((isset($_GET['anio']) && isset($_GET['id_solicitud']))){
		
			if (isset($_GET['anio']))
				$consulta.= " AND anio=".$_GET['anio'];
			if (isset($_GET['id_solicitud']))
				$consulta.= " AND s.id_solicitud=".$_GET['id_solicitud'];
		}else{
			if (isset($_POST['anio']))
				$consulta.= " AND anio=".$_POST['anio'];

			if (isset($_POST['estado']) && $_POST['estado']!="Todos")
				$consulta.= " AND s.estado=".$_POST['estado'];

			if (isset($_POST['estatus']) && $_POST['estatus']!="Todos")
				$consulta.= " AND s.id_validacion=".$_POST['estatus'];

			if (isset($_POST['id_municipio']) && $_POST['id_municipio']!="Todos")
				$consulta.= " AND s.id_municipio=".$_POST['id_municipio'];
			if (isset($_POST['id_proyecto']) && $_POST['id_proyecto']!="Todos")
				$consulta.= " AND s.id_proyecto1=".$_POST['id_proyecto'];
	
			if (isset($_POST['fecha1']) && isset($_POST['fecha2'])){
				$consulta.= " AND (s.fecha_ingreso BETWEEN '".$_POST['fecha1']."' AND '".$_POST['fecha2']."')";
			}

			if (isset($_POST['tipo']) && $_POST['tipo']=="1")
				$consulta.= " AND s.id_solicitud=".$_POST['numero'];

			if (isset($_POST['tipo']) && $_POST['tipo']=="2")
				$consulta.= " AND s.propietario LIKE '%".$_POST['descripcion']."%'";

			if (isset($_POST['tipo']) && $_POST['tipo']=="3")
				$consulta.= " AND s.nombre_proyecto LIKE '%".$_POST['descripcion']."%'";

			if (isset($_POST['tipo']) && $_POST['tipo']=="4")
				$consulta.= " AND s.id_solicitud BETWEEN ".$_POST['numero']." AND ".$_POST['numero2'];
		}

		if ($userLevel==3){
			$consulta.= '
				AND concat( anio,"/", id_solicitud ) 
				IN (
					SELECT concat( anio,"/", id_solicitud ) 
					FROM`solicitud_revisor` 
					WHERE `id_revisor` = "'.$login.'"
				)
			';
		}
		$consulta.= " ORDER BY s.id_solicitud";
		
		if(isset($_GET["backing"]) && isset($_SESSION["coSolicitud"]))
			$consulta=$_SESSION["coSolicitud"];
		
		$_SESSION["coSolicitud"]=$consulta;
//echo $consulta;				
		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	$existe= mysql_num_rows($resultado);

?>
<div class="tituSec">Listado de solicitudes</div>
<div style="text-align:center">Presione sobre el icono para visualizar, modificar, cancelar. <br />
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

				?>
</div>
<table width="95%" align="center" class="tablaDatos">
  
  <tr class="tituTab">
    <td width="15%" rowspan="2" align="center" class="primeralinea" >Operaci&oacute;n</td>
    <td width="8%" rowspan="2" align="center" class="primeralinea" >Ficha</td>
    <td width="9%" rowspan="2" align="center" class="primeralinea" >Fecha</td>
    <td width="33%"  rowspan="2" align="center" class="primeralinea" >Descripci&oacute;n</td>
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
    <td height="26" align="center" class="primeralinea" >Agregar</td>
  </tr>
  <?PHP 
	while ($datos= mysql_fetch_array($resultado)){
?>
  <tr class="<?PHP echo $datos["improcedente"]==1?"improcedente":""; ?>">
    <td align="center"  class="segundalinea"><img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" title="Ver datos de solicitud" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']."&abreviatura=".$datos['abreviatura']; ?>')" />
      <img src="../images/mapa.png" alt="" width="20" height="20" class="btnOld" title="Ver en mapa" onclick="mostrarMapa(<? echo $datos["anio"]; ?>,<? echo $datos["id_solicitud"]; ?>)" />
      <?PHP  
		  if ($userLevel<=2){ 
		  
			  if ($datos['estado']==0){ 
		  ?>
      <?
	  if($datos["editable"]==1){
	  ?>
      <img src="../images/b_edit.png" title="Modificar datos de solicitud" alt="Modificar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="location.replace('editar.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']; ?>')" />
      <?
	  }
	  else{
		?>
        <img src="../images/editar.png" title="Enviar solicitud de modificación" alt="Enviar solicitud de modificación" width="16" height="16" border="0" class="btnOld" onclick="solmodif(<?PHP  echo $datos['anio'].",".$datos['id_solicitud']; ?>)" />
        <?  
	  }
      ?> <img src="../images/b_drop.png" title="Cancelar solicitud" alt="Cancelar solicitud" width="16" height="16" border="0" class="btnOld" onclick="location.replace('solicitud_drop.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']; ?>')"   />
      <?PHP  }
		  if ($datos['estado']==1){ 
		  
		  ?>
      <img src="../images/restaurar.png" alt="Restaurar solicitud" title="Restaurar solicitud" width="16" height="16" border="0" class="btnOld" onclick="location.replace('solicitud_asignar.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']; ?>')"   />
      <?PHP  } }?></td>
    <td align="center"  class="segundalinea"><?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?></td>
    <td align="center"  class="segundalinea"><?PHP  echo date("d/m/Y",strtotime($datos["fecha_ingreso"])); ?></td>
    <td align="left"  class="segundalinea"><?PHP  echo $datos["propietario"]; ?>
      <br />
      <?PHP  echo $datos["nombre_proyecto"]; ?></td>
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
										<form name="modificar" method="post" action="seguimiento_mod.php">
							 <td align="center" class="segundalinea">
							 <img src="../images/b_search.png" class="btnOld" title="Ver seguimiento" alt="Visualizar '.$data_seg['fecha'].'" width="16" height="16" border="0"  onclick="window.open(\'seguimiento_ver.php?anio='.$datos['anio'].'&id_solicitud='.$datos["id_solicitud"].'&id_seguimiento='.$dato_seg['id_seguimiento'].'&abreviatura='.$datos['abreviatura'].'\',\'miwin\',\'width=600,height=400,scrollbars=yes\')" />';
							 
							 
							$consulta= "SELECT id_perfil
										FROM `cat_perfil_seguimiento` 
										WHERE id_perfil=".$userLevel." AND id_seguimiento=".$dato_seg['id_seguimiento'];
					
							$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
							if (mysql_num_rows($sql_datos)>0 && $datos['estado']==0 && ($datos["editable"]==1 || $dato_seg['id_seguimiento']>4))
							 	echo '
										  	<input type="image" src="../images/b_edit.png" title="Editar seguimiento" style="border:0 hidden " alt="Editar '.$dato_seg['descripcion'].'" />
											<input name="anio" type="hidden" value="'.$datos['anio'].'" />
											<input name="id_solicitud" type="hidden" value="'.$datos['id_solicitud'].'" />
											<input name="abreviatura" type="hidden" value="'.$datos['abreviatura'].'" />
										<input name="etapa" type="hidden" value="'.$dato_seg['id_seguimiento'].'" />
								';
								
							if (mysql_num_rows($sql_datos)>0 && $numero_seg	== $dato_seg['id_seguimiento'] && $datos['estado']==0 && $datos["editable"]==1)
							 	echo '
										  	<input type="image" src="../images/b_drop.png" style="border:0 hidden " alt="Eliminar '.$dato_seg['descripcion'].'" onclick="this.form.action=\'seguimiento_drop.php\'" title="Eliminar seguimiento" />
											<input name="anio" type="hidden" value="'.$datos['anio'].'" />
											<input name="id_solicitud" type="hidden" value="'.$datos['id_solicitud'].'" />
											<input name="abreviatura" type="hidden" value="'.$datos['abreviatura'].'" />
										<input name="etapa" type="hidden" value="'.$dato_seg['id_seguimiento'].'" />
										
								';
														 
							echo '</td>
								</form>
							';						

							if ($dato_seg['id_seguimiento']==$etapas) $terminado++;
							$etapa=$dato_seg['id_seguimiento'];
						}
					}else
						echo '<td align="center" class="segundalinea">&nbsp;</td>';
				}
				?>
    
      <td align="center" valign="middle"  class="segundalinea">
	  	<form action="seguimiento_add.php" method="post" name="agregar_seguimiento" id="agregar_seguimiento">
	  <?PHP 
					$etapa++;
					$consulta= "SELECT count(id_perfil)
								FROM `cat_perfil_seguimiento` 
								WHERE id_perfil=".$userLevel." AND id_seguimiento=".$etapa;
			
					$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$autorizado=0;
					if (mysql_num_rows($sql_datos)>0)
						$autorizado=mysql_result($sql_datos,0);
			  		
			  
			  		if ($datos['estado']==0 && $datos['id_validacion']<=1 && ($terminado==0) && ($autorizado>0)){ 
					
						$consulta= "SELECT count(id_solicitud)
									FROM `solicitud_autoriza2` 
									WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud'];
						//echo $consulta;
						$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
						$autoriza2=0;
						if (mysql_num_rows($sql_datos)>0)
							$autoriza2=mysql_result($sql_datos,0);
						
						if($datos["improcedente"]==1)
								echo "Improcedente";
						else if ($etapa>=5 && ($autoriza2 == 0)){
							echo "Requiere Autorizaci&oacute;n del Secretario";
						}else{
					?>
        <input type="image" class="btnOld" style="border:0 hidden " src="../images/abook_add.gif" title="Agregar seguimiento" alt="Agregar seguimiento" />
        <?PHP }  }else
                  		echo '<img src="../images/b_no.png" alt="NO tiene permido agregar seguimiento" />';
			  
			   ?>
               <input name="anio" type="hidden" value="<?PHP  echo $datos['anio'] ?>" />
      <input name="id_solicitud" type="hidden" value="<?PHP  echo $datos['id_solicitud'] ?>" />
      <input name="abreviatura" type="hidden" value="<?PHP  echo $datos['abreviatura'] ?>" />
    </form>
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
      
      hay solicitudes con esas caracter&iacute;sticas</font></td>
  </tr>
  <?PHP 

	}

	

	mysql_free_result($resultado);

	mysql_close($conexion);



?>
</table>
<table width="70%" align="center" >
  <tr>
    <td align="center"><img src="../images/buscar1.png" alt="Realizar nueva búsqueda" class="btnOld" onclick="location.replace('<?PHP  echo $_SERVER['PHP_SELF'] ?>')" /></td>
    <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" class="btnOld" <?PHP  echo $central; ?> /></td>
  </tr>
</table>
 <?PHP  } ?>

</body>
</html>

