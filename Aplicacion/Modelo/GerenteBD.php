<?php

  include_once "Aplicacion/Modelo/Modelo.php";


  class GerenteBD extends Modelo
  {

    public function visualizarEmpleados(){
      $this->conectar();
			$aux = $this->consultar("SELECT usuario, cedula, rol, nombres, apellidos, correo, telefono
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

    public function registrarEmpleado($usuario, $nombres, $apellidos, $cedula,
      $correo, $telefono, $direccion, $rol, $password){
      $this->conectar();
			$aux = $this->consultar(
        "INSERT INTO Usuario VALUES('".$nombres."','".$apellidos."','".$cedula."','".$correo."','".$telefono."','".$direccion."','".$usuario."','".$password."','".$rol."');"
      );
			$this->desconectar();
			if($aux)
			{
				return true;
			}
			return false;
    }

    public function visualizarEmpleado($usuario){
      $this->conectar();
			$aux = $this->consultar("SELECT usuario, nombres, apellidos, cedula, correo, telefono, direccion, password FROM Usuario WHERE usuario = '".$usuario."' AND (rol = 'Chef' OR rol = 'Cajero' OR rol = 'Mesero')");
			$datos = array();
			while($fila = mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos[0];
    }

    public function modificarUsuario($usuario, $nombres , $apellidos,
    $cedula, $correo, $telefono, $direccion){

			$this->conectar();
			$aux = $this->consultar(" UPDATE Usuario SET nombres = '".$nombres."', apellidos = '".$apellidos."' , cedula = '".$cedula."' , correo = '".$correo."', telefono = '".$telefono."', direccion='".$direccion."' WHERE usuario = '".$usuario."'");
			$this->desconectar();
			return $aux;
    }

    public function eliminarEmpleado($usuario){
      $this->conectar();
			$aux = $this->consultar("DELETE FROM Usuario WHERE usuario = '".$usuario."'");
			$this->desconectar();
			return $aux;
    }

    public function visualizarRecetas(){

      $this->conectar();
      $aux = $this->consultar("SELECT r.codigo_receta, r.nombre_receta, c.nombres, c.apellidos
         FROM Receta r, Usuario c WHERE r.chef = c.usuario AND c.rol = 'Chef' ");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos;
    }

    public function visualizarReceta($codigo_receta){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Receta WHERE codigo_receta = '".$codigo_receta."'");
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

    public function visualizarIngredientes(){
      $this->conectar();
      $aux = $this->consultar("SELECT * FROM Ingrediente");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos;
    }

    public function registrar_ingrediente($codigo, $nombre, $cantidad, $unidad){
      $this->conectar();

			$aux = $this->consultar(
        "INSERT INTO Ingrediente VALUES('".$codigo."','".$nombre."',".$cantidad.",'".$unidad."','despensa1', 'N');"
      );

			$this->desconectar();

			if($aux)
			{
				return true;
			}
			return false;
    }

    public function visualizarIngrediente($codigo_ingrediente){
      $this->conectar();
      $aux = $this->consultar("SELECT * FROM Ingrediente WHERE codigo_ingrediente = '".$codigo_ingrediente."'");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos[0];
    }
    public function modificarIngrediente($codigo, $nombre, $cantidad, $unidad){
      $this->conectar();
      $aux = $this->consultar(" UPDATE Ingrediente SET nombre_ingrediente = '".$nombre."', cantidad = '".$cantidad."' , unidad = '".$unidad."' WHERE codigo_ingrediente = '".$codigo."'");
      $this->desconectar();
      return $aux;
    }

    public function visualizarChefs(){
      $this->conectar();
      $aux = $this->consultar("SELECT * FROM Usuario WHERE rol = 'Chef'");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos;
    }

    public function agregarIngrediente_Receta($codigo_receta, $codigo_ingrediente, $cantidad){
      $this->conectar();
			$aux = $this->consultar(
        "INSERT INTO Ingrediente_Receta VALUES('".$codigo_receta."','".$codigo_ingrediente."',$cantidad);"
      );
			$this->desconectar();
			if($aux)
			{
				return true;
			}
			return false;
    }

    public function registrarReceta($codigo_receta, $nombre_receta,$chef, $proceso){
      $this->conectar();
			$aux = $this->consultar(
        "INSERT INTO Receta VALUES('".$codigo_receta."','".$nombre_receta."','".$chef."', 'N', '".$proceso."');"
      );
			$this->desconectar();
			if($aux)
			{
				return true;
			}
			return false;
    }

  }
