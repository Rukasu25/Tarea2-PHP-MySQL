<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();
$error = '';

// Obtener id_paciente
$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE email = ?");
$stmt->execute([$u['email']]);
$paciente = $stmt->fetch();
$id_paciente = (int)$paciente['id_paciente'];

// Datos para los selects
$especialidades = $pdo->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();
$centros = $pdo->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();

// Filtros seleccionados (vienen por GET)
$sel_esp = (int)($_GET['id_especialidad'] ?? 0);
$sel_centro = (int)($_GET['id_centro'] ?? 0);

// Cargar médicos según filtros
$medicos = [];
if ($sel_esp > 0 && $sel_centro > 0) {
    $stmt = $pdo->prepare("
        SELECT m.id_medico, CONCAT(u.nombre, ' ', u.apellido) AS nombre
        FROM medico m
        JOIN usuario u ON u.email = m.email
        JOIN medico_especialidad me ON me.id_medico = m.id_medico
        JOIN medico_centro mc ON mc.id_medico = m.id_medico
        WHERE me.id_especialidad = ? AND mc.id_centro = ?
        ORDER BY u.apellido, u.nombre
    ");
    $stmt->execute([$sel_esp, $sel_centro]);
    $medicos = $stmt->fetchAll();
}

// Procesar reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_medico = (int)($_POST['id_medico'] ?? 0);
    $id_especialidad = (int)($_POST['id_especialidad'] ?? 0);
    $id_centro = (int)($_POST['id_centro'] ?? 0);
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    if ($id_medico <= 0 || $id_especialidad <= 0 || $id_centro <= 0) {
        $error = 'Debe completar todos los campos.';
    } elseif (empty($fecha) || empty($hora)) {
        $error = 'Debe seleccionar fecha y hora.';
    } else {
        $fecha_hora = "$fecha $hora:00";
        $dia_semana = date('N', strtotime($fecha));

        if (strtotime($fecha_hora) <= time()) {
            $error = 'La cita debe ser en una fecha futura.';
        }

        elseif ($hora < '08:00' || $hora > '17:30') {
            $error = 'El horario de atención es de 08:00 a 17:30.';
        }
        else {
            // Validar especialidad del médico
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM medico_especialidad WHERE id_medico = ? AND id_especialidad = ?");
            $stmt->execute([$id_medico, $id_especialidad]);
            if ($stmt->fetchColumn() == 0) {
                $error = 'El médico no posee esa especialidad.';
            } else {
                // Validar centro del médico
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM medico_centro WHERE id_medico = ? AND id_centro = ?");
                $stmt->execute([$id_medico, $id_centro]);
                if ($stmt->fetchColumn() == 0) {
                    $error = 'El médico no atiende en ese centro.';
                } else {
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO cita (id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado)
                            VALUES (?, ?, ?, ?, ?, (SELECT id_estado FROM estado_cita WHERE nombre='Reservada'))
                        ");
                        $stmt->execute([$id_paciente, $id_medico, $id_especialidad, $id_centro, $fecha_hora]);
                        flash('¡Cita agendada correctamente!');
                        redirect('mis_citas.php');
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $error = 'Ya existe una cita en ese horario.';
                        } else {
                            $error = $e->getMessage();
                        }
                    }
                }
            }
        }
    }
}
header_html('Agendar hora');
?>

<?php footer_html(); ?>