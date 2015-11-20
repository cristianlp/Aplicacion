<?php

	require_once "Aplicacion/Controlador/Controlador.php";
	include_once "Aplicacion/Modelo/MeseroBD.php";

	class Mesero extends Controlador
	{

		public function inicioValidado()
		{
			$plantilla = $this->init();
			$workspace = $this->leerPlantilla("Aplicacion/Vista/mesero/mesero_home.html");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);

		}

		public function init()
		{

			$plantilla = $this->leerPlantilla("Aplicacion/Vista/principal/principal.html");
			$barraSup=$this->leerPlantilla("Aplicacion/Vista/principal/barraSuperior.html");

			$barraSup = $this->reemplazar($barraSup, "{{nombre}}", $_SESSION["nombre"]);
			$barraSup = $this->reemplazar($barraSup, "{{rol}}", $_SESSION["rol"]);

			$barraLat = $this->leerPlantilla("Aplicacion/Vista/mesero/mesero_barra_lateral.html");
			$barraLat = $this->reemplazar($barraLat, "{{rol}}", $_SESSION["rol"]);
			
			$plantilla = $this->reemplazar($plantilla, "{{barra_superior}}", $barraSup);

			$plantilla = $this->reemplazar($plantilla, "{{barra_lateral}}", $barraLat);


			return $plantilla;
		}


	}