<?php
require_once __DIR__ . '/../includes/common.php';
require_role('MEDICO');

$u = user();
$busqueda = trim($_GET['buscar'] ?? '');
$atenciones = [];
$paciente_encontrado = null;

$stmt = $pdo->prepare("SELECT id_medico FROM medico WHERE email = ?");
$stmt->execute([$u['email']]);
$id_medico = (int)$stmt->fetchColumn();

if ($busqueda !== '') {
    $stmt = $pdo->prepare("
        SELECT DISTINCT pa.id_paciente, up.rut,
               CONCAT(up.nombre,' ',up.apellido) AS nombre_completo,
               up.fecha_nacimiento, p.nombre AS prevision
        FROM paciente pa
        JOIN usuario up ON up.email = pa.email
        JOIN prevision p ON p.id_prevision = pa.id_prevision
        JOIN cita c ON c.id_paciente = pa.id_paciente
        WHERE c.id_medico = ? AND (up.rut = ? OR CONCAT(up.nombre,' ',up.apellido) LIKE ?)
        LIMIT 1
    ");
    $stmt->execute([$id_medico, $busqueda, "%$busqueda%"]);
    $paciente_encontrado = $stmt->fetch();

    if ($paciente_encontrado) {
        $stmt = $pdo->prepare("
            SELECT a.id_atencion, a.motivo, a.observaciones, a.fecha_registro,
                   c.fecha_hora, e.nombre AS especialidad, cm.nombre AS centro
            FROM atencion a
            JOIN cita c ON c.id_cita = a.id_cita
            JOIN especialidad e ON e.id_especialidad = c.id_especialidad
            JOIN centro_medico cm ON cm.id_centro = c.id_centro
            WHERE c.id_medico = ? AND c.id_paciente = ?
            ORDER BY c.fecha_hora DESC
        ");
        $stmt->execute([$id_medico, $paciente_encontrado['id_paciente']]);
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
    }
}

header_html('Historial de pacientes');
?>

<h2 class="mb-4">Historial de pacientes</h2>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="buscar" class="form-control form-control-lg" 
                       placeholder="Buscar por RUT o nombre..." value="<?= e($busqueda) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-lg w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<?php if ($busqueda !== '' && !$paciente_encontrado): ?>
    <div class="alert alert-warning">No se encontró el paciente.</div>
<?php endif; ?>

<?php if ($paciente_encontrado): ?>
    <div class="card shadow mb-4 border-start border-4 border-info">
        <div class="card-body">
            <h5><?= e($paciente_encontrado['nombre_completo']) ?></h5>
            <p class="mb-0"><strong>RUT:</strong> <?= e($paciente_encontrado['rut']) ?> | 
            <strong>Previsión:</strong> <?= e($paciente_encontrado['prevision']) ?></p>
        </div>
    </div>

    <p class="text-muted"><?= count($atenciones) ?> atenciones encontradas</p>

    <?php foreach ($atenciones as $at): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <strong><?= date('d-m-Y H:i', strtotime($at['fecha_hora'])) ?></strong>
                <span class="badge bg-primary"><?= e($at['especialidad']) ?></span>
                <span class="badge bg-secondary"><?= e($at['centro']) ?></span>
            </div>
            <div class="card-body">
                <p><strong>Motivo:</strong> <?= e($at['motivo']) ?></p>
                <?php if ($at['observaciones']): ?>
                    <p><strong>Observaciones:</strong> <?= e($at['observaciones']) ?></p>
                <?php endif; ?>
                <p class="mb-1"><strong>Diagnósticos:</strong>
                    <?php foreach ($at['diagnosticos'] as $d): ?>
                        <span class="badge bg-dark"><?= e($d['codigo_cie10']) ?></span> <?= e($d['descripcion']) ?>;
                    <?php endforeach; ?>
                </p>
                <p class="mb-0"><strong>Recetas:</strong>
                    <?php foreach ($at['recetas'] as $r): ?>
                        <?= e($r['medicamento']) ?> (<?= e($r['dosis']) ?>, <?= e($r['dias_tratamiento']) ?>d);
                    <?php endforeach; ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php footer_html(); ?>