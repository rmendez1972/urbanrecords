<?PHP 

		$requiredUserLevel = array(1,2,3);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/config.php");

include ("../libreria/encabezado.php");

include ("../libreria/libreria.php");



// Conexion con la BD

$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

if (!isset($_POST['filtrar']) && (!isset($_GET['anio'])) && (!isset($_GET['id_solicitud']))){
?>
<SCRIPT language="javascript" src="../libreria/validacion_entero.js"></SCRIPT>
<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function activa(){
	if (document.formulario.tipo[0].checked){
		document.getElementById('estado').style.display='block';
		document.getElementById('ranio').style.display='block';
		document.getElementById('rmunicipio').style.display='block';
		document.getElementById('rtipo').style.display='block';
		document.getElementById('fechas1').style.display='block';
		document.getElementById('fechas2').style.display='block';
		document.getElementById('rnumero').style.display='none';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='none';
		document.formulario.id_municipio.disabled=false; 
		document.formulario.id_proyecto.disabled=false; 
		document.formulario.fecha1.disabled=false; 
		document.formulario.fecha2.disabled=false;
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	}
	if (document.formulario.tipo[1].checked || document.formulario.tipo[4].checked){
		document.getElementById('estado').style.display='none';
		document.getElementById('ranio').style.display='block';
		document.getElementById('rmunicipio').style.display='none';
		document.getElementById('rtipo').style.display='none';
		document.getElementById('fechas1').style.display='none';
		document.getElementById('fechas2').style.display='none';
		document.getElementById('rnumero').style.display='block';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='none';
		document.formulario.id_municipio.disabled=true; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.fecha1.disabled=true; 
		document.formulario.fecha2.disabled=true;
		document.formulario.numero.disabled=false;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	
	}
	if (document.formulario.tipo[2].checked || document.formulario.tipo[3].checked){
		document.getElementById('estado').style.display='none';
		document.getElementById('ranio').style.display='block';
		document.getElementById('rmunicipio').style.display='none';
		document.getElementById('rtipo').style.display='none';
		document.getElementById('fechas1').style.display='none';
		document.getElementById('fechas2').style.display='none';
		document.getElementById('rnumero').style.display='none';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='block';
		document.formulario.id_municipio.disabled=true; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.fecha1.disabled=true; 
		document.formulario.fecha2.disabled=true;
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=false;
	}
	if (document.formulario.tipo[4].checked){
		document.getElementById('estado').style.display='block';
		document.getElementById('rango').style.display='block';
		document.formulario.numero2.disabled=false;
	}
}

correcto2=true; //ojo DEBE ser global



function ejecuta(valor){



var correcto;

var cad,nombrecampo;



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



return correcto;

}//function



//-->

</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF'] ?>">

 <table width="80%" class="tabla1" align="center">

  <tr class="fuente2"> 

  	  <td align="center"  class="primeralinea">Filtro de solicitudes </td>

  </tr>

  <tr>	<td align=center >

  		<br><br>

		<table width="95%" class="tabla2">
		  <tr  >
            <td align="left" class="segundalinea" >Tipo reporte</td>
		    <td align="left" class="segundalinea" ><label>
              <input type="radio" name="tipo" value="0" checked="checked" onclick="activa()" />
		      General</label>
                <label>
                <input type="radio" name="tipo" value="1" onclick="activa()" />
                N&uacute;mero                </label>
                <label>
                <input type="radio" name="tipo" value="2" onclick="activa()" />
                Propietario                </label>                  
                <label>
                <input type="radio" name="tipo" value="3" onclick="activa()" />
                Proyecto                </label>			
                <label>
                <input type="radio" name="tipo" value="4" onclick="activa()" />
                Rango</label>			
                </td>
	      </tr>
           <tr  id="estado" >
             <td align="left" class="segundalinea" >Estado Solicitud</td>
             <td align="left" class="segundalinea" ><select name="estado" size="1">
						<option value="Todos">Todos...</option>
						<option value="0" selected="selected">En proceso</option>
						<option value="1">Bajas</option>
						<option value="2">Finalizados</option>
             </select></td>
           </tr>
           <tr  id="restatus" >
             <td align="left" class="segundalinea" >Estado validaci&oacute;n</td>
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
           <tr  id="ranio" >
             <td align="left" class="segundalinea" >A&ntilde;o</td>
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
           <tr  id="rmunicipio" >
             <td align="left" class="segundalinea" >Municipio</td>
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
		  <tr id="rtipo">

            <td class="segundalinea">Tipo de proyecto</td>

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

          <tr  id="fechas1"> 

            <td width="20%" rowspan="2" class="segundalinea">Periodo</td>

            <td width="80%"  class="segundalinea">Inicio<input name="fecha1" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />

            <img src='../images/calendario.png' alt='calendario' name='calendario' id="calendario" onclick='showCalendar(this,formulario.fecha1, &quot;yyyy-mm-dd&quot;,null,1,-1,-1)' /></td>
          </tr>

          <tr id="fechas2"> 

            <td  class="segundalinea">Final
              <input name="fecha2" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly>

          <img src='../images/calendario.png' name='calendario' alt='calendario' onclick='showCalendar(this,formulario.fecha2, "yyyy-mm-dd",null,1,-1,-1)'></td>
          </tr>
          <tr  id="rnumero" style="display:none">
            <td width="20%" class="segundalinea">N&uacute;mero</td>
            <td width="80%" colspan="3"  class="segundalinea"><input name="numero" type="text" size="20" maxlength="20" disabled="disabled" /><div id="rango" style="display:none"> al
                    <input name="numero2" type="text" size="20" maxlength="20" disabled="disabled"  />
                </div></td>
          </tr>
          <tr  id="rdescripcion" style="display:none">
            <td width="20%" class="segundalinea">Descripci&oacute;n</td>
            <td width="80%" colspan="3"  class="segundalinea"><input name="descripcion" type="text" size="60" disabled="disabled" />
            </td>
          </tr>
        </table>

		<input name="filtrar" type="hidden" />
		<br />

		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >

  		<tr>

      		<td align="center"><img src="../images/aceptar1.png" alt="Generar Información"  onclick="ejecuta(-1);" /></td>

      		<td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

		</tr>

		</table><br>

</td></tr>

</table>

</form>


<?PHP 
}
if ((isset($_POST['filtrar']) && $_POST['filtrar']=="OK") || (isset($_GET['anio']) && isset($_GET['id_solicitud']))){
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
//echo $consulta;				
		$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	$existe= mysql_num_rows($resultado);

?>

		 <table width="95%" class="tabla1" align="center">

		  <tr class="fuente2"> 

		    <td align="center" class="primeralinea">Listado de solicitudes</td>

		  </tr>

		  <tr>

		    <td align="center"><br>

			 <table width="95%" class="tabla2">

			  <tr class="fuente2">

		          <td align="center" colspan="15"> Presione sobre el icono para visualizar, modificar, cancelar.
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

				?>                 </td>
			  </tr>

			  <tr >
		          <td width="15%" rowspan="2" align="center" class="primeralinea" >Operaci&oacute;n</td>
	            <td width="8%" rowspan="2" align="center" class="primeralinea" >Ficha</td>
                <td width="9%" rowspan="2" align="center" class="primeralinea" >Fecha</td>
	            <td width="33%"  rowspan="2" align="center" class="primeralinea" >Descripci&oacute;n</td>
	            <td height="40" colspan="11" align="center" class="primeralinea" >Seguimiento</td>
			  </tr>
			  <tr >
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

			  <tr >

		      

          <td align="center"  class="segundalinea"> 

          <img src="../images/b_search.png" onclick="window.open('../reportes/constancia.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']."&abreviatura=".$datos['abreviatura']; ?>')" alt="Visualizar datos solicitud" width="16" height="16" border="0" />
          <?PHP  
		  if ($userLevel<=2){ 
		  
			  if ($datos['estado']==0){ 
		  ?>
          <img src="../images/b_edit.png" alt="Modificar datos solicitud" width="16" height="16" border="0" onclick="location.replace('editar.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']; ?>')" />
          <img src="../images/b_drop.png" onclick="location.replace('solicitud_drop.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']; ?>')" alt="Cancelar solicitud" width="16" height="16" border="0"   />
          <?PHP  }
		  if ($datos['estado']==1){ 
		  
		  ?>
          <img src="../images/restaurar.png" onclick="location.replace('solicitud_asignar.php<?PHP  echo "?anio=".$datos['anio']."&id_solicitud=".$datos['id_solicitud']; ?>')" alt="Restaurar solicitud" width="16" height="16" border="0"   />
          <?PHP  } }?>
          </td>

          <td align="center"  class="segundalinea"> 
			<?PHP  echo $datos['id_solicitud']."/".$datos['abreviatura']."/".$datos['anio']; ?>		  </td>
          <td align="center"  class="segundalinea"> 
			<?PHP  echo date("d-m-Y",strtotime($datos["fecha_ingreso"])); ?>          </td>
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
										<form name="modificar" method="post" action="seguimiento_mod.php">
							 <td align="center" class="segundalinea">
							 <img src="../images/b_search.png" alt="Visualizar '.$data_seg['fecha'].'" width="16" height="16" border="0"  onclick="window.open(\'seguimiento_ver.php?anio='.$datos['anio'].'&id_solicitud='.$datos["id_solicitud"].'&id_seguimiento='.$dato_seg['id_seguimiento'].'&abreviatura='.$datos['abreviatura'].'\',\'miwin\',\'width=600,height=400,scrollbars=yes\')" />';
							 
							 
							$consulta= "SELECT id_perfil
										FROM `cat_perfil_seguimiento` 
										WHERE id_perfil=".$userLevel." AND id_seguimiento=".$dato_seg['id_seguimiento'];
					
							$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
							if (mysql_num_rows($sql_datos)>0 && $datos['estado']==0)
							 	echo '
										  	<input type="image" src="../images/b_edit.png" style="border:0 hidden " alt="Editar '.$dato_seg['descripcion'].'" />
											<input name="anio" type="hidden" value="'.$datos['anio'].'" />
											<input name="id_solicitud" type="hidden" value="'.$datos['id_solicitud'].'" />
											<input name="abreviatura" type="hidden" value="'.$datos['abreviatura'].'" />
										<input name="etapa" type="hidden" value="'.$dato_seg['id_seguimiento'].'" />
								';
								
							if (mysql_num_rows($sql_datos)>0 && $numero_seg	== $dato_seg['id_seguimiento'] && $datos['estado']==0)
							 	echo '
										  	<input type="image" src="../images/b_drop.png" style="border:0 hidden " alt="Eliminar '.$dato_seg['descripcion'].'" onclick="this.form.action=\'seguimiento_drop.php\'" />
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
 			<form name="agregar_seguimiento" method="post" action="seguimiento_add.php">         
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
                  <input type="image" src="../images/abook_add.gif" style="border:0 hidden " alt="Agregar seguimiento" />
              <?PHP  }else
                  		echo '<img src="../images/b_no.png" alt="NO tiene permido agregar seguimiento" />';
			  
			   ?>
              
              </td>
            <input name="anio" type="hidden" value="<?PHP  echo $datos['anio'] ?>" />
            <input name="id_solicitud" type="hidden" value="<?PHP  echo $datos['id_solicitud'] ?>" />
            <input name="abreviatura" type="hidden" value="<?PHP  echo $datos['abreviatura'] ?>" />
            </form>

			  </tr>

 <?PHP 

	}	
?>
<?PHP 
	if (!$existe){

?>

			  <tr class="aviso">

		      

          <td align="center" colspan="15"> No 

            hay solicitudes con esas caracter&iacute;sticas</font>            </td>
			  </tr>

<?PHP 

	}

	

	mysql_free_result($resultado);

	mysql_close($conexion);



?>
			  </table>

			 <br>

			 <table width="70%" >

			  <tr>

		      <td align="center"><img src="../images/buscar1.png" alt="Realizar nueva búsqueda" onclick="location.replace('<?PHP  echo $_SERVER['PHP_SELF'] ?>')" /></td>
		      <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

			  </tr>

			  </table><br>

			</td>

		  </tr>

	  </table>
 <?PHP  } ?>
</body>

</html>

