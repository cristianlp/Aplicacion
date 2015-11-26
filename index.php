<?php

	session_start();

	require_once "Aplicacion/Controlador/Controlador.php";
	require_once "Aplicacion/Controlador/Gerente.php";
	//require_once "Aplicacion/Controlador/Cliente.php";
	require_once "Aplicacion/Controlador/Mesero.php";
	//require_once "Aplicacion/Controlador/Chef.php";

	$principal = new Controlador();
	//Si esta variable esta definida, el ususario esta logueado
	if(isset($_SESSION["rol"])){

		//para solicitudes desde formularios
		if(isset($_POST["tipo"])){

			if($_SESSION["rol"]=="Gerente"){
				$gerente = new Gerente();
				if($_POST["tipo"] == "agregar_consultar_empleado"){
					$gerente->agregar_consultar_empleado($_POST["parametro_busqueda"]);
				}else if($_POST["tipo"] == "agregarEmpleado"){
					$gerente->agregarEmpleado($_POST["usuario"], $_POST["nombres"], $_POST["apellidos"], $_POST["cedula"],
						$_POST["correo"], $_POST["telefono"], $_POST["direccion"], $_POST["rol"], $_POST["password"]
					);
				}else if($_POST["tipo"] == "vistaEditarEmpleado"){
					$gerente->vistaEditarEmpleado($_POST["usuario"]);
				}else if($_POST["tipo"] == "editarEmpleado"){
					$gerente->editarEmpleado($_POST["usuario"], $_POST["nombres"], $_POST["apellidos"],
					 $_POST["cedula"], $_POST["correo"], $_POST["telefono"], $_POST["direccion"]);
				}else if($_POST["tipo"] == "eliminarEmpleado"){
					$gerente->eliminarEmpleado($_POST["usuario"]);
				}else if($_POST["tipo"] == "vista_agregar_ingrediente"){
					$gerente->vista_agregar_ingrediente($_POST["codigo_ingrediente"]);
				}else if($_POST["tipo"] == "agregarIngrediente"){
					$gerente->agregar_ingrediente($_POST["codigo_ingrediente"], $_POST["nombre_ingrediente"],
					$_POST["cantidad"],$_POST["unidad"], $_POST['tipo_ingrediente']
				);
				}else if($_POST["tipo"] == "vistaEditarIngrediente"){
					$gerente->vista_editar_ingrediente($_POST["codigo_ingrediente"]);
				}else if($_POST["tipo"] == "editarIngrediente"){
					$gerente->editar_ingrediente(
					$_POST["codigo_ingrediente"], $_POST["nombre_ingrediente"],
					$_POST["cantidad"],$_POST["unidad"]
					);
				}else if($_POST["tipo"] == "agregar_consultar_receta"){
					$gerente->agregar_consultar_receta($_POST["codigo_receta"]);
				}else if($_POST["tipo"] == "agregar_receta"){
					$gerente->agregar_receta($_POST["codigo_receta"], $_POST["nombre_receta"] , $_POST["chef"],$_POST['cantidades'], $_POST['ingredientes'], $_POST['descripcion_proceso'] );
				}else if($_POST["tipo"] == "vistaEditarReceta"){
					$gerente->vista_editar_receta($_POST["codigo_receta"]);
				}else if($_POST["tipo"] == "editar_receta"){
					$gerente->editar_receta(
						$_POST["codigo_receta_ok"],$_POST["nombre_receta"],
						$_POST['chef'], $_POST['descripcion_proceso']
					);
				}

			}else if($_SESSION["tipo"]=="Operario"){

			}else if($_SESSION["tipo"]=="Cliente"){

			}

		}else if($_SESSION["rol"]=="Gerente"){

			//SOLICITUDES PARA CARGAR VISTAS
			$gerente = new Gerente();
			if(isset($_GET["accion"])){

				switch ($_GET["accion"]) {
					//si la soliciud es salir
					case "salir":
						$_SESSION["nombre"] = false;
						$_SESSION["rol"] = false;
						$_SESSION['usuario'] = false;
						session_destroy();
						header('location: index.php');
						break;
					//si la solicitud es cambiar contraseña
					case "cambiar_password":
						$gerente->vistaCambioPassword();
						break;
					//si la solicitud es ver el menu diario
					case "menu":
						$gerente->vistaMenu();
						break;
					//si la solicitud es ver la despensa|
					case "despensa":
						$gerente->vistaDespensa();
						break;
					//si la solicitud es ver empleados
					case "empleados":
						$gerente->vistaEmpleados();
						break;
					//si la solicitud es ver
					case "recetas":
						$gerente->vistaRecetas();
						break;
						//si la solicitud es ver
					case "pedidos":
						$gerente->vistaPedidos();
						break;
					//si la solicitud es ver
					case "ventas":
						$gerente->vistaVentas();
						break;

				}//fin del switch

			}else{
				$gerente->inicioValidado();
			}

		}else if($_SESSION["rol"]=="Cajero"){

			$cajero = new Cajero();
			//SI EL MESERO HACE ALGUNA SOLICITUD
			if(isset($_GET["accion"])){

				switch ($_GET["accion"]) {
					//si la soliciud es salir
					case "salir":
						$_SESSION["nombre"] = false;
						$_SESSION["rol"] = false;
						$_SESSION['usuario'] = false;
						session_destroy();
						header('location: index.php');
						break;
					//si la solicitud es cambiar contraseña
					case "cambiar_password":
						$cajero->vistaCambioPassword();
						break;
				}//fin del switch

			}else{
				$mesero->inicioValidado();
				echo $_SESSION['rol'];
			}

		}else if($_SESSION["rol"]=="Chef"){

			$chef = new Chef();

			if(isset($_GET["accion"])){
				if($_GET['accion'] == "salir"){
					$_SESSION["nombre"] = false;
					$_SESSION["rol"] = false;
					$_SESSION['usuario'] = false;
					session_destroy();
					header('location: index.php');
				}

			}else{
				$chef->inicioValidado();
			}
		}else if($_SESSION["rol"] == "Cliente"){

			$cliente = new Cliente();

			if(isset($_GET['accion'])){
				if($_GET['accion'] == "salir"){
					$_SESSION["nombre"] = false;
					$_SESSION["rol"] = false;
					$_SESSION['usuario'] = false;
					session_destroy();
					header('location: index.php');
				}

			}else{
				$mesero->inicioValidado();
			}

		}
	}
	//Para los usuarios que no estan logueados
	else if(isset($_POST["formulario_login"])){
		$principal->login($_POST["usuario"], $_POST["password"]);
	}

	//Muestra el inicio de la aplicacion
	else{
		$principal->inicio();
	}
