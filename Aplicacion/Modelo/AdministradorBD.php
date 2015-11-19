<?php


	include_once "Aplicacion/Modelo/Modelo.php";

	class AdministradorBD extends Modelo
	{


		public function login($usuario,$password)
		{

			/*$this->conectar();
			$aux = $this->consultar("SELECT nombre,rol FROM Usuario WHERE usuario = '".$usuario."' 
									AND password = '".$password."'");
			$this->desconectar();
			$cont = 0;
			$nombre = "";
			$rol = "";
			while($fila = mysqli_fetch_array($aux))
			{
				$nombre = $fila[0];
				$rol = $fila[1];
				$cont++;
			}
			
			if($cont==1)
			{
				return {$nombre, $rol};
			}
			else
			{
				return false;
			}*/

			return array("Maite Isabel", "Gerente");
		}

	}