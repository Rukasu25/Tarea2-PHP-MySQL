<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$u = user();

// ============================================================
// 1. RESUMEN GENERAL
// ============================================================
$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM centro_medico) AS total_centros,
        (SELECT COUNT(*) FROM medico) AS total_medicos,
        (SELECT COUNT(*) FROM paciente) AS total_pacientes,
        (SELECT COUNT(*) FROM cita) AS total_citas,
        (SELECT COUNT(*) FROM atencion) AS total_atenciones,
        (SELECT COUNT(*) FROM especialidad) AS total_especialidades
");
$resumen = $stmt->fetch();

// ============================================================
// 2. CITAS POR CENTRO CON PORCENTAJE DE INASISTENCIA
// ============================================================
$stmt = $pdo->query("
    SELECT 
        cm.id_centro,
        cm.nombre AS centro,
        cm.comuna,
        cm.region,
        COUNT(c.id_cita) AS total_citas,
        SUM(CASE WHEN ec.nombre = 'No Asistio' THEN 1 ELSE 0 END) AS inasistencias,
        ROUND(
            (SUM(CASE WHEN ec.nombre = 'No Asistio' THEN 1 ELSE 0 END) / COUNT(c.id_cita)) * 100, 
            2
        ) AS porcentaje_inasistencia
    FROM centro_medico cm
    LEFT JOIN cita c ON c.id_centro = cm.id_centro
    LEFT JOIN estado_cita ec ON ec.id_estado = c.id_estado
    GROUP BY cm.id_centro, cm.nombre, cm.comuna, cm.region
    ORDER BY porcentaje_inasistencia DESC
");
$citas_por_centro = $stmt->fetchAll();

// ============================================================
// 3. TOP 5 DIAGNÓSTICOS MÁS FRECUENTES DE LA RED
// ============================================================
$stmt = $pdo->query("
    SELECT 
        d.codigo_cie10,
        d.descripcion,
        COUNT(*) AS total
    FROM diagnostico_atencion da
    JOIN diagnostico d ON d.id_diagnostico = da.id_diagnostico
    GROUP BY d.id_diagnostico, d.codigo_cie10, d.descripcion
    ORDER BY total DESC
    LIMIT 5
");
$top_diagnosticos = $stmt->fetchAll();

// ============================================================
// 4. CITAS POR ESTADO (GENERAL)
// ============================================================
$stmt = $pdo->query("
    SELECT ec.nombre AS estado, COUNT(*) AS total
    FROM cita c
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    GROUP BY ec.nombre
    ORDER BY total DESC
");
$citas_por_estado = $stmt->fetchAll();

header_html('Panel de Administración');
?>

<h2 class="mb-4"><i class="bi bi-speedometer2"></i> Panel de Administración</h2>

<!-- ============================================================
     TARJETAS RESUMEN
     ============================================================ -->
<div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-building fs-2 text-primary"></i>
                <h3 class="mt-1 mb-0"><?= $resumen['total_centros'] ?></h3>
                <small class="text-muted">Centros</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-person-badge fs-2 text-success"></i>
                <h3 class="mt-1 mb-0"><?= $resumen['total_medicos'] ?></h3>
                <small class="text-muted">Médicos</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-people fs-2 text-info"></i>
                <h3 class="mt-1 mb-0"><?= $resumen['total_pacientes'] ?></h3>
                <small class="text-muted">Pacientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-calendar-check fs-2 text-warning"></i>
                <h3 class="mt-1 mb-0"><?= $resumen['total_citas'] ?></h3>
                <small class="text-muted">Citas</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-clipboard-pulse fs-2 text-danger"></i>
                <h3 class="mt-1 mb-0"><?= $resumen['total_atenciones'] ?></h3>
                <small class="text-muted">Atenciones</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-clipboard2-pulse fs-2 text-secondary"></i>
                <h3 class="mt-1 mb-0"><?= $resumen['total_especialidades'] ?></h3>
                <small class="text-muted">Especialidades</small>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     CITAS POR CENTRO CON INASISTENCIA
     ============================================================ -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Citas por centro e inasistencia</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Centro</th>
                    <th>Comuna</th>
                    <th>Región</th>
                    <th class="text-center">Total citas</th>
                    <th class="text-center">Inasistencias</th>
                    <th class="text-center">% Inasistencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas_por_centro as $c): ?>
                    <tr>
                        <td><strong><?= e($c['centro']) ?></strong></td>
                        <td><?= e($c['comuna']) ?></td>
                        <td><?= e($c['region']) ?></td>
                        <td class="text-center"><?= $c['total_citas'] ?></td>
                        <td class="text-center"><?= $c['inasistencias'] ?></td>
                        <td class="text-center">
                            <?php if ($c['total_citas'] == 0): ?>
                                <span class="text-muted">-</span>
                            <?php else: ?>
                                <?php
                                $pct = (float)$c['porcentaje_inasistencia'];
                                $color = $pct > 15 ? 'danger' : ($pct > 10 ? 'warning' : 'success');
                                ?>
                                <span class="badge bg-<?= $color ?>">
                                    <?= $pct ?>%
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================================
     TOP 5 DIAGNÓSTICOS Y CITAS POR ESTADO
     ============================================================ -->
<div class="row g-3">

    <!-- Top 5 diagnósticos -->
    <div class="col-md-7">
        <div class="card shadow h-100">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Top 5 diagnósticos de la red</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($top_diagnosticos)): ?>
                    <div class="alert alert-info m-3 mb-3">
                        No hay diagnósticos registrados aún.
                    </div>
                <?php else: ?>
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código CIE-10</th>
                                <th>Descripción</th>
                                <th class="text-center">Atenciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_diagnosticos as $i => $d): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark">#<?= $i + 1 ?></span>
                                        <code><?= e($d['codigo_cie10']) ?></code>
                                    </td>
                                    <td><?= e($d['descripcion']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $d['total'] ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Citas por estado -->
    <div class="col-md-5">
        <div class="card shadow h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Citas por estado</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($citas_por_estado)): ?>
                    <div class="alert alert-info m-3 mb-3">
                        No hay citas registradas aún.
                    </div>
                <?php else: ?>
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Estado</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citas_por_estado as $c): ?>
                                <tr>
                                    <td>
                                        <?php
                                        $color = match($c['estado']) {
                                            'Reservada'  => 'secondary',
                                            'Confirmada' => 'primary',
                                            'Atendida'   => 'success',
                                            'No Asistio' => 'warning',
                                            'Cancelada'  => 'danger',
                                            default      => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $color ?>"><?= e($c['estado']) ?></span>
                                    </td>
                                    <td class="text-center"><?= $c['total'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- ============================================================
     ACCESOS RÁPIDOS
     ============================================================ -->
<div class="row g-3 mt-3">
    <div class="col-md-4">
        <a href="centros.php" class="btn btn-primary w-100 py-3">
            <i class="bi bi-building fs-3 d-block mb-2"></i>
            Gestionar Centros
        </a>
    </div>
    <div class="col-md-4">
        <a href="medicos.php" class="btn btn-success w-100 py-3">
            <i class="bi bi-person-badge fs-3 d-block mb-2"></i>
            Gestionar Médicos
        </a>
    </div>
    <div class="col-md-4">
        <a href="buscar_citas.php" class="btn btn-info w-100 py-3 text-white">
            <i class="bi bi-search fs-3 d-block mb-2"></i>
            Búsqueda avanzada de citas
        </a>
    </div>
</div>

<?php footer_html(); ?>