<?php

  include_once "Aplicacion/Modelo/Modelo.php";


  class ChefBD extends Modelo
  {
    public function visualizarPedidos(){
      $this->conectar();
			$aux = $this->consultar("SELECT * FROM Pedido WHERE estado = 'solicitado' ORDER BY fecha ASC ");
			$this->desconectar();
			$datos = array();
			while($fila=mysqli_fetch_array($aux))
			{
				array_push($datos,$fila);
			}
			return $datos;
    }

    public function cocinarPedido($codigo_pedido){
      $this->conectar();
      $aux = $this->consultar("UPDATE Pedido SET estado = 'cocinado', fecha=NOW() WHERE codigo_pedido = '".$codigo_pedido."'");
      $this->desconectar();
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

  }
