-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-10-2024 a las 08:33:10
-- Versión del servidor: 10.11.9-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u345853623_jaiteva_final`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Menu`
--

CREATE TABLE `Menu` (
  `idmenu` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `condicion_alimenticia` varchar(50) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Menu`
--

INSERT INTO `Menu` (`idmenu`, `nombre`, `descripcion`, `precio`, `condicion_alimenticia`, `imagen_url`) VALUES
(5, 'Pizza sin gluten', 'Pizza apta para celíacos', 1200.00, 'celiaco', 'https://www.soyceliaconoextraterrestre.com/wp-content/uploads/2020/06/pizza-sin-tacc-para-celiacos-1024x683.jpg'),
(6, 'Pasta vegana', 'Pasta sin ingredientes de origen animal', 900.00, 'vegano', 'https://mariaalcazar.com/wp-content/uploads/2015/11/Espaguetis-con-tomates-cherry-y-espinacas-1-e1680041352167-1280x720.jpg.avif'),
(7, 'Ensalada vegetariana', 'Ensalada con vegetales frescos', 800.00, 'vegetariano', 'https://www.dietfarma.com/wp-content/uploads/2016/02/ensalada_vegetales-queso-69f.jpg'),
(8, 'Empanadas sin gluten', 'Empanadas aptas para celíacos', 1000.00, 'celiaco', 'https://ilsole.com.ar/wp-content/uploads/2016/07/empanadas-de-carne-sin-tacc-sin-gluten-il-sole-1600x1060-1.jpg'),
(9, 'Hamburguesa vegana', 'Hamburguesa de vegetales', 1100.00, 'vegano', 'https://proexpansion.com/uploads/article/image/2966/larger_shutterstock_133977308.jpg'),
(10, 'Tarta vegetariana', 'Tarta de espinaca y queso', 950.00, 'vegetariano', 'https://www.lasaltena.com.ar/wp-content/uploads/2020/03/Tarta-de-espinaca_banner-400x196.png'),
(11, 'Sándwich sin gluten', 'Sándwich sin TACC', 850.00, 'celiaco', 'https://ceroglut.com.ar/wp-content/uploads/2021/09/SANDWICH-J-Y-Q-DESNUDO-04-scaled.jpg'),
(12, 'Wrap vegano', 'Wrap de vegetales y hummus', 780.00, 'vegano', 'https://cdn.nutritionstudies.org/wp-content/uploads/2015/09/salad-wraps-with-beans-and-greens-1024x683.jpg'),
(13, 'Omelette vegetariano', 'Omelette con queso y verduras', 920.00, 'vegetariano', 'https://cordobanutricion.com/wp-content/uploads/2016/03/omelette-de-verduras.png'),
(14, 'Baguette sin gluten', 'Baguette sin gluten', 700.00, 'celiaco', 'https://www.elpanderebe.com/wp-content/uploads/2019/05/20190515_182420-1-e1558391466675-768x1024.jpg'),
(15, 'Ensalada de quinoa', 'Ensalada vegana con quinoa', 890.00, 'vegano', 'https://recetasveganas.net/wp-content/uploads/2020/03/ensalada-quinoa-espinaca-rucula-salsa-mango-recetas-vegetarianas1.jpg'),
(16, 'Pizza vegetariana', 'Pizza con vegetales', 1050.00, 'vegetariano', 'https://www.lasaltena.com.ar/wp-content/uploads/2020/03/Pizza-de-vegetales_banner-400x196.png'),
(17, 'Milanesa Completa', 'Milanesa de carne con papas fritas', 900.00, 'sin condicion alimenticia', 'https://www.essen.com.ar/contenido/objetos/1/Milanesa-con-papas-fritas.jpg'),
(18, 'Spaghetti al Pesto', 'Spaghetti con salsa de pesto casero', 700.00, 'sin condicion alimenticia', 'https://www.lavanguardia.com/files/image_948_465/uploads/2020/05/29/5ed11fb61d750.jpeg'),
(19, 'Ensalada Mixta', 'Ensalada fresca con vegetales variados.', 150.00, 'sin condicion alimenticia', 'https://content-cocina.lecturas.com/medio/2023/08/04/ensalada-de-hortalizas-con-vinagreta-de-remolacha_c8ef31ea_1200x1200.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pedido`
--

CREATE TABLE `Pedido` (
  `id_pedido` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `ticket` varchar(20) NOT NULL,
  `opcion_pedido` varchar(50) DEFAULT NULL,
  `estado` enum('pendiente','confirmado','rechazado','cocinando','entregado','cancelado','completado','en reserva') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Pedido`
--

INSERT INTO `Pedido` (`id_pedido`, `fecha_pedido`, `total`, `idusuario`, `metodo_pago`, `ticket`, `opcion_pedido`, `estado`) VALUES
(204, '2024-10-31 05:02:42', 842.00, 8, 'Efectivo', '97804A9863DC3445A41B', 'reservar', 'rechazado'),
(205, '2024-10-31 05:06:08', 149.00, 8, 'Efectivo', '9F829F5DF6F532C64F0B', 'reservar', 'entregado'),
(206, '2024-10-31 05:09:31', 2673.00, 5, 'Mercado Pago', '6C78858A5A99D42B83D3', 'comer_acá', 'rechazado'),
(207, '2024-10-31 05:09:48', 693.00, 5, 'Efectivo', '3D5BA5632477167F36D7', 'comer_acá', 'rechazado'),
(208, '2024-10-31 05:10:28', 773.00, 5, 'Efectivo', '76C147CDE7EF87E419CD', 'comer_acá', 'rechazado'),
(209, '2024-10-31 05:11:18', 13910.00, 5, 'Mercado Pago', '2907ED17490640E797B9', 'comer_acá', 'rechazado'),
(210, '2024-10-31 05:11:51', 17820.00, 5, 'Efectivo', '7D34A110A75E080C8269', 'comer_acá', 'cancelado'),
(211, '2024-10-31 05:12:27', 4455.00, 5, 'Mercado Pago', 'C5026EA8D0A338872FBC', 'comer_acá', 'cancelado'),
(212, '2024-10-31 05:12:55', 891.00, 5, 'Efectivo', '9125905E67DA1DB9C86C', 'comer_acá', 'rechazado'),
(213, '2024-10-31 05:14:06', 1089.00, 5, 'Efectivo', 'B6ADD31E220292AEFB60', 'comer_acá', 'rechazado'),
(214, '2024-10-31 05:15:40', 891.00, 5, 'Efectivo', 'AAC611F0158564298977', 'comer_acá', 'rechazado'),
(215, '2024-10-31 05:17:08', 700.00, 5, 'Efectivo', 'B826E0182E65BC38A3BD', 'comer_acá', 'rechazado'),
(216, '2024-10-31 05:17:50', 1250.00, 8, 'Efectivo', '450EDC50961FA89E73DA', 'comer_acá', 'entregado'),
(217, '2024-10-31 05:18:29', 1100.00, 8, 'Efectivo', '542E73DF97E0C53570A2', 'comer_acá', 'entregado'),
(218, '2024-10-31 05:20:21', 876.00, 8, 'Efectivo', '92AA4B720DAAD5AACCDB', 'comer_acá', 'entregado'),
(219, '2024-10-31 05:20:36', 1776.00, 8, 'Mercado Pago', 'C772FC87352B26980BD6', 'comer_acá', 'entregado'),
(220, '2024-10-31 05:20:47', 876.00, 8, 'Mercado Pago', 'A16790191B4AABADB831', 'para_llevar', 'entregado'),
(221, '2024-10-31 05:20:57', 876.00, 8, 'Efectivo', 'D7F1351DFDAF4372C19C', 'para_llevar', 'entregado'),
(222, '2024-10-31 05:21:28', 876.00, 8, 'Mercado Pago', '7F0726867B6D0C280DA6', 'reservar', 'entregado'),
(223, '2024-10-31 05:21:35', 876.00, 8, 'Efectivo', '04BB20AD3BFD78470324', 'reservar', 'entregado'),
(224, '2024-10-31 05:21:48', 900.00, 5, 'Efectivo', '44ECE4F40947D4DE3F97', 'comer_acá', 'entregado'),
(225, '2024-10-31 05:22:27', 900.00, 5, 'Mercado Pago', '4F9CA585A2ED7170BA4A', 'comer_acá', 'rechazado'),
(226, '2024-10-31 05:22:41', 900.00, 5, 'Efectivo', 'D48DD7554F5FDB79BB5D', 'comer_acá', 'rechazado'),
(227, '2024-10-31 05:22:50', 900.00, 5, 'Efectivo', '20CD375EDE2D329D5A31', 'para_llevar', 'entregado'),
(228, '2024-10-31 05:23:20', 150.00, 5, 'Mercado Pago', '85CD32A7A47564ECCF05', 'para_llevar', 'entregado'),
(229, '2024-10-31 05:24:20', 900.00, 9, 'Efectivo', '79DFFCEA812B49957CF5', 'comer_acá', 'entregado'),
(230, '2024-10-31 05:25:21', 646.00, 9, 'Mercado Pago', 'CF10DDBA1D30EB2AF339', 'comer_acá', 'rechazado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Reserva`
--

CREATE TABLE `Reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_reserva` time NOT NULL,
  `num_personas` int(11) NOT NULL CHECK (`num_personas` <= 15)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Reserva`
--

INSERT INTO `Reserva` (`id_reserva`, `id_pedido`, `fecha_reserva`, `hora_reserva`, `num_personas`) VALUES
(78, 204, '2024-10-31', '05:04:00', 8),
(79, 205, '2024-10-31', '05:07:00', 5),
(80, 222, '2024-10-31', '05:23:00', 3),
(81, 223, '2024-10-31', '05:23:00', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `nombrerol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `nombrerol`) VALUES
(1, 'Admin'),
(2, 'Usuario'),
(3, 'invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `idrol` int(11) DEFAULT NULL,
  `puntos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `contraseña`, `correo`, `idrol`, `puntos`) VALUES
(2, 'bautioperador', '$2y$10$YC.OA2vgtBCClnNXsZAduuREyWoRToc7M8cKrHK1zBpB1wotv8UTC', 'bautioperador@gmail.com', 1, 0),
(3, 'bauti', '$2y$10$6jtsBcyEbhAhtctKI4Mg8uM4UuHzNWxUJ/jEGzkWAX1VtIWOip.P2', 'bauti@gmail.com', 2, 0),
(5, 'cliente', 'contraseña_cliente', 'cliente@ejemplo.com', 3, 0),
(6, 'usuario', '$2y$10$OTyvGlYnChq0309WQYVPH.TD/oTmDCOQfi1yEZKBtW4Zz5e263T3S', 'probando@yahoo.net', 2, 0),
(7, 'Fernando David', '$2y$10$W2Lu2nNRZ9tcClrU1chKJ.J//GLtqdosyJ/goCoiGDLjlfbmMksfS', 'delossantosfernandodavid@gmail.com', 2, 0),
(8, 'prueba3', '$2y$10$ZcqX9GrZtIqvlZfIHJUsbOoYsbLJ3oV86EtgHqhzJtQx0KNmn1YjG', 'probando@llano.com', 2, 24),
(9, '123', '$2y$10$eqRu0KFicLMhNapXeqQvOut3R.xvZvspsUVjDVkUxSC.kVBEi7qEG', '123@123.123', 2, 54);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Menu`
--
ALTER TABLE `Menu`
  ADD PRIMARY KEY (`idmenu`);

--
-- Indices de la tabla `Pedido`
--
ALTER TABLE `Pedido`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `Reserva`
--
ALTER TABLE `Reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `idrol` (`idrol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Menu`
--
ALTER TABLE `Menu`
  MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `Pedido`
--
ALTER TABLE `Pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT de la tabla `Reserva`
--
ALTER TABLE `Reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Reserva`
--
ALTER TABLE `Reserva`
  ADD CONSTRAINT `Reserva_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `Pedido` (`id_pedido`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
