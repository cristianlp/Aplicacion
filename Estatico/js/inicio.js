function alerta () {
	var content = "";
	if(arguments.length >= 2){

		content = "<h3>"+arguments[0]+"</h3><p>"+arguments[1]+"</p>";
	}else{
		content= "<p>"+arguments[0]+"</p>";
	}

	var div = document.createElement("div");
	var claseDiv = "alerta";
	div.setAttribute("class", claseDiv);
	div.innerHTML = content;
	document.body.appendChild(div);
	setTimeout(function() {
		div.style.opacity = "0";
	}, arguments[2] || 10000);
	setTimeout(function() {
		document.body.removeChild(div);
	}, arguments[2]+1000 ||  11000);
}

	function shake(dom){
		var i = 0;
	    while (i < 2) {

		    $(dom).animate({
		        left: "10px",
		    }, 30);
		   
		    $(dom).animate({
		        left: "-10px",
		    }, 30);
		    
		    $(dom).animate({
		        left: "10px",
		    },30);
		     i++;   
	    }
	}

	function errorCampoTexto(campo){
		$(campo).css("box-shadow","inset 0 0 3px 1px rgba(255,0,0,0.5)");
		$("#MensajeNoVivisble").css("display","block");
	}