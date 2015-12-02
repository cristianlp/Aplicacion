<?php

	require_once "Aplicacion/Controlador/Controlador.php";
	include_once "Aplicacion/Modelo/ChefBD.php";

	class Chef extends Controlador
	{

		public function inicioValidado()
		{
			$chefBD = new ChefBD();
			$pedidos = $chefBD->visualizarPedidos();

			$plantilla = $this->init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/chef/consultarPedidos.html");
			$plantilla = $this->procesarConsultaPedidos($plantilla, $workspace, $pedidos);
			$this->mostrarVista($plantilla);

		}

		public function procesarConsultaPedidos($plantilla, $workspace, $datos)
		{
			$total = "";
			$filaModelo = $this->leerPlantilla("Aplicacion/Vista/chef/fila_pedido.html");
			for($i = 0; $i < count($datos); $i++){
				$tr = $filaModelo;
				$pedido = $datos[$i];
				$tr = $this->reemplazar($tr, "{{codigo_pedido}}", $pedido['codigo_pedido']);
				$tr = $this->reemplazar($tr, "{{cliente}}", $pedido['cliente']);
				$tr = $this->reemplazar($tr, "{{mesero}}", $pedido['mesero']);

				$hoy = date("j, Y, g:i:s a", strtotime($pedido['fecha']));
				$mes = substr((date("F",strtotime($pedido['fecha']))), 0 ,3);
				$tr = $this->reemplazar($tr, "{{fecha}}", $mes . " " . $hoy);

				$tr = $this->reemplazar($tr, "{{valor}}", $pedido['valor']);
				$tr = $this->reemplazar($tr, "{{estado}}", $pedido['estado']);
				$total .= $tr;
			}
			$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
			return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		}

		public function init()
		{

			$plantilla = $this->leerPlantilla("Aplicacion/Vista/principal/principal.html");
			$barraSup=$this->leerPlantilla("Aplicacion/Vista/principal/barraSuperior.html");

			$barraSup = $this->reemplazar($barraSup, "{{nombre}}", $_SESSION["nombre"]);
			$barraSup = $this->reemplazar($barraSup, "{{rol}}", $_SESSION["rol"]);

			$barraLat = $this->leerPlantilla("Aplicacion/Vista/chef/chef_barra_lateral.html");
			$barraLat = $this->reemplazar($barraLat, "{{rol}}", $_SESSION["rol"]);

			$opciones_persona = $this->leerPlantilla("Aplicacion/Vista/principal/opciones_persona.html");
			$barraLat = $this->reemplazar($barraLat, "{{opciones_persona}}", $opciones_persona );

			$plantilla = $this->reemplazar($plantilla, "{{barra_superior}}", $barraSup);

			$plantilla = $this->reemplazar($plantilla, "{{barra_lateral}}", $barraLat);


			return $plantilla;
		}

		public function cocinarPedido($codigo_pedido){
			$chefBD = new ChefBD();
			$chefBD->cocinarPedido($codigo_pedido);
			$plantilla = $this->inicioValidado();
			$plantilla=$this->alerta($plantilla, "El pedido se ha cocinado.", "success");
			$this->mostrarVista($plantilla);
		}

		public function consultarPedido($codigo_pedido){
		$chefBD = new ChefBD();
		$plantilla = $this->init();
		$datos = $chefBD->buscarPedido($codigo_pedido);
		if($datos != false){
			$workspace = $this->leerPlantilla("Aplicacion/Vista/chef/detalles_pedido.html");
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
		}

	}

	public function procesarConsultaItemsPedido($codigo_pedido){
		$chefBD = new ChefBD();
		$items = $chefBD->visualizarIngredientesPedido($codigo_pedido);

		$total = "";
		for($i = 0; $i < count($items); $i++){
			$item = $items[$i];
			$total .= "<b>- </b>" .$item['cantidad']. " ". $item['nombre_ingrediente'] . "<br>";
		}

		$items = $chefBD->visualizarRecetasPedido($codigo_pedido);

		for($i = 0; $i < count($items); $i++){
			$item = $items[$i];
			$total .= "<b>- </b>" .$item['cantidad'] ." ". $item['nombre_receta'] . "<br>";
		}
		return $total;
	}




}
