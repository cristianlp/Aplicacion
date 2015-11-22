<?php


	include_once "Aplicacion/Modelo/Modelo.php";

	class AdministradorBD extends Modelo
	{


		public function login($usuario,$password)
		{

			$this->conectar();
			$aux = $this->consultar("SELECT nombres, apellidos ,rol FROM Usuario WHERE usuario = '".$usuario."'
			AND password = '".$password."'");


			$this->desconectar();
			$cont = 0;
			$nombre = "";
			$apellido = "";
			$rol = "";
			while($fila = mysqli_fetch_array($aux))
			{
				$nombre = $fila[0];
				$apellido = $fila[1];
				$rol = $fila[2];
				$cont++;
			}

			if($cont==1)
			{
				return array($nombre, $apellido ,$rol);
			}
			else
			{
				return false;
			}


		}

	}
