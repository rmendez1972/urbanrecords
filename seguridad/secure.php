<?php
//$requiredUserLevel = array(1,2,3,4,5,6,7);

session_start();

$userLevel=-1;
$login="";
$pasa=false;

if(isset($_SESSION["user_constancia"]) && isset($_SESSION["user_profile"])){
	foreach($requiredUserLevel as $lev){
		if($lev==$_SESSION["user_profile"]){
			$pasa=true;
			$userLevel=$_SESSION["user_profile"];
			$login=$_SESSION["user_constancia"];
		}
	}
}

if(!$pasa){
	echo "<script>parent.location.href='login.php';</script>";
	exit;
}
?>
