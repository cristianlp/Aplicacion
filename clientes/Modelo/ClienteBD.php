<?php

  include_once "Aplicacion/Modelo/Modelo.php";


  class ClienteBD extends Modelo
  {

      public function visualizarDomicilios(){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Domicilio ORDER BY fecha_entrega DESC ");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

      public function domiciliarioDisponible(){
          $this->conectar();
          $aux = $this->consultar("SELECT usuario FROM Usuario WHERE rol = 'Domiciliario' ORDER BY RAND() LIMIT 1 ");
          $this->desconectar();
          $usuario = '';
          
          while($fila = mysqli_fetch_array($aux))
          {
              $usuario = $fila['usuario'];
          }
          return $usuario;
      }

      public function guardarDomicilio($descripcion, $direccion, $usuario, $domiciliario){
          $this->conectar();
          $aux = $this->consultar("INSERT INTO Domicilio VALUES (NULL, '$descripcion', NULL, 
          '$direccion', '$usuario', '$domiciliario', NULL, 'espera')");
          $this->desconectar();

          if($aux){
              return true;
          }
          return false;
      }

      public function getUltimoDomicilio(){
          $this->conectar();
          $aux = $this->consultar("SELECT id FROM Domicilio ORDER BY id DESC LIMIT 1 ");
          $this->desconectar();
          $usuario = '';

          while($fila = mysqli_fetch_array($aux))
          {
              $usuario = $fila['id'];
          }
          return $usuario;
      }

      public function guardarProductosDomicilio($productos, $domicilio){

          $this->conectar();

          for($i = 0; $i < sizeof($productos); $i++){
              $producto = $productos[$i];

              $aux = $this->consultar("INSERT INTO ingrediente_domicilio  VALUES
                ( '$domicilio', '" . substr( $producto['codigo']  , 2). "' ,  '" . $producto['cantidad'] . "', '" . $producto['tipo'] . "')");

          }
          $this->desconectar();

          if($aux){
              return true;
          }
          return false;
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

      public function detallesDomicilio($codigo)
      {

          $this->conectar();
          $datos = $this->consultar("SELECT d.id as codigo, d.descripcion, d.fecha_entrega, d.direccion, u.nombres,
            u.apellidos, dom.nombres as dnom, dom.apellidos as dapell, d.tiempo_gastado, d.estado 
            FROM Domicilio d, Usuario u, Usuario dom 
	        WHERE d.id = ". $codigo." AND d.domiciliario = dom.usuario AND  d.usuario = u.usuario");
          $this->desconectar();
          while($fila = mysqli_fetch_array($datos))
          {
              $datos = $fila;
          }

          return $datos;

      }

      public function articulosDomicilio($codigo, $tipo)
      {

          $tabla = ($tipo == 'receta') ? 'Receta' : 'Ingrediente';
          $codigo_x = ($tipo == 'receta') ? 'codigo_receta' : 'codigo_ingrediente';
          $nombre_x = ($tipo == 'receta') ? 'nombre_receta' : 'nombre_ingrediente';

          $this->conectar();
          $consulta = "SELECT i.". $nombre_x ." as nombre, ido.cantidad
            from $tabla i, ingrediente_domicilio ido
            where ido.domicilio = ". $codigo  ." and ido.codigo_ingrediente = i.". $codigo_x;

          $aux = $this->consultar($consulta);
          $this->desconectar();
          $datos = array();
          while($fila = mysqli_fetch_array($aux))
          {
              array_push($datos,$fila);
          }

          return $datos;

      }

      public function visualizarReservas()
      {

          $this->conectar();
          $aux = $this->consultar("SELECT * FROM Reserva ORDER BY fecha_reserva DESC ");
          $this->desconectar();
          $datos = array();
          while($fila=mysqli_fetch_array($aux))
          {
              array_push($datos,$fila);
          }
          return $datos;

      }

      public function visualizarMesas()
      {

          $this->conectar();
          $aux = $this->consultar("SELECT * FROM Mesa WHERE estado = 'libre'");
          $this->desconectar();
          $datos = array();
          while($fila=mysqli_fetch_array($aux))
          {
              array_push($datos,$fila);
          }
          return $datos;

      }

      public function cambiarEstadoMesa($mesa, $estado)
      {
          
          $consulta = "UPDATE Mesa SET estado = '$estado' WHERE id = $mesa ";
          $this->conectar();
          $this->consultar($consulta);
          $this->desconectar();
      }

      public function getUltimaReserva(){
          $this->conectar();
          $aux = $this->consultar("SELECT id FROM Reserva ORDER BY id DESC LIMIT 1 ");
          $this->desconectar();
          $usuario = '';

          while($fila = mysqli_fetch_array($aux))
          {
              $usuario = $fila['id'];
          }
          return $usuario;
      }

      public function generarReserva($fecha, $usuario, $estado, $nombre)
      {

          $this->conectar();
          $this->consultar("INSERT INTO Reserva VALUES(NULL, " . $fecha . ", '$usuario', '$estado', '$nombre')");
          $this->desconectar();

      }

      public function generarReservaMesas($reserva, $mesa)
      {

          $this->conectar();
          $this->consultar("INSERT INTO reservacion_mesa VALUES(NULL, '$reserva', '$mesa')");
          $this->desconectar();


      }

      public function getMesasReserva($id)
      {

          $this->conectar();
          $aux = $this->consultar("select mesa.id from Mesa mesa, reservacion_mesa rm where rm.reserva = ".$id." and rm.mesa = mesa.id");
          $this->desconectar();
          $datos = array();
          while($fila=mysqli_fetch_array($aux))
          {
              array_push($datos,$fila['id']);
          }
          return $datos;


      }


  }
