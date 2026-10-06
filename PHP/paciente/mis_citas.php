<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();

$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE email = ?");
$stmt->execute([$u['email']]);
$id_paciente = (int)$stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar'])) {
    $id_cita = (int)$_POST['id_cita'];
    $stmt = $pdo->prepare("
        SELECT c.fecha_hora, ec.nombre AS estado
        FROM cita c JOIN estado_cita ec ON ec.id_estado = c.id_estado
        WHERE c.id_cita = ? AND c.id_paciente = ?
    ");
    $stmt->execute([$id_cita, $id_paciente]);
    $cita = $stmt->fetch();

    if ($cita && in_array($cita['estado'], ['Reservada', 'Confirmada']) && strtotime($cita['fecha_hora']) > time()) {
        $stmt = $pdo->prepare("UPDATE cita SET id_estado = (SELECT id_estado FROM estado_cita WHERE nombre = 'Cancelada') WHERE id_cita = ?");
        $stmt->execute([$id_cita]);
        flash('Cita cancelada correctamente.');
    } else {
        flash('No se puede cancelar esta cita.');
    }
    redirect('mis_citas.php');
}

$stmt = $pdo->prepare("
    SELECT c.id_cita, c.fecha_hora, ec.nombre AS estado,
           CONCAT(um.nombre, ' ', um.apellido) AS medico,
           e.nombre AS especialidad,
           cm.nombre AS centro
    FROM cita c
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.email = m.email
    JOIN especialidad e ON e.id_especialidad = c.id_especialidad
    JOIN centro_medico cm ON cm.id_centro = c.id_centro
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    WHERE c.id_paciente = ?
    ORDER BY c.fecha_hora DESC
");
$stmt->execute([$id_paciente]);
$todas = $stmt->fetchAll();

$proximas = [];
$historicas = [];
$ahora = time();
foreach ($todas as $c) {
    if (strtotime($c['fecha_hora']) > $ahora && in_array($c['estado'], ['Reservada', 'Confirmada'])) {
        $proximas[] = $c;
    } else {
        $historicas[] = $c;
    }
}
usort($proximas, fn($a, $b) => strtotime($a['fecha_hora']) - strtotime($b['fecha_hora']));

header_html('Mis Citas');
?>

<h2 class="mb-4">Mis Citas</h2>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Próximas (<?= count($proximas) ?>)</h5></div>
    <div class="card-body p-0">
        <?php if (empty($proximas)): ?>
            <div class="alert alert-info m-3">No tienes citas próximas.</div>
        <?php else: ?>
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Fecha</th><th>Hora</th><th>Médico</th><th>Especialidad</th><th>Centro</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($proximas as $c): ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($c['fecha_hora'])) ?></td>
                            <td><?= date('H:i', strtotime($c['fecha_hora'])) ?></td>
                            <td><?= e($c['medico']) ?></td>
                            <td><?= e($c['especialidad']) ?></td>
                            <td><?= e($c['centro']) ?></td>
                            <td><span class="badge bg-secondary"><?= e($c['estado']) ?></span></td>
                            <td>
                                <a href="reprogramar.php?id=<?= $c['id_cita'] ?>" class="btn btn-sm btn-outline-primary">Reprogramar</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Cancelar?')">
                                    <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
                                    <button type="submit" name="cancelar" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-secondary text-white"><h5 class="mb-0">Historial (<?= count($historicas) ?>)</h5></div>
    <div class="card-body p-0">
        <?php if (empty($historicas)): ?>
            <div class="alert alert-info m-3">No hay citas en el historial.</div>
        <?php else: ?>
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Fecha</th><th>Hora</th><th>Médico</th><th>Especialidad</th><th>Centro</th><th>Estado</th></tr></thead>
                <tbody>
                    <?php foreach ($historicas as $c): ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($c['fecha_hora'])) ?></td>
                            <td><?= date('H:i', strtotime($c['fecha_hora'])) ?></td>
                            <td><?= e($c['medico']) ?></td>
                            <td><?= e($c['especialidad']) ?></td>
                            <td><?= e($c['centro']) ?></td>
                            <td><span class="badge bg-secondary"><?= e($c['estado']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php footer_html(); ?>