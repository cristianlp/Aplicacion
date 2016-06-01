<?php

	include_once "Modelo.php";

	class AdministradorBD extends Modelo
	{

		public function login($usuario,$password)
		{

			$this->conectar();
			$aux = $this->consultar("SELECT nombres, apellidos , rol FROM Usuario WHERE usuario = '".$usuario."'
			AND password = '".$password."' AND rol = 'Cliente'");


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

		public function buscarContrasenia($usuario){
      $this->conectar();
      $aux = $this->consultar("SELECT password FROM Usuario WHERE usuario='".$usuario."'");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }

      if(count($datos) != 1){
        return false;
      }else{
        return $datos[0][0];
      }
    }

    public function cambioContrasenia($usuario, $cn){
      $this->conectar();
      $aux = $this->consultar("UPDATE Usuario SET password = '".$cn."' WHERE usuario ='".$usuario."' ");
      $this->desconectar();
      return $aux;
    }

		public function cambiarPerfil($ruta, $usuario){
			$this->conectar();
			$aux = $this->consultar("UPDATE Usuario SET perfil = '".$ruta."' WHERE usuario ='".$usuario."', espera = '1' ");
			$this->desconectar();
			return $aux;
		}

	}
