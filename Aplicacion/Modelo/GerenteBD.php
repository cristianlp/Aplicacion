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
    $cedula, $correo, $telefono, $direccion)
    {
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
      $aux = $this->consultar("SELECT r.codigo_receta, r.nombre_receta, c.nombres, c.apellidos, r.esta_en_menu
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
			$aux = $this->consultar("SELECT R.codigo_receta, R.nombre_receta, R.descripcion_proceso, C.nombres FROM Receta R, Usuario C WHERE codigo_receta = '".$codigo_receta."' AND C.usuario = R.chef");
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

    public function modificar_receta($codigo_receta, $nombre_receta, $chef, $descripcion_proceso){
      $this->conectar();
      $aux = $this->consultar(" UPDATE Receta SET nombre_receta = '".$nombre_receta."', chef = '".$chef."', descripcion_proceso = '".$descripcion_proceso."' WHERE codigo_receta = '".$codigo_receta."'");
      $this->desconectar();
      return $aux;
    }

    public function visualizarIngredientes(){
      $this->conectar();
      $aux = $this->consultar("SELECT * FROM Ingrediente ORDER BY esta_en_menu DESC");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos;
    }

    public function visualizarIngredientesReceta($codigo_receta){
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


    public function registrar_ingrediente($codigo, $nombre, $cantidad, $unidad, $tipo){
      $this->conectar();

			$aux = $this->consultar(
        "INSERT INTO Ingrediente VALUES('".$codigo."','".$nombre."',".$cantidad.",'".$unidad."','".$tipo."','despensa1', 'N');"
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

    public function visualizarIngredientesDeReceta($codigo_receta){
      $this->conectar();
      $aux = $this->consultar(
        "SELECT i.nombre_ingrediente, ir.cantidad, i.unidad from Ingrediente_Receta ir , Ingrediente i WHERE ir.codigo_receta = '".$codigo_receta."' AND ir.codigo_ingrediente = i.codigo_ingrediente"
      );
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos;
    }

    public function visualizarProductosCandidatos($esta){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Ingrediente WHERE tipo = 'P' AND esta_en_menu = '".$esta."'");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function visualizarRecetasCandidatas($esta){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Receta WHERE esta_en_menu = '".$esta."'");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function sacarTodoDelMenu(){
      $this->conectar();
			$aux = $this->consultar("UPDATE Ingrediente SET esta_en_menu = 'N' WHERE tipo = 'P' AND esta_en_menu = 'S'");
      $aux = $this->consultar("UPDATE Receta SET esta_en_menu = 'N' WHERE esta_en_menu = 'S'");
			$this->desconectar();
    }

    public function ingresarRecetaAlMenu($codigo){
      $this->conectar();
			$aux = $this->consultar("UPDATE Receta SET esta_en_menu = 'S' WHERE codigo_receta = '".$codigo."'");
			$this->desconectar();
    }

    public function ingresarProductoAlMenu($codigo){
      $this->conectar();
			$aux = $this->consultar("UPDATE Ingrediente SET esta_en_menu = 'S' WHERE tipo = 'P' AND codigo_ingrediente = '".$codigo."'");
			$this->desconectar();
    }

    public function editar_menu($usuario){
      $this->conectar();
			$aux = $this->consultar("UPDATE Menu SET gerente = '".$usuario."', fecha = NOW() ");
			$this->desconectar();
    }

    public function visualizarDatosMenu(){
      $this->conectar();
      $aux = $this->consultar("SELECT G.nombres, G.apellidos, m.fecha FROM Usuario G , Menu m WHERE m.gerente = G.usuario");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos[0];
    }

    public function visualizarPedidos(){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Pedido WHERE estado <> 'pagado' ORDER BY fecha ASC ");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function visualizarVentas(){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Pedido WHERE estado = 'pagado' ORDER BY fecha ASC ");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function estaIngrediente($codigo_ingrediente){
      $this->conectar();
      $aux = $this->consultar("SELECT * FROM Ingrediente WHERE codigo_ingrediente = '".$codigo_ingrediente."' ");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }

      if(count($datos) != 1){
        return false;
      }else{
        return true;
      }
    }


  }
