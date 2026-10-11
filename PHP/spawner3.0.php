<?php
// PHP/spawner3.0.php
header('Content-Type: text/html; charset=utf-8');

// Configuración de conexión MySQLi para XAMPP
$host     = "localhost";
$usuario  = "root";
$password = "";
$bd       = "saludusm";

// Intentar incluir archivo de conexión si existe, o usar conexión directa
if (file_exists(__DIR__ . '/config/db.php')) {
    require_once __DIR__ . '/config/db.php';
} elseif (file_exists(__DIR__ . '/conexion.php')) {
    require_once __DIR__ . '/conexion.php';
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = @new mysqli($host, $usuario, $password, $bd);
}

if ($conn->connect_error) {
    die("<h3 style='color:red;'>Error de conexión a MySQL: " . $conn->connect_error . "</h3>");
}

$conn->set_charset("utf8mb4");
set_time_limit(180);

echo "<h2>Iniciando Poblamiento Adaptado al Esquema Real - SaludUSM</h2>";

// ---------------------------------------------------------
// 1. DESACTIVAR LLAVES FORÁNEAS Y RECREAR ESTRUCTURA LIMPIA
// ---------------------------------------------------------
$conn->query("SET FOREIGN_KEY_CHECKS = 0;");
$conn->query("DROP TRIGGER IF EXISTS trg_cita_validar;");

$tablasOperativas = [
    "receta_linea", "diagnostico_atencion", "atencion", "cita",
    "medico_centro", "medico_especialidad", "medico", "paciente", "usuario"
];

foreach ($tablasOperativas as $tabla) {
    $conn->query("TRUNCATE TABLE `$tabla`;");
}

echo "<p>✓ Tablas de usuarios, médicos, pacientes, citas y atenciones limpiadas.</p>";

// ---------------------------------------------------------
// 2. ASEGURAR DATOS EN CATÁLOGOS BASE
// ---------------------------------------------------------
$conn->query("INSERT IGNORE INTO prevision (id_prevision, nombre) VALUES (1, 'Fonasa'), (2, 'Isapre'), (3, 'Particular'), (4, 'Otra');");
$conn->query("INSERT IGNORE INTO estado_cita (id_estado, nombre) VALUES (1, 'Reservada'), (2, 'Confirmada'), (3, 'Atendida'), (4, 'No Asistio'), (5, 'Cancelada');");

// ---------------------------------------------------------
// 3. FUNCIONES AUXILIARES
// ---------------------------------------------------------
function calcularDV($rutNum) {
    $s = 1;
    $m = 0;
    while ($rutNum > 0) {
        $s = ($s + $rutNum % 10 * (9 - $m++ % 6)) % 11;
        $rutNum = (int)($rutNum / 10);
    }
    return $s ? (string)($s - 1) : 'K';
}

function generarRut($baseNum) {
    return $baseNum . '-' . calcularDV($baseNum);
}

// ---------------------------------------------------------
// 4. GENERAR HASHES Y USUARIOS
// ---------------------------------------------------------
$passAdminHash = password_hash("admin123", PASSWORD_BCRYPT);
$passMedicoHash = password_hash("medico123", PASSWORD_BCRYPT);
$passPacienteHash = password_hash("paciente123", PASSWORD_BCRYPT);

$nombres = ["Carlos", "María", "Juan", "Ana", "Pedro", "Sofía", "Diego", "Valentina", "Camila", "Lucas", "Javier", "Fernanda"];
$apellidos = ["Pérez", "González", "Tapia", "Rojas", "Soto", "Contreras", "Silva", "Sepúlveda", "Morales", "Araya"];

// A) ADMINISTRADOR
$rutAdmin = generarRut(11111111);
$stmtUser = $conn->prepare("INSERT INTO usuario (email, rut, nombre, apellido, telefono, fecha_nacimiento, sexo, password_hash, rol, activo) VALUES (?, ?, 'Admin', 'SaludUSM', '912345678', '1985-05-15', 'M', ?, 'ADMIN', 1)");
$emailAdmin = 'admin@saludusm.cl';
$stmtUser->bind_param("sss", $emailAdmin, $rutAdmin, $passAdminHash);
$stmtUser->execute();

// B) MÉRITOS Y REGISTRO DE MÉDICOS (10 Médicos)
$medicosData = [];
$stmtUserMed = $conn->prepare("INSERT INTO usuario (email, rut, nombre, apellido, telefono, fecha_nacimiento, sexo, password_hash, rol, activo) VALUES (?, ?, ?, ?, '987654321', '1980-08-20', 'F', ?, 'MEDICO', 1)");
$stmtMed = $conn->prepare("INSERT INTO medico (email) VALUES (?)");
$stmtMedEsp = $conn->prepare("INSERT INTO medico_especialidad (id_medico, id_especialidad) VALUES (?, ?)");
$stmtMedCen = $conn->prepare("INSERT INTO medico_centro (id_medico, id_centro) VALUES (?, ?)");

