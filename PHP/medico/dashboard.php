<?php
require_once __DIR__ . '/../includes/common.php';
require_role('MEDICO');

$u = user();

$stmt = $pdo->prepare("SELECT id_medico FROM medico WHERE email = ?");
$stmt->execute([$u['email']]);
$id_medico = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT c.id_cita, c.fecha_hora, ec.nombre AS estado,
           CONCAT(up.nombre,' ',up.apellido) AS paciente,
           ce.nombre AS centro
    FROM cita c
    JOIN paciente pa ON pa.id_paciente = c.id_paciente
    JOIN usuario up ON up.email = pa.email
    JOIN centro_medico ce ON ce.id_centro = c.id_centro
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    WHERE c.id_medico = ? AND DATE(c.fecha_hora) = CURDATE()
    ORDER BY c.fecha_hora
");
$stmt->execute([$id_medico]);
$citas_hoy = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN ec.nombre = 'Reservada' THEN 1 ELSE 0 END) AS reservadas,
        SUM(CASE WHEN ec.nombre = 'Atendida' THEN 1 ELSE 0 END) AS atendidas,
        SUM(CASE WHEN ec.nombre = 'No Asistio' THEN 1 ELSE 0 END) AS no_asistio,
        COUNT(*) AS total
    FROM cita c
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    WHERE c.id_medico = ? AND DATE(c.fecha_hora) = CURDATE()
");
$stmt->execute([$id_medico]);
$resumen = $stmt->fetch();

header_html('Inicio - Médico');
?>

<div class="card shadow mb-4 border-start border-4 border-primary">
    <div class="card-body">
        <h2 class="mb-1">Bienvenido/a, Dr/a. <?= e($u['nombre'] . ' ' . $u['apellido']) ?></h2>
        <p class="text-muted mb-0">Rol: <strong>Médico</strong></p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-calendar-check fs-2 text-primary"></i><h3><?= (int)$resumen['total'] ?></h3><small>Citas hoy</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-hourglass fs-2 text-warning"></i><h3><?= (int)$resumen['reservadas'] ?></h3><small>Reservadas</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-check-circle fs-2 text-success"></i><h3><?= (int)$resumen['atendidas'] ?></h3><small>Atendidas</small></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><i class="bi bi-x-circle fs-2 text-danger"></i><h3><?= (int)$resumen['no_asistio'] ?></h3><small>No asistió</small></div></div></div>
</div>

<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h5 class="mb-0">Mis citas de hoy</h5>
        <a href="agenda.php" class="btn btn-light btn-sm">Ver agenda completa</a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($citas_hoy)): ?>
            <div class="alert alert-info m-3 mb-3">No tienes citas hoy.</div>
        <?php else: ?>
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Hora</th><th>Paciente</th><th>Centro</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($citas_hoy as $c): ?>
                        <tr>
                            <td><strong><?= date('H:i', strtotime($c['fecha_hora'])) ?></strong></td>
                            <td><?= e($c['paciente']) ?></td>
                            <td><?= e($c['centro']) ?></td>
                            <td><span class="badge bg-secondary"><?= e($c['estado']) ?></span></td>
                            <td>
                                <?php if ($c['estado'] === 'Atendida'): ?>
                                    <a href="atencion.php?id=<?= $c['id_cita'] ?>" class="btn btn-sm btn-success">Atención</a>
                                <?php else: ?>
                                    <a href="agenda.php" class="btn btn-sm btn-outline-primary">Gestionar</a>
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