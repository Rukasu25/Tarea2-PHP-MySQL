<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$centros = $pdo->query("SELECT * FROM centro_medico ORDER BY nombre")->fetchAll();
$especialidades = $pdo->query("SELECT * FROM especialidad ORDER BY nombre")->fetchAll();
$medicos = $pdo->query("SELECT m.id_medico, CONCAT(u.nombre,' ',u.apellido) AS nombre FROM medico m JOIN usuario u ON u.email = m.email ORDER BY u.apellido")->fetchAll();
$estados = $pdo->query("SELECT * FROM estado_cita ORDER BY id_estado")->fetchAll();
$previsiones = $pdo->query("SELECT * FROM prevision ORDER BY nombre")->fetchAll();
$regiones = $pdo->query("SELECT DISTINCT region FROM centro_medico ORDER BY region")->fetchAll();

$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';
$id_centro = (int)($_GET['id_centro'] ?? 0);
$region = trim($_GET['region'] ?? '');
$id_esp = (int)($_GET['id_especialidad'] ?? 0);
$id_medico = (int)($_GET['id_medico'] ?? 0);
$id_estado = (int)($_GET['id_estado'] ?? 0);
$id_prev = (int)($_GET['id_prevision'] ?? 0);

$where = [];
$params = [];

if ($fecha_desde !== '') { $where[] = "DATE(c.fecha_hora) >= ?"; $params[] = $fecha_desde; }
if ($fecha_hasta !== '') { $where[] = "DATE(c.fecha_hora) <= ?"; $params[] = $fecha_hasta; }
if ($id_centro > 0)       { $where[] = "c.id_centro = ?"; $params[] = $id_centro; }
if ($region !== '')       { $where[] = "cm.region = ?"; $params[] = $region; }
if ($id_esp > 0)          { $where[] = "c.id_especialidad = ?"; $params[] = $id_esp; }
if ($id_medico > 0)       { $where[] = "c.id_medico = ?"; $params[] = $id_medico; }
if ($id_estado > 0)       { $where[] = "c.id_estado = ?"; $params[] = $id_estado; }
if ($id_prev > 0)         { $where[] = "pa.id_prevision = ?"; $params[] = $id_prev; }

$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$stmt = $pdo->prepare("
    SELECT c.id_cita, c.fecha_hora, ec.nombre AS estado,
           CONCAT(up.nombre,' ',up.apellido) AS paciente, up.rut AS rut_paciente,
           p.nombre AS prevision,
           CONCAT(um.nombre,' ',um.apellido) AS medico,
           e.nombre AS especialidad,
           cm.nombre AS centro, cm.comuna, cm.region
    FROM cita c
    JOIN paciente pa ON pa.id_paciente = c.id_paciente
    JOIN usuario up ON up.email = pa.email
    JOIN prevision p ON p.id_prevision = pa.id_prevision
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.email = m.email
    JOIN especialidad e ON e.id_especialidad = c.id_especialidad
    JOIN centro_medico cm ON cm.id_centro = c.id_centro
    JOIN estado_cita ec ON ec.id_estado = c.id_estado
    $where_sql
    ORDER BY c.fecha_hora DESC
    LIMIT 200
");
$stmt->execute($params);
$citas = $stmt->fetchAll();

header_html('Búsqueda de Citas');
?>

<h2 class="mb-4">Búsqueda avanzada de citas</h2>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Filtros</h5></div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3"><label class="form-label">Desde</label><input type="date" name="fecha_desde" class="form-control" value="<?= e($fecha_desde) ?>"></div>
            <div class="col-md-3"><label class="form-label">Hasta</label><input type="date" name="fecha_hasta" class="form-control" value="<?= e($fecha_hasta) ?>"></div>
            <div class="col-md-3"><label class="form-label">Región</label>
                <select name="region" class="form-select">
                    <option value="">-- Todas --</option>
                    <?php foreach ($regiones as $r): ?>
                        <option value="<?= e($r['region']) ?>" <?= $region === $r['region'] ? 'selected' : '' ?>><?= e($r['region']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Centro</label>
                <select name="id_centro" class="form-select">
                    <option value="0">-- Todos --</option>
                    <?php foreach ($centros as $c): ?>
                        <option value="<?= $c['id_centro'] ?>" <?= $id_centro == $c['id_centro'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Especialidad</label>
                <select name="id_especialidad" class="form-select">
                    <option value="0">-- Todas --</option>
                    <?php foreach ($especialidades as $e): ?>
                        <option value="<?= $e['id_especialidad'] ?>" <?= $id_esp == $e['id_especialidad'] ? 'selected' : '' ?>><?= e($e['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Médico</label>
                <select name="id_medico" class="form-select">
                    <option value="0">-- Todos --</option>
                    <?php foreach ($medicos as $m): ?>
                        <option value="<?= $m['id_medico'] ?>" <?= $id_medico == $m['id_medico'] ? 'selected' : '' ?>><?= e($m['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Estado</label>
                <select name="id_estado" class="form-select">
                    <option value="0">-- Todos --</option>
                    <?php foreach ($estados as $e): ?>
                        <option value="<?= $e['id_estado'] ?>" <?= $id_estado == $e['id_estado'] ? 'selected' : '' ?>><?= e($e['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Previsión</label>
                <select name="id_prevision" class="form-select">
                    <option value="0">-- Todas --</option>
                    <?php foreach ($previsiones as $p): ?>
                        <option value="<?= $p['id_prevision'] ?>" <?= $id_prev == $p['id_prevision'] ? 'selected' : '' ?>><?= e($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Buscar</button>
                <a href="buscar_citas.php" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">Resultados (<?= count($citas) ?>)</h5></div>
    <div class="card-body p-0">
        <?php if (empty($citas)): ?>
            <div class="alert alert-info m-3">No se encontraron citas.</div>
        <?php else: ?>
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light"><tr><th>Fecha</th><th>Hora</th><th>Paciente</th><th>Previsión</th><th>Médico</th><th>Especialidad</th><th>Centro</th><th>Estado</th></tr></thead>
                <tbody>
                    <?php foreach ($citas as $c): ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($c['fecha_hora'])) ?></td>
                            <td><?= date('H:i', strtotime($c['fecha_hora'])) ?></td>
                            <td><?= e($c['paciente']) ?><br><small><?= e($c['rut_paciente']) ?></small></td>
                            <td><span class="badge bg-info text-dark"><?= e($c['prevision']) ?></span></td>
                            <td><?= e($c['medico']) ?></td>
                            <td><span class="badge bg-primary"><?= e($c['especialidad']) ?></span></td>
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