// Obtener IDs de especialidades y centros creados
$resEsp = $conn->query("SELECT id_especialidad FROM especialidad");
$especialidadesIDs = [];
while ($row = $resEsp->fetch_assoc()) $especialidadesIDs[] = $row['id_especialidad'];

$resCen = $conn->query("SELECT id_centro FROM centro_medico");
$centrosIDs = [];
while ($row = $resCen->fetch_assoc()) $centrosIDs[] = $row['id_centro'];

for ($i = 1; $i <= 10; $i++) {
    $rut = generarRut(15000000 + $i);
    $nom = $nombres[array_rand($nombres)];
    $ape = $apellidos[array_rand($apellidos)];
    $email = "medico{$i}@saludusm.cl";

    $stmtUserMed->bind_param("sssss", $email, $rut, $nom, $ape, $passMedicoHash);
    $stmtUserMed->execute();

    $stmtMed->bind_param("s", $email);
    $stmtMed->execute();
    $idMedico = $stmtMed->insert_id;

    // Asignar entre 1 y 3 especialidades
    $espKeys = (array)array_rand($especialidadesIDs, mt_rand(1, min(3, count($especialidadesIDs))));
    $medEspSel = [];
    foreach ($espKeys as $k) {
        $idEsp = $especialidadesIDs[$k];
        $stmtMedEsp->bind_param("ii", $idMedico, $idEsp);
        $stmtMedEsp->execute();
        $medEspSel[] = $idEsp;
    }

    // Asignar entre 1 y 2 centros
    $cenKeys = (array)array_rand($centrosIDs, mt_rand(1, min(2, count($centrosIDs))));
    $medCenSel = [];
    foreach ($cenKeys as $k) {
        $idCentro = $centrosIDs[$k];
        $stmtMedCen->bind_param("ii", $idMedico, $idCentro);
        $stmtMedCen->execute();
        $medCenSel[] = $idCentro;
    }

    $medicosData[] = [
        'id_medico' => $idMedico,
        'email' => $email,
        'especialidades' => $medEspSel,
        'centros' => $medCenSel
    ];
}

// C) PACIENTES (20 Pacientes)
$pacientesData = [];
$stmtUserPac = $conn->prepare("INSERT INTO usuario (email, rut, nombre, apellido, telefono, fecha_nacimiento, sexo, password_hash, rol, activo) VALUES (?, ?, ?, ?, '911223344', '1995-03-10', 'X', ?, 'PACIENTE', 1)");
$stmtPac = $conn->prepare("INSERT INTO paciente (email, id_prevision) VALUES (?, ?)");

$resPrev = $conn->query("SELECT id_prevision FROM prevision");
$previsionesIDs = [];
while ($row = $resPrev->fetch_assoc()) $previsionesIDs[] = $row['id_prevision'];

for ($i = 1; $i <= 20; $i++) {
    $rut = generarRut(20000000 + $i);
    $nom = $nombres[array_rand($nombres)];
    $ape = $apellidos[array_rand($apellidos)];
    $email = "paciente{$i}@gmail.com";
    $idPrevision = $previsionesIDs[array_rand($previsionesIDs)];

    $stmtUserPac->bind_param("sssss", $email, $rut, $nom, $ape, $passPacienteHash);
    $stmtUserPac->execute();

    $stmtPac->bind_param("si", $email, $idPrevision);
    $stmtPac->execute();
    $pacientesData[] = $stmtPac->insert_id;
}

echo "<p>✓ Usuarios creados e insertados en BD (1 Admin, 10 Médicos, 20 Pacientes).</p>";

// ---------------------------------------------------------
// 5. CITAS, ATENCIONES, DIAGNÓSTICOS Y RECETAS
// ---------------------------------------------------------
$resDiag = $conn->query("SELECT id_diagnostico FROM diagnostico");
$diagnosticosIDs = [];
while ($row = $resDiag->fetch_assoc()) $diagnosticosIDs[] = $row['id_diagnostico'];

$resMed = $conn->query("SELECT id_medicamento FROM medicamento");
$medicamentosIDs = [];
while ($row = $resMed->fetch_assoc()) $medicamentosIDs[] = $row['id_medicamento'];

