<?php

require_once "Aplicacion/Controlador/Controlador.php";
include_once "Aplicacion/Modelo/GerenteBD.php";

class Gerente extends Controlador
{

	public function inicioValidado()
	{
		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/gerente_home.html");
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);

	}

	public function init()
	{
		$plantilla = $this->leerPlantilla("Aplicacion/Vista/principal/principal.html");
		$barraSup=$this->leerPlantilla("Aplicacion/Vista/principal/barraSuperior.html");

		$barraSup = $this->reemplazar($barraSup, "{{nombre}}", $_SESSION["nombre"]);
		$barraSup = $this->reemplazar($barraSup, "{{rol}}", $_SESSION["rol"]);

		$barraLat = $this->leerPlantilla("Aplicacion/Vista/gerente/gerente_barra_lateral.html");
		$barraLat = $this->reemplazar($barraLat, "{{rol}}", $_SESSION["rol"]);

		$opciones_persona = $this->leerPlantilla("Aplicacion/Vista/principal/opciones_persona.html");
		$barraLat = $this->reemplazar($barraLat, "{{opciones_persona}}", $opciones_persona );

		$plantilla = $this->reemplazar($plantilla, "{{barra_superior}}", $barraSup);
		$plantilla = $this->reemplazar($plantilla, "{{barra_lateral}}", $barraLat);

		return $plantilla;
	}

	public function vistaCambioPassword()
	{
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/principal/cambioPassword.html");
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	/*
	*	GESTION DEL MENU
	*/
	public function vistaMenu()
	{
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaMenu.html");
		$gerenteBD = new GerenteBD();

		$workspace = $this->reemplazar($workspace, "{{modificador}}", $this->procesarDatosMenu($gerenteBD->visualizarDatosMenu()));
		$workspace = $this->reemplazar($workspace, "{{productos_candidatos}}", $this->procesarProductosCandidatos($gerenteBD->visualizarProductosCandidatos("N")));
		$workspace = $this->reemplazar($workspace, "{{recetas_candidatos}}", $this->procesarRecetasCandidatos($gerenteBD->visualizarRecetasCandidatas("N")));
		$workspace = $this->reemplazar($workspace, "{{menu_del_dia_1}}", $this->procesarProductosCandidatos($gerenteBD->visualizarProductosCandidatos("S")));
		$workspace = $this->reemplazar($workspace, "{{menu_del_dia_2}}", $this->procesarRecetasCandidatos($gerenteBD->visualizarRecetasCandidatas("S")));
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function procesarDatosMenu($datos){
		$hoy = date("j, Y, g:i a", strtotime($datos['fecha']));
		$mes = substr((date("F",strtotime($datos['fecha']))), 0, 3);
		$nombres = (explode(" ", $datos['nombres']));
		$apellidos = (explode(" ", $datos['apellidos']));
		$n1 = $nombres[0];
		$a1 = $apellidos[0];
		return $mes ." ". $hoy . "<br> por: " . $n1 . " " . $a1;
	}

	public function procesarProductosCandidatos($productos){
		$total = "";
		$modelo_fila = "
		<div class='mdl-shadow--2dp manito_card'>
			<span id='nombre'>{{nombre_ingrediente}}</span><br>
			<span id='cantidad_unidad'>{{cantidad_unidad}}</span>
			<span id='codigo_enviar' class='oculto'>P-{{codigo_ingrediente}}</span>
		</div>";
		for($i = 0; $i< count($productos); $i++){
			$producto = $productos[$i];
			$tr = $modelo_fila;
			$tr = $this->reemplazar($tr, "{{codigo_ingrediente}}", $producto['codigo_ingrediente']);
			$tr = $this->reemplazar($tr, "{{nombre_ingrediente}}", "<b>Nombre: </b>" . $producto['nombre_ingrediente']);
			$tr = $this->reemplazar($tr, "{{cantidad_unidad}}", "<b>Cantidad: </b>" . $producto['cantidad'] . " - " . $producto['unidad']);
			$total .= $tr;
		}

		return $total;
	}

	public function procesarRecetasCandidatos($productos){
		$total = "";
		$modelo_fila = "
		<div class='mdl-shadow--2dp manito_card'>
			<span id='codigo'>{{codigo_receta}}</span><br>
			<span id='nombre'>{{nombre_receta}}</span>
			<span id='codigo_enviar' class='oculto'>R-{{codigo_receta_2}}</span>
		</div>";
		for($i = 0; $i< count($productos); $i++){
			$producto = $productos[$i];
			$tr = $modelo_fila;
			$tr = $this->reemplazar($tr, "{{codigo_receta}}", "<b>C&oacute;digo: </b>" . $producto['codigo_receta']);
			$tr = $this->reemplazar($tr, "{{codigo_receta_2}}",$producto['codigo_receta']);
			$tr = $this->reemplazar($tr, "{{nombre_receta}}", "<b>Nombre: </b>" . $producto['nombre_receta']);
			$total .= $tr;
		}

		return $total;
	}

	public function guardar_menu($datos_menu, $valor){

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/vistaMenu.html");
		$gerenteBD = new GerenteBD();
		$gerenteBD->sacarTodoDelMenu();
		if($datos_menu != null ){
			$this->registrarProductosEnMenu($datos_menu);
		}


		$workspace = $this->reemplazar($workspace, "{{productos_candidatos}}", $this->procesarProductosCandidatos($gerenteBD->visualizarProductosCandidatos("N")));
		$workspace = $this->reemplazar($workspace, "{{recetas_candidatos}}", $this->procesarRecetasCandidatos($gerenteBD->visualizarRecetasCandidatas("N")));
		$workspace = $this->reemplazar($workspace, "{{menu_del_dia_1}}", $this->procesarProductosCandidatos($gerenteBD->visualizarProductosCandidatos("S")));
		$workspace = $this->reemplazar($workspace, "{{menu_del_dia_2}}", $this->procesarRecetasCandidatos($gerenteBD->visualizarRecetasCandidatas("S")));

		if($datos_menu != null){
			$plantilla = $this->alerta($plantilla, "Menú guardado con éxito", "success");
			$gerenteBD->editar_menu($_SESSION['usuario']);
		}else{
			if(intval($valor)!=0 ){
				$gerenteBD->editar_menu($_SESSION['usuario']);
			}
			$plantilla = $this->alerta($plantilla, "El Menú quedó vacío-Procure poner algo en el menú diario", "info");
		}
		$workspace = $this->reemplazar($workspace, "{{modificador}}", $this->procesarDatosMenu($gerenteBD->visualizarDatosMenu()));
		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function registrarProductosEnMenu($datos){
		$gerenteBD = new GerenteBD();
		for($i = 0; $i < count($datos) ; $i++){
			$codigo_completo = $datos[$i];
			$codigo_completo = (explode("-" , $codigo_completo));
			if($codigo_completo[0] == "R"){
				$gerenteBD->ingresarRecetaAlMenu($codigo_completo[1]);
			}else{
				$gerenteBD->ingresarProductoAlMenu($codigo_completo[1]);
			}
		}
	}

	//DESPENSA - INGREDIENTES

	public function vistaDespensa()
	{
		$plantilla = $this->cargarConsultaIngredientes();
		$this->mostrarVista($plantilla);
	}

	public function cargarConsultaIngredientes(){
		$gerenteBD = new GerenteBD();
		$ingredientes = $gerenteBD->visualizarIngredientes();

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/consultarIngredientes.html");
		$plantilla = $this->procesarConsultaIngredientes($plantilla, $workspace, $ingredientes);
		return $plantilla;
	}

	public function procesarConsultaIngredientes($plantilla, $workspace, $ingredientes){
		$total = "";
		$filaIngredienteModelo = $this->leerPlantilla("Aplicacion/Vista/gerente/filaIngrediente.html");
		for($i = 0; $i < count($ingredientes); $i++){
			$tr = $filaIngredienteModelo;
			$ingrediente = $ingredientes[$i];
			$tr = $this->reemplazar($tr, "{{codigo_ingrediente}}", $ingrediente['codigo_ingrediente']);
			$tr = $this->reemplazar($tr, "{{nombre_ingrediente}}", $ingrediente['nombre_ingrediente']);
			if($ingrediente['tipo'] == "I"){
				$tr = $this->reemplazar($tr, "{{tipo_ingrediente}}", "Ingrediente");
			}else{
				$tr = $this->reemplazar($tr, "{{tipo_ingrediente}}", "Producto preparado");
			}

			if($ingrediente['esta_en_menu'] == "S"){
				$tr = $this->reemplazar($tr, "{{esta_en_menu}}", "S&iacute;");
			}else{
				$tr = $this->reemplazar($tr, "{{esta_en_menu}}", "No");
			}

			$tr = $this->reemplazar($tr, "{{cantidad}}", $ingrediente['cantidad'] . " " . $ingrediente['unidad']);
			$total .= $tr;
		}
		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function vista_agregar_ingrediente($codigo_ingrediente){
		$gerenteBD = new GerenteBD();
		$plantilla = $this->init();

		$esta = $gerenteBD->estaIngrediente($codigo_ingrediente);
		if($esta){
			$plantilla = $this->cargarConsultaIngredientes();
			$plantilla = $this->alerta($plantilla, "No se puede registrar ese ingrediente-Digite otro código de ingrediente", "info");
		}else{
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registro_ingrediente.html");
			$workspace = $this->reemplazar($workspace, "{{codigo_ingrediente}}", $codigo_ingrediente);
			$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "Agregar un ingrediente");
			$workspace = $this->reemplazar($workspace, "{{tipo}}", "agregarIngrediente");
			$workspace = $this->reemplazar($workspace, "{{nombre_ingrediente}}", "");
			$workspace = $this->reemplazar($workspace, "{{cantidad}}", "");
			$workspace = $this->reemplazar($workspace, "{{unidad}}", "");
			$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Registrar");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		}

		
		$this->mostrarVista($plantilla);
	}

	public function agregar_ingrediente($codigo, $nombre, $cantidad, $unidad, $tipo){
		$gerenteBD = new GerenteBD();
		$ok = $gerenteBD->registrar_ingrediente($codigo, $nombre, $cantidad, $unidad, $tipo);
		if($ok){
			$plantilla = $this->cargarConsultaIngredientes();
			$plantilla = $this->alerta($plantilla, "Registro éxitoso", "success");
			$this->mostrarVista($plantilla);
		}else{
			$plantilla = $this->cargarConsultaIngredientes();
			$plantilla = $this->alerta($plantilla, "Registro no fué éxitoso", "error");
			$this->mostrarVista($plantilla);
		}
	}

	public function vista_editar_ingrediente($codigo_ingrediente){
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registro_ingrediente.html");

		$gerenteBD = new GerenteBD();
		$datos = $gerenteBD->visualizarIngrediente($codigo_ingrediente);

		$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "Modificar ingrediente");
		$workspace = $this->reemplazar($workspace, "{{codigo_ingrediente}}", $datos[0]);
		$workspace = $this->reemplazar($workspace, "{{nombre_ingrediente}}", $datos[1]);
		$workspace = $this->reemplazar($workspace, "{{cantidad}}", $datos[2]);
		$workspace = $this->reemplazar($workspace, "{{unidad}}", $datos[3]);

		$workspace = $this->reemplazar($workspace, "{{disable}}", "disabled");
		$workspace = $this->reemplazar($workspace, "{{tipo}}", "editarIngrediente");
		$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Guardar");

		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function editar_ingrediente($codigo, $nombre, $cantidad, $unidad){
		$gerenteBD = new GerenteBD();
		$ok = $gerenteBD->modificarIngrediente($codigo, $nombre, $cantidad, $unidad);

		$plantilla = $this->cargarConsultaIngredientes();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Edición éxitosa", "success");
		}else{
			$plantilla=$this->alerta($plantilla, "No se pudo realizar la edición", "error");
		}
		$this->mostrarVista($plantilla);
	}

	/*
	*	EMPLEADOS
	*/

	public function vistaEmpleados()
	{
		$plantilla = $this->cargarConsultaEmpleados();
		$this->mostrarVista($plantilla);
	}

	public function cargarConsultaEmpleados()
	{
		$gerenteBD = new GerenteBD();
		$empleados = $gerenteBD->visualizarEmpleados();

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/consultarEmpleados.html");
		$plantilla = $this->procesarConsultaEmpleados($plantilla, $workspace, $empleados);
		return $plantilla;
	}

	public function procesarConsultaEmpleados($plantilla, $workspace, $datos)
	{
		$total = "";
		$filaUsuarioModelo = $this->leerPlantilla("Aplicacion/Vista/gerente/filaEmpleado.html");
		for($i = 0; $i < count($datos); $i++){
			$tr = $filaUsuarioModelo;
			$persona = $datos[$i];
			$tr = $this->reemplazar($tr, "{{nombre}}", $persona['nombres'] . " " . $persona['apellidos']);
			$tr = $this->reemplazar($tr, "{{cedula}}", $persona['cedula']);
			$tr = $this->reemplazar($tr, "{{usuario}}", $persona['usuario']);
			$tr = $this->reemplazar($tr, "{{rol}}", $persona['rol']);
			$tr = $this->reemplazar($tr, "{{telefono}}", $persona['telefono']);
			$total .= $tr;
		}
		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function agregar_consultar_empleado($parametro_busqueda){
		$matches = array();
		preg_match("/\D*/", $parametro_busqueda, $matches);
		$gerenteBD = new GerenteBD();
		$plantilla = $this->init();
		$bnd = false;
		if(strcmp($matches[0], "") === 0){
			$datos = $gerenteBD->buscarEmpleadoPorCedula($parametro_busqueda);
			$workspace = "";
			if($datos != false){
				$bnd = true;
			}else{
				$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registroEmpleado.html");

				$workspace = $this->reemplazar($workspace, "{{usuario}}", "");
				$workspace = $this->reemplazar($workspace, "{{nombres}}", "");
				$workspace = $this->reemplazar($workspace, "{{apellidos}}", "");
				$workspace = $this->reemplazar($workspace, "{{cedula}}", $parametro_busqueda);
				$workspace = $this->reemplazar($workspace, "{{correo}}", "");
				$workspace = $this->reemplazar($workspace, "{{telefono}}", "");
				$workspace = $this->reemplazar($workspace, "{{direccion}}", "");
				$workspace = $this->reemplazar($workspace, "{{password}}", "");

				$workspace = $this->reemplazar($workspace, "{{disable}}", "");
				$workspace = $this->reemplazar($workspace, "{{tipo}}", "agregarEmpleado");
				$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Registrar");
				$workspace = $this->reemplazar($workspace, "{{che1}}", "checked");
				$workspace = $this->reemplazar($workspace, "{{che2}}", "");
				$workspace = $this->reemplazar($workspace, "{{che3}}", "");
				$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "El empleado no se encuentra registrado, ¿desea registrarlo?");
				$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
				$this->mostrarVista($plantilla);
			}

		}else{
			$datos = $gerenteBD->buscarEmpleadoPorUsuario($parametro_busqueda);
			$workspace = "";
			if($datos != false){
				$bnd = true;
			}else{
				$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registroEmpleado.html");
				$workspace = $this->reemplazar($workspace, "{{usuario}}", $parametro_busqueda);

				$workspace = $this->reemplazar($workspace, "{{nombres}}", "");
				$workspace = $this->reemplazar($workspace, "{{apellidos}}", "");
				$workspace = $this->reemplazar($workspace, "{{cedula}}", "");
				$workspace = $this->reemplazar($workspace, "{{correo}}", "");
				$workspace = $this->reemplazar($workspace, "{{telefono}}", "");
				$workspace = $this->reemplazar($workspace, "{{direccion}}", "");
				$workspace = $this->reemplazar($workspace, "{{password}}", "");

				$workspace = $this->reemplazar($workspace, "{{disable}}", "");
				$workspace = $this->reemplazar($workspace, "{{tipo}}", "agregarEmpleado");
				$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Registrar");
				$workspace = $this->reemplazar($workspace, "{{che1}}", "checked");
				$workspace = $this->reemplazar($workspace, "{{che2}}", "");
				$workspace = $this->reemplazar($workspace, "{{che3}}", "");
				$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "El empleado no se encuentra registrado, ¿desea registrarlo?");
				$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
				$this->mostrarVista($plantilla);
			}
		}//fin del si es numero o letra
		if($bnd == true){
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/detallesEmpleado.html");
			$cuerpo_tabla = "
			<tr><td><strong>Usuario </strong></td><td>".$datos['usuario']."</td></tr>
			<tr><td><strong>C&eacute;dula </strong></td><td>".$datos['cedula']."</td></tr>
			<tr><td><strong>Nombres </strong></td><td>".$datos['nombres']."</td></tr>
			<tr><td><strong>Apellidos </strong></td><td>".$datos['apellidos']."</td></tr>
			<tr><td><strong>Correo </strong></td><td>".$datos['correo']."</td></tr>
			<tr><td><strong>Tel&eacute;fono </strong></td><td>".$datos['telefono']."</td></tr>
			<tr><td><strong>Direcci&oacute;n </strong></td><td>".$datos['direccion']."</td></tr>";
			$workspace = $this->reemplazar($workspace, "{{rol}}", $datos['rol']);
			$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $cuerpo_tabla );
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}
	}

	public function agregarEmpleado($usuario, $nombres, $apellidos, $cedula,
	$correo, $telefono, $direccion, $rol, $password)
	{
		$gerenteBD = new GerenteBD();
		$password2 = $this->encriptarPassword($password);
		$ok = $gerenteBD->registrarEmpleado($usuario, $nombres, $apellidos, $cedula,
		$correo, $telefono, $direccion, $rol, $password2);
		if($ok){
			$plantilla = $this->cargarRegistroEmpleado();
			$plantilla = $this->alerta($plantilla, "Registro éxitoso", "success");
			$this->mostrarVista($plantilla);
		}else{
			$plantilla = $this->cargarRegistroEmpleado();
			$plantilla = $this->alerta($plantilla, "Registro no fué éxitoso", "error");
			$this->mostrarVista($plantilla);
		}
	}

	public function cargarRegistroEmpleado(){
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registroEmpleado.html");

		$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "Registro de empleados");
		$workspace = $this->reemplazar($workspace, "{{usuario}}", "");
		$workspace = $this->reemplazar($workspace, "{{nombres}}", "");
		$workspace = $this->reemplazar($workspace, "{{apellidos}}", "");
		$workspace = $this->reemplazar($workspace, "{{cedula}}", "");
		$workspace = $this->reemplazar($workspace, "{{correo}}", "");
		$workspace = $this->reemplazar($workspace, "{{telefono}}", "");
		$workspace = $this->reemplazar($workspace, "{{direccion}}", "");
		$workspace = $this->reemplazar($workspace, "{{password}}", "");

		$workspace = $this->reemplazar($workspace, "{{disable}}", "");
		$workspace = $this->reemplazar($workspace, "{{tipo}}", "agregarEmpleado");
		$workspace = $this->reemplazar($workspace, "{{che1}}", "checked");
		$workspace = $this->reemplazar($workspace, "{{che2}}", "");
		$workspace = $this->reemplazar($workspace, "{{che3}}", "");
		$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Registrar");

		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		return $plantilla;
	}

	public function vistaEditarEmpleado($usuario){
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registroEmpleado.html");

		$gerenteBD = new GerenteBD();
		$datos = $gerenteBD->visualizarEmpleado($usuario);

		$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "Modificar empleado");
		$workspace = $this->reemplazar($workspace, "{{usuario}}", $datos[0]);
		$workspace = $this->reemplazar($workspace, "{{nombres}}", $datos[1]);
		$workspace = $this->reemplazar($workspace, "{{apellidos}}", $datos[2]);
		$workspace = $this->reemplazar($workspace, "{{cedula}}", $datos[3]);
		$workspace = $this->reemplazar($workspace, "{{correo}}", $datos[4]);
		$workspace = $this->reemplazar($workspace, "{{telefono}}", $datos[5]);
		$workspace = $this->reemplazar($workspace, "{{direccion}}", $datos[6]);
		$workspace = $this->reemplazar($workspace, "{{password}}", $datos[7]);

		$workspace = $this->reemplazar($workspace, "{{disable}}", "disabled");
		$workspace = $this->reemplazar($workspace, "{{tipo}}", "editarEmpleado");
		$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Guardar");

		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function editarEmpleado($usuario, $nombres , $apellidos,
	$cedula, $correo, $telefono, $direccion){
		$gerenteBD = new GerenteBD();
		$ok = $gerenteBD->modificarUsuario($usuario, $nombres , $apellidos,
		$cedula, $correo, $telefono, $direccion);

		$plantilla = $this->cargarConsultaEmpleados();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Edición éxitosa", "success");
		}else{
			$plantilla=$this->alerta($plantilla, "No se pudo realizar la edición", "error");
		}
		$this->mostrarVista($plantilla);
	}

	public function eliminarEmpleado($usuario){
		$gerente = new GerenteBD();
		$ok = $gerente->eliminarEmpleado($usuario);
		$plantilla = $this->cargarConsultaEmpleados();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Empleado eliminado éxitosamente", "success");
		}else{
			$plantilla=$this->alerta($plantilla, "No se pudo eliminar el Empleado", "error");
		}
		$this->mostrarVista($plantilla);
	}

	/*
	* RECETAS
	*/

	public function vistaRecetas()
	{
		$plantilla = $this->cargarConsultaRecetas();
		$this->mostrarVista($plantilla);
	}

	public function cargarConsultaRecetas(){
		$gerenteBD = new GerenteBD();
		$recetas = $gerenteBD->visualizarRecetas();

		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/consultarRecetas.html");
		$plantilla = $this->procesarConsultaRecetas($plantilla, $workspace, $recetas);
		return $plantilla;
	}

	public function procesarConsultaRecetas($plantilla, $workspace, $recetas){
		$total = "";
		$filaRecetaModelo = $this->leerPlantilla("Aplicacion/Vista/gerente/filaReceta.html");
		for($i = 0; $i < count($recetas); $i++){
			$tr = $filaRecetaModelo;
			$receta = $recetas[$i];
			$tr = $this->reemplazar($tr, "{{codigo_receta}}", $receta['codigo_receta']);
			$tr = $this->reemplazar($tr, "{{nombre_receta}}", $receta['nombre_receta']);
			$tr = $this->reemplazar($tr, "{{Chef}}", $receta['nombres' ]. " " . $receta['apellidos']);
			if($receta['esta_en_menu'] == 'S'){
				$tr = $this->reemplazar($tr, "{{esta_en_menu}}", "S&iacute;");
			}else{
				$tr = $this->reemplazar($tr, "{{esta_en_menu}}", "No");
			}
			$total .= $tr;
		}
		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}

	public function agregar_consultar_receta($parametro_busqueda){
		$gerenteBD = new GerenteBD();
		$plantilla = $this->init();
		$datos = $gerenteBD->visualizarReceta($parametro_busqueda);

		if($datos != false){
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/detallesReceta.html");
			$workspace = $this->reemplazar($workspace, "{{codigo_receta}}", $datos['codigo_receta']);
			$workspace = $this->reemplazar($workspace, "{{nombre_receta}}", $datos['nombre_receta'] );
			$workspace = $this->reemplazar($workspace, "{{Chef}}", $datos['nombres'] );
			$workspace = $this->reemplazar($workspace, "{{ingredientes}}", $this->procesarConsultaIngredientesDeReceta($parametro_busqueda) );
			$workspace = $this->reemplazar($workspace, "{{descripcion_proceso}}", $datos['descripcion_proceso']);
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);
		}else{
			$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registro_receta.html");
			$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "Registrar receta");
			$workspace = $this->reemplazar($workspace, "{{codigo_receta}}", $parametro_busqueda);
			$workspace = $this->reemplazar($workspace, "{{nombre_receta}}", "");
			$workspace = $this->reemplazar($workspace, "{{tipo}}", "agregar_receta");
			$workspace = $this->reemplazar($workspace, "{{required}}", "required");
			$workspace = $this->reemplazar($workspace, "{{chefs}}", $this->procesarConsultaChefsReceta($gerenteBD->visualizarChefs()));
			$workspace = $this->reemplazar($workspace, "{{ingredientes}}", $this->procesarConsultaIngredientesReceta($gerenteBD->visualizarIngredientes()));
			$workspace = $this->reemplazar($workspace, "{{descripcion_proceso}}", "");
			$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Registrar");
			$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
			$this->mostrarVista($plantilla);

		}
	}

	public function procesarConsultaIngredientesDeReceta($codigo_receta){
		$gerenteBD = new GerenteBD();
		$ingredientes = $gerenteBD->visualizarIngredientesDeReceta($codigo_receta);

		$total = "";
		for($i = 0; $i < count($ingredientes); $i++){
			$ingrediente = $ingredientes[$i];
			$total .= "<b>- </b>" . $ingrediente['nombre_ingrediente'] . " " .$ingrediente['cantidad']. " " . $ingrediente['unidad'] . "<br>";
		}
		return $total;
	}

	public function procesarConsultaChefsReceta($chefs){
		$total = "";
		for($i = 0; $i < count($chefs); $i++){
			$chef = $chefs[$i];
			$total .= "<option value='".$chef['usuario']."''>".$chef['nombres'] ." " .$chef['apellidos']."</option>";
		}
		return $total;
	}

	public function procesarConsultaIngredientesReceta($ingredientes){
		$total = "";
		for($i = 0; $i < count($ingredientes); $i++){

			$ingrediente = $ingredientes[$i];
			if($ingrediente['tipo'] == 'I'){
				$total .= "<option value='".$ingrediente['codigo_ingrediente']."''>".$ingrediente['nombre_ingrediente']." - ".$ingrediente['unidad']. "</option>";
			}

		}
		return $total;
	}

	public function agregar_receta($codigo_receta, $nombre_receta, $chef,$cantidades,
	$ingredientes, $proceso){
		$gerenteBD = new GerenteBD();
		$ok = $gerenteBD->registrarReceta($codigo_receta, $nombre_receta, $chef, $proceso);
		for($i = 0; $i< count($ingredientes);$i++){
			$gerenteBD->agregarIngrediente_Receta($codigo_receta, $ingredientes[$i], $cantidades[$i]);
		}
		$plantilla = $this->cargarConsultaRecetas();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Receta registrada éxitosamente", "success");
		}else{
			$plantilla=$this->alerta($plantilla, "No se pudo registrar la receta", "error");
		}
		$this->mostrarVista($plantilla);

	}

	public function vista_editar_receta($codigo_receta)
	{
		$plantilla = $this -> init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/registro_receta.html");

		$gerenteBD = new GerenteBD();
		$datos_receta = $gerenteBD->visualizarReceta($codigo_receta);

		$workspace = $this->reemplazar($workspace, "{{mensaje_encabezado}}", "Modificar receta");
		$workspace = $this->reemplazar($workspace, "{{codigo_receta}}", $codigo_receta);
		$workspace = $this->reemplazar($workspace, "{{nombre_receta}}", $datos_receta['nombre_receta']);
		$workspace = $this->reemplazar($workspace, "{{tipo}}", "editar_receta");
		$workspace = $this->reemplazar($workspace, "{{chefs}}", $this->procesarConsultaChefsReceta($gerenteBD->visualizarChefs()) );

		$workspace = $this->reemplazar($workspace, "{{disable}}", "disabled");
		$workspace = $this->reemplazar($workspace, "{{required}}", "");
		$workspace = $this->reemplazar($workspace, "{{oculto}}", "style='display:none;'");
		$workspace = $this->reemplazar($workspace, "{{nombre_boton}}", "Guardar");
		$workspace = $this->reemplazar($workspace, "{{descripcion_proceso}}", $datos_receta['descripcion_proceso']);

		$plantilla = $this->reemplazar($plantilla, "{{workspace}}", $workspace);
		$this->mostrarVista($plantilla);
	}

	public function editar_receta($codigo_receta, $nombre_receta, $chef, $descripcion_proceso){
		$gerenteBD = new GerenteBD();
		$ok = $gerenteBD->modificar_receta($codigo_receta, $nombre_receta, $chef, $descripcion_proceso);

		$plantilla = $this->cargarConsultaRecetas();
		if($ok){
			$plantilla=$this->alerta($plantilla, "Edición éxitosa", "success");
		}else{
			$plantilla=$this->alerta($plantilla, "No se pudo realizar la edición", "error");
		}
		$this->mostrarVista($plantilla);
	}

	//PEDIDOS
	public function vistaPedidos()
	{
		$plantilla = $this->cargarConsultaPedidos();
		$this->mostrarVista($plantilla);
	}

	//VENTAS
	public function vistaVentas()
	{
		$plantilla = $this->cargarConsultaVentas();
		$this->mostrarVista($plantilla);
	}

	private function cargarConsultaVentas(){
		$gerenteBD = new GerenteBD();
		$pedidos = $gerenteBD->visualizarVentas();

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/consultaPedidos.html");
		$workspace = $this->reemplazar($workspace, "{{titulo}}", "Ventas");
		$plantilla = $this->procesarConsultaVentas($plantilla, $workspace, $pedidos, "V");
		return $plantilla;
	}

	private function cargarConsultaPedidos(){
		$gerenteBD = new GerenteBD();
		$pedidos = $gerenteBD->visualizarPedidos();

		$plantilla = $this->init();
		$workspace = $this->leerPlantilla("Aplicacion/Vista/gerente/consultaPedidos.html");
		$workspace = $this->reemplazar($workspace, "{{titulo}}", "Pedidos");
		$plantilla = $this->procesarConsultaVentas($plantilla, $workspace, $pedidos);
		return $plantilla;
	}

	public function procesarConsultaVentas($plantilla, $workspace, $datos)
	{
		$total = "";
		$filaModelo = $this->leerPlantilla("Aplicacion/Vista/gerente/fila_pedido.html");
		for($i = 0; $i < count($datos); $i++){
			$tr = $filaModelo;
			$pedido = $datos[$i];
			$tr = $this->reemplazar($tr, "{{codigo_pedido}}", $pedido['codigo_pedido']);
			$tr = $this->reemplazar($tr, "{{cliente}}", $pedido['cliente']);
			$tr = $this->reemplazar($tr, "{{mesero}}", $pedido['mesero']);

			$hoy = date("j, Y, g:i a", strtotime($pedido['fecha']));
			$mes = substr((date("F",strtotime($pedido['fecha']))), 0 ,3);
			$tr = $this->reemplazar($tr, "{{fecha}}", $mes . " " . $hoy);

			$tr = $this->reemplazar($tr, "{{valor}}", $pedido['valor']);
			$tr = $this->reemplazar($tr, "{{estado}}", $pedido['estado']);
			$total .= $tr;
		}
		$workspace = $this->reemplazar($workspace, "{{cuerpo_tabla}}", $total);
		return $this->reemplazar($plantilla, "{{workspace}}", $workspace);
	}
}
