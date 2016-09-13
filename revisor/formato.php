<?PHP 

		$requiredUserLevel = array(1,3);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/libreria.php");


include ("../libreria/ConexionPDO.php");
	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);
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
			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];
					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);


?>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

var miPopup

function abreVentana(){

	if (document.forms.formulario.id_municipio.value!=""){
		miPopup = window.open("../localidades/listado.php?id_municipio="+document.formulario.id_municipio.options[document.formulario.id_municipio.selectedIndex].value,"miwin","width=600,height=400,scrollbars=yes")

		miPopup.focus()
	}else
		alert("Seleccione un municipio");
}




correcto2=true; //ojo DEBE ser global



function ejecuta(valor,ejecutar){

if(!ejecutar){
	ejecutar=true;	
}

var correcto;

var cad,nombrecampo;



switch(valor){

<?PHP  
		$consulta='select numero,id_pregunta , CONCAT(LEFT(p.descripcion,15),"...") AS pregunta from cat_apartado_formato f, `cat_preguntas_formato` p WHERE f.`id_apartado`=p.`id_apartado`';
		$sql_pregv=mysql_query($consulta,$conexion);
		$cont=0;
		while ($data=mysql_fetch_array($sql_pregv)){
			$cont++;
			echo '			
			case '.$cont.':
				nombrecampo="'.$data['numero'].".".$data['id_pregunta'].".- ".$data['pregunta'].'\nAsigne respuesta";
				cad=formulario.'.$data['numero'].$data['id_pregunta'].'.value;
				if (cad=="")
					correcto=false;
				else
					correcto=true;
		
				break;
			';
				}
?>

	case -1:

		correcto2=true;

		for(var i=1;i<<?PHP  echo $cont; ?>;i++){
			correcto=ejecuta(i,ejecutar);
			if(!correcto)
				ejecutar=false;
			correcto2=correcto2&&correcto;

		}// for

		break;

}// Switch



if (valor!=-1){	

	if (!correcto && ejecutar){
		//alert("Error en: "+nombrecampo);
	}// if	

}// if

else{
	//if (correcto2){
			document.forms.formulario.ingresar.value="OK";
			document.forms.formulario.submit( );
	//}	
	//else{
		//parent.error("Llene todos los campos marcados con *");	
	//}

}//else



return correcto;

}//function



//-->

</script>
<div class="tituSec">Formato único</div>
 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">
   <input name="ingresar" type="hidden" />
   <input name="anio" type="hidden" value="<?PHP  echo $_GET['anio'] ?>" />
   <input name="id_solicitud" type="hidden" value="<?PHP  echo $_GET['id_solicitud'] ?>" />
   <input name="abreviatura" type="hidden" value="<?PHP  echo $_GET['abreviatura'] ?>" />
   <table width="80%" class="tablaDatos" align="center">
     <tr >
       <td align="center"  class="segundalinea"> Nombre del proyecto</td>
       <td align="center" colspan="3"  class="segundalinea"><?PHP  echo $datos['nombre_proyecto']; ?>
         <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" /></td>
     </tr>
     <tr class="tituTab">
       <td height="28" align="center"  class="primeralinea">Ficha</td>
       <td align="center"  class="primeralinea">Ubicaci&oacute;n</td>
       <td align="center"  class="primeralinea">Superficie</td>
       <td align="center"  class="primeralinea"># Viviendas</td>
     </tr>
     <tr >
       <td align="center"  class="segundalinea"><?PHP  echo $datos['id_solicitud']."/".$_GET['abreviatura']."/".$datos['anio']; ?></td>
       <td align="center"  class="segundalinea"><?PHP  echo $datos['direccion']; ?></td>
       <td align="center"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>
         m<sup>2</sup></td>
       <td align="center"  class="segundalinea"><?PHP  echo $datos['num_viviendas']; ?></td>
     </tr>
   </table>
   <br />


<?PHP  
				$consulta= "select * from cat_apartado_formato";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					while($apartado= mysql_fetch_array($resultado)){
?>
		<table width="95%" align="center" class="tablaDatos"> 
        <tr class="tituTab"><td><?PHP  echo $apartado['numero'].". ".$apartado['descripcion'] ?></td></tr>
        <tr >
<?PHP  
				$consulta= "select * from cat_preguntas_formato WHERE id_apartado=".$apartado['id_apartado'];
				$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					while($pregunta= mysql_fetch_array($sql_preg)){
?>
            <td class="segundalinea"><?PHP  echo $apartado['numero'].".".$pregunta['id_pregunta'].".- ".$pregunta['descripcion'] ?></td>
            </tr><tr > 
            <td class="segundalinea" >  <textarea name="<?PHP  echo $apartado['numero'].$pregunta['id_pregunta']; ?>" cols="100" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"></textarea>            </td>
          </tr>
<?PHP 					} //fin while


?>
            
          </table>
<br />
<?PHP 					} //fin while


?>

<table width="95%" align="center" >
  <tr >
    <td height="28" align="center"  class="primeralinea">OBSERVACIONES</td>
  </tr>
  <tr >
    <td height="28" align="center"  class="segundalinea"><textarea name="observaciones" cols="100" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"></textarea></td>
  </tr>
</table>
<br />
<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td align="center"><img src="../images/continuar1.png" alt="Capturar documentación" class="btnOld" onclick="ejecuta(-1)" onmouseover="" /></td>
    <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>'" /></td>
  </tr>
</table>
<br />
</form>

<?PHP 



}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion);

		$consulta='select f.id_apartado,numero,id_pregunta from cat_apartado_formato f, `cat_preguntas_formato` p WHERE f.`id_apartado`=p.`id_apartado`';

		$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		while($data=mysql_fetch_array($sql_preg)){
			$consulta= "insert into formato values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$data['id_apartado'].",".$data['id_pregunta'].",'".$_POST[$data['numero'].$data['id_pregunta']]."')";
//			echo $consulta."<br>";
			mysql_query($consulta,$conexion);
		}
		mysql_free_result($sql_preg);

		mysql_query("COMMIT",$conexion);

		Conexion::ejecutar("insert into formato_observaciones values (?,?,1,?)",array($_POST['anio'],$_POST['id_solicitud'],$_POST['observaciones']));

?>
<script language="javascript">
<!--

	<?php 
		$consulta='select id_proyecto1 from solicitud WHERE `anio`='.$_POST['anio'].' AND id_solicitud='.$_POST['id_solicitud'];
		$sql_tipo=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$tipo=mysql_result($sql_tipo,0);
		mysql_free_result($sql_tipo);
		if ($tipo!=2 && $tipo!=4){ ?>
			window.open('../reportes/formato_unico.php?id_solicitud=<?PHP   echo $_POST['id_solicitud']; ?>&anio=<?PHP   echo $_POST['anio']; ?>');
		<?php 
		}
		if ($tipo==1 || ($tipo>=5 && $tipo<= 10))
			$destino="formato2.php";
		if ($tipo==2)
			$destino="formato4.php";
		if ($tipo==3 || $tipo==23 || ($tipo>=11 && $tipo<= 22))
			$destino="formato2.php";
		if ($tipo==4)
			$destino="formato3.php";
	?>
	window.location='<?PHP   echo $destino."?id_solicitud=".$_POST['id_solicitud']."&anio=".$_POST['anio']."&abreviatura=".$_POST['abreviatura']; ?>';

-->
</script>
<?PHP 
}	mysql_close($conexion);

?>
</body>

</html>

