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

  }
