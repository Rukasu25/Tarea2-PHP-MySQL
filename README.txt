Nombre: Pablo Andres Rodriguez Gonzalez
ROL: 202430541-7
Nombre: Lukas Ignacio Labrin Salazar
ROL: 202473624-8

REPOSITORIO GITHUB:
Enlace:https://github.com/Rukasu25/Tarea2-PHP-MySQL/tree/main

ESTRUCTURA DEL ARCHIVO:
El archivo comprimido debe llamarse T2-202430541-7-202473624-8.zip
y contener las siguientes carpetas:

- PHP: contiene todos los archivos .php de la aplicacion
  - PHP/includes: contiene common.php con la conexion y funciones
  - PHP/paciente: modulos del rol paciente
  - PHP/medico: modulos del rol medico
  - PHP/admin: modulos del rol administrador
- PDF: contiene el documento con los pasos de normalizacion a 3FN
- BD: contiene el archivo script_bd.sql con la creacion de la base de datos
- CSS: contiene las hojas de estilo utilizadas
- README.md: este archivo

REQUISITOS PREVIOS:
- XAMPP instalado con Apache y MySQL activos
- PHP 7.4 o superior
- Navegador web actualizado
- Carpeta del proyecto ubicada en C:\xampp\htdocs\Saludusm

INICIAR SERVICIOS:
1. Abrir el Panel de Control de XAMPP
2. Iniciar el servicio Apache
3. Iniciar el servicio MySQL
4. Verificar que ambos servicios aparezcan en estado Running CREAR LA BASE DE DATOS
1. Abrir el navegador e ingresar a http://localhost/phpmyadmin
2. Hacer clic en la pestana SQL
3. Abrir el archivo BD/script_bd.sql
4. Copiar todo el contenido del archivo
5. Pegar el contenido en el area de texto de phpMyAdmin
6. Hacer clic en Continuar o Ejecutar


GENERAR LOS USUARIOS DE PRUEBA:
El sistema incluye un archivo llamado spawner.php ubicado en la
carpeta PHP. Este archivo genera automaticamente los usuarios de
prueba con la contrasena encriptada usando la funcion
password_hash de PHP.

No es necesario generar el hash manualmente ni modificar el
script SQL.

1. Verificar que el archivo spawner.php se encuentre en
   C:\xampp\htdocs\Saludusm\PHP

2. Abrir el navegador e ingresar a
   http://localhost/Saludusm/PHP/spawner.php

3. El archivo ejecutara las siguientes acciones:
   - Genera el hash de la contrasena usando password_hash
   - Inserta los usuarios administradores, medicos y pacientes
   - Inserta los registros correspondientes en las tablas
     medico y paciente
   - Asigna especialidades y centros a los medicos generados
   - Muestra en pantalla el listado de usuarios creados con su
     rol y correo electronico

4. Copiar o guardar el listado de usuarios generados que
   aparece en pantalla

5. Una vez finalizado el proceso, eliminar o renombrar el
   archivo spawner.php por seguridad



1. Abrir el navegador e ingresar a
   http://localhost/Saludusm/PHP/login.php
2. Ingresar con cualquiera de los usuarios generados en el Paso 3
3. La contrasena para todos los usuarios generados es
   Password123!
4. El sistema redirige automaticamente segun el rol del usuario

REQUISITOS DE CONTRASENA
Para el registro de nuevos pacientes desde el formulario, la
contrasena debe cumplir con los siguientes requisitos:
- Longitud minima de 6 caracteres
- Se almacena siempre encriptada con password_hash

VERIFICACION POR ROL

Rol Administrador:
- Acceder al panel de administracion desde el menu Panel
- Verificar que se muestren los totales de centros, medicos,
  pacientes y citas
- Verificar el listado de citas por centro con porcentaje de
  inasistencia
- Verificar el top 5 de diagnosticos mas frecuentes
- Probar el CRUD de centros en la seccion Centros
- Probar el CRUD de medicos en la seccion Medicos
- Probar la busqueda avanzada de citas en la seccion
  Buscar citas

Rol Medico:
- Acceder a la agenda desde el menu Agenda
- Seleccionar una fecha para ver las citas del dia
- Cambiar el estado de una cita a Confirmada, Atendida o
  No Asistio
- Registrar una atencion en una cita en estado Atendida
- Agregar diagnosticos y recetas a la atencion
- Consultar el historial de pacientes en la seccion
  Historial pacientes
- Editar los datos personales en la seccion Perfil

Rol Paciente:
- Buscar medicos por nombre o especialidad en la seccion Buscar
- Agendar una nueva hora seleccionando especialidad, centro,
  medico, fecha y hora
- Consultar las citas en la seccion Mis citas
- Cancelar o reprogramar una cita en estado Reservada o
  Confirmada
- Consultar el historial clinico en la seccion Historial
- Editar los datos personales en la seccion Perfil
- Eliminar la cuenta desde la seccion Perfil

USUARIOS DE PRUEBA:
Los usuarios se generan automaticamente al ejecutar spawner.php.
En pantalla se muestra el listado completo con el correo y el
rol de cada usuario.

La contrasena para todos los usuarios generados es:
Password123!

Como referencia, los correos siguen el siguiente formato:

- Administradores: nombre.apellidoNUMERO@saludusm.cl
- Medicos: nombre.apellidoNUMERO@saludusm.cl
- Pacientes: nombre.apellidoNUMERO@correo.cl

El numero es aleatorio y se genera al momento de ejecutar
spawner.php.

Se garantiza que al menos un medico atiende en mas de un centro
y posee mas de una especialidad.

SUPUESTOS:
- La duracion de cada bloque de atencion es de 30 minutos
- El horario de atencion es de lunes a viernes de 08:00 a
  17:30 horas
- No se agendan citas en fines de semana
- Las citas vencidas en estado Reservada o Confirmada se
  cancelan automaticamente al abrir el modulo Mis citas del
  paciente
- Al eliminar la cuenta de un paciente se eliminan en cascada
  sus citas, atenciones, diagnosticos y recetas asociadas
- Los medicos son creados unicamente por el administrador
- El registro publico desde el login esta disponible solo para
  pacientes
- La contrasena debe tener al menos 6 caracteres

