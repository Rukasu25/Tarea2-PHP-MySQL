DROP DATABASE IF EXISTS saludusm;
CREATE DATABASE saludusm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saludusm;

-- ============================================================
-- CATÁLOGOS
-- ============================================================

CREATE TABLE prevision (
    id_prevision INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE especialidad (
    id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE estado_cita (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE centro_medico (
    id_centro INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(120) NOT NULL,
    comuna VARCHAR(80) NOT NULL,
    region VARCHAR(80) NOT NULL
);

-- ============================================================
-- USUARIO (PK = email)
-- ============================================================

CREATE TABLE usuario (
    email VARCHAR(150) PRIMARY KEY,
    rut VARCHAR(12) NOT NULL UNIQUE,
    nombre VARCHAR(80) NOT NULL,
    apellido VARCHAR(80) NOT NULL,
    telefono VARCHAR(30),
    fecha_nacimiento DATE,
    sexo ENUM('F','M','X') NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('PACIENTE','MEDICO','ADMIN') NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- PACIENTE Y MEDICO
-- ============================================================

CREATE TABLE paciente (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    id_prevision INT NOT NULL,
    CONSTRAINT fk_paciente_usuario FOREIGN KEY (email)
        REFERENCES usuario(email) ON DELETE CASCADE,
    CONSTRAINT fk_paciente_prevision FOREIGN KEY (id_prevision)
        REFERENCES prevision(id_prevision)
);

CREATE TABLE medico (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    CONSTRAINT fk_medico_usuario FOREIGN KEY (email)
        REFERENCES usuario(email) ON DELETE CASCADE
);

-- ============================================================
-- RELACIONES M:N
-- ============================================================

CREATE TABLE medico_especialidad (
    id_medico INT NOT NULL,
    id_especialidad INT NOT NULL,
    PRIMARY KEY (id_medico, id_especialidad),
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico) ON DELETE CASCADE,
    FOREIGN KEY (id_especialidad) REFERENCES especialidad(id_especialidad) ON DELETE RESTRICT
);

CREATE TABLE medico_centro (
    id_medico INT NOT NULL,
    id_centro INT NOT NULL,
    PRIMARY KEY (id_medico, id_centro),
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico) ON DELETE CASCADE,
    FOREIGN KEY (id_centro) REFERENCES centro_medico(id_centro) ON DELETE RESTRICT
);

-- ============================================================
-- CITA
-- ============================================================

CREATE TABLE cita (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_medico INT NOT NULL,
    id_especialidad INT NOT NULL,
    id_centro INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    id_estado INT NOT NULL,
    creada_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_medico_fecha (id_medico, fecha_hora),
    UNIQUE KEY uq_paciente_fecha (id_paciente, fecha_hora),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente) ON DELETE CASCADE,
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico) ON DELETE RESTRICT,
    FOREIGN KEY (id_especialidad) REFERENCES especialidad(id_especialidad) ON DELETE RESTRICT,
    FOREIGN KEY (id_centro) REFERENCES centro_medico(id_centro) ON DELETE RESTRICT,
    FOREIGN KEY (id_estado) REFERENCES estado_cita(id_estado) ON DELETE RESTRICT
);

-- ============================================================
-- ATENCIÓN Y DIAGNÓSTICOS
-- ============================================================

CREATE TABLE diagnostico (
    id_diagnostico INT AUTO_INCREMENT PRIMARY KEY,
    codigo_cie10 VARCHAR(20) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NOT NULL
);

CREATE TABLE atencion (
    id_atencion INT AUTO_INCREMENT PRIMARY KEY,
    id_cita INT NOT NULL UNIQUE,
    motivo VARCHAR(500) NOT NULL,
    observaciones TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cita) REFERENCES cita(id_cita) ON DELETE CASCADE
);

CREATE TABLE diagnostico_atencion (
    id_atencion INT NOT NULL,
    id_diagnostico INT NOT NULL,
    PRIMARY KEY (id_atencion, id_diagnostico),
    FOREIGN KEY (id_atencion) REFERENCES atencion(id_atencion) ON DELETE CASCADE,
    FOREIGN KEY (id_diagnostico) REFERENCES diagnostico(id_diagnostico) ON DELETE RESTRICT
);

-- ============================================================
-- MEDICAMENTO Y RECETA
-- ============================================================

