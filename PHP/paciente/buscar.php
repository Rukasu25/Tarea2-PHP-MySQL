<?php
require_once __DIR__ . '/../includes/common.php';
require_login();

$q = trim($_GET['q'] ?? '');
$rows = [];

if ($q !== '') {
    $like = "%$q%";
    $stmt = $pdo->prepare("
        SELECT m.id_medico, u.email,
               CONCAT(u.nombre, ' ', u.apellido) AS medico,
               GROUP_CONCAT(DISTINCT e.nombre ORDER BY e.nombre SEPARATOR ', ') AS especialidades,
               GROUP_CONCAT(DISTINCT CONCAT(c.nombre, ' (', c.comuna, ')') ORDER BY c.nombre SEPARATOR ' | ') AS centros
        FROM medico m
        JOIN usuario u ON u.email = m.email
        LEFT JOIN medico_especialidad me ON me.id_medico = m.id_medico
        LEFT JOIN especialidad e ON e.id_especialidad = me.id_especialidad
        LEFT JOIN medico_centro mc ON mc.id_medico = m.id_medico
        LEFT JOIN centro_medico c ON c.id_centro = mc.id_centro
        WHERE u.nombre LIKE ? OR u.apellido LIKE ? OR CONCAT(u.nombre, ' ', u.apellido) LIKE ? OR e.nombre LIKE ?
        GROUP BY m.id_medico, u.email, u.nombre, u.apellido
        ORDER BY u.apellido, u.nombre
    ");
    $stmt->execute([$like, $like, $like, $like]);
    $rows = $stmt->fetchAll();
}

header_html('Buscar médicos');
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white"><h4 class="mb-0">Buscar médicos</h4></div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-10"><input type="text" name="q" class="form-control form-control-lg" placeholder="Nombre o especialidad..." value="<?= e($q) ?>"></div>
            <div class="col-md-2"><button class="btn btn-primary btn-lg w-100">Buscar</button></div>
        </form>

        <?php if ($q === ''): ?>
            <div class="alert alert-info">Escriba el nombre o especialidad.</div>
        <?php elseif (empty($rows)): ?>
            <div class="alert alert-warning">No se encontraron médicos.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead class="table-dark"><tr><th>Médico</th><th>Especialidades</th><th>Centros</th><th>Email</th></tr></thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><strong><?= e($r['medico']) ?></strong></td>
                            <td><?= e($r['especialidades'] ?: 'Sin especialidades') ?></td>
                            <td><small><?= e($r['centros'] ?: 'Sin centros') ?></small></td>
                            <td><small><?= e($r['email']) ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php footer_html(); ?>