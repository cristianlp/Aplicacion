-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2016 at 03:35 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `SantoSasoft`
--

-- --------------------------------------------------------

--
-- Table structure for table `Despensa`
--

CREATE TABLE IF NOT EXISTS `Despensa` (
  `codigo_despensa` varchar(20) NOT NULL DEFAULT '',
  `gerente` varchar(20) NOT NULL,
  PRIMARY KEY (`codigo_despensa`),
  KEY `gerente` (`gerente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Despensa`
--

INSERT INTO `Despensa` (`codigo_despensa`, `gerente`) VALUES
('despensa1', 'gerente1');

-- --------------------------------------------------------

--
-- Table structure for table `Domicilio`
--

CREATE TABLE IF NOT EXISTS `Domicilio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `domiciliario` varchar(100) NOT NULL,
  `tiempo_gastado` varchar(255) DEFAULT NULL,
  `estado` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `domiciliario` (`domiciliario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `Domicilio`
--

INSERT INTO `Domicilio` (`id`, `descripcion`, `fecha_entrega`, `direccion`, `usuario`, `domiciliario`, `tiempo_gastado`, `estado`) VALUES
(23, 'descripcion', NULL, 'mi casita', 'dsfsfd', 'Pepito', NULL, 'espera'),
(24, '', NULL, 'mi super casa', 'dsfsfd', 'Pepito', NULL, 'espera');

-- --------------------------------------------------------

--
-- Table structure for table `historial_despensa`
--

CREATE TABLE IF NOT EXISTS `historial_despensa` (
  `codigo_ingrediente` varchar(20) NOT NULL,
  `nombre_ingrediente` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `unidad` varchar(20) NOT NULL,
  `tipo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historial_despensa`
--

INSERT INTO `historial_despensa` (`codigo_ingrediente`, `nombre_ingrediente`, `cantidad`, `unidad`, `tipo`) VALUES
('pe', 'pezcado', 122, 'kilos', 'I'),
('pollo', 'Pollo-pechuga', 25, 'kg', 'I'),
('Tomate', 'Tomate', 16, 'Unidades', 'I'),
('Cebolla', 'Cebolla', 24, 'Unidades', 'I'),
('Arroz', 'Arroz', 6, 'Kg', 'I'),
('Pan', ' Pan de Bolita', 307, 'Unidades', 'P'),
('Pastas', 'Pastas-fideos', 23, 'Unidades', 'I'),
('Carne Molida', 'Carne Molida', 36, 'Kg', 'I'),
('Mojarra', 'Mojarra-pesacado', 9, 'Unidades', 'I'),
('Atún', 'Atún Pescado', 14, 'Unidades', 'I'),
('Limones', 'Limones', 9, 'kg', 'I'),
('Lentejas', 'Lentejas', 24, 'kg', 'I');

-- --------------------------------------------------------

--
-- Table structure for table `historial_empleados`
--

CREATE TABLE IF NOT EXISTS `historial_empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `historial_empleados`
--

INSERT INTO `historial_empleados` (`id`, `nombres`, `apellidos`, `cedula`, `correo`, `rol`, `fecha_salida`) VALUES
(1, 'nombre cajero', 'apellido cajero', '12345', 'cajero@gmail.com', 'Cajero', '2016-05-14 18:42:54');

-- --------------------------------------------------------

--
-- Table structure for table `historial_ventas`
--

CREATE TABLE IF NOT EXISTS `historial_ventas` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `total_ventas` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `historial_ventas`
--

INSERT INTO `historial_ventas` (`ID`, `total_ventas`) VALUES
(1, 1274.399024963379);

-- --------------------------------------------------------

--
-- Table structure for table `Ingrediente`
--

CREATE TABLE IF NOT EXISTS `Ingrediente` (
  `codigo_ingrediente` varchar(20) NOT NULL DEFAULT '',
  `nombre_ingrediente` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `unidad` varchar(20) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `codigo_despensa` varchar(20) NOT NULL,
  `esta_en_menu` varchar(1) NOT NULL,
  `precio_producto` float(6,3) NOT NULL,
  PRIMARY KEY (`codigo_ingrediente`),
  KEY `codigo_despensa` (`codigo_despensa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Ingrediente`
--

INSERT INTO `Ingrediente` (`codigo_ingrediente`, `nombre_ingrediente`, `cantidad`, `unidad`, `tipo`, `codigo_despensa`, `esta_en_menu`, `precio_producto`) VALUES
('Arroz', 'Arroz', 6, 'Kg', 'I', 'despensa1', 'N', 4.800),
('Atún', 'Atún Pescado', 14, 'Unidades', 'I', 'despensa1', 'N', 9.632),
('car', 'carne', 18, 'kilos', 'I', 'despensa1', 'N', 5.000),
('Carne Molida', 'Carne Molida', 18, 'Kg', 'I', 'despensa1', 'N', 7.600),
('Cebolla', 'Cebolla', 12, 'Unidades', 'I', 'despensa1', 'N', 0.600),
('gas', 'gaseosa', 11, 'unidades', 'P', 'despensa1', 'S', 2.200),
('Lentejas', 'Lentejas', 24, 'kg', 'I', 'despensa1', 'N', 3.800),
('Limones', 'Limones', 9, 'kg', 'I', 'despensa1', 'N', 250.000),
('Mojarra', 'Mojarra-pesacado', 9, 'Unidades', 'I', 'despensa1', 'N', 6.750),
('Pan', ' Pan de Bolita', 12, 'Unidades', 'P', 'despensa1', 'S', 1.000),
('Pastas', 'Pastas-fideos', 23, 'Unidades', 'I', 'despensa1', 'N', 5.350),
('pe', 'pezcado', 50, 'kilos', 'I', 'despensa1', 'N', 7.500),
('pollo', 'Pollo-pechuga', 25, 'kg', 'I', 'despensa1', 'N', 6.300),
('sl', 'sal', 20, 'kilos', 'I', 'despensa1', 'N', 1.300),
('Tomate', 'Tomate', 8, 'Unidades', 'I', 'despensa1', 'N', 0.400);

--
-- Triggers `Ingrediente`
--
DROP TRIGGER IF EXISTS `trigger_historial_despensa_actualizaciones`;
DELIMITER //
CREATE TRIGGER `trigger_historial_despensa_actualizaciones` BEFORE UPDATE ON `Ingrediente`
 FOR EACH ROW UPDATE historial_despensa SET cantidad=cantidad+NEW.cantidad
   where codigo_ingrediente=new.codigo_ingrediente
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_historial_despensa_inserciones`;
DELIMITER //
CREATE TRIGGER `trigger_historial_despensa_inserciones` BEFORE INSERT ON `Ingrediente`
 FOR EACH ROW INSERT INTO historial_despensa(codigo_ingrediente,nombre_ingrediente, cantidad,unidad,tipo)
   VALUES (NEW.codigo_ingrediente, NEW.nombre_ingrediente,NEW.cantidad, NEW.unidad, NEW.tipo)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ingredientes_caducados`
--

CREATE TABLE IF NOT EXISTS `ingredientes_caducados` (
  `codigo_ingrediente` varchar(20) NOT NULL,
  `nombre_ingrediente` varchar(50) NOT NULL,
  `cantidad_caducada` int(11) NOT NULL,
  PRIMARY KEY (`codigo_ingrediente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ingredientes_caducados`
--

INSERT INTO `ingredientes_caducados` (`codigo_ingrediente`, `nombre_ingrediente`, `cantidad_caducada`) VALUES
('car', 'carne', 2);

--
-- Triggers `ingredientes_caducados`
--
DROP TRIGGER IF EXISTS `trigger_ingredientes_caducados`;
DELIMITER //
CREATE TRIGGER `trigger_ingredientes_caducados` BEFORE INSERT ON `ingredientes_caducados`
 FOR EACH ROW UPDATE Ingrediente SET cantidad=cantidad-NEW.cantidad_caducada
   where codigo_ingrediente=NEW.codigo_ingrediente
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ingrediente_domicilio`
--

CREATE TABLE IF NOT EXISTS `ingrediente_domicilio` (
  `domicilio` int(11) NOT NULL DEFAULT '0',
  `codigo_ingrediente` varchar(20) NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  PRIMARY KEY (`domicilio`,`codigo_ingrediente`),
  KEY `codigo_ingrediente` (`codigo_ingrediente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ingrediente_domicilio`
--

INSERT INTO `ingrediente_domicilio` (`domicilio`, `codigo_ingrediente`, `cantidad`, `tipo`) VALUES
(23, 'gas', 3, 'producto'),
(23, 'Pan', 5, 'producto'),
(23, 'r4', 2, 'receta'),
(24, 'car', 1, 'receta'),
(24, 'gas', 1, 'producto'),
(24, 'Pan', 1, 'producto'),
(24, 'r2', 1, 'receta'),
(24, 'r3', 1, 'receta'),
(24, 'r4', 1, 'receta');

-- --------------------------------------------------------

--
-- Table structure for table `Ingrediente_Pedido`
--

CREATE TABLE IF NOT EXISTS `Ingrediente_Pedido` (
  `codigo_ingrediente` varchar(20) NOT NULL DEFAULT '',
  `codigo_pedido` varchar(20) NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`codigo_ingrediente`,`codigo_pedido`),
  KEY `codigo_pedido` (`codigo_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Ingrediente_Pedido`
--

INSERT INTO `Ingrediente_Pedido` (`codigo_ingrediente`, `codigo_pedido`, `cantidad`) VALUES
('gas', 'p1', 2),
('gas', 'p2', 3),
('gas', 'p7', 2),
('gas', 'p97', 1),
('gas', 'pp', 1),
('Pan', 'nuevop', 6),
('Pan', 'p3', 5),
('Pan', 'p5', 5),
('Pan', 'p6', 3);

--
-- Triggers `Ingrediente_Pedido`
--
DROP TRIGGER IF EXISTS `precio_pedido_auto2`;
DELIMITER //
CREATE TRIGGER `precio_pedido_auto2` AFTER INSERT ON `Ingrediente_Pedido`
 FOR EACH ROW UPDATE Pedido SET valor= valor+(SELECT SUM((I.precio_producto * A.cantidad)) FROM Ingrediente_Pedido A, Ingrediente I WHERE A.codigo_ingrediente=I.codigo_ingrediente AND A.codigo_pedido=NEW.codigo_pedido) WHERE codigo_pedido=NEW.codigo_pedido
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Ingrediente_Receta`
--

CREATE TABLE IF NOT EXISTS `Ingrediente_Receta` (
  `codigo_receta` varchar(20) NOT NULL DEFAULT '',
  `codigo_ingrediente` varchar(20) NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`codigo_receta`,`codigo_ingrediente`),
  KEY `codigo_ingrediente` (`codigo_ingrediente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Ingrediente_Receta`
--

INSERT INTO `Ingrediente_Receta` (`codigo_receta`, `codigo_ingrediente`, `cantidad`) VALUES
('car', 'car', 2),
('r2', 'Lentejas', 1),
('r2', 'sl', 2),
('r2', 'Tomate', 1),
('r3', 'Arroz', 1),
('r3', 'pollo', 1),
('r3', 'sl', 1),
('r4', 'Limones', 1),
('r4', 'Mojarra', 1),
('r4', 'sl', 1),
('r5', 'Arroz', 1),
('r5', 'car', 2),
('r6', 'Carne Molida', 3),
('r6', 'Pastas', 3),
('r7', 'Arroz', 2),
('r7', 'pollo', 3),
('r7', 'sl', 5),
('r7', 'Tomate', 4);

-- --------------------------------------------------------

--
-- Table structure for table `Menu`
--

CREATE TABLE IF NOT EXISTS `Menu` (
  `fecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gerente` varchar(20) NOT NULL,
  PRIMARY KEY (`fecha`),
  KEY `gerente` (`gerente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Menu`
--

INSERT INTO `Menu` (`fecha`, `gerente`) VALUES
('2016-05-30 15:15:49', 'gerente1');

-- --------------------------------------------------------

--
-- Table structure for table `Pedido`
--

CREATE TABLE IF NOT EXISTS `Pedido` (
  `codigo_pedido` varchar(20) NOT NULL DEFAULT '',
  `cliente` varchar(20) NOT NULL,
  `mesero` varchar(20) NOT NULL,
  `fecha` datetime NOT NULL,
  `valor` float(6,3) DEFAULT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`codigo_pedido`),
  KEY `cliente` (`cliente`),
  KEY `mesero` (`mesero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Pedido`
--

INSERT INTO `Pedido` (`codigo_pedido`, `cliente`, `mesero`, `fecha`, `valor`, `estado`) VALUES
('cc', 'kiio', 'mesero1', '2016-05-16 21:02:52', 1.000, 'cancelado'),
('jj', 'kkkk', 'mesero1', '2016-05-16 21:00:22', 14.000, 'cancelado'),
('nuevop', 'Juan', 'mesero1', '2016-05-16 21:16:11', 7.000, 'cancelado'),
('p1', 'hola', 'mesero1', '2016-05-15 07:32:10', 42.000, 'pagado'),
('p2', 'Carlos Lopez', 'mesero1', '2016-05-16 15:58:52', 42.000, 'solicitado'),
('p3', 'Luisa Lane', 'mesero1', '2016-05-16 18:21:16', 42.000, 'solicitado'),
('p4', 'SARA', 'mesero1', '2016-05-16 19:40:51', 42.000, 'solicitado'),
('p5', 'Alberto', 'mesero1', '2016-05-16 19:44:52', 42.000, 'solicitado'),
('p6', 'MARIA', 'mesero1', '2016-05-16 19:58:53', 42.000, 'solicitado'),
('p7', 'yo', 'mesero1', '2016-05-16 20:29:30', NULL, 'cancelado'),
('p8', 'YA', 'mesero1', '2016-05-16 20:29:21', 10.000, 'cancelado'),
('p9', 'aja', 'mesero1', '2016-05-16 20:29:25', 20.000, 'cancelado'),
('p97', 'aja', 'mesero1', '2016-05-24 08:01:24', 999.999, 'solicitado'),
('pp', 'Pepito', 'mesero1', '2016-05-16 21:14:25', 35.200, 'cancelado'),
('pq', 'Juan', 'mesero1', '2016-05-16 21:00:26', 19.500, 'cancelado'),
('rr', 'dsfsfd', 'mesero1', '2016-05-16 21:14:29', 33.000, 'cancelado'),
('vv', 'Pepe', 'mesero1', '2016-05-16 21:02:57', 1.000, 'cancelado');

--
-- Triggers `Pedido`
--
DROP TRIGGER IF EXISTS `trigger_historial_ventas`;
DELIMITER //
CREATE TRIGGER `trigger_historial_ventas` BEFORE UPDATE ON `Pedido`
 FOR EACH ROW UPDATE historial_ventas SET total_ventas=total_ventas+NEW.valor
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Receta`
--

CREATE TABLE IF NOT EXISTS `Receta` (
  `codigo_receta` varchar(20) NOT NULL DEFAULT '',
  `nombre_receta` varchar(50) DEFAULT NULL,
  `chef` varchar(20) NOT NULL,
  `esta_en_menu` varchar(1) NOT NULL,
  `descripcion_proceso` varchar(5000) DEFAULT NULL,
  `precio_receta` float(6,3) NOT NULL,
  PRIMARY KEY (`codigo_receta`),
  KEY `chef` (`chef`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Receta`
--

INSERT INTO `Receta` (`codigo_receta`, `nombre_receta`, `chef`, `esta_en_menu`, `descripcion_proceso`, `precio_receta`) VALUES
('car', 'carne con sal ', 'chef1', 'S', 'Con mucho cuidado', 5.000),
('r2', 'Lentejas', 'chef1', 'S', 'Preparacion de la lenteja', 5.500),
('r3', 'Arroz con pollo', 'chef1', 'S', 'Se prepara el arroz con pollo casero', 7.000),
('r4', 'Mojarra frita', 'chef1', 'S', 'se prepara la mojarra', 9.000),
('r5', 'Carne con arroz', 'chef1', 'N', 'Se hace arroz con carne', 6.500),
('r6', 'Pastas con carne molida', 'chef1', 'S', 'Se hace Pasta con carne', 8.000),
('r7', 'Pollo frito', 'chef1', 'S', 'Pollo frito con arroz', 7.500);

-- --------------------------------------------------------

--
-- Table structure for table `Receta_Pedido`
--

CREATE TABLE IF NOT EXISTS `Receta_Pedido` (
  `codigo_receta` varchar(20) NOT NULL DEFAULT '',
  `codigo_pedido` varchar(20) NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`codigo_receta`,`codigo_pedido`),
  KEY `codigo_pedido` (`codigo_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Receta_Pedido`
--

INSERT INTO `Receta_Pedido` (`codigo_receta`, `codigo_pedido`, `cantidad`) VALUES
('car', 'p2', 2),
('r3', 'p3', 1),
('r3', 'p4', 2),
('r3', 'p6', 3),
('r4', 'p3', 2),
('r4', 'p5', 2),
('r4', 'p9', 2),
('r4', 'pp', 2),
('r4', 'rr', 2),
('r5', 'p8', 2),
('r5', 'pq', 3),
('r7', 'p4', 3),
('r7', 'p5', 2),
('r7', 'p6', 2),
('r7', 'pp', 2),
('r7', 'rr', 2);

--
-- Triggers `Receta_Pedido`
--
DROP TRIGGER IF EXISTS `precio_pedido_auto`;
DELIMITER //
CREATE TRIGGER `precio_pedido_auto` AFTER INSERT ON `Receta_Pedido`
 FOR EACH ROW UPDATE Pedido SET valor=(SELECT SUM((R.precio_receta * A.cantidad)) FROM Receta_Pedido A, Receta R WHERE A.codigo_receta=R.codigo_receta AND A.codigo_pedido=NEW.codigo_pedido) WHERE codigo_pedido=NEW.codigo_pedido
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Usuario`
--

CREATE TABLE IF NOT EXISTS `Usuario` (
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Usuario`
--

INSERT INTO `Usuario` (`nombres`, `apellidos`, `cedula`, `correo`, `telefono`, `direccion`, `usuario`, `password`, `rol`, `tipo`, `perfil`) VALUES
('aja', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'aja', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('Alberto', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Alberto', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('Anita', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Anita', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('bayardo pineda', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'bayardo pineda', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('oscar', 'gelvez', '1234', 'oscar@gmail.com', '12345', 'calle falsa 1234', 'cajero1', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cajero', 'presencial', NULL),
('Carlos Lopez', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Carlos Lopez', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('bayardo', 'pineda', '123456', 'chef@gmail.com', '12345', 'calle falsa 1234', 'chef1', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Chef', 'presencial', NULL),
('dsfsfd', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'dsfsfd', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'virtual', NULL),
('First Name', 'Last Name', '123456798', 'something@something.com', '1234567891', 'Address', 'gerente1', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Gerente', 'presencial', NULL),
('hola', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'hola', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('Juan', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Juan', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('kiio', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'kiio', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('kkkk', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'kkkk', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('Luisa Lane', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Luisa Lane', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('MARIA', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'MARIA', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('carlos', 'gelvez', '12345', 'carlos@gmail.com', '1234', 'calle falsa 1234', 'mesero1', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Mesero', 'presencial', NULL),
('Pepe', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Pepe', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('Pepito', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'Pepito', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Domiciliario', 'presencial', NULL),
('SARA', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'SARA', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('YA', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'YA', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL),
('yo', 'presencial', 'presencial', 'presencial', 'presencial', 'presencial', 'yo', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Cliente', 'presencial', NULL);

--
-- Triggers `Usuario`
--
DROP TRIGGER IF EXISTS `trigger_historial_empleados`;
DELIMITER //
CREATE TRIGGER `trigger_historial_empleados` AFTER DELETE ON `Usuario`
 FOR EACH ROW INSERT INTO historial_empleados(nombres,apellidos, cedula, correo,rol,fecha_salida )
   VALUES (OLD.nombres, OLD.apellidos,OLD.cedula, OLD.correo, OLD.rol,NOW())
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Despensa`
--
ALTER TABLE `Despensa`
  ADD CONSTRAINT `Despensa_ibfk_1` FOREIGN KEY (`gerente`) REFERENCES `Usuario` (`usuario`);

--
-- Constraints for table `Domicilio`
--
ALTER TABLE `Domicilio`
  ADD CONSTRAINT `Domicilio_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `Usuario` (`usuario`),
  ADD CONSTRAINT `Domicilio_ibfk_2` FOREIGN KEY (`domiciliario`) REFERENCES `Usuario` (`usuario`);

--
-- Constraints for table `Ingrediente`
--
ALTER TABLE `Ingrediente`
  ADD CONSTRAINT `Ingrediente_ibfk_1` FOREIGN KEY (`codigo_despensa`) REFERENCES `Despensa` (`codigo_despensa`);

--
-- Constraints for table `ingrediente_domicilio`
--
ALTER TABLE `ingrediente_domicilio`
  ADD CONSTRAINT `ingrediente_domicilio_ibfk_1` FOREIGN KEY (`domicilio`) REFERENCES `Domicilio` (`id`);

--
-- Constraints for table `Ingrediente_Pedido`
--
ALTER TABLE `Ingrediente_Pedido`
  ADD CONSTRAINT `Ingrediente_Pedido_ibfk_1` FOREIGN KEY (`codigo_ingrediente`) REFERENCES `Ingrediente` (`codigo_ingrediente`),
  ADD CONSTRAINT `Ingrediente_Pedido_ibfk_2` FOREIGN KEY (`codigo_pedido`) REFERENCES `Pedido` (`codigo_pedido`);

--
-- Constraints for table `Ingrediente_Receta`
--
ALTER TABLE `Ingrediente_Receta`
  ADD CONSTRAINT `Ingrediente_Receta_ibfk_1` FOREIGN KEY (`codigo_receta`) REFERENCES `Receta` (`codigo_receta`),
  ADD CONSTRAINT `Ingrediente_Receta_ibfk_2` FOREIGN KEY (`codigo_ingrediente`) REFERENCES `Ingrediente` (`codigo_ingrediente`);

--
-- Constraints for table `Menu`
--
ALTER TABLE `Menu`
  ADD CONSTRAINT `Menu_ibfk_1` FOREIGN KEY (`gerente`) REFERENCES `Usuario` (`usuario`);

--
-- Constraints for table `Pedido`
--
ALTER TABLE `Pedido`
  ADD CONSTRAINT `Pedido_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `Usuario` (`usuario`),
  ADD CONSTRAINT `Pedido_ibfk_2` FOREIGN KEY (`mesero`) REFERENCES `Usuario` (`usuario`);

--
-- Constraints for table `Receta`
--
ALTER TABLE `Receta`
  ADD CONSTRAINT `Receta_ibfk_1` FOREIGN KEY (`chef`) REFERENCES `Usuario` (`usuario`);

--
-- Constraints for table `Receta_Pedido`
--
ALTER TABLE `Receta_Pedido`
  ADD CONSTRAINT `Receta_Pedido_ibfk_1` FOREIGN KEY (`codigo_receta`) REFERENCES `Receta` (`codigo_receta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Receta_Pedido_ibfk_2` FOREIGN KEY (`codigo_pedido`) REFERENCES `Pedido` (`codigo_pedido`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
