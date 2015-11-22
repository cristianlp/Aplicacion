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

	//funcion que poner color cada item de la barra de navagion
	//dependiendo de la peticion get
	function colorearNavegacionGerente(){
		var parametro = ((window.location.href).split("="))[1];

		var dom = "";

		if(parametro == null){
			$(".nav1").addClass("active_current");
		}else {
			switch (parametro) {
				case "menu":
					dom = ".nav2";
					break;
				case "despensa":
					dom = ".nav3";
					break;
				case "empleados":
					dom = ".nav4";
					break;
				case "recetas":
					dom = ".nav5";
					break;
				case "pedidos":
					dom = ".nav6";
					break;
				case "ventas":
					dom = ".nav7";
					break;
				case "cambiar_password":
					dom = ".nav8";
					break;
			}
			$(dom).addClass("active_current");
		}

	$(".manito").click(function(){
			var parametro = (($(this).attr("id")).split("-"))[1];
			window.location.href ="index.php?accion=" + parametro;
		});
	}


	//cuando el documento esta listo
	$(document).ready(function(){
		var rol = $(".rol_persona").html();
		colorearNavegacionGerente();
		/*if(rol == "Cajero"){
			colorearNavegacionCajero();
		}else if(rol == "Gerente"){
			colorearNavegacionGerente();
		}else if(rol == "Chef"){
			colorearNavegacionChef();
		}*/

	});
