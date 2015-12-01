<?php

require_once "Aplicacion/Controlador/Controlador.php";
include_once "Aplicacion/Modelo/CajeroBD.php";

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
			$workspace = $this->reemplazar($workspace, "{{codigo_pedido}}", $datos[0]);
			$workspace = $this->reemplazar($workspace, "{{cliente}}", $datos[1] . "" . $datos[2]);
			$workspace = $this->reemplazar($workspace, "{{mesero}}", $datos[3]. "" .$datos[4] );
			$workspace = $this->reemplazar($workspace, "{{fecha}}", $datos[5] );
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
			$total .= "<option value='".$dato['codigo_ingrediente']."' data='I'>".$dato['nombre_ingrediente']." - m&aacute;ximo " .$dato['cantidad']." ". $dato['unidad'] ."</option>";
		}

		for($i = 0; $i < count($datos[1]); $i++){
			$dato = $datos[1][$i];
			$total .= "<option value='".$dato['codigo_receta']."'' data='R'>".$dato['nombre_receta'] ."</option>";
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
	$mesero, $codigo_pedido, $items, $cantidades, $data, $valor){
		$cajeroBD = new CajeroBD();
		$esta = $cajeroBD->estaCliente($cliente);
		if($esta == false){
			$cajeroBD->registrarClientePresencial($cliente);
		}

		$ok = $cajeroBD->registarPedido($codigo_pedido, $cliente, $mesero, $valor);

		for($i = 0; $i<count($cantidades); $i++){
			if($data[$i] == "R") {
				$cajeroBD->registrarReceta_Pedido($items[$i], $codigo_pedido, $cantidades[$i]);
			}else{
				$cajeroBD->registrarIngrediente_Pedido($items[$i], $codigo_pedido, $cantidades[$i]);
				$cajeroBD->restarExistenciaIngrediente($items[$i], $cantidades[$i]);
			}
		}

		$plantilla = $this->cargarConsultaPedidos();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Pedido registrado exitosamente", "");
		}else{
			$plantilla=$this->alerta($plantilla, "No se pudo registrar el pedido", "");
		}
		$this->mostrarVista($plantilla);

	}

	public function pagarPedido($codigo_pedido){

		$cajeroBD = new CajeroBD();
		$cajeroBD->pagarPedido($codigo_pedido);
		$plantilla = $this->cargarConsultaPedidos();
		$plantilla=$this->alerta($plantilla, "El pedido se ha pagado correctamente", "");
		$this->mostrarVista($plantilla);
	}

	public function cancelarPedido($codigo_pedido){

		$cajeroBD = new CajeroBD();
		$cajeroBD->cancelarPedido($codigo_pedido);
		$plantilla = $this->cargarConsultaPedidos();
		$plantilla=$this->alerta($plantilla, "El pedido se ha cancelado.", "");
		$this->mostrarVista($plantilla);
	}

	public function vistaVentas(){

	}


}
