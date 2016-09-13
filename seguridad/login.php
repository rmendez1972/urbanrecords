<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Sistema de Control de Constancias :.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK href="<? echo $cfgProgDir; ?>libreria/estilos.css" rel="stylesheet" type="text/css">
<LINK href="<? echo $cfgProgDir; ?>css/main.css" rel="stylesheet" type="text/css">
</head>

<body style="background:url(<? echo $cfgProgDir; ?>images/fondo.jpg);">

<table border="0" cellpadding="0" cellspacing="0" width="990px" align="center">
<tr><td>
<div class="mainWrap" style="margin:auto; background-color:#FFF">

<img src="<? echo $cfgProgDir; ?>images/banner.png" width="990px">
<div style="width:100%; height:10px; background-color:#CC0000"></div>

<br><br><br><br><br>
<SCRIPT LANGUAGE="JavaScript">
<!--
//  ------ check form ------
function salir() {
	window.parent.close();
}
function checkData() {
	var f1 = document.forms[0];
	var wm = "<?PHP echo $strJSHello; ?>\n\r\n";
	var noerror = 1;

	// --- entered_login ---
	var t1 = f1.entered_login;
	if (t1.value == "" || t1.value == " ") {
		wm += "<?PHP echo $strLogin; ?>\r\n";
		noerror = 0;
	}

	// --- entered_password ---
	var t1 = f1.entered_password;
	if (t1.value == "" || t1.value == " ") {
		wm += "<?PHP echo $strPassword; ?>\r\n";
		noerror = 0;
	}

	// --- check if errors occurred ---
	if (noerror == 0) {
		alert(wm);
		return false;
	}
	else return true;
}
//-->
</SCRIPT>
<form action='<?PHP echo $documentLocation; ?>' METHOD="post" onSubmit="return checkData()">
	<TABLE align="center" width="402" height="248" border="0" CELLPADDING="0" CELLSPACING="0" BACKGROUND="<? echo $cfgProgDir; ?>seguridad/images/gestion.png">
  <tr> 
    <td align="center" colspan="2" >&nbsp;<br><br><br><br>
	</td>
  </tr>
  <tr> 
    <td align="right" width="150" ><span class="titulo1" >Usuario:</span></td>
    <td align="center" ><INPUT TYPE="text" NAME="entered_login" TABINDEX="1" ></td>
  </tr>
  <tr> 
    <td align="right" ><span class="titulo1" >Contrase&ntilde;a:</span></td>
    <td align="center" ><INPUT TYPE="password" NAME="entered_password" TABINDEX="2"></td>
  </tr>
  <tr> 
    <td align="center" colspan="2" >
	<table align="center" width="100%">
  <tr> 
    <td width="50%" height="20" align="center" >
    <INPUT TYPE=image style=" vertical-align:middle;  border:0 hidden " src="<? echo $cfgProgDir; ?>seguridad/images/aceptar1.png"  border=0 hspace=7 vspace=4 TABINDEX="3">
    </td>
    <td align="center" >
    <img src="<? echo $cfgProgDir; ?>seguridad/images/salir1.png" onMouseOver="" alt="Salir del Sistema" onClick="salir();">
    </td>
  </tr>
	</table>
	
	</td>
  </tr>  
</table>
</form>
            <?PHP
			// check for error messages
			if ($message) {
				echo '
					<script language="javascript">
					alert("'.$message.'");
					</script>
				';				
			} ?>
<SCRIPT LANGUAGE="JavaScript">
<!--
document.forms[0].entered_login.select();
document.forms[0].entered_login.focus();
//-->
</SCRIPT>
<p></p>
<br><br><br><br><br><br><br>
<div id="bottom"></div>
</div>
</td></tr>
</table>
</body>
</html>
