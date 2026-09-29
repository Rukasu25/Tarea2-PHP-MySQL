
DROP DATABASE IF EXISTS SALUD_USM;

CREATE DATABASE SALUD_USM;
USE SALUD_USM;

CREATE TABLE REGION (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE COMUNA (
    id_comuna INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    CONSTRAINT FK_COMUNA_REGION FOREIGN KEY (id_region) REFERENCES REGION(id_region),
    CONSTRAINT UQ_COMUNA_REGION UNIQUE (id_region, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PREVISION (
    id_prevision INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ESPECIALIDAD (
    id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ESTADO_CITA (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(15) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE USUARIO (
    email VARCHAR(100) PRIMARY KEY,
    rol ENUM('paciente', 'medico', 'admin') NOT NULL,
    rut VARCHAR(12) NOT NULL UNIQUE,
    nombre_completo VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE CENTRO_MEDICO (
    codigo_interno VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_comuna INT NOT NULL,
    direccion VARCHAR(150) NULL,
    CONSTRAINT FK_CENTRO_COMUNA FOREIGN KEY (id_comuna) REFERENCES COMUNA(id_comuna)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PACIENTE (
    rut VARCHAR(12) PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    sexo CHAR(1) NOT NULL,
    telefono VARCHAR(15) NULL,
    id_comuna_residencia INT NOT NULL,
    id_prevision INT NOT NULL,
    CONSTRAINT FK_PACIENTE_COMUNA FOREIGN KEY (id_comuna_residencia) REFERENCES COMUNA(id_comuna),
    CONSTRAINT FK_PACIENTE_PREVISION FOREIGN KEY (id_prevision) REFERENCES PREVISION(id_prevision),
    CONSTRAINT CHK_PACIENTE_SEXO CHECK (sexo IN ('M', 'F', 'O'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE MEDICO (
    rut VARCHAR(12) PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE MEDICO_ESPECIALIDAD (
    rut_medico VARCHAR(12) NOT NULL,
    id_especialidad INT NOT NULL,
    PRIMARY KEY (rut_medico, id_especialidad),
    CONSTRAINT FK_MED_ESP_MEDICO FOREIGN KEY (rut_medico) 
        REFERENCES MEDICO(rut) ON DELETE CASCADE,
    CONSTRAINT FK_MED_ESP_ESPECIALIDAD FOREIGN KEY (id_especialidad) 
        REFERENCES ESPECIALIDAD(id_especialidad) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE MEDICO_CENTRO (
    rut_medico VARCHAR(12) NOT NULL,
    codigo_centro VARCHAR(20) NOT NULL,
    PRIMARY KEY (rut_medico, codigo_centro),
    CONSTRAINT FK_MED_CEN_MEDICO FOREIGN KEY (rut_medico) 
        REFERENCES MEDICO(rut) ON DELETE CASCADE,
    CONSTRAINT FK_MED_CEN_CENTRO FOREIGN KEY (codigo_centro) 
        REFERENCES CENTRO_MEDICO(codigo_interno) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE CITA (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    fecha_hora DATETIME NOT NULL,
    rut_paciente VARCHAR(12) NOT NULL,
    rut_medico VARCHAR(12) NOT NULL,
    codigo_centro VARCHAR(20) NOT NULL,
    id_especialidad INT NOT NULL,
    id_estado INT NOT NULL,
    
    CONSTRAINT FK_CITA_PACIENTE FOREIGN KEY (rut_paciente) 
        REFERENCES PACIENTE(rut) ON DELETE CASCADE,
    CONSTRAINT FK_CITA_MEDICO FOREIGN KEY (rut_medico) 
        REFERENCES MEDICO(rut) ON DELETE CASCADE,
    CONSTRAINT FK_CITA_CENTRO FOREIGN KEY (codigo_centro) 
        REFERENCES CENTRO_MEDICO(codigo_interno),
    CONSTRAINT FK_CITA_ESPECIALIDAD FOREIGN KEY (id_especialidad) 
        REFERENCES ESPECIALIDAD(id_especialidad),
    CONSTRAINT FK_CITA_ESTADO FOREIGN KEY (id_estado) 
        REFERENCES ESTADO_CITA(id_estado),
    
    CONSTRAINT UQ_CITA_MEDICO_CENTRO_FECHA UNIQUE (rut_medico, codigo_centro, fecha_hora),
    CONSTRAINT UQ_CITA_PACIENTE_FECHA UNIQUE (rut_paciente, fecha_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE ATENCION (
    id_atencion INT AUTO_INCREMENT PRIMARY KEY,
    id_cita INT NOT NULL UNIQUE,
    motivo_consulta TEXT NOT NULL,
    observaciones TEXT NULL,
    CONSTRAINT FK_ATENCION_CITA FOREIGN KEY (id_cita) 
        REFERENCES CITA(id_cita) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE DIAGNOSTICO (
    id_diagnostico INT AUTO_INCREMENT PRIMARY KEY,
    id_atencion INT NOT NULL,
    codigo_cie10 VARCHAR(10) NOT NULL,
    descripcion TEXT NOT NULL,
    CONSTRAINT FK_DIAGNOSTICO_ATENCION FOREIGN KEY (id_atencion) 
        REFERENCES ATENCION(id_atencion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE RECETA (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_atencion INT NOT NULL,
    medicamento VARCHAR(100) NOT NULL,
    dosis TEXT NOT NULL,
    dias_tratamiento INT NOT NULL,
    CONSTRAINT FK_RECETA_ATENCION FOREIGN KEY (id_atencion) 
        REFERENCES ATENCION(id_atencion) ON DELETE CASCADE,
    CONSTRAINT CHK_RECETA_DIAS CHECK (dias_tratamiento > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO REGION (nombre) VALUES 
('Región Metropolitana'),
('Valparaíso'),
('Biobío'),
('Maule'),
('La Araucanía');


INSERT INTO COMUNA (id_region, nombre) VALUES 
(1, 'Santiago'),
(1, 'Providencia'),
(1, 'Las Condes'),
(1, 'Ñuñoa'),
(2, 'Viña del Mar'),
(2, 'Valparaíso'),
(2, 'Quilpué'),
(3, 'Concepción'),
(3, 'Talcahuano'),
(3, 'Chiguayante'),
(4, 'Talca'),
(4, 'Curicó'),
(5, 'Temuco'),
(5, 'Padre Las Casas');

INSERT INTO PREVISION (nombre) VALUES 
('Fonasa'),
('Isapre'),
('Particular');

INSERT INTO ESPECIALIDAD (nombre) VALUES 
('Cardiología'),
('Pediatría'),
('Traumatología'),
('Medicina General'),
('Dermatología'),
('Neurología'),
('Oftalmología'),
('Ginecología');

INSERT INTO ESTADO_CITA (nombre) VALUES 
('Reservada'),
('Confirmada'),
('Atendida'),
('No Asistió'),
('Cancelada');