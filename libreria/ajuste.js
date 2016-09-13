// JavaScript Document
$(document).ready(ajustador);
function ajustador(){
	parent.setFH();	
	setTimeout("ajustar()",50);
}
function ajustar(){
	var hei=$(document).height();
	parent.ajustar(hei+30);
}