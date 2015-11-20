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
	* @author Elian Nahum Zapata Alfonso 1151193
	*/


	$conexion = mysqli_connect("localhost","root","") or die (("Error " . mysqli_error($conexion)));
	$creacion = "CREATE DATABASE SantoSasoft";
	mysqli_query($conexion,$creacion);
	mysqli_close($conexion);

	$conexion = mysqli_connect("localhost","root","","SantoSasoft") or die(("Error " . mysqli_error($conexion)));

	//tabla Persona
	$tabla = "CREATE TABLE Persona(
		usuario VARCHAR (20),
		password VARCHAR(100) NOT NULL,
		rol VARCHAR (20),
		cedula VARCHAR (20) NOT NULL,
		nombres VARCHAR (50) NOT NULL,
		apellidos VARCHAR (50) NOT NULL,
		correo VARCHAR(100) NOT NULL,
		telefono VARCHAR (10) NOT NULL,
		direccion VARCHAR (50) NOT NULL,

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

	//tabla Empleado
	$tabla = "CREATE TABLE Empleado(
		usuario VARCHAR (20),
		salario DOUBLE,
		referencias_l VARCHAR (300) NOT NULL,
		referencias_p VARCHAR (300) NOT NULL,
		PRIMARY KEY(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Empleado<br>");
	}
	else
	{
		echo("No se creo la tabla Empleado<br>");
	}

	//tabla Cliente
	$tabla = "CREATE TABLE Cliente(
		usuario VARCHAR (20),
		PRIMARY KEY(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Ciente<br>");
	}
	else
	{
		echo("No se creo la tabla Ciente<br>");
	}

	//tabla Gerente
	$tabla = "CREATE TABLE Gerente(
		usuario VARCHAR (20),
		PRIMARY KEY(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Gerente<br>");
	}
	else
	{
		echo("No se creo la tabla Gerente<br>");
	}

	//tabla Chef
	$tabla = "CREATE TABLE Chef(
		usuario VARCHAR (20),
		PRIMARY KEY(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Chef<br>");
	}
	else
	{
		echo("No se creo la tabla Chef<br>");
	}

	//tabla Mesero
	$tabla = "CREATE TABLE Mesero(
		usuario VARCHAR (20),
		PRIMARY KEY(usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Mesero<br>");
	}
	else
	{
		echo("No se creo la tabla Mesero<br>");
	}


	//tabla Despensa
	$tabla = "CREATE TABLE Despensa(
		codigo_despensa VARCHAR (20),
		gerente VARCHAR (20) NOT NULL,
		PRIMARY KEY(codigo_despensa),
		FOREIGN KEY (gerente) REFERENCES Gerente(usuario)
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
		nombre_ingrediente VARCHAR (20),
		cantidad INT,
		descripcion VARCHAR (40),
		codigo_despensa VARCHAR (20) NOT NULL,
		PRIMARY KEY (nombre_ingrediente),
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
		nombre_receta VARCHAR (20),
		chef VARCHAR (20) NOT NULL,
		descripcion_proceso VARCHAR (800),
		PRIMARY KEY(nombre_receta),
		FOREIGN KEY (chef) REFERENCES Chef (usuario)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Receta<br>");
	}
	else
	{
		echo("No se creo la tabla Receta<br>");
	}

	//tabla Producto
	$tabla = "CREATE TABLE Producto(
		nombre_producto VARCHAR (20),
		nombre_receta VARCHAR (20)  NOT NULL, 
		precio DOUBLE,
		PRIMARY KEY(nombre_producto),
		FOREIGN KEY (nombre_receta) REFERENCES Receta (nombre_receta)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Producto<br>");
	}
	else
	{
		echo("No se creo la tabla Producto<br>");
	}

	//tabla Menu
	$tabla = "CREATE TABLE Menu(
		fecha DATETIME,
		nombre_producto VARCHAR (20) NOT NULL,
		gerente VARCHAR (20) NOT NULL,
		PRIMARY KEY(fecha),
		FOREIGN KEY (nombre_producto) REFERENCES Producto (nombre_producto),
		FOREIGN KEY (gerente) REFERENCES Gerente (usuario)
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
		producto VARCHAR (20) NOT NULL,
		fecha DATETIME NOT NULL,
		valor DOUBLE, 
		estado VARCHAR (1) NOT NULL,
		PRIMARY KEY(codigo_pedido),
		FOREIGN KEY (cliente) REFERENCES Cliente (usuario),
		FOREIGN KEY (mesero) REFERENCES Mesero (usuario),
		FOREIGN KEY (producto) REFERENCES Producto (nombre_producto)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Pedido<br>");
	}
	else
	{
		echo("No se creo la tabla Pedido<br>");
	}

	//tabla PlatoFuerte
	$tabla = "CREATE TABLE PlatoFuerte(
		nombre_producto VARCHAR (20),
		PRIMARY KEY(nombre_producto),
		FOREIGN KEY (nombre_producto) REFERENCES Producto (nombre_producto)

	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla PlatoFuerte<br>");
	}
	else
	{
		echo("No se creo la tabla PlatoFuerte<br>");
	}

	//tabla Bebida
	$tabla = "CREATE TABLE Bebida(
		nombre_producto VARCHAR (20),
		PRIMARY KEY(nombre_producto),
		FOREIGN KEY (nombre_producto) REFERENCES Producto (nombre_producto)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Bebida<br>");
	}
	else
	{
		echo("No se creo la tabla Bebida<br>");
	}

	//tabla Postre
	$tabla = "CREATE TABLE Postre(
		nombre_producto VARCHAR (20),
		PRIMARY KEY(nombre_producto),
		FOREIGN KEY (nombre_producto) REFERENCES Producto (nombre_producto)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Postre<br>");
	}
	else
	{
		echo("No se creo la tabla Postre<br>");
	}

	//tabla Adicional
	$tabla = "CREATE TABLE Adicional(
		nombre_producto VARCHAR (20),
		PRIMARY KEY(nombre_producto),
		FOREIGN KEY (nombre_producto) REFERENCES Producto (nombre_producto)
	)";

	if(mysqli_query($conexion,$tabla))
	{
		echo("Se creo la tabla Adicional<br>");
	}
	else
	{
		echo("No se creo la tabla Adicional<br>");
	}


	//se agrega el usuario de tipo gerente para arrancar el sistema
	$query = "INSERT INTO Persona VALUES('gerente1','".sha1("1234")."','Gerente','1090484602','Cristhian Leonardo','Leon Lizarazo','cristhian.leonlizarazo@gmail.com','3223898713', 'Altos de tamarindo casa N-12')";
	mysqli_query($conexion,$query);

	$query = "INSERT INTO Gerente VALUES(gerente1);";
	mysqli_query($conexion,$query);

	mysqli_close($conexion);

	?>





