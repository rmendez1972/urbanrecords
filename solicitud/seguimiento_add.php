<?PHP

	$requiredUserLevel = array(1,2,3,6);

	$cfgProgDir = '../';


		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
	
	mysql_query("set names 'utf8'",$conexion);
	
include ("../libreria/ConexionPDO.php");
Conexion::init(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/calendario.css">
<script src="../calendar/calendario.js"></script>
</head>

<body>
<?php

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion);

		$consulta= "insert into seguimiento values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$_POST['id_seguimiento'].",'".$_POST['fecha']."','".$_POST['observaciones']."','".$login."',NOW())";

		mysql_query($consulta,$conexion);

		if ( $_POST['etapa']==0 && isset($_POST['id_revisor']) && ($_POST['id_revisor']!="")){

			$consulta= "insert into solicitud_revisor values (".$_POST['anio'].",".$_POST['id_solicitud'].",'".$_POST['id_revisor']."','".$_POST['fecha']."','',0,'".$login."',NOW())";
	
			mysql_query($consulta,$conexion);
		}
		if ($_POST['etapa']==1){
			$consulta= "UPDATE solicitud SET fracciones='".$_POST['fracciones']."',num_viviendas='".$_POST['viviendas']."', superficie='".$_POST['superficie']."', descripcion='".$_POST['descripcionpr']."'  WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion);

			if (strpos ($_POST['abreviatura'],"FRACC") !== false  || strpos ($_POST['abreviatura'],"CON") !== false  ){
				if (!isset($_POST['tipo_con'])) $_POST['tipo_con']=0;
				if (!isset($_POST['niveles'])) $_POST['niveles']=0;
				if (!isset($_POST['numxnivel'])) $_POST['numxnivel']=0;
				if (!isset($_POST['vivxcon'])) $_POST['vivxcon']=0;
				if (!isset($_POST['tipo_viv'])) $_POST['tipo_viv']=0;
				if (!isset($_POST['no_cajones'])) $_POST['no_cajones']=0;
				$consulta= "insert into datos_vivienda values (".$_POST['anio'].",".$_POST['id_solicitud'].",'".$_POST['tipo_con']."','".$_POST['niveles']."','".$_POST['numxnivel']."','".$_POST['vivxcon']."','".$_POST['tipo_viv']."','".$_POST['no_cajones']."','".$login."',NOW())";
				//echo $consulta."<br>";
				mysql_query($consulta,$conexion);
			}
			
			$idsol=$_POST["id_solicitud"];
			mysql_query("delete from ubicacion where anio='".$_POST["anio"]."' and id_solicitud='".$idsol."'",$conexion);
			
			$ubicaciones=$_POST["ubics"];
			$cont=0;
			$i=1;
			while($cont<$ubicaciones){
				if(isset($_POST["lat".$i])){
					$cont++;
					
					$lat=$_POST["lat".$i];
					$lon=$_POST["lon".$i];
					
					mysql_query("insert into ubicacion values (".$_POST["anio"].",'".$idsol."','".$lat."','".$lon."','".($cont)."')",$conexion);
				}
				$i++;
			}
		}
		if ($_POST['etapa']==2){
		
			$consulta= "UPDATE solicitud SET id_validacion='".$_POST['id_validacion']."' WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion);

			$consulta= "insert into solicitud_validacion values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$_POST['id_validacion'].",'".$_POST['fecha']."','".$_POST['observaciones']."','".$login."',NOW())";
		
			mysql_query($consulta,$conexion);
			
		}
		if ($_POST['etapa']==3){
			$repetidos="";
			$consulta= "UPDATE solicitud_revisor SET  estado=1  WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
		}

		if ($_POST['etapa']==4){
	   			$consulta= "select concat( anio ,'-', id_solicitud ,'-', id_solicitante ) AS email,`nombre`, `correo` 
				FROM solicitantes s
				WHERE id_solicitud=".$_POST['id_solicitud']." AND anio=".$_POST['anio'];

				$sql_solicitante=mysql_query($consulta,$conexion);
				while($solicitantes= mysql_fetch_array($sql_solicitante)){
					$aviso="aviso".$solicitantes['email'];
					if (isset($$aviso)){
						$asunto = "Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']." con Orden de Pago Autorizada"; 
						$cuerpo = ' 
						<html> 
						<head> 
						   <title>'."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].'</title> 
						</head> 
						<body> 
						<h1>Orden de Pago Autorizada</h1> 
						<p> 
						 <b>Estimado '.$solicitantes['nombre'].': </b></p>
						<p>
						  Por este medio se le comunica con fecha '.$_POST['fecha'].' que la '."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].' promovida por Ud. para su Constancia de Compatibilidad, ha sido autorizada la orden de pago.<br>
						  Para solicitar mayores informes:<br>
						  Gobierno del Estado de Quintana Roo<br>
						  Secretaría de Desarrollo Urbano<br>
						  Subsecretaría de Desarrollo Urbano y Vivienda<br>
						  DIRECCIÓN DE ADMINISTRACIÓN URBANA<br>
						  Av. Efraín Aguilar No. 418 entre Dimas Sansores y Retorno 3, Chetumal, Quintana Roo<br>
						  Tel. (01-983 83 24108, 1293317, 1293318 Ext. 137)  ad_urbana@qroo.gob.mx<br><br>
						Sin otro particular, le envio un cordial saludo. 
						</p>
						</body> 
						</html> 
						'; 
						//$solicitantes['correo']
						//mail("sistemas_sedu@qroo.gob.mx",$asunto,$cuerpo,$headers);
						
						mail($solicitantes['correo'],$asunto,$cuerpo,$headers);
					}
				}
				mysql_free_result($sql_solicitante);	
		}

		if ($_POST['etapa']==6){
		
			$consulta= "UPDATE solicitud SET estado='2' WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion);
		}

		mysql_query("COMMIT",$conexion);


		if (isset($_POST['id_revisor']) && ($_POST['id_revisor']!="") && $_POST['etapa']==0){
		
			$consulta= "SELECT nombre,email FROM cat_usuario WHERE login='".$_POST['id_revisor']."'";
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			$usuario= mysql_fetch_array($sql_user);
			
			if (!empty($usuario['email'])){			
				$asunto = "Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']; 
				$cuerpo = ' 
				<html> 
				<head> 
				   <title>'."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].'</title> 
				</head> 
				<body> 
				<h1>Asignaci&oacute;n de ficha para revisi&oacute;n</h1> 
				<p> 
				 <b>Estimado '.$usuario['nombre'].': </b></p>
				<p>
				  Por este medio se le turna con fecha '.$_POST['fecha'].' la '."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].' para su revisi&oacute;n.<br>
				  Con las siguientes observaciones:<br>
				  '.$_POST['observaciones'].'<br>
				Sin otro particular, le envio un cordial saludo. 
				</p>
				</body> 
				</html> 
				'; 
				
				mail($usuario['email'],$asunto,$cuerpo,$headers);
			}
		}
		if ($_POST['etapa']==1){
				$asunto = "Validar Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']; 
				$cuerpo = ' 
				<html> 
				<head> 
				   <title>'."Validar Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].'</title> 
				</head> 
				<body> 
				<h1>Ficha revisada lista para validaci&oacute;n</h1> 
				<p> 
				 <b>Direcci&oacute;n de Administraci&oacute;n Urbana: </b></p>
				<p>
				  Por este medio se le turna con fecha '.$_POST['fecha'].' la '."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].' para su validaci&oacute;n. <br>
				  Una vez que ha sido capturada la informaci&oacute;n correspondiente de la revisi&oacute;n.
				  <br>				  
				Sin otro particular, le envio un cordial saludo. 
				</p>
				</body> 
				</html> 
				'; 
				//"ad_urbana@qroo.gob.mx"
				//mail("sistemas_sedu@qroo.gob.mx",$asunto,$cuerpo,$headers);
				
				
				mail("ad_urbana@qroo.gob.mx",$asunto,$cuerpo,$headers);
		
		}

		
		$destino="seleccionarS.php?backing=1&anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud'];
		if ($_POST['etapa']==1)
			$destino="../revisor/formato.php?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura'];
		if ($_POST['etapa']==4)
			$destino="constancias.php?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura'];
