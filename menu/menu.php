<?PHP  

$_nivel="../";

//stm_bm

//[nombre,'','','imagen',0,'','','','alineacion-->0,1,2']

//FFFFF7 blanco como gris<br />

//FFFFFF azul tirando a moradito<br />

//FFFFFF azul<br />

//FFFFFF AZUL BAJO

//FFFFFF gris tirando a cafe



echo '



<script type="text/javascript" language="JavaScript1.2" src="'.$_nivel.'menu/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2">
<!--

stm_bm(["menu5264",430,"","blank.gif",0,"","",0,3,250,0,100,1,0,0,"","",0],this);

stm_sc(1,["transparent","transparent","","",3,3,0,0,"#FFFFFF","#FFFFFF","","",7,9,0,"","",7,9,0]);

stm_bp("p0",[0,4,0,0,3,1,16,0,100,"",-2,"",-2,90,0,0,"#FFFFFF","#FFFFFF","",3,1,1,"#FFFFFF"]);

stm_ai("p0i0",[0,"","","",-1,-1,0,"../libreria/principal.php","_self","","","../menu/inicio1.png","../menu/inicio2.png",90,30,0,"","",0,0,0,0,1,"#FFFFFF",0,"#FFFFFF",0,"","",3,3,1,1,"#FFFFFF","","#FFFFFF","#FFFFFF","bold 8pt Verdana","bold 8pt Verdana",0,0]);

stm_aix("p0i1","p0i0",[0,"","","",-1,-1,0,"","_self","","","../menu/constancias1.png","../menu/constancias2.png",90,30,0,"","",0,0,0,0,1,"#FFFFFF",0,"#FFFFFF",0,"","",3,3,1,1,"#FFFFFF","","#FFFFFF","#FFFFFF","8pt Verdana","8pt Verdana"]);

stm_bpx("p1","p0",[1,4,0,4,3,2,16,0,100,"",-2,"",-2,100,2,3,"#FFFFFF"]);

';

if ($userLevel==1 || $userLevel==2) {
	echo '
stm_aix("p1i0","p0i1",[0,"","","",-1,-1,0,"../solicitud/nuevo.php","_self","","","../menu/solicitudes1.png","../menu/solicitudes2.png"]);
	';
}
if ($userLevel==1 || $userLevel==4) {
	echo '
stm_aix("p1i0","p0i1",[0,"","","",-1,-1,0,"../solicitud/validar.php","_self","","","../menu/autorizar1.png", "../menu/autorizar2.png"]);
	';
}

echo '
stm_aix("p1i0","p0i1",[0,"","","",-1,-1,0,"../solicitud/seleccionarS.php","_self","","","../menu/seguimiento1.png","../menu/seguimiento2.png"]);


stm_ep();






stm_aix("p0i3","p0i1",[0,"","","",-1,-1,0,"","_self","","","../menu/reportes1.png","../menu/reportes2.png",90,30,0,"","",0,0,0,0,1,"#FFFFFF",0,"#FFFFFF",0,"","",3,3,1,1,"#FFFFFF","","#FFFFFF","#FFFFFF","8pt Verdana","8pt Verdana"]);

stm_bpx("p3","p1",[1,4,10,1,1,1,1]);


	stm_aix("p3i0","p1i0",[0,"","","",-1,-1,0,"'.$_nivel.'reportes/seleccionarn.php","_self","","","'.$_nivel.'menu/constancias1.png","'.$_nivel.'menu/constancias2.png"]);
	stm_aix("p3i0","p1i0",[0,"","","",-1,-1,0,"'.$_nivel.'graficos/select_graf.php","_self","","","'.$_nivel.'menu/graficos1.png","'.$_nivel.'menu/graficos2.png"]);
stm_ep();




stm_aix("p0i5","p0i1",[0,"","","",-1,-1,0,"","_self","","","../menu/administracion1.png","../menu/administracion2.png",90,30,0,"","",0,0,0,0,1,"#FFFFFF",0,"#FFFFFF",0,"","",3,3,1,1,"#FFFFFF","","#FFFFFF","#FFFFFF","8pt Verdana","8pt Verdana"]);



stm_bpx("p3","p1",[0,4,0,4,3,2,16,0,100,"",-2,"",-2,100,2,3,"#FFFFFF"]);



';


echo '
stm_aix("p3i2","p0i1",[0,"","","",-1,-1,0,"","_self","","","'.$_nivel.'menu/usuarios1.png","'.$_nivel.'menu/usuarios2.png",90,30]);



stm_bpx("p30","p1",[1,4,10,1,1,1,1]);

';

if ($userLevel==1){ 

	echo '

	stm_aix("p30i0","p1i0",[0,"","","",-1,-1,0,"'.$_nivel.	'usuarios/nuevo.php","_self","","","../menu/usercaptura1.png","../menu/usercaptura2.png"])

	';

	

	}

echo '

	stm_aix("p30i1","p1i0",[0,"","","",-1,-1,0,"'.$_nivel.	'usuarios/modificar.php","_self","","","../menu/usereditar1.png","../menu/usereditar2.png"]);

';

if ($userLevel==1){ 

	echo '

	stm_aix("p30i2","p1i0",[0,"","","",-1,-1,0,"'.$_nivel.	'usuarios/eliminar.php","_self","","","../menu/usereliminar1.png","../menu/usereliminar2.png"]);

	stm_aix("p30i3","p1i0",[0,"","","",-1,-1,0,"'.$_nivel.	'usuarios/consulta.php","_self","","","../menu/userconsulta1.png","../menu/userconsulta2.png"]);

	';

}

echo '



stm_ep();

';

if($userLevel==1){
	echo '
stm_aix("p3i2","p0i1",[0,"","","",-1,-1,0,"'.$_nivel.'configuracion/configuracion.php","_self","","","'.$_nivel.'menu/config1.png","'.$_nivel.'menu/config2.png",90,30]);



stm_bpx("p30","p1",[1,4,10,1,1,1,1]);

';

	echo 'stm_ep();';	
}

echo '



stm_ep();



stm_aix("p0i5","p0i1",[0,"","","",-1,-1,0,"'.$_nivel.'index.php","_parent","","","'.$_nivel.'menu/cerrarsesion1.png","'.$_nivel.'menu/cerrarsesion2.png"]);



stm_ep();



stm_em();



//-->



</script>';



?>



