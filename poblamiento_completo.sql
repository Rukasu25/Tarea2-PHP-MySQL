-- ============================================================
-- DATOS DE PRUEBA - SALUDUSM
-- Generado automáticamente
-- Contraseña para TODOS: Password123!
-- ============================================================

USE saludusm;

-- ============================================================
-- 1. CATÁLOGOS
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
('J00','Rinofaringitis aguda'),
('J06.9','Infeccion respiratoria aguda'),
('I10','Hipertension esencial'),
('M54.5','Dolor lumbar'),
('L20.9','Dermatitis atopica'),
('R51','Cefalea'),
('K21.9','Reflujo gastroesofagico'),
('E11','Diabetes mellitus tipo 2'),
('J45','Asma'),
('F41.9','Trastorno de ansiedad'),
('M23.2','Desgarro de menisco'),
('H66.9','Otitis media');

INSERT INTO medicamento(nombre) VALUES
('Paracetamol'),
('Ibuprofeno'),
('Amoxicilina'),
('Loratadina'),
('Omeprazol'),
('Diclofenaco'),
('Losartan'),
('Metformina'),
('Salbutamol'),
('Sertralina'),
('Enalapril'),
('Aspirina'),
('Prednisona'),
('Diazepam'),
('Clobetasol');

-- ============================================================
-- 2. USUARIOS (contraseña: Password123!)
-- ============================================================

-- Admins
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('hector.tapia88668@saludusm.cl','20330986-7','Hector','Tapia','975800103','1956-03-29','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','ADMIN');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('patricia.castro87097@saludusm.cl','16047297-8','Patricia','Castro','915288320','1994-01-09','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','ADMIN');

