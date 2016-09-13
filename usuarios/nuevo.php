<?PHP 
		$requiredUserLevel = array(1);
		$cfgProgDir =  '../';

		include("../seguridad/secure.php");

		include ("../libreria/config.php");

		include ("../libreria/encabezado.php");

		include ("../libreria/libreria.php"); 



?>



<script language="javascript">

<!--

function ejecuta(valor){



var correcto;

var cad,nombrecampo;



switch(valor){



	case 1:

		nombrecampo="Nombre de Usuario\nDebe de ser mayor a 6 caracteres";

		cad=formulario.Usuario_add.value;

		if (cad.length<6){

			correcto=false;

		}else{

		correcto=true;

		}

		break;

	case 2:

		nombrecampo="Contraseña\nDebe de ser mayor a 6 caracteres";

		cad=formulario.Password_add.value;

		if (cad.length<6){

			correcto=false;

		}else{

		correcto=true;

		}

		break;

	case 3:

		nombrecampo="Tipo de usuario\nSeleccione el tipo";

		cad=formulario.tipo.value;

		if (cad==""){

			correcto=false;

		}else{

		correcto=true;

		}

		break;

	case 4:


		correcto=true;
		break;

	case 5:

		nombrecampo="Contraseñas\nLas contraseñas ingresadas no coinciden";

		cad=formulario.Password_add.value;

		cad2=formulario.Password_add2.value;

		if (cad!=cad2){

			correcto=false;

		}else{

			correcto=true;

		}

		break;

	case -1:

		correcto2=true;

		for(var i=1;i<6;i++){

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

		document.forms.formulario.submit( );

	}	



}//else



return correcto;

}//function



//-->

</script>

<?PHP 

if (!isset($_POST['Usuario_add'])&& !isset($_POST['Password_add']) ){


	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);



?>

<form method="POST" action="" name="formulario">

<table width="90%" cellpadding="0" cellspacing="0" align="center" class="tabla1">

  <tr class="fuente2">

    <td align=center class="td1">Crear Usuario de Sistema</td>

  </tr>

<tr>

<td align=center><br>

<table width="90%" border="0" align="center" cellpadding="3" class="tabla2">

          <tr > 

            <td colspan=4  align="center" class="primeralinea" >Ingrese 

              los siguientes datos:</td>
          <tr > 

            <td class="segundalinea"  >Nombre Completo:</td>

            <td class="segundalinea"  ><input type="text"  name="nombre" size="70"  maxlength="140" onblur="javascript:this.value=this.value.toUpperCase()"></td>

          </tr>

          <tr >
            <td class="segundalinea"  >Iniciales</td>
            <td colspan="3" class="segundalinea" ><input name="iniciales" type="text" id="iniciales" size="6" maxlength="5" /></td>
          </tr>
          <tr > 

            <td class="segundalinea"  >Correo Electr&oacute;nico:</td>

            <td colspan="3" class="segundalinea" ><input type="text"  name="email" size="40"  maxlength="40"></td>
          </tr>

          <tr > 

            <td width="40%" class="segundalinea"  >Usuario(login):</td>

            <td width="60%" colspan="3" class="segundalinea" > <input type="text"  name="Usuario_add" size="24"  maxlength="15">            </td>
          </tr>

          <tr > 

            <td  width="40%" class="segundalinea"  >Contraseña:</td>

            <td  width="60%" colspan="3" class="segundalinea" > <input type="Password"  name="Password_add" size="24"  maxlength="15"> 

              <br> Seis caracteres como m&iacute;nimo</td>
          </tr>

          <tr >

            <td class="segundalinea"  >Reescriba Contrase&ntilde;a:</td>

            <td colspan="3" class="segundalinea" ><input type="password"  name="Password_add2" size="24"  maxlength="15" />

                <br />

              Seis caracteres como m&iacute;nimo</td>
          </tr>

          <tr > 

            <td class="segundalinea"  >Tipo de usuario:</td>

            <td colspan="3" class="segundalinea"  ><select name="tipo" size="1">
            		<option value="">Seleccione...</option>
              <?PHP   $consulta= "select * from cat_perfiles";
					$consulta.= " ORDER BY id_perfil";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					while($perfiles= mysql_fetch_array($resultado)){
						echo '<option value="'.$perfiles['id_perfil'].'">'.$perfiles['descripcion'].'</option>';
					} //fin while

				?>
            </select></td>
          </tr>

          <tr > 

            <td class="segundalinea"  >Estatus:</td>

            <td colspan="3" class="segundalinea"  >

              <input name="activo" type="radio" value="1" checked>

            Activado <input name="activo" type="radio" value="0">Desactivado </td>
          </tr>
        </table><br />
