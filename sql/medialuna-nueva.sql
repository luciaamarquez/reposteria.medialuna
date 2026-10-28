-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2026 a las 18:41:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `medialuna`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Tortas / Tartas'),
(2, 'Budines'),
(3, 'Cookies'),
(4, 'Donuts'),
(5, 'Muffins');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `id_pedido`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 1, 11, 3, 8000.00),
(2, 1, 9, 1, 28000.00),
(3, 2, 31, 1, 2800.00),
(4, 2, 30, 1, 2500.00),
(5, 2, 29, 1, 2500.00),
(6, 3, 31, 1, 2800.00),
(7, 3, 30, 3, 2500.00),
(8, 3, 29, 1, 2500.00),
(9, 4, 31, 1, 2800.00),
(10, 4, 30, 3, 2500.00),
(11, 4, 29, 1, 2500.00),
(12, 5, 30, 3, 2500.00),
(13, 5, 29, 1, 2500.00),
(14, 5, 38, 2, 4000.00),
(15, 5, 36, 1, 4000.00),
(16, 6, 30, 3, 2500.00),
(17, 6, 29, 1, 2500.00),
(18, 6, 38, 2, 4000.00),
(19, 6, 36, 1, 4000.00),
(20, 6, 67, 1, 9000.00),
(21, 6, 66, 2, 18000.00),
(22, 6, 65, 2, 8000.00),
(23, 7, 30, 3, 2500.00),
(24, 7, 67, 1, 9000.00),
(25, 7, 66, 2, 18000.00),
(26, 7, 65, 2, 8000.00),
(27, 8, 30, 3, 2500.00),
(28, 8, 67, 1, 9000.00),
(29, 8, 66, 2, 18000.00),
(30, 8, 65, 2, 8000.00),
(31, 9, 30, 3, 2500.00),
(32, 9, 67, 1, 9000.00),
(33, 9, 66, 2, 18000.00),
(34, 9, 65, 2, 8000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `fecha`, `total`, `estado`) VALUES
(1, 8, '2026-10-17 02:03:23', 52500.00, 'pagado'),
(2, 8, '2026-10-21 23:28:57', 8300.00, 'pendiente_pago'),
(3, 8, '2026-10-21 23:30:38', 13300.00, 'pendiente_pago'),
(4, 8, '2026-10-21 23:31:19', 13300.00, 'pendiente_pago'),
(5, 8, '2026-10-22 02:40:23', 22500.00, 'pendiente_pago'),
(6, 8, '2026-10-23 02:08:31', 83500.00, 'pendiente_pago'),
(7, 8, '2026-10-23 02:14:07', 69000.00, 'pendiente_pago'),
(8, 8, '2026-10-23 02:21:11', 69000.00, 'pendiente_pago'),
(9, 8, '2026-10-23 09:00:28', 69000.00, 'pendiente_pago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `id_categoria`, `stock`) VALUES
(16, 'Dona Pistacho Lima', 'Cubierta con un glaseado verde de pistacho y trocitos crocantes que aportan un toque especial.', 2500.00, '0b9b92ff2f2469f62df01343a23cfa84.jpg', 4, 10),
(21, 'Dona Cookies & Cream', 'Dona de chocolate cubierta con migas de galletitas y líneas de chocolate para una combinación bien intensa.', 2500.00, '5f418723b60fe79a85a387e722abd367.jpg', 4, 10),
(22, 'Dona Frutos Rosados', 'Glaseado rosado, un toque de cobertura blanca y pequeñas migas crocantes.', 2300.00, 'ece334d5d626e1e52c0d16b06084e4ee.jpg', 4, 10),
(23, 'Dona Pistacho & Chocolate', 'Bañada en chocolate y decorada con crema de pistacho y trocitos crocantes que combinan sabores intensos.', 2800.00, 'd0b0319adb51e328f9361bb496259dc8.jpg', 4, 8),
(24, 'Dona Frutos del Bosque', 'Cobertura violeta y delicados hilos de glaseado claro, de aspecto suave y sabor frutal.', 2500.00, 'e459a2d24da585bc395bb137225d9546.jpg', 4, 8),
(25, 'Dona Pink Chocolate', 'Glaseado rosa y líneas de chocolate oscuro que crean una combinación dulce y tentadora.', 2300.00, '8aaf1983c65378dd2b84e86847fb5255.jpg', 4, 10),
(26, 'Dona Corazón', 'Glaseado rojo brillante y pequeños corazones decorativos, perfecta para regalar o compartir.', 2500.00, 'e2ca0d5725a349066e305d25eff53576.jpg', 4, 8),
(27, 'Dona Tentación', 'Cubierta de chocolate, decorada con un delicado baño rosa y una crema suave en el centro.', 2500.00, '6e768dd480f54202c9ff1e43e9fa6c9a.jpg', 4, 8),
(28, 'Dona Perlas de Chocolate', 'Cobertura de chocolate con leche y pequeñas perlas blancas.', 2300.00, '323cc0172d168697404aabe6b9e20e42.jpg', 4, 10),
(29, 'Dona Red Velvet', 'Glaseado rojo intenso y brillante.', 2500.00, 'ddd488078963c4d24a0135741da86105.jpg', 4, 8),
(30, 'Dona Chocolate Blanco', 'Cobertura blanca, trocitos de chocolate y líneas de chocolate oscuro para combinar dulzura y cacao.', 2500.00, '7fe756985dd28c8b785a64ecb26e24d0.jpg', 4, 10),
(31, 'Dona Triple Chocolate', 'Dona con una generosa cobertura de chocolate, hilos de chocolate blanco y lluvia de chips para un sabor intenso.', 2800.00, 'c032db895cd67eb9c105b957975422d7.jpg', 4, 8),
(32, 'Budín de chocolate con frutillas', 'Budín de chocolate acompañado de una suave crema, frutillas frescas y un toque de pistacho.', 4500.00, '0b51993fc238d6b99bfd84817a3bc29c.jpg', 2, 8),
(33, 'Budín marmolado con chocolate', 'La combinación perfecta de vainilla y chocolate en un budín esponjoso, terminado con hilos de chocolate por encima.', 3500.00, 'd8695cb4835fa6515d61cf7e9e975566.jpg', 2, 10),
(34, 'Budín de limón glaseado', 'Un budín fresco y esponjoso con sabor a limón, cubierto con un glaseado dulce y decorado con rodajas de limón.', 3500.00, 'b23d7d922462ef6055c7db93c2c11ebd.jpg', 2, 10),
(35, 'Budín de limón y arándanos', 'Budín húmedo de limón con arándanos, cubierto con un glaseado suave y decorado con frutos frescos.', 4000.00, '21ab47f38d368b8e5ac4e08b2a44dcc6.jpg', 2, 8),
(36, 'Budín de frutos rojos', 'Budín esponjoso con frutos rojos, cubierto con un delicado glaseado que combina dulzura y un toque frutal.', 4000.00, '1d6e078ce7acd74b541e46ee23bed800.jpg', 2, 8),
(37, 'Budín de zanahoria con nueces', 'Budín de zanahoria suave y húmedo, acompañado de una cobertura cremosa y nueces que aportan un toque crocante.', 4000.00, 'a44429c7327f1018a82236cdcc66740a.jpg', 2, 8),
(38, 'Budín doble chocolate', 'Budín húmedo de chocolate con chips, pensado para los amantes del chocolate en cada bocado.', 4000.00, 'ff150777435c1b782d8ee2296c173f36.jpg', 2, 10),
(39, 'Budín de zanahoria glaseado', 'Una preparación tierna de zanahoria con una cobertura cremosa y trocitos de nuez para completar su sabor casero.', 4000.00, '7bdce9c938c432b4c8fba1f5cb83cad8.jpg', 2, 8),
(40, 'Cookie Cadbury', 'Galleta de chocolate con un centro cremoso de frutilla, decorada con hilos de chocolate y una pieza de chocolate Cadbury.', 2800.00, '1c9b90833475017212a5e8e5e9e0af9b.jpg', 3, 8),
(41, 'Cookie Dos Corazones', 'Galleta con chips de chocolate, cubierta con hilos de chocolate y decorada con dos corazones.', 2500.00, 'f474172a61ad0c7d62a1dc2a46f11c82.jpg', 3, 10),
(42, 'Cookie Rocher', 'Galleta de chocolate con una generosa cobertura cremosa y bombones rellenos de avellana.', 2800.00, 'e4e653f0750364af04d60810fd9166e3.jpg', 3, 8),
(43, 'Cookie Bon o Bon', 'Galleta con chips de chocolate, cubierta con una suave crema sabor chocolate y decorada con salsa y bon o bon.', 2800.00, '05d7289863ae44765b08c1f3ab50c927.jpg', 3, 8),
(44, 'Cookie Chocotorta', 'Galleta con una cremosa cobertura de dulce de leche y crema, migas de chocolate y una galletita Chocolina.', 2700.00, '1d117d868fd50b158087a44c32cc87ba.jpg', 3, 10),
(45, 'Cookie Franui', 'Galleta con frambuesa y chocolate, decorada con una cobertura brillante, trocitos de Franui.', 3000.00, '82c7ae37d8dffa5ee69f0df214b9af33.jpg', 3, 6),
(46, 'Cookie mermelada', 'Galleta de chocolate con una corona de crema chocolatosa y un centro de salsa de frambuesa que aporta un toque frutal.', 2300.00, 'c4ce8e7d617ed49ad689814ec59016c1.jpg', 3, 10),
(47, 'Cookie triple chocolate', 'Galleta de chocolate con abundante crema de chocolate, migas crocantes y granas de colores.', 2300.00, 'a847575c0fa483ca6d5383c19d12c5e5.jpg', 3, 10),
(48, 'Muffin de Frutilla', 'Muffin suave y esponjoso, cubierto con una crema de frutilla y decorado con una frutilla fresca que le aporta un toque dulce y frutal.', 2500.00, '1cc0f2dc2ff754cd249257cc935bd2d2.jpg', 5, 8),
(49, 'Muffin de Cereza y Chocolate', 'Muffin con una deliciosa cobertura de crema rosada, acompañado de crema blanca, trocitos de chocolate y una cereza en la parte superior.', 2800.00, '89febf6e0949e442354f9c299d62b652.jpg', 5, 8),
(50, 'Muffin de Frutilla con Chocolate', 'Muffin de sabor frutal, decorado con una suave crema rosada, rulos de chocolate blanco y un toque de chocolate por encima.', 2700.00, '6a3484c2991e1db015140f4d385e6de2.jpg', 5, 8),
(51, 'Muffin de arándano', 'Muffin de chocolate con una cobertura cremosa de arándano, decorado con pequeños trocitos de chocolate y una delicada flor blanca.', 2800.00, 'e2a29276050fa33373cd9903d418e4a8.jpg', 5, 8),
(52, 'Muffin de Chocolate Intenso', 'Muffin de chocolate cubierto con una capa brillante de chocolate y decorado con una crema suave y trocitos de chocolate en la parte superior.', 2800.00, 'aa6acdc9bd7f456067747e0de1ceba14.jpg', 5, 10),
(53, 'Muffin de Limón', 'Muffin esponjoso con una cobertura cremosa de limón y un toque cítrico, terminado con una rodaja de limón que le da frescura.', 2500.00, '5c6c18b96a2103a63934000a7171f82c.jpg', 5, 8),
(54, 'Muffin de Banana', 'Muffin suave con una crema dulce, decorado con una rodaja de banana y rulos de chocolate que combinan perfectamente sus sabores.', 2600.00, 'e31694ae50d568c96c2673578eb0310d.jpg', 5, 8),
(55, 'Muffin de Crema Blanca', 'Muffin de chocolate con una generosa espiral de crema blanca, decorado con pequeños corazones rosados que le dan un acabado delicado y dulce.', 2700.00, '003217a5406814ef8d027140e9b05537.jpg', 5, 10),
(56, 'Cheesecake de frutos rojos', 'Cheesecake cremoso sobre una base crocante de galletitas, cubierto con una deliciosa salsa de frutos rojos.', 18000.00, '5f90e807bcb7742570c08136c7d16735.jpg', 1, 5),
(57, 'Torta de limón', 'Bizcochuelo suave y esponjoso con un delicado sabor a limón, cubierto con crema y decorado con una rodaja de limón.', 15000.00, 'bf66746fde2a34dd641517608e62b9e3.jpg', 1, 3),
(58, 'Marquise', 'Brownie con dulce de leche y una suave crema que combina a la perfección.', 20000.00, '7a5b00e80732657193933faffefd2abe.jpg', 1, 4),
(59, 'Lemon pie', 'Base crocante rellena de una crema de limón fresca y suave, coronada con un abundante merengue.', 16000.00, '3d7d7fe5445d8d6cb45429cd37fedd4e.jpg', 1, 5),
(60, 'Tarta de frutillas', 'Base dulce y crocante con crema suave, cubierta con frutillas frescas que le aportan un toque frutal y delicioso.', 17000.00, '171c3491ecc4c5046d72328ad4daad69.jpg', 1, 5),
(61, 'Chocotorta', 'Capas de galletas chocolinas humedecidas en café con un relleno cremoso de queso con dulce de leche y terminada con una cobertura cacao espolvoreado.', 19000.00, '16e5f307d3e34b03ddb70782b63e7521.jpg', 1, 4),
(62, 'Rogel', 'Clásico Rogel de finas capas crocantes rellenas de abundante dulce de leche, coronado con un generoso merengue.', 20000.00, '5cb37dd1c2e3a11440c50b072763811c.jpg', 1, 4),
(63, 'Torta invertida de naranja', 'Torta esponjosa con rodajas de naranja caramelizadas que aportan un sabor cítrico, dulce y muy especial.', 14000.00, '03a7864ed7f6fb47a412a07d4cc6d48c.jpg', 1, 6),
(64, 'Cheesecake clásico con arándanos', 'Cheesecake suave y cremoso sobre una base crocante de galletitas, decorado con arándanos frescos.', 17000.00, 'c590a67c1b947f009043ee06b2e2030d.jpg', 1, 5),
(65, 'Flan', 'Flan de textura suave y delicada, acompañado de una irresistible capa de caramelo líquido.', 8000.00, '4cc55c003cf24b8f09dd8b81b3d30c18.jpg', 1, 8),
(66, 'Torta de chocolate con frutos secos', 'Torta húmeda de chocolate, cubierta con chips de chocolate y una variedad de frutos secos para disfrutar de una combinación irresistible.', 18000.00, 'faf247b554bd8a82fe17217c5814d439.jpg', 1, 5),
(67, 'Tiramisú', 'Postre de capas suaves con crema y café, terminado con una fina lluvia de cacao que le da su sabor clásico.', 9000.00, '9663d0cb060551b6547197248d99337a.jpg', 1, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `ingredientes` text NOT NULL,
  `preparacion` text NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `titulo`, `ingredientes`, `preparacion`, `imagen`) VALUES
(12, 'Tarta de coco y dulce de leche', '200 g de harina\r\n100 g de manteca\r\n80 g de azúcar\r\n1 huevo\r\n1 cucharadita de esencia de vainilla\r\n400 g de dulce de leche repostero\r\n150 g de coco rallado\r\n100 g de azúcar para la cubierta\r\n1 huevo para la cubierta\r\n2 cucharadas de leche', '1. Mezclar la manteca con el azúcar. Agregar el huevo y la esencia de vainilla.\r\n2. Incorporar la harina hasta formar una masa suave. Distribuirla en un molde para tarta enmantecado.\r\n3. Cubrir la base con el dulce de leche repostero.\r\n4. Mezclar el coco rallado con el azúcar, el huevo y la leche. Distribuir sobre el dulce de leche.\r\n5. Hornear a 180 °C durante 30–40 minutos, hasta que la cubierta esté dorada. Dejar enfriar antes de cortar.', '30f76ff45fe35ef4e43459554b6ea385.jpg'),
(13, 'Muffins de arándanos', '250 g de harina\r\n100 g de azúcar\r\n2 huevos\r\n120 ml de leche\r\n80 ml de aceite\r\n150 g de arándanos\r\n2 cucharaditas de polvo de hornear\r\n1 cucharadita de esencia de vainilla', '1. Batir los huevos con el azúcar, la leche, el aceite y la vainilla.\r\n2. Incorporar la harina y el polvo de hornear, mezclando hasta integrar.\r\n3. Agregar los arándanos suavemente y distribuir la preparación en moldes para muffins.\r\n4. Hornear a 180 °C durante 20–25 minutos, hasta que estén dorados y cocidos por dentro.', '164d51099cf1f61c81c28b5b9be4e0bc.jpg'),
(14, 'Volcán de chocolate', '150 g de chocolate semiamargo\r\n100 g de manteca\r\n2 huevos\r\n2 yemas\r\n80 g de azúcar\r\n50 g de harina', '1. Derretir el chocolate junto con la manteca y dejar entibiar.\r\n2. Batir los huevos, las yemas y el azúcar hasta integrar.\r\n3. Agregar el chocolate derretido y luego incorporar la harina tamizada.\r\n4. Verter en moldes individuales enmantecados y hornear a 200 °C durante aproximadamente 8–10 minutos. El centro debe quedar húmedo para lograr el efecto volcán.', '61eea1b14566a2ac37f7e8e77915609d.jpg'),
(15, 'Pastafrola de membrillo', '300 g de harina\r\n100 g de azúcar\r\n150 g de manteca\r\n1 huevo\r\n1 cucharadita de polvo de hornear\r\n300 g de dulce de membrillo\r\n2 cucharadas de agua', '1. Mezclar la manteca con el azúcar y el huevo.\r\n2. Incorporar la harina y el polvo de hornear hasta formar una masa. Reservar una parte para las tiras.\r\n3. Forrar un molde con el resto de la masa y distribuir el membrillo previamente ablandado con el agua.\r\n4. Decorar con tiras de masa y hornear a 180 °C durante 35–40 minutos, hasta que esté dorada.', '839af36b28f97551a2a003c12f840ded.jpg'),
(16, 'Budín de limón y amapola', '250 g de harina\r\n150 g de azúcar\r\n2 huevos\r\n100 ml de aceite\r\n120 ml de leche\r\nJugo y ralladura de 1 limón\r\n2 cucharadas de semillas de amapola\r\n2 cucharaditas de polvo de hornear', '1. Batir los huevos con el azúcar y agregar el aceite, la leche, el jugo y la ralladura de limón.\r\n2. Incorporar la harina, el polvo de hornear y las semillas de amapola.\r\n3. Verter la preparación en un molde para budín previamente enmantecado y enharinado.\r\n4. Hornear a 180 °C durante 35–45 minutos. Dejar enfriar antes de desmoldar.', 'e70b41a7389b14490b84b971b576ea09.jpg'),
(17, 'Cookies de chocolate y sal marina', '250 g de harina\r\n125 g de manteca blanda\r\n120 g de azúcar rubia\r\n50 g de azúcar blanca\r\n1 huevo\r\n150 g de chocolate semiamargo picado\r\n1 cucharadita de esencia de vainilla\r\n1 cucharadita de polvo de hornear\r\nSal marina en escamas, a gusto', '1. Batir la manteca con los dos tipos de azúcar hasta obtener una mezcla cremosa.\r\n2. Agregar el huevo y la vainilla. Incorporar la harina y el polvo de hornear.\r\n3. Añadir el chocolate picado, formar bolitas y colocarlas separadas en una bandeja.\r\n4. Hornear a 180 °C durante 10–14 minutos. Al salir del horno, agregar unas escamas de sal marina y dejar enfriar.', '4846e58da1859038074ba43a77931af6.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `password`, `rol`) VALUES
(8, 'Administrador', 'Medialuna', 'admin@dulce.com', '2996062026', 'Lanin 2020', '$2y$10$Tj3gPei./d6fHTO0yM7Qg.8jqPqfHUe/.d7Yz5wmbt6iXhNsVtsza', 'admin'),
(9, 'Lucia', 'Márquez', 'luciammarquez11@gmail.com', '2996091680', 'Belgrano 2626', '$2y$10$/olqAnx0nhC8jQAUabhcAO9nZJJTU3G.Q8E4iKyDoI3mU.JkO7BAi', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
