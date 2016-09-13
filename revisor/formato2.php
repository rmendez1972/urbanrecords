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

			  $consulta= "select id_proyecto1,nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio, propietario from solicitud WHERE anio=".$_GET['anio']." AND id_solicitud=".$_GET['id_solicitud'];

					$sql_solicitud=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					$datos= mysql_fetch_array($sql_solicitud);
					mysql_free_result($sql_solicitud);


		$c2=mysql_query("select * from formato_observaciones where anio=".$_GET["anio"]." and id_solicitud=".$_GET["id_solicitud"]." and formato=2",$conexion);
		$r2=mysql_fetch_array($c2);
?>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function activa(visualizar){
	if (visualizar=='OK'){
		document.getElementById('desglose').style.display='block';
		document.forms.formulario.desglose.value="OK";
	}else{
		document.getElementById('desglose').style.display='none';
		document.forms.formulario.desglose.value="NO";
	}
}



correcto2=true; //ojo DEBE ser global



function ejecuta(){

	//if (correcto2){
		document.forms.formulario.ingresar.value="OK";
<?
		if ($datos['id_proyecto1']==1 || ($datos['id_proyecto1']>=5 && $datos['id_proyecto1']<= 10))
			echo '
		document.forms.formulario.desglose.value="OK";
			';
	?>
		document.forms.formulario.submit( );

}//function

var nfilas=0;
var ncolumnas=0;
var colact=0;

//-->
function eliminarColumna(col){
	document.getElementById("titulosgen").removeChild(document.getElementById("ctit"+col));
	var i;
	for(i=1;i<=nfilas;i++)
		document.getElementById("fila"+i).removeChild(document.getElementById("ctxt_"+i+"_"+col));
	ncolumnas--;
	document.getElementById("ncols").value=ncolumnas;
	ajustador();
}
function agregarColumna(){
	colact++;
	var nodtitulo=document.createElement("th");
	nodtitulo.id="ctit"+colact;
	nodtitulo.innerHTML="<input type='text' size='14' name='columna"+colact+"' id='columna"+colact+"' value='' onblur='javascript:this.value=this.value.toUpperCase()' /> <img src='../images/remove.png' width='20' height='20' class='btnOld' title='Eliminar columna' onclick=\"eliminarColumna("+colact+")\" />";
	document.getElementById("titulosgen").appendChild(nodtitulo);
	
	var i;
	for(i=1;i<=nfilas;i++){
		var nodtxt=document.createElement("td");
		nodtxt.id="ctxt_"+i+"_"+colact;
		nodtxt.style.textAlign="center";
		var idtxt=colact+"_"+document.getElementById("idp_"+i).value;
		nodtxt.innerHTML="<textarea name='"+idtxt+"' id='"+idtxt+"' cols='15' rows='3' onblur='javascript:this.value=this.value.toUpperCase()'></textarea>";
		document.getElementById("fila"+i).appendChild(nodtxt);
	}
	
	ncolumnas++;
	document.getElementById("ncols").value=ncolumnas;
	ajustador();
}