<table width="70%" cellpadding="0" cellspacing="0" align="center" class="tabla1">

  <tr >

    <td align=center ><img src="../images/aceptar1.png" alt="Guardar usuario"  onclick="ejecuta(-1);" /></td>
    <td align=center ><img src="../images/cancelar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>
  </tr>
</table>
      <br>
      <br>

	<br>

</td>

</tr>

</table>

  </form>

 <?PHP 

 }else{

 

// Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

	$_POST['Password_add']= MD5($_POST['Password_add']);


	

	//verifica si existe el usuario

	$verifica=mysql_query("SELECT count(login) FROM cat_usuario WHERE login='".$_POST['Usuario_add']."'",$conexion);

	$n=mysql_result($verifica,0);

	if($n==0) //se agrega el usuario si no existe

   	{   $insertar= "insert into cat_usuario 

					values ('".$_POST['nombre']."',

					'".$_POST['Usuario_add']."','".$_POST['Password_add']."',

					'".$_POST['email']."',".$_POST['tipo'].",

					'0','".$_POST['activo']."','".$_POST["iniciales"]."')";

 	    mysql_query($insertar,$conexion) or die ("La consulta:<br>".$insertar."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		

		mysql_close($conexion);

   ?>

	 <table width="70%" class="tabla1" align="center">

	 <tr class="fuente2"> <td align="center" class="td1"> Se agreg&oacute; 

     		 con éxito el nuevo usuario</font> </td>

		  </tr>

		  <tr>

		    <td height="240" align="center"><table width="70%" class="tabla1" >

        <tr > 

          <td colspan=2 align="center" class="segundalinea" >

 Si desea agregar otro usuario presione el bot&oacute;n Capturar. Si 

              desea terminar la operaci&oacute;n presione Terminar.

            </td>

        <tr> 

          <td colspan=2 align="center" > <br> </td>

        <tr> </tr>

        <tr> 

          <td align="center"><img src="../menu/usercaptura1.png" alt="Capturar usuario" onClick="location.replace('<?PHP  echo $_SERVER['PHP_SELF'] ?>')" /></td>

          <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

        </tr>

      </table> 

      <br>

    </td>

		  </tr>

	  </table>

<?PHP  } // fin de agregar con exito

   else 

   { // imprimo mensaje de ERROR

?>

   <table width="70%" class="tabla1" align="center">

    <tr > 

      <td align="center" class="primeralinea" > Error </td>

    </tr>

   <tr> 

    <td align="center"> <br> <table width="70%"  >

        <tr class="aviso"> 

          <td colspan=2 align="center" > <p> El 

              Nombre de usuario <?PHP  echo "<font color='#003366'><strong>".$_POST['Usuario_add']." </strong></font>  "; ?>  

              ya existe. Si desea intentar de nuevo, presione el bot&oacute;n Nuevo. Si 

              desea terminar la operaci&oacute;n, presione el bot&oacute;n Terminar. </p></td>

        <tr> 

          <td colspan=2 align="center" > <br> </td>

        <tr> </tr>

        <tr> 

          <td align="center"><img src="../menu/usercaptura1.png" alt="Capturar usuario" onclick="location.replace('<?PHP  echo $_SERVER['PHP_SELF'] ?>')" /></td>

          <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

        </tr>

      </table>

      <br> </td>

  </tr>

</table>

  <?PHP 

 }// fin del mensaje de ERROR

}

 ?>

</body>

</html>