$stmtCita = $conn->prepare("INSERT IGNORE INTO cita (id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES (?, ?, ?, ?, ?, ?)");
$stmtAtencion = $conn->prepare("INSERT INTO atencion (id_cita, motivo, observaciones) VALUES (?, 'Consulta médica por control/síntomas', 'Atención realizada ambulatoriamente conforme a protocolo.')");
$stmtDiagAtencion = $conn->prepare("INSERT INTO diagnostico_atencion (id_atencion, id_diagnostico) VALUES (?, ?)");
$stmtReceta = $conn->prepare("INSERT INTO receta_linea (id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES (?, ?, ?, ?)");

// Citas Pasadas
for ($d = 1; $d <= 15; $d++) {
    $fechaBase = date('Y-m-d', strtotime("-$d days"));
    for ($k = 0; $k < 2; $k++) {
        $hora = sprintf("%02d:00:00", mt_rand(8, 17));
        $fechaHora = "$fechaBase $hora";
        
        $idPac = $pacientesData[array_rand($pacientesData)];
        $medElegido = $medicosData[array_rand($medicosData)];
        
        $idMed = $medElegido['id_medico'];
        $idEsp = $medElegido['especialidades'][array_rand($medElegido['especialidades'])];
        $idCen = $medElegido['centros'][array_rand($medElegido['centros'])];
        
        $randEstado = mt_rand(1, 100);
        if ($randEstado <= 55) {
            $idEstado = 3; // Atendida
        } elseif ($randEstado <= 75) {
            $idEstado = 4; // No Asistio
        } else {
            $idEstado = mt_rand(1, 2); // Reservada / Confirmada pasadas para probar SP
        }

        $stmtCita->bind_param("iiiisi", $idPac, $idMed, $idEsp, $idCen, $fechaHora, $idEstado);
        if ($stmtCita->execute()) {
            $idCita = $stmtCita->insert_id;
            
            if ($idEstado == 3 && $idCita > 0) {
                $stmtAtencion->bind_param("i", $idCita);
                if ($stmtAtencion->execute()) {
                    $idAtencion = $stmtAtencion->insert_id;
                    
                    if (!empty($diagnosticosIDs)) {
                        $idDiag = $diagnosticosIDs[array_rand($diagnosticosIDs)];
                        $stmtDiagAtencion->bind_param("ii", $idAtencion, $idDiag);
                        $stmtDiagAtencion->execute();
                    }
                    
                    if (!empty($medicamentosIDs)) {
                        $idMedication = $medicamentosIDs[array_rand($medicamentosIDs)];
                        $dosis = "1 comprimido cada 8 horas";
                        $dias = mt_rand(3, 7);
                        $stmtReceta->bind_param("iisi", $idAtencion, $idMedication, $dosis, $dias);
                        $stmtReceta->execute();
                    }
                }
            }
        }
    }
}

// Citas Futuras
for ($d = 1; $d <= 10; $d++) {
    $fechaBase = date('Y-m-d', strtotime("+$d days"));
    for ($k = 0; $k < 2; $k++) {
        $hora = sprintf("%02d:00:00", mt_rand(8, 17));
        $fechaHora = "$fechaBase $hora";
        
        $idPac = $pacientesData[array_rand($pacientesData)];
        $medElegido = $medicosData[array_rand($medicosData)];
        
        $idMed = $medElegido['id_medico'];
        $idEsp = $medElegido['especialidades'][array_rand($medElegido['especialidades'])];
        $idCen = $medElegido['centros'][array_rand($medElegido['centros'])];
        $idEstado = mt_rand(1, 2);

        $stmtCita->bind_param("iiiisi", $idPac, $idMed, $idEsp, $idCen, $fechaHora, $idEstado);
        $stmtCita->execute();
    }
}

echo "<p>✓ Citas pasadas, futuras, atenciones y recetas creadas correctamente.</p>";

// ---------------------------------------------------------
// 6. RECONSTRUIR TRIGGER Y REACTIVAR LLAVES FORÁNEAS
// ---------------------------------------------------------
$conn->query("SET FOREIGN_KEY_CHECKS = 1;");

$sqlTrigger = "
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
END;";

$conn->query($sqlTrigger);

echo "<h3 style='color:green;'>¡Poblamiento completado con éxito y perfectamente alineado a la base de datos!</h3>";
echo "<hr>";
echo "<h4>Cuentas de prueba creadas:</h4>";
echo "<ul>";
echo "<li><strong>Administrador:</strong> <code>admin@saludusm.cl</code> / Clave: <code>admin123</code></li>";
echo "<li><strong>Médico:</strong> <code>medico1@saludusm.cl</code> (al 10) / Clave: <code>medico123</code></li>";
echo "<li><strong>Paciente:</strong> <code>paciente1@gmail.com</code> (al 20) / Clave: <code>paciente123</code></li>";
echo "</ul>";
?>