<?php
require_once __DIR__ . '/../includes/common.php';
require_role('PACIENTE');

$u = user();

$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE email = ?");
$stmt->execute([$u['email']]);
$id_paciente = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT a.id_atencion, a.motivo, a.observaciones, a.fecha_registro,
           c.fecha_hora,
           CONCAT(um.nombre,' ',um.apellido) AS medico,
           e.nombre AS especialidad,
           cm.nombre AS centro
    FROM atencion a
    JOIN cita c ON c.id_cita = a.id_cita
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.email = m.email
    JOIN especialidad e ON e.id_especialidad = c.id_especialidad
    JOIN centro_medico cm ON cm.id_centro = c.id_centro
    WHERE c.id_paciente = ?
    ORDER BY c.fecha_hora DESC
");
$stmt->execute([$id_paciente]);
$atenciones = $stmt->fetchAll();

foreach ($atenciones as &$at) {
    $d = $pdo->prepare("
        SELECT d.codigo_cie10, d.descripcion
        FROM diagnostico_atencion da
        JOIN diagnostico d ON d.id_diagnostico = da.id_diagnostico
        WHERE da.id_atencion = ?
    ");
    $d->execute([$at['id_atencion']]);
    $at['diagnosticos'] = $d->fetchAll();

    $r = $pdo->prepare("
        SELECT m.nombre AS medicamento, rl.dosis, rl.dias_tratamiento
        FROM receta_linea rl
        JOIN medicamento m ON m.id_medicamento = rl.id_medicamento
        WHERE rl.id_atencion = ?
    ");
    $r->execute([$at['id_atencion']]);
    $at['recetas'] = $r->fetchAll();
}
unset($at);

header_html('Mi Historial');
?>

<h2 class="mb-4">Mi Historial Clínico</h2>

<div class="alert alert-info">Registro de solo lectura.</div>

<?php if (empty($atenciones)): ?>
    <div class="alert alert-secondary">No tienes atenciones registradas.</div>
<?php else: ?>
    <?php foreach ($atenciones as $at): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <strong><?= date('d-m-Y H:i', strtotime($at['fecha_hora'])) ?></strong>
                <span class="badge bg-primary"><?= e($at['especialidad']) ?></span>
                <span class="badge bg-secondary"><?= e($at['centro']) ?></span>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Médico:</strong> <?= e($at['medico']) ?></p>
                <p class="mb-2"><strong>Motivo:</strong> <?= e($at['motivo']) ?></p>
                <?php if ($at['observaciones']): ?>
                    <p class="mb-2"><strong>Observaciones:</strong> <?= e($at['observaciones']) ?></p>
                <?php endif; ?>

                <div class="mb-2">
                    <strong>Diagnósticos:</strong>
                    <?php if (empty($at['diagnosticos'])): ?>
                        <span class="text-muted">Sin diagnósticos</span>
                    <?php else: ?>
                        <ul class="mb-0 mt-1">
                            <?php foreach ($at['diagnosticos'] as $d): ?>
                                <li><span class="badge bg-dark"><?= e($d['codigo_cie10']) ?></span> <?= e($d['descripcion']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <div>
                    <strong>Recetas:</strong>
                    <?php if (empty($at['recetas'])): ?>
                        <span class="text-muted">Sin recetas</span>
                    <?php else: ?>
                        <table class="table table-sm table-bordered mt-2 mb-0">
                            <thead><tr><th>Medicamento</th><th>Dosis</th><th>Días</th></tr></thead>
                            <tbody>
                                <?php foreach ($at['recetas'] as $r): ?>
                                    <tr>
                                        <td><?= e($r['medicamento']) ?></td>
                                        <td><?= e($r['dosis']) ?></td>
                                        <td><?= e($r['dias_tratamiento']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php footer_html(); ?>