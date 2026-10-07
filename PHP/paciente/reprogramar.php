<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();
$error = '';

$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE email = ?");
$stmt->execute([$u['email']]);
$id_paciente = (int)$stmt->fetchColumn();

$id_cita = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT c.id_cita, c.fecha_hora, ec.nombre AS estado,
           CONCAT(um.nombre,' ',um.apellido) AS medico,
           e.nombre AS especialidad,
           cm.nombre AS centro
    FROM cita c
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.email = m.email
    JOIN especialidad e ON e.id_especialidad = c.id_especialidad
    JOIN centro_medico cm ON cm.id_centro = c.id_centro
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    WHERE c.id_cita = ? AND c.id_paciente = ?
");
$stmt->execute([$id_cita, $id_paciente]);
$cita = $stmt->fetch();

if (!$cita) {
    flash('Cita no encontrada.');
    redirect('mis_citas.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $fecha_hora = "$fecha $hora:00";
    $dia_semana = date('N', strtotime($fecha));

    if (strtotime($fecha_hora) <= time()) {
        $error = 'La fecha debe ser futura.';
    } elseif ($dia_semana > 5) {
        $error = 'Solo lunes a viernes.';
    } elseif ($hora < '08:00' || $hora > '17:30') {
        $error = 'Horario 08:00-17:30.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE cita SET fecha_hora = ? WHERE id_cita = ? AND id_paciente = ?");
            $stmt->execute([$fecha_hora, $id_cita, $id_paciente]);
            flash('Cita reprogramada.');
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

header_html('Reprogramar');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white"><h4 class="mb-0">Reprogramar cita</h4></div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Actual:</strong> <?= date('d-m-Y H:i', strtotime($cita['fecha_hora'])) ?><br>
                    <?= e($cita['medico']) ?> - <?= e($cita['especialidad']) ?><br>
                    <?= e($cita['centro']) ?>
                </div>

                <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nueva fecha *</label>
                        <input type="date" name="fecha" class="form-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nueva hora *</label>
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
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="mis_citas.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>