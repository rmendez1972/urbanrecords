<?php
if(isset($_GET["lat"]) && isset($_GET["lon"])){
	$lat=$_GET["lat"];
	$lon=$_GET["lon"];
}
else
	exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body style="margin:0; background-color:#FFF">
<iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com.mx/?q=<? echo $lat; ?>,<? echo $lon; ?>&amp;ie=UTF8&amp;t=h&amp;ll=<? echo $lat; ?>,<? echo $lon; ?>&amp;spn=0.002442,0.003433&amp;z=18&amp;output=embed"></iframe>
</body>
</html>