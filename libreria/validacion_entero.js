<!-- Hide JavaScript from older browsers


   


   // Determina si un caracter es un número


   function numero(car)


   {


   var numeros="0123456789";


   return (numeros.indexOf(car)>=0)


   }


   


   //Numeros incluido el punto decimal   


   function numerocondecimal(car)


   {


     var numerosconpunto="0123456789.";


	 return (numerosconpunto.indexOf(car)>=0)


   }








   // Determina si un carácter es un signo positivo o negativo


   function signo(car)


   {


   var signos="+-";


   return (signos.indexOf(car)>=0)


   }





    // Comprueba si el contenido es un número es natural incluido el 0


   function esnatural(contenido)


   {


   var car="";


   if (contenido.length == 0)


   	return false;


   for (var i=0; i<contenido.length;i++){


	car=contenido.charAt(i);


 	 if (!numero(car))


        		 return false;


   	}


      


   return true;


   }





   


//comprueba si es un numero con decimales


   function esnumerocondecimal(contenido)


   {


   var car="";


   if (contenido.length == 0)


    	return false;


   for (var i=0; i<contenido.length;i++){


	car=contenido.charAt(i);


 	 if (!numerocondecimal(car))


        		 return false;


   	}


      


   return true;


   }








   // Comprueba si un signo se encuetra en la posici&oacute;n correcta 


   function signocorrecto(contenido)


   {


   if (contenido.length == 0)


	 return false;


   else 


	//si el 1er car. No es un Nº y No es un signo: error


	 if (!esnatural(contenido.charAt(0)) && !signo(contenido.charAt(0)))


	         return false;


   return true;


   }





   // Comprueba si el contenido es un número es entero


   function esentero(contenido)


   { 


   if (!signocorrecto(contenido))    


     	return false;    


   else 


	return esnatural(contenido.substring(1,contenido.length));


   }//esentero





/* Funci&oacute;n para validar la direcci&oacute;n de e-mail */





function ValidarEmail(contenido){


    if( contenido.indexOf('@',0) <= 0  || contenido.indexOf(';',0) != -1


     || contenido.indexOf(' ',0) != -1 || contenido.indexOf('/',0) != -1


     || contenido.indexOf(';',0) != -1 || contenido.indexOf('<',0) != -1


     || contenido.indexOf('>',0) != -1 || contenido.indexOf('*',0) != -1


     || contenido.indexOf('|',0) != -1 || contenido.indexOf('`',0) != -1


     || contenido.indexOf('&',0) != -1 || contenido.indexOf('$',0) != -1


     || contenido.indexOf('!',0) != -1 || contenido.indexOf('"',0) != -1


     || contenido.indexOf(':',0) != -1 )


       { return false; }


    else return true;


   }








//-->





