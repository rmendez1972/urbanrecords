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
<script src="../libreria/jquery-1.7.js"></script>
<script src="../libreria/ajuste.js"></script>
<script src="../calendar/calendario.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/calendario.css">
<SCRIPT language="javascript" src="../libreria/validacion_entero.js"></SCRIPT>
<SCRIPT language="javascript" src="../libreria/popcalendar.js"></SCRIPT>

<SCRIPT language="javascript">

<!--

function activa(){
	if (document.formulario.tipo[0].checked){
		//document.getElementById('ranio').style.display='block';
		$("#repGeneral").fadeIn();
		//document.getElementById('rmunicipio').style.display='block';
		//document.getElementById('rtipo').style.display='block';
		$("#repNum").hide();
		//document.getElementById('rnumero').style.display='none';
		$("#repProp").hide();
		$("#rango").hide();
		//document.getElementById('rango').style.display='none';
		//document.getElementById('rdescripcion').style.display='none';
		document.formulario.id_municipio.disabled=false; 
		document.formulario.id_proyecto.disabled=false; 
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	}
	if (document.formulario.tipo[1].checked || document.formulario.tipo[4].checked){
		//document.getElementById('ranio').style.display='block';
		$("#repGeneral").hide();
		//document.getElementById('rmunicipio').style.display='none';
		//document.getElementById('rtipo').style.display='none';
		$("#repNum").fadeIn();
		//document.getElementById('rnumero').style.display='block';
		$("#repProp").hide();
		
		if(document.formulario.tipo[1].checked)
			$("#rango").hide();
		//document.getElementById('rango').style.display='none';
		//document.getElementById('rdescripcion').style.display='none';
		document.formulario.id_municipio.disabled=true; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.act_fecha[1].checked=true;
		document.formulario.numero.disabled=false;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=true;
	
	}
	if (document.formulario.tipo[2].checked || document.formulario.tipo[3].checked){
		//document.getElementById('ranio').style.display='block';
		$("#repGeneral").hide();
		//document.getElementById('rmunicipio').style.display='none';
		//document.getElementById('rtipo').style.display='none';
		$("#repNum").hide();
		//document.getElementById('rnumero').style.display='none';
		$("#repProp").fadeIn();
		document.getElementById('rango').style.display='none';
		//document.getElementById('rdescripcion').style.display='block';
		document.formulario.id_municipio.disabled=true; 
		document.formulario.id_proyecto.disabled=true; 
		document.formulario.act_fecha[1].checked=true;
		document.formulario.numero.disabled=true;
		document.formulario.numero2.disabled=true;
		document.formulario.descripcion.disabled=false;
	}
	if (document.formulario.tipo[4].checked){
		$("#repGeneral").hide();
		//$("#repNum").hide();
		$("#repProp").hide();
		$("#rango").fadeIn();
		//document.getElementById('rango').style.display='block';
		document.formulario.numero2.disabled=false;
	}
	
	if (document.formulario.act_fecha[0].checked){
		$("#divPeriodo").fadeIn();
		//document.getElementById('fechas1').style.display='block';
		//document.getElementById('fechas2').style.display='block';
		document.formulario.fecha1.disabled=false; 
		document.formulario.fecha2.disabled=false;
	}else{
		$("#divPeriodo").fadeOut();
		//document.getElementById('fechas1').style.display='none';
		//document.getElementById('fechas2').style.display='none';
		document.formulario.fecha1.disabled=true; 
		document.formulario.fecha2.disabled=true;
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
		document.forms.formulario.action=document.formulario.reporte.options[document.formulario.reporte.selectedIndex].value;
		document.forms.formulario.filtrar.value="OK";
		document.forms.formulario.submit( );

	}	



}//else



return correcto;

}//function



//-->

