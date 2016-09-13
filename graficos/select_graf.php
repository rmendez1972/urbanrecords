<?PHP 

		$requiredUserLevel = array(1,2,3,4,5);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/config.php");

include ("../libreria/libreria.php");



// Conexion con la BD

$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);
mysql_query("set names 'utf8'",$conexion);

if (!isset($_POST['filtrar'])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<LINK href="../libreria/estilos.css" rel="stylesheet" type="text/css">
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
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
</head>

<body onLoad="ajustar()">
<div class="tituSec" style="margin-bottom:20px">Reportes gráficos</div>
 <form name="formulario" method="post" target="_blank"  action="constancias.php">
 <input name="filtrar" type="hidden" />

 <table width="680" align="center" class="tablaDatos">
 <thead>
   <th class="tituTab" colspan="2">Filtro de solicitudes</th>
   </thead>
   
   <tbody>
   <tr  >
     <td align="left"  >Reporte</td>
     <td align="left"  ><select name="reporte" size="1" onchange="activa()">
       <option value="municipio.php" >Constancias x Municipio</option>
       <option value="comp_anual.html" >Comparativo Anual</option>
     </select></td>
   </tr>
   <tr id="cantidades"  >
     <td align="left"  >Cantidades</td>
     <td align="left"  ><label>
       <input type="radio" name="tipo" value="false" checked="checked"  />
       Numeros</label>
       <label>
         <input type="radio" name="tipo" value="true" />
         Porcentaje</label></td>
   </tr>
   <tr  id="ranio" >
     <td align="left"  >A&ntilde;o</td>
     <td align="left"  ><select name="anio" size="1">
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
     <td align="left"  >Municipio</td>
     <td align="left"  ><select name="id_municipio" size="1">
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
     <td >Tipo de proyecto</td>
     <td ><select name="id_proyecto" size="1">
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
     <td width="20%" rowspan="2" >Periodo</td>
     <td width="80%"  >Inicio
       <input name="fecha1" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />
       <img src='../images/calendario.png' alt='calendario' name='calendario' id="calendario" onclick='showCalendar(this,formulario.fecha1, &quot;yyyy-mm-dd&quot;,null,1,-1,-1)' /></td>
   </tr>
   <tr id="fechas2" style="display:none">
     <td  >Final
       <input name="fecha2" type="text" size="15" maxlength="10" value="<?PHP  echo date('Y-m-d') ?>" readonly="readonly" />
       <img src='../images/calendario.png' alt='calendario' name='calendario' id="calendario" onclick='showCalendar(this,formulario.fecha2, &quot;yyyy-mm-dd&quot;,null,1,-1,-1)' /></td>
   </tr>
   <tr  id="rnumero" style="display:none">
     <td width="20%" >N&uacute;mero</td>
     <td width="80%" colspan="3"  ><input name="numero" type="text" size="20" maxlength="20" disabled="disabled" />
       <div id="rango" style="display:none"> al
         <input name="numero2" type="text" size="20" maxlength="20" disabled="disabled"  />
       </div></td>
   </tr>
   <tr  id="rdescripcion" style="display:none">
     <td width="20%" >Descripci&oacute;n</td>
     <td width="80%" colspan="3"  ><input name="descripcion" type="text" size="60" disabled="disabled" /></td>
   </tr>
   </tbody>
 </table>
 
<div class="botones" style="display:table; margin:auto; margin-top:15px;"><div class="btnAceptar" onclick="ejecuta(-1)">Aceptar</div><div class="btnCancelar" onclick="parent.cargar('libreria/principal.php')">Cancelar</div></div>

</form>

<?PHP  } ?>
</body>

</html>

