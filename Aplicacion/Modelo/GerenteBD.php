<?php

  include_once "Aplicacion/Modelo/Modelo.php";


  class GerenteBD extends Modelo
  {

    public function visualizarEmpleados(){
      $this->conectar();
			$aux = $this->consultar("SELECT usuario, rol, nombres, apellidos, correo, telefono
         FROM Usuario WHERE rol = 'Cajero' OR rol='Chef' OR rol='Mesero'");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function buscarEmpleadoPorCedula($cedula)
    {
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Usuario WHERE (rol='Chef' OR rol='Mesero' OR rol='Cajero')
        AND (cedula='".$cedula."')");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}

      if(count($datos) != 1){
        return false;
      }else{
        return $datos[0];
      }
    }

    public function buscarEmpleadoPorUsuario($usuario){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Usuario WHERE (rol='Chef' OR rol='Mesero' OR rol='Cajero')
        AND (usuario='".$usuario."')");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}

      if(count($datos) != 1){
        return false;
      }else{
        return $datos[0];
      }
    }

  }
