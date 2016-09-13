<?PHP 

		$requiredUserLevel = array(1,3,6);

		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

include ("../libreria/ConexionPDO.php");
include ("../libreria/Utilidades.php");
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
<script src="../calendar/calendario.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/calendario.css">

<script>
function agregarPago(){
	if($("#oficio").val().length > 0 && $("#derechos").val().length > 0 && $("#letra").val().length > 0){
		var params=new Object();
		params.modulo="CONSTANCIAS";
		params.accion="AGREGAR_ORDEN";
		params.anio=$("#anio").val();
		params.id_solicitud=$("#id_solicitud").val();
		params.id_tipo_pago=$("#tipo").val();
		params.oficio=$("#oficio").val();
		params.derechos=$("#derechos").val();
		params.cantidadLetras=$("#letra").val();
		
		$.post("ControladorConst.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Orden de pago agregada");
				location.reload(true);
			}
			else
				parent.error(datos);
		},"html");
	}
	else
		parent.error("Llene todos los datos");
}

function eliminarPago(anio, sol, tipo, idorden){
	if(confirm("¿Está seguro que desea eliminar?")){
		var params=new Object();
		params.modulo="CONSTANCIAS";
		params.accion="ELIMINAR_ORDEN";
		params.anio=anio;
		params.id_solicitud=sol;
		params.id_tipo_pago=tipo;
		params.id_orden=idorden;
		
		$.post("ControladorConst.php",params,function(datos){
			if(datos=="ok"){
				parent.aviso("Orden de pago eliminada");
				location.reload(true);
			}
			else
				error(datos);
		},"html");
	}
}

function guardarPago(tmpid, anio, sol, tipo, idorden){
	var params=new Object();
	params.modulo="CONSTANCIAS";
	params.accion="ACTUALIZAR_ORDEN";
	params.anio=anio;
	params.id_solicitud=sol;
	params.id_tipo_pago=tipo;
	params.id_orden=idorden;
	params.oficio=$("#ed_"+tmpid+"_0").val();
	params.derechos=$("#ed_"+tmpid+"_1").val();
	params.cantidadLetras=$("#ed_"+tmpid+"_2").val();
	
	$.post("ControladorConst.php",params,function(datos){
		if(datos=="ok"){
			parent.aviso("Orden de pago actualizada");
			location.reload(true);
		}
		else
			parent.error(datos);
	},"html");
}

function editarPago(tmpid){
	var i;
	for(i=0;i<=3;i++){
		$("#ed_"+tmpid+"_"+i).addClass("editable");
		$("#ed_"+tmpid+"_"+i).removeAttr("readonly");
	}
}

function actualizarSeg(){
	var params=new Object();
	params.modulo="CONSTANCIAS";
	params.accion="ACTUALIZAR_SEGUIMIENTO";
	params.anio=$("#anio").val();
	params.id_solicitud=$("#id_solicitud").val();
	params.fecha=$("#fecha").val();
	params.observaciones=$("#observaciones").val();
	
	$.post("ControladorConst.php",params,function(datos){
		if(datos=="ok"){
			parent.aviso("Datos de seguimiento actualizados");
		}
		else
			parent.error(datos);
	},"html");
}
</script>

</head>

<body>
    <div id="calendario" style="position:absolute"></div>
<?php
// Si no ha sido enviado algo

