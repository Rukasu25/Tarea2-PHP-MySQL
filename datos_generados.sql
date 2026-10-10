USE saludusm;


-- ========== ADMINS ==========
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('jorge.gonzalez787@saludusm.cl','14953615-2','Jorge','Gonzalez','939048307','1988-05-10','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','ADMIN');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ricardo.munoz347@saludusm.cl','20576334-6','Ricardo','Munoz','937171783','1980-03-28','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','ADMIN');

-- ========== MEDICOS ==========
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('diego.munoz624@saludusm.cl','8042425-5','Diego','Munoz','984197650','1982-11-21','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('carlos.munoz826@saludusm.cl','10585032-3','Carlos','Munoz','967394728','1991-08-09','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ricardo.perez509@saludusm.cl','15119115-6','Ricardo','Perez','959602562','1962-03-29','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ana.diaz648@saludusm.cl','16400269-3','Ana','Diaz','981796805','1989-01-21','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('maria.munoz703@saludusm.cl','16791559-1','Maria','Munoz','946144861','1983-09-08','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ana.diaz690@saludusm.cl','5486533-1','Ana','Diaz','940945627','1965-07-03','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('camila.soto319@saludusm.cl','6826138-3','Camila','Soto','957907308','1962-05-06','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('valentina.rojas524@saludusm.cl','17049987-4','Valentina','Rojas','945105531','1993-01-13','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','MEDICO');

-- ========== PACIENTES ==========
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('pedro.rojas708@correo.cl','18967131-7','Pedro','Rojas','954547400','1975-12-14','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('pedro.diaz448@correo.cl','15830407-K','Pedro','Diaz','917121228','2000-04-14','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('jorge.munoz782@correo.cl','12826855-2','Jorge','Munoz','981985325','1978-09-19','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('diego.diaz164@correo.cl','16378854-9','Diego','Diaz','943523070','1971-06-08','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('juan.perez502@correo.cl','19360449-6','Juan','Perez','966103902','1972-07-26','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('valentina.diaz678@correo.cl','17277291-2','Valentina','Diaz','969352544','1968-05-13','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('camila.soto751@correo.cl','17634061-3','Camila','Soto','920482851','1975-11-27','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('sofia.munoz823@correo.cl','24977135-7','Sofia','Munoz','966392603','1962-08-12','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('carlos.silva782@correo.cl','22123298-K','Carlos','Silva','953005814','1969-04-23','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('valentina.soto20@correo.cl','7484879-4','Valentina','Soto','991258198','1977-09-09','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('sofia.perez650@correo.cl','23150656-8','Sofia','Perez','951470847','1982-01-28','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('valentina.munoz912@correo.cl','19990898-6','Valentina','Munoz','963486284','1997-02-17','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('valentina.munoz638@correo.cl','6121518-1','Valentina','Munoz','980274162','1986-11-08','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ana.diaz218@correo.cl','8484719-0','Ana','Diaz','930130811','1965-11-25','F','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');
INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES ('ricardo.munoz340@correo.cl','13216985-5','Ricardo','Munoz','914100120','1989-03-18','M','$2y$10$BNtUPfdYVGPBRLPJ6BcfzeOBdyB2OyPCpr.TNAQ0Y4UQEEahXobt.','PACIENTE');

-- ========== REGISTROS EN TABLA MEDICO ==========
INSERT INTO medico(email) VALUES ('diego.munoz624@saludusm.cl');
INSERT INTO medico(email) VALUES ('carlos.munoz826@saludusm.cl');
INSERT INTO medico(email) VALUES ('ricardo.perez509@saludusm.cl');
INSERT INTO medico(email) VALUES ('ana.diaz648@saludusm.cl');
INSERT INTO medico(email) VALUES ('maria.munoz703@saludusm.cl');
INSERT INTO medico(email) VALUES ('ana.diaz690@saludusm.cl');
INSERT INTO medico(email) VALUES ('camila.soto319@saludusm.cl');
INSERT INTO medico(email) VALUES ('valentina.rojas524@saludusm.cl');

-- ========== REGISTROS EN TABLA PACIENTE ==========
INSERT INTO paciente(email, id_prevision) VALUES ('pedro.rojas708@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('pedro.diaz448@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('jorge.munoz782@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('diego.diaz164@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('juan.perez502@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('valentina.diaz678@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('camila.soto751@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('sofia.munoz823@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('carlos.silva782@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('valentina.soto20@correo.cl', 1);
INSERT INTO paciente(email, id_prevision) VALUES ('sofia.perez650@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('valentina.munoz912@correo.cl', 3);
INSERT INTO paciente(email, id_prevision) VALUES ('valentina.munoz638@correo.cl', 4);
INSERT INTO paciente(email, id_prevision) VALUES ('ana.diaz218@correo.cl', 2);
INSERT INTO paciente(email, id_prevision) VALUES ('ricardo.munoz340@correo.cl', 4);