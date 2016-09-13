<?PHP 

	$requiredUserLevel = array(1,2,3);

	$cfgProgDir = '../';


		include("../seguridad/secure.php");

		include ("../libreria/config.php");
		
		include ("../libreria/encabezado.php");
		
		include ("../libreria/libreria.php");



	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);




if (isset($_POST['ingresar']) && $_POST['ingresar']=='OK'){
		mysql_query("BEGIN",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		$consulta= "insert into seguimiento values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$_POST['id_seguimiento'].",'".$_POST['fecha']."','".$_POST['observaciones']."','".$login."',NOW())";

		mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		if ( $_POST['etapa']==0 && isset($_POST['id_revisor']) && ($_POST['id_revisor']!="")){

			$consulta= "insert into solicitud_revisor values (".$_POST['anio'].",".$_POST['id_solicitud'].",'".$_POST['id_revisor']."','".$_POST['fecha']."','',0,'".$login."',NOW())";
	
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}
		if ($_POST['etapa']==1){
		
			$consulta= "UPDATE solicitud SET fracciones='".$_POST['fracciones']."',num_viviendas='".$_POST['viviendas']."', superficie='".$_POST['superficie']."'  WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}
		if ($_POST['etapa']==2){
		
			$consulta= "UPDATE solicitud SET id_validacion='".$_POST['id_validacion']."' WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

			$consulta= "insert into solicitud_validacion values (".$_POST['anio'].",".$_POST['id_solicitud'].",".$_POST['id_validacion'].",'".$_POST['fecha']."','".$_POST['observaciones']."','".$login."',NOW())";
		
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			
		}
		if ($_POST['etapa']==3){
			$array_constancia=split(" ",$_POST['constancias']);
			$repetidos="";
			for($i=0;$array_constancia[$i];$i++){
				// Insertar datos 
				$consulta= "insert into constancias values (".$_POST['anio'].",".$_POST['id_solicitud'].",'".$array_constancia[$i]."','".$_POST['municipio']."')";
				//echo $consulta."<br>";
				
				$sql_constancia=mysql_query($consulta,$conexion);
				if (!$sql_constancia){
					$repetidos.= $array_constancia[$i].", ";
				}
			}
			//agregar los pagos
			$consulta= "INSERT INTO constancias_pagos values (".$_POST['anio'].", ".$_POST['id_solicitud'].",".$_POST['sancion'].", ".$_POST['derechos'].")"; 
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			
			$consulta= "UPDATE solicitud_revisor SET  estado=1  WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
			
		}

		if ($_POST['etapa']==4){
	   			$consulta= "select concat( anio ,'-', id_solicitud ,'-', id_solicitante ) AS email,`nombre`, `correo` 
				FROM solicitantes s
				WHERE id_solicitud=".$_POST['id_solicitud']." AND anio=".$_POST['anio'];

				$sql_solicitante=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				while($solicitantes= mysql_fetch_array($sql_solicitante)){
					$aviso="aviso".$solicitantes['email'];
					if (isset($$aviso)){
						$asunto = "Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']." con Constancia Autorizada"; 
						$cuerpo = ' 
						<html> 
						<head> 
						   <title>'."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].'</title> 
						</head> 
						<body> 
						<h1>Constancia Autorizada</h1> 
						<p> 
						 <b>Estimado '.$solicitantes['nombre'].': </b></p>
						<p>
						  Por este medio se le comunica con fecha '.$_POST['fecha'].' que la '."Ficha No. ".$_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio'].' promovida por Ud. para su Constancia de Compatibilidad, ha sido autorizada.<br>
						  Para solicitar mayores informes:<br>
						  Gobierno del Estado de Quintana Roo<br>
						  Secretar&iacute;a de Desarrollo Urbano<br>
						  Subsecretar&iacute;a de Desarrollo Urbano y Vivienda<br>
						  DIRECCI&Oacute;N DE ADMINISTRACI&Oacute;N URBANA<br>
						  Av. Efra&iacute;n Aguilar No. 418 entre Dimas Sansores y Retorno 3, Chetumal, Quintana Roo<br>
						  Tel. (01-983 83 24108, 1293317, 1293318 Ext. 137)  ad_urbana@qroo.gob.mx<br><br>
						Sin otro particular, le envio un cordial saludo. 
						</p>
						</body> 
						</html> 
						'; 
						//$solicitantes['correo']
						mail("sistemas_sedu@qroo.gob.mx",$asunto,$cuerpo,$headers);
					}
				}
				mysql_free_result($sql_solicitante);	
		}

		if ($_POST['etapa']==6){
		
			$consulta= "UPDATE solicitud SET estado='2' WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
			//echo $consulta."<br>";
			mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		}

		mysql_query("COMMIT",$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


		if (isset($_POST['id_revisor']) && ($_POST['id_revisor']!="") && $_POST['etapa']==0){
		
			$consulta= "SELECT nombre,email FROM cat_usuario WHERE login='".$_POST['id_revisor']."'";
			$sql_user=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
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
				mail("sistemas_sedu@qroo.gob.mx",$asunto,$cuerpo,$headers);
		
		}

		
		$destino="seleccionarS.php?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud'];
		if ($_POST['etapa']==1)
			$destino="../revisor/formato.php?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura'];
?>

<script language="javascript">
	<?PHP  if (isset($repetidos) && $repetidos!=""){ 
		echo 'alert("Las constancias con número '.$repetidos.' están repetidas.\nNo se ingres&oacute; las constancias con estos números, favor de verificar");';
	 } ?>
	window.location='<?PHP  echo $destino ?>';

</script>
<?PHP 

}// si ya existe la variable nombre. Envio una medida para guardar

//Cerrar la conexion y liberar memoria

//	mysql_close($conexion);







?>


<?PHP 

// Si no ha sido enviado algo

if (isset($_POST['id_solicitud']) && isset($_POST['anio'])){

		$consulta= "SELECT id_seguimiento 
					FROM `seguimiento` 
					WHERE anio=".$_POST['anio']." AND id_solicitud='".$_POST['id_solicitud']."'";
		$consulta.= " ORDER BY id_seguimiento DESC limit 1"; 

		$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
		$etapa=0;
		if (mysql_num_rows($sql_datos)>0)
			$etapa=mysql_result($sql_datos,0);

?>
<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>
<script language="javascript" src="../libreria/validacion_entero.js"></script>

<SCRIPT language="javascript">

<!--
          <?PHP  if ($etapa==3){ ?>

function ClearList(OptionList, TitleName) 
    {   
    OptionList.length = 0;
    }
	
function move(side)
{   
	var temp1 = new Array();
	var temp2 = new Array();
	var tempa = new Array();
	var tempb = new Array();
	var current1 = 0;
	var current2 = 0;
	var y=0;
	var attribute;
	
	//assign what select attribute treat as attribute1 and attribute2
		attribute1 = document.formulario.numero; 
		attribute2 = document.formulario.category_selected;

	//fill an array with old values
	for (var i = 0; i < attribute2.length; i++)
	{  
		if (!attribute2.options[i].selected)
		{
			y=current1++
			temp1[y] = attribute2.options[i].value;
			tempa[y] = attribute2.options[i].text;
		}
	}

	//assign new values to arrays
		if (side == "right")
		{
			if (attribute1.value != "")
			{
					y=current1++;
					temp1[y] = attribute1.value;
					tempa[y] = attribute1.value;
			}else
				alert("Asigne Número de constancia");
		}

	//vaciando array
	ClearList(attribute2,attribute2);
	document.formulario.numero.value="";
	
	//generating new options 
	for (var i = 0; i < temp1.length; i++)
	{  
		attribute2.options[i] = new Option();
		attribute2.options[i].value = temp1[i];
		attribute2.options[i].text =  tempa[i];
	}

}

		

function calculo()   
  {   
	  formulario.monto.value=parseFloat(formulario.sancion.value) + parseFloat(formulario.derechos.value);
 }
          <?PHP  } ?>


correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){



	case 1:
		<?PHP  if ($etapa==1)
			echo '
		nombrecampo="Superficie\nAsigne sólo números y/o decimales";
		cad=formulario.superficie.value;
		correcto=esnumerocondecimal(cad);
			';
		if ($etapa==3)
			echo '
		nombrecampo="Sanción\nAsigne sólo números y/o decimales";
		cad=formulario.sancion.value;
		correcto=esnumerocondecimal(cad);
			';			
		else
			echo " correcto=true;";
			?>

		break;
	case 2:

		<?PHP  if ($etapa==0){
			echo "nombrecampo=\"Revisor\\nAsigne un revisor\";
			cad=formulario.revisor.value;
			if (cad==\"\")
				correcto=false;
			else";
			}
		if ($etapa==3)
			echo '
		nombrecampo="Derechos\nAsigne sólo números y/o decimales";
		cad=formulario.derechos.value;
		correcto=esnumerocondecimal(cad);
			';			
		else
			echo " correcto=true;";

		?>

		break;


	case 3:

		<?PHP  if ($etapa==1)
			echo '
			nombrecampo="Fracciones\nAsigne sólo números";
			cad=formulario.fracciones.value;
			correcto=esnatural(cad);';
			else
				echo "correcto=true;";
			?>
		break;

	case 4:

		<?PHP  if ($etapa==1)
				echo '
				nombrecampo="Viviendas\nAsigne sólo números";
				cad=formulario.viviendas.value;
				correcto=esnatural(cad);
				';
			if ($etapa==3)
				echo '
				nombrecampo="Constancias.\n Debe de asignar al menos una";
				attribute2 = document.formulario.category_selected;
				if (attribute2.length==0){
					correcto=false;
				}else{
					correcto=true;
				}
				';			
			else
				echo "correcto=true;";
			?>

		break;



	case -1:

		correcto2=true;

		for(var i=1;i<5;i++){

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
		<?PHP  if ($etapa==3){ ?>
			attribute2 = document.formulario.category_selected;
			attribute = document.formulario.constancias;
			//fill an array with old values
			for (var i = 0; i < attribute2.length; i++)
			{  
				attribute.value=attribute.value+attribute2.options[i].value+" ";
			}
		<?PHP  } ?>
		document.forms.formulario.submit( );

	}	



}//else



return correcto;

}//function



//-->

</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>" >

 <table width="90%" class="tabla1" align="center">

  <tr > 

  	  <td align="center"  class="primeralinea"> Seguimiento Solicitud</td>

  </tr>

  <tr>	<td align=center ><br>

		<table width="95%" class="tabla2">
          <tr >
            <td  class="segundalinea">Etapa</td>
            <td  class="segundalinea"><select name="id_seguimiento" size="1">
              <?PHP   
			  	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

			  $consulta= "select * from cat_seguimiento WHERE id_seguimiento IN (SELECT id_seguimiento FROM cat_seguimiento_opc WHERE seleccion=$etapa)";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");


					while($tipos= mysql_fetch_array($resultado)){
						echo '<option value="'.$tipos['id_seguimiento'].'">'.$tipos['descripcion'].'</option>';

					} //fin while
					mysql_free_result($resultado);
				?>
            </select>              <?PHP  echo $_POST['id_solicitud']."/".$_POST['abreviatura']."/".$_POST['anio']; ?><img src="../images/b_search.png" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$_POST['anio']."&id_solicitud=".$_POST['id_solicitud']."&abreviatura=".$_POST['abreviatura']; ?>')" alt="Visualizar datos solicitud" width="16" height="16" border="0" />            </td>
          </tr>
          <tr > 
            <td class="segundalinea" > Fecha </td>
            <td class="segundalinea" ><input name="fecha" type="text" style="vertical-align:middle" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly />
            <img src='../images/calendario.png' style="vertical-align:middle" name='calendario' alt='calendario' onclick='showCalendar(this,formulario.fecha, "yyyy-mm-dd",null,1,-1,-1)'></td>
          </tr>
          <?PHP  
		  if ($etapa==1){ 
		  

			  $consulta= "select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio from solicitud WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];

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

            <td class="segundalinea" ><input name="superficie" style="text-align:right" type="text" size="15" maxlength="10" value="<?PHP  echo $datos['superficie'] ?>"/>
            m<sup>2</sup></td>
          </tr>
          <tr > 

            <td  class="segundalinea" > Fracciones</td>

            <td class="segundalinea" ><input name="fracciones" style="text-align:right" type="text" size="15" maxlength="10" value="<?PHP  echo $datos['fracciones'] ?>"/></td>
          </tr>
          <tr > 

            <td  class="segundalinea" > Viviendas</td>

            <td class="segundalinea" >
              <input name="viviendas" type="text" style="text-align:right" size="15" maxlength="10" value="<?PHP  echo $datos['num_viviendas'] ?>" /></td>
          </tr>
          <?PHP  } ?>
          <?PHP  if ($etapa==2){ ?>
          <tr >
            <td class="segundalinea">Estado</td>
            <td class="segundalinea" ><select name="id_validacion" size="1">
              <?PHP   
			  	$consulta= "select * from cat_validacion";
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

				while($tipo_v= mysql_fetch_array($resultado)){
					echo '<option value="'.$tipo_v['id_validacion'].'">'.$tipo_v['descripcion'].'</option>';

				} //fin while
				mysql_free_result($resultado);
			?>
            </select></td>
          </tr>
          <?PHP  } ?>
          <?PHP  if ($etapa==3){ ?>
          <tr >
            <td class="segundalinea">Constancia(s) No.</td>
            <td class="segundalinea" style="vertical-align:middle"   >Asigne los números de constancia &gt;&gt;, para eliminar doble click sobre los asignados<br />
            <input name="numero" style="text-align:right" type="text" size="15" maxlength="10" />
              <input name="button" type="button" onclick="move('right')" value="&gt;&gt;" />
            
            <select ondblclick="move('left')" multiple="multiple" style="text-align:right; WIDTH: 150px" size="3" name="category_selected" width="150px">
            </select>
            <?PHP   
			  	$consulta= "select abreviatura from cat_municipios m, solicitud s WHERE s.id_municipio=m.id_municipio AND s.anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				echo "/".mysql_result($resultado,0)."/".$_POST['anio']; 
			?>
            <input name="municipio" type="hidden" value="<?PHP  echo mysql_result($resultado,0); ?>" /></td>
          </tr>
          <tr >
            <td class="segundalinea">Sanci&oacute;n</td>
            <td class="segundalinea" ><input name="sancion" style="text-align:right" type="text" size="15" maxlength="10" value="0" onblur="calculo();" />            </td>
          </tr>
          <tr >
            <td class="segundalinea">Derechos</td>
            <td class="segundalinea" ><input name="derechos" style="text-align:right" type="text" size="15" maxlength="10" value="0" onblur="calculo();"/>            </td>
          </tr>
          <tr >
            <td class="segundalinea">Monto a pagar</td>
            <td class="segundalinea" ><input name="monto" style="text-align:right" type="text" size="15" maxlength="10" value="0" readonly="readonly" />
              <input name="constancias" type="hidden" /></td>
          </tr>
          <?PHP  } ?>
          <?PHP  if ($etapa==4 || $etapa==5 || $etapa==6){ ?>
          <tr >
            <td class="segundalinea">Datos de Solicitantes</td>
            <td class="segundalinea" >
			<?PHP  
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

			 ?>
            </td>
          </tr>
          <?PHP  } ?>
          <?PHP  if ($etapa==5 || $etapa==6 || $etapa==7){ ?>
          <tr >
            <td class="segundalinea">Informaci&oacute;n de pagos</td>
            <td class="segundalinea" >
              <?PHP   
			  	$consulta= "select * from constancias WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
				$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
				$salto=0;
				echo "Constancias No. : ";
				while($constancias= mysql_fetch_array($resultado)){
					if ($salto <> 0) echo ", ";
					echo $constancias['numero']."/".$constancias['municipio']."/".$constancias['anio'];
					$salto++;
				}
				mysql_free_result($resultado);
			  	$consulta= "select * from constancias_pagos WHERE anio=".$_POST['anio']." AND id_solicitud=".$_POST['id_solicitud'];
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
          <tr > 
            <td width="20%"  class="segundalinea">
            	<?PHP  if ($etapa==1) 
						echo "Pendientes";
					else
						echo "Observaciones";
				?></td>
            <td width="80%" class="segundalinea" ><textarea name="observaciones" cols="70" rows="3" onblur="javascript:this.value=this.value.toUpperCase()"></textarea></td>
          </tr>
          <?PHP  if ($etapa==0){ ?>
          <tr >
            <td class="segundalinea">Revisor</td>
            <td class="segundalinea" ><input name="revisor"  type="text" style="vertical-align:middle" size="70" readonly=""> <input name="id_revisor" type="hidden" />
            
            <img src="../images/elegir1.png" style="vertical-align:middle" alt="Elegir y asignar revisor" onclick="window.open('../revisor/listado.php<?PHP  echo "?anio=".$_POST['anio'] ?>','miwin','width=600,height=400,scrollbars=yes')" /></td>
          </tr>
          <?PHP  } ?>
        </table>

		<input name="ingresar" type="hidden" />
		<input name="anio" type="hidden" value="<?PHP  echo $_POST['anio'] ?>" />
		<input name="id_solicitud" type="hidden" value="<?PHP  echo $_POST['id_solicitud'] ?>" />
		<input name="abreviatura" type="hidden" value="<?PHP  echo $_POST['abreviatura'] ?>" />
		<input name="etapa" type="hidden" value="<?PHP  echo $etapa; ?>" />
		<br />

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center"><img src="../images/aceptar1.png" alt="Guardar seguimiento"  onClick="ejecuta(-1);" /></td>

      		<td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>
		</tr>
		</table>
		<br>

</td></tr>

</table>

</form>

<?PHP 



}

?>

</body>

</html>

