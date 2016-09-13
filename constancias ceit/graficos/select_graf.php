<?PHP 

		$requiredUserLevel = array(1,2,3);

		$cfgProgDir =  '../';

//		include("../seguridad/secure.php");

include ("../libreria/config.php");

include ("../libreria/encabezado.php");

include ("../libreria/libreria.php");



// Conexion con la BD

$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

if (!isset($_POST['filtrar'])){
?>
<SCRIPT language="javascript" src="../libreria/validacion_entero.js"></SCRIPT>
<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function activa(){
	var destino=document.formulario.reporte.options[document.formulario.reporte.selectedIndex].value
	if (destino=="municipio.php"){
		document.getElementById('ranio').style.display='block';
		document.getElementById('cantidades').style.display='block';
		document.getElementById('rmunicipio').style.display='none';
		document.getElementById('rtipo').style.display='none';
		document.getElementById('fechas1').style.display='none';
		document.getElementById('fechas2').style.display='none';
		document.getElementById('rnumero').style.display='none';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='none';
		document.formulario.id_municipio.disabled=false; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.fecha1.disabled=true; 
		document.formulario.fecha2.disabled=true;
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	}
	if (destino=="comp_anual.html"){
		document.getElementById('ranio').style.display='block';
		document.getElementById('cantidades').style.display='none';
		
		document.getElementById('rmunicipio').style.display='none';
		document.getElementById('rtipo').style.display='none';
		document.getElementById('fechas1').style.display='none';
		document.getElementById('fechas2').style.display='none';
		document.getElementById('rnumero').style.display='none';
		document.getElementById('rango').style.display='none';
		document.getElementById('rdescripcion').style.display='none';
		document.formulario.id_municipio.disabled=false; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.fecha1.disabled=true; 
		document.formulario.fecha2.disabled=true;
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
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
		document.forms.formulario.action=document.formulario.reporte.options[document.formulario.reporte.selectedIndex].value;
		document.forms.formulario.submit( );

	}	



}//else



return correcto;

}//function



//-->

</script>

 <form name="formulario" method="post" target="_blank"  action="constancias.php">

 <table width="80%" class="tabla1" align="center">

  <tr class="fuente2"> 

  	  <td align="center"  class="primeralinea">Filtro de solicitudes </td>

  </tr>

  <tr>	<td align=center >

  		<br><br>

		<table width="95%" class="tabla2">
		  <tr  >
		    <td align="left" class="segundalinea" >Reporte</td>
		    <td align="left" class="segundalinea" ><select name="reporte" size="1" onchange="activa()">
            <option value="municipio.php" >Constancias x Municipio</option>
            <option value="comp_anual.html" >Comparativo Anual</option>
	        </select></td>
	      </tr>
		  <tr id="cantidades"  >
		    <td align="left" class="segundalinea" >Cantidades</td>
		    <td align="left" class="segundalinea" ><label>
		      <input type="radio" name="tipo" value="false" checked="checked"  />
		      Numeros</label>
		      <label>
		        <input type="radio" name="tipo" value="true" />
		        Porcentaje</label></td>
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
           <tr  id="rmunicipio" style="display:none">
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
		  <tr id="rtipo" style="display:none">

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

          <tr  id="fechas1" style="display:none"> 

            <td width="20%" rowspan="2" class="segundalinea">Periodo</td>

            <td width="80%"  class="segundalinea">Inicio<input name="fecha1" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />

            <img src='../images/calendario.png' alt='calendario' name='calendario' id="calendario" onclick='showCalendar(this,formulario.fecha1, &quot;yyyy-mm-dd&quot;,null,1,-1,-1)' /></td>
          </tr>

          <tr id="fechas2" style="display:none"> 

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

<?PHP  } ?>
</body>

</html>

