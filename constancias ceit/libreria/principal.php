<?PHP 
		$requiredUserLevel = array(1,2,3);
		$cfgProgDir =  '../';
		include('../seguridad/' . "secure.php");
?>
<?PHP 
include ("../libreria/config.php");
include ("../libreria/libreria.php");
include ("../libreria/encabezado.php");
?>
<p></p>

<table align="center">
<tr>
  <td class="primeralinea" colspan="4">
<?PHP  
		
		$fecha = "Chetumal, Quintana Roo a ".date("d"); 
		$mes = date("F");
		if ($mes=="January") $mes="Enero";
		if ($mes=="February") $mes="Febrero";
		if ($mes=="March") $mes="Marzo";
		if ($mes=="April") $mes="Abril";
		if ($mes=="May") $mes="Mayo";
		if ($mes=="June") $mes="Junio";
		if ($mes=="July") $mes="Julio";
		if ($mes=="August") $mes="Agosto";
		if ($mes=="September") $mes="Setiembre";
		if ($mes=="October") $mes="Octubre";
		if ($mes=="November") $mes="Noviembre";
		if ($mes=="December") $mes="Diciembre"; 
		$fecha .= " de ".$mes." ".date("Y");  
		echo $fecha
?> Sistema SCC V 1.0
  </td>
</tr>        
<tr>
  <td class="primeralinea" colspan="4">
  		Gobierno del Estado de Quintana Roo<br>
        Secretaria de Desarrollo Urbano<br>
        Departamento de Sistemas y Tecnolog&iacute;as de Informaci&oacute;n<br>
  </td>
</tr>        

</table>
</body>
</html>
