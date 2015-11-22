<?php

	require_once "Aplicacion/Controlador/Controlador.php";
	include_once "Aplicacion/Modelo/GerenteBD.php";

	class Gerente extends Controlador
	{

		public function inicioValidado()
		{
			$plantilla = $this->init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/gerente_home.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);

		}

		public function init()
		{
			$plantilla = $this->leerPlantilla("Aplicacion/Vista/principal/principal.html");
			$barraSup=$this->leerPlantilla("Aplicacion/Vista/principal/barraSuperior.html");

			$barraSup = $this->reemplazar($barraSup, "{{nombre}}", $_SESSION["nombre"]);
			$barraSup = $this->reemplazar($barraSup, "{{rol}}", $_SESSION["rol"]);

			$barraLat = $this->leerPlantilla("Aplicacion/Vista/gerente/gerente_barra_lateral.html");
			$barraLat = $this->reemplazar($barraLat, "{{rol}}", $_SESSION["rol"]);

			$opciones_persona = $this->leerPlantilla("Aplicacion/Vista/principal/opciones_persona.html");
			$barraLat = $this->reemplazar($barraLat, "{{opciones_persona}}", $opciones_persona );

			$plantilla = $this->reemplazar($plantilla, "{{barra_superior}}", $barraSup);
			$plantilla = $this->reemplazar($plantilla, "{{barra_lateral}}", $barraLat);

			return $plantilla;
		}



		/*
		*
		*		 FUNCIONES QUE PERMITEN MOSTRAR LAS VISTAS SOICITADAS
		*
		*/

		public function vistaCambioPassword()
		{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}

		public function vistaMenu()
		{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaMenu.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}
		public function vistaDespensa()
		{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaDespensa.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}
		public function vistaEmpleados()
		{
			$plantilla = $this->cargarConsultaEmpleados();
			$this->mostrarVista($plantilla);
		}

		public function cargarConsultaEmpleados()
		{
			$gerenteBD = new GerenteBD();
			$empleados = $gerenteBD->visualizarEmpleados();

			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/consultarUsuarios.html");
			$plantilla = $this->procesarConsultaUsuarios($plantilla, $workspace, $empleados);
			return $plantilla;
		}

		public function procesarConsultaUsuarios($plantilla, $workspace, $datos)
		{
			$total = "";
			$filaUsuarioModelo = $this->leerPlantilla("Aplicacion/Vista/gerente/filaUsuario.html");
			for($i = 0; $i < count($datos); $i++){
				$tr = $filaUsuarioModelo;
				$persona = $datos[$i];
				$tr = $this->reemplazar($tr, "{{nombre}}", $persona['nombres'] . " " . $persona['apellidos']);
				$tr = $this->reemplazar($tr, "{{usuario}}", $persona['usuario']);
				$tr = $this->reemplazar($tr, "{{rol}}", $persona['rol']);
				$tr = $this->reemplazar($tr, "{{telefono}}", $persona['telefono']);
				$total .= $tr;
			}
			$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
			return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		}

		public function vistaRecetas()
		{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaRecetas.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}
		public function vistaPedidos()
		{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaPedidos.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}
		public function vistaVentas()
		{
			$plantilla = $this -> init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaVentas.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}

		public function agregar_consultar_empleado($parametro_busqueda){
			$output_array;
			preg_match("/[0-9]*/", $parametro_busqueda, $output_array);

			$gerenteBD = new GerenteBD();
			$plantilla = $this->init();
			$bnd = false;
			if(strcmp($output_array[0], "") !== 0){
				$datos = $gerenteBD->buscarEmpleadoPorCedula($parametro_busqueda);
				$workspace = "";
				if($datos != false){
					$bnd = true;
				}else{
					$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/agregarEmpleadoCedula.html");
					$workspace = $this->reemplazar($workspace, "{{cedula}}", $parametro_busqueda);
					$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
					$this->mostrarVista($plantilla);
				}

			}else{
				$datos = $gerenteBD->buscarEmpleadoPorUsuario($parametro_busqueda);
				$workspace = "";
				if($datos != false){
					$bnd = true;
				}else{
					$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/agregarEmpleadoUsuario.html");
					$workspace = $this->reemplazar($workspace, "{{usuario}}", $parametro_busqueda);
					$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
					$this->mostrarVista($plantilla);
				}
			}//fin del si es numero o letra
			if($bnd == true){
				$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/detallesEmpleado.html");
				$cuerpo_tabla = "
					<tr><td><strong>Usuario </strong></td><td>".$datos['usuario']."</td></tr>
					<tr><td><strong>Cedula </strong></td><td>".$datos['cedula']."</td></tr>
					<tr><td><strong>Nombres </strong></td><td>".$datos['nombres']."</td></tr>
					<tr><td><strong>Apellidos </strong></td><td>".$datos['apellidos']."</td></tr>
					<tr><td><strong>Correo </strong></td><td>".$datos['correo']."</td></tr>
					<tr><td><strong>Telefono </strong></td><td>".$datos['telefono']."</td></tr>
					<tr><td><strong>Direccion </strong></td><td>".$datos['direccion']."</td></tr>";
				$workspace = $this->reemplazar($workspace, "{{rol}}", $datos['rol']);
				$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $cuerpo_tabla );
				$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
				$this->mostrarVista($plantilla);
			}
		}

	}
