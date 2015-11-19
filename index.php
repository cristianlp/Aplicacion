<?php

	session_start();
	
	require_once "Aplicacion/Controlador/Controlador.php";
	require_once "Aplicacion/Controlador/Gerente.php";
	//require_once "Aplicacion/Controlador/Cliente.php";
	//require_once "Aplicacion/Controlador/Mesero.php";
	//require_once "Aplicacion/Controlador/Chef.php";

	$principal = new Controlador();
	//Si esta variable esta definida, el ususario esta logueado
	if(isset($_SESSION["rol"])){

		if(isset($_POST["tipo"])){

			if($_SESSION["tipo"]=="Administrador"){

			}else if($_SESSION["tipo"]=="Operario"){
				
			}else if($_SESSION["tipo"]=="Cliente"){
				
			}

		}else if($_SESSION["rol"]=="Gerente"){

			$gerente = new Gerente();

			if(isset($_GET["accion"])){
				if($_GET['accion'] == "salir"){
					$_SESSION["nombre"] = false;
					$_SESSION["rol"] = false;
					$_SESSION['usuario'] = false;
					session_destroy();
					header('location:index.php');
				}
				
			}else{
				$gerente->inicioValidado();
			}	

		}else if($_SESSION["rol"]=="Mesero"){

			$mesero = new Mesero();

			if(isset($_GET["accion"])){
				
			}else{
				$mesero->inicioValidado();
				echo $_SESSION['rol'];
			}

		}else if($_SESSION["rol"]=="Chef"){

			$chef = new Chef();

			if(isset($_GET["accion"])){
				
			}else{
				$chef->inicioValidado();
			}
		}else if($_SESSION["rol"] == "Cliente"){

			$cliente = new Cliente();

			if(isset($_GET['accion'])){

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