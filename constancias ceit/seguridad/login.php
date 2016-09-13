<?PHP
//  ------ create table variable ------
// variables for Netscape Navigator 3 & 4 are +4 for compensation of render errors
$Browser_Type  =  strtok($HTTP_ENV_VARS['HTTP_USER_AGENT'],  "/");
if ( ereg( "MSIE", $HTTP_ENV_VARS['HTTP_USER_AGENT']) || ereg( "Mozilla/5.0", $HTTP_ENV_VARS['HTTP_USER_AGENT']) || ereg ("Opera/5.11", $HTTP_ENV_VARS['HTTP_USER_AGENT']) ) {
	$theTable = 'WIDTH="400" HEIGHT="245"';
} else {
	$theTable = 'WIDTH="404" HEIGHT="249"';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Sistema de Control de Constancias :.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK href="<? echo $cfgProgDir; ?>libreria/estilos.css" rel="stylesheet" type="text/css">
</head>
<body> 
<div id="Layer1" style="position:absolute; width:100%; height:244px; z-index:1; left: 0; top: 0;"><img src="<? echo $cfgProgDir; ?>images/banner.png" width="102%" height="100%"></div>
<body >
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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
<div align="center">
	<TABLE <?PHP echo $theTable; ?> CELLPADDING="0" CELLSPACING="0" BACKGROUND="<? echo $cfgProgDir; ?>seguridad/images/gestion.png">
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
    <INPUT TYPE=image style=" vertical-align:middle;  border:0 hidden " src="<? echo $cfgProgDir; ?>seguridad/images/aceptar1.png"  border=0 hspace=7 vspace=4 alt="<?PHP echo $strEnter; ?>   &gt;&gt;&gt;" TABINDEX="3">
    </td>
    <td align="center" >
    <img src="<? echo $cfgProgDir; ?>seguridad/images/salir1.png" onMouseOver="" alt="Salir del Sistema" onClick="salir();">
    </td>
  </tr>
	</table>
	
	</td>
  </tr>  
</table>
</div>
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
</body>
</html>
