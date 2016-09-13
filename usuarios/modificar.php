<?PHP 

// Verificar el acceso
		$requiredUserLevel = array(1,2,3,4);
		$cfgProgDir =  '../';

		include("../seguridad/secure.php");



include ("../libreria/config.php");
include ("../libreria/encabezado.php");
include ("../libreria/libreria.php");



if (isset($_POST['Usuario_add']) ){

// Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

   	$insertar= "UPDATE cat_usuario SET

					nombre='".$_POST['nombre']."',

					email='".$_POST['email']."',

					id_perfil=".$_POST['tipo'].",

					estatus='".$_POST['activo']."',
					
					iniciales='".$_POST["iniciales"]."'";

		if (isset($_POST['Password_add'])){

			$_POST['Password_add']= MD5($_POST['Password_add']);

			$insertar.=", password='".$_POST['Password_add']."'";

		}

		$insertar.=" WHERE login='".$_POST['Usuario_add']."'";

//		echo $insertar;



 	    mysql_query($insertar,$conexion) or die ("La consulta:<br>".$insertar."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

		

		mysql_close($conexion);

?>

		 <table width="70%" class="tabla1" align="center">

		  <tr > 

		    

    <td align="center" class="primeralinea"> Modificado</td>

		  </tr>

		  <tr>

		    <td align="center"> <br>

			 <table width="70%" class="tabla2">

			  <tr class="fuente2">

		      <td colspan=2 align="center" > 

				<p>  Se ha modificado los datos del usuario con &eacute;xito. 

              Si desea continuar modificando, presione el bot&oacute;n Editar. Si 

              desea terminar la operaci&oacute;n, presione el bot&oacute;n Terminar. </font> 

            </p>  

	    	  </td>

			  </tr>

			  </table><br /><table width="50%">

			  <tr>

		      <td align="center"><img src="../menu/usereditar1.png" alt="Editar otro usuario" onclick="location.replace('<?PHP  echo $_SERVER['PHP_SELF'] ?>')" /></td>

 		      <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

			  </tr>

			  </table><br>

    </td>

	

		  </tr>

	  </table>



<?PHP  

exit();

}

if (isset($_GET['modifica']) || ($userLevel>1)){

	if ($userLevel>1){

		$_GET['modifica']= $login;

	}

	// Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

	$consulta= "select * from cat_usuario where login='".$_GET['modifica']."'";

	$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	$datos_usuario= mysql_fetch_array($resultado);



?>

<script language="javascript" src="../libreria/md5.js"></script>

<script language="javascript">

<!--

function verifica(){

	var variable=document.formulario.tipo.value;


	if (document.formulario.cambiar.value==0){

		document.getElementById('pass').style.display = 'block';

		document.getElementById('pass2').style.display = 'block';

		document.getElementById('pass3').style.display = 'block';

		document.formulario.Password_add.disabled=false;

		document.formulario.Password_add2.disabled=false;

		document.formulario.Password_add_a.disabled=false;

		document.formulario.cambiar.value=1;

	}else{

		document.getElementById('pass').style.display = 'none';

		document.getElementById('pass2').style.display = 'none';

		document.getElementById('pass3').style.display = 'none';

		document.formulario.Password_add.disabled=true; 

		document.formulario.Password_add2.disabled=true;

		document.formulario.Password_add_a.disabled=true;

		document.formulario.cambiar.value=0;

	}

}



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

		if (document.formulario.cambiar.value==1){

			nombrecampo="Contraseña\nDebe de ser mayor a 6 caracteres";
	
			cad=formulario.Password_add.value;
	
			if (cad.length<6 ){
	
				correcto=false;
	
			}else{
	
				correcto=true;
	
			}
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
		if (document.formulario.cambiar.value==1){

			nombrecampo="Contraseñas\nLas contraseñas ingresadas no coinciden";
	
			cad=formulario.Password_add.value;
	
			cad2=formulario.Password_add2.value;
	
			if (cad!=cad2){
	
				correcto=false;
	
			}else{
	
				correcto=true;
	
			}
		}else{

			correcto=true;

		}

		break;

	case 6:

		if (document.formulario.cambiar.value==1){
			nombrecampo="Contraseña anterior\nLa contraseña ingresada no coincide con la del sistema";
	
			cad="<?PHP   echo $datos_usuario['password']; ?>";
	
			cad2=hex_md5(formulario.Password_add_a.value);
	
			if (cad!=cad2){
	
				correcto=false;
				
				document.forms.formulario.target="_parent";
				document.forms.formulario.action="index.php";
				document.forms.formulario.submit( );
	
			}else{
	
				correcto=true;
	
			}
		}else{

			correcto=true;

		}

		break;

		

	case -1:

		correcto2=true;

		for(var i=1;i<7;i++){

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

<form action="<?PHP  echo $_SERVER['PHP_SELF'] ?>" method="post" name="formulario">

<table width="90%" class="tabla1" align="center">

		  <tr class="fuente2"> 

    <td align="center" class="td1">Modificar datos 

      del Usuario</td>

		  </tr>

		  <tr>

		    <td align="center"><br>

			 

              <table width="90%" class="tabla2">

                <tr >

                  <td colspan=4  align="center" class="primeralinea" >Ingrese los siguientes datos:</td>

                <tr >

                  <td  class="segundalinea" >Nombre Usuario:</td>

                  <td class="segundalinea"  ><input type="text"  name="nombre" size="70" value="<?PHP  echo $datos_usuario['nombre'] ?>"  maxlength="140" onblur="javascript:this.value=this.value.toUpperCase()"  /<?php if($userLevel!=1) echo 'readonly=""'; ?>>
					</td>
	
                </tr>

                <tr >
                  <td class="segundalinea"  >Iniciales</td>
                  <td colspan="3" class="segundalinea"  ><input name="iniciales" type="text" id="iniciales" value="<?PHP  echo $datos_usuario['iniciales'] ?>" size="6" maxlength="5" /></td>
                </tr>
                <tr >

                  <td class="segundalinea"  >Correo Electr&oacute;nico:</td>

                  <td colspan="3" class="segundalinea"  ><input type="text"  name="email" size="40" value="<?PHP  echo $datos_usuario['email'] ?>"  maxlength="40"></td>

                </tr>

                <tr >

                  <td width="40%" class="segundalinea"  >Usuario(login):</td>

                  <td width="60%" colspan="3" class="segundalinea" >

                    <input type="text"  name="Usuario_add" size="24" value="<?PHP  echo $datos_usuario['login'] ?>" maxlength="15" readonly="">

                  </td>

                </tr>

                <tr >

                  <td  colspan="4" align="center" class="segundalinea" >

                      <input type="checkbox" name="cambiar" value="0" onClick="verifica();">

                            Cambiar contraseña</td>

                </tr>

                <tr id="pass" style="display:none" >

                  <td  width="40%" class="segundalinea" ><span class="fuente2">Contrase&ntilde;a nueva:</span></td>

                  <td  width="60%" colspan="3" class="segundalinea" >

                      <input type="Password"  name="Password_add" size="24"  maxlength="15" disabled>

					  <span class="aviso">Seis caracteres como m&iacute;nimo</span>

                      </td>

                </tr>

                <tr id="pass2" style="display:none" >

                  <td  width="40%" class="segundalinea" ><span class="fuente2">Reescriba Contrase&ntilde;a:</span></td>

                  <td  width="60%" colspan="3" class="segundalinea" >

                      <input type="Password"  name="Password_add2" size="24"  maxlength="15" disabled></td>

                </tr>

                <tr id="pass3" style="display:none" >

                  <td  width="40%" class="segundalinea" ><span class="fuente2">Contrase&ntilde;a anterior:</span></td>

                  <td  width="60%" colspan="3" class="segundalinea" >

                      <input type="Password"  name="Password_add_a" size="24"  maxlength="15" disabled></td>

                </tr>

                <tr >

                  <td  class="segundalinea" >Tipo de usuario:</td>

                  <td colspan="3" class="segundalinea"  >
                  
                  
                  
                  <select name="tipo" size="1"> 
                    <?PHP   
					    if($userLevel==1) 
		                    echo '<option value="">Seleccione...</option>';
						$consulta= "select * from cat_perfiles";
					    if($userLevel!=1) {
							$consulta.= " WHERE id_perfil=".$datos_usuario['id_perfil'];
						}

					$consulta.= " ORDER BY id_perfil";

					$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

					while($perfiles= mysql_fetch_array($resultado)){
						if ($datos_usuario['id_perfil']==$perfiles['id_perfil'])
							echo '<option value="'.$perfiles['id_perfil'].'" selected>'.$perfiles['descripcion'].'</option>';
						else
							echo '<option value="'.$perfiles['id_perfil'].'">'.$perfiles['descripcion'].'</option>';
					} //fin while

				?>
                  </select></td>

                </tr>


                <tr >

                  <td class="segundalinea"  >Estatus:</td>

                  <td colspan="3" class="segundalinea"  >

                      <input name="activo" type="radio" value="1" <?PHP  if ($datos_usuario['estatus']==1) echo "checked"; ?>>

      Activado

      <input name="activo" type="radio" value="0" <?PHP  if ($datos_usuario['estatus']==0) echo "checked"; ?>>

      Desactivado </td>

                </tr>

              </table>

              <br>

			<table width="70%" >

			  <tr>

			    <td align="center"><img src="../images/aceptar1.png" alt="Guardar datos usuario"  onclick="ejecuta(-1);" /></td>

 		        <td align="center"><img src="../images/cancelar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>
			  </tr>
			  </table>

        <br>

			</td>

		  </tr>

	  </table>

</form>	  

<?PHP 

exit();

}

// Conexion con la BD

	$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

		$consulta= "select login,nombre from cat_usuario";

 	if ($userLevel!=1) 

		$consulta.= " where login='".$login."'";

	$consulta.= " order by login";

		

	$resultado=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");

	$existe= mysql_num_rows($resultado);

?>

		 

<table width="70%" class="tabla1" align="center">

  <tr >

    <td align="center" class="primeralinea" >Usuarios del Sistema</td>

  </tr>

  <tr> 

    <td align="center"><br> 

	<table width="70%" class="tabla2" >

        <tr class="fuente2">

          <td align="center"  > Presione sobre 

            el usuario que desee modificar. </td>

        </tr>

        <?PHP 

	while ($usuarios= mysql_fetch_array($resultado)){

?>

        <tr >

          <td align="center" class="segundalinea" >  <?PHP  echo "<a href='".$_SERVER['PHP_SELF']."?modifica=".$usuarios['login']."'>".$usuarios['login']."-->".$usuarios['nombre']."</a>" ?> 

          </td>

        </tr>

        <?PHP 

	}	

	if (!$existe){

?>

        <tr class="aviso">

          <td align="center"  > No hay usuarios 

            para visualizar</font></td>

        </tr>

        <?PHP 

	}

	

	mysql_free_result($resultado);

	mysql_close($conexion);



?>

      </table>

      <br> <table width="70%" >

        <tr> 

          <td align="center"><img src="../images/terminar1.png" alt="Ir a inicio" <?PHP  echo $central; ?> /></td>

        </tr>

      </table>

      <br>

    </td>

  </tr>

</table>

</body>

</html>

