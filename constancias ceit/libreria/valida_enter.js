<!-- Hide JavaScript from older browsers



function enter(CampoSig){

	if(window.event && window.event.keyCode == 13)

		{

			CampoSig.focus();	

		  	return false; 

		}

	else

  		return true;	

}



/*************************************************************************/

/* Funci&oacute;n que hace que cada campo se oprima enter y pase al siguiente   */

/* también pasa al siguiente campo con el tab.                           */

/*************************************************************************/

function fn(form,field)

{

var next=0, found=false

var f=form

if(event.keyCode!=13) return;

for(var i=0;i<f.length;i++)	{

	if(field.name==f.item(i).name){

		next=i+1;

		found=true

		break;

	}

}

while(found){

	if( f.item(next).disabled==false &&  f.item(next).type!='hidden'){

		f.item(next).focus();

		break;

	}

	else{

		if(next<f.length-1)

			next=next+1;

		else

			break;

	}

}

}

/******************** Termina la Funci&oacute;n del Enter *************************/



//calcular la edad de una persona 

//recibe la clave de elector como un string en formato español 

//devuelve un entero con la edad. Devuelve false en caso de que la fecha sea incorrecta

function calcular_edad(cadena){ 



    //calculo la fecha de hoy 

    hoy=new Date(); 





    //compruebo que los ano, mes, dia son correctos 

    var ano; 

	ano = cadena.substr(6,2);

    if (isNaN(ano)) 

       return false; 



    var mes 

	mes = cadena.substr(8,2)

    if (isNaN(mes)) 

       return false 



    var dia 

	dia = cadena.substr(10,2)

    if (isNaN(dia)) 

       return false 





    //si el año de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4 

    if (ano<=99) 

	ano=parseFloat(ano)

      ano +=1900 

    //resto los años de las dos fechas 

    edad=hoy.getYear()- ano - 1; //-1 porque no se si ha cumplido años ya este año 

	

    //si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido 

    if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0 

       return edad 

    if (hoy.getMonth() + 1 - mes > 0) 

       return edad+1 



    //entonces es que eran iguales. miro los dias 

    //si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido 

    if (hoy.getUTCDate() - dia >= 0) 

       return edad + 1 

	   

    return edad 

} 



		var Dias_Mes = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);



		function Bisiesto(year) 

		{

			if ((year % 4 == 0) && (( year % 100 != 0) || (year % 400 ==0)))

			  return true;

			else

		  	return false;

		}

		

		function getDays(month, year) {

			if (month==2){

				if (Bisiesto(year)) 

				  Dias_Mes[1]=29;			 // solo para Febrero

				else

				  Dias_Mes[1]=28;

			}

			return Dias_Mes[month]

		}





//-->

