<?php

session_start();

require_once "Controlador/Controlador.php";
require_once "Controlador/Cliente.php";

$principal = new Controlador();

if(isset($_SESSION['messages'])){
    $principal->mostrarMensajes($_SESSION['messages']);
}

if(isset($_SESSION['rol']) && $_SESSION['rol'] != 'Cliente')
{
    $_SESSION["nombre"] = false;
    $_SESSION["rol"] = false;
    $_SESSION['usuario'] = false;
    session_destroy();
    header('location: index.php');
}

if(isset($_SESSION["rol"])){

    //todos los formularios llegan por aca
    if(isset($_POST["tipo"])) {
        //ojo valida que sea el cliente, porque puede llegar otra solicitud por error
        if($_SESSION['rol'] == 'Cliente') {

            $cliente = new Cliente();

            $tipo = $_POST['tipo'];
            switch ($tipo) {

                case 'cambiar_contrasenia':
                    $cliente->cambiar_contrasenia($_POST["contra_actual"], $_POST["contra_nueva"], $_POST["confir_contra"], $_SESSION["usuario"]);
                    break;
                case 'pedir_domicilio':
                    $cliente->pedir_domicilio($_POST['pedido']);
                    break;
                case 'consultar_domicilio':
                    $cliente->detallesDomicilio($_POST['codigo_domicilio']);
                    break;

            }

        }

    }else{

        $cliente = new Cliente();

        if(isset($_GET["accion"])){

            $accion = $_GET['accion'];
            switch ($accion){
                case 'salir':
                    $_SESSION["nombre"] = false;
                    $_SESSION["rol"] = false;
                    $_SESSION['usuario'] = false;
                    session_destroy();
                    header('location: index.php');
                    break;
                case 'domicilios':
                    $cliente->domicilios();
                    break;
                case 'solicitar_domicilio':
                    $cliente->vista_solicitar_domicilio();
                    break;
                case 'reservas':
                    $cliente->reservas();
                    break;
                case 'cambiar_password':
                    $cliente->vistaCambioPassword();
                    break;
            }
        }else{
            //el inicio del dashboard
            $cliente->inicioValidado();
        }

    }
    

}else if(isset($_POST["formulario_login"])){
	$principal->login($_POST["usuario"], $_POST["password"]);
}
//Muestra el inicio de la aplicacion
else{
	$principal->inicio();
}
