<?php
require_once __DIR__ . '/../includes/common.php';
require_role('MEDICO');

$u = user();
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$error = '';

$stmt = $pdo->prepare("SELECT id_medico FROM medico WHERE email = ?");
$stmt->execute([$u['email']]);
$id_medico = (int)$stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cita'], $_POST['estado'])) {
    $id_cita = (int)$_POST['id_cita'];
    $nuevo_estado = $_POST['estado'];
    $permitidos = ['Confirmada', 'Atendida', 'No Asistio'];

    if (in_array($nuevo_estado, $permitidos, true)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE cita SET id_estado = (SELECT id_estado FROM estado_cita WHERE nombre = ?)
                WHERE id_cita = ? AND id_medico = ?
            ");
            $stmt->execute([$nuevo_estado, $id_cita, $id_medico]);
            flash('Estado actualizado.');
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
    redirect('agenda.php?fecha=' . urlencode($fecha));
}

$stmt = $pdo->prepare("
    SELECT c.id_cita, c.fecha_hora, ec.nombre AS estado,
           CONCAT(up.nombre,' ',up.apellido) AS paciente,
           up.rut AS rut_paciente,
           p.nombre AS prevision,
           ce.nombre AS centro,
           e.nombre AS especialidad
    FROM cita c
    JOIN paciente pa ON pa.id_paciente = c.id_paciente
    JOIN usuario up ON up.email = pa.email
    JOIN prevision p ON p.id_prevision = pa.id_prevision
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.email = m.email
    JOIN centro_medico ce ON ce.id_centro = c.id_centro
    JOIN especialidad e ON e.id_especialidad = c.id_especialidad
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    WHERE c.id_medico = ? AND DATE(c.fecha_hora) = ?
    ORDER BY c.fecha_hora
");
$stmt->execute([$id_medico, $fecha]);
$citas = $stmt->fetchAll();

header_html('Mi Agenda');
?>

<h2 class="mb-4">Mi Agenda</h2>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" value="<?= e($fecha) ?>" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-md-8 d-flex align-items-end">
                <a href="agenda.php?fecha=<?= date('Y-m-d') ?>" class="btn btn-outline-primary me-2">Hoy</a>
                <a href="agenda.php?fecha=<?= date('Y-m-d', strtotime('+1 day')) ?>" class="btn btn-outline-secondary">Mañana</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Citas del <?= date('d-m-Y', strtotime($fecha)) ?> (<?= count($citas) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($citas)): ?>
            <div class="alert alert-info m-3 mb-3">No hay citas para este día.</div>
        <?php else: ?>
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Hora</th><th>Paciente</th><th>Previsión</th><th>Centro</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($citas as $c): ?>
                        <tr>
                            <td><strong><?= date('H:i', strtotime($c['fecha_hora'])) ?></strong></td>
                            <td><?= e($c['paciente']) ?><br><small class="text-muted"><?= e($c['rut_paciente']) ?></small></td>
                            <td><?= e($c['prevision']) ?></td>
                            <td><?= e($c['centro']) ?></td>
                            <td><span class="badge bg-secondary"><?= e($c['estado']) ?></span></td>
                            <td>
                                <?php if (in_array($c['estado'], ['Reservada', 'Confirmada', 'No Asistio'])): ?>
                                    <form method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
                                        <select name="estado" class="form-select form-select-sm">
                                            <?php foreach (['Confirmada', 'Atendida', 'No Asistio'] as $est): ?>
                                                <option value="<?= $est ?>" <?= $c['estado'] === $est ? 'selected' : '' ?>><?= $est ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-sm btn-primary">OK</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($c['estado'] === 'Atendida'): ?>
                                    <a href="atencion.php?id=<?= $c['id_cita'] ?>" class="btn btn-sm btn-success mt-1">Atención</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php footer_html(); ?>