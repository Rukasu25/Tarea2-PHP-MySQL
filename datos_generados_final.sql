-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2026 a las 23:09:32
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
-- Base de datos: `saludusm`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cancelar_citas_vencidas` ()   BEGIN
    UPDATE cita c
    JOIN estado_cita e ON e.id_estado = c.id_estado
    SET c.id_estado = (SELECT id_estado FROM estado_cita WHERE nombre='Cancelada')
    WHERE e.nombre IN ('Reservada','Confirmada')
      AND c.fecha_hora < NOW();
END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_edad` (`fecha_nac` DATE) RETURNS INT(11) DETERMINISTIC BEGIN
    RETURN TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atencion`
--

CREATE TABLE `atencion` (
  `id_atencion` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `motivo` varchar(500) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `atencion`
--

INSERT INTO `atencion` (`id_atencion`, `id_cita`, `motivo`, `observaciones`, `fecha_registro`) VALUES
(1, 2, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(2, 4, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(3, 5, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(4, 6, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(5, 8, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(6, 10, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(7, 14, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(8, 16, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(9, 19, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(10, 21, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(11, 22, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(12, 26, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(13, 28, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17'),
(14, 29, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.', '2026-10-11 21:05:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro_medico`
--

CREATE TABLE `centro_medico` (
  `id_centro` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `comuna` varchar(80) NOT NULL,
  `region` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `centro_medico`
--

INSERT INTO `centro_medico` (`id_centro`, `codigo`, `nombre`, `comuna`, `region`) VALUES
(1, 'C001', 'Centro SaludUSM Casa Central', 'Valparaiso', 'Valparaiso'),
(2, 'C002', 'Centro SaludUSM San Joaquin', 'San Joaquin', 'Metropolitana'),
(3, 'C003', 'Centro SaludUSM Maipu', 'Maipu', 'Metropolitana'),
(4, 'C004', 'Centro SaludUSM Concepcion', 'Concepcion', 'Biobio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `id_centro` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `id_estado` int(11) NOT NULL,
  `creada_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cita`
--

INSERT INTO `cita` (`id_cita`, `id_paciente`, `id_medico`, `id_especialidad`, `id_centro`, `fecha_hora`, `id_estado`, `creada_en`) VALUES
(1, 17, 3, 2, 2, '2026-10-10 08:00:00', 2, '2026-10-11 21:05:17'),
(2, 6, 5, 4, 3, '2026-10-10 17:00:00', 3, '2026-10-11 21:05:17'),
(3, 5, 6, 4, 1, '2026-10-09 11:00:00', 1, '2026-10-11 21:05:17'),
(4, 19, 4, 5, 4, '2026-10-09 10:00:00', 3, '2026-10-11 21:05:17'),
(5, 1, 10, 4, 4, '2026-10-08 12:00:00', 3, '2026-10-11 21:05:17'),
(6, 7, 8, 8, 4, '2026-10-08 08:00:00', 3, '2026-10-11 21:05:17'),
(7, 13, 9, 7, 2, '2026-10-07 16:00:00', 1, '2026-10-11 21:05:17'),
(8, 12, 4, 5, 4, '2026-10-07 10:00:00', 3, '2026-10-11 21:05:17'),
(9, 5, 1, 7, 4, '2026-10-06 17:00:00', 2, '2026-10-11 21:05:17'),
(10, 7, 1, 7, 4, '2026-10-06 12:00:00', 3, '2026-10-11 21:05:17'),
(11, 18, 9, 4, 2, '2026-10-05 08:00:00', 4, '2026-10-11 21:05:17'),
(12, 11, 10, 3, 1, '2026-10-05 09:00:00', 4, '2026-10-11 21:05:17'),
(13, 2, 3, 7, 3, '2026-10-04 13:00:00', 2, '2026-10-11 21:05:17'),
(14, 9, 8, 3, 4, '2026-10-04 11:00:00', 3, '2026-10-11 21:05:17'),
(15, 10, 4, 5, 4, '2026-10-03 10:00:00', 1, '2026-10-11 21:05:17'),
(16, 18, 3, 2, 3, '2026-10-03 11:00:00', 3, '2026-10-11 21:05:17'),
(17, 18, 6, 5, 1, '2026-10-02 09:00:00', 4, '2026-10-11 21:05:17'),
(18, 6, 2, 5, 1, '2026-10-02 13:00:00', 1, '2026-10-11 21:05:17'),
(19, 14, 10, 3, 1, '2026-10-01 17:00:00', 3, '2026-10-11 21:05:17'),
(20, 11, 5, 4, 3, '2026-10-01 12:00:00', 1, '2026-10-11 21:05:17'),
(21, 9, 4, 5, 4, '2026-09-30 08:00:00', 3, '2026-10-11 21:05:17'),
(22, 17, 8, 8, 1, '2026-09-30 14:00:00', 3, '2026-10-11 21:05:17'),
(23, 2, 8, 4, 4, '2026-09-29 13:00:00', 1, '2026-10-11 21:05:17'),
(24, 3, 8, 8, 1, '2026-09-29 16:00:00', 4, '2026-10-11 21:05:17'),
(25, 15, 1, 2, 4, '2026-09-28 15:00:00', 2, '2026-10-11 21:05:17'),
(26, 20, 5, 4, 3, '2026-09-28 12:00:00', 3, '2026-10-11 21:05:17'),
(27, 20, 2, 1, 1, '2026-09-27 12:00:00', 4, '2026-10-11 21:05:17'),
(28, 9, 10, 3, 1, '2026-09-27 13:00:00', 3, '2026-10-11 21:05:17'),
(29, 12, 8, 4, 1, '2026-09-26 08:00:00', 3, '2026-10-11 21:05:17'),
(30, 18, 8, 4, 1, '2026-09-26 14:00:00', 2, '2026-10-11 21:05:17'),
(31, 6, 9, 4, 2, '2026-10-12 16:00:00', 2, '2026-10-11 21:05:17'),
(32, 18, 8, 8, 1, '2026-10-12 09:00:00', 2, '2026-10-11 21:05:17'),
(33, 17, 7, 8, 2, '2026-10-13 12:00:00', 2, '2026-10-11 21:05:17'),
(34, 1, 2, 5, 1, '2026-10-13 10:00:00', 2, '2026-10-11 21:05:17'),
(35, 1, 8, 3, 1, '2026-10-14 15:00:00', 1, '2026-10-11 21:05:17'),
(36, 16, 10, 3, 1, '2026-10-14 12:00:00', 1, '2026-10-11 21:05:17'),
(37, 19, 10, 3, 4, '2026-10-15 15:00:00', 1, '2026-10-11 21:05:17'),
(38, 9, 2, 5, 1, '2026-10-15 11:00:00', 2, '2026-10-11 21:05:17'),
(39, 4, 9, 7, 2, '2026-10-16 08:00:00', 1, '2026-10-11 21:05:17'),
(40, 3, 5, 4, 1, '2026-10-16 12:00:00', 2, '2026-10-11 21:05:17'),
(41, 5, 4, 5, 4, '2026-10-17 13:00:00', 1, '2026-10-11 21:05:17'),
(42, 14, 10, 3, 4, '2026-10-17 11:00:00', 2, '2026-10-11 21:05:17'),
(43, 6, 3, 2, 2, '2026-10-18 12:00:00', 2, '2026-10-11 21:05:17'),
(44, 6, 1, 2, 4, '2026-10-18 14:00:00', 2, '2026-10-11 21:05:17'),
(45, 2, 1, 2, 4, '2026-10-19 09:00:00', 1, '2026-10-11 21:05:17'),
(46, 4, 4, 5, 4, '2026-10-19 14:00:00', 2, '2026-10-11 21:05:17'),
(47, 17, 6, 5, 1, '2026-10-20 17:00:00', 1, '2026-10-11 21:05:17'),
(48, 18, 3, 2, 3, '2026-10-20 17:00:00', 2, '2026-10-11 21:05:17'),
(49, 2, 6, 2, 1, '2026-10-21 15:00:00', 2, '2026-10-11 21:05:17'),
(50, 20, 2, 8, 3, '2026-10-21 14:00:00', 2, '2026-10-11 21:05:17');

--
-- Disparadores `cita`
--
DELIMITER $$
CREATE TRIGGER `trg_cita_validar` BEFORE INSERT ON `cita` FOR EACH ROW BEGIN
    DECLARE n INT DEFAULT 0;

    SELECT COUNT(*) INTO n FROM medico_especialidad
    WHERE id_medico = NEW.id_medico AND id_especialidad = NEW.id_especialidad;
    IF n = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El medico no posee la especialidad solicitada';
    END IF;

    SELECT COUNT(*) INTO n FROM medico_centro
    WHERE id_medico = NEW.id_medico AND id_centro = NEW.id_centro;
    IF n = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El medico no atiende en el centro seleccionado';
    END IF;

    -- Solo exigir fecha futura si la cita se está recién reservando
    IF NEW.id_estado = 1 AND NEW.fecha_hora <= NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La cita a reservar debe quedar en una fecha futura';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico`
--

CREATE TABLE `diagnostico` (
  `id_diagnostico` int(11) NOT NULL,
  `codigo_cie10` varchar(20) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `diagnostico`
--

INSERT INTO `diagnostico` (`id_diagnostico`, `codigo_cie10`, `descripcion`) VALUES
(1, 'J00', 'Rinofaringitis aguda'),
(2, 'J06.9', 'Infeccion respiratoria aguda'),
(3, 'I10', 'Hipertension esencial'),
(4, 'M54.5', 'Dolor lumbar'),
(5, 'L20.9', 'Dermatitis atopica'),
(6, 'R51', 'Cefalea'),
(7, 'K21.9', 'Reflujo gastroesofagico'),
(8, 'E11', 'Diabetes mellitus tipo 2'),
(9, 'J45', 'Asma'),
(10, 'F41.9', 'Trastorno de ansiedad'),
(11, 'M23.2', 'Desgarro de menisco'),
(12, 'H66.9', 'Otitis media');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico_atencion`
--

CREATE TABLE `diagnostico_atencion` (
  `id_atencion` int(11) NOT NULL,
  `id_diagnostico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `diagnostico_atencion`
--

INSERT INTO `diagnostico_atencion` (`id_atencion`, `id_diagnostico`) VALUES
(1, 11),
(2, 9),
(3, 11),
(4, 3),
(5, 11),
(6, 11),
(7, 5),
(8, 2),
(9, 7),
(10, 1),
(11, 1),
(12, 11),
(13, 4),
(14, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `nombre`) VALUES
(2, 'Cardiologia'),
(4, 'Dermatologia'),
(8, 'Ginecologia'),
(1, 'Medicina General'),
(6, 'Neurologia'),
(7, 'Oftalmologia'),
(3, 'Pediatria'),
(5, 'Traumatologia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_cita`
--

CREATE TABLE `estado_cita` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estado_cita`
--

INSERT INTO `estado_cita` (`id_estado`, `nombre`) VALUES
(3, 'Atendida'),
(5, 'Cancelada'),
(2, 'Confirmada'),
(4, 'No Asistio'),
(1, 'Reservada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamento`
--

CREATE TABLE `medicamento` (
  `id_medicamento` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medicamento`
--

INSERT INTO `medicamento` (`id_medicamento`, `nombre`) VALUES
(3, 'Amoxicilina'),
(12, 'Aspirina'),
(15, 'Clobetasol'),
(14, 'Diazepam'),
(6, 'Diclofenaco'),
(11, 'Enalapril'),
(2, 'Ibuprofeno'),
(4, 'Loratadina'),
(7, 'Losartan'),
(8, 'Metformina'),
(5, 'Omeprazol'),
(1, 'Paracetamol'),
(13, 'Prednisona'),
(9, 'Salbutamol'),
(10, 'Sertralina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico`
--

CREATE TABLE `medico` (
  `id_medico` int(11) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medico`
--

INSERT INTO `medico` (`id_medico`, `email`) VALUES
(1, 'medico1@saludusm.cl'),
(10, 'medico10@saludusm.cl'),
(2, 'medico2@saludusm.cl'),
(3, 'medico3@saludusm.cl'),
(4, 'medico4@saludusm.cl'),
(5, 'medico5@saludusm.cl'),
(6, 'medico6@saludusm.cl'),
(7, 'medico7@saludusm.cl'),
(8, 'medico8@saludusm.cl'),
(9, 'medico9@saludusm.cl');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico_centro`
--

CREATE TABLE `medico_centro` (
  `id_medico` int(11) NOT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medico_centro`
--

INSERT INTO `medico_centro` (`id_medico`, `id_centro`) VALUES
(1, 4),
(2, 1),
(2, 3),
(3, 2),
(3, 3),
(4, 4),
(5, 1),
(5, 3),
(6, 1),
(7, 1),
(7, 2),
(8, 1),
(8, 4),
(9, 2),
(10, 1),
(10, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico_especialidad`
--

CREATE TABLE `medico_especialidad` (
  `id_medico` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medico_especialidad`
--

INSERT INTO `medico_especialidad` (`id_medico`, `id_especialidad`) VALUES
(1, 2),
(1, 7),
(2, 1),
(2, 5),
(2, 8),
(3, 2),
(3, 7),
(3, 8),
(4, 5),
(5, 4),
(6, 2),
(6, 4),
(6, 5),
(7, 3),
(7, 7),
(7, 8),
(8, 3),
(8, 4),
(8, 8),
(9, 2),
(9, 4),
(9, 7),
(10, 3),
(10, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `id_prevision` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `email`, `id_prevision`) VALUES
(1, 'paciente1@gmail.com', 3),
(2, 'paciente2@gmail.com', 1),
(3, 'paciente3@gmail.com', 3),
(4, 'paciente4@gmail.com', 1),
(5, 'paciente5@gmail.com', 3),
(6, 'paciente6@gmail.com', 3),
(7, 'paciente7@gmail.com', 3),
(8, 'paciente8@gmail.com', 3),
(9, 'paciente9@gmail.com', 2),
(10, 'paciente10@gmail.com', 1),
(11, 'paciente11@gmail.com', 2),
(12, 'paciente12@gmail.com', 3),
(13, 'paciente13@gmail.com', 3),
(14, 'paciente14@gmail.com', 1),
(15, 'paciente15@gmail.com', 3),
(16, 'paciente16@gmail.com', 3),
(17, 'paciente17@gmail.com', 3),
(18, 'paciente18@gmail.com', 2),
(19, 'paciente19@gmail.com', 3),
(20, 'paciente20@gmail.com', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevision`
--

CREATE TABLE `prevision` (
  `id_prevision` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prevision`
--

INSERT INTO `prevision` (`id_prevision`, `nombre`) VALUES
(1, 'Fonasa'),
(2, 'Isapre'),
(4, 'Otra'),
(3, 'Particular');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_linea`
--

CREATE TABLE `receta_linea` (
  `id_receta_linea` int(11) NOT NULL,
  `id_atencion` int(11) NOT NULL,
  `id_medicamento` int(11) NOT NULL,
  `dosis` varchar(120) NOT NULL,
  `dias_tratamiento` int(11) NOT NULL
) ;

--
-- Volcado de datos para la tabla `receta_linea`
--

INSERT INTO `receta_linea` (`id_receta_linea`, `id_atencion`, `id_medicamento`, `dosis`, `dias_tratamiento`) VALUES
(1, 1, 5, '1 comprimido cada 8 horas', 7),
(2, 2, 2, '1 comprimido cada 8 horas', 5),
(3, 3, 3, '1 comprimido cada 8 horas', 6),
(4, 4, 9, '1 comprimido cada 8 horas', 7),
(5, 5, 10, '1 comprimido cada 8 horas', 6),
(6, 6, 2, '1 comprimido cada 8 horas', 5),
(7, 7, 5, '1 comprimido cada 8 horas', 7),
(8, 8, 2, '1 comprimido cada 8 horas', 6),
(9, 9, 1, '1 comprimido cada 8 horas', 5),
(10, 10, 13, '1 comprimido cada 8 horas', 4),
(11, 11, 9, '1 comprimido cada 8 horas', 7),
(12, 12, 10, '1 comprimido cada 8 horas', 5),
(13, 13, 7, '1 comprimido cada 8 horas', 4),
(14, 14, 7, '1 comprimido cada 8 horas', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `email` varchar(150) NOT NULL,
  `rut` varchar(12) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('F','M','X') DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('PACIENTE','MEDICO','ADMIN') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`email`, `rut`, `nombre`, `apellido`, `telefono`, `fecha_nacimiento`, `sexo`, `password_hash`, `rol`, `activo`, `fecha_registro`) VALUES
('admin@saludusm.cl', '11111111-1', 'Admin', 'SaludUSM', '912345678', '1985-05-15', 'M', '$2y$10$058EITJjuxmyG5kWKA8JkupH4f1bJsOsgwq7wHQmwWeJUJN60EVWq', 'ADMIN', 1, '2026-10-11 21:05:17'),
('medico1@saludusm.cl', '15000001-7', 'Pedro', 'Rojas', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico10@saludusm.cl', '15000010-6', 'Camila', 'Pérez', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico2@saludusm.cl', '15000002-5', 'Ana', 'Silva', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico3@saludusm.cl', '15000003-3', 'Fernanda', 'Tapia', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico4@saludusm.cl', '15000004-1', 'Valentina', 'Rojas', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico5@saludusm.cl', '15000005-K', 'Juan', 'Araya', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico6@saludusm.cl', '15000006-8', 'Carlos', 'Araya', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico7@saludusm.cl', '15000007-6', 'Lucas', 'Pérez', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico8@saludusm.cl', '15000008-4', 'Ana', 'Rojas', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('medico9@saludusm.cl', '15000009-2', 'Fernanda', 'Araya', '987654321', '1980-08-20', 'F', '$2y$10$0JOQ7S3HDLJZEFJ2eh.ZSu5.8hVfATLNbnszEuku/cjMPmi1G9l6m', 'MEDICO', 1, '2026-10-11 21:05:17'),
('paciente1@gmail.com', '20000001-3', 'Diego', 'Rojas', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente10@gmail.com', '20000010-2', 'Juan', 'Rojas', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente11@gmail.com', '20000011-0', 'Juan', 'Sepúlveda', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente12@gmail.com', '20000012-9', 'Lucas', 'Tapia', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente13@gmail.com', '20000013-7', 'Carlos', 'Sepúlveda', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente14@gmail.com', '20000014-5', 'Juan', 'Sepúlveda', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente15@gmail.com', '20000015-3', 'Carlos', 'Pérez', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente16@gmail.com', '20000016-1', 'Lucas', 'Araya', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente17@gmail.com', '20000017-K', 'Valentina', 'Rojas', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente18@gmail.com', '20000018-8', 'Juan', 'Sepúlveda', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente19@gmail.com', '20000019-6', 'Carlos', 'Morales', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente2@gmail.com', '20000002-1', 'Javier', 'Araya', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente20@gmail.com', '20000020-K', 'Lucas', 'Pérez', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente3@gmail.com', '20000003-K', 'Camila', 'Rojas', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente4@gmail.com', '20000004-8', 'Juan', 'Sepúlveda', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente5@gmail.com', '20000005-6', 'Valentina', 'González', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente6@gmail.com', '20000006-4', 'Lucas', 'Silva', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente7@gmail.com', '20000007-2', 'Pedro', 'Soto', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente8@gmail.com', '20000008-0', 'Juan', 'Pérez', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17'),
('paciente9@gmail.com', '20000009-9', 'Sofía', 'González', '911223344', '1995-03-10', 'X', '$2y$10$ZzZiXOAXtKFCkkhmaytsvO7TseVZVjNPSJOqRzyxq6kbWHBYMqrSi', 'PACIENTE', 1, '2026-10-11 21:05:17');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_citas_detalle`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_citas_detalle` (
`id_cita` int(11)
,`fecha_hora` datetime
,`id_paciente` int(11)
,`paciente` varchar(161)
,`rut_paciente` varchar(12)
,`id_medico` int(11)
,`medico` varchar(161)
,`rut_medico` varchar(12)
,`especialidad` varchar(100)
,`codigo_centro` varchar(20)
,`centro` varchar(120)
,`comuna` varchar(80)
,`region` varchar(80)
,`prevision` varchar(80)
,`estado` varchar(30)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_citas_detalle`
--
DROP TABLE IF EXISTS `vw_citas_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_citas_detalle`  AS SELECT `c`.`id_cita` AS `id_cita`, `c`.`fecha_hora` AS `fecha_hora`, `c`.`id_paciente` AS `id_paciente`, concat(`up`.`nombre`,' ',`up`.`apellido`) AS `paciente`, `up`.`rut` AS `rut_paciente`, `c`.`id_medico` AS `id_medico`, concat(`um`.`nombre`,' ',`um`.`apellido`) AS `medico`, `um`.`rut` AS `rut_medico`, `e`.`nombre` AS `especialidad`, `ce`.`codigo` AS `codigo_centro`, `ce`.`nombre` AS `centro`, `ce`.`comuna` AS `comuna`, `ce`.`region` AS `region`, `p`.`nombre` AS `prevision`, `ec`.`nombre` AS `estado` FROM ((((((((`cita` `c` join `paciente` `pa` on(`pa`.`id_paciente` = `c`.`id_paciente`)) join `usuario` `up` on(`up`.`email` = `pa`.`email`)) join `medico` `me` on(`me`.`id_medico` = `c`.`id_medico`)) join `usuario` `um` on(`um`.`email` = `me`.`email`)) join `especialidad` `e` on(`e`.`id_especialidad` = `c`.`id_especialidad`)) join `centro_medico` `ce` on(`ce`.`id_centro` = `c`.`id_centro`)) join `prevision` `p` on(`p`.`id_prevision` = `pa`.`id_prevision`)) join `estado_cita` `ec` on(`ec`.`id_estado` = `c`.`id_estado`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atencion`
--
ALTER TABLE `atencion`
  ADD PRIMARY KEY (`id_atencion`),
  ADD UNIQUE KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `centro_medico`
--
ALTER TABLE `centro_medico`
  ADD PRIMARY KEY (`id_centro`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id_cita`),
  ADD UNIQUE KEY `uq_medico_fecha` (`id_medico`,`fecha_hora`),
  ADD UNIQUE KEY `uq_paciente_fecha` (`id_paciente`,`fecha_hora`),
  ADD KEY `id_especialidad` (`id_especialidad`),
  ADD KEY `id_centro` (`id_centro`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD PRIMARY KEY (`id_diagnostico`),
  ADD UNIQUE KEY `codigo_cie10` (`codigo_cie10`);

--
-- Indices de la tabla `diagnostico_atencion`
--
ALTER TABLE `diagnostico_atencion`
  ADD PRIMARY KEY (`id_atencion`,`id_diagnostico`),
  ADD KEY `id_diagnostico` (`id_diagnostico`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  ADD PRIMARY KEY (`id_medicamento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `medico`
--
ALTER TABLE `medico`
  ADD PRIMARY KEY (`id_medico`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `medico_centro`
--
ALTER TABLE `medico_centro`
  ADD PRIMARY KEY (`id_medico`,`id_centro`),
  ADD KEY `id_centro` (`id_centro`);

--
-- Indices de la tabla `medico_especialidad`
--
ALTER TABLE `medico_especialidad`
  ADD PRIMARY KEY (`id_medico`,`id_especialidad`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_paciente_prevision` (`id_prevision`);

--
-- Indices de la tabla `prevision`
--
ALTER TABLE `prevision`
  ADD PRIMARY KEY (`id_prevision`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `receta_linea`
--
ALTER TABLE `receta_linea`
  ADD PRIMARY KEY (`id_receta_linea`),
  ADD KEY `id_atencion` (`id_atencion`),
  ADD KEY `id_medicamento` (`id_medicamento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `rut` (`rut`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atencion`
--
ALTER TABLE `atencion`
  MODIFY `id_atencion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `centro_medico`
--
ALTER TABLE `centro_medico`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  MODIFY `id_diagnostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  MODIFY `id_medicamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `medico`
--
ALTER TABLE `medico`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `prevision`
--
ALTER TABLE `prevision`
  MODIFY `id_prevision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `receta_linea`
--
ALTER TABLE `receta_linea`
  MODIFY `id_receta_linea` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atencion`
--
ALTER TABLE `atencion`
  ADD CONSTRAINT `atencion_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id_cita`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE,
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`),
  ADD CONSTRAINT `cita_ibfk_3` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`),
  ADD CONSTRAINT `cita_ibfk_4` FOREIGN KEY (`id_centro`) REFERENCES `centro_medico` (`id_centro`),
  ADD CONSTRAINT `cita_ibfk_5` FOREIGN KEY (`id_estado`) REFERENCES `estado_cita` (`id_estado`);

--
-- Filtros para la tabla `diagnostico_atencion`
--
ALTER TABLE `diagnostico_atencion`
  ADD CONSTRAINT `diagnostico_atencion_ibfk_1` FOREIGN KEY (`id_atencion`) REFERENCES `atencion` (`id_atencion`) ON DELETE CASCADE,
  ADD CONSTRAINT `diagnostico_atencion_ibfk_2` FOREIGN KEY (`id_diagnostico`) REFERENCES `diagnostico` (`id_diagnostico`);

--
-- Filtros para la tabla `medico`
--
ALTER TABLE `medico`
  ADD CONSTRAINT `fk_medico_usuario` FOREIGN KEY (`email`) REFERENCES `usuario` (`email`) ON DELETE CASCADE;

--
-- Filtros para la tabla `medico_centro`
--
ALTER TABLE `medico_centro`
  ADD CONSTRAINT `medico_centro_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`) ON DELETE CASCADE,
  ADD CONSTRAINT `medico_centro_ibfk_2` FOREIGN KEY (`id_centro`) REFERENCES `centro_medico` (`id_centro`);

--
-- Filtros para la tabla `medico_especialidad`
--
ALTER TABLE `medico_especialidad`
  ADD CONSTRAINT `medico_especialidad_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`) ON DELETE CASCADE,
  ADD CONSTRAINT `medico_especialidad_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `fk_paciente_prevision` FOREIGN KEY (`id_prevision`) REFERENCES `prevision` (`id_prevision`),
  ADD CONSTRAINT `fk_paciente_usuario` FOREIGN KEY (`email`) REFERENCES `usuario` (`email`) ON DELETE CASCADE;

--
-- Filtros para la tabla `receta_linea`
--
ALTER TABLE `receta_linea`
  ADD CONSTRAINT `receta_linea_ibfk_1` FOREIGN KEY (`id_atencion`) REFERENCES `atencion` (`id_atencion`) ON DELETE CASCADE,
  ADD CONSTRAINT `receta_linea_ibfk_2` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id_medicamento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
