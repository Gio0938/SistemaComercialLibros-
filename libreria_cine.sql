/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - libreria_cine
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`libreria_cine` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `libreria_cine`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `carrito` */

DROP TABLE IF EXISTS `carrito`;

CREATE TABLE `carrito` (
  `idcarrito` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tipo_producto` enum('libro','pelicula') NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idcarrito`),
  UNIQUE KEY `unique_carrito` (`usuario_id`,`tipo_producto`,`producto_id`),
  KEY `idx_carrito_usuario` (`usuario_id`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `carrito` */

/*Table structure for table `categorias` */

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('libro','pelicula') NOT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `activa` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idcategoria`),
  KEY `idx_categorias_tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categorias` */

insert  into `categorias`(`idcategoria`,`nombre`,`descripcion`,`tipo`,`icono`,`activa`,`created_at`,`updated_at`) values 
(1,'Ficción','Libros de ficción y narrativa','libro',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(2,'No Ficción','Libros de no ficción y ensayos','libro',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(3,'Ciencia Ficción','Libros de ciencia ficción','libro',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(4,'Fantasía','Libros de fantasía','libro',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(5,'Terror','Libros de terror','libro',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(6,'Romance','Libros de romance','libro',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(7,'Aventura','Películas de aventura','pelicula',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(8,'Comedia','Películas de comedia','pelicula',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(9,'Drama','Películas de drama','pelicula',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(10,'Ciencia Ficción','Películas de ciencia ficción','pelicula',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(11,'Terror','Películas de terror','pelicula',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(12,'Animación','Películas animadas','pelicula',NULL,1,'2026-07-09 00:46:26','2026-07-09 00:46:26');

/*Table structure for table `clientes` */

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `pais` varchar(50) DEFAULT 'México',
  `fecha_registro` date DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idcliente`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_clientes_email` (`email`),
  KEY `idx_clientes_ciudad` (`ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `clientes` */

insert  into `clientes`(`idcliente`,`nombre`,`apellido`,`email`,`telefono`,`direccion`,`ciudad`,`estado`,`codigo_postal`,`pais`,`fecha_registro`,`created_at`,`updated_at`) values 
(1,'Juan','Pérez','juan.perez@email.com','2281234567','Calle Principal 123','Xalapa','Veracruz',NULL,'México','2026-07-09','2026-07-09 00:46:26','2026-07-09 00:46:26'),
(2,'María','González','maria.gonzalez@email.com','2287654321','Avenida Reforma 456','Xalapa','Veracruz',NULL,'México','2026-07-09','2026-07-09 00:46:26','2026-07-09 00:46:26'),
(3,'Carlos','Rodríguez','carlos.rodriguez@email.com','2289876543','Boulevard 789','Boca del Río','Veracruz',NULL,'México','2026-07-09','2026-07-09 00:46:26','2026-07-09 00:46:26'),
(4,'Ana','Martínez','ana.martinez@email.com','2284567890','Calle Independencia 321','Xalapa','Veracruz',NULL,'México','2026-07-09','2026-07-09 00:46:26','2026-07-09 00:46:26'),
(5,'Luis','Sánchez','luis.sanchez@email.com','2291234567','Calle 5 de Febrero 456','Veracruz','Veracruz',NULL,'México','2026-07-09','2026-07-09 00:46:26','2026-07-09 00:46:26');

/*Table structure for table `detalles_venta` */

DROP TABLE IF EXISTS `detalles_venta`;

CREATE TABLE `detalles_venta` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `tipo_producto` enum('libro','pelicula') NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`iddetalle`),
  KEY `idx_detalles_venta` (`venta_id`),
  KEY `idx_detalles_producto` (`tipo_producto`,`producto_id`),
  CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`idventa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `detalles_venta` */

insert  into `detalles_venta`(`iddetalle`,`venta_id`,`tipo_producto`,`producto_id`,`cantidad`,`precio_unitario`,`descuento`,`subtotal`,`created_at`) values 
(1,1,'libro',1,1,299.00,59.80,239.20,'2026-07-09 00:46:26'),
(2,1,'libro',2,1,149.00,0.00,149.00,'2026-07-09 00:46:26'),
(3,2,'pelicula',1,1,299.00,0.00,299.00,'2026-07-09 00:46:26'),
(4,2,'pelicula',5,1,259.00,0.00,259.00,'2026-07-09 00:46:26'),
(5,3,'libro',3,1,250.00,0.00,250.00,'2026-07-09 00:46:26'),
(6,3,'libro',5,1,350.00,0.00,350.00,'2026-07-09 00:46:26'),
(7,3,'pelicula',1,1,299.00,134.85,164.15,'2026-07-09 00:46:26');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `libros` */

DROP TABLE IF EXISTS `libros`;

CREATE TABLE `libros` (
  `idlibro` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(150) NOT NULL,
  `editorial` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_promocion` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `paginas` int(11) DEFAULT NULL,
  `idioma` varchar(30) DEFAULT 'Español',
  `disponible` tinyint(1) DEFAULT 1,
  `destacado` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idlibro`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `idx_libros_genero` (`genero`),
  KEY `idx_libros_autor` (`autor`),
  KEY `idx_libros_disponible` (`disponible`),
  KEY `idx_libros_destacado` (`destacado`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `libros` */

insert  into `libros`(`idlibro`,`titulo`,`autor`,`editorial`,`isbn`,`genero`,`descripcion`,`precio`,`precio_promocion`,`stock`,`stock_minimo`,`foto`,`fecha_publicacion`,`paginas`,`idioma`,`disponible`,`destacado`,`created_at`,`updated_at`) values 
(1,'Cien años de soledad','Gabriel García Márquez','Editorial Sudamericana','978-987-719-123-4','Ficción','La historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.',299.00,NULL,25,5,NULL,'1967-06-05',496,'Español',1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(2,'El principito','Antoine de Saint-Exupéry','Reynal & Hitchcock','978-987-719-124-1','Ficción','Un piloto se encuentra con un pequeño príncipe en el desierto del Sahara.',149.00,NULL,40,10,NULL,'1943-04-06',96,'Español',1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(3,'1984','George Orwell','Secker & Warburg','978-987-719-125-8','Ciencia Ficción','Una distopía sobre un régimen totalitario que controla todos los aspectos de la vida.',250.00,NULL,15,5,NULL,'1949-06-08',328,'Español',1,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(4,'El señor de los anillos','J.R.R. Tolkien','George Allen & Unwin','978-987-719-126-5','Fantasía','La épica aventura de Frodo Bolsón para destruir el Anillo Único.',450.00,NULL,20,5,NULL,'1954-07-29',1178,'Español',1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(5,'Harry Potter y la piedra filosofal','J.K. Rowling','Bloomsbury','978-987-719-127-2','Fantasía','El joven mago Harry Potter descubre su herencia mágica.',350.00,NULL,30,10,NULL,'1997-06-26',256,'Español',1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(6,'El código Da Vinci','Dan Brown','Doubleday','978-987-719-128-9','Suspenso','Un profesor de simbología investiga un asesinato en el Louvre.',280.00,NULL,18,5,NULL,'2003-03-18',454,'Español',1,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(7,'La sombra del viento','Carlos Ruiz Zafón','Planeta','978-987-719-129-6','Misterio','Un joven encuentra un libro maldito en el Cementerio de los Libros Olvidados.',320.00,NULL,12,5,NULL,'2001-07-28',565,'Español',1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(8,'El alquimista','Paulo Coelho','HarperCollins','978-987-719-130-2','Ficción','Un pastor andaluz viaja en busca de un tesoro en las pirámides de Egipto.',220.00,NULL,35,10,NULL,'1988-01-01',208,'Español',1,0,'2026-07-09 00:46:26','2026-07-09 00:46:26');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_11_15_195120_create_servicios_table',1),
(5,'2025_11_15_195322_create_productos_table',1),
(6,'2025_11_15_201735_create_promocions_table',1);

/*Table structure for table `movimientos_inventario` */

DROP TABLE IF EXISTS `movimientos_inventario`;

CREATE TABLE `movimientos_inventario` (
  `idmovimiento` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_producto` enum('libro','pelicula') NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida','ajuste') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_anterior` int(11) NOT NULL,
  `cantidad_nueva` int(11) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idmovimiento`),
  KEY `usuario_id` (`usuario_id`),
  KEY `idx_movimientos_producto` (`tipo_producto`,`producto_id`),
  KEY `idx_movimientos_fecha` (`created_at`),
  CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `movimientos_inventario` */

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `peliculas` */

DROP TABLE IF EXISTS `peliculas`;

CREATE TABLE `peliculas` (
  `idpelicula` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `director` varchar(150) NOT NULL,
  `reparto` text DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos',
  `genero` varchar(50) DEFAULT NULL,
  `clasificacion` varchar(10) DEFAULT NULL COMMENT 'PG, PG-13, R, etc.',
  `sinopsis` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_promocion` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `portada` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `formato` varchar(30) DEFAULT 'DVD' COMMENT 'DVD, Blu-ray, Digital',
  `idioma` varchar(30) DEFAULT 'Español',
  `subtitulos` varchar(50) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT 1,
  `destacado` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idpelicula`),
  KEY `idx_peliculas_genero` (`genero`),
  KEY `idx_peliculas_director` (`director`),
  KEY `idx_peliculas_anio` (`anio`),
  KEY `idx_peliculas_disponible` (`disponible`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `peliculas` */

insert  into `peliculas`(`idpelicula`,`titulo`,`director`,`reparto`,`anio`,`duracion`,`genero`,`clasificacion`,`sinopsis`,`precio`,`precio_promocion`,`stock`,`stock_minimo`,`portada`,`trailer_url`,`formato`,`idioma`,`subtitulos`,`disponible`,`destacado`,`created_at`,`updated_at`) values 
(1,'Inception','Christopher Nolan','Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page',2010,148,'Ciencia Ficción','PG-13','Un ladrón que roba secretos corporativos a través del uso de la tecnología de sueños.',299.00,NULL,20,5,NULL,NULL,'Blu-ray','Español',NULL,1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(2,'The Shawshank Redemption','Frank Darabont','Tim Robbins, Morgan Freeman',1994,142,'Drama','R','Un banquero condenado a cadena perpetua en Shawshank descubre la verdadera libertad.',249.00,NULL,15,5,NULL,NULL,'DVD','Español',NULL,1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(3,'The Dark Knight','Christopher Nolan','Christian Bale, Heath Ledger, Aaron Eckhart',2008,152,'Acción','PG-13','Batman se enfrenta al Joker, un criminal psicópata que quiere sumir a Gotham en el caos.',299.00,NULL,18,5,NULL,NULL,'Blu-ray','Español',NULL,1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(4,'Pulp Fiction','Quentin Tarantino','John Travolta, Uma Thurman, Samuel L. Jackson',1994,154,'Crimen','R','Historias entrelazadas sobre gánsteres, boxeadores y asesinos en Los Ángeles.',269.00,NULL,10,5,NULL,NULL,'DVD','Español',NULL,1,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(5,'Forrest Gump','Robert Zemeckis','Tom Hanks, Robin Wright, Gary Sinise',1994,142,'Drama','PG-13','Un hombre con un coeficiente intelectual bajo pero un corazón enorme vive momentos clave de la historia.',259.00,NULL,25,10,NULL,NULL,'Blu-ray','Español',NULL,1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(6,'Gladiator','Ridley Scott','Russell Crowe, Joaquin Phoenix, Connie Nielsen',2000,155,'Acción','R','Un general romano traicionado busca venganza en la arena del Coliseo.',279.00,NULL,12,5,NULL,NULL,'DVD','Español',NULL,1,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(7,'Matrix','Lana Wachowski, Lilly Wachowski','Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',1999,136,'Ciencia Ficción','R','Un hacker descubre la verdad sobre su realidad y lucha contra las máquinas.',289.00,NULL,20,5,NULL,NULL,'Blu-ray','Español',NULL,1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(8,'El Señor de los Anillos','Peter Jackson','Elijah Wood, Ian McKellen, Viggo Mortensen',2001,178,'Fantasía','PG-13','Una comunidad de razas debe destruir un anillo para salvar la Tierra Media.',350.00,NULL,15,5,NULL,NULL,'Blu-ray','Español',NULL,1,1,'2026-07-09 00:46:26','2026-07-09 00:46:26');

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `productos` */

/*Table structure for table `promociones` */

DROP TABLE IF EXISTS `promociones`;

CREATE TABLE `promociones` (
  `idpromocion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('libro','pelicula','ambos') NOT NULL,
  `libro_id` int(11) DEFAULT NULL,
  `pelicula_id` int(11) DEFAULT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT NULL,
  `descuento_fijo` decimal(10,2) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `activa` tinyint(1) DEFAULT 1,
  `codigo_promocional` varchar(50) DEFAULT NULL,
  `uso_maximo` int(11) DEFAULT NULL,
  `usos_actuales` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idpromocion`),
  UNIQUE KEY `codigo_promocional` (`codigo_promocional`),
  KEY `libro_id` (`libro_id`),
  KEY `pelicula_id` (`pelicula_id`),
  KEY `idx_promociones_fechas` (`fecha_inicio`,`fecha_fin`),
  KEY `idx_promociones_activa` (`activa`),
  KEY `idx_promociones_codigo` (`codigo_promocional`),
  CONSTRAINT `promociones_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`idlibro`) ON DELETE CASCADE,
  CONSTRAINT `promociones_ibfk_2` FOREIGN KEY (`pelicula_id`) REFERENCES `peliculas` (`idpelicula`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promociones` */

insert  into `promociones`(`idpromocion`,`nombre`,`descripcion`,`tipo`,`libro_id`,`pelicula_id`,`descuento_porcentaje`,`descuento_fijo`,`fecha_inicio`,`fecha_fin`,`activa`,`codigo_promocional`,`uso_maximo`,`usos_actuales`,`created_at`,`updated_at`) values 
(1,'Oferta Primavera - Libros','Descuento especial en libros de ficción','libro',NULL,NULL,20.00,NULL,'2026-07-04 00:46:26','2026-07-19 00:46:26',1,'PRIMAVERA20',NULL,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(2,'Oferta Primavera - Películas','2x1 en películas seleccionadas','pelicula',NULL,NULL,10.00,NULL,'2026-07-06 00:46:26','2026-07-24 00:46:26',1,'CINE2X1',NULL,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(3,'Oferta Especial Cien años','Descuento en Cien años de soledad','libro',1,NULL,25.00,NULL,'2026-07-02 00:46:26','2026-07-14 00:46:26',1,'GARCIA25',NULL,0,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(4,'Oferta Especial Inception','Descuento en Inception','pelicula',NULL,1,15.00,NULL,'2026-07-02 00:46:26','2026-07-14 00:46:26',1,'INCEPTION15',NULL,0,'2026-07-09 00:46:26','2026-07-09 00:46:26');

/*Table structure for table `promocions` */

DROP TABLE IF EXISTS `promocions`;

CREATE TABLE `promocions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promocions` */

/*Table structure for table `servicios` */

DROP TABLE IF EXISTS `servicios`;

CREATE TABLE `servicios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `servicios` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('JGvpzMZndDiqikGxwzZeIKrWRQUcMW9V4S5K0v3N',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWjAzakJPUlIxbm9LNUZuTDRXQkhtZ09DMEtDN1A1Nm1nUnBRRVFTSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czoxMjoicHVibGljLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fX0=',1783580756);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','vendedor','cliente') DEFAULT 'cliente',
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_usuarios_email` (`email`),
  KEY `idx_usuarios_rol` (`rol`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`idusuario`,`nombre`,`email`,`password`,`rol`,`telefono`,`activo`,`ultimo_acceso`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Admin Sistema','admin@libreriaycine.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',NULL,1,NULL,NULL,'2026-07-09 00:46:26','2026-07-09 00:46:26');

/*Table structure for table `ventas` */

DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(20) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_venta` datetime DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `iva` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','credito') NOT NULL,
  `estado` enum('completada','pendiente','cancelada') DEFAULT 'completada',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idventa`),
  UNIQUE KEY `folio` (`folio`),
  KEY `cliente_id` (`cliente_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `idx_ventas_folio` (`folio`),
  KEY `idx_ventas_fecha` (`fecha_venta`),
  KEY `idx_ventas_estado` (`estado`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`idcliente`) ON DELETE SET NULL,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ventas` */

insert  into `ventas`(`idventa`,`folio`,`cliente_id`,`usuario_id`,`fecha_venta`,`subtotal`,`descuento`,`iva`,`total`,`metodo_pago`,`estado`,`observaciones`,`created_at`,`updated_at`) values 
(1,'VEN-2024-001',1,1,'2026-07-07 00:46:26',598.00,59.80,80.73,618.93,'tarjeta','completada',NULL,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(2,'VEN-2024-002',2,1,'2026-07-08 00:46:26',448.00,0.00,60.48,508.48,'efectivo','completada',NULL,'2026-07-09 00:46:26','2026-07-09 00:46:26'),
(3,'VEN-2024-003',3,1,'2026-07-08 12:46:26',899.00,134.85,108.62,872.77,'tarjeta','completada',NULL,'2026-07-09 00:46:26','2026-07-09 00:46:26');

/* Trigger structure for table `detalles_venta` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tr_actualizar_stock_venta` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `tr_actualizar_stock_venta` AFTER INSERT ON `detalles_venta` FOR EACH ROW 
BEGIN
    IF NEW.tipo_producto = 'libro' THEN
        UPDATE libros 
        SET stock = stock - NEW.cantidad 
        WHERE idlibro = NEW.producto_id;
    ELSEIF NEW.tipo_producto = 'pelicula' THEN
        UPDATE peliculas 
        SET stock = stock - NEW.cantidad 
        WHERE idpelicula = NEW.producto_id;
    END IF;
END */$$


DELIMITER ;

/* Trigger structure for table `libros` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `tr_registrar_movimiento_stock` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `tr_registrar_movimiento_stock` AFTER UPDATE ON `libros` FOR EACH ROW 
BEGIN
    IF NEW.stock != OLD.stock THEN
        INSERT INTO movimientos_inventario (tipo_producto, producto_id, tipo_movimiento, cantidad, cantidad_anterior, cantidad_nueva, usuario_id)
        VALUES ('libro', NEW.idlibro, 
            IF(NEW.stock > OLD.stock, 'entrada', 'salida'),
            ABS(NEW.stock - OLD.stock),
            OLD.stock,
            NEW.stock,
            1);
    END IF;
END */$$


DELIMITER ;

/* Procedure structure for procedure `sp_registrar_venta` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_registrar_venta` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_registrar_venta`(
    IN p_cliente_id INT,
    IN p_usuario_id INT,
    IN p_metodo_pago VARCHAR(20),
    IN p_items JSON
)
BEGIN
    DECLARE v_subtotal DECIMAL(10,2) DEFAULT 0;
    DECLARE v_total DECIMAL(10,2) DEFAULT 0;
    DECLARE v_venta_id INT;
    DECLARE v_folio VARCHAR(20);
    DECLARE i INT DEFAULT 0;
    DECLARE v_items_count INT;
    
    -- Generar folio
    SET v_folio = CONCAT('VEN-', YEAR(NOW()), '-', LPAD((SELECT COUNT(*) + 1 FROM ventas), 3, '0'));
    
    -- Calcular subtotal
    -- (Aquí se procesaría el JSON de items)
    
    -- Insertar venta
    INSERT INTO ventas (folio, cliente_id, usuario_id, subtotal, total, metodo_pago, estado)
    VALUES (v_folio, p_cliente_id, p_usuario_id, v_subtotal, v_total, p_metodo_pago, 'completada');
    
    SET v_venta_id = LAST_INSERT_ID();
    
    -- Insertar detalles y actualizar stock
    -- (Aquí se procesarían los items)
    
END */$$
DELIMITER ;

/*Table structure for table `productos_agotados` */

DROP TABLE IF EXISTS `productos_agotados`;

/*!50001 DROP VIEW IF EXISTS `productos_agotados` */;
/*!50001 DROP TABLE IF EXISTS `productos_agotados` */;

/*!50001 CREATE TABLE  `productos_agotados`(
 `tipo` varchar(8) ,
 `id_producto` int(11) ,
 `nombre` varchar(255) ,
 `stock` int(11) ,
 `estado` varchar(7) 
)*/;

/*Table structure for table `productos_stock_bajo` */

DROP TABLE IF EXISTS `productos_stock_bajo`;

/*!50001 DROP VIEW IF EXISTS `productos_stock_bajo` */;
/*!50001 DROP TABLE IF EXISTS `productos_stock_bajo` */;

/*!50001 CREATE TABLE  `productos_stock_bajo`(
 `tipo` varchar(8) ,
 `id_producto` int(11) ,
 `nombre` varchar(255) ,
 `stock` int(11) ,
 `stock_minimo` int(11) ,
 `alerta` varchar(269) 
)*/;

/*Table structure for table `ventas_dia` */

DROP TABLE IF EXISTS `ventas_dia`;

/*!50001 DROP VIEW IF EXISTS `ventas_dia` */;
/*!50001 DROP TABLE IF EXISTS `ventas_dia` */;

/*!50001 CREATE TABLE  `ventas_dia`(
 `fecha` date ,
 `total_ventas` bigint(21) ,
 `total_ingresos` decimal(32,2) 
)*/;

/*View structure for view productos_agotados */

/*!50001 DROP TABLE IF EXISTS `productos_agotados` */;
/*!50001 DROP VIEW IF EXISTS `productos_agotados` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productos_agotados` AS select 'libro' AS `tipo`,`libros`.`idlibro` AS `id_producto`,`libros`.`titulo` AS `nombre`,`libros`.`stock` AS `stock`,'Agotado' AS `estado` from `libros` where `libros`.`stock` = 0 union select 'pelicula' AS `tipo`,`peliculas`.`idpelicula` AS `id_producto`,`peliculas`.`titulo` AS `nombre`,`peliculas`.`stock` AS `stock`,'Agotado' AS `estado` from `peliculas` where `peliculas`.`stock` = 0 */;

/*View structure for view productos_stock_bajo */

/*!50001 DROP TABLE IF EXISTS `productos_stock_bajo` */;
/*!50001 DROP VIEW IF EXISTS `productos_stock_bajo` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productos_stock_bajo` AS select 'libro' AS `tipo`,`libros`.`idlibro` AS `id_producto`,`libros`.`titulo` AS `nombre`,`libros`.`stock` AS `stock`,`libros`.`stock_minimo` AS `stock_minimo`,concat('Stock bajo en ',`libros`.`titulo`) AS `alerta` from `libros` where `libros`.`stock` <= `libros`.`stock_minimo` and `libros`.`stock` > 0 union select 'pelicula' AS `tipo`,`peliculas`.`idpelicula` AS `id_producto`,`peliculas`.`titulo` AS `nombre`,`peliculas`.`stock` AS `stock`,`peliculas`.`stock_minimo` AS `stock_minimo`,concat('Stock bajo en ',`peliculas`.`titulo`) AS `alerta` from `peliculas` where `peliculas`.`stock` <= `peliculas`.`stock_minimo` and `peliculas`.`stock` > 0 */;

/*View structure for view ventas_dia */

/*!50001 DROP TABLE IF EXISTS `ventas_dia` */;
/*!50001 DROP VIEW IF EXISTS `ventas_dia` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ventas_dia` AS select cast(`ventas`.`fecha_venta` as date) AS `fecha`,count(0) AS `total_ventas`,sum(`ventas`.`total`) AS `total_ingresos` from `ventas` where cast(`ventas`.`fecha_venta` as date) = curdate() group by cast(`ventas`.`fecha_venta` as date) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
