<?php

require_once "Controlador/Controlador.php";
require_once  'Controlador/../Modelo/AdministradorBD.php';
require_once  'Controlador/../Modelo/ClienteBD.php';
require_once  'Controlador/../Modelo/CajeroBD.php';

class Cliente extends Controlador
{

	public function inicioValidado()
	{

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Vista/cliente/cliente_home.html");
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);

	}

	public function init()
	{

		$plantilla = $this->leerPlantilla("Vista/principal/principal.html");

		$barraSup=$this->leerPlantilla("Vista/principal/barraSuperior.html");

		$barraSup = $this->reemplazar($barraSup, "{{nombre}}", $_SESSION["nombre"]);
		$barraSup = $this->reemplazar($barraSup, "{{rol}}", $_SESSION["rol"]);

		$barraLat = $this->leerPlantilla("Vista/cliente/barra_lateral.html");
		$barraLat = $this->reemplazar($barraLat, "{{rol}}", $_SESSION["rol"]);

		$opciones_persona = $this->leerPlantilla("Vista/principal/opciones_persona.html");
		$barraLat = $this->reemplazar($barraLat, "{{opciones_persona}}", $opciones_persona );

		$plantilla = $this->reemplazar($plantilla, "{{barra_superior}}", $barraSup);

		$plantilla = $this->reemplazar($plantilla, "{{barra_lateral}}", $barraLat);


		return $plantilla;
	}

	/*
	*	PEDIDOS
	*/

	public function vistaCambioPassword()
	{
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Vista/principal/cambioPassword.html");
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function domicilios(){

		$plantilla = $this->cargarConsultaDomicilios();
		$this->mostrarVista($plantilla);

	}

	public function vista_solicitar_domicilio(){

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Vista/cliente/solicitar_domicilio.html");
		$cliente = new CajeroBD();
		$workspace = $this->reemplazar($workspace, "{{items}}", $this->procesarConsultaItemsCandidatos($cliente->visualizarItemsCandidatos()) );
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);

	}

	public function procesarConsultaItemsCandidatos($datos){
		$total = "";
		$fila = $this->leerPlantilla('Vista/cliente/fila_solicitar_domicilio.html');

		for($i = 0; $i < count($datos[0]); $i++){
			$dato = $datos[0][$i];

			$tr = $fila . "";
			$tr = $this->reemplazar($tr, '{{nombre}}', $dato['nombre_ingrediente']);
			$tr = $this->reemplazar($tr, '{{codigo}}', 'p_' . $dato['codigo_ingrediente']);
			$tr = $this->reemplazar($tr, '{{precio}}', $dato['precio_producto']);
			$tr = $this->reemplazar($tr, '{{cantidad}}', $dato['cantidad']);
			$total .= $tr;
		}

		for($i = 0; $i < count($datos[1]); $i++){
			$dato = $datos[1][$i];

			$tr = $fila . "";
			$tr = $this->reemplazar($tr, '{{nombre}}', $dato['nombre_receta']);
			$tr = $this->reemplazar($tr, '{{codigo}}', 'r_' . $dato['codigo_receta']);
			$tr = $this->reemplazar($tr, '{{precio}}', $dato['precio_receta']);
			$tr = $this->reemplazar($tr, '{{cantidad}}', '-');
			$total .= $tr;
		}
		$total .= '<script>var bandera = true; var usuario = "' .  $_SESSION['usuario'] .'";</script>';
		return $total;
	}

	public function pedir_domicilio($datos){

		$clienteBD = new ClienteBD();
		$domiciliario = $clienteBD->domiciliarioDisponible();
			
		$clienteBD->guardarDomicilio($datos['descripcion'], $datos['direccion'], $datos['usuario'],
			$domiciliario, $domiciliario);
		$domicilio = $clienteBD->getUltimoDomicilio();

		$clienteBD->guardarProductosDomicilio($datos['productos'], $domicilio);

		return 'ok';

	}

	public function detallesDomicilio($codigo){

		$clienteBD = new ClienteBD();
		$domicilio = $clienteBD->detallesDomicilio($codigo);

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Vista/cliente/detalles_domicilios.html");

		$workspace = $this->reemplazar($workspace, '{{codigo}}', $domicilio['codigo']);
		$workspace = $this->reemplazar($workspace, '{{descripcion}}', $domicilio['descripcion']);
		$workspace = $this->reemplazar($workspace, '{{fecha}}', $domicilio['fecha_entrega'] . '-');
		$workspace = $this->reemplazar($workspace, '{{direccion}}', $domicilio['direccion']);
		$workspace = $this->reemplazar($workspace, '{{domiciliario}}', $domicilio['dnom'] . $domicilio['dapell']);
		$workspace = $this->reemplazar($workspace, '{{estado}}', $domicilio['estado']);

		$pes = $clienteBD->articulosDomicilio($codigo, 'producto');
		$reces = $clienteBD->articulosDomicilio($codigo, 'receta');
		$articulos = array_merge($pes, $reces );
		
		$data = '';
		foreach($articulos as $articulo){
			$data .= '<li>'. $articulo["cantidad"].' - '.$articulo["nombre"].'</li>';
		}

		$workspace = $this->reemplazar($workspace, "{{articulos}}", $data);
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);

	}



	private function cargarConsultaDomicilios(){
		$clienteBD = new ClienteBD();

		$domicilios = $clienteBD->visualizarDomicilios();

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Vista/cliente/consultarDomicilios.html");
		$plantilla = $this->procesarConsultaDomicilios($plantilla, $workspace, $domicilios);
		return $plantilla;
	}

	public function procesarConsultaDomicilios($plantilla, $workspace, $datos)
	{
		$total = "";
		$filaModelo = $this->leerPlantilla("Vista/cliente/fila_domicilio.html");
		for($i = 0; $i < count($datos); $i++){
			$tr = $filaModelo;
			$pedido = $datos[$i];
			$tr = $this->reemplazar($tr, "{{codigo}}", $pedido['id']);
			$tr = $this->reemplazar($tr, "{{descripcion}}", $pedido['descripcion'] . "-");
			$tr = $this->reemplazar($tr, "{{direccion}}", $pedido['direccion']);
			$tr = $this->reemplazar($tr, "{{domiciliario}}", $pedido['domiciliario']);
			$tr = $this->reemplazar($tr, "{{tiempo}}", $pedido['tiempo_gastado'] . "-");
			$hoy = date("j, Y, g:i a", strtotime($pedido['fecha_entrega']));
			$mes = substr((date("F",strtotime($pedido['fecha_entrega']))), 0 ,3);
			$tr = $this->reemplazar($tr, "{{fecha}}", $mes . " " . $hoy);

			$tr = $this->reemplazar($tr, "{{estado}}", $pedido['estado']);
			$total .= $tr;
		}

		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function reservas()
	{
		$plantilla = $this->cargarConsultaReservas();
		$this->mostrarVista($plantilla);
	}

	private function cargarConsultaReservas(){
		$clienteBD = new ClienteBD();

		$domicilios = $clienteBD->visualizarReservas();

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Vista/cliente/consultarReservas.html");
		$plantilla = $this->procesarConsultaReservas($plantilla, $workspace, $domicilios);
		return $plantilla;
	}

	public function procesarConsultaReservas($plantilla, $workspace, $datos)
	{
		$total = "";
		$filaModelo = $this->leerPlantilla("Vista/cliente/fila_reserva.html");
		
		$clienteBD = new ClienteBD();
		
		for($i = 0; $i < count($datos); $i++){
			$tr = $filaModelo;
			$pedido = $datos[$i];
			$tr = $this->reemplazar($tr, "{{codigo}}", $pedido['id']);
			$tr = $this->reemplazar($tr, "{{fecha}}", $pedido['fecha_reserva'] . "-");
			$tr = $this->reemplazar($tr, "{{estado}}", $pedido['estado']);
			$tr = $this->reemplazar($tr, "{{nombre}}", $pedido['nombre']);

			$mesas = $clienteBD->getMesasReserva($pedido['id']);

			$res_m = '';
			for ($u = 0; $u < sizeof($mesas); $u++){
				$res_m .= $mesas[$u] . ' - ';
			}
			$tr = $this->reemplazar($tr, "{{mesas}}", $res_m);
			
			$total .= $tr;
		}

		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function vista_solicitar_reserva()
	{

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Vista/cliente/solicitar_reserva.html");
		$cliente = new ClienteBD();
		$workspace = $this->reemplazar($workspace, "{{mesas}}", $this->procesarConsultaMesas($cliente->visualizarMesas()) );
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);

	}

	private function procesarConsultaMesas($mesas)
	{

		$aux = $this->leerPlantilla("Vista/cliente/auxiliar_radio.html");
		$total = '';
		for($i = 0; $i < sizeof($mesas); $i++){
			$mesa = $mesas[$i];
			$temp = $aux . '';
			$temp = $this->reemplazar($temp, '{{codigo}}', $mesa['id']);
			$temp = $this->reemplazar($temp, '{{nombre}}', $mesa['nombre']);
			$temp = $this->reemplazar($temp, '{{cantidad}}', $mesa['cantidad']);

			$total .= $temp;
		}

		$total .= '<script>var bandera = true; var usuario = "' .  $_SESSION['usuario'] .'";</script>';

		return $total;

	}

	public function pedir_reserva($reserva)
	{

		if($reserva['cantidad'] == 0){

			$this->reservarMesa($reserva);

		}else{
			//todo : buscar unas mesas que se ajusten
		}

	}

	private function reservarMesa($mesa)
	{
		$clienteBD = new ClienteBD();
		$clienteBD->CambiarEstadoMesa($mesa['mesa'], 'ocupada');
		$clienteBD->generarReserva('NOW()', $mesa['usuario'], 'activa', $mesa['nombre']);
		$reserva = $clienteBD->getUltimaReserva();

		$clienteBD->generarReservaMesas($reserva, $mesa['mesa']);
	}

	public function subirImagen($imagen){

			$errors= array();
			$file_name = $imagen['name'];
			$file_size = $imagen['size'];
			$file_tmp = $imagen['tmp_name'];
			$file_type = $imagen['type'];
			$file_ext=strtolower(end(explode('.',$imagen['name'])));

			$expensions= array("jpeg","jpg","png");

			if(in_array($file_ext,$expensions)=== false){
				$errors[]="extension not allowed, please choose a JPEG or PNG file.";
			}

			if($file_size > 2097152) {
				$errors[]='File size must be excately 2 MB';
			}

			if(empty($errors)==true) {
				move_uploaded_file($file_tmp,"../Estatico/img/uploads/".$file_name);
				return "Estatico/img/uploads/".$file_name;
			}


	}

	public function cambiar_contrasenia($contra_actual, $contra_nueva, $confir_contra, $usuario){

		if($contra_actual == '' || $contra_nueva == '' || $confir_contra == ''){

			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$plantilla = $this->alerta($plantilla, "Las contraseñas no coinciden-Verfique que sean iguales", "info");
			$this->mostrarVista($plantilla);
			return;

		}


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
