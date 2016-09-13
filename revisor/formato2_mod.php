<?PHP 

		$requiredUserLevel = array(1,3);

		$cfgProgDir =  '../';

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
</head>

<body>

<?PHP 

// Si no ha sido enviado algo

if (isset($_GET['anio']) && isset($_GET['id_solicitud']) ){

			  $consulta= "select id_proyecto1, nombre_proyecto, direccion, fracciones, num_viviendas, superficie, id_solicitud, anio, propietario from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);


?>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function activa(visualizar){
	if (visualizar=='OK'){
		document.getElementById('desglose').style.display='block';
	}else{
		document.getElementById('desglose').style.display='none';
	}
	alert (document.forms.formulario.visualizar.value);
}


correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){

<?PHP  
		$consulta='SELECT id_pregunta, CONCAT( LEFT( p.descripcion, 15) ,"...") AS pregunta
FROM `cat_preguntas_formato2` p';
		$sql_pregv=mysql_query($consulta,$conexion);
		$cont=1;
		while ($data=mysql_fetch_array($sql_pregv)){
			echo '			
			case '.$cont++.':
				nombrecampo="'."Proyecto. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.proyecto'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			case '.$cont++.':
				nombrecampo="'."PDU. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.pdu'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			case '.$cont++.':
				nombrecampo="'."Cumple. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.cumple'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			case '.$cont++.':
				nombrecampo="'."Diferencia. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.diferencia'.$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			';
				}
		if ($datos['id_proyecto1']==1 || ($datos['id_proyecto1']>=5 && $datos['id_proyecto1']<= 10)){
		$consulta='SELECT id_area, CONCAT( LEFT( p.descripcion, 15) ,"...") AS pregunta
FROM `cat_desglose_areas` p';
		$sql_pregv=mysql_query($consulta,$conexion);
		while ($data=mysql_fetch_array($sql_pregv)){
			echo '			
			case '.$cont++.':
				nombrecampo="'."Superficie. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.superficie'.$data['id_area'].'.value;
				correcto=esnumerocondecimal(cad);
		
				break;
			case '.$cont++.':
				nombrecampo="'."Observaciones. ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.observaciones'.$data['id_area'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			';
		}
		}
				
?>

	case -1:

		correcto2=true;

		for(var i=1;i<<?PHP  echo $cont; ?>;i++){
		
			correcto=ejecuta(i);

			correcto2=correcto2&&correcto;

		}// for

		break;

}// Switch





if (valor!=-1){	

	if (!correcto){

		alert("Error en "+ nombrecampo);

	}// if	

}// if

else{

	if (correcto2){
		document.forms.formulario.ingresar.value="OK";
<?
		if ($datos['id_proyecto1']==1 || ($datos['id_proyecto1']>=5 && $datos['id_proyecto1']<= 10))
			echo '
		document.forms.formulario.visualizar.value="OK";
			';
	?>
		
		document.forms.formulario.submit( );
	}	



}//else



return correcto;

}//function



//-->

</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">
 <div class="tituSec">Formato Tabla</div>

 <table width="80%" class="tablaDatos" align="center">
   <tr >
     <td colspan="4" align="center"  class="tituTab">Proyecto</td>
    </tr>
   <tr >
     <td align="center"  class="segundalinea"> Nombre Proyecto</td>
     <td colspan="3"  class="segundalinea"><?PHP  echo $datos['nombre_proyecto']; ?></td>
   </tr>
   <tr >
     <td align="center"  class="segundalinea">Ubicaci&oacute;n</td>
     <td colspan="3"  class="segundalinea"><?PHP  echo $datos['direccion']; ?></td>
   </tr>
   <tr >
     <td align="center"  class="segundalinea">Superficie</td>
     <td colspan="3"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>
       m<sup>2</sup></td>
   </tr>
   <tr >
     <td align="center"  class="segundalinea">Propietario</td>
     <td colspan="3"  class="segundalinea"><?PHP  echo $datos['propietario']; ?></td>
   </tr>
 </table>
 <br />
 <table width="95%" align="center" class="tablaDatos" > 
                  <tr class="tituTab">
                    <td height="28" colspan="5" align="center"  class="primeralinea">Generales</td>
                  </tr>
                  <tr > 
              <td height="28" align="center"  class="primeralinea">CONCEPTO</td>
              <td align="center"  class="primeralinea">PROYECTO</td>
              <td align="center"  class="primeralinea">PDU</td>
              <td align="center"  class="primeralinea">CUMPLE</td>
              <td align="center"  class="primeralinea">DIF.</td>
          </tr>

<?PHP  
				$consulta= "select * from cat_preguntas_formato2";
				$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($pregunta= mysql_fetch_array($sql_preg)){
					$consulta= "select proyecto,pdu,cumple,diferencia from formato2 WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud']." AND id_pregunta=".$pregunta['id_pregunta'];
					$sql_resp=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$dato_resp=mysql_fetch_array($sql_resp);
					mysql_free_result($sql_resp);
?>
        <tr >
            <td class="segundalinea"><?PHP  echo $pregunta['descripcion'] ?></td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "proyecto".$pregunta['id_pregunta']; ?>" cols="20" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['proyecto'] ?></textarea>            </td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "pdu".$pregunta['id_pregunta']; ?>" cols="20" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['pdu'] ?></textarea>            </td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "cumple".$pregunta['id_pregunta']; ?>" cols="20" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['cumple'] ?></textarea>            </td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "diferencia".$pregunta['id_pregunta']; ?>" cols="20" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  echo $dato_resp['diferencia'] ?></textarea>            </td>
          </tr>
<?PHP 					} //fin while
					mysql_free_result($sql_preg);