-- Médicos
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('roberto.morales70432@saludusm.cl','15902106-2','Roberto','Morales','935782325','2004-05-16','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ricardo.espinoza69621@saludusm.cl','23728735-6','Ricardo','Espinoza','993803324','1964-08-11','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('constanza.fuentes31978@saludusm.cl','12472883-1','Constanza','Fuentes','996897731','2000-11-06','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('martina.soto88829@saludusm.cl','6022000-8','Martina','Soto','964061211','1947-07-25','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('pablo.silva66247@saludusm.cl','22930610-7','Pablo','Silva','930471142','2001-07-29','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('javiera.lopez30555@saludusm.cl','17851507-1','Javiera','Lopez','964667470','1968-05-11','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('luis.diaz60532@saludusm.cl','18703044-6','Luis','Diaz','992341771','2007-05-02','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('valentina.castro29578@saludusm.cl','24944977-7','Valentina','Castro','941352276','1975-02-04','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','MEDICO');

-- Pacientes
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('felipe.espinoza49929@correo.cl','15582840-8','Felipe','Espinoza','997376311','1953-09-21','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('carlos.lopez83373@correo.cl','16871785-6','Carlos','Lopez','977489712','1966-03-14','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('maria.flores78790@correo.cl','6522511-6','Maria','Flores','977031123','1975-02-28','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('diego.silva92353@correo.cl','23655634-7','Diego','Silva','978753579','1954-10-02','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('cristobal.alvarez13891@correo.cl','9419698-1','Cristobal','Alvarez','965140948','1965-02-09','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('eduardo.reyes22213@correo.cl','7134666-4','Eduardo','Reyes','996096099','1996-03-09','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('constanza.lopez68334@correo.cl','8562785-9','Constanza','Lopez','928052592','1992-02-01','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('roberto.rojas79737@correo.cl','15348293-K','Roberto','Rojas','910871686','1952-01-23','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('pedro.silva8874@correo.cl','21528191-0','Pedro','Silva','940610636','1972-07-07','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('catalina.rodriguez18110@correo.cl','12342213-7','Catalina','Rodriguez','991517511','1949-09-16','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('eduardo.castro59322@correo.cl','22755587-9','Eduardo','Castro','923869638','2005-02-03','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('claudia.lopez73036@correo.cl','8240529-9','Claudia','Lopez','933770210','1946-02-03','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('mauricio.rojas72067@correo.cl','8506881-9','Mauricio','Rojas','964949777','1990-03-30','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('catalina.torres15756@correo.cl','18144817-8','Catalina','Torres','923244416','1987-08-02','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('mauricio.contreras42101@correo.cl','13501020-K','Mauricio','Contreras','910238386','1978-08-26','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('jorge.perez40222@correo.cl','21412817-5','Jorge','Perez','974086512','1986-09-11','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('carlos.gonzalez94765@correo.cl','15352345-9','Carlos','Gonzalez','992463794','1961-04-10','M','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('camila.silva27526@correo.cl','17163872-0','Camila','Silva','966408157','1985-07-06','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('claudia.martinez27596@correo.cl','12364752-K','Claudia','Martinez','978397110','1955-08-24','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('carolina.valenzuela42972@correo.cl','14172257-2','Carolina','Valenzuela','986793901','1947-05-18','F','$2y$10$8i2/b8KNBHKbNeIG7vO9..8zc8JlyOVjfYzg20WtBcv6kNNUh1OfO','PACIENTE');

-- ============================================================
-- 3. REGISTROS EN TABLAS PACIENTE Y MEDICO
-- ============================================================

-- Insertar en tabla paciente
INSERT INTO paciente(email, id_prevision) VALUES ('felipe.espinoza49929@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('carlos.lopez83373@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('maria.flores78790@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('diego.silva92353@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('cristobal.alvarez13891@correo.cl', 2);
INSERT INTO paciente(email, id_prevision) VALUES ('eduardo.reyes22213@correo.cl', 2);
INSERT INTO paciente(email, id_prevision) VALUES ('constanza.lopez68334@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('roberto.rojas79737@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('pedro.silva8874@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('catalina.rodriguez18110@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('eduardo.castro59322@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('claudia.lopez73036@correo.cl', 2);
INSERT INTO paciente(email, id_prevision) VALUES ('mauricio.rojas72067@correo.cl', 2);
INSERT INTO paciente(email, id_prevision) VALUES ('catalina.torres15756@correo.cl', 2);
INSERT INTO paciente(email, id_prevision) VALUES ('mauricio.contreras42101@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('jorge.perez40222@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('carlos.gonzalez94765@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('camila.silva27526@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('claudia.martinez27596@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('carolina.valenzuela42972@correo.cl', 4);

-- Insertar en tabla medico
INSERT INTO medico(email) VALUES ('roberto.morales70432@saludusm.cl');
INSERT INTO medico(email) VALUES ('ricardo.espinoza69621@saludusm.cl');
INSERT INTO medico(email) VALUES ('constanza.fuentes31978@saludusm.cl');
INSERT INTO medico(email) VALUES ('martina.soto88829@saludusm.cl');
INSERT INTO medico(email) VALUES ('pablo.silva66247@saludusm.cl');
INSERT INTO medico(email) VALUES ('javiera.lopez30555@saludusm.cl');
INSERT INTO medico(email) VALUES ('luis.diaz60532@saludusm.cl');
INSERT INTO medico(email) VALUES ('valentina.castro29578@saludusm.cl');

-- ============================================================
-- 4. ESPECIALIDADES Y CENTROS DE MEDICOS
-- ============================================================

-- NOTA: Los id_medico se asignan automáticamente.
-- Estos INSERT asumen que los médicos tienen IDs 1-8
-- Ajusta si ya tenías médicos en la BD.

-- Asignar especialidades (1-3 por médico)
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (1, 6);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (1, 2);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (2, 7);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (3, 3);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (3, 4);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (3, 5);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (4, 8);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (5, 5);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (5, 4);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (6, 4);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (6, 3);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (7, 4);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (7, 5);
INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES (8, 8);

-- Asignar centros (1-3 por médico)
INSERT INTO medico_centro(id_medico, id_centro) VALUES (1, 4);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (1, 1);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (1, 2);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (2, 1);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (2, 4);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (3, 3);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (4, 2);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (4, 1);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (4, 3);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (5, 1);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (5, 4);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (6, 4);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (6, 3);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (7, 2);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (7, 3);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (7, 1);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (8, 3);
INSERT INTO medico_centro(id_medico, id_centro) VALUES (8, 2);

-- ============================================================
-- 5. CITAS
-- ============================================================

-- id_estado: 1=Reservada, 2=Confirmada, 3=Atendida, 4=No Asistio, 5=Cancelada
-- id_paciente: 1-20  |  id_medico: 1-8

-- Citas ATENDIDAS (para historial)
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (18, 7, 8, 2, '2026-04-17 15:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (19, 2, 7, 4, '2026-06-20 10:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (5, 4, 4, 2, '2026-05-06 16:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (16, 4, 8, 2, '2026-05-26 13:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (10, 6, 3, 2, '2026-07-07 08:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (1, 4, 4, 3, '2026-07-01 10:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 2, 5, 4, '2026-08-29 16:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (19, 8, 2, 2, '2026-09-06 10:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (16, 6, 1, 4, '2026-07-11 17:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (16, 3, 4, 4, '2026-04-24 12:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (18, 3, 6, 2, '2026-08-31 15:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (18, 5, 3, 2, '2026-06-29 13:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (2, 8, 8, 3, '2026-07-15 17:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (9, 1, 3, 4, '2026-04-17 15:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 5, 1, 2, '2026-04-26 17:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (10, 5, 2, 2, '2026-05-07 09:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 7, 5, 1, '2026-07-03 12:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (3, 4, 4, 3, '2026-09-03 16:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 1, 7, 4, '2026-06-28 12:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (2, 7, 5, 3, '2026-09-05 14:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (5, 1, 4, 4, '2026-07-22 15:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (20, 1, 8, 3, '2026-08-16 08:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (8, 2, 6, 1, '2026-05-23 11:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (9, 2, 4, 1, '2026-07-26 08:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (9, 6, 1, 2, '2026-06-10 12:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (11, 5, 3, 1, '2026-06-27 15:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (8, 5, 3, 4, '2026-07-22 17:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (2, 7, 6, 3, '2026-08-02 16:30:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (15, 8, 3, 4, '2026-04-17 17:00:00', 3);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (5, 4, 8, 3, '2026-05-23 08:30:00', 3);

-- Citas RESERVADAS (futuras)
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (9, 8, 2, 2, '2026-11-28 14:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (14, 2, 4, 4, '2026-12-06 08:30:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 6, 5, 1, '2026-12-04 08:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 4, 4, 3, '2026-10-12 17:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (3, 1, 3, 4, '2026-11-27 13:30:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 1, 8, 4, '2026-10-21 16:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (19, 5, 7, 1, '2026-11-19 12:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (4, 2, 7, 4, '2026-11-26 17:30:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (19, 4, 2, 2, '2026-11-02 11:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (8, 1, 1, 3, '2026-11-03 10:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (5, 7, 1, 1, '2026-10-23 14:30:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 7, 7, 4, '2026-11-11 10:30:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (16, 2, 2, 1, '2026-10-31 11:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (11, 1, 6, 2, '2026-10-14 08:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 6, 4, 2, '2026-11-05 13:00:00', 1);

-- Citas CONFIRMADAS (futuras)
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (4, 8, 2, 1, '2026-10-24 15:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (3, 1, 8, 4, '2026-10-17 14:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 8, 4, 3, '2026-10-27 17:00:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (3, 1, 7, 2, '2026-10-19 12:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (5, 5, 2, 2, '2026-11-03 10:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (10, 8, 2, 3, '2026-10-26 16:00:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (8, 2, 7, 3, '2026-10-13 17:00:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (3, 5, 8, 1, '2026-10-13 14:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (4, 8, 2, 3, '2026-10-12 11:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (8, 7, 5, 4, '2026-10-30 10:30:00', 2);

-- Citas NO ASISTIO
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 2, 8, 3, '2026-09-03 14:00:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (3, 4, 6, 2, '2026-08-05 11:00:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (10, 7, 8, 3, '2026-09-30 14:30:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (4, 5, 5, 2, '2026-09-04 15:30:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (15, 7, 5, 2, '2026-08-26 14:30:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (8, 3, 7, 2, '2026-08-28 12:30:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (11, 2, 2, 1, '2026-09-26 12:30:00', 4);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (20, 7, 1, 4, '2026-08-09 10:00:00', 4);

-- Citas CANCELADAS
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 6, 5, 3, '2026-09-15 08:30:00', 5);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (2, 8, 2, 2, '2026-09-28 15:30:00', 5);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (6, 4, 7, 3, '2026-09-14 14:00:00', 5);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (15, 1, 7, 2, '2026-09-17 10:30:00', 5);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (7, 7, 5, 2, '2026-10-06 11:30:00', 5);

-- Citas VENCIDAS (Reservada/Confirmada en el pasado)
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (17, 2, 3, 4, '2026-09-15 11:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (9, 3, 3, 4, '2026-09-30 12:30:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (18, 8, 6, 3, '2026-09-19 12:00:00', 2);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (11, 5, 6, 1, '2026-09-28 10:00:00', 1);
INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (15, 6, 3, 1, '2026-09-20 09:30:00', 1);

-- ============================================================
-- 6. ATENCIONES
-- ============================================================

-- NOTA: Solo para citas con estado Atendida (id_estado=3)
-- Los id_cita se asignan automáticamente.
-- Este INSERT asume que las citas atendidas tienen IDs 1-30.
-- Ajusta según tu BD.

INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (1, 'Dolor en el pecho y palpitaciones', 'Se recomienda control en 15 dias.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (2, 'Tos persistente', 'Se recomienda control en 15 dias.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (3, 'Control de nino sano', 'Paciente estable. Se ajusta medicacion.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (4, 'Control de embarazo', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (5, 'Control de salud general', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (6, 'Control de embarazo', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (7, 'Control de embarazo', 'Sin novedades. Paciente asintomatico.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (8, 'Dolor de cabeza intenso', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (9, 'Control de nino sano', 'Se solicita examen de sangre para confirmar diagnostico.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (10, 'Dolor de espalda', 'Se solicita radiografia para descartar fractura.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (11, 'Dolor abdominal', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (12, 'Vision borrosa', 'Paciente presenta sintomas leves. Se recomienda reposo.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (13, 'Fiebre y malestar general', 'Paciente estable. Se ajusta medicacion.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (14, 'Dolor abdominal', 'Paciente estable. Se ajusta medicacion.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (15, 'Dolor en rodilla', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (16, 'Control de embarazo', 'Se recomienda control en 15 dias.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (17, 'Dolor abdominal', 'Se prescribe tratamiento por 7 dias.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (18, 'Vision borrosa', 'Se recomienda control en 15 dias.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (19, 'Vision borrosa', 'Paciente presenta sintomas leves. Se recomienda reposo.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (20, 'Vision borrosa', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (21, 'Control de diabetes', 'Paciente refiere mejoria con tratamiento actual.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (22, 'Control de salud general', 'Paciente con antecedentes familiares. Seguimiento.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (23, 'Control de nino sano', 'Se solicita radiografia para descartar fractura.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (24, 'Control de embarazo', 'Paciente con antecedentes familiares. Seguimiento.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (25, 'Dolor de cabeza intenso', 'Sin novedades. Paciente asintomatico.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (26, 'Control de salud general', 'Se recomienda control en 15 dias.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (27, 'Control de salud general', 'Paciente estable. Se ajusta medicacion.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (28, 'Dolor de oido', 'Paciente con antecedentes familiares. Seguimiento.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (29, 'Control de diabetes', 'Paciente estable. Se ajusta medicacion.');
INSERT INTO atencion(id_cita, motivo, observaciones) VALUES (30, 'Control de embarazo', 'Se prescribe tratamiento por 7 dias.');

-- ============================================================
-- 7. DIAGNOSTICOS POR ATENCION
-- ============================================================

INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (1, 8);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (2, 4);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (2, 3);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (3, 9);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (3, 4);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (4, 5);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (5, 7);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (6, 1);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (7, 1);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (8, 7);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (8, 12);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (9, 1);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (10, 12);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (10, 2);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (11, 1);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (11, 5);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (12, 4);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (13, 4);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (14, 10);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (14, 9);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (15, 4);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (16, 2);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (17, 11);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (18, 12);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (19, 12);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (20, 11);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (21, 12);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (22, 8);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (22, 6);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (23, 3);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (24, 10);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (24, 6);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (25, 5);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (25, 8);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (26, 7);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (26, 9);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (27, 7);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (27, 11);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (28, 8);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (28, 2);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (29, 11);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (29, 10);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (30, 7);
INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES (30, 6);

-- ============================================================
-- 8. RECETAS
-- ============================================================

INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (1, 1, '50 mg cada 8 horas', 23);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (2, 7, '40 mg cada 12 horas', 24);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (2, 9, '1 mg cada 24 horas', 30);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (3, 5, '850 mg cada 12 horas', 13);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (4, 1, '100 mg cada 24 horas', 11);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (4, 5, '5 mg cada 8 horas', 3);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (5, 3, '250 mg cada 6 horas', 18);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (5, 7, '50 mg cada 8 horas', 26);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (6, 3, '2 puff cada 6 horas', 30);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (7, 7, '5 mg cada 8 horas', 8);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (7, 9, '20 mg cada 24 horas', 30);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (8, 12, '400 mg cada 8 horas', 5);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (9, 14, '850 mg cada 12 horas', 14);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (9, 9, '400 mg cada 8 horas', 5);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (10, 1, '500 mg cada 8 horas', 9);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (10, 11, '1 mg cada 24 horas', 23);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (11, 1, '1 mg cada 24 horas', 8);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (12, 5, '2 puff cada 6 horas', 12);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (13, 10, '100 mg cada 24 horas', 21);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (13, 7, '100 mg cada 24 horas', 15);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (14, 6, '250 mg cada 6 horas', 21);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (15, 3, '250 mg cada 6 horas', 20);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (15, 9, '40 mg cada 12 horas', 5);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (16, 2, '2 puff cada 6 horas', 9);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (17, 14, '5 mg cada 8 horas', 12);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (17, 9, '250 mg cada 6 horas', 3);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (18, 11, '850 mg cada 12 horas', 22);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (18, 15, '500 mg cada 8 horas', 30);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (19, 8, '250 mg cada 6 horas', 26);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (20, 7, '400 mg cada 8 horas', 12);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (20, 1, '40 mg cada 12 horas', 24);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (21, 11, '400 mg cada 8 horas', 18);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (21, 10, '250 mg cada 6 horas', 4);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (22, 13, '5 mg cada 8 horas', 27);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (22, 15, '50 mg cada 8 horas', 28);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (23, 2, '5 mg cada 8 horas', 9);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (23, 3, '20 mg cada 24 horas', 24);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (24, 11, '2 puff cada 6 horas', 18);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (24, 4, '50 mg cada 8 horas', 4);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (25, 8, '500 mg cada 8 horas', 12);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (26, 11, '500 mg cada 8 horas', 18);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (26, 13, '1 mg cada 24 horas', 9);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (27, 15, '400 mg cada 8 horas', 5);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (27, 3, '40 mg cada 12 horas', 17);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (28, 4, '250 mg cada 6 horas', 22);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (29, 11, '100 mg cada 24 horas', 30);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (29, 8, '850 mg cada 12 horas', 7);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (30, 10, '50 mg cada 8 horas', 27);
INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (30, 7, '400 mg cada 8 horas', 29);

-- ============================================================
-- FIN DE LOS DATOS DE PRUEBA
-- ============================================================