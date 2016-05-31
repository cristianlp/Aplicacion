<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA   DE   SISTEMAS
 	*   SANTO SASOFT - RESTAURANTE-BAR SANTO SAZON
 	*             SAN JOSE DE CUCUTA-2015
	* .............................................
 	*/

 	/**
	* @author Cristhian Leonardo León 1151023
	* @author Oscar Andres Gelvez Soler 1150973
	* @author Bayardo Dandenis Pineda Mogollon 1150982
	*/

	include_once ('Modelo/AdministradorBD.php');


	class Controlador{

		/**
		* Metodo que toma el archivo estatico de la pagina inicial y lo carga en pantalla
		*/
		public function inicio(){
			$inicio = $this->leerPlantilla(__DIR__ . "/../Vista/principal/index.html");
			$this->mostrarVista($inicio);
		}

		/**
		* Metodo que carga un archivo de la vista
		* @param $plantilla - Ruta del archivo a cargar
		* @return string con el valor html que debe ser mostrado
		*/
		public function leerPlantilla($plantilla)
		{
			return file_get_contents($plantilla);
		}

		/**
		*	Toma una vista y la muestra en pantalla en el cliente
		* 	@param $vista - vista preconstruida para mostrar en el navegador
		*/
		public function mostrarVista($vista)
		{
			echo $vista;
		}

		/**
		*	Reemplaza un valor por otro en una cadena de texto. Utilizado para formatear las vistas
		* 	@param $ubicacion - String donde se reemplazará el valor
		* 	@param $cadenaReemplazar - Cadena que será buscada en la $ubicación
		*	@param $reemplazo - Cadena con la que se reemplazará $cadenaReemplazar
		*	@return cadena sobreescrita
		*/
		public function reemplazar($ubicacion, $cadenaReemplazar, $reemplazo)
		{
			return str_replace($cadenaReemplazar, $reemplazo, $ubicacion);
		}

		/**
		*	Método que se encarga de iniciar la variable de sesión con el username y la foto de perfil del usuario
		*	@param   $nombre - nombre del usuario
		*/
		public function cargarPerfil($nombre, $rol, $usuario){
			$_SESSION["nombre"]=$nombre;
			$_SESSION["rol"]=$rol;
			$_SESSION["usuario"]=$usuario;
		}

		/**
		*	Metodo que inicia sesión y crea una clase del tipo de usuario correspondiente
		*	@param $cedula - Numero de cedula del usuario a verificar
		*	@param $contrasena - Contrasena del usuario a verificar
		*/
		public function login($usuario, $password){
			$passwordSSH=$this->encriptarPassword($password);

			$admin = new AdministradorBD();
			
			//echo $passwordSSH . '<=>' . $usuario;die();
			$datos = $admin->login($usuario, $passwordSSH);

			if($datos != false){

				$nombres = explode(" ", $datos[0]);
				$nombre1 = $nombres[0];

				$apellidos = explode(" ", $datos[1]);
				$apellido1 = $apellidos[0];

				$this->cargarPerfil( $nombre1." ". $apellido1, $datos[2], $usuario);
				header('Location: index.php');
			}else{
				$inicio = $this->leerPlantilla(__DIR__ . '/../Vista/principal/index.html');
				$inicio = $this->errorDeLogin($inicio);
				//$inicio = $this->alerta($inicio, "No se ha podido iniciar sesión", "Verifique sus datos e intentelo nuevamente");
				$this->mostrarVista($inicio);
			}

		}

		/**
		*
		*/
		public function errorDeLogin($plantilla){
			return $plantilla."<script>shake('.contendor_formulario'); errorCampoTexto('.input_texto');</script>";
		}

		/**
		*	Metodo de seguridad. Encripta la contraseña mediante el algoritmo SHA1.
		*	Todas las validaciones y almacenamientos se hacen en este sistema.
		*	Las bases de datos no guardaran contraseñas tal cual las incluye el usuario.
		*	@param $password - Contraseña a encriptar
		*	@return contraseña encriptada en SHA1
		*/
		public function encriptarPassword($password){
			return sha1($password);
		}

		/**
		*	Método que se encarga de agregar una alerta al documento html
		*	@param   $plantilla - plantilla sobre la cua se debe mostrar la alerta
		*	@param   $titulo - titulo de la alerta
		*	@param   $alerta - mensaje de la alerta
		*	@return  un string del html de la plantilla que permite la ejecucion de la alerta
		*/
		public function alerta($plantilla, $titulo, $alerta)
		{
			return $plantilla."<script>alerta(\"".$titulo."\",\"".$alerta."\");</script>";
		}

		/**
		*	Método que se encarga de asignar el nombre con el que se guardará la imagen de perfil del usuario
		*	@param   $imagen - nombre de la nueva imagen de perfil del usuario
		*	@return  un string con el nombre con el que se almacenará la imagen
		*/
		public function procesarImagen($imagen)
		{
			$nombre = $_FILES['imagen']['name'];
			if($nombre!=""){
				if(!file_exists("Estatico/img/modelo/".$nombre)){
					move_uploaded_file($_FILES['imagen']['tmp_name'],"Estatico/img/modelo/".$nombre);
				}else{
					$cont=1;
					while(file_exists("Estatico/img/modelo/"."[".$cont."]".$nombre)){
						$cont++;
					}
					move_uploaded_file($_FILES['imagen']['tmp_name'],"Estatico/img/modelo/"."[".$cont."]".$nombre);
					$nombre =  "[".$cont."]".$_FILES['imagen']['name'];
				}
			}
			return $nombre;
		}
	}