?>
            
          </table>
 <div id="desglose" style="display:none">
 <div class="tituSec"><br />
   DESGLOSE DE ÁREAS (USO DE SUELO)</div>
   <table width="95%" align="center" class="tablaDatos"  >
     <tr class="tituTab">
       <td height="28" align="center"  class="primeralinea">ÁREAS</td>
       <td align="center"  class="primeralinea">SUPERFICIE M<sup>2</sup></td>
       <td align="center"  class="primeralinea">OBSERVACIONES SEG&Uacute;N NORMA</td>
      </tr>
     <?PHP  
				$consulta= "select * from cat_desglose_areas";
				$sql_area=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($areas= mysql_fetch_array($sql_area)){
					$consulta= "select * from formato2_desglose  WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud']." AND id_area=".$areas['id_area'];
					$sql_area_resp=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					$data_resp=mysql_fetch_array($sql_area_resp);
					mysql_free_result($sql_area_resp);
						
?>
     <tr >
       <td class="segundalinea"><?PHP  echo $areas['descripcion'] ?></td>
       <td class="segundalinea" ><input name="<?PHP  echo "superficie".$areas['id_area']; ?>" style="text-align:right" type="text" size="25" maxlength="20" value="<?php	echo $data_resp['superficie']  ?>"/></td>
       <td class="segundalinea" ><textarea name="<?PHP  echo "observaciones".$areas['id_area']; ?>" cols="60" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><?php	echo $data_resp['observaciones']  ?>
     </textarea></td>
      </tr>
     <?PHP 				} //fin while
					mysql_free_result($sql_area);

					$consulta= "select observaciones from formato_observaciones WHERE anio=".$datos['anio']." AND id_solicitud=".$datos['id_solicitud']." AND formato=2";
					$sql_resp=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

?>
   </table>
 </div>
<?
		if ($datos['id_proyecto1']==1 || ($datos['id_proyecto1']>=5 && $datos['id_proyecto1']<= 10))
			echo '
			<script language="javascript">
				activa("OK");
			</script>
			
			';
	?>
</td></tr></table>
 
 
 <br />
 <table width="95%" align="center" class="tablaDatos" >
   <tr >
     <td height="28" align="center"  class="tituTab">OBSERVACIONES</td>
   </tr>
   <tr >
     <td height="28" align="center"  class="segundalinea"><textarea name="observaciones" cols="100" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><?PHP  if (mysql_num_rows($sql_resp)>0) echo mysql_result($sql_resp,0); ?>
     </textarea></td>
   </tr>
 </table>
 <input name="ingresar" type="hidden" />
 <input name="visualizar" type="hidden" />
 <input name="anio" type="hidden" value="<?PHP  echo $_GET['anio'] ?>" />
 <input name="id_solicitud" type="hidden" value="<?PHP  echo $_GET['id_solicitud'] ?>" />
 <br />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><img src="../images/continuar1.png" alt="Capturar documentación" class="btnOld" onclick="ejecuta(-1)" onmouseover="" /></td>
     <td align="center"><img src="../images/cancelar1.png" alt="Ir a movimientos" class="btnOld" onclick="location.replace('../solicitud/seleccionarS.php?backing=1&id_solicitud=<?PHP  echo $datos['id_solicitud']; ?>&amp;anio=<?PHP  echo $datos['anio']; ?>')" /></td>
   </tr>
 </table>

</form>

<?PHP 



}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
	$consulta=mysql_query("select * from solicitud where id_solicitud=".$_POST["id_solicitud"]." and anio=".$_POST["anio"],$conexion);
	$datos=mysql_fetch_array($consulta);
	
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "DELETE FROM formato2 WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$consulta= "DELETE FROM formato2_desglose WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$consulta= "DELETE FROM formato_observaciones WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud']." AND formato=2";
		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "select * from cat_preguntas_formato2";
		$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		while($pregunta= mysql_fetch_array($sql_preg)){

			$consulta= "insert into formato2 values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$pregunta['id_pregunta'].",'".$_POST['proyecto'.$pregunta['id_pregunta']]."','".$_POST['pdu'.$pregunta['id_pregunta']]."','".$_POST['cumple'.$pregunta['id_pregunta']]."','".$_POST['diferencia'.$pregunta['id_pregunta']]."')";
//			echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}
		mysql_free_result($sql_preg);
		if (isset($_POST['visualizar']) && $_POST['visualizar']=='OK'){
				$consulta= "select * from cat_desglose_areas";
				$sql_area=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($areas= mysql_fetch_array($sql_area)){
					$consulta= "insert into formato2_desglose values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$areas['id_area'].",'".$_POST['superficie'.$areas['id_area']]."','".$_POST['observaciones'.$areas['id_area']]."')";
							echo $consulta."<br>";
					mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				
				}
		}

		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	Conexion::ejecutar("insert into formato_observaciones values (?,?,2,?)",array($_POST['anio'],$_POST['id_solicitud'],$_POST['observaciones']));


if($datos["id_proyecto1"]==3 || $datos["id_proyecto1"]==11 || $datos["id_proyecto1"]==12)
	$url="formatocon.php?id_solicitud=".$id_solicitud."&anio=".$_POST["anio"]."&mod=1";
else
	$url="../solicitud/seleccionarS.php?backing=1&id_solicitud=".$id_solicitud."&anio=".$_POST["anio"];
?>
<script language="javascript">

	window.open('../reportes/formato_cuadro.php?id_solicitud=<?PHP   echo $id_solicitud; ?>&anio=<?PHP   echo $_POST['anio']; ?>');
	window.location='<?PHP   echo $url; ?>';

</script>
<?PHP 

}	mysql_close($conexion);

?>
</body>

</html>

