
function calendario(div,div2){
	calendarioF(div,div2,2010,9,3,1960,2010);
}
function cambio(div,div2,id,m,d,aniomin,aniomax){
	calendarioF(div,div2,document.getElementById(id).value,m,d,aniomin,aniomax);
}

//div = ID de la etiqueta DIV donde se va a cargar el calendario
//div2 = ID de la caja de texto donde se va a poner la fecha
//a = año en el que va a inicializarse el calendario
//m = mes en el que va a inicializarse el calendario
//d = dia en el que va a inicializarse el calendario
//aniomin = valor minimo de año
//aniomax = valor maximo de año
function calendarioF(div,div2,a,m,d,aniomin,aniomax){
	if(a>aniomax || a<aniomin)
		return;
		
	var divid=document.getElementById(div);
	var date=new fecha(a,m,d);
	var meses=new Array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	var dias=new Array("D","L","M","M","J","V","S");
	var i;
	
	var mes1,mes2,a1=date.anio,a2=date.anio;
	mes1=date.mes-1;
	mes2=date.mes+1;
	if(mes1<1){
		mes1=12;
		a1--;
	}
	if(mes2>12){
		mes2=1;
		a2++;
	}
	var datos="<table width='230' cellspacing='0' cellpadding='0' border=0 class='calTabla calendarioRT'><tr><td><table cellpadding='0' cellspacing='0' border='0' width='100%' class='calendarioRT'><tr class='calMes calendarioRT'><td width='20' class='calendarioRT'><div onmouseover=this.style.cursor='pointer' class='calendarioRT' onclick=calendarioF('"+div+"','"+div2+"',"+a1+","+mes1+","+date.dia+","+aniomin+","+aniomax+")>&lt;</div></td><td class='calendarioRT'>"+meses[date.mes]+" <select id='cmbaniocal' onchange=cambio('"+div+"','"+div2+"','cmbaniocal',"+date.mes+",1,"+aniomin+","+aniomax+")>";
	
	for(i=aniomin;i<=aniomax;i++){
		datos+="<option value='"+i+"'";
		if(i==date.anio)
			datos+=" selected='selected'";
		datos+=">"+i+"</option>";
	}
	
	datos+="</select><span class='btnCalCerrar' title='Cerrar' onclick=cerrarCal('"+div+"')>X</span></td><td width='20' class='calendarioRT'><div onmouseover=this.style.cursor='pointer' onclick=calendarioF('"+div+"','"+div2+"',"+a2+","+mes2+","+date.dia+","+aniomin+","+aniomax+")>&gt;</div></td></tr></table></td></tr><tr><td><table width='100%' border='0' class='calendarioRT'><tr class='calDias calendarioRT'>";
	for(i=0;i<7;i++)
		datos+="<td>"+dias[i]+"</td>";
	datos+="</tr><tr class='calNum'>";
	
	date=new fecha(date.anio,date.mes,1);
	
	if(date.dia==1&&date.diasemana!=0){
		for(i=0;i<date.diasemana;i++)
			datos+="<td class='nodia'>-</td>";
	}
	
	var df=date.meses[date.mes];
	var ds=date.diasemana;
	var j;
	
	for(i=1;i<=df;i++){
		datos+="<td class='diames calendarioRT' onclick=asignarDia('"+div+"','"+div2+"',"+i+","+date.mes+","+date.anio+")><div>"+i+"</div></td>";
		if(ds==6){
			datos+="</tr>"
			if(i<=df)
				datos+="<tr class='calNum calendarioRT'>";
		}
		if(i==df){
			if(ds!=6){
				for(j=ds;j<6;j++)
					datos+="<td>-</td>";
				datos+="</tr>";
			}
		}
		ds++;
		if(ds==7)
			ds=0;
	}
	
	datos+="</table></td></tr></table>";
	divid.innerHTML=datos;
	
	var pos=$("#"+div2).offset();
	divid.style.left=pos.left+"px";
	divid.style.top=pos.top+"px";
}
function cerrarCal(div){
	document.getElementById(div).innerHTML="";
}
function asignarDia(div,div2,d,m,a){
	var dia="";
	var mes="";
	if(d<10)
		dia="0"+d;
	else
		dia=""+d;
	if(m<10)
		mes="0"+m;
	else
		mes=""+m;
	
	//document.getElementById(div2).value=dia+"/"+mes+"/"+a;
	document.getElementById(div2).value=a+"-"+mes+"-"+dia;
	//if(opc==1)
		document.getElementById(div).innerHTML="";
}

//FECHA
function fecha(a,m,d){
	this.diasemana=1;
	this.meses=new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	if(a<1)
		a=1;
	this.anio=a;
	ajustarMeses(this);
	if(m<1||m>12)
		m=1;
	this.mes=m;
	if(d<1||d>this.meses[m])
		d=1;
	this.dia=d;
	calcular(this);
}
function ajustarMeses(obj){
	if(obj.anio%4==0)
		obj.meses[2]=29;
	else
		obj.meses[2]=28;
}
function sumarDias(obj,val){
	obj.dia+=val;
	while(obj.dia>obj.meses[obj.mes]||obj.dia<1){
		if(obj.dia<1){
			obj.mes-=1;
			if(obj.mes<1){
				obj.mes=12;
				obj.anio--;	
			}
			ajustarMeses(obj);
			obj.dia+=obj.meses[obj.mes];
		}
		else{
			obj.dia-=this.meses[obj.mes];
			obj.mes+=1;
			if(obj.mes>12){
				obj.mes=1;
				obj.anio++;
			}
			ajustarMeses(obj);
		}
	}
	calcular(obj);
}
function calcular(obj){
	var adia=27;
	var ames=11;
	var aanio=2009;
	var asem=4;
	
	var tanio=obj.anio;
	var ndias=0;
	var i;
	
	while(tanio!=aanio){
		if(tanio>aanio){
			tanio--;
			if(tanio%4==0)
				ndias-=366;
			else
				ndias-=365;
		}
		else{
			if(tanio%4==0)
				ndias+=366;
			else
				ndias+=365;
			tanio++;
		}
	}
		
	var adias=0;
	var meses2=new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	
	for(i=1;i<ames;i++)
		adias+=meses2[i];
	adias+=adia;
	
	var tmdias=0;
	for(i=1;i<obj.mes;i++)
		tmdias+=meses2[i];
	tmdias+=obj.dia;
	
	var difdias=adias-tmdias;
	ndias+=difdias-1;
	
	var tsem;
	var cont;
	
	if(obj.anio%4==0&&obj.mes>2)
		ndias--;
	
	if(ndias!=0){
		tsem=asem;
		cont=ndias;
		while(cont!=0){
			if(cont<0){
				tsem++;
				cont++;
			}
			else if(cont>0){
				tsem--;
				cont--;
			}
			if(tsem<0)
				tsem=6;
			if(tsem>6)
				tsem=0;
		}
		obj.diasemana=tsem;
	}
	else
		obj.diasemana=asem;
}