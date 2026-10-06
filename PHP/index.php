<?php
require_once __DIR__ . '/includes/common.php';

$q = trim($_GET['q'] ?? '');
$rows = [];

if ($q !== '') {
    $like = "%$q%";
    $stmt = $pdo->prepare("
        SELECT 
            m.id_medico,
            u.email,
            CONCAT(u.nombre, ' ', u.apellido) AS medico,
            GROUP_CONCAT(DISTINCT e.nombre ORDER BY e.nombre SEPARATOR ', ') AS especialidades,
            GROUP_CONCAT(DISTINCT CONCAT(c.nombre, ' (', c.comuna, ')') ORDER BY c.nombre SEPARATOR ' | ') AS centros
        FROM medico m
        JOIN usuario u ON u.email = m.email
        LEFT JOIN medico_especialidad me ON me.id_medico = m.id_medico
        LEFT JOIN especialidad e ON e.id_especialidad = me.id_especialidad
        LEFT JOIN medico_centro mc ON mc.id_medico = m.id_medico
        LEFT JOIN centro_medico c ON c.id_centro = mc.id_centro
        WHERE u.nombre LIKE ? OR u.apellido LIKE ? 
           OR CONCAT(u.nombre, ' ', u.apellido) LIKE ? OR e.nombre LIKE ?
        GROUP BY m.id_medico, u.email, u.nombre, u.apellido
        ORDER BY u.apellido, u.nombre
    ");
    $stmt->execute([$like, $like, $like, $like]);
    $rows = $stmt->fetchAll();
}

header_html('Inicio');
?>

<div class="text-center mb-4">
    <h1><i class="bi bi-hospital"></i> Bienvenido a SaludUSM</h1>
    <p class="lead">Sistema de Gestión Clínica</p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-search"></i> Buscar Médicos</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control form-control-lg" 
                       placeholder="Buscar por nombre o especialidad..." 
                       value="<?= e($q) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<?php if ($q !== ''): ?>
    <?php if (empty($rows)): ?>
        <div class="alert alert-warning">No se encontraron médicos.</div>
    <?php else: ?>
        <h4>Resultados (<?= count($rows) ?>)</h4>
        <div class="row g-3">
            <?php foreach ($rows as $r): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5><?= e($r['medico']) ?></h5>
                            <p class="mb-1"><strong>Especialidades:</strong> <?= e($r['especialidades'] ?: 'Sin especialidades') ?></p>
                            <p class="mb-1"><strong>Centros:</strong> <?= e($r['centros'] ?: 'Sin centros') ?></p>
                            <p class="mb-0"><small class="text-muted"><?= e($r['email']) ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php footer_html(); ?>