function agregarArea(){
	var params=new Object();
	params.modulo="FRACCIONAMIENTO";
	params.accion="AGREGAR_AREA";
	params.area=$("#txtarea").val();
	
	if(params.area.length==0)
		return;
		
	$.post("Revisor.php",params,function(datos){
		var splt=datos.split("#");
		
		if(splt[0]=="ok"){
			var idarea=splt[1];
			var tabla=document.getElementById("tabDesglose");
			var desglose=document.getElementById("tabTotales");
			
			var fila=document.createElement("tr");
			fila.innerHTML="<td>"+params.area+"</td><td><input name='superficie"+idarea+"' style='text-align:right' type='text' size='25' maxlength='20' /></td><td><textarea name='observaciones"+idarea+"' cols='60' rows='2' onblur='javascript:this.value=this.value.toUpperCase()'></textarea></td>";
			tabla.appendChild(fila);
		}
		else
			parent.error(splt[1]);
	},"html");
	
	$("#txtarea").val("");
}
</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">
   <div class="tituSec">Formato tabla</div>
   <table width="80%" class="tablaDatos" align="center">
     <tr class="tituTab">
       <td colspan="4" align="center">Proyecto</td>
     </tr>
     <tr >
       <td align="center"  class="segundalinea"> Nombre del proyecto</td>
       <td colspan="3"  class="segundalinea"><?PHP  echo $datos['nombre_proyecto']; ?>
         <img src="../images/b_search.png" alt="Visualizar datos solicitud" width="16" height="16" border="0" class="btnOld" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura']; ?>')" /></td>
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
<div class="tituSec" style="position:relative; margin:18px;">
  Generales
  <div class="btnVerde" onclick='agregarColumna()' style="padding:6px; position:absolute; right:5px; top:-5px;">Agregar columna</div>
  </div>
   <table width="95%" align="center" class="tablaDatos" >
     <tr id="titulosgen" class="tituTab">
       <td width="110" height="28" align="center">CONCEPTO</td>
       <?
	   $cons=mysql_query("select count(*) from formato2 where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."'",$conexion);
	   $res=mysql_fetch_array($cons);
	   $columnas=array();
	   if($res[0]==0){
		   $cons=mysql_query("select * from cat_columnas_formato2 order by id_columna asc limit 0,4",$conexion);
		   $columna=0;
		   while($res=mysql_fetch_array($cons)){
			   $columnas[$columna]=$res["descripcion"];
			   $columna++;
				echo "<th id='ctit".$columna."'><input type='text' size='14' name='columna".$columna."' id='columna".$columna."' value='".$res["descripcion"]."' onblur='javascript:this.value=this.value.toUpperCase()' /> <img src='../images/remove.png' width='20' height='20' class='btnOld' title='Eliminar columna' onclick=\"eliminarColumna(".$columna.")\" /></th>";   
		   }
	   }
	   else{
		   $cons=mysql_query("select distinct C.id_columna,C.descripcion from formato2 F inner join cat_columnas_formato2 C on F.id_columna=C.id_columna where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."' order by id_columna asc",$conexion);
		   $columna=0;
		   while($res=mysql_fetch_array($cons)){
			   $columnas[$columna]=$res["descripcion"];
			   $columna++;
				echo "<th id='ctit".$columna."'><input type='text' size='14' name='columna".$columna."' id='columna".$columna."' value='".$res["descripcion"]."' onblur='javascript:this.value=this.value.toUpperCase()' /> <img src='../images/remove.png' width='20' height='20' class='btnOld' title='Eliminar columna' onclick=\"eliminarColumna(".$columna.")\" /></th>";
		   }
	   }
	   ?>
     </tr>
     <?PHP  
				$consulta= "select * from cat_preguntas_formato2";
				$sql_preg=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

$fila=0;
					while($pregunta= mysql_fetch_array($sql_preg)){
						$fila++;
?>
     <tr id="fila<? echo $fila; ?>">
       <td class="segundalinea"><?PHP  echo $pregunta['descripcion'] ?><input id="idp_<? echo $pregunta["id_pregunta"]; ?>" name="idp_<? echo $pregunta["id_pregunta"]; ?>" type="hidden" value="<? echo $pregunta["id_pregunta"]; ?>" /></td>
       <?
	   $ncol=0;
	   foreach($columnas as $col){
		   $ncol++;
		   $idtxt=$ncol."_".$pregunta["id_pregunta"];
		   $val="";
		   $cons=mysql_query("select id_columna from cat_columnas_formato2 where descripcion='".$col."'",$conexion);
		   if($res=mysql_fetch_row($cons)){
		   		$cons2=mysql_query("select valor from formato2 where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."' and id_pregunta='".$pregunta["id_pregunta"]."' and id_columna='".$res[0]."'",$conexion);
				if($res2=mysql_fetch_row($cons2))
					$val=$res2[0];
		   }
			echo "<td id='ctxt_".$fila."_".$ncol."' style='text-align:center'><textarea name='$idtxt' id='$idtxt' cols='15' rows='3' onblur='javascript:this.value=this.value.toUpperCase()'>$val</textarea></td>";   
	   }
	   ?>
     </tr>
     <?PHP 					} //fin while
					mysql_free_result($sql_preg);

?>
   </table>
   <script>
   nfilas=<? echo $fila; ?>;
   ncolumnas=<? echo $columna; ?>;
   colact=ncolumnas;
   </script>
   <br />

	

   <div id="desglose" style="display:none">
   
   <div class="tituSec">DESGLOSE DE ÁREAS (USO DE SUELO)</div>
   
   <div style="margin:4px; margin-left:25px">
   Agregar uso de suelo 
     <input type="text" name="txtarea" id="txtarea" />
     <input type="button" name="button" id="button" value="Agregar" class="btnVerde" style="float:none; font-weight:bold; padding:5px" onclick="agregarArea()" />
   </div>
   
   <table id="tabDesglose" width="95%" align="center" class="tablaDatos"  > 
          <tr id="titDesglose" class="tituTab"> 
              <td height="28" align="center"  class="primeralinea">ÁREAS</td>
              <td align="center"  class="primeralinea">SUPERFICIE M<sup>2</sup></td>
              <td align="center"  class="primeralinea">OBSERVACIONES SEGÚN NORMA</td>
          </tr>
<?PHP  
				$consulta= "select * from cat_desglose_areas order by orden desc,id_area asc";
				$sql_area=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$areatotal=0.00;
					while($areas= mysql_fetch_array($sql_area)){
						$valsup="";
						$valobs="";
						$cons=mysql_query("select superficie,observaciones from formato2_desglose where anio='".$_GET["anio"]."' and id_solicitud='".$_GET["id_solicitud"]."' and id_area='".$areas["id_area"]."'",$conexion);
						if($res=mysql_fetch_row($cons)){
							$valsup=$res[0];
							$valobs=$res[1];
							
							$areatotal+=$res[0];
						}
?>
        <tr <? echo $areas["id_area"]==7?"id='tabTotales'":""; ?>>
            <td class="segundalinea"><?PHP  echo $areas['descripcion'] ?></td>
            <td class="segundalinea" >
              <input name="<?PHP  echo "superficie".$areas['id_area']; ?>" style="text-align:right" type="text" size="25" maxlength="20" value="<? echo $valsup; ?>"/></td>
            <td class="segundalinea" >  <textarea name="<?PHP  echo "observaciones".$areas['id_area']; ?>" cols="60" rows="2" onblur="javascript:this.value=this.value.toUpperCase()"><? echo $valobs; ?></textarea>            </td>
          </tr>
<?PHP 					} //fin while
					mysql_free_result($sql_area);

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
   
   <input name="ncols" type="hidden" id="ncols" value="<? echo count($columnas); ?>" />
   <br />

   <table width="95%" align="center" class="tablaDatos" >
     <tr >
       <td height="28" align="center"  class="tituTab">OBSERVACIONES</td>
     </tr>
     <tr >
       <td height="28" align="center"  class="segundalinea"><textarea name="observaciones" cols="100" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"><?php echo $r2["observaciones"]; ?></textarea></td>
     </tr>
   </table>
   <input name="ingresar" type="hidden" />
   <input name="desglose" type="hidden" />
   <input name="anio" type="hidden" value="<?PHP  echo $_GET['anio'] ?>" />
   <input name="id_solicitud" type="hidden" value="<?PHP  echo $_GET['id_solicitud'] ?>" />
   <input name="abreviatura" type="hidden" value="<?PHP  echo $_GET['abreviatura'] ?>" />
   <br />
   <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
     <tr>
       <td align="center"><img src="../images/continuar1.png" alt="Capturar documentación" class="btnOld" onclick="ejecuta()" onmouseover="" /></td>
       <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>'" /></td>
     </tr>
   </table>
</form>

<?PHP 



}

if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
	$consulta=mysql_query("select * from solicitud where id_solicitud=".$_POST["id_solicitud"]." and anio=".$_POST["anio"],$conexion);
	$datos=mysql_fetch_array($consulta);
		
		//DATOS GENERALES
		$ncols=$_POST["ncols"];
		$cont=0;
		$i=0;
		
		mysql_query("delete from formato2 where anio='".$_POST["anio"]."' and id_solicitud='".$_POST["id_solicitud"]."'",$conexion);
		while($cont<$ncols){
			if(isset($_POST["columna".$i])){
				$nomcol=$_POST["columna".$i];
				$cons=mysql_query("select id_columna from cat_columnas_formato2 where descripcion='".$nomcol."'",$conexion);
				if($res=mysql_fetch_row($cons))
					$idcol=$res[0];
				else{
					$idcol=0;
					$cons=mysql_query("select max(id_columna) from cat_columnas_formato2",$conexion);	
					if($res=mysql_fetch_row($cons))
						$idcol=$res[0];
					$idcol++;
				}
				
				$cons=mysql_query("select * from cat_preguntas_formato2 order by id_pregunta asc",$conexion);
				while($res=mysql_fetch_row($cons)){
					$pregid=$res[0];
					$valorx=$_POST[$i."_".$pregid];
					
					mysql_query("insert into formato2 values ('".$_POST["anio"]."','".$_POST["id_solicitud"]."','".$pregid."','".$idcol."','".$valorx."')",$conexion);
				}
				
				$cont++;
			}
			$i++;
		}
		
		//DESGLOSE DE AREAS
		if (isset($_POST['desglose']) && $_POST['desglose']=='OK'){
				$consulta= "select * from cat_desglose_areas";
				$sql_area=mysql_query($consulta,$conexion);
				
				mysql_query("delete from formato2_desglose where anio='".$_POST['anio']."' and id_solicitud='".$_POST['id_solicitud']."'",$conexion);
				
				while($areas= mysql_fetch_array($sql_area)){
					$superficie=$_POST['superficie'.$areas['id_area']];
					$observaciones=$_POST['observaciones'.$areas['id_area']];
					
					if($superficie!=""){
						$consulta= "insert into formato2_desglose values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$areas['id_area'].",'".$superficie."','".$observaciones."')";
						mysql_query($consulta,$conexion);
					}
				}
		}
		mysql_query("delete from formato_observaciones where anio='".$_POST['anio']."' and id_solicitud='".$_POST['id_solicitud']."' and formato=2",$conexion);
		
		Conexion::ejecutar("insert into formato_observaciones values (?,?,2,?)",array($_POST['anio'],$_POST['id_solicitud'],$_POST['observaciones']));

if($datos["id_proyecto1"]==3 || $datos["id_proyecto1"]==11 || $datos["id_proyecto1"]==12)
	$url="formatocon.php?id_solicitud=".$_POST["id_solicitud"]."&anio=".$_POST["anio"];
else if($datos["id_proyecto1"]==23)
	$url="condomaestro.php?id_solicitud=".$_POST["id_solicitud"]."&anio=".$_POST["anio"];
else
	$url="../solicitud/seleccionarS.php?backing=1&id_solicitud=".$_POST["id_solicitud"]."&anio=".$_POST["anio"];
?>
<script language="javascript">

	window.open('../reportes/formato_cuadro.php?id_solicitud=<?PHP   echo $_POST["id_solicitud"]; ?>&anio=<?PHP echo $_POST['anio']; ?>');
	
	window.location='<?PHP   echo $url; ?>';

</script>
<?PHP 

}	mysql_close($conexion);

?>
</body>

</html>

