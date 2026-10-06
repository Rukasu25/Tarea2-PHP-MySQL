<?php
require_once __DIR__ . '/../includes/common.php';
require_role('ADMIN');

$u = user();
$error = '';
$accion = $_POST['accion'] ?? '';
$id_editar = (int)($_GET['editar'] ?? 0);

// ============================================================
// CREAR / ACTUALIZAR
// ============================================================
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
                $stmt = $pdo->prepare("
                    INSERT INTO centro_medico (codigo, nombre, comuna, region)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$codigo, $nombre, $comuna, $region]);
                flash('Centro creado correctamente.');
            } else {
                $id = (int)$_POST['id_centro'];
                $stmt = $pdo->prepare("
                    UPDATE centro_medico SET codigo = ?, nombre = ?, comuna = ?, region = ?
                    WHERE id_centro = ?
                ");
                $stmt->execute([$codigo, $nombre, $comuna, $region, $id]);
                flash('Centro actualizado correctamente.');
            }
            redirect('centros.php');
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = 'El código del centro ya existe.';
            } else {
                $error = $e->getMessage();
            }
        }
    }
}

// ============================================================
// ELIMINAR
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'eliminar') {
    $id = (int)$_POST['id_centro'];
    try {
        // Verificar si tiene médicos o citas asociadas
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM medico_centro WHERE id_centro = ?");
        $stmt->execute([$id]);
        $tiene_medicos = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cita WHERE id_centro = ?");
        $stmt->execute([$id]);
        $tiene_citas = (int)$stmt->fetchColumn();

        if ($tiene_medicos > 0 || $tiene_citas > 0) {
            flash('No se puede eliminar: el centro tiene médicos o citas asociadas.', 'danger');
        } else {
            $stmt = $pdo->prepare("DELETE FROM centro_medico WHERE id_centro = ?");
            $stmt->execute([$id]);
            flash('Centro eliminado.');
        }
    } catch (Throwable $e) {
        flash('Error al eliminar: ' . $e->getMessage(), 'danger');
    }
    redirect('centros.php');
}

// ============================================================
// DATOS PARA EDITAR
// ============================================================
$centro_editar = null;
if ($id_editar > 0) {
    $stmt = $pdo->prepare("SELECT * FROM centro_medico WHERE id_centro = ?");
    $stmt->execute([$id_editar]);
    $centro_editar = $stmt->fetch();
}

// ============================================================
// LISTADO DE CENTROS
// ============================================================
$centros = $pdo->query("
    SELECT 
        cm.id_centro, cm.codigo, cm.nombre, cm.comuna, cm.region,
        (SELECT COUNT(*) FROM medico_centro WHERE id_centro = cm.id_centro) AS medicos,
        (SELECT COUNT(*) FROM cita WHERE id_centro = cm.id_centro) AS citas
    FROM centro_medico cm
    ORDER BY cm.region, cm.comuna, cm.nombre
")->fetchAll();

header_html('Gestión de Centros');
?>

<h2 class="mb-4"><i class="bi bi-building"></i> Gestión de Centros Médicos</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<div class="row g-3">

    <!-- Formulario -->
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-<?= $centro_editar ? 'pencil' : 'plus-circle' ?>"></i>
                    <?= $centro_editar ? 'Editar centro' : 'Nuevo centro' ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="accion" value="<?= $centro_editar ? 'actualizar' : 'crear' ?>">
                    <?php if ($centro_editar): ?>
                        <input type="hidden" name="id_centro" value="<?= $centro_editar['id_centro'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Código *</label>
                        <input type="text" name="codigo" class="form-control" required
                               value="<?= e($centro_editar['codigo'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= e($centro_editar['nombre'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comuna *</label>
                        <input type="text" name="comuna" class="form-control" required
                               value="<?= e($centro_editar['comuna'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Región *</label>
                        <input type="text" name="region" class="form-control" required
                               value="<?= e($centro_editar['region'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i>
                        <?= $centro_editar ? 'Guardar cambios' : 'Crear centro' ?>
                    </button>
                    <?php if ($centro_editar): ?>
                        <a href="centros.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Listado -->
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Centros registrados (<?= count($centros) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($centros)): ?>
                    <div class="alert alert-info m-3 mb-3">No hay centros registrados.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Comuna</th>
                                    <th>Región</th>
                                    <th class="text-center">Médicos</th>
                                    <th class="text-center">Citas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($centros as $c): ?>
                                    <tr>
                                        <td><code><?= e($c['codigo']) ?></code></td>
                                        <td><?= e($c['nombre']) ?></td>
                                        <td><?= e($c['comuna']) ?></td>
                                        <td><?= e($c['region']) ?></td>
                                        <td class="text-center"><?= $c['medicos'] ?></td>
                                        <td class="text-center"><?= $c['citas'] ?></td>
                                        <td>
                                            <a href="centros.php?editar=<?= $c['id_centro'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" style="display:inline;"
                                                  onsubmit="return confirm('¿Eliminar este centro?')">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <input type="hidden" name="id_centro" value="<?= $c['id_centro'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php footer_html(); ?>