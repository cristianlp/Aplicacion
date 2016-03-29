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


	$conexion = mysqli_connect("localhost","root","") or die (("Error " . mysqli_error($conexion)));
	$creacion = "CREATE DATABASE SantoSasoft";
	mysqli_query($conexion,$creacion);
	mysqli_close($conexion);

	$conexion = mysqli_connect("localhost","root","","SantoSasoft") or die(("Error " . mysqli_error($conexion)));

	//tabla Usuario
	$tabla = "CREATE TABLE Usuario(
		nombres VARCHAR (100) NOT NULL,
		apellidos VARCHAR (100) NOT NULL,
		cedula VARCHAR (20) NOT NULL,
		correo VARCHAR(100) NOT NULL,
		telefono VARCHAR (15) NOT NULL,
		direccion VARCHAR (100) NOT NULL,
		usuario VARCHAR (20),
		password VARCHAR(100) NOT NULL,
		rol VARCHAR (20),
		PRIMARY KEY(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Persona<br>");
	}
	else
	{
		echo("No se creo la tabla Persona<br>");
	}

	//tabla Despensa
	$tabla = "CREATE TABLE Despensa(
		codigo_despensa VARCHAR (20),
		gerente VARCHAR (20) NOT NULL,
		PRIMARY KEY(codigo_despensa),
		FOREIGN KEY (gerente) REFERENCES Usuario(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Despensa<br>");
	}
	else
	{
		echo("No se creo la tabla Despensa<br>");
	}

	//tabla Ingrediente
	$tabla = "CREATE TABLE Ingrediente(
		codigo_ingrediente VARCHAR (20),
		nombre_ingrediente VARCHAR (50),
		cantidad INT,
		unidad VARCHAR (20) NOT NULL,
		tipo VARCHAR(1) NOT NULL,
		codigo_despensa VARCHAR (20) NOT NULL,
		esta_en_menu VARCHAR (1) NOT NULL,
		PRIMARY KEY (codigo_ingrediente),
		FOREIGN KEY (codigo_despensa) REFERENCES Despensa (codigo_despensa)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Ingrediente<br>");
	}
	else
	{
		echo("No se creo la tabla Ingrediente<br>");
	}

	//tabla Receta
	$tabla = "CREATE TABLE Receta(
		codigo_receta VARCHAR (20),
		nombre_receta VARCHAR (50),
		chef VARCHAR (20) NOT NULL,
		esta_en_menu VARCHAR (1) NOT NULL,
		descripcion_proceso VARCHAR (5000),
		PRIMARY KEY(codigo_receta),
		FOREIGN KEY (chef) REFERENCES Usuario (usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Receta<br>");
	}
	else
	{
		echo("No se creo la tabla Receta<br>");
	}

	//tabla Menu
	$tabla = "CREATE TABLE Menu(
		fecha DATETIME,
		gerente VARCHAR (20) NOT NULL,
		PRIMARY KEY(fecha),
		FOREIGN KEY (gerente) REFERENCES Usuario (usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Menu<br>");
	}
	else
	{
		echo("No se creo la tabla Menu<br>");
	}


	//tabla Pedido
	$tabla = "CREATE TABLE Pedido(
		codigo_pedido VARCHAR (20),
		cliente VARCHAR (20) NOT NULL,
		mesero VARCHAR (20) NOT NULL,
		fecha DATETIME NOT NULL,
		valor DOUBLE,
		estado VARCHAR (20) NOT NULL,
		PRIMARY KEY(codigo_pedido),
		FOREIGN KEY (cliente) REFERENCES Usuario (usuario),
		FOREIGN KEY (mesero) REFERENCES Usuario (usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Pedido<br>");
	}
	else
	{
		echo("No se creo la tabla Pedido<br>");
	}

		//tabla IngredienteXreceta
		$tabla = "CREATE TABLE Ingrediente_Receta(
			codigo_receta VARCHAR (20),
			codigo_ingrediente VARCHAR (20),
			cantidad INT NOT NULL,
			PRIMARY KEY(codigo_receta, codigo_ingrediente),
			FOREIGN KEY (codigo_receta) REFERENCES Receta (codigo_receta),
			FOREIGN KEY (codigo_ingrediente) REFERENCES Ingrediente (codigo_ingrediente)
		)";

		if(mysqli_query($conexion,$tabla))
		{
			echo("Se creo la tabla relacion 1<br>");
		}
		else
		{
			echo("No se creo la tabla relacion 1<br>");
		}

		//tabla Receta_Pedido
		$tabla = "CREATE TABLE Receta_Pedido(
			codigo_receta VARCHAR (20),
			codigo_pedido VARCHAR (20),
			cantidad INT NOT NULL,
			PRIMARY KEY(codigo_receta, codigo_pedido),
			FOREIGN KEY (codigo_receta) REFERENCES Receta (codigo_receta) ON UPDATE CASCADE,
			FOREIGN KEY (codigo_pedido) REFERENCES Pedido (codigo_pedido) ON UPDATE CASCADE
		)";

		if(mysqli_query($conexion,$tabla))
		{
			echo("Se creo la tabla relacion 2<br>");
		}
		else
		{
			echo("No se creo la tabla relacion 2<br>");
		}

		//tabla ingrediente_pedido
		$tabla = "CREATE TABLE Ingrediente_Pedido(
			codigo_ingrediente VARCHAR (20),
			codigo_pedido VARCHAR (20),
			cantidad INT NOT NULL,
			PRIMARY KEY(codigo_ingrediente, codigo_pedido),
			FOREIGN KEY (codigo_ingrediente) REFERENCES Ingrediente (codigo_ingrediente),
			FOREIGN KEY (codigo_pedido) REFERENCES Pedido (codigo_pedido)
		)";

		if(mysqli_query($conexion,$tabla))
		{
			echo("Se creo la tabla relacion 3<br>");
		}
		else
		{
			echo("No se creo la tabla relacion 3<br>");
		}


	//se agrega el usuario de tipo gerente para arrancar el sistema
	$query = "INSERT INTO Usuario VALUES('First Name','Last Name','123456798','something@something.com','1234567891', 'Address', 'gerente1', '".sha1("1234")."', 'Gerente')";
	mysqli_query($conexion,$query);

	$query = "INSERT INTO Despensa VALUES('despensa1', 'gerente1')";
	mysqli_query($conexion,$query);

	$query = "INSERT INTO Menu VALUES(NOW(), 'gerente1')";
	mysqli_query($conexion,$query);

	mysqli_close($conexion);

	?>
