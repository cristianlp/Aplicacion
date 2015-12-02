<?php

require_once "Aplicacion/Controlador/Controlador.php";
include_once "Aplicacion/Modelo/CajeroBD.php";
include_once "Aplicacion/Modelo/AdministradorBD.php";

class Cajero extends Controlador
{

	public function inicioValidado()
	{
		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/cajero/cajero_home.html");
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);

	}

	public function init()
	{

		$plantilla = $this->leerPlantilla("Aplicacion/Vista/principal/principal.html");
		$barraSup=$this->leerPlantilla("Aplicacion/Vista/principal/barraSuperior.html");

		$barraSup = $this->reemplazar($barraSup, "{{nombre}}", $_SESSION["nombre"]);
		$barraSup = $this->reemplazar($barraSup, "{{rol}}", $_SESSION["rol"]);

		$barraLat = $this->leerPlantilla("Aplicacion/Vista/cajero/cajero_barra_lateral.html");
		$barraLat = $this->reemplazar($barraLat, "{{rol}}", $_SESSION["rol"]);

		$opciones_persona = $this->leerPlantilla("Aplicacion/Vista/principal/opciones_persona.html");
		$barraLat = $this->reemplazar($barraLat, "{{opciones_persona}}", $opciones_persona );

		$plantilla = $this->reemplazar($plantilla, "{{barra_superior}}", $barraSup);

		$plantilla = $this->reemplazar($plantilla, "{{barra_lateral}}", $barraLat);


		return $plantilla;
	}

	/*
	*	PEDIDOS
	*/

	public function vistaPedidos(){
		$plantilla = $this->cargarConsultaPedidos();
		$this->mostrarVista($plantilla);
	}

	private function cargarConsultaPedidos(){
		$cajeroBD = new CajeroBD();
		$pedidos = $cajeroBD->visualizarPedidos();

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/cajero/consultarPedidos.html");
		$plantilla = $this->procesarConsultaPedidos($plantilla, $workspace, $pedidos);
		return $plantilla;
	}

	public function procesarConsultaPedidos($plantilla, $workspace, $datos)
	{
		$total = "";
		$filaModelo = $this->leerPlantilla("Aplicacion/Vista/cajero/fila_pedido.html");
		for($i = 0; $i < count($datos); $i++){
			$tr = $filaModelo;
			$pedido = $datos[$i];
			$tr = $this->reemplazar($tr, "{{codigo_pedido}}", $pedido['codigo_pedido']);
			$tr = $this->reemplazar($tr, "{{cliente}}", $pedido['cliente']);
			$tr = $this->reemplazar($tr, "{{mesero}}", $pedido['mesero']);

			$hoy = date("j, Y, g:i a", strtotime($pedido['fecha']));
			$mes = substr((date("F",strtotime($pedido['fecha']))), 0 ,3);
			$tr = $this->reemplazar($tr, "{{fecha}}", $mes . " " . $hoy);

			$tr = $this->reemplazar($tr, "{{valor}}", $pedido['valor']);
			$tr = $this->reemplazar($tr, "{{estado}}", $pedido['estado']);
			$total .= $tr;
		}
		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function agregar_consultar_pedido($codigo_pedido){

		$cajeroBD = new CajeroBD();
		$plantilla = $this->init();
		$bnd = false;
		$datos = $cajeroBD->buscarPedido($codigo_pedido);
		$workspace = "";
		if($datos != false){
			$workspace = $this->leerPlantilla("Aplicacion/Vista/cajero/detalles_pedido.html");
			$workspace = $this->reemplazar($workspace, "{{goBack}}", "pedidos");
			$workspace = $this->reemplazar($workspace, "{{codigo_pedido}}", $datos[0]);
			$workspace = $this->reemplazar($workspace, "{{cliente}}", $datos[1] . " " . $datos[2]);
			$workspace = $this->reemplazar($workspace, "{{mesero}}", $datos[3]. " " .$datos[4] );

			$hoy = date("j, Y, g:i a", strtotime($datos[5]));
			$mes = substr((date("F",strtotime($datos[5]))), 0 ,3);
			$workspace = $this->reemplazar($workspace, "{{fecha}}", $mes . " " . $hoy );
			$workspace = $this->reemplazar($workspace, "{{valor}}", $datos[6] );
			$workspace = $this->reemplazar($workspace, "{{estado}}", $datos[7] );
			$workspace = $this->reemplazar($workspace, "{{items}}", $this->procesarConsultaItemsPedido($codigo_pedido) );
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}else{
			$workspace = $this->leerPlantilla("Aplicacion/Vista/cajero/registroPedidos.html");
			$workspace = $this->reemplazar($workspace, "{{codigo_pedido}}", $codigo_pedido);

			$workspace = $this->reemplazar($workspace, "{{cliente}}", "");
			$workspace = $this->reemplazar($workspace, "{{disable}}", "");
			$workspace = $this->reemplazar($workspace, "{{tipo}}", "agregarPedido");
			$workspace = $this->reemplazar($workspace, "{{valor}}", "");
			$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Registrar");
			$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "El pedido no se encuentra registrado, ¿desea registrarlo?");
			$workspace = $this->reemplazar($workspace, "{{meseros}}", $this->procesarConsultaMeserosPedido($cajeroBD->visualizarMeseros()) );
			$workspace = $this->reemplazar($workspace, "{{items}}", $this->procesarConsultaItemsCandidatos($cajeroBD->visualizarItemsCandidatos()) );
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);

		}
	}
	public function vistaCambioPassword()
	{
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function procesarConsultaMeserosPedido($meseros){
		$total = "";
		for($i = 0; $i < count($meseros); $i++){
			$mesero = $meseros[$i];
			$total .= "<option value='".$mesero['usuario']."''>".$mesero['nombres'] ." " .$mesero['apellidos']."</option>";
		}
		return $total;
	}

	public function procesarConsultaItemsCandidatos($datos){
		$total = "";
		for($i = 0; $i < count($datos[0]); $i++){
			$dato = $datos[0][$i];
			$total .= "<option value='I/".$dato['codigo_ingrediente']."' data='I'>".$dato['nombre_ingrediente']." - m&aacute;ximo " .$dato['cantidad']." ". $dato['unidad'] ."</option>";
		}

		for($i = 0; $i < count($datos[1]); $i++){
			$dato = $datos[1][$i];
			$total .= "<option value='R/".$dato['codigo_receta']."'' data='R'>".$dato['nombre_receta'] ."</option>";
		}
		return $total;
	}

	public function procesarConsultaItemsPedido($codigo_pedido){
		$cajeroBD = new CajeroBD();
		$items = $cajeroBD->visualizarIngredientesPedido($codigo_pedido);

		$total = "";
		for($i = 0; $i < count($items); $i++){
			$item = $items[$i];
			$total .= "<b>- </b>" .$item['cantidad']. " ". $item['nombre_ingrediente'] . "<br>";
		}

		$items = $cajeroBD->visualizarRecetasPedido($codigo_pedido);

		for($i = 0; $i < count($items); $i++){
			$item = $items[$i];
			$total .= "<b>- </b>" .$item['cantidad'] ." ". $item['nombre_receta'] . "<br>";
		}
		return $total;
	}

	public function  agregar_pedido($codigo_pedido, $cliente,
	$mesero, $codigo_pedido, $items, $cantidades, $valor){
		$cajeroBD = new CajeroBD();
		$esta = $cajeroBD->estaCliente($cliente);
		if($esta == false){
			$aux = $cajeroBD->registrarClientePresencial($cliente);
			if($aux == false){
				$ok = false;
				$p= true;
				goto etiqueta;
			}
		}

		$ok = $cajeroBD->registarPedido($codigo_pedido, $cliente, $mesero, $valor);
		$p = true;
		for($i = 0; $i<count($cantidades); $i++){
			$datos_item = explode("/", $items[$i]);
			if($datos_item[0] == "R") {
				$cajeroBD->registrarReceta_Pedido($datos_item[1], $codigo_pedido, $cantidades[$i]);
			}else{
				if($this->preguntarSiPuedeRestar($datos_item[1], $cantidades[$i])){
					$cajeroBD->registrarIngrediente_Pedido($datos_item[1], $codigo_pedido, $cantidades[$i]);
					$cajeroBD->restarExistenciaIngrediente($datos_item[1], $cantidades[$i]);
					
				}else{
					$cajeroBD->eliminarPedido($codigo_pedido);
					$ok = false;
					$p = false;
					break;
				}
				
			}
		}

		etiqueta:
		$plantilla = $this->cargarConsultaPedidos();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Pedido registrado exitosamente", "success");
		}else{
			if($p == false){
				$plantilla=$this->alerta($plantilla, "Las cantidades no son suficientes para hacer este pedido", "error");
			}else{
				$plantilla=$this->alerta($plantilla, "No se pudo registrar el pedido", "error");
			}
		}
		$this->mostrarVista($plantilla);

	}

	private function preguntarSiPuedeRestar($codigo_item, $cantidad){
		$cajeroBD = new CajeroBD();
		$cant = $cajeroBD->buscarCantidadDeIngrediente($codigo_item);
		if($cantidad <= $cant ){
			return true;
		}
		return false;
	}

	public function pagarPedido($codigo_pedido){

		$cajeroBD = new CajeroBD();
		$cajeroBD->pagarPedido($codigo_pedido);
		$plantilla = $this->cargarConsultaPedidos();
		$plantilla=$this->alerta($plantilla, "El pedido se ha pagado correctamente", "success");
		$this->mostrarVista($plantilla);
	}

	public function cancelarPedido($codigo_pedido){

		$cajeroBD = new CajeroBD();
		$cajeroBD->cancelarPedido($codigo_pedido);
		$plantilla = $this->cargarConsultaPedidos();
		$plantilla=$this->alerta($plantilla, "El pedido se ha cancelado-Recuerde que los pedidos cancelados los podrá ver algún administrador", "info");
		$this->mostrarVista($plantilla);
	}

	public function vistaVentas(){
		$plantilla = $this->cargarConsultaVentas();
		$this->mostrarVista($plantilla);
	}

	private function cargarConsultaVentas(){
		$cajeroBD = new CajeroBD();
		$pedidos = $cajeroBD->visualizarVentas();

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/cajero/consultarVentas.html");
		$plantilla = $this->procesarConsultaVentas($plantilla, $workspace, $pedidos);
		return $plantilla;
	}

	public function procesarConsultaVentas($plantilla, $workspace, $datos)
	{
		$total = "";
		$filaModelo = $this->leerPlantilla("Aplicacion/Vista/cajero/fila_venta.html");
		for($i = 0; $i < count($datos); $i++){
			$tr = $filaModelo;
			$pedido = $datos[$i];
			$tr = $this->reemplazar($tr, "{{codigo_pedido}}", $pedido['codigo_pedido']);
			$tr = $this->reemplazar($tr, "{{cliente}}", $pedido['cliente']);
			$tr = $this->reemplazar($tr, "{{mesero}}", $pedido['mesero']);

			$hoy = date("j, Y, g:i a", strtotime($pedido['fecha']));
			$mes = substr((date("F",strtotime($pedido['fecha']))), 0 ,3);
			$tr = $this->reemplazar($tr, "{{fecha}}", $mes . " " . $hoy);

			$tr = $this->reemplazar($tr, "{{valor}}", $pedido['valor']);
			$tr = $this->reemplazar($tr, "{{estado}}", $pedido['estado']);
			$total .= $tr;
		}
		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function consultarVenta($codigo_pedido){
		$cajeroBD = new CajeroBD();
		$plantilla = $this->init();
		$datos = $cajeroBD->buscarVenta($codigo_pedido);
		if($datos != false){
			$workspace = $this->leerPlantilla("Aplicacion/Vista/cajero/detalles_pedido.html");
			$workspace = $this->reemplazar($workspace, "{{goBack}}", "ventas");
			$workspace = $this->reemplazar($workspace, "{{codigo_pedido}}", $datos[0]);
			$workspace = $this->reemplazar($workspace, "{{cliente}}", $datos[1] . " " . $datos[2]);
			$workspace = $this->reemplazar($workspace, "{{mesero}}", $datos[3]. " " .$datos[4] );

			$hoy = date("j, Y, g:i a", strtotime($datos[5]));
			$mes = substr((date("F",strtotime($datos[5]))), 0 ,3);
			$workspace = $this->reemplazar($workspace, "{{fecha}}", $mes . " " . $hoy );
			$workspace = $this->reemplazar($workspace, "{{valor}}", $datos[6] );
			$workspace = $this->reemplazar($workspace, "{{estado}}", $datos[7] );
			$workspace = $this->reemplazar($workspace, "{{items}}", $this->procesarConsultaItemsPedido($codigo_pedido) );
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}else{
			$plantilla = $this->cargarConsultaVentas();
			$plantilla=$this->alerta($plantilla, "Ese código de venta no existe-Digite un codigo distinto", "info");
		}
		$this->mostrarVista($plantilla);

	}

	public function cambiar_contrasenia($contra_actual, $contra_nueva, $confir_contra, $usuario){
		$ca = $this->encriptarPassword($contra_actual);
		$cn = $this->encriptarPassword($contra_nueva);
		$ccn = $this->encriptarPassword($confir_contra);


		if($cn != $ccn){
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$plantilla = $this->alerta($plantilla, "Las contraseñas no coinciden-Verfique que sean iguales", "info");
			$this->mostrarVista($plantilla);
			return;
		}

		$administradorBD = new AdministradorBD();
		$contr_sistema = $administradorBD->buscarContrasenia($usuario);
		if($contr_sistema != $ca){
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$plantilla = $this->alerta($plantilla, "Su contraseña y usuario no coinciden-Verfique sus datos e intentelo de nuevo", "info");
			$this->mostrarVista($plantilla);
			return;
		}

		if($contr_sistema == $cn){
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$plantilla = $this->alerta($plantilla, "Esa contraseña ya ha sido usada-Por la seguridad de sus datos digite una contraseña diferente", "info");
			$this->mostrarVista($plantilla);
			return;
		}

		$ok = $administradorBD->cambioContrasenia($usuario, $cn);
		if($ok){
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$plantilla = $this->alerta($plantilla, "La contraseña se ha cambiado exitosamente", "success");
		}else{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$plantilla = $this->alerta($plantilla, "La contraseña no se ha podido cambiar", "error");
		}
		
		$this->mostrarVista($plantilla);

	}
}
