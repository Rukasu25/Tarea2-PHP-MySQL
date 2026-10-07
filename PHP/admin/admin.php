<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$resumen = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM centro_medico) AS total_centros,
        (SELECT COUNT(*) FROM medico) AS total_medicos,
        (SELECT COUNT(*) FROM paciente) AS total_pacientes,
        (SELECT COUNT(*) FROM cita) AS total_citas
")->fetch();

$centros = $pdo->query("
    SELECT c.nombre, c.comuna, c.region, COUNT(ci.id_cita) AS total,
           COALESCE(SUM(CASE WHEN e.nombre = 'No Asistio' THEN 1 ELSE 0 END), 0) AS no_asistio
    FROM centro_medico c
    LEFT JOIN cita ci ON ci.id_centro = c.id_centro
    LEFT JOIN estado_cita e ON e.id_estado = ci.id_estado
    GROUP BY c.id_centro, c.nombre, c.comuna, c.region
    ORDER BY c.nombre
")->fetchAll();

$diag = $pdo->query("
    SELECT d.codigo_cie10, d.descripcion, COUNT(da.id_atencion) AS total
    FROM diagnostico d
    LEFT JOIN diagnostico_atencion da ON da.id_diagnostico = d.id_diagnostico
    GROUP BY d.id_diagnostico, d.codigo_cie10, d.descripcion
    ORDER BY total DESC, d.codigo_cie10
    LIMIT 5
")->fetchAll();

header_html('Panel de Administración');
?>

<h2 class="mb-4">Panel de Gestión</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-building fs-2 text-primary"></i><h3><?= $resumen['total_centros'] ?></h3><small>Centros</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-person-badge fs-2 text-success"></i><h3><?= $resumen['total_medicos'] ?></h3><small>Médicos</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-people fs-2 text-info"></i><h3><?= $resumen['total_pacientes'] ?></h3><small>Pacientes</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-calendar-check fs-2 text-warning"></i><h3><?= $resumen['total_citas'] ?></h3><small>Citas</small></div></div></div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Citas por centro</h5></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Centro</th><th>Comuna</th><th>Región</th><th class="text-center">Total</th><th class="text-center">No asistió</th><th class="text-center">% Inasistencia</th></tr></thead>
            <tbody>
                <?php foreach ($centros as $c): ?>
                    <?php $pct = $c['total'] > 0 ? 100 * $c['no_asistio'] / $c['total'] : 0; ?>
                    <tr>
                        <td><?= e($c['nombre']) ?></td>
                        <td><?= e($c['comuna']) ?></td>
                        <td><small><?= e($c['region']) ?></small></td>
                        <td class="text-center"><?= $c['total'] ?></td>
                        <td class="text-center"><?= $c['no_asistio'] ?></td>
                        <td class="text-center"><span class="badge bg-<?= $pct > 15 ? 'danger' : ($pct > 10 ? 'warning' : 'success') ?>"><?= number_format($pct, 2) ?>%</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-danger text-white"><h5 class="mb-0">Top 5 diagnósticos</h5></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>#</th><th>Código</th><th>Descripción</th><th class="text-center">Atenciones</th></tr></thead>
            <tbody>
                <?php foreach ($diag as $i => $d): ?>
                    <tr>
                        <td><span class="badge bg-dark"><?= $i + 1 ?></span></td>
                        <td><code><?= e($d['codigo_cie10']) ?></code></td>
                        <td><?= e($d['descripcion']) ?></td>
                        <td class="text-center"><span class="badge bg-primary"><?= $d['total'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4"><a href="centros.php" class="btn btn-primary w-100 py-3">Centros</a></div>
    <div class="col-md-4"><a href="medicos.php" class="btn btn-success w-100 py-3">Médicos</a></div>
    <div class="col-md-4"><a href="buscar_citas.php" class="btn btn-info w-100 py-3 text-white">Buscar citas</a></div>
</div>

<?php footer_html(); ?>