?>

<script language="javascript">
	<? if (isset($repetidos) && $repetidos!=""){ 
		echo 'alert("Las constancias con número '.$repetidos.' están repetidas.\nNo se ingresó las constancias con estos números, favor de verificar");';
	 } ?>
	window.location='<? echo $destino ?>';

</script>
<?

}// si ya existe la variable nombre. Envio una medida para guardar

//Cerrar la conexion y liberar memoria

//	mysql_close($conexion);







?>


<?php

// Si no ha sido enviado algo
if (isset($_POST['id_solicitud']) && isset($_POST['anio'])){
	

		$consulta= "SELECT id_seguimiento 
					FROM `seguimiento` 
					WHERE anio=".$_POST['anio']." AND id_solicitud='".$_POST['id_solicitud']."'";
		$consulta.= " ORDER BY id_seguimiento DESC limit 1"; 

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realizó correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$etapa=0;
		if (mysql_num_rows($sql_datos)>0)
			$etapa=mysql_result($sql_datos,0);

		$consulta= "SELECT count(id_solicitud)
					FROM `solicitud_autoriza2` 
					WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
		//echo $consulta;
		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$autoriza2=0;
		if (mysql_num_rows($sql_datos)>0)
			$autoriza2=mysql_result($sql_datos,0);
		
		if ($etapa>=4 && ($autoriza2 == 0)){
			$destino="seleccionarS.php?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura'];

?>
<script language="javascript">
		alert("Para continuar con el seguimiento.\nRequiere Autorización del Srio.");
	  
	window.location='<? echo $destino ?>';

</script>
<?php			
		}
?>
<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript">

var cuenta=0;
var totalNodos=0;
function agregarUbicacion(){
	totalNodos++;
	cuenta++;
	
	document.getElementById("ubics").value=totalNodos;
	var nuevo=document.createElement("div");
	nuevo.id="latlon"+cuenta;
	nuevo.innerHTML="X <input type='text' name='lat"+cuenta+"' id='lat"+cuenta+"' size='13' /> Y <input type='text' name='lon"+cuenta+"' id='lon"+cuenta+"' /> <img src='../images/remove.png' class='btnOld' title='Eliminar ubicación' width='22' height='22' onclick=eliminarUbicacion('latlon"+cuenta+"') />";
	
	document.getElementById("ubicaciones").appendChild(nuevo);
	$("#"+nuevo.id).hide();
	$("#"+nuevo.id).fadeIn();
}
function eliminarUbicacion(id){
	document.getElementById("ubicaciones").removeChild(document.getElementById(id));
	totalNodos--;
	document.getElementById("ubics").value=totalNodos;
}

function validar(etapa){
	var pasa=true;
	
	if(etapa==0){
		if($("#revisor").val().length>0)
			submitForm();
		else
			pasa=false;
	}
	if(etapa==1)
		submitForm();
	if(etapa==2)
		submitForm();
	if(etapa==3)
		submitForm();
	if(etapa==4)
		submitForm();
	if(etapa==5)
		submitForm();
	if(etapa==6)
		submitForm();
	
	if(!pasa)
		parent.error("Llene todos los campos marcados con *");
}

function submitForm(){
	document.forms.formulario.ingresar.value="OK";
	document.forms.formulario.submit( );
}
          <?PHP  if ($etapa==1){ ?>
function preguntas() 
    {   
	var cad1;
	cad1=document.formulario.tipo_con.options[document.formulario.tipo_con.selectedIndex].text;
	if (cad1=="VERTICAL"){
		//habilitar las preguntas
		document.getElementById('rniveles').style.display='block';
		document.getElementById('rnumxnivel').style.display='block';
	}else{
		//deshabilitar las preguntas
		document.getElementById('rniveles').style.display='none';
		document.getElementById('rnumxnivel').style.display='none';
	}
		  }
          <?PHP  } ?>
</script>
<div id="calendario" style="position:absolute"></div>
 <form name="formulario" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
   <br />
   <table width="95%" align="center" class="tablaDatos">
     <thead>
     <tr class="tituTab">
       <th colspan="2">Seguimiento de solicitud</th>
     </tr>
     </thead>
     <tbody>
     <tr >
       <td  class="segundalinea">Etapa</td>
       <td  class="segundalinea"><select name="id_seguimiento" size="1">
         <?  
			  	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

			  $consulta= "select * from cat_seguimiento WHERE id_seguimiento IN (SELECT id_seguimiento FROM cat_seguimiento_opc WHERE seleccion=$etapa)";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					while($tipos= mysql_fetch_array($resultado)){
						echo '<option value="'.$tipos['id_seguimiento'].'">'.$tipos['descripcion'].'</option>';

					} //fin while
					mysql_free_result($resultado);
				?>
       </select>
         <? echo $_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']; ?><img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP echo "?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura']; ?>')" /></td>
     </tr>
     <tr >
       <td class="segundalinea" > Fecha </td>
       <td class="segundalinea" >
       <input id="fecha" name="fecha" type="text" style="vertical-align:middle" size="15" maxlength="10" value="<? echo date('Y-m-d') ?>" readonly="readonly" />
         <img src='../images/calendario.png' class="btnOld" style="vertical-align:middle" onclick="calendarioF('calendario','fecha',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" /></td>
     </tr>
     <? 
		  if ($etapa==1){ 
		  

			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio,id_proyecto1 from solicitud WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);
		  
		  
		  ?>
     <tr >
       <td class="segundalinea">Generales</td>
       <td class="segundalinea" ><? echo $datos['nombre_proyecto'] ?><br />
         <? echo $datos['direccion'] ?></td>
     </tr>
     <tr >
       <td  class="segundalinea" > Superficie</td>
       <td class="segundalinea" ><input name="superficie" style="text-align:right" type="text" size="15" maxlength="10" value="<? echo $datos['superficie'] ?>"/>
         m<sup>2</sup></td>
     </tr>
     <tr >
       <td valign="top"  class="segundalinea" >Ubicación</td>
       <td class="segundalinea" ><div class="btnAceptar" title="Agregar ubicación" onclick="agregarUbicacion()">Agregar ubicación <img src="../images/agregar.png" width="18" height="18" /></div>
       <div id="ubicaciones" style="clear:both">
       <?
	   $cons=mysql_query("select * from ubicacion where anio='".$datos["anio"]."' and id_solicitud='".$datos["id_solicitud"]."' order by orden asc",$conexion);
	   $totubic=0;
	   while($res=mysql_fetch_array($cons)){
			$totubic++;
			
			echo "<div id='latlon".$totubic."'>X <input type='text' name='lat".$totubic."' id='lat".$totubic."' size='13' value='".$res["latitud"]."' /> Y <input type='text' name='lon".$totubic."' id='lon".$totubic."' size='13' value='".$res["longitud"]."' /> <img src='../images/remove.png' class='btnOld' title='Eliminar ubicación' width='22' height='22' onclick=eliminarUbicacion('latlon".$totubic."') /></div>";
	   }
	   ?>
       </div>
       <script>
	   <?
	   echo "cuenta=".$totubic."; 
	   totalNodos=".$totubic.";";
	   ?>
	   </script>
       <input name="ubics" type="hidden" id="ubics" value="<? echo $totubic; ?>" />
       </td>
     </tr>
     <?php
	 $tproy=$datos["id_proyecto1"];
	 //if($tproy>=13 && $tproy<=16){
	 ?>
     <tr >
       <td valign="top"  class="segundalinea" >Descripción / Resumen del proyecto</td>
       <td class="segundalinea" ><textarea name="descripcionpr" id="descripcionpr" cols="65" rows="4"></textarea></td>
     </tr>
     <?php
	 //}
	 ?>
     <tr >
       <td  class="segundalinea" > Fracciones</td>
       <td class="segundalinea" ><input name="fracciones" style="text-align:right" type="text" size="15" maxlength="10" value="<? echo $datos['fracciones'] ?>"/></td>
     </tr>
     <tr >
       <td  class="segundalinea" > Viviendas</td>
       <td class="segundalinea" ><input name="viviendas" type="text" style="text-align:right" size="15" maxlength="10" value="<? echo $datos['num_viviendas'] ?>" /></td>
     </tr>
     <?php          if (strpos ($_POST['abreviatura'],"CON")!== false  ){ 
?>
     <tr >
       <td  class="segundalinea" > Tipo de Condominio</td>
       <td class="segundalinea" ><select name="tipo_con" size="1" onchange="preguntas();">
         <?PHP   
			  	$consulta= "select * from cat_tipo_con";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($tipo_v= mysql_fetch_array($resultado)){
					echo '<option value="'.$tipo_v['id_tipo'].'">'.$tipo_v['descripcion'].'</option>';

				} //fin while
				mysql_free_result($resultado);
			?>
       </select></td>
     </tr>
     <tr style="display:none" id="rniveles" >
       <td  class="segundalinea" > N&uacute;mero de niveles</td>
       <td class="segundalinea" ><input name="niveles" style="text-align:right" type="text" size="15" maxlength="10" value="0" /></td>
     </tr>
     <tr style="display:none" id="rnumxnivel" >
       <td  class="segundalinea" > N&uacute;mero de viviendas por niveles</td>
       <td class="segundalinea" ><input name="numxnivel" style="text-align:right" type="text" size="15" maxlength="10" value="0"/></td>
     </tr>
     <tr >
       <td  class="segundalinea" > N&uacute;mero de viviendas por condominio</td>
       <td class="segundalinea" ><input name="vivxcon" style="text-align:right" type="text" size="15" maxlength="10" value="0"/></td>
     </tr>
     <?php } ?>
     <?php          if (strpos ($_POST['abreviatura'],"FRACC") !== false  || strpos ($_POST['abreviatura'],"CON") !== false  ){ 
?>
     <tr >
       <td  class="segundalinea" > Tipo de Vivienda</td>
       <td class="segundalinea" ><select name="tipo_viv" size="1">
         <?PHP   
			  	$consulta= "select * from cat_tipo_viv";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($tipo_v= mysql_fetch_array($resultado)){
					echo '<option value="'.$tipo_v['id_tipo'].'">'.$tipo_v['descripcion'].'</option>';

				} //fin while
				mysql_free_result($resultado);
			?>
       </select></td>
     </tr>
     <tr >
       <td  class="segundalinea" > No. de cajones de estacionamiento</td>
       <td class="segundalinea" ><input name="no_cajones" style="text-align:right" type="text" size="15" maxlength="10" value="0"/></td>
     </tr>
     <?php } ?>
     <? } ?>
     <? if ($etapa==2){ ?>
     <tr >
       <td class="segundalinea">Estado</td>
       <td class="segundalinea" ><select name="id_validacion" size="1">
         <?  
			  	$consulta= "select * from cat_validacion";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($tipo_v= mysql_fetch_array($resultado)){
					echo '<option value="'.$tipo_v['id_validacion'].'">'.$tipo_v['descripcion'].'</option>';

				} //fin while
				mysql_free_result($resultado);
			?>
       </select></td>
     </tr>
     <? } ?>
     
     <? if ($etapa==4 || $etapa==5 || $etapa==6){ ?>
     <tr >
       <td class="segundalinea">Datos de Solicitantes</td>
       <td class="segundalinea" ><? 
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
					if ($solicitantes['correo']!="" && $etapa==4)
						echo ' Notificar por correo <input name="aviso'.$solicitantes['email'].'" type="checkbox" value="1" />';
				}
				mysql_free_result($sql_solicitante);	

			 ?></td>
     </tr>
     <? } ?>
     <? if ($etapa==5 || $etapa==6 || $etapa==7){ ?>
     <tr >
       <td class="segundalinea">Informaci&oacute;n de pagos</td>
       <td class="segundalinea" ><?  
			  	$consulta= "select * from constancias_pagos WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				
				while($pagos= mysql_fetch_array($resultado)){
					$cons=mysql_query("select descripcion from cat_tipo_pago where id_tipo_pago=".$pagos["id_tipo_pago"],$conexion);
					$row=mysql_fetch_array($cons);
					
					echo "Orden de pago de ".$row[0]."<br/>";
					echo "Total: $ ".number_format($pagos['derechos'],2,'.',',');
					echo "<br>";
				}
				mysql_free_result($resultado);
			?></td>
     </tr>
     <? } ?>
     <tr >
       <td width="20%"  class="segundalinea"><? if ($etapa==1) 
						echo "Pendientes";
					else
						echo "Observaciones";
				?></td>
       <td width="80%" class="segundalinea" ><textarea name="observaciones" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"></textarea></td>
     </tr>
     <? if ($etapa==0){ ?>
     <tr >
       <td class="segundalinea">Revisor *</td>
       <td class="segundalinea" ><input name="revisor" id="revisor"  type="text" style="vertical-align:middle" size="70" readonly="readonly" />
         <input name="id_revisor" type="hidden" />
         <img src="../images/elegir1.png" alt="Elegir y asignar revisor" class="btnOld" style="vertical-align:middle" onclick="window.open('../revisor/listado.php<? echo "?anio=".$_POST['anio'] ?>','miwin','width=600,height=400,scrollbars=yes')" /></td>
     </tr>
     <? } ?>
     </tbody>
   </table>
   <input name="ingresar" type="hidden" />
   <input name="anio" type="hidden" value="<? echo $_POST['anio'] ?>" />
   <input name="id_solicitud" type="hidden" value="<? echo $_POST['id_solicitud'] ?>" />
   <input name="abreviatura" type="hidden" value="<? echo $_POST['abreviatura'] ?>" />
   <input name="etapa" type="hidden" value="<? echo $etapa; ?>" />
   <br />
   <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
       <td align="center"><img src="../images/aceptar1.png" alt="Guardar seguimiento" class="btnOld"  onclick="validar(<?php echo $etapa; ?>)" /></td>
       <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_POST['id_solicitud']."&anio=".$_POST['anio']; ?>'" /></td>
    </tr>
   </table>

</form>

<?php



}

?>

</body>

</html>

