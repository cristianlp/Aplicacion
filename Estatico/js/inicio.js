function alerta (titulo, mensaje) {
	swal(titulo, mensaje);
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

		var ing = 2;
		$("#ingX").attr("id","ing1");
		$("#cantidadX").attr("id","cantidad1");
		$(".forX").attr("for","cantidad1");

		$(".botoncito_agregar").click(function(){
			$("#panel_agregar_ingredientes").append(
			"<div class='mdl-grid'>"+
				"<div class='mdl-cell mdl-cell--12-col'>"+
					"<div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>"+
						"<select name='ingredientes[]' id='ingX'> "+
							$("#ing1").html() +
						"</select>"+
						"<label class='mdl-textfield__label' for=''></label>"+
					"</div>"+
					"<div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>"+
						"<input class='mdl-textfield__input' type='number' pattern='-?[0-9]*(\.[0-9]+)?' id='cantidadX' name='cantidades[]' value='' required {{disable}} placeholder='Cantidad'> "+
					"</div>"+
				"</div>"+
			"</div>"
				);

		$("#ingX").attr("id","ing" + ing);
		$("#cantidadX").attr("id","cantidad" + ing);
		$(".forX").attr("for","cantidad" + ing);
		ing++;
		});

	});
