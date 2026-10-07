<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();

$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE email = ?");
$stmt->execute([$u['email']]);
$id_paciente = (int)$stmt->fetchColumn();

$error = '';
$especialidades = $pdo->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();
$centros = $pdo->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();

$sel_esp = (int)($_GET['id_especialidad'] ?? 0);
$sel_centro = (int)($_GET['id_centro'] ?? 0);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_medico = (int)$_POST['id_medico'];
    $id_especialidad = (int)$_POST['id_especialidad'];
    $id_centro = (int)$_POST['id_centro'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $fecha_hora = "$fecha $hora:00";
    $dia_semana = date('N', strtotime($fecha));

    if (strtotime($fecha_hora) <= time()) {
        $error = 'La cita debe ser en una fecha futura.';
    } elseif ($dia_semana > 5) {
        $error = 'Solo se pueden agendar citas de lunes a viernes.';
    } elseif ($hora < '08:00' || $hora > '17:30') {
        $error = 'El horario es de 08:00 a 17:30.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO cita (id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado)
                VALUES (?, ?, ?, ?, ?, (SELECT id_estado FROM estado_cita WHERE nombre = 'Reservada'))
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

header_html('Agendar hora');
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white"><h4 class="mb-0">Agendar hora</h4></div>
    <div class="card-body">

        <div class="alert alert-info">
            <strong>Horario:</strong> Lunes a viernes, 08:00 a 17:30 hrs. Bloques de 30 min.
        </div>

        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <label class="form-label">Especialidad</label>
                <select name="id_especialidad" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($especialidades as $x): ?>
                        <option value="<?= $x['id_especialidad'] ?>" <?= $sel_esp == $x['id_especialidad'] ? 'selected' : '' ?>>
                            <?= e($x['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Centro médico</label>
                <select name="id_centro" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($centros as $x): ?>
                        <option value="<?= $x['id_centro'] ?>" <?= $sel_centro == $x['id_centro'] ? 'selected' : '' ?>>
                            <?= e($x['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
            </div>
        </form>

        <?php if ($sel_esp > 0 && $sel_centro > 0): ?>
            <?php if (empty($medicos)): ?>
                <div class="alert alert-warning">No hay médicos disponibles.</div>
            <?php else: ?>
                <form method="POST" class="row g-3">
                    <input type="hidden" name="id_especialidad" value="<?= $sel_esp ?>">
                    <input type="hidden" name="id_centro" value="<?= $sel_centro ?>">

                    <div class="col-md-4">
                        <label class="form-label">Médico *</label>
                        <select name="id_medico" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            <?php foreach ($medicos as $m): ?>
                                <option value="<?= $m['id_medico'] ?>"><?= e($m['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha *</label>
                        <input type="date" name="fecha" class="form-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hora *</label>
                        <select name="hora" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            <?php
                            for ($h = 8; $h <= 17; $h++) {
                                foreach (['00', '30'] as $m) {
                                    $hora = sprintf('%02d:%s', $h, $m);
                                    if ($hora > '17:30') continue;
                                    echo "<option value=\"$hora\">$hora</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Reservar</button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php footer_html(); ?>