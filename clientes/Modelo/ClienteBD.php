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

    public function procesarConsultaItemsPedido($codigo_pedido){
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

    public function buscarPedido($codigo_pedido){
      $this->conectar();
			$aux = $this->consultar("SELECT P.codigo_pedido, C.nombres, C.apellidos, M.nombres, M.apellidos, P.fecha, P.valor, P.estado
         FROM Pedido P, Usuario C, Usuario M WHERE P.codigo_pedido = '".$codigo_pedido."' AND P.cliente = C.usuario AND P.mesero = M.usuario AND
         C.rol = 'Cliente' AND M.rol = 'Mesero'");
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

    public function buscarVenta($codigo_pedido){
      $this->conectar();
      $aux = $this->consultar("SELECT P.codigo_pedido, C.nombres, C.apellidos, M.nombres, M.apellidos, P.fecha, P.valor, P.estado
         FROM Pedido P, Usuario C, Usuario M WHERE P.estado='pagado' AND P.codigo_pedido = '".$codigo_pedido."' AND P.cliente = C.usuario AND P.mesero = M.usuario AND
         C.rol = 'Cliente' AND M.rol = 'Mesero'");
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

    public function visualizarMeseros(){
      $this->conectar();
      $aux = $this->consultar("SELECT * FROM Usuario WHERE rol = 'Mesero'");
      $this->desconectar();
      $datos = array();
      while($fila=mysqli_fetch_array($aux))
      {
        array_push($datos,$fila);
      }
      return $datos;
    }

    public function visualizarItemsCandidatos(){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Ingrediente WHERE tipo = 'P' AND esta_en_menu = 'S'");
      $aux2 = $this->consultar("SELECT * FROM Receta WHERE esta_en_menu = 'S'");
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}

      $datos2 = array();
			while($fila=mysqli_fetch_array($aux2))
			{
				array_push($datos2,$fila);
			}

      $res = array();

      array_push($res, $datos);
      array_push($res, $datos2);
			$this->desconectar();
			return $res;
    }

    public function visualizarIngredientesPedido($codigo_pedido){
      $this->conectar();
      $aux = $this->consultar("SELECT I.nombre_ingrediente, ip.cantidad FROM Ingrediente I, Ingrediente_Pedido ip WHERE I.codigo_ingrediente = ip.codigo_ingrediente AND ip.codigo_pedido = '".$codigo_pedido."'");
      $this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function visualizarRecetasPedido($codigo_pedido){
      $this->conectar();
      $aux = $this->consultar("SELECT R.nombre_receta, rp.cantidad FROM Receta R, Receta_Pedido rp WHERE R.codigo_receta = rp.codigo_receta AND rp.codigo_pedido = '".$codigo_pedido."'");
      $this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function registrarReceta_Pedido($codigo_receta, $codigo_pedido, $cantidad){
      $this->conectar();
      $aux = $this->consultar("INSERT INTO Receta_Pedido VALUES ('".$codigo_receta."','".$codigo_pedido."', ". intval($cantidad) .")");
      $this->desconectar();
    }

    public function registrarIngrediente_Pedido($codigo_ingrediente, $codigo_pedido, $cantidad){
      $this->conectar();
      $aux = $this->consultar("INSERT INTO Ingrediente_Pedido VALUES ('".$codigo_ingrediente."','".$codigo_pedido."', ". intval($cantidad) .")");
      $this->desconectar();
    }

    public function restarExistenciaIngrediente($item, $cantidad){
      $this->conectar();
      $aux = $this->consultar("UPDATE Ingrediente SET cantidad = IFNULL(cantidad, 0)-".$cantidad." WHERE codigo_ingrediente = '".$item."' ");
      $this->desconectar();
    }

    public function estaCliente($codigo){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Usuario WHERE rol = 'Cliente' AND usuario = '".$codigo."'");
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
      public function registrarClientePresencial($cliente){
        $this->conectar();
        $aux = $this->consultar("INSERT INTO Usuario VALUES ('".$cliente."', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial' ,'".$cliente."', '".sha1("1234")."', 'Cliente')");
        $this->desconectar();
        return $aux;
      }

      public function registarPedido($codigo_pedido, $cliente, $mesero, $valor){
        $this->conectar();
        $aux = $this->consultar("INSERT INTO Pedido VALUES ('".$codigo_pedido."','".$cliente."','".$mesero."',NOW(),".$valor.",'solicitado')");
        $this->desconectar();

        if($aux){
          return true;
        }
        return false;
      }

      public function pagarPedido($codigo_pedido){
        $this->conectar();
      	$aux = $this->consultar("UPDATE Pedido SET estado = 'pagado', fecha=NOW() WHERE codigo_pedido = '".$codigo_pedido."'");
      	$this->desconectar();
      }

      public function cancelarPedido($codigo_pedido){
        $this->conectar();
        $aux = $this->consultar("UPDATE Pedido SET estado = 'cancelado', fecha=NOW() WHERE codigo_pedido = '".$codigo_pedido."'");
        $this->desconectar();
      }

      public function buscarCantidadDeIngrediente($codigo_item){
        $this->conectar();
        $aux = $this->consultar("SELECT cantidad FROM Ingrediente WHERE codigo_ingrediente = '".$codigo_item."'");
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

      public function eliminarPedido($codigo_pedido){
        $this->conectar();
        $this->consultar("DELETE FROM Ingrediente_Pedido WHERE codigo_pedido = '".$codigo_pedido."'");
        $this->consultar("DELETE FROM Pedido WHERE codigo_pedido = '".$codigo_pedido."'");
        $this->desconectar();
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


  }