if (isset($_GET['anio']) && isset($_GET['id_solicitud']) ){

Conexion::ejecutar("update solicitud_revisor SET estado=1 where anio=? and id_solicitud=?",array($_GET["anio"],$_GET["id_solicitud"]));

$datos=Conexion::ejecutarFila("select nombre_proyecto,direccion,fracciones,num_viviendas,superficie, id_solicitud,anio,id_municipio from solicitud WHERE anio=? AND id_solicitud=?",array($_GET['anio'],$_GET['id_solicitud']));

$seguimiento=Conexion::ejecutarFila("select observaciones,fecha from seguimiento WHERE anio=? AND id_solicitud=? AND id_seguimiento=4",array($_GET['anio'],$_GET['id_solicitud']));

if($seguimiento!=NULL && $seguimiento!="")
	$mostrar=true;
else
	$mostrar=false;
?>

</script>

 <form name="formulario" method="post" action="<?PHP  echo $_SERVER['PHP_SELF']."?anio=".$_GET['anio']."&id_solicitud=".$_GET['id_solicitud']."&abreviatura=".$_GET['abreviatura'] ?>">
 <div class="tituSec">Órdenes de pago</div>

 <table width="80%" class="tablaDatos" align="center">
   <tr >
     <td align="center"  class="segundalinea"> Nombre Proyecto</td>
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
     <td align="center"  class="segundalinea"><?PHP  echo $datos['id_solicitud']."/".$_GET['abreviatura']."/".$datos['anio']; ?>
       <input name="municipio" type="hidden" value="<?PHP  echo $n_roman[$datos['id_municipio']]; ?>" /></td>
     <td align="center"  class="segundalinea"><?PHP  echo $datos['direccion']; ?></td>
     <td align="center"  class="segundalinea"><?PHP  echo $datos['superficie']; ?>
       m<sup>2</sup></td>
     <td align="center"  class="segundalinea"><?PHP  echo $datos['num_viviendas']; ?></td>
   </tr>
 </table>
 <br />
 <div id="ordpago">
   <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
   	<thead>
         <tr class="tituTab">
           <th scope="col">Oficio No.</th>
           <th scope="col">Tipo de orden</th>
           <th scope="col">Cantidad ($)</th>
           <th scope="col">Cantidad en letra</th>
           <th scope="col">Acciones</th>
         </tr>
      </thead>
      <tbody>
      <?php
$res=Conexion::ejecutarConsulta("select CP.anio,CP.id_solicitud,CP.id_tipo_pago,CP.numero,TP.descripcion,CP.derechos,CP.cantidadLetras,CP.id_orden from constancias_pagos CP inner join cat_tipo_pago TP on CP.id_tipo_pago=TP.id_tipo_pago where CP.anio=? and CP.id_solicitud=?",array($_GET["anio"],$_GET["id_solicitud"]),PDO::FETCH_NUM);

$tmpid=0;
foreach($res as $row){
	echo "<tr style='text-align:center'><td><input id='ed_".$tmpid."_0' type='text' class='editinput' value='".$row[3]."' size='10' maxlength='5' readonly='readonly' /></td><td>".$row[4]."</td><td>$ <input id='ed_".$tmpid."_1' type='text' class='editinput' value='".$row[5]."' size='15' maxlength='25' readonly='readonly' /></td><td><textarea id='ed_".$tmpid."_2' class='editinput' cols='40' readonly='readonly' rows='2' />".$row[6]."</textarea></td><td><img src='../images/b_edit.png' class='imgBtnNo' title='Editar orden de pago' onclick='editarPago($tmpid)' /><img src='../images/save.png' class='imgBtnNo' title='Guardar cambios' width='22' onclick='guardarPago($tmpid,".($row[0].",".$row[1].",".$row[2]).",".$row[7].")' /><img src='../images/b_drop.png' class='imgBtnNo' title='Eliminar orden de pago' onclick='eliminarPago(".($row[0].",".$row[1].",".$row[2]).",".$row[7].")' /></td></tr>";
	
	$tmpid++;
}
	  ?>
         <tr style="text-align:center">
           <td scope="col">
             <input type="text" name="oficio" size="10" maxlength="5" style="text-align:right" id="oficio" />
           </td>
           <td scope="col"><select name="tipo" id="tipo">
           <?php
		   Utilidades::catalogo("cat_tipo_pago","id_tipo_pago");
		   ?>
           </select></td>
           <td scope="col">
             $
             <input type="text" name="derechos" maxlength="25" value="0"  style="text-align:right" id="derechos"/>
           </td>
           <td scope="col"><textarea name="letra" id="letra" cols="30" rows="3"></textarea></td>
           <td scope="col">
             <input name="agregar" type="button" class="btnOld" onclick="agregarPago();" value="Agregar pago" id="agregar" />
           </td>
         </tr>
      </tbody>
 </table>
   <br />
   <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaDatos">
     <thead>
     <tr class="tituTab">
       <th colspan="5" scope="col">Fecha y observaciones</th>
      </tr>
     </thead>
     
     <tbody>
     <tr style="text-align:center">
       <td scope="col">Fecha</td>
       <td scope="col"><span class="segundalinea">
         <input name="fecha" id="fecha" type="text" style="vertical-align:middle" size="15" maxlength="10" value="<?php echo $mostrar?$seguimiento["fecha"]:""; ?>" readonly="readonly" />
         <img src='../images/calendario.png' class="btnOld" style="vertical-align:middle" onclick="calendarioF('calendario','fecha',<? echo "$anioC,$mesC,$diaC,".(2008).",".($anioC); ?>);" /></span></td>
       <td scope="col">Observaciones</td>
       <td scope="col"><span class="segundalinea">
         <textarea name="observaciones" cols="40" rows="3" onblur="javascript:this.value=this.value.toUpperCase()" id="observaciones"><?php echo $mostrar?$seguimiento["observaciones"]:""; ?></textarea>
       </span></td>
       <td scope="col"><span class="segundalinea">
         <input name="asignar2" type="button" class="btnOld" onclick="actualizarSeg()" value="Guardar" />
       </span></td>
     </tr>
     </tbody>
   </table>
 </div>
<br />
 <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" >
   <tr>
     <td align="center"><input name="anio" type="hidden" id="anio" value="<?PHP echo $_GET["anio"]; ?>" />       <input name="id_solicitud" type="hidden" id="id_solicitud" value="<?PHP echo $_GET["id_solicitud"]; ?>" />       <img src="../images/terminar1.png" alt="Ir a inicio" class="btnOld" onclick="location.href='../solicitud/seleccionarS.php<?PHP echo "?backing=1&id_solicitud=".$_GET['id_solicitud']."&anio=".$_GET['anio']; ?>'" /></td>
   </tr>
 </table>

</form>

<?PHP 



}

?>
</body>

</html>

