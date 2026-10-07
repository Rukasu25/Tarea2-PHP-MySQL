<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$error = '';
$accion = $_POST['accion'] ?? '';
$id_editar = (int)($_GET['editar'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($accion, ['crear', 'actualizar'])) {
    $codigo = trim($_POST['codigo'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $comuna = trim($_POST['comuna'] ?? '');
    $region = trim($_POST['region'] ?? '');

    if (empty($codigo) || empty($nombre) || empty($comuna) || empty($region)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        try {
            if ($accion === 'crear') {
                $stmt = $pdo->prepare("INSERT INTO centro_medico (codigo, nombre, comuna, region) VALUES (?, ?, ?, ?)");
                $stmt->execute([$codigo, $nombre, $comuna, $region]);
                flash('Centro creado.');
            } else {
                $id = (int)$_POST['id_centro'];
                $stmt = $pdo->prepare("UPDATE centro_medico SET codigo=?, nombre=?, comuna=?, region=? WHERE id_centro=?");
                $stmt->execute([$codigo, $nombre, $comuna, $region, $id]);
                flash('Centro actualizado.');
            }
            redirect('centros.php');
        } catch (PDOException $e) {
            $error = $e->getCode() == 23000 ? 'El código ya existe.' : $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'eliminar') {
    $id = (int)$_POST['id_centro'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM medico_centro WHERE id_centro = ?");
    $stmt->execute([$id]);
    $tiene = (int)$stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cita WHERE id_centro = ?");
    $stmt->execute([$id]);
    $citas = (int)$stmt->fetchColumn();

    if ($tiene > 0 || $citas > 0) {
        flash('No se puede eliminar: tiene médicos o citas asociadas.');
    } else {
        $stmt = $pdo->prepare("DELETE FROM centro_medico WHERE id_centro = ?");
        $stmt->execute([$id]);
        flash('Centro eliminado.');
    }
    redirect('centros.php');
}

$centro_editar = null;
if ($id_editar > 0) {
    $stmt = $pdo->prepare("SELECT * FROM centro_medico WHERE id_centro = ?");
    $stmt->execute([$id_editar]);
    $centro_editar = $stmt->fetch();
}

$centros = $pdo->query("
    SELECT cm.*, 
        (SELECT COUNT(*) FROM medico_centro WHERE id_centro = cm.id_centro) AS medicos,
        (SELECT COUNT(*) FROM cita WHERE id_centro = cm.id_centro) AS citas
    FROM centro_medico cm ORDER BY cm.region, cm.comuna, cm.nombre
")->fetchAll();

header_html('Gestión de Centros');
?>

<h2 class="mb-4">Gestión de Centros</h2>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white"><h5 class="mb-0"><?= $centro_editar ? 'Editar' : 'Nuevo' ?> centro</h5></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="accion" value="<?= $centro_editar ? 'actualizar' : 'crear' ?>">
                    <?php if ($centro_editar): ?><input type="hidden" name="id_centro" value="<?= $centro_editar['id_centro'] ?>"><?php endif; ?>
                    <div class="mb-2"><label class="form-label">Código *</label><input name="codigo" class="form-control" required value="<?= e($centro_editar['codigo'] ?? '') ?>"></div>
                    <div class="mb-2"><label class="form-label">Nombre *</label><input name="nombre" class="form-control" required value="<?= e($centro_editar['nombre'] ?? '') ?>"></div>
                    <div class="mb-2"><label class="form-label">Comuna *</label><input name="comuna" class="form-control" required value="<?= e($centro_editar['comuna'] ?? '') ?>"></div>
                    <div class="mb-3"><label class="form-label">Región *</label><input name="region" class="form-control" required value="<?= e($centro_editar['region'] ?? '') ?>"></div>
                    <button class="btn btn-primary w-100">Guardar</button>
                    <?php if ($centro_editar): ?><a href="centros.php" class="btn btn-secondary w-100 mt-2">Cancelar</a><?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-dark text-white"><h5 class="mb-0">Centros (<?= count($centros) ?>)</h5></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Código</th><th>Nombre</th><th>Comuna</th><th>Médicos</th><th>Citas</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($centros as $c): ?>
                            <tr>
                                <td><code><?= e($c['codigo']) ?></code></td>
                                <td><?= e($c['nombre']) ?></td>
                                <td><?= e($c['comuna']) ?></td>
                                <td><?= $c['medicos'] ?></td>
                                <td><?= $c['citas'] ?></td>
                                <td>
                                    <a href="centros.php?editar=<?= $c['id_centro'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_centro" value="<?= $c['id_centro'] ?>">
                                        <button class="btn btn-sm btn-outline-danger">X</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php footer_html(); ?>