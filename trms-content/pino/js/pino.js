/*
 *	scripts for www.pinothebear.se for templates /trms-content/pino
 */

$(function() {
 	
 	$("#menuicon").click(function(){
 	
 		$("#navigate").toggle();
 	});
 	
 	$("#carticon").click(function(e){
 		
 		if($("#minicart").is(":visible"))
 			$("#minicart").slideUp("fast");
 		else{
 			$("#minicart-inner").load("/trms-content/ajax/order-minicart.php");
 			$("#minicart").slideDown("fast");
 		}
 	
 	});
 	
 	/*$(window).on("scroll", function(){
    	
 			$("#minicart").slideUp('fast');
 			$("#navigate").slideUp('fast');
 	
	});*/
	
 });
 
 
 	