CREATE TABLE medicamento (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE receta_linea (
    id_receta_linea INT AUTO_INCREMENT PRIMARY KEY,
    id_atencion INT NOT NULL,
    id_medicamento INT NOT NULL,
    dosis VARCHAR(120) NOT NULL,
    dias_tratamiento INT NOT NULL,
    FOREIGN KEY (id_atencion) REFERENCES atencion(id_atencion) ON DELETE CASCADE,
    FOREIGN KEY (id_medicamento) REFERENCES medicamento(id_medicamento) ON DELETE RESTRICT,
    CHECK (dias_tratamiento > 0)
);

-- ============================================================
-- PROCEDIMIENTO, FUNCIÓN Y TRIGGER
-- ============================================================

DELIMITER //

CREATE PROCEDURE sp_cancelar_citas_vencidas()
BEGIN
    UPDATE cita c
    JOIN estado_cita e ON e.id_estado = c.id_estado
    SET c.id_estado = (SELECT id_estado FROM estado_cita WHERE nombre='Cancelada')
    WHERE e.nombre IN ('Reservada','Confirmada')
      AND c.fecha_hora < NOW();
END//

CREATE FUNCTION fn_edad(fecha_nac DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    RETURN TIMESTAMPDIFF(YEAR, fecha_nac, CURDATE());
END//

CREATE TRIGGER trg_cita_validar
BEFORE INSERT ON cita
FOR EACH ROW
BEGIN
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

    IF NEW.fecha_hora <= NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La cita debe quedar en una fecha futura';
    END IF;
END//

DELIMITER ;

-- ============================================================
-- VIEW
-- ============================================================

CREATE VIEW vw_citas_detalle AS
SELECT
    c.id_cita,
    c.fecha_hora,
    c.id_paciente,
    CONCAT(up.nombre,' ',up.apellido) AS paciente,
    up.rut AS rut_paciente,
    c.id_medico,
    CONCAT(um.nombre,' ',um.apellido) AS medico,
    um.rut AS rut_medico,
    e.nombre AS especialidad,
    ce.codigo AS codigo_centro,
    ce.nombre AS centro,
    ce.comuna,
    ce.region,
    p.nombre AS prevision,
    ec.nombre AS estado
FROM cita c
JOIN paciente pa ON pa.id_paciente = c.id_paciente
JOIN usuario up ON up.email = pa.email
JOIN medico me ON me.id_medico = c.id_medico
JOIN usuario um ON um.email = me.email
JOIN especialidad e ON e.id_especialidad = c.id_especialidad
JOIN centro_medico ce ON ce.id_centro = c.id_centro
JOIN prevision p ON p.id_prevision = pa.id_prevision
JOIN estado_cita ec ON ec.id_estado = c.id_estado;

-- ============================================================
-- DATOS DE PRUEBA (CATÁLOGOS)
-- ============================================================

INSERT INTO prevision(nombre) VALUES
('Fonasa'),('Isapre'),('Particular'),('Otra');

INSERT INTO especialidad(nombre) VALUES
('Medicina General'),('Cardiologia'),('Pediatria'),('Dermatologia'),
('Traumatologia'),('Neurologia'),('Oftalmologia'),('Ginecologia');

INSERT INTO estado_cita(nombre) VALUES
('Reservada'),('Confirmada'),('Atendida'),('No Asistio'),('Cancelada');

INSERT INTO centro_medico(codigo,nombre,comuna,region) VALUES
('C001','Centro SaludUSM Casa Central','Valparaiso','Valparaiso'),
('C002','Centro SaludUSM San Joaquin','San Joaquin','Metropolitana'),
('C003','Centro SaludUSM Maipu','Maipu','Metropolitana'),
('C004','Centro SaludUSM Concepcion','Concepcion','Biobio');

INSERT INTO diagnostico(codigo_cie10,descripcion) VALUES
('J00','Rinofaringitis aguda'),('J06.9','Infeccion respiratoria aguda'),
('I10','Hipertension esencial'),('M54.5','Dolor lumbar'),
('L20.9','Dermatitis atopica'),('R51','Cefalea'),
('K21.9','Reflujo gastroesofagico'),('E11','Diabetes mellitus tipo 2'),
('J45','Asma'),('F41.9','Trastorno de ansiedad'),
('M23.2','Desgarro de menisco'),('H66.9','Otitis media');

INSERT INTO medicamento(nombre) VALUES
('Paracetamol'),('Ibuprofeno'),('Amoxicilina'),('Loratadina'),
('Omeprazol'),('Diclofenaco'),('Losartan'),('Metformina'),
('Salbutamol'),('Sertralina'),('Enalapril'),('Aspirina'),
('Prednisona'),('Diazepam'),('Clobetasol');