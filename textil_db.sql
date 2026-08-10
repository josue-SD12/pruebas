-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-08-2026 a las 02:28:50
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `textil_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listaclientes`
--

CREATE TABLE `listaclientes` (
  `id_cliente` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `direccion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `listaclientes`
--

INSERT INTO `listaclientes` (`id_cliente`, `nombre`, `correo`, `telefono`, `fecha_registro`, `direccion`) VALUES
(41, 'maria', 'maria.lopez@gmail.com', '987654321', '2025-11-24 14:12:05', 'lima'),
(42, 'carlos', 'carlosr@gmail.com', '956412378', '2025-11-24 15:30:22', NULL),
(43, 'ana', 'ana.torres@hotmail.com', '912345678', '2025-11-25 13:45:10', 'trujillo'),
(44, 'jose', 'jperez@gmail.com', '998877665', '2025-11-25 16:20:00', NULL),
(45, 'rosa', 'rosag@gmail.com', '945612378', '2025-11-25 20:05:33', 'chiclayo'),
(46, 'pedro', 'pedrocastillo@gmail.com', '987412365', '2025-11-26 14:00:00', NULL),
(47, 'lucia', 'luciamendoza@gmail.com', '923456781', '2025-11-26 18:40:12', 'arequipa'),
(48, 'jorge', 'jorgevalle@gmail.com', '934567891', '2025-11-26 22:22:45', NULL),
(49, 'patricia', 'patriciasoto@gmail.com', '945678912', '2025-11-27 13:10:30', 'cusco'),
(50, 'miguel', 'miguelrios@gmail.com', '956789123', '2025-11-27 15:55:19', NULL),
(51, 'daniela', 'danielaflores@gmail.com', '967891234', '2025-11-27 19:33:50', 'lima'),
(52, 'ernesto', 'werwerer@gmail.com', '243', '2025-11-28 14:18:22', 'callao'),
(53, 'sofia', 'sofiaruiz@gmail.com', '978912345', '2025-11-28 16:44:08', NULL),
(54, 'diego', 'diegomorales@gmail.com', '989123456', '2025-11-28 21:02:37', 'piura'),
(55, 'valeria', 'valeriacruz@gmail.com', '990123456', '2025-11-29 14:30:00', 'lima');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `idNota` int NOT NULL,
  `tipo_cliente` varchar(50) DEFAULT NULL,
  `tipo_nota` varchar(50) DEFAULT NULL,
  `diseño` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `talla` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `vendedora` varchar(100) DEFAULT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `nombre_completo` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `agencia_envio` varchar(100) DEFAULT NULL,
  `tipo_delivery` varchar(50) DEFAULT NULL,
  `tipo_cliente_extra` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`idNota`, `tipo_cliente`, `tipo_nota`, `diseño`, `categoria`, `talla`, `descripcion`, `vendedora`, `dni`, `nombre_completo`, `telefono`, `direccion`, `provincia`, `distrito`, `departamento`, `agencia_envio`, `tipo_delivery`, `tipo_cliente_extra`) VALUES
(1, 'Mayorista', 'Nota de cambio', 'cuadrado', 'regalos a amistades', 'M', 'cambio de polos ', 'luisa', '7264321', 'juan manuel', '23456789', 'avlos olivos', 'lima/comas/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(2, 'Mayorista', 'Nota de cambio', 'cuadrado', 'regalos a amistades', 'M', 'cambio de polos ', 'luisa', '7264321', 'juan manuel', '23456789', 'avlos olivos', 'lima/comas/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(3, 'Mayorista', 'Nota de cambio', 'cuadrado', 'cliente mayorista ', 'L-S', 'pedido a mayor de polos', 'luisa', '7264321', 'juan manuel', '23456789', 'avlos olivos', 'lima/comas/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(5, 'Minorista', 'Nota de cambio', '333', '12312', 'M-XL', '234', '234', '1345', '234', '243', 'trujillo', '1245', NULL, NULL, '22342', 'Particular', NULL),
(6, 'Minorista', 'Nota de evento', 'sin estampado', 'devolucion', 'S', 'polo manga larga', 'sofia', '4324532', 'seew', '1234234534', '2eqwrsafs', '4234234', NULL, NULL, '123242', 'Particular', NULL),
(7, 'Mayorista', 'Nota de cambio', 'cuadrado', 'regalos a amistades', 'S', 'cambio de talla', 'luisa', '7412589', 'maria fernanda', '987654321', 'av. arequipa', 'lima/miraflores/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(8, 'Minorista', 'Nota de evento', 'sin estampado', 'cumpleaños', 'L', 'polo estampado personalizado', 'sofia', '4589632', 'carlos ramirez', '956412378', 'jr. huancayo 234', 'lima/san juan de lurigancho', NULL, NULL, 'agencia', 'Particular', NULL),
(9, 'Mayorista', 'Nota de cambio', 'cuadrado', 'cliente mayorista', 'M-L', 'pedido a mayor de polos', 'luisa', '7852369', 'jose antonio perez', '998877665', 'av. brasil 456', 'lima/pueblo libre/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(10, 'Minorista', 'Nota de cambio', '12312', 'devolucion', 'XL', 'talla incorrecta', 'sofia', '6321478', 'ana lucia torres', '912345678', 'calle los pinos 78', 'trujillo/trujillo', NULL, NULL, 'agencia', 'Particular', NULL),
(11, 'Mayorista', 'Nota de evento', 'cuadrado', 'regalos a amistades', 'S-M', 'pedido especial navideño', 'luisa', '7458963', 'rosa maria gomez', '945612378', 'av. los olivos 123', 'lima/los olivos/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(12, 'Minorista', 'Nota de evento', 'sin estampado', 'promocion', 'M', 'polo basico sin diseño', 'sofia', '3216549', 'pedro luis castillo', '987412365', 'jr. amazonas 90', 'chiclayo/chiclayo', NULL, NULL, 'agencia', 'Particular', NULL),
(13, 'Mayorista', 'Nota de cambio', 'cuadrado', 'regalos a amistades', 'S', 'cambio de talla', 'luisa', '7412589', 'maria fernanda', '987654321', 'av. arequipa', 'lima/miraflores/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(14, 'Minorista', 'Nota de evento', 'sin estampado', 'cumpleaños', 'L', 'polo estampado personalizado', 'sofia', '4589632', 'carlos ramirez', '956412378', 'jr. huancayo 234', 'lima/san juan de lurigancho', NULL, NULL, 'agencia', 'Particular', NULL),
(15, 'Mayorista', 'Nota de cambio', 'cuadrado', 'cliente mayorista', 'M-L', 'pedido a mayor de polos', 'luisa', '7852369', 'jose antonio perez', '998877665', 'av. brasil 456', 'lima/pueblo libre/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(16, 'Minorista', 'Nota de cambio', '12312', 'devolucion', 'XL', 'talla incorrecta', 'sofia', '6321478', 'ana lucia torres', '912345678', 'calle los pinos 78', 'trujillo/trujillo', NULL, NULL, 'agencia', 'Particular', NULL),
(17, 'Mayorista', 'Nota de evento', 'cuadrado', 'regalos a amistades', 'S-M', 'pedido especial navideño', 'luisa', '7458963', 'rosa maria gomez', '945612378', 'av. los olivos 123', 'lima/los olivos/lima', NULL, NULL, 'delivery', 'Particular', NULL),
(18, 'Minorista', 'Nota de evento', 'sin estampado', 'promocion', 'M', 'polo basico sin diseño', 'sofia', '3216549', 'pedro luis castillo', '987412365', 'jr. amazonas 90', 'chiclayo/chiclayo', NULL, NULL, 'agencia', 'Particular', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrarclientes`
--

CREATE TABLE `registrarclientes` (
  `id_registro` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `usuario_registro` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `direccion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `registrarclientes`
--

INSERT INTO `registrarclientes` (`id_registro`, `nombre`, `correo`, `telefono`, `usuario_registro`, `fecha_registro`, `direccion`) VALUES
(9, 'alonzo', 'pepito@yonigmial.com', '23456789', 'josue', '2025-05-30 13:24:44', 'los olivos '),
(10, 'alonzo', 'pepito@yonigmial.com', '23456789', 'josue', '2025-05-30 13:24:49', 'los olivos '),
(11, 'Pepito', 'pepito@yonigmial.com', '23456789', 'josue', '2025-05-30 13:26:08', 'su casa '),
(12, 'jordan', 'jordandel@gmail.com', '23456789', 'manuel', '2025-05-30 13:29:37', 'el cerro'),
(13, 'Pepito', 'pepito@yonigmial.com', '23456789', 'kiki butouski', '2025-05-30 13:34:41', 'su casa ps'),
(14, 'JOSUE ', 'jordandel@gmail.com', '23456789', 'manuel', '2025-05-31 19:35:18', 'agua'),
(15, 'alonzo', 'jordandel@gmail.com', '23456789', 'kiki butouski', '2025-05-31 19:38:23', 'ddd'),
(16, 'alonzo', 'jordandel@gmail.com', '23456789', 'kiki butouski', '2025-05-31 19:38:31', 'ddd'),
(17, 'juan', 'juabnalberto@gmail.com', '23456789', 'manuel', '2025-05-31 19:39:36', 'comas'),
(18, '', '', '', '', '2025-06-02 13:37:39', ''),
(19, 'franli', 'pepito@yonigmial.com', '23456789', 'manuel', '2025-06-02 14:50:10', 'eldenring'),
(20, 'JOSUE ', 'pepito@yonigmial.com', '23456789', 'goku', '2025-06-02 14:56:52', 'casa'),
(21, 'JOSUE ', 'josue@yonigmial.com', '23456789', 'goku', '2025-06-02 14:58:37', 'hogar'),
(22, 'franli', 'frangood@gmail.com', '23456789', 'kiki butouski', '2025-06-03 04:55:19', 'sucasa'),
(23, 'jesus', 'jesus@gmai.com', '23456789', 'josue ', '2025-06-03 23:54:23', 'los olivos ucv '),
(24, 'ernesto ', 'werwerer@gmail.com', 'rewrwerwerwe', 'josue', '2025-11-11 02:46:29', 'werwer'),
(25, 'ernesto ', 'werwerer@gmail.com', '243', 'josue', '2025-11-23 23:19:41', 'madrid'),
(26, 'ernesto ', 'werwerer@gmail.com', '243', 'josue', '2025-11-23 23:19:51', 'madrid'),
(27, 'ernesto ', 'werwerer@gmail.com', '243', 'josue', '2025-11-23 23:23:00', 'a'),
(28, 'ernesto ', 'werwerer@gmail.com', 'rewrwerwerwe', 'josue', '2025-11-23 23:24:18', 'o'),
(29, 'ernesto ', 'werwerer@gmail.com', '243', 'josue', '2025-11-23 23:59:18', '');

--
-- Disparadores `registrarclientes`
--
DELIMITER $$
CREATE TRIGGER `after_insert_registrar` AFTER INSERT ON `registrarclientes` FOR EACH ROW BEGIN
    INSERT INTO ListaClientes (nombre, correo, telefono)
    VALUES (NEW.nombre, NEW.correo, NEW.telefono);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_registrarclientes` AFTER INSERT ON `registrarclientes` FOR EACH ROW BEGIN
  INSERT INTO ListaClientes (nombre, correo, telefono, direccion, fecha_registro)
  VALUES (NEW.nombre, NEW.correo, NEW.telefono, NEW.direccion, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_telas`
--

CREATE TABLE `registros_telas` (
  `id` int NOT NULL,
  `rollo` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `talla` varchar(20) DEFAULT NULL,
  `cantidad_tallas` int DEFAULT NULL,
  `peso` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `registros_telas`
--

INSERT INTO `registros_telas` (`id`, `rollo`, `color`, `talla`, `cantidad_tallas`, `peso`) VALUES
(11, 'Tela Tull Licrado', 'negro', 'S', 22, 222),
(12, 'Tela Suplex', 'negro', 'XL', 34, 50),
(13, 'Tela Piel de Durazno', 'blanco', 'M', 18, 195),
(14, 'Yersey', 'negro', 'L', 25, 240),
(15, 'Malla Licrada', 'azul', 'S', 15, 130),
(16, 'Fresh Terri', 'rojo', 'XL', 20, 210),
(17, 'Franela', 'gris', 'M', 30, 260),
(18, 'Tela Suplex', 'negro', 'S', 22, 180),
(19, 'Tela Tull Licrado', 'blanco', 'XL', 12, 150),
(20, 'Tela Piel de Durazno', 'negro', 'L', 28, 230),
(21, 'Yersey', 'azul', 'M', 20, 200),
(22, 'Malla Licrada', 'negro', 'XL', 16, 170),
(23, 'Fresh Terri', 'blanco', 'S', 24, 190),
(24, 'Franela', 'rojo', 'L', 19, 220),
(25, 'Tela Suplex', 'gris', 'M', 26, 205),
(26, 'Tela Tull Licrado', 'negro', 'S', 21, 175),
(27, 'Tela Piel de Durazno', 'azul', 'XL', 14, 160);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_insumos`
--

CREATE TABLE `registro_insumos` (
  `id` int NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tinta_color` varchar(100) NOT NULL,
  `litros_usados` int NOT NULL,
  `papel_subliminado` int NOT NULL,
  `medidas_usadas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `registro_insumos`
--

INSERT INTO `registro_insumos` (`id`, `fecha_registro`, `tinta_color`, `litros_usados`, `papel_subliminado`, `medidas_usadas`) VALUES
(1, '2025-11-15 19:34:55', '12', 22, 32, 1231),
(2, '2025-11-16 14:12:30', '8', 15, 20, 850),
(3, '2025-11-16 19:45:10', '15', 28, 40, 1520),
(4, '2025-11-17 13:30:00', '10', 18, 25, 990),
(5, '2025-11-17 21:20:45', '20', 35, 50, 1800),
(6, '2025-11-18 15:05:22', '6', 10, 15, 600),
(7, '2025-11-18 18:40:00', '12', 22, 30, 1210),
(8, '2025-11-19 14:55:18', '18', 30, 42, 1650),
(9, '2025-11-19 20:10:33', '9', 16, 22, 870),
(10, '2025-11-20 13:20:00', '14', 25, 35, 1340),
(11, '2025-11-20 22:00:12', '22', 38, 55, 1920),
(12, '2025-11-21 14:30:45', '7', 12, 18, 700),
(13, '2025-11-21 19:15:00', '16', 27, 38, 1450),
(14, '2025-11-22 15:45:20', '11', 19, 26, 1020),
(15, '2025-11-22 21:30:00', '19', 32, 46, 1700),
(16, '2025-11-23 14:00:15', '13', 24, 33, 1280);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `descripcion` text,
  `producto` varchar(200) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `nombre`, `correo`, `telefono`, `direccion`, `descripcion`, `producto`, `usuario`, `fecha`) VALUES
(1, 'ernesto ', 'melany@grupo.com', '243', 'av abancai', 'cambio de talla ', 'polo deportivo ', 'josue', '2025-11-24 00:10:53'),
(2, 'maria', 'mariar@gmail.com', '987654321', 'av. arequipa', 'talla incorrecta', 'polo manga larga', 'josue', '2025-11-24 14:15:20'),
(3, 'carlos', 'carlosl@gmail.com', '956412378', 'jr. huancayo 234', 'defecto en costura', 'polo cuello redondo', 'sofia', '2025-11-24 16:30:45'),
(4, 'ana', 'anatorres@gmail.com', '912345678', 'calle los pinos 78', 'cambio de color', 'polo deportivo', 'josue', '2025-11-25 13:20:10'),
(5, 'jose', 'joseperez@gmail.com', '998877665', 'av. brasil 456', 'pedido incompleto', 'buzo', 'luisa', '2025-11-25 19:05:33'),
(6, 'rosa', 'rosagomez@gmail.com', '945612378', 'av. los olivos 123', 'demora en entrega', 'polo estampado', 'sofia', '2025-11-26 14:45:00'),
(7, 'pedro', 'pedrocastillo@gmail.com', '987412365', 'jr. amazonas 90', 'cambio de talla', 'polo cuello v', 'josue', '2025-11-26 18:10:22'),
(8, 'lucia', 'luciamendoza@gmail.com', '923456781', 'av. universitaria 55', 'producto dañado', 'chompa', 'luisa', '2025-11-27 15:30:15'),
(9, 'jorge', 'jorgevalle@gmail.com', '934567891', 'jr. cusco 300', 'consulta de pedido', 'polo deportivo', 'sofia', '2025-11-27 21:50:40'),
(10, 'patricia', 'patriciasoto@gmail.com', '945678912', 'av. tacna 200', 'cambio de diseño', 'polo estampado', 'josue', '2025-11-28 13:25:05'),
(11, 'miguel', 'miguelrios@gmail.com', '956789123', 'jr. junin 150', 'reclamo por talla', 'buzo', 'luisa', '2025-11-28 17:40:30'),
(12, 'daniela', 'danielaflores@gmail.com', '967891234', 'av. colonial 400', 'pedido a mayor', 'polo cuello redondo', 'sofia', '2025-11-28 20:55:18'),
(13, 'ernesto', 'melany@grupo.com', '243', 'av abancai', 'cambio de talla', 'polo deportivo', 'josue', '2025-11-29 14:10:50'),
(14, 'sofia', 'sofiaruiz@gmail.com', '978912345', 'jr. moquegua 60', 'consulta de producto', 'polo manga larga', 'luisa', '2025-11-29 18:35:25'),
(15, 'diego', 'diegomorales@gmail.com', '989123456', 'av. piura 500', 'cambio de color', 'chompa', 'josue', '2025-11-29 22:20:00'),
(16, 'valeria', 'valeriacruz@gmail.com', '990123456', 'jr. lima 25', 'reclamo de entrega', 'polo estampado', 'sofia', '2025-11-30 14:00:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_insumos`
--

CREATE TABLE `reportes_insumos` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `producto` varchar(200) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reportes_insumos`
--

INSERT INTO `reportes_insumos` (`id`, `nombre`, `correo`, `descripcion`, `producto`, `fecha`) VALUES
(3, 'melany', 'melany@correo.com', 'falta de telas y estampado ', '15 unidades', '2026-07-01 22:39:41'),
(4, 'josue', 'josue@correo.com', 'falta de hilo negro', '10 conos', '2026-07-02 13:15:20'),
(5, 'sofia', 'sofia@correo.com', 'stock bajo de botones', '200 unidades', '2026-07-02 15:30:45'),
(6, 'luisa', 'luisa@correo.com', 'falta de tela suplex', '30 metros', '2026-07-02 19:05:10'),
(7, 'ernesto', 'ernesto@correo.com', 'falta de etiquetas', '500 unidades', '2026-07-03 14:20:33'),
(8, 'melany', 'melany@correo.com', 'falta de cierres', '80 unidades', '2026-07-03 16:45:00'),
(9, 'josue', 'josue@correo.com', 'stock bajo de tinta negra', '5 litros', '2026-07-03 20:10:22'),
(10, 'sofia', 'sofia@correo.com', 'falta de papel sublimado', '25 unidades', '2026-07-04 13:35:15'),
(11, 'luisa', 'luisa@correo.com', 'falta de tela franela', '18 metros', '2026-07-04 17:50:40'),
(12, 'ernesto', 'ernesto@correo.com', 'falta de bolsas de empaque', '150 unidades', '2026-07-04 21:25:05'),
(13, 'melany', 'melany@correo.com', 'falta de hilo blanco', '12 conos', '2026-07-05 14:00:30'),
(14, 'josue', 'josue@correo.com', 'stock bajo de cinta métrica', '4 unidades', '2026-07-05 18:15:55'),
(15, 'sofia', 'sofia@correo.com', 'falta de tela yersey', '22 metros', '2026-07-05 22:40:12'),
(16, 'luisa', 'luisa@correo.com', 'falta de agujas de máquina', '30 unidades', '2026-07-06 13:20:38'),
(17, 'ernesto', 'ernesto@correo.com', 'falta de tela malla licrada', '15 metros', '2026-07-06 16:05:20'),
(18, 'melany', 'melany@correo.com', 'stock bajo de tinta color', '6 litros', '2026-07-06 19:30:45'),
(19, 'josue', 'josue@correo.com', 'falta de etiquetas de talla', '300 unidades', '2026-07-07 14:45:10'),
(20, 'sofia', 'sofia@correo.com', 'falta de tela piel de durazno', '20 metros', '2026-07-07 17:10:33'),
(21, 'luisa', 'luisa@correo.com', 'falta de estampado y telas', '10 unidades', '2026-07-07 21:35:00'),
(22, 'ernesto', 'ernesto@correo.com', 'falta de hilo de bordado', '8 conos', '2026-07-08 14:50:22'),
(23, 'melany', 'melany@correo.com', 'stock bajo de bolsas plásticas', '100 unidades', '2026-07-08 18:15:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_telas`
--

CREATE TABLE `reportes_telas` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `producto` varchar(200) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reportes_telas`
--

INSERT INTO `reportes_telas` (`id`, `nombre`, `correo`, `descripcion`, `producto`, `fecha`) VALUES
(1, 'josue', 'josue@correo.com', 'falta de tela suplex', '30 metros', '2026-07-01 13:15:20'),
(2, 'sofia', 'sofia@correo.com', 'stock bajo de tela franela', '18 metros', '2026-07-01 15:30:45'),
(3, 'luisa', 'luisa@correo.com', 'falta de tela yersey', '25 metros', '2026-07-02 14:20:10'),
(4, 'ernesto', 'ernesto@correo.com', 'falta de tela tull licrado', '15 metros', '2026-07-02 19:05:33'),
(5, 'melany', 'melany@correo.com', 'falta de tela piel de durazno', '20 metros', '2026-07-02 21:40:00'),
(6, 'josue', 'josue@correo.com', 'stock bajo de malla licrada', '12 metros', '2026-07-03 13:50:22'),
(7, 'sofia', 'sofia@correo.com', 'falta de fresh terri', '22 metros', '2026-07-03 16:15:45'),
(8, 'luisa', 'luisa@correo.com', 'falta de tela suplex color negro', '28 metros', '2026-07-03 20:30:10'),
(9, 'ernesto', 'ernesto@correo.com', 'falta de tela franela gris', '16 metros', '2026-07-04 14:05:33'),
(10, 'melany', 'melany@correo.com', 'stock bajo de tela yersey azul', '19 metros', '2026-07-04 18:20:15'),
(11, 'josue', 'josue@correo.com', 'falta de tela piel de durazno blanco', '24 metros', '2026-07-04 22:45:40'),
(12, 'sofia', 'sofia@correo.com', 'falta de malla licrada roja', '14 metros', '2026-07-05 13:10:05'),
(13, 'luisa', 'luisa@correo.com', 'stock bajo de fresh terri negro', '17 metros', '2026-07-05 17:35:30'),
(14, 'ernesto', 'ernesto@correo.com', 'falta de tela tull licrado blanco', '21 metros', '2026-07-05 21:50:55'),
(15, 'melany', 'melany@correo.com', 'falta de tela suplex gris', '26 metros', '2026-07-06 14:25:12'),
(16, 'josue', 'josue@correo.com', 'stock bajo de tela franela roja', '13 metros', '2026-07-06 18:40:38'),
(17, 'sofia', 'sofia@correo.com', 'falta de tela yersey negro', '20 metros', '2026-07-06 22:15:20'),
(18, 'luisa', 'luisa@correo.com', 'falta de malla licrada blanco', '18 metros', '2026-07-07 13:30:45'),
(19, 'ernesto', 'ernesto@correo.com', 'stock bajo de fresh terri azul', '23 metros', '2026-07-07 17:05:10'),
(20, 'melany', 'melany@correo.com', 'falta de tela piel de durazno rojo', '15 metros', '2026-07-07 21:20:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_accesorios`
--

CREATE TABLE `stock_accesorios` (
  `id` int NOT NULL,
  `accesorio` varchar(100) NOT NULL,
  `tallas` varchar(100) NOT NULL,
  `diseños` varchar(100) NOT NULL,
  `cantidad_tallas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `stock_accesorios`
--

INSERT INTO `stock_accesorios` (`id`, `accesorio`, `tallas`, `diseños`, `cantidad_tallas`) VALUES
(1, 'logos', 'M-XL', 'polos de compresion', 15),
(2, '22', 'M', '22', 22),
(3, 'cierres', 'M-L', 'chompas deportivas', 80),
(4, 'etiquetas', 'S-XL', 'polos estampados', 500),
(5, 'cintas', 'M', 'buzos', 60),
(6, 'hebillas', 'L-XL', 'chompas', 45),
(7, 'logos', 'S-M', 'polos deportivos', 120),
(8, 'parches', 'M-L', 'chaquetas', 35),
(9, 'cordones', 'S-XL', 'buzos deportivos', 90),
(10, 'broches', 'M', 'polos cuello v', 70),
(11, 'tachas', 'L', 'chompas casuales', 40),
(12, 'apliques', 'S-M', 'polos personalizados', 55),
(13, 'reflectivos', 'M-XL', 'buzos deportivos', 25),
(14, 'zippers', 'S-L', 'chaquetas deportivas', 65),
(15, 'strass', 'M', 'polos de fiesta', 30),
(16, 'velcro', 'L-XL', 'buzos escolares', 50),
(17, 'cierres', 'L', 'chompa deportiva', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contraseña` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rol` enum('admin','almacen','taller','ventas') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña`, `rol`) VALUES
(7, 'josue', 'josue@grupo7.com', '123456', 'admin'),
(8, 'valentino', 'JS2@grupo.com', '123456', 'almacen'),
(9, 'josue', 'JS3@grupo.com', '123456', 'taller'),
(10, 'gabriel', 'JS4@grupo.com', '123456', 'ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `producto` varchar(200) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `nombre`, `correo`, `telefono`, `direccion`, `producto`, `usuario`, `fecha`) VALUES
(1, 'maria', 'mariar@gmail.com', '987654321', 'av. arequipa', 'polo deportivo', 'josue', '2026-07-01 14:15:20'),
(2, 'carlos', 'carlosl@gmail.com', '956412378', 'jr. huancayo 234', 'polo cuello redondo', 'sofia', '2026-07-01 16:30:45'),
(3, 'ana', 'anatorres@gmail.com', '912345678', 'calle los pinos 78', 'buzo', 'luisa', '2026-07-01 19:20:10'),
(4, 'jose', 'joseperez@gmail.com', '998877665', 'av. brasil 456', 'polo manga larga', 'josue', '2026-07-02 13:45:33'),
(5, 'rosa', 'rosagomez@gmail.com', '945612378', 'av. los olivos 123', 'chompa', 'sofia', '2026-07-02 15:05:00'),
(6, 'pedro', 'pedrocastillo@gmail.com', '987412365', 'jr. amazonas 90', 'polo cuello v', 'luisa', '2026-07-02 18:40:22'),
(7, 'lucia', 'luciamendoza@gmail.com', '923456781', 'av. universitaria 55', 'polo estampado', 'josue', '2026-07-03 14:10:15'),
(8, 'jorge', 'jorgevalle@gmail.com', '934567891', 'jr. cusco 300', 'chaqueta deportiva', 'sofia', '2026-07-03 17:35:40'),
(9, 'patricia', 'patriciasoto@gmail.com', '945678912', 'av. tacna 200', 'polo deportivo', 'luisa', '2026-07-03 21:50:05'),
(10, 'miguel', 'miguelrios@gmail.com', '956789123', 'jr. junin 150', 'buzo escolar', 'josue', '2026-07-04 13:25:30'),
(11, 'daniela', 'danielaflores@gmail.com', '967891234', 'av. colonial 400', 'polo cuello redondo', 'sofia', '2026-07-04 16:40:55'),
(12, 'ernesto', 'melany@grupo.com', '243', 'av abancai', 'polo deportivo', 'josue', '2026-07-04 20:15:12'),
(13, 'sofia', 'sofiaruiz@gmail.com', '978912345', 'jr. moquegua 60', 'polo manga larga', 'luisa', '2026-07-05 14:30:38'),
(14, 'diego', 'diegomorales@gmail.com', '989123456', 'av. piura 500', 'chompa', 'josue', '2026-07-05 18:05:20'),
(15, 'valeria', 'valeriacruz@gmail.com', '990123456', 'jr. lima 25', 'polo estampado', 'sofia', '2026-07-05 22:20:45');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `listaclientes`
--
ALTER TABLE `listaclientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`idNota`);

--
-- Indices de la tabla `registrarclientes`
--
ALTER TABLE `registrarclientes`
  ADD PRIMARY KEY (`id_registro`);

--
-- Indices de la tabla `registros_telas`
--
ALTER TABLE `registros_telas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_insumos`
--
ALTER TABLE `registro_insumos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes_insumos`
--
ALTER TABLE `reportes_insumos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes_telas`
--
ALTER TABLE `reportes_telas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock_accesorios`
--
ALTER TABLE `stock_accesorios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `listaclientes`
--
ALTER TABLE `listaclientes`
  MODIFY `id_cliente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `idNota` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `registrarclientes`
--
ALTER TABLE `registrarclientes`
  MODIFY `id_registro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `registros_telas`
--
ALTER TABLE `registros_telas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `registro_insumos`
--
ALTER TABLE `registro_insumos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `reportes_insumos`
--
ALTER TABLE `reportes_insumos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `reportes_telas`
--
ALTER TABLE `reportes_telas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `stock_accesorios`
--
ALTER TABLE `stock_accesorios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