</script>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body onLoad="activa();">
<div id="calendario" style="position:absolute; z-index:4"></div>
<div class="tituSec">Reporte de constancias</div>
<form name="formulario" method="post" target="_blank"  action="<? echo $_SERVER['PHP_SELF']; ?>">

		<table width="800" align="center" class="tablaDatos" style="margin-top:20px">
        
        <thead>
        <th colspan="2" align="center"  class="tituTab">Filtro de solicitudes 
        </th>
          <tbody>
		  <tr class="regTab">
            <td align="left">Reporte</td>
		    <td align="left">
            	<select name="reporte" size="1">
                	<option value="constancia.php" >Datos de las Fichas</option>
                	<option value="ingresos.php" >Ingresos</option>
                	<option value="constancias.php" >Constancias</option>
		      	</select>
                
            </td>
	      </tr>
		  <tr class="regTab">
            <td rowspan="2" align="left">Tipo reporte</td>
		    <td align="left"><label>
              <input type="radio" name="tipo" value="0" checked="checked" onClick="activa()" />
		      General</label>
                <label>
                <input type="radio" name="tipo" value="1" onClick="activa()" />
                N&uacute;mero                </label>
                <label>
                <input type="radio" name="tipo" value="2" onClick="activa()" />
                Propietario/Proyecto                </label>                  
                <label style="display:none">
                <input type="radio" name="tipo" value="3" onClick="activa()" />
                Proyecto                </label>			
                <label>
                <input type="radio" name="tipo" value="4" onClick="activa()" />
                Rango</label></td>
	      </tr>
		  <tr class="regTab">
		    <td align="left" style="position:relative"><label>Periodo
		      <input type="radio" name="act_fecha" value="0" checked="checked" onClick="activa()" />
		      SI</label>
              <label>
                <input type="radio" name="act_fecha" value="0" onClick="activa()" />
            NO</label>
            
            <div id="divPeriodo" style="position:absolute; top:0px; right:0px">
            Inicio <input name="fecha1" type="text" id="fecha1" value="<?PHP  echo date('Y-m-d') ?>" size="15" maxlength="10" readonly="readonly" />
              <img src='../images/calendario.png' alt='calendario' style="cursor:pointer" name='calendario' id="calendario2" onclick="calendarioF('calendario','fecha1',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" height="22" />
              
              - Final <input name="fecha2" type="text" id="fecha2" value="<?PHP  echo date('Y-m-d') ?>" size="15" maxlength="10" readonly="readonly" />
              <img src='../images/calendario.png' alt='calendario' style="cursor:pointer" name='calendario' id="calendario3" onclick="calendarioF('calendario','fecha2',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" height="22" />
  </div>
  
            </td>
	      </tr>
           <tr id="ranio" class="regTab">
             <td align="left">A&ntilde;o</td>
             <td align="left"><select name="anio" size="1">
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
          </tbody>
</table>
        
        <div id="repGeneral" style="margin-top:20px">
		<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
		  <thead>
          <tr class="tituTab">
		    <th colspan="2" scope="col">Reporte general</th>
	      </tr>
          </thead>
          
          <tbody>
		  <tr class="regTab">
		    <td>Municipio</td>
		    <td><select name="id_municipio" size="1">
		      <?PHP   $consulta= "select * from cat_municipios";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="Todos">Todos...</option>';

					while($municipios= mysql_fetch_array($resultado)){
						echo '<option value="'.$municipios['id_municipio'].'">'.$municipios['descripcion'].'</option>';

					} //fin while

				?>
		      </select></td>
	      </tr>
		  <tr class="regTab">
		    <td>Tipo de proyecto</td>
		    <td><select name="id_proyecto" size="1">
		      <?PHP   $consulta= "select * from cat_tipo_proy";


					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					echo '<option value="Todos">Todos...</option>';

					while($tipos= mysql_fetch_array($resultado)){
							echo '<option value="'.$tipos['id_tipo'].'">'.$tipos['descripcion'].'</option>';

					} //fin while

				?>
		      </select></td>
	      </tr>
          </tbody>
	    </table>
  </div>
        
        <div id="repNum" style="margin-top:20px">
        <table width="350" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
          <tr class="tituTab">
            <th colspan="2" scope="col">Reporte por número</th>
          </tr>
          <tr class="regTab">
            <td>N&uacute;mero</td>
            <td><input name="numero" type="text" size="20" maxlength="20" disabled="disabled" /><div id="rango"> al<br />
              <input name="numero2" type="text" size="20" maxlength="20" disabled="disabled"  />
                </div></td>
          </tr>
        </table>
  </div>
        
        <div id="repProp" style="margin-top:20px">
        <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
          <thead>
          <tr class="tituTab">
            <th colspan="2" scope="col">Reporte por propietario/proyecto</th>
          </tr>
          </thead>
          <tbody>
          <tr class="regTab">
            <td>Descripci&oacute;n</td>
            <td><input name="descripcion" type="text" size="60" disabled="disabled" /></td>
          </tr>
          </tbody>
        </table>
  </div>
        
<input name="filtrar" type="hidden" />
		<br />
<div class="botones" style="display:table; margin:auto; margin-top:15px;"><div class="btnAceptar" onclick="ejecuta(-1)">Aceptar</div><div class="btnCancelar" onclick="parent.cargar('libreria/principal.php')">Cancelar</div></div>

</form>
<?PHP  } ?>
</body>

</html>

